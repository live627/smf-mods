<?php
// Licence: MIT

// If SSI.php is in the same place as this file, and SMF isn't defined...
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');

// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot uninstall - please verify you put this in the same place as SMF\'s index.php.');

remove_integration_function('integrate_pre_include', '$sourcedir/FooterMenu.php');
remove_integration_function('integrate_load_theme', 'footer_menu_load_theme');
remove_integration_function('integrate_admin_areas', 'footer_menu_admin_areas');

?>
