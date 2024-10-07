<?php
// Version: 1.0: PMAutoResponder.php

if (!defined('SMF'))
	die('Hacking attempt...');

function pm_ar_personal_message($recipients, $from_name, $subject, $message)
{
	global $context, $smcFunc, $txt, $user_info;

	if (isset($context['pm_ar']))
		return;

	loadLanguage('PMAutoResponder');
	if (!empty($recipients['to']) || !empty($recipients['bcc']))
	{
		$auto_recipients = array_merge($recipients['to'], $recipients['bcc']);
		$request = $smcFunc['db_query']('', '
			SELECT m.id_member, m.real_name, m.member_name, t.value, t.variable
			FROM {db_prefix}members AS m
				INNER JOIN {db_prefix}themes AS t ON (m.id_member = t.id_member AND t.variable LIKE {string:auto_recipients_var})
			WHERE m.id_member IN ({array_int:auto_recipients})
				AND t.value != ""',
			array(
				'auto_recipients' => $auto_recipients,
				'auto_recipients_var' => '%pm_ar_%',
			)
		);
		$members = array();
		$theme_members = array();
		while ($row = $smcFunc['db_fetch_assoc']($request))
		{
			$members[$row['id_member']] = array(
				'name' => $row['real_name'],
				'username' => $row['member_name'],
			);
			$theme_members[$row['id_member']][$row['variable']] = $row['value'];
		}

		foreach ($members as $id_member => $member)
		{
			if (isset($theme_members[$id_member]['pm_ar_enabled'], $theme_members[$id_member]['pm_ar_subject'], $theme_members[$id_member]['pm_ar_body']))
			{
				$context['pm_ar'] = true;
				list ($subject, $body, $save_in_outbox) = pm_ar_apply_rules($id_member, $theme_members[$id_member]['pm_ar_subject'], $theme_members[$id_member]['pm_ar_body'], $theme_members[$id_member]['pm_ar_outbox']);
				sendpm(
					array(
						'to' => array($user_info['id']),
						'bcc' => array()
					),
					$subject,
					$body,
					!empty($save_in_outbox),
					array(
						'id' => $id_member,
						'name' => $member['name'],
						'username' => $member['username']
					)
				);
			}
		}
	}
}

function pm_ar_profile_areas(&$profile_areas)
{
	global $txt;

	if (!allowedTo('pm_ar'))
		return $profile_areas;

	loadLanguage('PMAutoResponder');
	$profile_areas['edit_profile']['areas']['pm_ar'] = array(
		'label' => $txt['pm_ar_profile_area'],
		'file' => 'PMAutoResponder.php',
		'function' => 'PMAutoResponderProfile',
		'enabled' => allowedTo(array('profile_extra_own', 'profile_extra_any')),
		'sc' => 'post',
		'permission' => array(
			'own' => array('profile_extra_own'),
			'any' => array('profile_extra_any'),
		),
		'subsections' => array(
			'general' => array($txt['pm_ar_general']),
			'filters' => array($txt['pm_ar_filters']),
		),
	);
}

function PMAutoResponderProfile($memID)
{
	global $context, $txt, $scripturl;

	$sub_actions = array(
		'general' => 'PMAutoResponderGeneral',
		'filters' => 'PMAutoResponderFilters',
	);

	// Default to sub action 'general'
	if (!isset($_GET['sa']) || !isset($sub_actions[$_GET['sa']]))
		$_GET['sa'] = 'general';

	// Create the tabs for the template.
	$context[$context['profile_menu_name']]['tab_data'] = array(
		'title' => $txt['pm_ar_profile_area'],
		'description' => $txt['pm_ar_general_desc'],
		'icon' => 'profile_sm.gif',
		'tabs' => array(
			'general' => array(
				'title' => $txt['pm_ar_general'],
				'description' => $txt['pm_ar_general_desc'],
			),
			'filters' => array(
				'title' => $txt['pm_ar_filters'],
				'description' => $txt['pm_ar_filters_desc'],
			),
		),
	);

	// Calls a function based on the sub-action
	$sub_actions[$_GET['sa']]($memID);
}

