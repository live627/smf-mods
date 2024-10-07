<?php
// Version: 1.0.1: PostNotifier.php
// Licence: MIT

if (!defined('SMF'))
	die('Hacking attempt...');

function post_notifier_load_theme()
{
	global $context, $settings, $topic;

	if (!$context['user']['is_guest'] && !empty($topic))
	{
		loadTemplate(false, 'postnotifier');
		$context['html_headers'] .= '
	<script type="text/javascript" src="' . $settings['default_theme_url'] . '/scripts/postnotifier.js"></script>';
		if (!isset($_REQUEST['xml']))
			array_splice($context['template_layers'], 1, 0, 'post_notifier');
	}
}

function post_notifier_actions(&$actionArray)
{
	$actionArray['postnotifier'] = array('PostNotifier.php', 'PostNotifier');
}

function PostNotifier()
{
	global $board, $context, $modSettings, $smcFunc, $topic, $txt, $user_info;

	if (empty($topic) || empty($_REQUEST['last_msg']))
		obExit(false);

	$request = $smcFunc['db_query']('', '
		SELECT
			COUNT(id_msg)
		FROM {db_prefix}messages
		WHERE
			id_topic = {int:current_topic}
			AND id_msg > {int:topic_last_message}' . ($modSettings['postmod_active'] && !allowedTo('approve_posts') ? '
			AND approved = {int:is_approved}' : '') . '
		LIMIT 1',
		array(
			'current_board' => $board,
			'current_member' => $user_info['id'],
			'topic_last_message' => $_REQUEST['last_msg'],
			'current_topic' => $topic,
			'is_approved' => 1,
		)
	);
	list ($num) = $smcFunc['db_fetch_row']($request);
	$smcFunc['db_free_result']($request);

	loadLanguage('PostNotifier');
	if (isset($txt['post_notifier_' . $num]))
		$message = sprintf($txt['post_notifier_' . $num], $num);
	else
		$message = sprintf($txt['post_notifier_0'], $num);

	$context['xml_data']['messages'] = array(
		'identifier' => 'message',
		'children' => array(
			array(
				'attributes' => array(
					'num' => $num,
				),
				'value' => $message,
			),
		),
	);
	$context['sub_template'] = 'generic_xml';
}

function template_post_notifier_above()
{
	global $context;

	echo '
	<script type="text/javascript">
		var iLastMsg = ' . $context['topic_last_message'] . ';
	</script>
	<div id="topbar">
		&nbsp;
	</div>';
}

function template_post_notifier_below() {}
