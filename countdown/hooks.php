<?php

// If SSI.php is in the same place as this file, and SMF isn't defined, this is being run standalone.
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');
 
db_extend('packages');

// Insert the hooks.
$hooks = array(
	'integrate_pre_include' => '$sourcedir/Subs-CountdownBBC.php',
	'integrate_general_mod_settings' => 'CountdownBBC::general_mod_settings',
	'integrate_bbc_buttons' => 'CountdownBBC::bbc_buttons',
	'integrate_bbc_codes' => 'CountdownBBC::bbc_codes',
);

if (!empty($context['uninstalling']))
	$hook_function = 'remove_integration_function';
else
	$hook_function = 'add_integration_function';

foreach ($hooks as $hook => $function)
	$hook_function($hook, $function);
