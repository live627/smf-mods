<?php
/*
	Drafts Modification for SMF 2.0/1.1

	Created by:		Charles Hill
	Website:		http://www.degreesofzero.com/

	Copyright 2008 - 2010.  All Rights Reserved.
*/

if (!defined('SMF'))
	die('Hacking attempt...');

/*
	SMF 2.0
	-------
	'htmlspecialchars' => $smcFunc['htmlspecialchars'],
	'db_fetch_row' => $smcFunc['db_fetch_row'],
	'db_fetch_assoc' => $smcFunc['db_fetch_assoc'],
	'db_free_result' => $smcFunc['db_free_result'],
	'db_num_rows' => $smcFunc['db_num_rows'],
	'db_insert_id' => $smcFunc['db_insert_id'],
	'un_htmlspecialchars' => 'un_htmlspecialchars'

	$smcFunc['db_query']($identifier, $query, $values);
*/

function drafts_is_draft_author($draft_id)
{
	if (empty($draft_id))
		return false;

	// admins can do anything or, in this case, be anything...
	if (allowedTo('admin_forum'))
		return true;

	global $context;

	if (empty($context['user']['id']) || !allowedTo('save_drafts'))
	{
		global $txt;

		fatal_error($txt['drafts'][16], false);
	}

	global $smcFunc;

	$request = $smcFunc['db_query']('', '
		SELECT draft_id
		FROM {db_prefix}drafts
		WHERE member_id = {int:member_id}
			AND draft_id = {int:draft_id}
		LIMIT 1',
		array(
			'member_id' => (int) $context['user']['id'],
			'draft_id' => (int) $draft_id
		)
	);

	if ($smcFunc['db_num_rows']($request) == 0)
	{
		$smcFunc['db_free_result']($request);

		global $txt;

		fatal_error($txt['drafts'][17], false);
	}

	$smcFunc['db_free_result']($request);
}

function drafts_save_draft()
{
	if (!empty($_POST['drafts-draft_id']))
		drafts_is_draft_author($_POST['drafts-draft_id']);

	global $board, $topic;

	// verify this user has permission to post a new topic to this board
	if (empty($topic))
		isAllowedTo('post_new', $board);

	global $context, $sourcedir, $smcFunc, $modSettings;

	$context['draft_saved'] = false;

	$values = array(
		'member_id' => (int) $context['user']['id'],
		'board_id' => (int) $board,
		'topic_id' => empty($topic) ? 0 : $topic,
		'subject' => substr($_POST['subject'], 0, 255),
		'body' => substr($_POST['message'], 0, 65535),
		'timestamp' => time(),
		'is_sticky' => isset($_POST['sticky']) && !empty($modSettings['enableStickyTopics']) ? (int) $_POST['sticky'] : 0,
		'locked' => isset($_POST['lock']) ? (int) $_POST['lock'] : 0,
		'smileys_enabled' => !isset($_POST['ns']) ? 1 : 0,
		'icon' => substr(preg_replace('~[\./\\\\*\':"<>]~', '', $_POST['icon']), 0, 16),
		'poll' => ''
	);

	if (isset($_REQUEST['poll']))
	{
		$poll = array(
			'question' => $_POST['question'],
			'choices' => $_POST['options'],
			'options' => array(
				'max_votes' => $_POST['poll_max_votes'],
				'hide' => $_POST['poll_hide'],
				'change_vote' => $_POST['poll_change_vote'],
				'expire' => isset($_POST['poll_expire']) ? $_POST['poll_expire'] : null,
				'guest_vote' => $_POST['poll_guest_vote']
			)
		);

		$values['poll'] = @serialize($poll);
	}

	// updating an existing draft?
	if (!empty($_POST['drafts-draft_id']))
	{
		$values['draft_id'] = (int) $_POST['drafts-draft_id'];

		$result = $smcFunc['db_query']('', '
			UPDATE {db_prefix}drafts
			SET board_id = {int:board_id},
				topic_id = {int:topic_id},
				is_sticky = {int:is_sticky},
				locked = {int:locked},
				smileys_enabled = {int:smileys_enabled},
				icon = {string:icon},
				body = {string:body},
				subject = {string:subject},
				timestamp = {int:timestamp},
				poll = {string:poll}
			WHERE draft_id = {int:draft_id}
				AND member_id = {int:member_id}
			LIMIT 1',
			$values
		);

		$context['draft_id'] = $values['draft_id'];

		$context['draft_saved'] = $result;
	}
	else
	{
		// we're creating a new draft then?
		$result = $smcFunc['db_query']('', '
			INSERT INTO {db_prefix}drafts
				(member_id, board_id, topic_id, body, subject, timestamp, is_sticky, locked, smileys_enabled, icon, poll)
			VALUES ({int:member_id}, {int:board_id}, {int:topic_id}, {string:body}, {string:subject}, {int:timestamp}, {int:is_sticky}, {int:locked}, {int:smileys_enabled}, {string:icon}, {string:poll})',
			$values
		);

		if (!$result)
			$context['post_error']['messages'][] = $txt['drafts'][0];
		else
		{
			$values['draft_id'] = $smcFunc['db_insert_id']('{db_prefix}drafts', 'draft_id');

			// something went wrong
			if (empty($values['draft_id']))
				$context['post_error']['messages'][] = $txt['drafts'][0];
			else
				$context['draft_saved'] = true;
		}
	}

	drafts_prepare_draft_context($values);
}

