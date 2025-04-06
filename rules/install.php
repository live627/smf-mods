<?php

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
{
	$ssi = true;
	require_once(dirname(__FILE__) . '/SSI.php');
}
elseif (!defined('SMF'))
	exit('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

add_integration_function('integrate_pre_include', '$sourcedir/Subs-Rules.php');
add_integration_function('integrate_load_theme', 'rules_load_theme');
add_integration_function('integrate_actions', 'rules_actions');
add_integration_function('integrate_menu_buttons', 'rules_menu_buttons');
add_integration_function('integrate_modify_modifications', 'rules_modify_modifications');
add_integration_function('integrate_admin_areas', 'rules_admin_areas');
add_integration_function('integrate_load_theme', 'rules_load_theme');
add_integration_function('integrate_actions', 'rules_actions');
add_integration_function('integrate_load_permissions', 'rules_load_permissions');

if (!empty($ssi))
	echo 'Database installation complete!';

?>