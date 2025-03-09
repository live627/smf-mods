<?php

class TopicDescriptions
{
	private static string $descriptiun = '';

	/**
	 * Called by:
	 *        integrate_general_mod_settings
	 */
	public static function settings(array &$config_vars): void
	{
		global $txt;

		loadLanguage('TopicDescriptions');

		$config_vars = array_merge(
			$config_vars,
			[
				'',
				$txt['topic_descriptions'],
				['check', 'topic_descriptions_enable'],
				[
					'select',
					'topic_descriptions_where',
					[$txt['topic_descriptions_below'], $txt['topic_descriptions_right']]
				],
				'',
				['check', 'topic_descriptions_topics'],
				['int', 'topic_descriptions_maxlen', 4],
			]);
	}

	/**
	 * Called by:
	 *        integrate_load_permissions
	 */
	public static function load_permissions(array &$permissionGroups, array &$permissionList, array &$leftPermissionGroups, array &$hiddenPermissions, array &$relabelPermissions): void
	{
		loadLanguage('TopicDescriptions');

		$permissionList['board'] += [
			'view_topic_descriptions' => [true, 'topic', 'make_posts'],
			'view_topic_description' => [true, 'topic', 'make_posts'],
		];
	}

	public static function message_index(array &$message_index_selects): void
	{
		$message_index_selects[] = 'COALESCE(t.description, "") AS description';

		loadLanguage('TopicDescriptions');
	}

	public static function display_topic(array &$topic_selects): void
	{
		$topic_selects[] = 'COALESCE(t.description, "") AS description';

		loadLanguage('TopicDescriptions');
	}

	public static function before_create_topic(array &$msgOptions, array &$topicOptions, array &$posterOptions, &$topic_columns, &$topic_parameters): void
	{
		$topic_columns['description'] = 'string';
		$topic_parameters[] = $msgOptions['description'] ?? self::$description;
	}

	/**
	 * Called by:
	 *        integrate_modify_post
	 */
	public static function modify_post(array &$messages_columns, array &$update_parameters, array &$msgOptions, array &$topicOptions, array &$posterOptions, array &$messageInts): void
	{
		global $smcFunc;

		if (isset($msgOptions['description'])) {
			self::$description = $msgOptions['description'];
		}

		$smcFunc['db_query']('', '
			UPDATE {db_prefix}topics
			SET description = {string:description}
			WHERE id_first_msg = {int:id_first_msg}',
			[
				'id_first_msg' => $msgOptions['id'],
				'description' => self::$description,
			]
		);
	}

	/**
	 * Called by:
	 *        integrate_post_end
	 */
	public static function post_end(): void
	{
		global $context, $modSettings, $smcFunc, $topic, $txt;

		loadLanguage('TopicDescriptions');
		$context['can_see_description'] = $context['is_first_post'] && (allowedTo('view_topic_descriptions_any') || ($context['topicinfo']['id_member_started'] == $user_info['id'] && allowedTo('view_topic_descriptions_own')));

		if ($context['can_see_description']) {
			if (!empty($context['editing'])) {
				$request = $smcFunc['db_query']('', '
					SELECT
						COALESCE(description, "")
					FROM {db_prefix}topics
					WHERE id_topic = {int:current_topic}',
					[
						'current_topic' => $topic,
					]
				);
				[$description] = $smcFunc['db_fetch_row']($request);
				$smcFunc['db_free_result']($request);
			}

			$context['posting_fields']['description'] = [
				'label' => [
					'text' => $txt['topic_descriptions_post_desc'],
					'class' => isset($context['post_error']['description']) ? 'error' : '',
				],
				'input' => [
					'type' => 'text',
					'attributes' => [
						'size' => 80,
						'maxlength' => min(!empty($modSettings['topic_descriptions_maxlen']) ? (int) $modSettings['topic_descriptions_maxlen'] : 25, 140),
						'value' => $description,
					],
				],
			];
		}
	}