function drafts_delete_draft($draft_id)
{
	if (empty($draft_id))
		return false;

	drafts_is_draft_author($draft_id);

	global $context;

	if (empty($context['user']['id']))
		return false;

	global $smcFunc;

	$result = $smcFunc['db_query']('', '
		DELETE FROM {db_prefix}drafts
		WHERE member_id = {int:member_id}
			AND draft_id = {int:draft_id}
		LIMIT 1',
		array(
			'member_id' => (int) $context['user']['id'],
			'draft_id' => (int) $draft_id
		)
	);

	return $result;
}

function drafts_load_list_of_drafts($member_id = null)
{
	global $context;

	$member_id = $member_id === null ? $context['user']['id'] : $member_id;

	if (empty($member_id))
		return;

	global $smcFunc, $scripturl;

	// get drafts from the db
	$request = $smcFunc['db_query']('', '
		SELECT
			d.draft_id, d.board_id, d.topic_id, d.subject, d.timestamp, d.poll,
			b.name AS board_name,
			msg.subject AS topic_subject
		FROM {db_prefix}drafts AS d
			LEFT JOIN {db_prefix}boards AS b ON (b.id_board = d.board_id)
			LEFT JOIN {db_prefix}topics AS t ON (t.id_topic = d.topic_id)
			LEFT JOIN {db_prefix}messages AS msg ON (msg.id_msg = t.id_first_msg)
		WHERE d.member_id = {int:member_id}
		ORDER BY d.timestamp DESC',
		array(
			'member_id' => (int) $member_id
		)
	);

	$list_of_drafts = array();

	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		$row['subject'] = un_htmlspecialchars($row['subject']);
		$truncate_subject = drafts_truncate($row['subject'], 20);

		if (strlen($truncate_subject) < strlen($row['subject']))
			$row['subject'] .= ' ...';

		$list_of_drafts[$row['draft_id']] = array(
			'id' => $row['draft_id'],
			'board' => array(
				'id' => $row['board_id'],
				'name' => $row['board_name']
			),
			'topic' => array(
				'id' => $row['topic_id'],
				'subject' => !empty($row['topic_id']) ? $row['topic_subject'] : ''
			),
			'subject' => $smcFunc['htmlspecialchars']($row['subject'], ENT_QUOTES),
			'last_saved' => timeformat($row['timestamp']),
			'edit' => $scripturl . '?action=post;board=' . $row['board_id'] . '.0;' . (!empty($row['poll']) ? 'poll;' : '') . (!empty($row['topic_id']) ? 'topic=' . $row['topic_id'] . '.0;' : '') . 'draft=' . $row['draft_id'],
			'post' => $scripturl . '?action=profile;area=show_drafts;u=' . $member_id . ';postDraft='. $row['draft_id'] . ';sesc=' . $context['session_id']
		);
	}

	$smcFunc['db_free_result']($request);

	return $list_of_drafts;
}

