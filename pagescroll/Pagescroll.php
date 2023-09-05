<?php

/**
 * @package Pagescroll
 * @version 1.0
 * @author John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2019, John Rayes
 * @license http://opensource.org/licenses/MIT MIT
 */

if (!defined('SMF'))
	die('Hacking attempt...');

function pagescroll_load_theme()
{
	global $context, $settings;

	loadTemplate(false, 'pagescroll');
	$context['html_headers'] .= '
	<script type="text/javascript" src="' . $settings['default_theme_url'] . '/scripts/pagescroll.js"></script>';
}