function PMAutoResponderGeneral($memID)
{
	global $context, $cur_profile, $txt;

	$context['profile_fields'] = array(
		'pm_ar_enabled' => array(
			'label' => $txt['pm_ar_enabled'],
			'type' => 'check',
			'input_attr' => '',
			'value' => isset($cur_profile['options']['pm_ar_enabled']) ? $cur_profile['options']['pm_ar_enabled'] : '',
		),
		'pm_ar_subject' => array(
			'label' => $txt['pm_ar_subject'],
			'subtext' => $txt['pm_ar_subject_desc'],
			'type' => 'text',
			'input_attr' => '',
			'value' => isset($cur_profile['options']['pm_ar_subject']) ? $cur_profile['options']['pm_ar_subject'] : '',
		),
		'pm_ar_body' => array(
			'type' => 'callback',
			'callback_func' => 'pm_ar_body',
		),
		'pm_ar_outbox' => array(
			'label' => $txt['pm_ar_outbox'],
			'type' => 'check',
			'input_attr' => '',
			'value' => isset($cur_profile['options']['pm_ar_outbox']) ? $cur_profile['options']['pm_ar_outbox'] : '',
		),
	);

	$context['sub_template'] = 'edit_options';
	$context['profile_header_text'] = $txt['pm_ar_profile_area'];
	$context['page_desc'] = $txt['pm_ar_profile_area'];
	$context['profile_execute_on_save'] = array('pm_ar_profile_save');
}

function template_profile_pm_ar_body()
{
	global $cur_profile, $txt;

	echo '
						<dt>
							<strong>', $txt['pm_ar_body'], '</strong>
							<br />
							<span class="smalltext">', $txt['pm_ar_body_desc'], '</span>
						</dt>
						<dd>
							<textarea id="pm_ar_body" name="pm_ar_body" cols="8" rows="40" style="width:90%; height: 300px;">', isset($cur_profile['options']['pm_ar_body']) ? $cur_profile['options']['pm_ar_body'] : '', '</textarea>
						</dd>';
}

function pm_ar_profile_save()
{
	global $context;

	$_POST['default_options'] = array(
		'pm_ar_enabled',
		'pm_ar_subject',
		'pm_ar_body',
		'pm_ar_outbox',
	);

	makeThemeChanges($context['member'], 1);
}

function pm_ar_load_permissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
	global $context;

	loadLanguage('PMAutoResponder');
	$permissionList['membergroup'] += array(
		'pm_ar' => array(false, 'pm', 'use_pm_system'),
	);

	$context['non_guest_permissions'] = array_unshift($context['non_guest_permissions'],
		'pm_ar'
	);
}

function template_profile_pm_ar_body2()
{
	global $context, $txt;

	echo '
						<dt>
							<strong>', $txt['pm_ar_body'], '</strong>
							<br />
							<span class="smalltext">', $txt['pm_ar_body_desc'], '</span>
						</dt>
						<dd>
							<textarea id="body" name="body" style="width:90%; height: 300px;">', isset($context['rule']['body']) ? $context['rule']['body'] : '', '</textarea>
						</dd>';
}

