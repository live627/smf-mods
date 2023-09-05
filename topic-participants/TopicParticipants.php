<?php

/**
 * @package TopicParticipants
 * @version 1.0
 * @author John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2019, John Rayes
 * @license http://opensource.org/licenses/MIT MIT
 */

function topic_participants_display_message_list(&$messages, &$posters)
{
	global $context, $smcFunc, $topic, $modSettings, $topicinfo, $user_info;

	if (allowedTo('view_topic_participants_any') || ($context['topicinfo']['id_member_started'] == $user_info['id'] && allowedTo('view_topic_participants_own')))
	{
		$request = $smcFunc['db_query']('', '
			SELECT id_member
			FROM {db_prefix}topics
				JOIN {db_prefix}messages USING (id_topic)
			WHERE id_topic = {int:current_topic}',
			array(
				'current_topic' => $topic,
			)
		);
		$context['topic_participants'] = array();
		while (list ($id_member) = $smcFunc['db_fetch_row']($request))
			if ($id_member != 0)
			{
				if (!isset($context['topic_participants'][$id_member]))
					$context['topic_participants'][$id_member] = 0;

				$context['topic_participants'][$id_member]++;
				$posters[] = $id_member;
			}

		arsort($context['topic_participants']);

		loadLanguage('TopicParticipants');
		$context['template_layers'][] = 'display_topic_participants';
	}
}

function topic_participants_actions(&$action_array)
{
	$action_array['topicparticipants'] = array('TopicParticipants.php', 'TopicParticipants');
}

function topic_participants_load_permissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
	loadLanguage('TopicParticipants');
	$permissionList['board'] += array(
		'view_topic_participants' => array(true, 'topic', 'make_posts'),
	);
}

function topic_participants_general_mod_settings(&$config_vars)
{
	global $txt;

	loadLanguage('TopicParticipants');
	$config_vars = array_merge($config_vars, array(
		'',
		array('int', 'topic_participants_num', 'subtext' => $txt['topic_participants_num_subtext']),
	));
}

function template_display_topic_participants_above() {}
function template_display_topic_participants_below()
{
	global $context, $modSettings, $scripturl, $txt, $user_profile;

	echo '
		<div class="cat_bar">
			<h3 class="catbg">
				', $txt['topic_participants'], '
			</h3>
		</div>
		<div class="windowbg">
			<span class="topslice"><span></span></span>
			<div class="content">';

	$num_active_members = count($context['topic_participants']);
	$i = 0;
	foreach ($context['topic_participants'] as $member => $num)
	{
		echo $user_profile[$member]['real_name'], ' (', $num, ')';
		$i++;

		if ((!empty($modSettings['topic_participants_num']) && $i >= $modSettings['topic_participants_num']) || $i >= $num_active_members)
			break;
		else
			echo ', ';
	}

	if (!empty($modSettings['topic_participants_num']) && $num_active_members > $modSettings['topic_participants_num'])
		echo ', <a href="', $scripturl, '?action=topicparticipants;topic=', $context['current_topic'], '">', $txt['more'], '</a> (', ($num_active_members - $modSettings['topic_participants_num']), ')';

	echo '
			</div>
			<span class="botslice"><span></span></span>
		</div>';
}

function TopicParticipants()
{
	global $context, $memberContext, $smcFunc, $topic, $topicinfo, $user_info;

	if ($topic != 0 && (allowedTo('view_topic_participants_any') || ($topicinfo['id_member_started'] == $user_info['id'] && allowedTo('view_topic_participants_own'))))
	{
		$request = $smcFunc['db_query']('', '
			SELECT id_member
			FROM {db_prefix}topics
				JOIN {db_prefix}messages USING (id_topic)
			WHERE id_topic = {int:current_topic}',
			array(
				'current_topic' => $topic,
			)
		);
		$context['topic_participants'] = array();
		while (list ($id_member) = $smcFunc['db_fetch_row']($request))
			if ($id_member != 0)
			{
				if (!isset($context['topic_participants'][$id_member]))
					$context['topic_participants'][$id_member] = 0;

				$context['topic_participants'][$id_member]++;
			}

		arsort($context['topic_participants']);
		loadMemberData(array_keys($context['topic_participants']));

		$context['help_text'] = '<style><!--
			#helf img { max-width: 40px; }
		// --></style>
		<table class="w100 cs3">';

		foreach ($context['topic_participants'] as $member => $num)
		{
			loadMemberContext($member);
			$context['help_text'] .= '
			<tr><td class="ava">' . $memberContext[$member]['avatar']['image'] . '</td><td class="link">' . $memberContext[$member]['link'] . '</td><td class="right">' . $num . '</td></tr>';
		}

		$context['help_text'] .= '
		</table>';

		loadTemplate('TopicParticipants');
		loadLanguage('TopicParticipants');
	}
	else
		fatal_lang_error('no_access', false);
}
