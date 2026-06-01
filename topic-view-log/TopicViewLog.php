<?php
// Version: 2.0: TopicViewLog.php
// Licence: CC-BY-NC-SA

if (!defined('SMF'))
	die('Hacking attempt...');

function tvl_actions(&$action_array)
{
	$action_array['topicviewlog'] = array('TopicViewLog.php', 'TopicViewLog');
}

function tvl_whos_online_after(/*string|array*/ &$urls, array &$data): void
{
	global $scripturl, $smcFunc, $txt, $user_info;

	loadLanguage('TopicViewLog');
	$requested_data = [];
	$requested_ids = [];

	// Fix the anomaly where $urls is a string when
	// coming from the profile section.
	foreach (!is_array($urls) ? [[$urls, 0]] : $urls as $k => $url)
	{
		// Get the request parameters..
		$actions = $smcFunc['json_decode']($url[0], true);

		if ($actions === [])
			continue;

		if (isset($actions['topic'], $actions['action']) && $actions['action'] == 'topicviewlogs')
		{
			$requested_ids[] = (int) $actions['topic'];
			$requested_data[$k] = (int) $actions['topic'];
		}
	}

	if ($requested_ids === [])
		return;

	$result = $smcFunc['db_query']('', '
		SELECT t.id_topic, m.subject, id_member
		FROM {db_prefix}topics AS t
			INNER JOIN {db_prefix}messages AS m ON (m.id_msg = t.id_first_msg)
		WHERE {query_see_topic_board}
			AND t.id_topic IN ({array_int:requested_ids})' . ($modSettings['postmod_active'] ? '
			AND t.approved = 1'),
		[
			'requested_ids' => array_unique($requested_ids);
		]
	);

	$topics = [];

	while ([$id_topic, $subject, $id_member] = $smcFunc['db_fetch_row']($request))
	{
		if (allowedTo('tvl_view_any') || (allowedTo('tvl_view_own') && $id_member == $user_info['id']))
			$topics[$id_topic] = $subject;
	}

	$smcFunc['db_free_result']($request);

	foreach ($requested_data as $k => $topic_id)
	{
		if (isset($topics[$topic_id]))
		{
			$data[$k] = sprintf(
				$txt['who_topiclog'],
				$scripturl,
				$topic_id,
				$topics[$topic_id]
			);
		}
	}
}

/**
 * Hook for who's online - adds topic log action
 */
function tvl_who(&$actions)
{
	return;
	global $scripturl, $txt, $smcFunc;

	// Check for topicviewlog action
	if (isset($actions['action']) && $actions['action'] == 'topicviewlog')
	{
		if (allowedTo('tvl_view_any'))
		{
			loadLanguage('TopicViewLog');
			
			// Get topic information
			$request = $smcFunc['db_query']('', '
				SELECT id_topic, subject
				FROM {db_prefix}topics AS t
					INNER JOIN {db_prefix}messages AS m ON (m.id_msg = t.id_first_msg)
				WHERE id_topic = {int:topic}
				LIMIT 1',
				array('topic' => (int) $actions['topic'])
			);
			
			if ($smcFunc['db_num_rows']($request) > 0)
			{
				$row = $smcFunc['db_fetch_assoc']($request);
				$actions['label'] = sprintf($txt['who_topiclog'], $row['id_topic'], censorText($row['subject']));
			}
			$smcFunc['db_free_result']($request);
		}
		else
		{
			loadLanguage('TopicViewLog');
			$actions['label'] = $txt['who_hidden'];
		}
	}
}

function tvl_load_permissions(&$permissionGroups, &$permissionList)
{
	global $context;

	loadLanguage('TopicViewLog');
	$permissionList['board']['tvl_view'] = array(true, 'topic', 'moderate');
	$context['non_guest_permissions'][] = 'tvl_view';
}

function tvl_display_button(&$buttons)
{
	global $context, $scripturl, $txt, $user_info;

	if (!$user_info['is_guest'])
		tvl_log();

	// Check permissions for viewing topic log
	$context['can_view_topic_log'] = allowedTo('tvl_view_any') || (allowedTo('tvl_view_own') && $context['user']['started']);

	loadLanguage('TopicViewLog');
	$buttons['topiclog'] = array(
		'test' => 'can_view_topic_log',
		'text' => 'tvl_title',
		'image' => 'topiclog.gif',
		'lang' => true,
		'url' => $scripturl . '?action=topicviewlog;topic=' . $context['current_topic'],
		'show' => true,
	);
}

/**
 * Main action handler for topicviewlog
 */
