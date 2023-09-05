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
	die('<b>Error:</b> Cannot uninstall - please verify you put this in the same place as SMF\'s index.php.');

remove_integration_function('integrate_pre_include', '$sourcedir/Pagescroll.php');
remove_integration_function('integrate_load_theme', 'pagescroll_load_theme');
