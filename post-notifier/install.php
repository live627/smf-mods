<?php
// Licence: MIT

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
{
	$ssi = true;
	require_once(dirname(__FILE__) . '/SSI.php');
}
elseif (!defined('SMF'))
	exit('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

add_integration_function('integrate_pre_include', '$sourcedir/PostNotifier.php');
add_integration_function('integrate_actions', 'post_notifier_actions');
add_integration_function('integrate_load_theme', 'post_notifier_load_theme');

if (!empty($ssi))
	echo 'Database installation complete!';
