<?php

function cbi_loadBoard(&$custom_column_selects)
{
	$custom_column_selects[] = 'b.cbi';
}

function cbi_boardInfo(&$board_info, $row)
{
	$board_info['cbi'] = $row['cbi'];
}

function cbi_modifyBoard($id, $boardOptions, &$boardUpdates, &$boardUpdateParameters)
{
	if (isset($boardOptions['cbi']))
	{
		$boardUpdates[] = 'cbi = {string:cbi}';
		$boardUpdateParameters['cbi'] = $boardOptions['cbi'];
	}
}

function cbi_preBoardTree(&$boardColumns)
{
	$boardColumns[] = 'b.cbi';
}

function cbi_boardTree($row)
{
	global $boards;

	$boards[$row['id_board']]['cbi'] = $row['cbi'];
}

/**
 * Adds the icon & color fields to the edit/add screen
 *
 * Called by:
 *		integrate_edit_board
 */
function cbi_editBoard()
{
	global $context, $txt;

	loadLanguage('CBI');

	if (empty($context['custom_board_settings']))
		$context['custom_board_settings'] = [];

	$context['custom_board_settings'] = array_merge(
		[
			[
				'dt' => sprintf(
					'<strong>%s:</strong><br />
						<span class="smalltext">%s</span><br />',
					$txt['cbi'],
					$txt['cbi_desc']
				),
				'dd' => sprintf(
					'<input type="text" name="cbi" value="%s" size="40" class="input_text" />
						<br /><input type="file" name="upfile" size="40" class="input_text" />',
					$context['board']['cbi']
				)
			],
		],
		$context['custom_board_settings']
	);
}

/**
 * Loads board icons from the database
 *
 * Called by:
 *		integrate_getboardtree
 */
function cbi_getboardtree($boardIndexOptions, &$categories)
{
	global $smcFunc;

	$board_ids = [];
	if ($boardIndexOptions['include_categories'])
		foreach ($categories as $cat_id => $category)
			foreach ($category['boards'] as $board_id => $board)
				$board_ids[$board_id] = $cat_id;
	else
		$board_ids = $categories;

	if ($board_ids == [])
		return;

	$request = $smcFunc['db_query']('', '
		SELECT id_board, cbi
		FROM {db_prefix}boards
		WHERE id_board IN ({array_int:board_ids})',
		[
			'board_ids' => array_keys($board_ids)
		]
	);
	while ([$id_board, $cbi] = $smcFunc['db_fetch_row']($request))
	{
		$this_category = $boardIndexOptions['include_categories']
			? $categories[$board_ids[$id_board]]['boards']
			: $categories;
		// Board type dictates the template function to call.
		$this_old_board = [
			'old_type' => $this_category[$id_board]['type'],
			'type' => 'cbi',
			'cbi' => $cbi,
		];

		// If we are on the board index, the board is inside a category, so we need to determine where
		if ($boardIndexOptions['include_categories'])
			$categories[$board_ids[$id_board]]['boards'][$id_board] = array_merge($categories[$board_ids[$id_board]]['boards'][$id_board], $this_old_board);
		else
			$categories[$id_board] = array_merge($categories[$id_board], $this_old_board);
	}
	$smcFunc['db_free_result']($request);
}

/**
 * Called by:
 *		integrate_general_mod_settings
 */
function cbi_settings(array &$config_vars)
{
	global $txt;

	loadLanguage('CBI');
	$config_vars = array_merge($config_vars, array(
		'',
		array('select', 'cbi_where', $txt['cbi_where_options']),
	));
}

function template_bi_cbi_icon($board)
{
	global $context, $modSettings, $scripturl;

	if ($board['cbi'] != '' && empty($modSettings['cbi_where']))
		printf(
			'<a href="%s" class="cbi_%s" style="background: none;"><img src="%s" alt="*" title="%s" /></a>',
			$context['user']['is_guest'] || $board['is_redirect']
				? $board['href']
				: $scripturl . '?action=unread;board=' . $board['id'] . '.0;children',
			$board['board_class'],
			$board['cbi'],
			$board['board_tooltip']
		);
	elseif (function_exists('template_bi_' . $board['old_type'] . '_icon'))
		call_user_func('template_bi_' . $board['old_type'] . '_icon', $board);
	else
		template_bi_board_icon($board);
}

function template_bi_cbi_info($board)
{
	global $modSettings;

	if ($board['cbi'] != '' &&  !empty($modSettings['cbi_where']))
		printf(
			'<a href="%s" class="floatleft"><img src="%s" alt="*" title="%s" /></a>',
			$board['href'],
			$board['cbi'],
			$board['board_tooltip']
		);

	if (function_exists('template_bi_' . $board['old_type'] . '_info'))
		call_user_func('template_bi_' . $board['old_type'] . '_info', $board);
	else
		template_bi_board_info($board);
}

function template_bi_cbi_stats($board)
{
	if (function_exists('template_bi_' . $board['old_type'] . '_stats'))
		call_user_func('template_bi_' . $board['old_type'] . '_stats', $board);
	else
		template_bi_board_stats($board);
}

function template_bi_cbi_lastpost($board)
{
	if (function_exists('template_bi_' . $board['old_type'] . '_lastpost'))
		call_user_func('template_bi_' . $board['old_type'] . '_lastpost', $board);
	else
		template_bi_board_lastpost($board);
}

function template_bi_cbi_children($board)
{
	if (function_exists('template_bi_' . $board['old_type'] . '_children'))
		call_user_func('template_bi_' . $board['old_type'] . '_children', $board);
	else
		template_bi_board_children($board);
}
