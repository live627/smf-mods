<?php

if (!defined('SMF'))
	die('Hacking attempt...');

$hooks = array(
    'integrate_pre_include' => '$boarddir/Themes/default/GuestRegistrationNotification.template.php',
    'integrate_load_theme' => 'grnModifyLayer',
	'integrate_admin_areas' => 'integrateAdminAreasGRN',
	'integrate_modify_modifications' => 'integrateModifyModificationsGRN'
);

foreach ($hooks as $hook => $function)
	remove_integration_function($hook, $function);

?>