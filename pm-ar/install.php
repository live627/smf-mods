<?php

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
{
	$ssi = true;
	require_once(dirname(__FILE__) . '/SSI.php');
}
elseif (!defined('SMF'))
	exit('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

add_integration_function('integrate_pre_include', '$sourcedir/PMAutoResponder.php');
add_integration_function('integrate_personal_message', 'pm_ar_personal_message');
add_integration_function('integrate_profile_areas', 'pm_ar_profile_areas');
add_integration_function('integrate_load_permissions', 'pm_ar_load_permissions');

if (!array_key_exists('db_add_column', $smcFunc))
	db_extend('packages');

$columns = array(
	array(
		'name' => 'id_rule',
		'type' => 'int',
		'size' => '10',
		'unsigned' => true,
		'auto' => true,
	),
	array(
		'name' => 'id_member',
		'type' => 'int',
		'size' => '10',
		'unsigned' => true,
	),
	array(
		'name' => 'rule_name',
		'type' => 'varchar',
		'size' => '60',
	),
	array(
		'name' => 'criteria',
		'type' => 'text',
	),
	array(
		'name' => 'is_or',
		'type' => 'tinyint',
		'size' => '1',
		'default' => '0',
		'unsigned' => true,
	),
	array(
		'name' => 'subject',
		'type' => 'varchar',
		'size' => '60',
	),
	array(
		'name' => 'body',
		'type' => 'text',
	),
	array(
		'name' => 'save_in_outbox',
		'type' => 'tinyint',
		'size' => '1',
		'default' => '0',
		'unsigned' => true,
	),
);

$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('id_rule')
	),
	array(
		'columns' => array('id_member')
	),
);

$smcFunc['db_create_table']('{db_prefix}pm_ar_rules', $columns, $indexes, array(), 'update_remove');

if (!empty($ssi))
	echo 'Database installation complete!';

?>
