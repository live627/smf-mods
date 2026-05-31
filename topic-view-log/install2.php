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

    // Creating tables
	$tables = array(
		'log_topic_view' => array(
			'name' => 'log_topic_view',
			//Columns
			'columns' => array(
				array(
					'name' => 'id_member',
					'type' => 'mediumint',
					'size' => '8',
					'default' => '0',
				),
				array(
					'name' => 'id_topic',
					'type' => 'mediumint',
					'size' => '8',
					'default' => '0',
				),
				array(
					'name' => 'time',
					'type' => 'int',
					'size' => '10',
					'default' => '0',
				),
			),
			//End Columns
			'indexes' => array(
				array(
					'type' => 'primary',
					'columns' => array('id_member', 'id_topic', 'time')
				),
				array(
					'columns' => array('id_member')
				),
				array(
					'columns' => array('id_topic')
				),
			)
		),
		//End Table
	);

	foreach ($tables as $table)
	{
		$smcFunc['db_create_table']('{db_prefix}' . $table['name'], $table['columns'], $table['indexes'], array(), 'update');
	}

if (!empty($ssi))
	echo 'Database installation complete!';

?>