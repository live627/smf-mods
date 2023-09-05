<?php

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF')) // If we are outside SMF and can't find SSI.php, then throw an error
	die('<b>Error:</b> Cannot install - please verify you put this file in the same place as SMF\'s SSI.php.');

db_extend('packages');

$column = array(
	'name' => 'stack_trace',
	'type' => 'text',
	'null' => true
);

$smcFunc['db_add_column']('{db_prefix}log_errors', $column);

if (SMF == 'SSI')
	echo 'Database changes are complete!';

?>
