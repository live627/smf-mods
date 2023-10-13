<?php

/**
 * @package   WordPress Bridge
 * @version   2.0.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2017, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

class WordpressBridge
{
	private static bool $bypassRegisterHook = false;

	/**
	 * Add the user to SMF
	 */
	public static function load_theme()
	{
		global $context, $modSettings, $smcFunc;

		if (empty($modSettings['wordpress_enabled']))
			return;

		$context['disable_login_hashing'] = true;

		if ($context['current_action'] == 'login2' && isset($_POST['user']))
		{
			$request = $smcFunc['db_query']('', '
				SELECT id_member
				FROM {db_prefix}members
				WHERE member_name = {string:user} OR email_address = {string:user}
				LIMIT 1',
				[
					'user' => $_POST['user'],
				]
			);
			if ($smcFunc['db_num_rows']($request))
				return;

			require_once $modSettings['wordpress_path'] . '/wp-config.php';
			require_once ABSPATH . WPINC . '/pluggable.php';
			require_once ABSPATH . WPINC . '/user.php';
			$roleMaps = !empty($modSettings['wordpress_role_maps']) ? json_decode(
				$modSettings['wordpress_role_maps']
			) : ['smf' => [], 'wp' => []];
			$user = get_user_by('login', $_POST['user']);
			if (!$user->ID)
				$user = get_user_by('email', $_POST['user']);

			if ($user->ID && wp_check_password($_POST['passwrd'], $user->data->user_pass, $user->ID))
			{
				$role = current($user->roles);
				$regOptions = [
					'interface' => 'wordpress_bridge',
					'auth_method' => 'password',
					'username' => $user->data->user_login,
					'email' => $user->data->user_email,
					'require' => 'nothing',
					'password' => $_POST['passwrd'],
					'password_check' => $_POST['passwrd'],
					'check_password_strength' => false,
					'check_email_ban' => false,
					'extra_register_vars' => [
						'id_group' => !empty($roleMaps['wp'][$role]) ? $roleMaps['wp'][$role] : 0,
						'real_name' => !empty($user->data->display_name) ? $user->data->display_name : $user->data->user_login,
						'date_registered' => strtotime($user->data->user_registered),
					],
				];

				require_once __DIR__ . '/Subs-Members.php';
				self::$bypassRegisterHook = true;
				registerMember($regOptions, true);
			}
		}
	}

	/**
	 * Adds the WordPress menu options to SMF's admin panel
	 *
	 * @param array &$admin_areas Admin areas from SMF
	 */
	public static function admin_areas(array &$admin_areas)
	{
		global $txt, $modSettings;

		// We insert it after Features and Options
		$counter = 0;
		foreach ($admin_areas['config']['areas'] as $area => $dummy)
			if (++$counter && $area == 'featuresettings')
				break;

		$admin_areas['config']['areas'] = array_merge(
			array_slice($admin_areas['config']['areas'], 0, $counter, true),
			[
				'wordpress' => [
					'label' => $txt['wordpress_bridge'],
					'function' => function ()
					{
						loadLanguage('WordpressBridge');
						require_once __DIR__ . '/ManageWordpressBridge.php';
						new ManageWordpressBridge;
					},
					'icon' => 'administration.gif',
					'subsections' => [
						'bridge' => [$txt['wordpress_bridge_settings']],
						'roles' => [$txt['wordpress_roles'], 'enabled' => !empty($modSettings['wordpress_path'])],
					],
				],
			],
			array_slice($admin_areas['config']['areas'], $counter, null, true)
		);
	}

	/**
	 * Logs a user into WordPress by setting cookies
	 *
	 * @param string $member_name
	 */
	public static function login(string $member_name)
	{
		global $modSettings, $smcFunc, $user_settings;

		if (empty($modSettings['wordpress_enabled']))
			return;

		require_once $modSettings['wordpress_path'] . '/wp-config.php';
		require_once ABSPATH . WPINC . '/pluggable.php';
		require_once ABSPATH . WPINC . '/user.php';
		$roleMaps = !empty($modSettings['wordpress_role_maps']) ? json_decode(
			$modSettings['wordpress_role_maps']
		) : ['smf' => [], 'wp' => []];
		$user = new WP_User($member_name);
		if (!$user->ID)
		{
			$new_user = new WP_User;
			$new_user->data->user_login = $user_settings['member_name'];
			$new_user->data->user_email = $user_settings['email_address'];
			$new_user->data->user_pass = $_POST['passwrd'];
			$request = $smcFunc['db_query']('', '
				SELECT date_registered
				FROM {db_prefix}members
				WHERE member_name = {string:user}
				LIMIT 1',
				[
					'user' => $member_name,
				]
			);
			[$date_registered] = $smcFunc['db_fetch_row']($request);
			$smcFunc['db_free_result']($request);
			$new_user->data->user_registered = gmdate("Y-m-d H:i:s", $date_registered);
			$new_user->data->display_name = $user_settings['real_name'];

			if (isset($roleMaps['smf'][$user_settings['id_group']]))
				$new_user->data->role = $roleMaps['smf'][$user_settings['id_group']];

			$user_id = wp_insert_user($new_user->data);
		}
		$user = new WP_User($member_name);
		if ($user && wp_check_password($_POST['passwrd'], $user->data->user_pass, $user->ID))
		{
			wp_set_current_user($user->ID, $user->user_login);
			wp_set_auth_cookie($user->ID);
			do_action('wp_login', $user->user_login);
			// Stupid haxorz to make SMF behave
			$_SESSION['login_url'] = home_url();
		}
	}

	/**
	 * Deletes the WordPress cookies
	 */
	public static function logout()
	{
		global $modSettings;

		if (empty($modSettings['wordpress_enabled']))
			return;

		require_once $modSettings['wordpress_path'] . '/wp-config.php';
		require_once ABSPATH . WPINC . '/pluggable.php';
		require_once ABSPATH . WPINC . '/user.php';

		wp_logout();
	}

	/**
	 * Takes the registration data from SMF, creates a new user in WordPress
	 * and populates its data and saves.
	 *
	 * @param array &$regOptions Array of Registration data)
	 */
	public static function register(array &$regOptions)
	{
		global $modSettings;

		if (empty($modSettings['wordpress_enabled']) || self::$bypassRegisterHook)
			return;

		require_once $modSettings['wordpress_path'] . '/wp-config.php';
		require_once ABSPATH . WPINC . '/pluggable.php';
		require_once ABSPATH . WPINC . '/user.php';
		$roleMaps = !empty($modSettings['wordpress_role_maps']) ? json_decode(
			$modSettings['wordpress_role_maps']
		) : ['smf' => [], 'wp' => []];
		$new_user = new WP_User;
		$new_user->data->user_login = $regOptions['register_vars']['member_name'];
		$new_user->data->user_email = $regOptions['register_vars']['email_address'];
		$new_user->data->user_pass = $regOptions['password'];
		if (isset($roleMaps['smf'][$regOptions['register_vars']['id_group']]))
			$new_user->data->role = $roleMaps['smf'][$regOptions['register_vars']['id_group']];

		$user_id = wp_insert_user($new_user->data);
	}

	/**
	 * Called when a user resets their password in SMF.  It will properly hash
	 * it into a WordPress compatible version and modify the user in WordPress.
	 *
	 * @param string $member_name
	 * @param string $member_name2
	 * @param string $password Plaintext password to reset to
	 */
	public static function reset_pass(string $member_name, string $member_name2, string $password)
	{
		global $context, $modSettings;

		if (empty($modSettings['wordpress_enabled']))
			return;

		require_once $modSettings['wordpress_path'] . '/wp-config.php';
		require_once ABSPATH . WPINC . '/pluggable.php';
		require_once ABSPATH . WPINC . '/user.php';

		$user = new WP_User($member_name);
		if ($user->ID)
		{
			wp_set_password($password, $user->ID);

			if ($context['user']['is_owner'])
			{
				wp_set_current_user($user->ID, $user->user_login);
				wp_set_auth_cookie($user->ID);
				do_action('wp_login', $user->user_login);
			}
		}
	}

	/**
	 * Updates a users' WordPress information when they change in SMF
	 *
	 * @param array  $member_names A list of all the members to change
	 * @param string $var          Variable that is being updated in SMF
	 * @param mixed  $data         Data being updated in SMF
	 */
	public static function change_member_data(array $member_names, string $var, $data)
	{
		if (empty($modSettings['wordpress_enabled']))
			return;

		// SMF var => Wordpress user var
		$integrateVars = [
			'member_name' => 'user_login',
			'real_name' => 'display_name',
			'email_address' => 'user_email',
			'id_group' => 'role',
			'website_url' => 'user_url',
		];

		if (!isset($integrateVars[$var]))
			return;

		require_once $modSettings['wordpress_path'] . '/wp-config.php';
		require_once ABSPATH . WPINC . '/pluggable.php';
		require_once ABSPATH . WPINC . '/user.php';

		$roleMaps = !empty($modSettings['wordpress_role_maps']) ? json_decode(
			$modSettings['wordpress_role_maps']
		) : ['smf' => [], 'wp' => []];

		foreach ($member_names as $member_name)
		{
			$user = new WP_User($member_name);
			if (!$user->ID)
				continue;

			// if this is a member_name, we have to update the nicename too
			if ($var === 'member_name')
				$user->data->user_nicename = $data;

			if ($var === 'id_group' && isset($roleMaps['smf'][$data]))
				$user->data->role = $roleMaps['smf'][$data];
			else
				$user->data->{$integrateVars[$var]} = $data;

			$user_id = wp_insert_user($user->data);
		}
	}
}
