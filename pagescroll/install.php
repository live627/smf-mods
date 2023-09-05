<?php

/**
 * @package Pagescroll
 * @version 1.0
 * @author John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2019, John Rayes
 * @license http://opensource.org/licenses/MIT MIT
 */

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
	exit('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

add_integration_function('integrate_pre_include', '$sourcedir/Pagescroll.php');
add_integration_function('integrate_load_theme', 'pagescroll_load_theme');