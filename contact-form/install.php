<?php

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
{
	$ssi = true;
	require_once(dirname(__FILE__) . '/SSI.php');
}
elseif (!defined('SMF'))
	exit('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

add_integration_function('integrate_pre_include', '$sourcedir/Subs-Contact.php');
add_integration_function('integrate_menu_buttons', 'contact_menu_buttons');
add_integration_function('integrate_load_theme', 'contact_load_theme');
add_integration_function('integrate_actions', 'contact_actions');
add_integration_function('integrate_spam_protection', 'contact_spam_protection');

if (!empty($ssi))
	echo 'Database installation complete!';

?>