<?php
// Licence: MIT

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
{
	$ssi = true;
	require_once(dirname(__FILE__) . '/SSI.php');
}
elseif (!defined('SMF'))
	exit('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

add_integration_function('integrate_pre_include', '$sourcedir/ManageForms.php');
add_integration_function('integrate_load_theme', 'forms_load_theme');
add_integration_function('integrate_admin_areas', 'forms_admin_areas');

if (!array_key_exists('db_add_column', $smcFunc))
	db_extend('packages');

$columns = array(
	array(
		'name' => 'id_item',
		'type' => 'mediumint',
		'size' => 8,
		'unsigned' => true,
		'auto' => true,
	),
	array(
		'name' => 'name',
		'type' => 'varchar',
		'size' => 80,
	),
	array(
		'name' => 'url',
		'type' => 'varchar',
		'size' => 4096,
	),
	array(
		'name' => 'groups',
		'type' => 'varchar',
		'size' => 80,
	),
	array(
		'name' => 'category',
		'type' => 'mediumint',
		'size' => 8,
		'unsigned' => true,
	),
	array(
		'name' => 'active',
		'type' => 'enum(\'yes\',\'no\')',
	),
);

$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('id_item')
	),
);

$smcFunc['db_create_table']('{db_prefix}forms', $columns, $indexes, array(), 'overwrite');

$columns = array(
	array(
		'name' => 'id_item',
		'type' => 'mediumint',
		'size' => 8,
		'unsigned' => true,
		'auto' => true,
	),
	array(
		'name' => 'name',
		'type' => 'varchar',
		'size' => 50,
	),
);

$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('id_item')
	),
);

$smcFunc['db_create_table']('{db_prefix}forms_categories', $columns, $indexes, array(), 'update_remove');

$in_col = array(
	'id_item' => 'int', 'name' => 'string',
);
$in_data = array(
	array(
		1, 'Untitled',
	),
);
$smcFunc['db_insert']('ignore',
	'{db_prefix}forms_categories',
	$in_col,
	$in_data,
	array('id_field')
);

if (!empty($ssi))
	echo 'Database installation complete!';

?>