// List all rules, and allow adding/entering etc....
function PMAutoResponderFilters($memID)
{
	global $txt, $context, $user_info, $scripturl, $smcFunc;

	// Load them... load them!!
	pm_ar_load_rules(false, $memID);
	loadLanguage('PersonalMessage');
	$context['sub_template'] = 'rules2';

	// Likely to need all the groups!
	$request = $smcFunc['db_query']('', '
		SELECT mg.id_group, mg.group_name, IFNULL(gm.id_member, 0) AS can_moderate, mg.hidden
		FROM {db_prefix}membergroups AS mg
			LEFT JOIN {db_prefix}group_moderators AS gm ON (gm.id_group = mg.id_group AND gm.id_member = {int:current_member})
		WHERE mg.min_posts = {int:min_posts}
			AND mg.id_group != {int:moderator_group}
			AND mg.hidden = {int:not_hidden}
		ORDER BY mg.group_name',
		array(
			'current_member' => $user_info['id'],
			'min_posts' => -1,
			'moderator_group' => 3,
			'not_hidden' => 0,
		)
	);
	$context['groups'] = array();
	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		// Hide hidden groups!
		if ($row['hidden'] && !$row['can_moderate'] && !allowedTo('manage_membergroups'))
			continue;

		$context['groups'][$row['id_group']] = $row['group_name'];
	}
	$smcFunc['db_free_result']($request);

	// Editing a specific one?
	if (isset($_GET['add']))
	{
		$context['in'] = isset($_GET['in']) && isset($context['rules'][$_GET['in']])? (int) $_GET['in'] : 0;
		$context['sub_template'] = 'edit_options';

		// Current rule information...
		if ($context['in'])
		{
			$context['rule'] = $context['rules'][$context['in']];
			$members = array();
			// Need to get member names!
			foreach ($context['rule']['criteria'] as $k => $criteria)
				if ($criteria['t'] == 'mid' && !empty($criteria['v']))
					$members[(int) $criteria['v']] = $k;

			if (!empty($members))
			{
				$request = $smcFunc['db_query']('', '
					SELECT id_member, member_name
					FROM {db_prefix}members
					WHERE id_member IN ({array_int:member_list})',
					array(
						'member_list' => array_keys($members),
					)
				);
				while ($row = $smcFunc['db_fetch_assoc']($request))
					$context['rule']['criteria'][$members[$row['id_member']]]['v'] = $row['member_name'];
				$smcFunc['db_free_result']($request);
			}
		}
		else
			$context['rule'] = array(
				'id' => '',
				'name' => '',
				'criteria' => array(),
				'subject' => '',
				'message' => '',
				'save_in_outbox' => 0,
				'logic' => 'and',
			);

		$context['profile_fields'] = array(
			'rule_name' => array(
				'label' => $txt['pm_rule_name'],
				'subtext' => $txt['pm_rule_name_desc'],
				'type' => 'text',
				'input_attr' => '',
				'value' => empty($context['rule']['name']) ? $txt['pm_rule_name_default'] : $context['rule']['name'],
			),
			'pm_ar_add_rule' => array(
				'type' => 'callback',
				'callback_func' => 'pm_ar_add_rule',
			),
			'subject' => array(
				'label' => $txt['pm_ar_subject'],
				'subtext' => $txt['pm_ar_subject_desc'],
				'type' => 'text',
				'input_attr' => '',
				'value' => isset($context['rule']['subject']) ? $context['rule']['subject'] : '',
			),
			'body' => array(
				'type' => 'callback',
				'callback_func' => 'pm_ar_body2',
			),
			'save_in_outbox' => array(
				'label' => $txt['pm_ar_outbox'],
				'type' => 'check',
				'input_attr' => '',
				'value' => isset($context['rule']['save_in_outbox']) ? $context['rule']['save_in_outbox'] : '',
			),
		);
		$context['profile_header_text'] = $txt['pm_ar_profile_area'];
		$context['page_desc'] = $txt['pm_ar_profile_area'];
		$context['submit_button_text'] = $txt['pm_rule_save'];
		$context['profile_custom_submit_url'] = $scripturl . '?action=profile;area=' . $context['menu_item_selected'] . ';sa=filters;u=' . $context['id_member'] . ';pmarsave';
	}
	// Saving?
	elseif (isset($_GET['pmarsave']))
	{
		checkSession('post');
		$context['in'] = isset($_GET['in']) && isset($context['rules'][$_GET['in']])? (int) $_GET['in'] : 0;

		// Name is easy!
		$rule_name = $smcFunc['htmlspecialchars'](trim($_POST['rule_name']));
		if (empty($rule_name))
			fatal_lang_error('pm_rule_no_name', false);

		// Sanity check...
		if (empty($_POST['ruletype']))
			fatal_lang_error('pm_rule_no_criteria', false);

		// Let's do the criteria first - it's also hardest!
		$criteria = array();
		foreach ($_POST['ruletype'] as $ind => $type)
		{
			// Check everything is here...
			if ($type == 'gid' && (!isset($_POST['ruledefgroup'][$ind]) || !isset($context['groups'][$_POST['ruledefgroup'][$ind]])))
				continue;
			elseif ($type != 'bud' && !isset($_POST['ruledef'][$ind]))
				continue;

			// Members need to be found.
			if ($type == 'mid')
			{
				$name = trim($_POST['ruledef'][$ind]);
				$request = $smcFunc['db_query']('', '
					SELECT id_member
					FROM {db_prefix}members
					WHERE real_name = {string:member_name}
						OR member_name = {string:member_name}
						OR id_member = {string:member_name}',
					array(
						'member_name' => $name,
					)
				);
				if ($smcFunc['db_num_rows']($request) == 0)
					continue;
				list ($memID) = $smcFunc['db_fetch_row']($request);
				$smcFunc['db_free_result']($request);

				$criteria[] = array('t' => 'mid', 'v' => $memID);
			}
			elseif ($type == 'bud')
				$criteria[] = array('t' => 'bud', 'v' => 1);
			elseif ($type == 'gid')
				$criteria[] = array('t' => 'gid', 'v' => (int) $_POST['ruledefgroup'][$ind]);
			elseif (in_array($type, array('sub', 'msg')) && trim($_POST['ruledef'][$ind]) != '')
				$criteria[] = array('t' => $type, 'v' => $smcFunc['htmlspecialchars'](trim($_POST['ruledef'][$ind])));
		}
		$is_or = $_POST['rule_logic'] == 'or' ? 1 : 0;

		if (empty($criteria))
			fatal_lang_error('pm_rule_no_criteria', false);

		// What are we storing?
		$criteria = serialize($criteria);
		$subject = $smcFunc['htmlspecialchars'](un_htmlspecialchars(trim($_POST['subject'])));
		$body = $smcFunc['htmlspecialchars'](un_htmlspecialchars(trim($_POST['body'])));

		// Create the rule?
		if (empty($context['in']))
			$smcFunc['db_insert']('',
				'{db_prefix}pm_ar_rules',
				array(
					'id_member' => 'int', 'rule_name' => 'string', 'criteria' => 'string',
					'subject' => 'string', 'body' => 'string', 'save_in_outbox' => 'int', 'is_or' => 'int',
				),
				array(
					$user_info['id'], $rule_name, $criteria, $subject, $body, (int) !empty($_POST['save_in_outbox']), $is_or,
				),
				array('id_rule')
			);
		else
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}pm_ar_rules
				SET rule_name = {string:rule_name}, criteria = {string:criteria}, subject = {string:subject},
					body = {string:body}, save_in_outbox = {int:save_in_outbox}, is_or = {int:is_or}
				WHERE id_rule = {int:id_rule}
					AND id_member = {int:current_member}',
				array(
					'current_member' => $user_info['id'],
					'is_or' => $is_or,
					'id_rule' => $context['in'],
					'rule_name' => $rule_name,
					'criteria' => $criteria,
					'subject' => $subject,
					'body' => $body,
					'save_in_outbox' => (int) !empty($_POST['save_in_outbox']),
				)
			);

		redirectexit('action=profile;area=pm_ar;sa=filters');
	}
	// Deleting?
	elseif (isset($_POST['delselected']) && !empty($_POST['delrule']))
	{
		checkSession('post');
		$delete_list = array();
		foreach ($_POST['delrule'] as $k => $v)
			$delete_list[] = (int) $k;

		if (!empty($delete_list))
			$smcFunc['db_query']('', '
				DELETE FROM {db_prefix}pm_ar_rules
				WHERE id_rule IN ({array_int:delete_list})
					AND id_member = {int:current_member}',
				array(
					'current_member' => $user_info['id'],
					'delete_list' => $delete_list,
				)
			);

		redirectexit('action=profile;area=pm_ar;sa=filters');
	}
}

