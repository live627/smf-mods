<?php
// Licence: MIT

// If SSI.php is in the same place as this file, and SMF isn't defined...
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');

// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot uninstall - please verify you put this in the same place as SMF\'s index.php.');

if (isset($modSettings['footer_menu']))
	unset($modSettings['footer_menu']);

$smcFunc['db_query']('', '
	DELETE FROM {db_prefix}settings
	WHERE variable = {string:setting}',
	array(
		'setting' => 'footer_menu',
	)
);

?>