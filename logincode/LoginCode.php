<?php

/**
 * @package   Login Code
 * @version   1.0.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2022, John Rayes
 * @license   http://opensource.org/licenses/0BSD Zero-Clause BSD
 */

declare(strict_types=1);

function logincode_actions(array &$actions): void
{
	$actions['logincode'] = array('LoginCode.php', 'LoginCode');
	$actions['logincode2'] = array('LoginCode.php', 'LoginCode2');
}

function LoginCode(): void
{
	global $context, $modSettings, $scripturl, $smcFunc, $txt;

	if (!isset($_SESSION['old_url']) || strpos($_SESSION['old_url'], 'login2') === false)
		fatal_lang_error('no_access', false);

	$context['robot_no_index'] = true;
	$context['canonical_url'] = $scripturl . '?action=login';
	$context['u'] = $_SESSION['id_member'];
	loadLanguage('LoginCode');
	$context['page_title'] = $txt['logincode_verification'];
	loadTemplate('LoginCode');

	$smcFunc['db_query']('', 'DELETE FROM {db_prefix}login_keys WHERE timestamp < (NOW() - INTERVAL {int:ttl} MINUTE)', ['ttl' => $modSettings['logincode_ttl']]);
	$code = substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789'), 0, 6);
	$smcFunc['db_insert'](
		'ignore',
		'{db_prefix}login_keys',
		array('id_member' => 'int', 'code' => 'string', 'timestamp' => 'raw'),
		array($_SESSION['id_member'], $code, 'NOW()'),
		array('id_member')
	);

	if ($smcFunc['db_affected_rows']() == 0)
	{
		$request = $smcFunc['db_query']('', '
			SELECT code
			FROM {db_prefix}login_keys
			WHERE id_member = {int:current_member}',
			array(
				'current_member' => $_SESSION['id_member'],
			)
		);
		[$code] = $smcFunc['db_fetch_row']($request);
		$smcFunc['db_free_result']($request);
	}

	$request = $smcFunc['db_query']('', '
		SELECT email_address, lngfile, real_name, time_offset, time_format
		FROM {db_prefix}members
		WHERE id_member = {int:current_member}',
		array(
			'current_member' => $_SESSION['id_member'],
		)
	);
	[$email_address, $lngfile, $real_name, $time_offset, $time_format] = $smcFunc['db_fetch_row']($request);
	$smcFunc['db_free_result']($request);
	$GLOBALS['user_info']['time_offset'] = $time_offset ?: 0;
	require_once __DIR__ . '/Class-LoginCode.php';
	$browserObj = new Sinergi\BrowserDetector\Browser;
	$osObj = new Sinergi\BrowserDetector\Os;
	$deviceObj = new Sinergi\BrowserDetector\Device;
	$ip = $_SERVER['REMOTE_ADDR'];
	require_once __DIR__ . '/Subs-Package.php';
	$details = json_decode(fetch_web_data("http://ipinfo.io/{$ip}/json"), true);
	$replacements = array(
		'CODE' => $code,
		'TTL' => $modSettings['logincode_ttl'],
		'WEBMASTER' => $GLOBALS['webmaster_email'],
		'TIME' => timeformat(time(), $time_format ?: $txt['time_format'] ?? $modSettings['time_format']),
		'NAME' => $real_name,
		'LOCATION' => isset($details['city']) ? $details['city'] . ' ' . $details['region'] . ' ' . $details['country'] : '',
		'BROWSER' => $browserObj->getName() . ' ' . $browserObj->getVersion(),
		'OS' => $osObj->getName() . ' ' . $osObj->getVersion(),
		'DEVICE' => $deviceObj->getName(),
		'PROFILELINK' => $scripturl . 'action=profie;area=notification;u=' . $_SESSION['id_member'],
	);
	if ($replacements['DEVICE'] == '')
	{
		$txt['emails']['logincode']['body'] = str_replace("{DEVICE}\n", '', $txt['emails']['logincode']['body']);
		$txt['emails']['logincode']['html_body'] = str_replace('{DEVICE}<br />', '', $txt['emails']['logincode']['html_body']);
	}
	if ($replacements['LOCATION'] == '')
	{
		$txt['emails']['logincode']['body'] = str_replace("{LOCATION}\nLocation is aproximate based on the login\'s IP address.\n", '', $txt['emails']['logincode']['body']);
		$txt['emails']['logincode']['html_body'] = str_replace("{LOCATION}<br />\nLocation is aproximate based on the login\'s IP address.", '', $txt['emails']['logincode']['html_body']);
	}
	if ($txt['emails']['logincode']['html'])
		$txt['emails']['logincode']['body'] = $txt['emails']['logincode']['html_body'];
	unset($_SESSION['id_member']);
	require_once __DIR__ . '/Subs-Post.php';
	$emaildata = loadEmailTemplate('logincode', $replacements, '', false);
	sendmail($email_address, $emaildata['subject'], $emaildata['body'], null, null, $txt['emails']['logincode']['html'], 0);
}

