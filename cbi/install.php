<?php

$column = array(
	'name' => 'cbi',
	'type' => 'varchar',
	'size' => 250,
	'null' => true
);

$smcFunc['db_add_column']('{db_prefix}boards', $column);