function drafts_load_draft()
{
	if (empty($_REQUEST['draft']))
		return false;

	global $context, $smcFunc, $board, $topic;

	$request = $smcFunc['db_query']('', '
		SELECT draft_id, subject, body, board_id, topic_id, is_sticky, locked, smileys_enabled, icon, poll
		FROM {db_prefix}drafts
		WHERE member_id = {int:member_id}
			AND draft_id = {int:draft_id}
			AND board_id = {int:board_id}
			AND topic_id = {int:topic_id}
		LIMIT 1',
		array(
			'member_id' => (int) $context['user']['id'],
			'draft_id' => (int) $_REQUEST['draft'],
			'board_id' => (int) $board,
			'topic_id' => (int) $topic
		)
	);

	if ($smcFunc['db_num_rows']($request) == 0)
	{
		global $txt;

		fatal_error($txt['drafts'][18], false);
	}

	while ($row = $smcFunc['db_fetch_assoc']($request))
		drafts_prepare_draft_context($row);

	$smcFunc['db_free_result']($request);
}

function drafts_prepare_draft_context($draft_info)
{
	global $context, $sourcedir;

	// only do this stuff if this is a draft for a topic
	if (empty($draft_info['topic_id']))
	{
		drafts_load_list_of_boards();

		$context['sticky'] = $draft_info['is_sticky'];
		$context['locked'] = $draft_info['locked'];

		if (!empty($draft_info['poll']) && isset($_REQUEST['poll']))
		{
			global $board;

			$poll = !is_array($draft_info['poll']) ? @unserialize($draft_info['poll']) : $draft_info['poll'];

			$context['question'] = $poll['question'];

			require_once($sourcedir . '/Subs-Members.php');
			$allowedVoteGroups = groupsAllowedTo('poll_vote', $board);

			$context['poll_options'] = array(
				'max_votes' => empty($poll['options']['max_votes']) ? 1 : max(1, $poll['options']['max_votes']),
				'hide' => empty($poll['options']['hide']) ? 0 : $poll['options']['hide'],
				'change_vote' => !empty($poll['options']['change_vote']),
				'expire' => empty($poll['options']['expire']) ? '' : $poll['options']['expire'],
				'guest_vote' => isset($poll['options']['guest_vote']),
				'guest_vote_enabled' => in_array(-1, $allowedVoteGroups['allowed'])
			);

			unset($allowedVoteGroups);

			$context['choices'] = array();

			if (!empty($poll['choices']))
			{
				$n = count($poll['choices']);
				$i = 0;

				foreach ($poll['choices'] as $choice)
					$context['choices'][] = array(
						'id' => $i++,
						'label' => $choice,
						'number' => $i,
						'is_last' => $i == $n
					);

				unset($n, $i, $choice);
			}

			unset($poll);

			// so the post form knows this is a poll
			$context['make_poll'] = true;
		}
	}

	$context['use_smileys'] = !empty($draft_info['smileys_enabled']);
	$context['icon'] = !empty($draft_info['icon']) ? $draft_info['icon'] : '';

	require_once($sourcedir . '/Subs-Post.php');

	// reverse what preparsecode() does last before we use un_preparsecode()
	// not quite sure why un_preparsecode() doesn't already do this...
	$context['message'] = strtr($draft_info['body'], array('&#91;]' => '[]', '&#91;&#039;' => '[&#039;'));

	$context['message'] = un_preparsecode($context['message']);
	$context['subject'] = $draft_info['subject'];

	$context['draft_id'] = $draft_info['draft_id'];

}

function drafts_truncate($string, $length, $break_with = ' ')
{
	if (empty($length) || strlen($string) < $length)
		return $string;

	$break_point = strpos($string, $break_with, $length);

	if ($break_point === false)
		return $string;

	return substr($string, 0, $break_point);
}

function drafts_get_num_drafts($member_id = null)
{
	global $smcFunc, $context, $txt;

	$request = $smcFunc['db_query']('', '
		SELECT count(draft_id)
		FROM {db_prefix}drafts
		WHERE member_id = {int:member_id}',
		array(
			'member_id' => $member_id === null ? (int) $context['user']['id'] : $member_id
		)
	);

	list ($num_drafts) = $smcFunc['db_fetch_row']($request);

	$smcFunc['db_free_result']($request);

	return $num_drafts;
}

