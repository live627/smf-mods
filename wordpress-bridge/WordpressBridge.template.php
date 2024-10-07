<?php

/**
 * @package   Wordpress Bridge
 * @version   2.0.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2017, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

function template_callback_wordpress_edit_roles()
{
	global $context, $txt;

	echo '
			<dt><b>', $txt['wordpress_smf_groups'], '</b></dt>
			<dd><b>', $txt['wordpress_wp_groups'], '</b></dd>';

	foreach ($context['smfGroups'] as $id_group => $group_name)
	{
		echo '
			<dt>', $group_name, '</dt>
			<dd>
				<select name="smfroles[', $id_group, ']">
					<option value="">', $txt['wordpress_select_one'], '</option>';

		foreach ($context['wpRoles'] as $id => $name)
			echo '
					<option value="', $id, '"', (!empty($context['wpMapping']['smf'][$id_group]) && $context['wpMapping']['smf'][$id_group] === $id ? ' selected="selected"' : ''), '>', $name, '</option>';

		echo '
				</select>
			</dd>';
	}
}

function template_callback_wordpress_edit_membergroups()
{
	global $context, $txt;

	echo '
			<dt><b>', $txt['wordpress_wp_groups'], '</b></dt>
			<dd><b>', $txt['wordpress_smf_groups'], '</b></dd>';

	foreach ($context['wpRoles'] as $id => $name)
	{
		echo '
			<dt>', $name, '</dt>
			<dd>
				<select name="wproles[', $id, ']">
					<option value="">', $txt['wordpress_select_one'], '</option>';

		foreach ($context['smfGroups'] as $id_group => $group_name)
			echo '
					<option value="', $id_group, '"', (isset($context['wpMapping']['wp'][$id]) && $context['wpMapping']['wp'][$id] === $id_group ? ' selected="selected"' : ''), '>', $group_name, '</option>';

		echo '
				</select>
			</dd>';
	}
}
