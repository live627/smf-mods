<?php

if (!defined('SMF'))
	die('Hacking attempt...');

$mod_settings = array(
	'guest_notify_css' => 'border: 1px solid #cc3344;color: #000;background-color: #ffe4e9;',
	'guest_notify_css_title' => 'font-size: 1.1em;text-decoration: underline;',
);

updateSettings($mod_settings);

$hooks = array(
    'integrate_pre_include' => '$boarddir/Themes/default/GuestRegistrationNotification.template.php',
    'integrate_load_theme' => 'grnModifyLayer',
	'integrate_admin_areas' => 'integrateAdminAreasGRN',
	'integrate_modify_modifications' => 'integrateModifyModificationsGRN'
);

foreach ($hooks as $hook => $function)
	add_integration_function($hook, $function);

?>