function drafts_profile_show_drafts($member_id)
{
	global $context;

	loadTemplate('Drafts');

	global $txt, $scripturl, $smcFunc;

	// are we deleting drafts?
	if (!empty($_POST['drafts-delete']))
	{
		// gotta check the session ID
		checkSession('post');

		$delete_drafts = array();

		// sanitize draft IDs
		foreach ($_POST['drafts-delete'] as $draft_id)
			$delete_drafts[] = (int) $draft_id;

		// delete the drafts from the db
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}drafts
			WHERE draft_id IN ({array_int:delete_drafts})
				AND member_id = {int:member_id}
			LIMIT {int:limit}',
			array(
				'member_id' => $member_id,
				'delete_drafts' => $delete_drafts,
				'limit' => count($delete_drafts)
			)
		);

		// send them back to where they came from
			redirectexit(preg_replace('~;draft=([0-9]+)~', '', str_replace('action=post2;', 'action=post;', $_SESSION['old_url'])));
	}

	// are we posting a draft as a topic?
	if (isset($_REQUEST['postDraft']))
	{
		// gotta check the session ID
		checkSession('get');

		// sanitize the draft ID
		$draft_id = (int) $_REQUEST['postDraft'];

		// get some info about this draft
		$request = $smcFunc['db_query']('', '
			SELECT
				d.body, d.subject, d.board_id, d.topic_id, d.icon, d.smileys_enabled, d.locked, d.is_sticky, d.poll,
				b.count_posts
			FROM {db_prefix}drafts AS d
				LEFT JOIN {db_prefix}boards AS b ON (b.id_board = d.board_id)
			WHERE d.draft_id = {int:draft_id}
			LIMIT 1',
			array(
				'draft_id' => $draft_id
			)
		);

		// it doesn't exist
		if ($smcFunc['db_num_rows']($request) == 0)
			fatal_error($txt['drafts'][19], false);

		// this draft does exist... so we can keep doing stuff... yay I like doing stuff :)
		while ($row = $smcFunc['db_fetch_assoc']($request))
		{
			// verify this user has permission to post a new topic to this board
			if (empty($row['topic_id']))
				isAllowedTo('post_new', $row['board_id']);

			global $sourcedir;

			require_once($sourcedir . '/Subs-Post.php');

			$posterOptions = array();
			$msgOptions = array();
			$topicOptions = array();

			// remember, can only post polls as a new topic
			if (empty($row['topic_id']) && !empty($row['poll']))
			{
				$poll = @unserialize($row['poll']);

				// Create the poll.
				$result = $smcFunc['db_query']('', '
					INSERT INTO {db_prefix}polls
						(question, hide_results, max_votes, expire_time, id_member, poster_name, change_vote, guest_vote)
					VALUES ({string:question}, {int:hide}, {int:max_votes}, {int:expire}, {int:member_id}, {string:poster_name}, {int:change_vote}, {int:guest_vote})',
					array(
						'question' => substr($poll['question'], 0, 255),
						'hide' => $poll['options']['hide'],
						'max_votes' => $poll['options']['max_votes'],
						'expire' => empty($poll['options']['expire']) ? 0 : time() + $poll['options']['expire'] * 3600 * 24,
						'member_id' => $member_id,
						'poster_name' => substr(addslashes($context['user']['username']), 0, 255),
						'change_vote' => $poll['options']['change_vote'],
						'guest_vote' => $poll['options']['guest_vote']
					)
				);

				if (!$result)
					fatal_error($txt['drafts'][20], false);

				$poll_id = $smcFunc['db_insert_id']('{db_prefix}polls', 'poll_id');

				if (empty($poll_id))
					fatal_error($txt['drafts'][20], false);

				$i = 0;
				$insert = array();
				$values = array('poll_id' => $poll_id);

				// Create each answer choice.
				foreach ($poll['choices'] as $k => $choice)
				{
					$insert[] = '({int:poll_id}, {int:choice_id_' . $k . '}, {string:label_' . $k . '})';

					$values += array(
						'choice_id_' . $k => $i++,
						'label_' . $k => substr($choice, 0, 255)
					);
				}

				unset($i, $choice);

				$result = $smcFunc['db_query']('', '
					INSERT INTO {db_prefix}poll_choices
						(id_poll, id_choice, label)
					VALUES
						' . implode(',
						', $insert),
					$values
				);

				if (!$result)
				{
					// delete the poll we just created
					$smcFunc['db_query']('', '
						DELETE FROM {db_prefix}polls
						WHERE id_poll = {int:poll_id}
						LIMIT 1',
						array(
							'poll_id' => $poll_id
						)
					);

					fatal_error($txt['drafts'][20], false);
				}

				$topicOptions['poll'] = $poll_id;

				unset($insert, $poll_id, $poll);
			}

			// let's set some variables before we create the post
			$posterOptions['id'] = $member_id;

			$msgOptions['body'] = $row['body'];
			$msgOptions['subject'] = $row['subject'];
			$msgOptions['icon'] = $row['icon'];
			$msgOptions['smileys_enabled'] = $row['smileys_enabled'];

			$topicOptions['board'] = $row['board_id'];
			$topicOptions['id'] = $row['topic_id'];

			$is_topic = empty($topicOptions['id']);

			if ($is_topic)
			{
				$topicOptions['lock_mode'] = $row['locked'];
				$topicOptions['sticky_mode'] = $row['is_sticky'];
			}

			// tells createPost() to update the poster's post count
			$posterOptions['update_post_count'] = empty($row['count_posts']);

			// tells createPost() to mark the new topic/reply as read for the poster
			$topicOptions['mark_as_read'] = true;

			$smcFunc['db_free_result']($request);

			if (createPost($msgOptions, $topicOptions, $posterOptions))
			{
				global $board, $modSettings;

				$board = $topicOptions['board'];

				// delete the draft that was used
				$smcFunc['db_query']('', '
					DELETE FROM {db_prefix}drafts
					WHERE draft_id = {int:draft_id}
						AND member_id = {int:member_id}
					LIMIT 1',
					array(
						'draft_id' => $draft_id,
						'member_id' => $member_id
					)
				);

				if (!empty($topicOptions['lock_mode']))
					logAction('lock', array('topic' => $topicOptions['id']));

				if (!empty($topicOptions['sticky_mode']) && !empty($modSettings['enableStickyTopics']))
					logAction('sticky', array('topic' => $topicOptions['id']));

				require_once($sourcedir . '/Post.php');

				// Notify members of a new topic posted to this board
				if ($is_topic)
				{
					global $user_info;

					$notifyData = array(
						'body' => $row['body'],
						'subject' => $row['subject'],
						'name' => $user_info['name'],
						'poster' => $user_info['id'],
						'msg' => $msgOptions['id'],
						'board' => $topicOptions['board'],
						'topic' => $topicOptions['id']
					);

					notifyMembersBoard($notifyData);
				}
				// Notify members of a new reply posted to this topic
				else
					sendNotifications($topicOptions['id'], 'reply');

				redirectexit('board=' . $topicOptions['board'] . ';topic=' . $topicOptions['id'] . (!$is_topic ? '.msg' . $msgOptions['id'] . '#msg' . $msgOptions['id'] : '.0'));
			}

			// something went wrong
			fatal_error($txt['drafts'][20]);
		}
	}

	$context['list_of_drafts'] = drafts_load_list_of_drafts($member_id);

	$context['sub_template'] = 'show_drafts';
}