function pm_ar_apply_rules($id_member_from, $subject, $body, $save_in_outbox)
{
	global $context, $user_info;

	// Want this - duh!
	pm_ar_load_rules(false, $id_member_from);

	// No rules?
	if (empty($context['rules']))
		return false;

	foreach ($context['rules'] as $rule)
	{
		$match = false;
		// Loop through all the criteria hoping to make a match.
		foreach ($rule['criteria'] as $criterium)
		{
			die(var_dump($criterium, $id_member_from, $criterium['t'] == 'mid' && $criterium['V'] == $id_member_from));
			if (($criterium['t'] == 'mid' && $criterium['V'] == $id_member_from) || ($criterium['t'] == 'gid' && in_array($criterium['V'], $user_info['groups'])) || ($criterium['t'] == 'sub' && strpos($subject, $criterium['V']) !== false) || ($criterium['t'] == 'msg' && strpos($body, $criterium['V']) !== false))
				$match = true;
			// If we're adding and one criteria don't match then we stop!
			elseif ($rule['logic'] == 'and')
			{
				$match = false;
				break;
			}
		}

		// If we have a match the rule must be true - act!
		if ($match)
			return array($rule['subject'], $rule['body'], $rule['save_in_outbox']);
	}

	// No applicable rule found for you, sucka!!
	return array($subject, $body, $save_in_outbox);
}