function LoginCode2(): void
{
	global $context, $modSettings, $scripturl, $smcFunc, $txt, $user_settings;

	if (!isset($_SESSION['old_url']) || strpos($_SESSION['old_url'], 'logincode') === false || !isset($_POST['u']) || !isset($_POST['code']) || !isset($modSettings['logincode_ttl']) || !ctype_digit($modSettings['logincode_ttl']))
		fatal_lang_error('no_access', false);

	$context['errors'] = array();
	if (($sc_error = checkSession('post', '', false)) != '')
		$context['errors'][] = 'error_' . $sc_error;

	$request = $smcFunc['db_query']('', '
		SELECT code
		FROM {db_prefix}login_keys
		WHERE id_member = {int:current_member}',
		array(
			'current_member' => $_POST['u'],
		)
	);
	[$code] = $smcFunc['db_fetch_row']($request);
	$smcFunc['db_free_result']($request);

	if ($_POST['code'] !== $code)
		$context['errors'][] = 'logincode_invalid_value';

	if ($context['errors'] === [])
	{
		$smcFunc['db_query']('', 'DELETE FROM {db_prefix}login_keys WHERE id_member = {int:current_member}', ['current_member' => $_POST['u']]);
		$request = $smcFunc['db_query']('', '
			SELECT
				passwd, id_member, id_group, lngfile, is_activated,
				email_address, additional_groups, member_name,
				password_salt, openid_uri, passwd_flood
			FROM {db_prefix}members
			WHERE id_member = {int:current_member}',
			array(
				'current_member' => $_POST['u'],
			)
		);
		$user_settings = $smcFunc['db_fetch_assoc']($request);
		$smcFunc['db_free_result']($request);

		require_once __DIR__ . '/LogInOut.php';
		DoLogin();
		redirectexit();
	}
	else
	{
		$context['robot_no_index'] = true;
		$context['canonical_url'] = $scripturl . '?action=login';
		$context['u'] = $_POST['u'];
		loadLanguage('LoginCode+Errors');
		$context['page_title'] = $txt['logincode_verification'];
		loadTemplate('LoginCode');
	}
}

function logincode_login(): void
{
	global $context, $modSettings, $user_settings;

	if ($context['current_action'] != 'logincode2' && !empty($modSettings['logincode_ttl']) && ctype_digit($modSettings['logincode_ttl']))
	{
		$_SESSION['id_member'] = $user_settings['id_member'];
		redirectexit('action=logincode');
	}
}

function logincode_general_mod_settings(&$config_vars): void
{
	global $txt;

	loadLanguage('LoginCode');
	$config_vars = array_merge($config_vars, array(
		'',
		array('text', 'logincode_ttl', 'subtext' => $txt['logincode_ttl_zero'], 'postinput' => $txt['logincode_ttl_minutes']),
	));
}
