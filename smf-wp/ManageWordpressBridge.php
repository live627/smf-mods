<?php

/**
 * @package   Wordpress Bridge
 * @version   2.0.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2017, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

class ManageWordpressBridge
{
	private $plugin_path;

	/**
	 * Base admin callback function
	 */
	public function __construct()
	{
		global $txt, $context;

		$context['page_title'] = $txt['wordpress_bridge'];
		loadTemplate('WordpressBridge');
		$context[$context['admin_menu_name']]['tab_data'] = [
			'title' => $txt['wordpress_bridge'],
			'description' => '',
			'tabs' => [
				'bridge' => [
					'description' => $txt['wordpress_settings_desc'],
				],
				'roles' => [
					'description' => $txt['wordpress_roles_desc'],
				],
			],
		];

		require_once __DIR__ . '/ManageSettings.php';
		$context['sub_template'] = 'show_settings';

		$sub_action = 'ModifyBridgeSettings';
		if (isset($_GET['sa']))
			switch ($_GET['sa'])
			{
				case 'roles':
					$sub_action = 'ManageRoles';
					break;
			}
		call_user_func([$this, $sub_action]);
	}

	/**
	 * General Settings page for bridge in SMF
	 */
	public function ModifyBridgeSettings()
	{
		global $scripturl, $txt, $context, $boarddir, $modSettings;

		$config_vars = [
			['check', 'wordpress_enabled'],
			['text', 'wordpress_path', 'size' => 50, 'subtext' => $txt['wordpress_path_desc']],
			'',
			['var_message', 'wordpress_version', 'var_message' => $txt['wordpress_cannot_connect']],
		];

		// Saving?
		if (isset($_GET['save']))
		{
			checkSession();

			if (isset($_POST['activate']))
			{
				require_once $modSettings['wordpress_path'] . '/wp-config.php';
				$this->plugin_path = 'smf-wp-auth.php';
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
				$result = activate_plugin($this->plugin_path);
				$context['settings_insert_above'] = sprintf(
					'<div class="errorbox">' .  . '</div>',
					$result instanceof WP_Error
						? sprintf(
							'%s<ul><li>%s</li></ul>',
							$txt['wordpress_problems'],
							implode('</li><li>', $result->get_error_messages())
						)
						: $txt['wordpress_activated'];
				);
			}
			else
			{
				if (!empty($_POST['wordpress_path']))
				{
					if (basename($_POST['wordpress_path']) === 'wp-config.php')
						$_POST['wordpress_path'] = dirname($_POST['wordpress_path']);
					elseif (is_dir($_POST['wordpress_path']))
						$_POST['wordpress_path'] = realpath($_POST['wordpress_path']);
					}
				if (empty($_POST['wordpress_path']) || !file_exists($_POST['wordpress_path'] . '/wp-config.php'))
					unset($_POST['wordpress_enabled']);

				saveDBSettings($config_vars);
			}
			redirectexit('action=admin;area=wordpress;sa=bridge');
		}

		if (!empty($modSettings['wordpress_path']) && !file_exists($modSettings['wordpress_path'] . '/wp-config.php'))
			$config_vars[1]['subtext'] .= ' ' . $txt['wordpress_path_desc_extra2'];
		elseif (empty($modSettings['wordpress_path']) && ($modSettings['wordpress_path'] = $this->findWordpressPath(dirname($boarddir))) != '')
			$config_vars[1]['subtext'] .= ' ' . $txt['wordpress_path_desc_extra'];
		else
		{
			$config_vars = array_merge(
				$config_vars,
				[
					'',
					['callback', 'wordpress_edit_files'],
				]
			);
			require_once $modSettings['wordpress_path'] . '/wp-config.php';
			$this->plugin_path = 'smf-wp-auth.php';
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
			$config_vars[3]['var_message'] = $wp_version;
			if (is_plugin_inactive($this->plugin_path))
			{
				$context['settings_insert_above'] = '<div class="errorbox">' . $txt['wordpress_inactive'] . '</div>';
				$config_vars[1]['postinput'] = sprintf(
					'<br><input name="activate" value="%s" type="submit">',
					$txt['wordpress_activate_plugin']
				);
			}
			elseif (!get_option('smf_path'))
			{
				update_option('smf_path', $boarddir);
				$context['settings_insert_above'] = '<div class="successbox">' . $txt['wordpress_activated'] . '</div>';
			}
			else
				$context['settings_insert_above'] = '<div class="infobox">' . $txt['wordpress_active'] . '</div>';
		}
		$context['post_url'] = $scripturl . '?action=admin;area=wordpress;sa=bridge;save';

		prepareDBSettingContext($config_vars);
	}

	/**
	 * Called in SMF admin panel for managing role maps
	 */
	public function ManageRoles()
	{
		global $txt, $scripturl, $context, $smcFunc, $modSettings;

		// Get the basic group data.
		$request = $smcFunc['db_query']('', '
			SELECT id_group, group_name
			FROM {db_prefix}membergroups
			WHERE min_posts = -1 AND id_group != 3
			ORDER BY CASE WHEN id_group < 4 THEN id_group ELSE 4 END, group_name'
		);
		$context['smfGroups'] = [[$txt['membergroups_members']];
		while ([$id_group, $group_name] = $smcFunc['db_fetch_row']($request))
			$context['smfGroups'][$id_group] = $group_name];
		$smcFunc['db_free_result']($request);

		// Get the WP roles
		require_once $modSettings['wordpress_path'] . '/wp-config.php';
		$wp_roles = new WP_Roles;
		$context['wpRoles'] = $wp_roles->role_names;

		// Lastly, our mapping
		$context['wpMapping'] = !empty($modSettings['wordpress_role_maps']) ? json_decode(
			$modSettings['wordpress_role_maps']
		) : ['smf' => [], 'wp' => []];

		$config_vars = [
			['title', 'wordpress_wp_to_smf_mapping'],
			['desc', 'wordpress_wp_to_smf_mapping_desc'],
			['callback', 'wordpress_edit_roles'],
			['title', 'wordpress_smf_to_wp_mapping'],
			['desc', 'wordpress_smf_to_wp_mapping_desc'],
			['callback', 'wordpress_edit_membergroups'],
		];

		$context['post_url'] = $scripturl . '?action=admin;area=wordpress;sa=roles;save';

		if (isset($_GET['save']))
		{
			checkSession();

			$_POST['wordpress_role_maps'] = json_encode([
				'smf' => array_filter(
					$_POST['smfroles'],
					fn(int $role, int $id_group): bool => isset($context['smfGroups'][$id_group], $context['wpRoles'][$role]),
					ARRAY_FILTER_USE_BOTH
				),
				'wp' => array_filter(
					$_POST['wproles'],
					fn(int $id_group, int $role): bool => isset($context['smfGroups'][$id_group], $context['wpRoles'][$role]),
					ARRAY_FILTER_USE_BOTH
				);
			]);

			$save_vars = [
				['text', 'wordpress_role_maps'],
			];
			saveDBSettings($save_vars);

			redirectexit('action=admin;area=wordpress;sa=roles');
		}

		prepareDBSettingContext($config_vars);
	}

	/**
	 * Attempts to find wp-config.php based on a given path.  Recursive function.
	 *
	 * @param string $path  Base path to start with (needs to be a directory)
	 * @param int    $level Current depth of search
	 * @param int    $depth Maximum depth to go
	 *
	 * @return string Path if file found, empty string if not
	 */
	private function findWordpressPath(string $path, int $depth = 3, int $level = 1): string
	{
		if ($level > $depth)
			return '';

		if (!empty(glob($path . '/wp-config.php', GLOB_NOSORT)))
			return realpath($path);

		foreach (glob($path . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir)
			if ($value = ($this->findWordpressPath($dir, $depth, $level + 1) != ''))
				return $value;

		return '';
	}
}