// Load up all the rules for the current user.
function pm_ar_load_rules($reload = false, $id_member_from)
{
	global $user_info, $context, $smcFunc;

	if (isset($context['rules']) && !$reload)
		return;

	$request = $smcFunc['db_query']('', '
		SELECT
			id_rule, rule_name, criteria, subject, body, save_in_outbox, is_or
		FROM {db_prefix}pm_ar_rules
		WHERE id_member = {int:id_member_from}',
		array(
			'id_member_from' => $id_member_from,
		)
	);
	$context['rules'] = array();
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$context['rules'][$row['id_rule']] = array(
			'id' => $row['id_rule'],
			'name' => $row['rule_name'],
			'criteria' => unserialize($row['criteria']),
			'subject' => $row['subject'],
			'body' => $row['body'],
			'save_in_outbox' => $row['save_in_outbox'],
			'logic' => $row['is_or'] ? 'or' : 'and',
		);

	$smcFunc['db_free_result']($request);
}

// Manage rules.
// !!! TODO: Convert this to use the generic list.
function template_rules2()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
	<form action="', $scripturl, '?action=profile;area=pm_ar;sa=filters;u=' . $context['id_member'] . '" method="post" accept-charset="', $context['character_set'], '" name="manRules" id="manrules">
		<div class="cat_bar">
			<h3 class="catbg">', $txt['pm_manage_rules'], '</h3>
		</div>
		<div class="description">
			', $txt['pm_manage_rules_desc'], '
		</div>
		<table width="100%" class="table_grid">
		<thead>
			<tr class="catbg">
				<th class="lefttext first_th">
					', $txt['pm_rule_title'], '
				</th>
				<th width="4%" class="centertext last_th">';

	if (!empty($context['rules']))
		echo '
					<input type="checkbox" onclick="invertAll(this, this.form);" class="input_check" />';

	echo '
				</th>
			</tr>
		</thead>
		<tbody>';

	if (empty($context['rules']))
		echo '
			<tr class="windowbg2">
				<td colspan="2" align="center">
					', $txt['pm_rules_none'], '
				</td>
			</tr>';

	$alternate = false;
	foreach ($context['rules'] as $rule)
	{
		echo '
			<tr class="', $alternate ? 'windowbg' : 'windowbg2', '">
				<td>
					<a href="', $scripturl, '?action=profile;area=pm_ar;sa=filters;add;in=', $rule['id'], ';u=' . $context['id_member'] . '">', $rule['name'], '</a>
				</td>
				<td width="4%" align="center">
					<input type="checkbox" name="delrule[', $rule['id'], ']" class="input_check" />
				</td>
			</tr>';
		$alternate = !$alternate;
	}

	echo '
		</tbody>
		</table>
		<div class="righttext">
			[<a href="', $scripturl, '?action=profile;area=pm_ar;sa=filters;add;in=0;u=' . $context['id_member'] . '">', $txt['pm_add_rule'], '</a>]';

	if (!empty($context['rules']))
		echo '
			<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
			<input type="submit" name="delselected" value="', $txt['pm_delete_selected_rule'], '" onclick="return confirm(\'', $txt['pm_js_delete_rule_confirm'], '\');" class="button_submit smalltext" />';

	echo '
		</div>
	</form>';

}

