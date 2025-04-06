<?php

// If SSI.php is in the same place as this file, and SMF isn't defined...
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');

// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot uninstall - please verify you put this in the same place as SMF\'s index.php.');

remove_integration_function('integrate_pre_include', '$sourcedir/Subs-Rules.php');
remove_integration_function('integrate_load_theme', 'rules_load_theme');
remove_integration_function('integrate_actions', 'rules_actions');
remove_integration_function('integrate_menu_buttons', 'rules_menu_buttons');
remove_integration_function('integrate_modify_modifications', 'rules_modify_modifications');
remove_integration_function('integrate_admin_areas', 'rules_admin_areas');
remove_integration_function('integrate_load_theme', 'rules_load_theme');
remove_integration_function('integrate_actions', 'rules_actions');
remove_integration_function('integrate_load_permissions', 'rules_load_permissions');

?>