	/**
	 * Called by:
	 *        integrate_post2_pre
	 */
	public static function post2_pre(array &$post_errors): void
	{
		global $modSettings, $smcFunc, $txt;

		if (isset($_POST['description']) && $smcFunc['htmltrim']($_POST['description']) !== '') {
			$_POST['description'] = strtr($smcFunc['htmlspecialchars']($_POST['description']), ["\r" => '', "\n" => '', "\t" => '']);

			$max_descriptionLength = min(!empty($modSettings['topic_descriptions_maxlen']) ? (int) $modSettings['topic_descriptions_maxlen'] : 25, 140);
			if ($smcFunc['strlen']($_POST['description']) > $max_descriptionLength) {
				$txt['error_long_description'] = sprintf($txt['error_long_description'], $max_descriptionLength);
				$post_errors[] = 'long_description';
				unset($_POST['description']);
			}

			// Maximum number of bytes allowed.
			self::$description = $smcFunc['truncate']($_POST['description'], 0, 255);
		}
	}

	/**
	 * Called by:
	 *        integrate_mod_buttons
	 */
	public static function mod_buttons(array &$mod_buttons): void
	{
		global $modSettings, $board, $context;

		$context['can_see_description'] = !empty($context['topicinfo']['description']) && !empty($modSettings['topic_descriptions_topics']) && !empty($modSettings['topic_descriptions_boards']) && in_array($board, explode(",", $modSettings['topic_descriptions_boards']));
	}

	/**
	 * Called by:
	 *        integrate_unread_list
	 */
	public static function unread_list(): void
	{
		global $context, $smcFunc;

		$request = $smcFunc['db_query']('', '
			SELECT
				id_topic, COALESCE(description, "")
			FROM {db_prefix}topics
			WHERE id_topic IN ({array_int:topic_list})',
			[
				'topic_list' => array_keys($context['topics']),
			]
		);
		[$id_topic, $description] = $smcFunc['db_fetch_row']($request);
		$smcFunc['db_free_result']($request);

		$context['topics'][$id_topic]['description'] = $description;
	}

	/**
	 * Called by:
	 *        integrate_messageindex_buttons
	 */
	public static function messageindex_buttons(array &$normal_buttons): void
	{
		global $context, $smcFunc;

		$request = $smcFunc['db_query']('', '
			SELECT
				id_topic, COALESCE(description, "")
			FROM {db_prefix}topics
			WHERE id_topic IN ({array_int:topic_list})',
			[
				'topic_list' => array_keys($context['topics']),
			]
		);
		[$id_topic, $description] = $smcFunc['db_fetch_row']($request);
		$smcFunc['db_free_result']($request);

		$context['topics'][$id_topic]['description'] = $description;
	}

	/**
	 * Called by:
	 *        integrate_exit
	 */
	public static function exiting(): void
	{
		global $context, $modSettings, $smcFunc, $topic;

		if ($context['current_action'] === 'quotefast' && isset($context['message']) && $context['message']['id'] != 0) {
			$context['can_see_description'] = !empty($modSettings['topic_descriptions_boards']) && in_array($context['current_board'], explode(",", $modSettings['topic_descriptions_boards']));

			if ($context['can_see_description']) {
				$request = $smcFunc['db_query']('', '
					SELECT
						COALESCE(description, "")
					FROM {db_prefix}topics
					WHERE id_topic = {int:current_topic}',
					[
						'current_topic' => $topic,
					]
				);
				[$description] = $smcFunc['db_fetch_row']($request);
				$smcFunc['db_free_result']($request);
			}

			$context['message']['description'] = $description ?? '';
		}
	}

	public static function post_JavascriptModify(array &$post_errors): void
	{
		global $modSettings, $smcFunc, $txt;

		if (isset($_POST['description']) && $smcFunc['htmltrim']($_POST['description']) !== '') {
			$_POST['description'] = strtr($smcFunc['htmlspecialchars']($_POST['description']), ["\r" => '', "\n" => '', "\t" => '']);

			$max_descriptionLength = min(!empty($modSettings['topic_descriptions_maxlen']) ? (int) $modSettings['topic_descriptions_maxlen'] : 25, 140);
			if ($smcFunc['strlen']($_POST['description']) > $max_descriptionLength) {
				$txt['error_long_description'] = sprintf($txt['error_long_description'], $max_descriptionLength);
				$post_errors[] = 'long_description';
				unset($_POST['description']);
			}

			// Maximum number of bytes allowed.
			self::$description = $smcFunc['truncate']($_POST['description'], 0, 255);
		}
	}

	public static function jsmodify_xml(): void
	{
		global $context;

		if (isset($_POST['description'])) {
			if (!isset($context['message']['errors'])) {
				$context['message']['description'] = $_POST['description'];
				censorText($context['message']['description']);
			}
		} else {
			$context['message']['description'] = '';
		}
	}
}