// Template for adding/editing a rule.
function template_profile_pm_ar_add_rule()
{
	global $context, $settings, $options, $txt, $scripturl;

	$context['profile_javascript'] = '
			var criteriaNum = 0;
			var actionNum = 0;
			var groups = [];';

	foreach ($context['groups'] as $id => $title)
		$context['profile_javascript'] .= '
			groups[' . $id . '] = "' . addslashes($title) . '";';

	$context['profile_javascript'] .= '
			function addCriteriaOption()
			{
				if (criteriaNum == 0)
				{
					for (var i = 0; i < document.forms.creator.elements.length; i++)
						if (document.forms.creator.elements[i].id.substr(0, 8) == "ruletype")
							criteriaNum++;
				}
				criteriaNum++

				setOuterHTML(document.getElementById("criteriaAddHere"), \'<br /><select name="ruletype[\' + criteriaNum + \']" id="ruletype\' + criteriaNum + \'" onchange="updateRuleDef(\' + criteriaNum + \'); rebuildRuleDesc();"><option value="">' . addslashes($txt['pm_rule_criteria_pick']) . ':<\' + \'/option><option value="mid">' . addslashes($txt['pm_rule_mid']) . '<\' + \'/option><option value="gid">' . addslashes($txt['pm_rule_gid']) . '<\' + \'/option><option value="sub">' . addslashes($txt['pm_rule_sub']) . '<\' + \'/option><option value="msg">' . addslashes($txt['pm_rule_msg']) . '<\' + \'/option><option value="bud">' . addslashes($txt['pm_rule_bud']) . '<\' + \'/option><\' + \'/select>&nbsp;<span id="defdiv\' + criteriaNum + \'" style="display: none;"><input type="text" name="ruledef[\' + criteriaNum + \']" id="ruledef\' + criteriaNum + \'" onkeyup="rebuildRuleDesc();" value="" class="input_text" /><\' + \'/span><span id="defseldiv\' + criteriaNum + \'" style="display: none;"><select name="ruledefgroup[\' + criteriaNum + \']" id="ruledefgroup\' + criteriaNum + \'" onchange="rebuildRuleDesc();"><option value="">' . addslashes($txt['pm_rule_sel_group']) . '<\' + \'/option>';

	foreach ($context['groups'] as $id => $group)
		$context['profile_javascript'] .= '<option value="' . $id . '">' . strtr($group, array("'" => "\'")) . '<\' + \'/option>';

	$context['profile_javascript'] .= '<\' + \'/select><\' + \'/span><span id="criteriaAddHere"><\' + \'/span>\');
			}

			function updateRuleDef(optNum)
			{
				if (document.getElementById("ruletype" + optNum).value == "gid")
				{
					document.getElementById("defdiv" + optNum).style.display = "none";
					document.getElementById("defseldiv" + optNum).style.display = "";
				}
				else if (document.getElementById("ruletype" + optNum).value == "bud" || document.getElementById("ruletype" + optNum).value == "")
				{
					document.getElementById("defdiv" + optNum).style.display = "none";
					document.getElementById("defseldiv" + optNum).style.display = "none";
				}
				else
				{
					document.getElementById("defdiv" + optNum).style.display = "";
					document.getElementById("defseldiv" + optNum).style.display = "none";
				}
			}

			// Rebuild the rule description!
			function rebuildRuleDesc()
			{
				// Start with... nothing. D\'OH!
				var text = "";
				var joinText = "";
				var actionText = "";
				var hadBuddy = false;
				var foundCriteria = false;
				var foundAction = false;
				var curNum, curVal, curDef;

				for (var i = 0; i < document.forms.creator.elements.length; i++)
				{
					if (document.forms.creator.elements[i].id.substr(0, 8) == "ruletype")
					{
						if (foundCriteria)
							joinText = document.getElementById("logic").value == \'and\' ? ' . JavaScriptEscape(' ' . $txt['pm_readable_and'] . ' ') . ' : ' . JavaScriptEscape(' ' . $txt['pm_readable_or'] . ' ') . ';
						else
							joinText = \'\';
						foundCriteria = true;

						curNum = document.forms.creator.elements[i].id.match(/\d+/);
						curVal = document.forms.creator.elements[i].value;
						if (curVal == "gid")
							curDef = document.getElementById("ruledefgroup" + curNum).value.php_htmlspecialchars();
						else if (curVal != "bud")
							curDef = document.getElementById("ruledef" + curNum).value.php_htmlspecialchars();
						else
							curDef = "";

						// What type of test is this?
						if (curVal == "mid" && curDef)
							text += joinText + ' . JavaScriptEscape($txt['pm_readable_member']) . '.replace("{MEMBER}", curDef);
						else if (curVal == "gid" && curDef && groups[curDef])
							text += joinText + ' . JavaScriptEscape($txt['pm_readable_group']) . '.replace("{GROUP}", groups[curDef]);
						else if (curVal == "sub" && curDef)
							text += joinText + ' . JavaScriptEscape($txt['pm_readable_subject']) . '.replace("{SUBJECT}", curDef);
						else if (curVal == "msg" && curDef)
							text += joinText + ' . JavaScriptEscape($txt['pm_readable_body']) . '.replace("{BODY}", curDef);
						else if (curVal == "bud" && !hadBuddy)
						{
							text += joinText + ' . JavaScriptEscape($txt['pm_readable_buddy']) . ';
							hadBuddy = true;
						}
					}
				}

				// If still nothing make it default!
				if (text == "" || !foundCriteria)
					text = "' . $txt['pm_rule_not_defined'] . '";
				else
				{
					if (actionText != "")
						text += ' . JavaScriptEscape(' ' . $txt['pm_readable_then'] . ' ') . ' + actionText;
					text = ' . JavaScriptEscape($txt['pm_readable_start']) . ' + text + ' . JavaScriptEscape($txt['pm_readable_end']) . ';
				}

				// Set the actual HTML!
				//setInnerHTML(document.getElementById("ruletext"), text);
			}';

	echo '
				<dt><strong>', $txt['pm_rule_criteria'], '</strong></dt><dd>';

	// Add a dummy criteria to allow expansion for none js users.
	$context['rule']['criteria'][] = array('t' => '', 'v' => '');

	// For each criteria print it out.
	$isFirst = true;
	foreach ($context['rule']['criteria'] as $k => $criteria)
	{
		if (!$isFirst && $criteria['t'] == '')
			echo '<div id="removeonjs1">';
		elseif (!$isFirst)
			echo '<br />';

		echo '
					<select name="ruletype[', $k, ']" id="ruletype', $k, '" onchange="updateRuleDef(', $k, '); rebuildRuleDesc();">
						<option value="">', $txt['pm_rule_criteria_pick'], ':</option>
						<option value="mid" ', $criteria['t'] == 'mid' ? 'selected="selected"' : '', '>', $txt['pm_rule_mid'], ' or ID</option>
						<option value="gid" ', $criteria['t'] == 'gid' ? 'selected="selected"' : '', '>', $txt['pm_rule_gid'], '</option>
						<option value="sub" ', $criteria['t'] == 'sub' ? 'selected="selected"' : '', '>', $txt['pm_rule_sub'], '</option>
						<option value="msg" ', $criteria['t'] == 'msg' ? 'selected="selected"' : '', '>', $txt['pm_rule_msg'], '</option>
						<option value="bud" ', $criteria['t'] == 'bud' ? 'selected="selected"' : '', '>', $txt['pm_rule_bud'], '</option>
					</select>
					<span id="defdiv', $k, '" ', !in_array($criteria['t'], array('gid', 'bud')) ? '' : 'style="display: none;"', '>
						<input type="text" name="ruledef[', $k, ']" id="ruledef', $k, '" onkeyup="rebuildRuleDesc();" value="', in_array($criteria['t'], array('mid', 'sub', 'msg')) ? $criteria['v'] : '', '" class="input_text" />
					</span>
					<span id="defseldiv', $k, '" ', $criteria['t'] == 'gid' ? '' : 'style="display: none;"', '>
						<select name="ruledefgroup[', $k, ']" id="ruledefgroup', $k, '" onchange="rebuildRuleDesc();">
							<option value="">', $txt['pm_rule_sel_group'], '</option>';

		foreach ($context['groups'] as $id => $group)
			echo '
							<option value="', $id, '" ', $criteria['t'] == 'gid' && $criteria['v'] == $id ? 'selected="selected"' : '', '>', $group, '</option>';
		echo '
						</select>
					</span>';

		// If this is the dummy we add a means to hide for non js users.
		if ($isFirst)
			$isFirst = false;
		elseif ($criteria['t'] == '')
			echo '</div>';
	}

	echo '
					<span id="criteriaAddHere"></span><br />
					<a href="#" onclick="addCriteriaOption(); return false;" id="addonjs1" style="display: none;">(', $txt['pm_rule_criteria_add'], ')</a>
					</dd>
					<dt><strong>', $txt['pm_rule_logic'], ':</strong></dt><dd>
					<select name="rule_logic" id="logic" onchange="rebuildRuleDesc();">
						<option value="and" ', $context['rule']['logic'] == 'and' ? 'selected="selected"' : '', '>', $txt['pm_rule_logic_and'], '</option>
						<option value="or" ', $context['rule']['logic'] == 'or' ? 'selected="selected"' : '', '>', $txt['pm_rule_logic_or'], '</option>
					</select>
				</dd>';

	foreach ($context['rule']['criteria'] as $k => $c)
		$context['profile_javascript'] .= '
			updateRuleDef(' . $k . ');';

	$context['profile_javascript'] .= '
			rebuildRuleDesc();';

	// If this isn't a new rule and we have JS enabled remove the JS compatibility stuff.
	if ($context['in'])
		$context['profile_javascript'] .= '
			document.getElementById("removeonjs1").style.display = "none";';

	$context['profile_javascript'] .= '
			document.getElementById("addonjs1").style.display = "";';
}

?>