<?php
// Version: 1.0: ActiveMembers.php

if (!defined('WEDGE'))
	die('Hacking attempt...');

function active_members_display_main()
{
	global $active_members, $context, $settings, $topic, $txt, $topicinfo, $user_info;

	if (!allowedTo('view_active_members_any') && ($topicinfo['id_member_started'] != $user_info['id'] || !allowedTo('view_active_members_own')))
		return;

	loadPluginLanguage('live627:active_members', 'ActiveMembers');
	wetem::load('sidebar', 'display_active_members');

	$request = wesql::query('
		SELECT id_member
		FROM {db_prefix}topics AS t
			INNER JOIN {db_prefix}messages AS m ON (m.id_topic = t.id_topic)
		WHERE t.id_topic = {int:topic}',
		array(
			'topic' => $topic,
		)
	);
	$active_members = array();
	while ($row = wesql::fetch_row($request))
	{
		if (!isset($active_members[$row[0]]))
			$active_members[$row[0]] = 0;

		$active_members[$row[0]]++;
	}

	$request = wesql::query('
		SELECT id_member, real_name
		FROM {db_prefix}members
		WHERE id_member IN ({array_int:active_members})',
		array(
			'active_members' => array_unique(array_keys($active_members)),
		)
	);
	$context['active_members'] = array();
	$i = 0;
	while ($row = wesql::fetch_row($request))
		$context['active_members'][$row[0]] = '<a href="<URL>?action=profile;u=' . $row[0] . '">' . $row[1] . '</a> (' . $active_members[$row[0]] . ')';

	uksort($context['active_members'], 'active_members_cmp');

	foreach ($context['active_members'] as $k => $v)
	{
		if (!empty($settings['activemembers_num']) && $i < $settings['activemembers_num'])
			$context['active_members'][$k] = $v;
		else
			unset($context['active_members'][$k]);

		$i++;
	}

	$num_active_members = count($active_members);
	if (!empty($settings['activemembers_num']) && $num_active_members > $settings['activemembers_num'])
		$context['active_members'][] = '<a href="#" onclick="reqWin(weUrl(\'action=activemembers;topic=' . $topic. '\'), 500); return false;">' . $txt['more'] . '</a> (' . ($num_active_members - $settings['activemembers_num']) . ')';
}

function active_members_cmp($a, $b)
{
	global $active_members;

	if ($active_members[$a] == $active_members[$b])
		return 0;

	return ($active_members[$a] > $active_members[$b]) ? -1 : 1;
}

function template_display_active_members()
{
	global $context, $txt;

	echo '
	<section>
		<we:title>
			', $txt['active_members'], '
		</we:title>
		<p id="active_members">', implode(', ', $context['active_members']), '</p>
	</section>';
}

function active_members_action()
{
	global $active_members, $context, $memberContext, $txt, $topic, $topicinfo, $user_info;

	if (empty($topic))
		fatal_lang_error('no_access', false);

	if (allowedTo('view_active_members_any') || ($topicinfo['id_member_started'] == $user_info['id'] && allowedTo('view_active_members_own')))
	{
		loadPluginLanguage('live627:active_members', 'ActiveMembers');

		$request = wesql::query('
			SELECT id_member
			FROM {db_prefix}topics AS t
				INNER JOIN {db_prefix}messages AS m ON (m.id_topic = t.id_topic)
			WHERE t.id_topic = {int:topic}',
			array(
				'topic' => $topic,
			)
		);
		$active_members = array();
		while ($row = wesql::fetch_row($request))
		{
			if (!isset($active_members[$row[0]]))
				$active_members[$row[0]] = 0;

			$active_members[$row[0]]++;
		}

		$context['active_members'] = $active_members;
		$_POST['t'] = $txt['active_members'] . ' (' . count($active_members) . ')';
		uksort($context['active_members'], 'active_members_cmp');
		loadMemberData(array_keys($active_members));

		$context['help_text'] = '<style><!--
			#helf img { max-width: 40px; }
		// --></style>
		<table class="w100 cs3">';

		foreach ($context['active_members'] as $member => $num)
		{
			loadMemberContext($member);
			$context['help_text'] .= '
			<tr><td class="ava">' . $memberContext[$member]['avatar']['image'] . '</td><td class="link">' . $memberContext[$member]['link'] . '</td><td class="right">' . $num . '</td></tr>';
		}

		$context['help_text'] .= '
		</table>';

		loadTemplate('Help');
		loadLanguage('Help');
		wetem::hide();
		wetem::load('popup');
	}
}

?>