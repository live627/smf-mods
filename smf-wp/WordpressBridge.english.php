<?php

/**
 * @package   Wordpress Bridge
 * @version   2.0.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2017, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

$txt['wordpress_bridge'] = 'Wordpress Bridge';
$txt['wordpress_bridge_settings'] = 'Bridge Settings';
$txt['wordpress_settings_desc'] = 'Enter and modify settings that pertain to Wordpress and the bridge.';

// Basic Settings
$txt['wordpress_enabled'] = 'Enable Wordpress Bridge';
$txt['wordpress_path'] = 'Wordpress Path';
$txt['wordpress_path_desc'] = 'This should be the full file path to your wp-config.php file.';
$txt['wordpress_path_desc_extra'] = 'This path is a guess and has NOT been saved yet.  Please click the "Save" button to save this path permamently.';
$txt['wordpress_path_desc_extra2'] = 'Empty this field and hit save to attempt to find this automatically.';
$txt['wordpress_version'] = 'Wordpress Version';

// Role settings
$txt['wordpress_roles'] = 'Role Settings';
$txt['wordpress_roles_desc'] = 'Select which roles in either software correspond to each other.';
$txt['wordpress_smf_groups'] = 'SMF Membergroup';
$txt['wordpress_wp_groups'] = 'Wordpress Role';
$txt['wordpress_select_one'] = 'Select one...';
$txt['wordpress_smf_to_wp_mapping'] = 'Map SMF Membergroups to Wordpress Roles';
$txt['wordpress_smf_to_wp_mapping_desc'] = 'As users are imported from Wordpress, they will be created with the SMF Membergroup that you assign to their Wordpress role.  Any user with a Wordpress role that is not mapped will be created in SMF as a Regular Member.';
$txt['wordpress_wp_to_smf_mapping'] = 'Map Wordpress roles to SMF Membergroups';
$txt['wordpress_wp_to_smf_mapping_desc'] = 'As users are created in Wordpress, they will be created with the Wordpress role that you assign to their primary membergroup.';

// Error strings
$txt['wordpress_no_config'] = 'No Wordpress configuration file was found';
$txt['wordpress_cannot_connect'] = 'Could not connect to the Wordpress database';
$txt['wordpress_cannot_sync'] = 'There was a problem logging %s into SMF using the Wordpress account.  Please ask the administrator to check the error log for more information.';
$txt['wordpress_cannot_read'] = 'Could not read the Wordpress file.  Please ask your host to allow one of the following functions: %s';
$txt['wordpress_problems'] = 'We found the following problems:';

// plugin strings
$txt['wordpress_inactive'] = 'The Wordpress redirection plugin is inactive.';
$txt['wordpress_active'] = 'The Wordpress redirection plugin is active.';
$txt['wordpress_activated'] = 'The Wordpress redirection plugin is now activated.';
$txt['wordpress_activate_plugin'] = 'Activate the Wordpress redirection plugin';
