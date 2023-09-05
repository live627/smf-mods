<?php

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
{
	$ssi = true;
	require_once(dirname(__FILE__) . '/SSI.php');
}
elseif (!defined('SMF'))
	exit('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

if (!array_key_exists('db_add_column', $smcFunc))
	db_extend('packages');

$columns = array(
	array(
		'name' => 'id_msg',
		'type' => 'mediumint',
		'size' => 10,
		'unsigned' => true,
	),
	array(
		'name' => 'id_member',
		'type' => 'mediumint',
		'size' => 8,
		'unsigned' => true,
	),
);

$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('id_msg', 'id_member'),
	),
);

$smcFunc['db_create_table']('{db_prefix}log_message_quotes', $columns, $indexes, array(), 'update_remove');

if (!empty($ssi))
	echo 'Database installation complete!';