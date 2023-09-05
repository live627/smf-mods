<?php

/**
 * @package   Login Code
 * @version   1.0.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2022, John Rayes
 * @license   http://opensource.org/licenses/0BSD Zero-Clause BSD
 */

if (!defined('SMF'))
	require_once __DIR__  . '/SSI.php';

$smcFunc['db_create_table']('{db_prefix}login_keys', array(
	array('name' => 'id_member', 'type' => 'mediumint'),
	array('name' => 'code', 'type' => 'char', 'size' => 6),
	array('name' => 'timestamp', 'type' => 'timestamp')
), array(
	array('type' => 'primary', 'columns' => array('id_member'))
));