function TopicViewLog()
{
	global $smcFunc, $context, $user_info, $scripturl, $sourcedir, $txt, $topic;

	if (empty($topic))
		fatal_lang_error('no_board', false);

	loadLanguage('TopicViewLog');

	$request = $smcFunc['db_query']('', '
		SELECT id_member_started
		FROM {db_prefix}topics
		WHERE id_topic = {int:topic} LIMIT 1',
		array('topic' => $topic)
	);
	list ($starter) = $smcFunc['db_fetch_row']($request);
	$smcFunc['db_free_result']($request);

	// Permission check
	if (!allowedTo('tvl_view_any') && $user_info['id'] == $starter)
		isAllowedTo('tvl_view_own');
	else
		isAllowedTo('tvl_view_any');

	$context['page_title'] = $txt['tvl_title'];

	require_once($sourcedir . '/Subs-List.php');

	$listOptions = array(
		'id' => 'tvl_list',
		'items_per_page' => 30,
		'base_href' => $scripturl . '?action=topicviewlog;topic=' . $topic,
		'default_sort_col' => 'time',
		'get_items' => array(
			'function' => 'list_get_tvl_members',
		),
		'get_count' => array(
			'function' => 'list_get_tvl_num_members',
		),
		'columns' => array(
			'name' => array(
				'header' => array(
					'value' => $txt['name'],
				),
				'data' => array(
					'sprintf' => array(
						'format' => '<a href="' . strtr($scripturl, array('%' => '%%')) . '?action=profile;u=%1$d">%2$s</a>',
						'params' => array(
							'id_member' => false,
							'real_name' => false,
						),
					),
					'style' => 'width: 25%;',
				),
				'sort' => array(
					'default' => 'real_name',
					'reverse' => 'real_name DESC',
				),
			),
			'group' => array(
				'header' => array(
					'value' => $txt['position'],
				),
				'data' => array(
					'db' => 'group_name',
					'style' => 'width: 25%;',
				),
				'sort' =>  array(
					'default' => 'group_name',
					'reverse' => 'group_name DESC',
				),
			),
			'posts' => array(
				'header' => array(
					'value' => $txt['posts'],
				),
				'data' => array(
					'db' => 'topic_posts',
					'style' => 'width: 10%; text-align: center;',
				),
				'sort' =>  array(
					'default' => 'topic_posts',
					'reverse' => 'topic_posts DESC',
				),
			),
			'time' => array(
				'header' => array(
					'value' => $txt['tvl_times'],
				),
				'data' => array(
					'function' => function($rows)
					{
						return timeformat($rows['time']);
					},
					'style' => 'width: 30%;',
				),
				'sort' =>  array(
					'default' => 'time',
					'reverse' => 'time DESC',
				),
			),
		),
	);

	createList($listOptions);

	$context['sub_template'] = 'show_list';
	$context['default_list'] = 'tvl_list';
}

/**
 * Get topic view log members for listing
 */
function list_get_tvl_members($start, $items_per_page, $sort)
{
	global $smcFunc, $topic;

	$request = $smcFunc['db_query']('', '
		SELECT
			mem.id_member, mem.member_name, mem.real_name, mg.group_name,
			tvl.time, COUNT(m.id_msg) AS topic_posts
		FROM {db_prefix}log_topic_view AS tvl
			LEFT JOIN {db_prefix}members AS mem ON (mem.id_member = tvl.id_member)
			LEFT JOIN {db_prefix}membergroups AS mg ON (mg.id_group = CASE WHEN mem.id_group = {int:regular_id_group} THEN mem.id_post_group ELSE mem.id_group END)
			LEFT JOIN {db_prefix}messages AS m ON (m.id_member = mem.id_member AND m.id_topic = tvl.id_topic)
		WHERE tvl.id_topic = {int:topic}
		ORDER BY {raw:sort}
		LIMIT {int:start}, {int:per_page}',
		array(
			'sort' => $sort,
			'start' => $start,
			'per_page' => $items_per_page,
			'topic' => $topic,
			'regular_id_group' => 0,
		)
	);

	$rows = array();
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$rows[] = $row;
	$smcFunc['db_free_result']($request);

	return $rows;
}

/**
 * Get total number of topic view log entries
 */
function list_get_tvl_num_members()
{
	global $smcFunc, $topic;

	$request = $smcFunc['db_query']('', '
		SELECT COUNT(*)
		FROM {db_prefix}log_topic_view
		WHERE id_topic = {int:topic}',
		array('topic' => $topic)
	);
	list ($num_rows) = $smcFunc['db_fetch_row']($request);
	$smcFunc['db_free_result']($request);

	return $num_rows;
}

/**
 * Log a topic view for the current user
 */
function tvl_log()
{
	global $smcFunc, $user_info, $topic;

	if (empty($topic) || $user_info['is_guest'])
		return false;

	$smcFunc['db_insert']('',
		'{db_prefix}log_topic_view',
		array('id_member' => 'int', 'id_topic' => 'int', 'time' => 'int'),
		array($user_info['id'], $topic, time()),
		array('id_member', 'id_topic')
	);
}
