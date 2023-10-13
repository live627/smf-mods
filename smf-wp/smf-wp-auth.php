<?php

/**
 * @package   WordPress Bridge
 * @version   2.0.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2017, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

/*
Plugin Name: smf-wp-auth
Description: Login redirect to smf
Version: 2.0.0
Author: live627
*/

add_action(
	'init',
	function ()
	{
		if (stripos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false && get_option('smf_path') !== false)
		{
			require get_option('smf_path') . '/SSI.php';
			$origin = parse_url($_SERVER['REQUEST_URI']);
			// Coming from wp-login.php?
			if (strpos($origin['path'], 'wp-login.php') !== false)
			{
				if (empty($origin['query']))
					$origin['query'] = 'action=login';
				$query = [];
				parse_str($origin['query'], $query);
				if (empty($query['action']))
					$query['action'] = 'login';
				switch ($query['action'])
				{
					case 'register':
						redirectexit('action=register');
						break;

					case 'logout':
						// Need to load the session real quick, so we can properly log out and redirect
						//loadSession();
						$_SESSION['logout_url'] = home_url();
						redirectexit(
							'action=logout&' . $_SESSION['session_var'] . '=' . $_SESSION['session_value']
						);
						break;

					case 'lostpassword':
					case 'retrievepassword':
						redirectexit('action=reminder');
						break;

					default:
						$_SESSION['login_url'] = home_url();
						redirectexit('action=login');
						break;
				}
			}
		}
	}
);