function drafts_load_list_of_boards()
{
	global $topic, $context;

	$context['list_of_boards'] = array();

	// has to be a new topic
	if (!empty($topic))
		return;

	// Get list of boards that can be posted in.
	$boards = boardsAllowedTo('post_new');

	if (empty($boards))
		fatal_lang_error('cannot_post_new');

	global $smcFunc;

	$request = $smcFunc['db_query']('', '
		SELECT
			b.id_board, b.name AS board_name, b.child_level,
			c.name AS cat_name, c.id_cat
		FROM {db_prefix}boards AS b
			LEFT JOIN {db_prefix}categories AS c ON (c.id_cat = b.id_cat)
		WHERE {query_see_board}' . (in_array(0, $boards) ? '' : '
			AND b.id_board IN ({array_int:boards})'),
		array(
			'boards' => $boards
		)
	);

	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		if (!isset($context['list_of_boards'][$row['id_cat']]))
			$context['list_of_boards'][$row['id_cat']] = array('name' => $row['cat_name'], 'boards' => array());

		$context['list_of_boards'][$row['id_cat']]['boards'][$row['id_board']] = array(
			'id' => $row['id_board'],
			'name' => $row['board_name'],
			'child_level' => $row['child_level'],
			'prefix' => str_repeat('&nbsp;', ($row['child_level'] + 1) * 3)
		);
	}

	$smcFunc['db_free_result']($request);
}

?>