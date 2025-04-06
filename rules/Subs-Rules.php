<?php
// Version: 1.0: Subs-Rules.php

if (!defined('SMF'))
	die('Hacking attempt...');

function rules_admin_areas(&$admin_areas)
{
	global $txt;

	$admin_areas['config']['areas']['modsettings']['subsections']['rules'] = array($txt['mods_cat_rules']);
}

function rules_load_theme()
{
	loadLanguage('Rules');
}

function rules_menu_buttons($menu_buttons)
{
	global $txt, $context, $scripturl;

	$new_button = array(
		'title' => !empty($modSettings['rules_tab_label']) ? $modSettings['rules_tab_label'] : $txt['rules'],
		'href' => $scripturl . '?action=rules',
		'show' => allowedTo('view_rules'),
		'active_button' => false,
	);

	$new_menu_buttons = array();
	foreach ($menu_buttons as $area => $info)
	{
		$new_menu_buttons[$area] = $info;
		if ($area == 'help')
			$new_menu_buttons['rules'] = $new_button;
	}

	$menu_buttons = $new_menu_buttons;
}

function rules_modify_modifications(&$sub_actions)
{
	$sub_actions['rules'] = 'ModifyRulesSettings';
}

function ModifyRulesSettings($return_config = false)
{
	global $txt, $scripturl, $context, $settings, $sc;

	$config_vars = array(
		array('text', 'rules_tab_label', '20'),
	'',
		array('text', 'the_rules_display_name', '20'),
		array('large_text', 'the_rules_text', '12'),
	'',
		array('check', 'the_rules_enable_agreement'),
		array('text', 'the_rules_agreement_display_name', '20', 'postinput' => $txt['the_rules_edit_agreement_pre_html'] . $scripturl .
			'?action=admin;area=regcenter;sa=agreement">' . $txt['the_rules_edit_name'] . $txt['the_rules_edit_agreement_post_html']),
	'',
		array('check', 'the_rules_enable_additional'),
		array('text', 'the_rules_additional_display_name', '20'),
		array('large_text', 'the_rules_additional_text', '12'),
	);


	if ($return_config)
		return $config_vars;

	if (isset($_GET['save']))
	{
		checkSession();

		saveDBSettings($config_vars);
		writeLog();

		redirectexit('action=admin;area=modsettings;sa=rules');
	}

	$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=rules';
	$context['settings_title'] = $txt['rules_title'];

	prepareDBSettingContext($config_vars);
}

function rules_actions(&$action_array)
{
	$action_array['rules'] = array('Rules.php', 'Rules');
}

function rules_load_permissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
	$permissionList['membergroup'] += array(
		'view_rules' => array(false, 'general', 'view_basic_info'),
	);
}

?>