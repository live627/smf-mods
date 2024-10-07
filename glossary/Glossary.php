<?php

//============================================================================
// Original Glossary mod by Slinouille
// https://www.simplemachines.org/community/index.php?action=profile;u=68142
// https://custom.simplemachines.org/mods/index.php?mod=1525
//
// Updated and enhanced for SMF 2.1 by GL700Wing
// https://www.simplemachines.org/community/index.php?action=profile;u=112942
//============================================================================

// We calling this directly, if so, you're evil ...
if (!defined('SMF'))
	die('Hacking attempt...');

// Load the language file.
loadLanguage('glossary/Glossary');

function Glossary()
{
	global $context, $txt, $smcFunc, $settings, $modSettings, $scripturl;

	$context['linktree'][] = array(
		'url' => $scripturl . '?action=glossary',
		'name' => $txt['glossary'],
	);

	// Initialise the error messages.
	$context['glossary_action_status'] = '';
	$context['glossary_error_submit'] = '';
	$context['glossary_error_submit_message'] = '';

	// Get the template ready ...
	$context['glossary_tooltip_bbc'] = $modSettings['glossary_tooltip_bbc'];
	$context['page_title'] = $txt['glossary'];
	loadTemplate('Glossary');

	// ADD a keyword
	if ((allowedTo('glossary_admin') || allowedTo('glossary_suggest')) && !empty($_POST['submit_new_word']))
	{
		// Security checks.
		checkSession('post');

		// Check for keyword/synonym conflicts.
		$synonymError = synonymCheck('new');

		// Check if the keyword already exists.
		$data_glossary = $smcFunc['db_query']('', '
			SELECT COUNT(*)
			FROM {db_prefix}glossary
			WHERE word = {string:new_word}',
			array(
				'new_word' => $_POST['new_word'],
			)
		);
		$res = $smcFunc['db_fetch_row']($data_glossary);
		if (empty($synonymError) && empty($res[0]))
		{
			// Remove duplicate synonyms.
			$synonyms = implode(',', array_unique(explode(',', $_POST['edit_word_synonyms'])));

			// Add keyword to database.
			$show_in_message = isset($_POST['new_show_in_message']) && $_POST['new_show_in_message'] == 'on' ? 1 : 0;
			$validword = isset($_POST['new_valid']) && $_POST['new_valid'] == 'on' ? 1 : 0;
			$smcFunc['db_insert']('insert',
				'{db_prefix}glossary',
				array(
					'word' => 'string-50',
					'definition' => 'text',
					'member_id' => 'int',
					'date' => 'string-30',
					'valid' => 'int',
					'synonyms' => 'text',
					'show_in_message' => 'int',
					'group_id' => 'int',
				),
				array(
					addslashes(htmlspecialchars($_POST['new_word'], ENT_QUOTES)),
					addslashes(htmlspecialchars(trim($_POST['new_definition']), ENT_QUOTES)),
					(int) $context['user']['id'],
					mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")),
					(int) $validword,
					addslashes(htmlspecialchars($synonyms, ENT_QUOTES)),
					(int) $show_in_message,
					isset($_POST['new_group']) ? (int) $_POST['new_group'] : '0',
				),
				array()
			);
		}
		else
		{
			// Keyword exists and/or keyword/synonym conflict - return error.
			$context['glossary_action_status'] = 'check_new';
			$context['glossary_error_submit'] = true;
			if ($res[0] && $synonymError)
				$context['glossary_error_submit_message'] = $txt['glossary_submission_error_1'] . $synonymError;
			elseif ($res[0])
				$context['glossary_error_submit_message'] = $txt['glossary_submission_error_1'];
			elseif ($synonymError)
				$context['glossary_error_submit_message'] = $synonymError;
		}
	}
	// UPDATE a keyword
	elseif ((allowedTo('glossary_admin') || (!empty($_POST['is_author_of_word']) && $_POST['is_author_of_word'] == 'true')) && !empty($_POST['submit_edit_word']))
	{
		// Security checks.
		checkSession('post');

		// Check for keyword/synonym conflicts.
		$synonymError = synonymCheck('edit');

		// Check if the keyword already exists.
		$data_glossary = $smcFunc['db_query']('', '
			SELECT COUNT(*)
			FROM {db_prefix}glossary
			WHERE word = {string:edit_word}
				AND id != {int:id}',
			array(
				'id' => (int) $_POST['edit_word_id'],
				'edit_word' => $_POST['edit_word'],
			)
		);
		$res = $smcFunc['db_fetch_row']($data_glossary);
		if (empty($synonym_in_use) && empty($res[0]))
		{
			// Remove duplicate synonyms.
			$synonyms = implode(',', array_unique(explode(',', $_POST['edit_word_synonyms'])));

			// Update the keyword.
			$show_in_message = isset($_POST['edit_show_in_message']) && $_POST['edit_show_in_message'] == 'on' ? 1 : 0;
			$validword = isset($_POST['edit_valid']) && $_POST['edit_valid'] == 'on' ? 1 : 0;
			if (isset($_POST['is_author_of_word']) && $_POST['is_author_of_word'] == 'true')
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}glossary
					SET word = {string:word},
						definition = {string:definition},
						date = {string:date},
						synonyms = {string:synonyms},
						show_in_message = {string:show_in_message},
						group_id = {int:group_id}
					WHERE id = {int:id}',
					array(
						'id' => (int) $_POST['edit_word_id'],
						'word' => addslashes(htmlspecialchars($_POST['edit_word'], ENT_QUOTES)),
						'definition' => addslashes(htmlspecialchars(trim($_POST['edit_definition']), ENT_QUOTES)),
						'date' => mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")),
						'synonyms' => addslashes(htmlspecialchars($synonyms, ENT_QUOTES)),
						'show_in_message' => (int) $show_in_message,
						'group_id' => isset($_POST['edit_group']) ? (int) $_POST['edit_group'] : '0',
					)
				);
			else
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}glossary
					SET word = {string:word},
						definition = {string:definition},
						date = {string:date},
						valid = {int:valid},
						synonyms = {string:synonyms},
						show_in_message = {string:show_in_message},
						group_id = {int:group_id}
					WHERE id = {int:id}',
					array(
						'id' => (int) $_POST['edit_word_id'],
						'word' => addslashes(htmlspecialchars(trim($_POST['edit_word']), ENT_QUOTES)),
						'definition' => addslashes(htmlspecialchars(trim($_POST['edit_definition']), ENT_QUOTES)),
						'date' => mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")),
						'valid' => (int) $validword,
						'synonyms' => addslashes(htmlspecialchars($synonyms, ENT_QUOTES)),
						'show_in_message' => (int) $show_in_message,
						'group_id' => isset($_POST['edit_group']) ? (int) $_POST['edit_group'] : '0',
					)
				);
		}
		else
		{
			// Keyword exists and/or keyword/synonym conflict - return error.
			$context['glossary_action_status'] = 'edit';
			$context['glossary_error_submit'] = true;
			if ($res[0] && $synonymError)
				$context['glossary_error_submit_message'] = $txt['glossary_submission_error_1'] . $synonymError;
			elseif ($res[0])
				$context['glossary_error_submit_message'] = $txt['glossary_submission_error_1'];
			elseif ($synonymError)
				$context['glossary_error_submit_message'] = $synonymError;
		}
	}
	// DELETE a keyword
	elseif (allowedTo('glossary_admin') && !empty($_POST['submit_delete_word']) && !empty($_POST['id_word_to_delete']))
	{
		// Security checks.
		checkSession('post');

		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}glossary
			WHERE id = {int:id}',
			array(
				'id' => $_POST['id_word_to_delete'],
			)
		);
	}
	// APPROVE a keyword
	elseif (allowedTo('glossary_admin') && isset($_POST['action_on_word']) && $_POST['action_on_word'] == 'approve_word' && !empty($_POST['id_word']))
	{
		// Security checks.
		checkSession('post');

		$smcFunc['db_query']('', '
			UPDATE {db_prefix}glossary
			SET valid = {int:valid}
			WHERE id = {int:id}',
			array(
				'valid' => '1',
				'id' => (int) $_POST['id_word'],
			)
		);
	}
	// UNAPPROVE a keyword
	elseif (allowedTo('glossary_admin') && isset($_POST['action_on_word']) && $_POST['action_on_word'] == 'unapprove_word' && !empty($_POST['id_word']))
	{
		// Security checks.
		checkSession('post');

		$smcFunc['db_query']('', '
			UPDATE {db_prefix}glossary
			SET valid = {int:valid}
			WHERE id = {int:id}',
			array(
				'id' => (int) $_POST['id_word'],
				'valid' => '0',
			)
		);
	}
	// ENABLE TOOLTIP for a keyword
	elseif (allowedTo('glossary_admin') && isset($_POST['action_on_word']) && $_POST['action_on_word'] == 'enable_tooltip' && !empty($_POST['id_word']))
	{
		// Security checks.
		checkSession('post');

		$smcFunc['db_query']('', '
			UPDATE {db_prefix}glossary
			SET show_in_message = {int:show_in_message}
			WHERE id = {int:id}',
			array(
				'id' => (int) $_POST['id_word'],
				'show_in_message' => '1',
			)
		);
	}
	// DISABLE TOOLTIP for a keyword
	elseif (allowedTo('glossary_admin') && isset($_POST['action_on_word']) && $_POST['action_on_word'] == 'disable_tooltip' && !empty($_POST['id_word']))
	{
		// Security checks.
		checkSession('post');

		$smcFunc['db_query']('', '
			UPDATE {db_prefix}glossary
			SET show_in_message = {int:show_in_message}
			WHERE id = {int:id}',
			array(
				'id' => (int) $_POST['id_word'],
				'show_in_message' => '0',
			)
		);
	}
	// ENABLE TOOLTIP for a SELECTION
	elseif (allowedTo('glossary_admin') && isset($_POST['action_on_list']) && $_POST['action_on_list'] == 'tooltip_selected' && !empty($_POST['list_of_ids']))
	{
		// Security checks.
		checkSession('post');

		$mylist = explode(";",$_POST['list_of_ids']);
		foreach($mylist as $newid)
		{
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}glossary
				SET show_in_message = {int:show_in_message}
				WHERE id = {int:id}',
				array(
					'id' => (int) $newid,
					'show_in_message' => '1',
				)
			);
		}
	}
	// DISABLE TOOLTIP for a SELECTION
	elseif (allowedTo('glossary_admin') && isset($_POST['action_on_list']) && $_POST['action_on_list'] == 'untooltip_selected' && !empty($_POST['list_of_ids']))
	{
		// Security checks.
		checkSession('post');

		$mylist = explode(";",$_POST['list_of_ids']);
		foreach($mylist as $newid)
		{
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}glossary
				SET show_in_message = {int:show_in_message}
				WHERE id = {int:id}',
				array(
					'id' => (int) $newid,
					'show_in_message' => '0',
				)
			);
		}
	}
	// APPROVE a SELECTION
	elseif (allowedTo('glossary_admin') && isset($_POST['action_on_list']) && $_POST['action_on_list'] == 'approve_selected' && !empty($_POST['list_of_ids']))
	{
		// Security checks.
		checkSession('post');

		$mylist = explode(";",$_POST['list_of_ids']);
		foreach($mylist as $newid)
		{
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}glossary
				SET valid = {int:valid}
				WHERE id = {int:id}',
				array(
					'id' => (int) $newid,
					'valid' => '1',
				)
			);
		}
	}
	// UNAPPROVE a SELECTION
	elseif (allowedTo('glossary_admin') && isset($_POST['action_on_list']) && $_POST['action_on_list'] == 'unapprove_selected' && !empty($_POST['list_of_ids']))
	{
		// Security checks.
		checkSession('post');

		$mylist = explode(";",$_POST['list_of_ids']);
		foreach($mylist as $newid)
		{
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}glossary
				SET valid = {int:valid}
				WHERE id = {int:id}',
				array(
					'id' => (int) $newid,
					'valid' => '0',
				)
			);
		}
	}
	// CHANGE GROUP for a SELECTION
	elseif (allowedTo('glossary_admin') && isset($_POST['action_on_list']) && $_POST['action_on_list'] == 'change_group_selected' && !empty($_POST['list_of_ids']) && !empty($_POST['group_id']))
	{
		// Security checks.
		checkSession('post');

		$mylist = explode(";",$_POST['list_of_ids']);
		foreach($mylist as $newid)
		{
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}glossary
				SET group_id = {int:group_id}
				WHERE id = {int:id}',
				array(
					'id' => (int) $newid,
					'group_id' => (int) $_POST['group_id'],
				)
			);
		}
	}
	// ADD a new GROUP
	elseif (allowedTo('glossary_admin') && !empty($_POST['manage_new_group']))
	{
		// Security checks.
		checkSession('post');

		// No "http(s)://" or trailing slashes in category names.
		$manage_new_group = addslashes(htmlspecialchars(preg_replace('~https?://~','',rtrim($_POST['manage_new_group'],'/')), ENT_QUOTES));
		$slashGroup = $manage_new_group.'/';
		$_POST['manage_new_group'] = addslashes(htmlspecialchars(rtrim($_POST['manage_new_group'],'/'), ENT_QUOTES));

		// Check if the group already exists.
		$data_glossary = $smcFunc['db_query']('', '
			SELECT COUNT(*)
			FROM {db_prefix}glossary_groups
			WHERE (title = {string:title} OR title = {string:title_no_http} OR title = {string:title_slash})',
			array(
				'title' => $_POST['manage_new_group'],
				'title_no_http' => $manage_new_group,
				'title_slash' => $slashGroup,
			)
		);
		$res = $smcFunc['db_fetch_row']($data_glossary);
		if (empty($res[0]))
		{
			// Add to database.
			$smcFunc['db_insert']('insert',
				'{db_prefix}glossary_groups',
				array(
					'title' => 'string-50',
				),
				array(
					$manage_new_group,
				),
				array()
			);
		}
		else
		{
			// Category already exists - return error.
			$context['glossary_action_status'] = 'check_new_group';
			$context['glossary_error_submit'] = true;
			$context['glossary_error_submit_message'] = $txt['glossary_submission_error_5'];
		}
	}
	// DELETE a GROUP.
	elseif (allowedTo('glossary_admin') && empty($_POST['update_category_title']) && !empty($_POST['group_update']))
	{
		// Security checks.
		checkSession('post');

		// Delete from database.
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}glossary_groups
			WHERE id = {int:id}',
			array(
				'id' => (int) $_POST['group_update'],
			)
		);
	}
	// UPDATE a GROUP.
	elseif (allowedTo('glossary_admin') && !empty($_POST['update_category_title']) && !empty($_POST['group_update']))
	{
		// Security checks.
		checkSession('post');

		// No "http(s)://" or trailing slashes in category names.
		$update_category_title = addslashes(htmlspecialchars(preg_replace('~https?://~','',rtrim($_POST['update_category_title'],'/')), ENT_QUOTES));
		$slashGroup = $update_category_title.'/';
		$_POST['update_category_title'] = addslashes(htmlspecialchars(rtrim($_POST['update_category_title'],'/'), ENT_QUOTES));

		// Check if the group already exists.
		$data_glossary = $smcFunc['db_query']('', '
			SELECT COUNT(*)
			FROM {db_prefix}glossary_groups
			WHERE (title = {string:title} OR title = {string:title_no_http} OR title = {string:title_slash})',
			array(
				'title' => $_POST['update_category_title'],
				'title_no_http' => $update_category_title,
				'title_slash' => $slashGroup,
			)
		);
		$res = $smcFunc['db_fetch_row']($data_glossary);
		if (empty($res[0]))
		{
			// Update in database.
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}glossary_groups
				SET title = {string:title}
				WHERE id = {int:id}',
				array(
					'id' => (int) $_POST['group_update'],
					'title' => $update_category_title,
				)
			);
		}
		else
		{
			// Category already exists - return error.
			$context['glossary_action_status'] = 'check_update_group';
			$context['glossary_error_submit'] = true;
			$context['glossary_error_submit_message'] = $txt['glossary_submission_error_5'];
		}
	}

	// An error occurred - show the error message as an alert and reload the page.
	// (Need to reload the page to prevent alert showing on page refresh and weird font resizing).
	if ($context['glossary_error_submit'] == true)
		echo '
			<script language="JavaScript" type="text/javascript">
				alert("'.$context['glossary_error_submit_message'].'");
				window.location.href="'.$scripturl.'?action='.$_GET['action'].';sa='.$_GET['sa'].'";
			</script>';

	// Build list of groups
	$context['glossary_groups'] = array();
	$data_groups = $smcFunc['db_query']('', '
		SELECT *
		FROM {db_prefix}glossary_groups
		ORDER BY title ASC',
		array()
	);
	while ($res = $smcFunc['db_fetch_row']($data_groups))
		$context['glossary_groups'][$res[0]] = $res[1];

	// Init of some needed variables
	$full_words_list = '';
	$words_list = '';
	$ids_list = '';

	$author_showAdmin = $modSettings['glossary_show_author_admin'];
	$author_showAll = $modSettings['glossary_show_author_all'];
	$glossary_defnWidth = empty($modSettings['glossary_definition_width']) ? 800 : $modSettings['glossary_definition_width'];
	$glossary_wordWidth = empty($modSettings['glossary_word_width']) ? 100 : $modSettings['glossary_word_width'];
	$groups_enabled = $modSettings['glossary_enable_groups'] ? 'ok' : '';
	$numeric_enabled = $modSettings['glossary_enable_numeric'];
	$show_usedChars = $modSettings['glossary_show_used_chars'];

	// ==================================================================
	// Prepare glossary list by ALPHABETIC ORDER
	// ==================================================================
	if ((isset($_GET['sa']) && $_GET['sa'] == 'alphabetic') || !isset($_GET['sa']))
	{
		$letter_in_progress = '';
		$context['glossary_letters'] =	'';
		$nb_words_for_letter_in_progress = 0;
		$alphabet_list = array();

		// Start of what to show ...
		$context['glossary_elements'] = '<table id="table_full_table" width="100%">';

		if (allowedTo('glossary_admin'))
			$data_glossary = $smcFunc['db_query']('', '
				SELECT *
				FROM {db_prefix}glossary
				ORDER BY word ASC',
				array()
			);
		else
			$data_glossary = $smcFunc['db_query']('', '
				SELECT *
				FROM {db_prefix}glossary
				WHERE (valid = 1 OR (valid = 0 AND member_id = {int:member_id}))
				ORDER BY word ASC',
				array(
					'member_id' => (int) $context['user']['id'],
				)
			);
		while ($res = $smcFunc['db_fetch_assoc']($data_glossary))
		{
			// Only manage numeric if asked by admin.
			if (($numeric_enabled && is_numeric(substr($res['word'],0,1))) || !is_numeric(substr($res['word'],0,1)))
			{
				if (strtoupper(substr($res['word'],0,1)) != $letter_in_progress)
				{
					if ($nb_words_for_letter_in_progress != 0)
					{
						// Write the title and keywords.
						$full_words_list .= '
							<tr id="letter_'.$letter_in_progress.'" style=""><td>
								<div class="letter_selection">&nbsp;'.$letter_in_progress.'</div>
								<table align="left" style="border-collapse: collapse; width:100%;">
									'.$words_list.'
								</table>
							</td></tr>';

						// Store the first letter => needed for building a dynamic alphabet list.
						array_push($alphabet_list,$letter_in_progress);
						$context['glossary_letters'] .= $letter_in_progress.',';
					}
					$nb_words_for_letter_in_progress = 0;
					$words_list = "";
				}

				// Construct keyword and definition list.
				$pending = '';
				if (empty($res['valid']) && $context['user']['id'] == $res['member_id'])
					$pending = ' title="[ '.$txt['glossary_suggestion_you_made'].' ]"';
				$words_list .= '
					<tr style="border-bottom: 1px solid black;"' . $pending . '>
						<td style="padding:3px;width:' . (allowedTo('glossary_admin') ? 125 : 45) . 'px;" valign="top">';

				// If admin glossary then add specific 'delete' and 'edit' icons
				if (allowedTo('glossary_admin'))
				{
					$words_list .= '
							<input type="checkbox" id="glossary_cb_'.$res['id'].'" title="'.$txt['glossary_tip_select'].'">
							<a href="javascript:EditWord(\''.$res['id'].'\',\''.$groups_enabled.'\',\'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_edit'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_blue_edit.png"></a>
							<a style="cursor:pointer;" href="javascript:DeleteWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_delete'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_blue_delete.png"></a>';

					// Identify UNAPPROVED keywords.
					if (empty($res['valid']) || $res['valid'] == 0)
						$words_list .= '
							<a href="javascript:ApproveWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_approve'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_unapproved.png"></a>';
					else
						$words_list .= '
							<a href="javascript:UnApproveWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_unapprove'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_approved.png"></a>';

					// Identify VISIBLE keywords.
					if ($res['show_in_message'] == 1)
						$words_list .= '&nbsp;<a href="javascript:DisableTooltipWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_visible'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_visible.png"></a>';
					else
						$words_list .= '&nbsp;<a href="javascript:EnableTooltipWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_unvisible'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_unvisible.png"></a>';
				}
				else
				{
					if ($context['user']['id'] == $res['member_id'] && $res['valid'] == 0)
						$words_list .= '<img src="'. $settings['default_theme_url']. '/images/glossary/glossary_unapproved.png" title="' . $txt['glossary_not_approved'] . '">';
					else
						$words_list .= '<img src="'. $settings['default_theme_url']. '/images/glossary/glossary_blue.png">';

					// Add button for editing if user is author of the keyword
					if ($context['user']['id'] == $res['member_id'])
						$words_list .= '
							<a href="javascript:EditWord(\''.$res['id'].'\',\''.$groups_enabled.'\',\'true\')" style="text-decoration:none;" title="'.$txt['glossary_tip_edit'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_blue_edit.png"></a>';
				}

				// Show the name of the keyword author (and a link to their profile if they are a current member).
				if ($author_showAdmin || $author_showAll)
				{
					$query_member = 'SELECT id_member, real_name FROM {db_prefix}members WHERE id_member='.$res['member_id'];
					$data_member = $smcFunc['db_query']('', $query_member,array());
					$res_member = $smcFunc['db_fetch_row']($data_member);
					// If the real name is longer than 15 characters shorten it and add an ellipsis to the end.
					if (!empty($res_member[1]) && strlen($res_member[1]) > 15)
						$res_member[1] = trim(substr($res_member[1], 0, 11)) . ' ...';
					$author = '<hr style="width:90%; margin:6px 0;"><i> '.$txt['glossary_keyword_author'].'<br></i>'.(!empty($res_member[1]) ? '<a href="'.$scripturl.'?action=profile;u='.$res_member[0].'"><u>'.$res_member[1].'</u></a>' : $txt['guest']);
					$words_list .= $author;
				}

				$synList = !empty($res['synonyms']) ? '</b><br><hr style="width:90%; margin:6px 0;"><i>' . $txt['glossary_synonyms'] . '</i><br>' . str_replace(',', '<br>', $res['synonyms']) : '';
				$words_list .= '
						</td>
						<td style="padding:3px; width:'.$glossary_wordWidth.'px;" valign="top"><b><div style="float:left;" id="word_'.$res['id'].'">'.$res['word'].'</div>'.$synList.'</b></td>
						<td style="padding:3px; width:'.$glossary_defnWidth.'px;" valign="top"><div style="float:left;" id="definition_'.$res['id'].'">';
				$pending = '';
				if (empty($res['valid']))
				{
					// If keyword is UNAPPROVED get the member's name (if admin) or inform the person who have made the suggestion.
					if (allowedTo('glossary_admin'))
					{
						$query_member = 'SELECT id_member, real_name FROM {db_prefix}members WHERE id_member='.$res['member_id'];
						$data_member = $smcFunc['db_query']('', $query_member,array());
						$res_member = $smcFunc['db_fetch_row']($data_member);
						$pending = '<br><b>[<i> '.$txt['glossary_suggestion_by'].' '.(!empty($res_member[1]) ? '<a href="'.$scripturl.'?action=profile;u='.$res_member[0].'"><u>'.$res_member[1].'</u></a>' : $txt['guest']).' </i>]</b>';
					}
					// If this is the member who made the suggestion ...
					elseif ($context['user']['id'] == $res['member_id'])
						$pending = '<br>[<i> '.$txt['glossary_suggestion_you_made'].' </i>]';
				}
				$words_list .= ($context['glossary_tooltip_bbc'] ? parse_bbc($res['definition']) : $res['definition']).$pending;

				if ($groups_enabled)
				{
					$words_list .= '
						</div>&nbsp;<div style="display:inline;"></div>
						</td>
						<td width="10%" style="vertical-align:top; text-align:center;">';

					$words_list .= isset($context['glossary_groups'][$res['group_id']])
						? '<b>'.$txt['glossary_group'].'<br>'.$context['glossary_groups'][$res['group_id']].'</b>'
						: '<i>'.$txt['glossary_group_none'].'</i>';
				}

				$words_list .= '
							<input type="hidden" id="definition_text_'.$res['id'].'" value="'.$res['definition'].'">
							<input type="hidden" id="show_in_message_'.$res['id'].'" value="'.$res['show_in_message'].'">
							<input type="hidden" id="valid_'.$res['id'].'" value="'.$res['valid'].'">
							<input type="hidden" id="group_id_'.$res['id'].'" value="'.$res['group_id'].'">
							<input type="hidden" id="synonyms_'.$res['id'].'" value="'.$res['synonyms'].'">
						</td>
					</tr>';

				// Get list of all IDs.
				$ids_list .= ';'.$res['id'];

				// Loop arguments.
				$letter_in_progress = strtoupper(substr($res['word'],0,1));
				$nb_words_for_letter_in_progress ++;
			}
		}
		$smcFunc['db_free_result']($data_glossary);

		// Manage last entry.
		if ($nb_words_for_letter_in_progress != 0)
		{
			// Write the title and keywords.
			$full_words_list .= '
				<tr id="letter_'.$letter_in_progress.'" style=""><td>
					<div class="letter_selection">&nbsp;'.$letter_in_progress.'</div>
					<table align="left" style="border-collapse: collapse; width:100%;">
						'.$words_list.'
					</table>
				</td></tr>';

			// Store the first letter => needed for building a dynamic alphabet list.
			array_push($alphabet_list,$letter_in_progress);
			$context['glossary_letters'] .= $letter_in_progress.',';
		}

		// Prepare alphabetic list.
		$context['glossary_elements'] .= '<tr><td colspan="3"><a href="javascript:Display_glossary_for_letter(\'all\')"><b><u>'.$txt['glossary_all'].'</u></b></a> | ';
		for ($i=ord("A");$i<=ord("Z");$i++)
		{
			if (in_array(chr($i),$alphabet_list))
				$context['glossary_elements'] .= '<a href="javascript:Display_glossary_for_letter(\''.chr($i).'\')"><b><u>'.chr($i).'</u></b></a> | ';

			// Hide unused alphabetic characters.
			if (!$show_usedChars && !in_array(chr($i),$alphabet_list))
				$context['glossary_elements'] .= '<i>'.chr($i).'</i> | ';
		}

		// Add numeric list if required.
		if ($numeric_enabled)
		{
			for ($i=0;$i<10;$i++)
			{
				if (in_array($i,$alphabet_list))
					$context['glossary_elements'] .= '<a href="javascript:Display_glossary_for_letter(\''.$i.'\')"><b><u>'.$i.'</u></b></a> | ';

				// Hide unused numeric characters.
				if (!$show_usedChars && !in_array($i,$alphabet_list))
					$context['glossary_elements'] .= '<i>'.$i.'</i> | ';
			}
		}
	}
	// ===============================================================================
	// Manage GROUP order
	// ===============================================================================
	elseif (isset($_GET['sa']) && $_GET['sa'] == 'categories')
	{
		$groups_list = array();
		$context['glossary_letters'] =	'';
		$words_list = "";
		$last_group_id = 0;

		// Prepare groups list
		$context['glossary_elements'] = '<table id="table_full_table" width="100%"><tr><td colspan="3"><a href="javascript:Display_glossary_for_letter(\'all\')"><b><u>'.$txt['glossary_all'].'</u></b></a> | ';

		// Go through all groups.
		$data_groups = $smcFunc['db_query']('', '
			SELECT *
			FROM {db_prefix}glossary_groups
			ORDER BY title ASC',
			array()
		);
		while ($res_groups = $smcFunc['db_fetch_assoc']($data_groups))
		{
			// Check if keywords are available for group.
			if (allowedTo('glossary_admin'))
				$data_glossary = $smcFunc['db_query']('', '
					SELECT COUNT(id)
					FROM {db_prefix}glossary
					WHERE (group_id = {int:group_id})
					GROUP BY id
					ORDER BY word ASC',
					array(
						'group_id' => (int) $res_groups['id'],
					)
				);
			else
				$data_glossary = $smcFunc['db_query']('', '
					SELECT COUNT(id)
					FROM {db_prefix}glossary
					WHERE (group_id = {int:group_id} AND (valid = 1 OR (valid = 0 AND member_id = {int:member_id})))
					GROUP BY id
					ORDER BY word ASC',
					array(
						'member_id' => (int) $context['user']['id'],
						'group_id' => (int) $res_groups['id'],
					)
				);
			$res_glossary = $smcFunc['db_fetch_row']($data_glossary);
			if (is_array($res_glossary) && $res_glossary[0] > 0)
			{
				// Found list of keywords.
				if (allowedTo('glossary_admin'))
					$data_glossary = $smcFunc['db_query']('', '
						SELECT *
						FROM {db_prefix}glossary
						WHERE (group_id = {int:group_id})
						ORDER BY word ASC',
						array(
							'group_id' => (int) $res_groups['id'],
						)
					);
				else
					$data_glossary = $smcFunc['db_query']('', '
						SELECT *
						FROM {db_prefix}glossary
						WHERE (group_id = {int:group_id} AND (valid = 1 OR (valid = 0 AND member_id = {int:member_id})))
						ORDER BY word ASC',
						array(
							'member_id' => (int) $context['user']['id'],
							'group_id' => (int) $res_groups['id'],
						)
					);
				while ($res = $smcFunc['db_fetch_assoc']($data_glossary))
				{
					// Build list of keywords for this group.
					// Only manage numeric if asked by admin.
					if (($numeric_enabled && is_numeric(substr($res['word'],0,1))) || !is_numeric(substr($res['word'],0,1)))
					{
						// Construct keyword and definition list.
						$pending = '';
						if (empty($res['valid']) && $context['user']['id'] == $res['member_id'])
							$pending = ' title="[ '.$txt['glossary_suggestion_you_made'].' ]"';
						$words_list .= '
							<tr style="border-bottom: 1px solid black;"' . $pending . '>
								<td style="padding:3px;width:' . (allowedTo('glossary_admin') ? 125 : 45) . 'px;" valign="top">';

						// If admin glossary then add specific 'delete' and 'edit' icons.
						if (allowedTo('glossary_admin'))
						{
							$words_list .= '
									<input type="checkbox" id="glossary_cb_'.$res['id'].'">
									<a href="javascript:EditWord(\''.$res['id'].'\',\''.$groups_enabled.'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_edit'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_blue_edit.png"></a>
									<a style="cursor:pointer;" href="javascript:DeleteWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_delete'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_blue_delete.png"></a>';

							// Identify UNAPPROVED keywords.
							if (empty($res['valid']) || $res['valid'] == 0)
								$words_list .= '
									<a href="javascript:ApproveWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_approve'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_unapproved.png"></a>';
							else
								$words_list .= '
									<a href="javascript:UnApproveWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_unapprove'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_approved.png"></a>';

							// Identify VISIBLE keywords.
							if ($res['show_in_message'] == 1)
								$words_list .= '&nbsp;<a href="javascript:DisableTooltipWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_visible'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_visible.png"></a>';
							else
								$words_list .= '&nbsp;<a href="javascript:EnableTooltipWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_unvisible'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_unvisible.png"></a>';
						}
						else
						{
							if ($context['user']['id'] == $res['member_id'] && $res['valid'] == 0)
								$words_list .= '<img src="'. $settings['default_theme_url']. '/images/glossary/glossary_unapproved.png" title="' . $txt['glossary_not_approved'] . '">';
							else
								$words_list .= '<img src="'. $settings['default_theme_url']. '/images/glossary/glossary_blue.png">';

							// Add button for editing if user is author of the keyword.
							if ($context['user']['id'] == $res['member_id'])
								$words_list .= '
									<a href="javascript:EditWord(\''.$res['id'].'\',\''.$groups_enabled.'\',\'true\')" style="text-decoration:none;" title="'.$txt['glossary_tip_edit'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_blue_edit.png"></a>';
						}

						// Show the name of the keyword author (and a link to their profile if they are a current member).
						if ($author_showAdmin || $author_showAll)
						{
							$query_member = 'SELECT id_member, real_name FROM {db_prefix}members WHERE id_member='.$res['member_id'];
							$data_member = $smcFunc['db_query']('', $query_member,array());
							$res_member = $smcFunc['db_fetch_row']($data_member);
							// If the real name is longer than 15 characters shorten it and add an ellipsis to the end.
							if (!empty($res_member[1]) && strlen($res_member[1]) > 15)
								$res_member[1] = trim(substr($res_member[1], 0, 11)) . ' ...';
							$author = '<hr style="width:90%; margin:6px 0;"><i> '.$txt['glossary_keyword_author'].'<br></i>'.(!empty($res_member[1]) ? '<a href="'.$scripturl.'?action=profile;u='.$res_member[0].'"><u>'.$res_member[1].'</u></a>' : $txt['guest']);
							$words_list .= $author;
						}

						$synList = !empty($res['synonyms']) ? '</b><br><hr style="width:90%; margin:6px 0;"><i>' . $txt['glossary_synonyms'] . '</i><br>' . str_replace(',', '<br>', $res['synonyms']) : '';
						$words_list .= '
								</td>
								<td style="padding:3px; width:'.$glossary_wordWidth.'px;" valign="top"><b><div style="float:left;" id="word_'.$res['id'].'">'.$res['word'].'</div>'.$synList.'</b></td>
								<td style="padding:3px; width:'.$glossary_defnWidth.'px;" valign="top"><div style="float:left;" id="definition_'.$res['id'].'">';

						$pending = '';
						if (empty($res['valid']))
						{
							// If keyword is UNAPPROVED get the member's name (if admin) or inform the person who have made the suggestion.
							if (allowedTo('glossary_admin'))
							{
								// Don't show UNAPPROVED keywords to members.
								$query_member = "SELECT id_member, real_name FROM {db_prefix}members WHERE id_member=".$res['member_id'];
								$data_member = $smcFunc['db_query']('', $query_member,array());
								$res_member = $smcFunc['db_fetch_row']($data_member);
								$pending = '<br><b>[<i> '.$txt['glossary_suggestion_by'].' '.(!empty($res_member[1]) ? '<a href="'.$scripturl.'?action=profile;u='.$res_member[0].'"><u>'.$res_member[1].'</u></a>' : $txt['guest']).' </i>]</b>';
							}
							// If this is the member who made the suggestion ...
							elseif ($context['user']['id'] == $res['member_id'])
								$pending = '<br>[<i> '.$txt['glossary_suggestion_you_made'].' </i>]';
						}
						$words_list .= ($context['glossary_tooltip_bbc'] ? parse_bbc($res['definition']) : $res['definition']).$pending;
						$words_list .= '
								</div>&nbsp;<div style="display:inline;"></div>
								<input type="hidden" id="definition_text_'.$res['id'].'" value="'.$res['definition'].'">
								<input type="hidden" id="show_in_message_'.$res['id'].'" value="'.$res['show_in_message'].'">
								<input type="hidden" id="valid_'.$res['id'].'" value="'.$res['valid'].'">
								<input type="hidden" id="group_id_'.$res['id'].'" value="'.$res['group_id'].'">
								<input type="hidden" id="synonyms_'.$res['id'].'" value="'.$res['synonyms'].'">
								</td>
							</tr>';
					}

					// Get list of all IDs.
					$ids_list .= ';'.$res['id'];
				}
				$smcFunc['db_free_result']($data_glossary);

				// Store the first letter => needed for building a dynamic category list.
				$groups_list[$res_groups['id']] = $res_groups['title'];
				$context['glossary_letters'] .= $res_groups['id'].',';
				$context['glossary_elements'] .= '<a href="javascript:Display_glossary_for_letter(\''.$res_groups['id'].'\')"><b><u>'.$res_groups['title'].'</u></b></a> | ';

				// Build new table.
				$full_words_list .= '
				<tr id="letter_'.$res_groups['id'].'" style=""><td>
					<div class="letter_selection">&nbsp;'.$res_groups['title'].'</div>
					<table align="left" style="border-collapse: collapse; width:100%;">
						'.$words_list.'
					</table>
				</td></tr>';
				$words_list = "";
			}
			// Just add the group in list.
			else
				$context['glossary_elements'] .= '<i>'.$res_groups['title'].'</i> | ';
		}

		// =======================================
		// Get a list of none categorized keywords
		if (allowedTo('glossary_admin'))
			$data_glossary = $smcFunc['db_query']('', '
				SELECT *
				FROM {db_prefix}glossary
				WHERE (group_id = {int:group_id})
				ORDER BY word ASC',
				array(
					'group_id' => 0,
				)
			);
		else
			$data_glossary = $smcFunc['db_query']('', '
				SELECT *
				FROM {db_prefix}glossary
				WHERE (group_id = {int:group_id} AND (valid = 1 OR (valid = 0 AND member_id = {int:member_id})))
				ORDER BY word ASC',
				array(
					'member_id' => (int) $context['user']['id'],
					'group_id' => 0,
				)
			);
		while ($res = $smcFunc['db_fetch_assoc']($data_glossary))
		{
			// Build list of keywords for this group.
			// Only manage numeric if asked by admin
			if (($numeric_enabled && is_numeric(substr($res['word'],0,1))) || !is_numeric(substr($res['word'],0,1)))
			{
				// Construct keyword and definition list.
				$pending = '';
				if (empty($res['valid']) && $context['user']['id'] == $res['member_id'])
					$pending = ' title="[ '.$txt['glossary_suggestion_you_made'].' ]"';
				$words_list .= '
					<tr style="border-bottom: 1px solid black;"' . $pending . '>
						<td style="padding:3px;width:' . (allowedTo('glossary_admin') ? 125 : 45) . 'px;" valign="top">';

				// If admin glossary then add specific 'delete' and 'edit' icons.
				if (allowedTo('glossary_admin'))
				{
					$words_list .= '
							<input type="checkbox" id="glossary_cb_'.$res['id'].'">
							<a href="javascript:EditWord(\''.$res['id'].'\',\''.$groups_enabled.'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_edit'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_blue_edit.png"></a>
							<a style="cursor:pointer;" href="javascript:DeleteWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_delete'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_blue_delete.png"></a>';

					// Identify UNAPPROVED keywords.
					if (empty($res['valid']) || $res['valid'] == 0)
						$words_list .= '
							<a href="javascript:ApproveWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_approve'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_unapproved.png"></a>';
					else
						$words_list .= '
							<a href="javascript:UnApproveWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_unapprove'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_approved.png"></a>';

					// Identify VISIBLE keywords.
					if ($res['show_in_message'] == 1)
						$words_list .= '&nbsp;<a href="javascript:DisableTooltipWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_visible'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_visible.png"></a>';
					else
						$words_list .= '&nbsp;<a href="javascript:EnableTooltipWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_unvisible'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_unvisible.png"></a>';
				}
				else
				{
					if ($context['user']['id'] == $res['member_id'] && $res['valid'] == 0)
						$words_list .= '<img src="'. $settings['default_theme_url']. '/images/glossary/glossary_unapproved.png" title="' . $txt['glossary_not_approved'] . '">';
					else
						$words_list .= '<img src="'. $settings['default_theme_url']. '/images/glossary/glossary_blue.png">';

					// Add button for editing if user is author of the keyword.
					if ($context['user']['id'] == $res['member_id'])
						$words_list .= '
							<a href="javascript:EditWord(\''.$res['id'].'\',\''.$groups_enabled.'\',\'true\')" style="text-decoration:none;" title="'.$txt['glossary_tip_edit'].'"><img src="'. $settings['default_theme_url']. '/images/glossary/glossary_blue_edit.png"></a>';
				}

				// Show the name of the keyword author (and a link to their profile if they are a current member).
				if ($author_showAdmin || $author_showAll)
				{
					$query_member = 'SELECT id_member, real_name FROM {db_prefix}members WHERE id_member='.$res['member_id'];
					$data_member = $smcFunc['db_query']('', $query_member,array());
					$res_member = $smcFunc['db_fetch_row']($data_member);
					// If the real name is longer than 15 characters shorten it and add an ellipsis to the end.
					if (!empty($res_member[1]) && strlen($res_member[1]) > 15)
						$res_member[1] = trim(substr($res_member[1], 0, 11)) . ' ...';
					$author = '<hr style="width:90%; margin:6px 0;"><i> '.$txt['glossary_keyword_author'].'<br></i>'.(!empty($res_member[1]) ? '<a href="'.$scripturl.'?action=profile;u='.$res_member[0].'"><u>'.$res_member[1].'</u></a>' : $txt['guest']);
					$words_list .= $author;
				}

				$synList = !empty($res['synonyms']) ? '</b><br><hr style="width:90%; margin:6px 0;"><i>' . $txt['glossary_synonyms'] . '</i><br>' . str_replace(',', '<br>', $res['synonyms']) : '';
				$words_list .= '
						</td>
						<td style="padding:3px; width:'.$glossary_wordWidth.'px;" valign="top"><b><div style="float:left;" id="word_'.$res['id'].'">'.$res['word'].'</div>'.$synList.'</b></td>
						<td style="padding:3px; width:'.$glossary_defnWidth.'px;" valign="top"><div style="float:left;" id="definition_'.$res['id'].'">';

				$pending = '';
				if (empty($res['valid']))
				{
					// If keyword is UNAPPROVED get the member's name (if admin) or inform the person who have made the suggestion.
					if (allowedTo('glossary_admin'))
					{
						// Don't show UNAPPROVED keywords to members.
						$query_member = 'SELECT id_member, real_name FROM {db_prefix}members WHERE id_member='.$res['member_id'];
						$data_member = $smcFunc['db_query']('', $query_member,array());
						$res_member = $smcFunc['db_fetch_row']($data_member);
						$pending = '<br><b>[<i> '.$txt['glossary_suggestion_by'].' '.(!empty($res_member[1]) ? '<a href="'.$scripturl.'?action=profile;u='.$res_member[0].'"><u>'.$res_member[1].'</u></a>' : $txt['guest']).' </i>]</b>';
					}
					// If this is the member who made the suggestion ...
					elseif ($context['user']['id'] == $res['member_id'])
						$pending = '<br>[<i> '.$txt['glossary_suggestion_you_made'].' </i>]';
				}
				$words_list .= ($context['glossary_tooltip_bbc'] ? parse_bbc($res['definition']) : $res['definition']).$pending;
				$words_list .= '
						</div>&nbsp;<div style="display:inline;"></div>
						<input type="hidden" id="definition_text_'.$res['id'].'" value="'.$res['definition'].'">
						<input type="hidden" id="show_in_message_'.$res['id'].'" value="'.$res['show_in_message'].'">
						<input type="hidden" id="valid_'.$res['id'].'" value="'.$res['valid'].'">
						<input type="hidden" id="group_id_'.$res['id'].'" value="'.$res['group_id'].'">
						<input type="hidden" id="synonyms_'.$res['id'].'" value="'.$res['synonyms'].'">
						</td>
					</tr>';
			}

			// Get list of all IDs.
			$ids_list .= ';'.$res['id'];
		}
		$smcFunc['db_free_result']($data_glossary);

		// Store the first letter => needed for building a dynamic alphabet list.
		$groups_list[9999] = ' --- '.$txt['glossary_group_none'].' --- ';
		$context['glossary_letters'] .= '9999,';
		$context['glossary_elements'] .= '<a href="javascript:Display_glossary_for_letter(\'9999\')"><b><u>'.$groups_list[9999].'</u></b></a> | ';

		// Build new table.
		if (!empty($words_list))
			$full_words_list .= '
			<tr id="letter_9999" style=""><td>
				<div class="letter_selection">&nbsp;'.$groups_list[9999].'</div>
				<table align="left" style="border-collapse: collapse; width:100%;">
					'.$words_list.'
				</table>
			</td></tr>';
		$words_list = "";
	}

	// Return the full glossary listing.
	$context['glossary_elements'] .= '</td></tr>'.$full_words_list.'</table>';
	$context['glossary_elements'] .= '<input type="hidden" id="full_list_of_ids" value="'.$ids_list.'">';
}

function synonymCheck($newEdit)
{
	global $smcFunc, $txt;

	$synonymError = '';

	// A list of all the keywords currently in use.
	$query_keywords = 'SELECT word FROM {db_prefix}glossary';
	$data_keywords = $smcFunc['db_query']('', $query_keywords, array());
	while ($keywords = $smcFunc['db_fetch_row']($data_keywords))
		$current_keywords[] = $keywords[0];

	// A list of all the synonyms currently in use.
	$query_synonyms = 'SELECT synonyms FROM {db_prefix}glossary WHERE synonyms > \'\'';
	$data_synonyms = $smcFunc['db_query']('', $query_synonyms, array());
	while ($synonyms = $smcFunc['db_fetch_row']($data_synonyms))
		$current_synonyms[] = strtolower($synonyms[0]);
	foreach ($current_synonyms as $current_synonym => $synonym)
		$all_synonyms = empty($all_synonyms) ? $synonym : $all_synonyms.','.$synonym;
	$current_synonyms = array_unique(explode(',', $all_synonyms));

	// Check if the keyword is already being used as a synonym.
	if (in_array(strtolower($_POST[$newEdit.'_word']), $current_synonyms))
		$synonymError .= '\\n' . $txt['glossary_submission_error_2'] . '\\n';

	// If the keyword has synonyms check if they are already in use as keywords or synonyms.
	if (!empty($_POST[$newEdit.'_word_synonyms']))
	{
		$current_keywords = array_map('strtolower', $current_keywords);
		$synonyms = array_unique(explode(',', $_POST[$newEdit.'_word_synonyms']));
		foreach($synonyms as $synonym)
		{
			// Check if this synonym is already being used as keyword.
			if (in_array(strtolower($synonym), $current_keywords))
				$synonym_as_keyword = empty($synonym_as_keyword) ? $synonym : $synonym_as_keyword.', '.$synonym;

			if (!empty($synonym_as_keyword))
				$synonymError .= '\\n' . $txt['glossary_submission_error_3'] . $synonym_as_keyword . '\\n';

			// Check if this synonym is being used as a synonym for another keyword.
			if (in_array(strtolower($synonym), $current_synonyms))
				$synonym_in_use = empty($synonym_in_use) ? $synonym : $synonym_in_use.', '.$synonym;

			if (!empty($synonym_in_use))
				$synonymError .= '\\n' . $txt['glossary_submission_error_4'] . $synonym_in_use . '\\n';
		}
	}

	return $synonymError;
}

//=============================================================================================================

// ./Sources/Load.php
// call_integration_hook('integrate_load_theme');

function glossary_loadTheme()
{
	global $context, $modSettings;

	if ($modSettings['glossary_mod_enable'])
		Glossary_Headers($context);
}

function Glossary_Headers(&$context)
{
	global $context, $modSettings, $settings;

	if (!empty($context['current_topic']) && $modSettings['glossary_enable_tooltips'])
	{
		loadJavaScriptFile('glossary/glossary.jquery-ui.tooltip.js', array('default_theme' => true, 'minimize' => true, 'defer' => true), 'glossary_jquery_tooltip');
		loadCSSFile('glossary/glossary.jquery-ui.tooltip.css', array('default_theme' => true, 'minimize' => true), 'glossary_tooltip');

		$context['html_headers'] .= '
		<script language="JavaScript" type="text/javascript">
			$(function() { $(".glossary, #postbuttons img").glossTooltip({delay: 0, track: false, showURL: false}); });
		</script>';
	}

	$context['html_headers'] .= '
		<link rel="stylesheet" type="text/css" href="'. $settings['default_theme_url']. '/css/glossary/glossary.css" />';
}

//=============================================================================================================
// Mod hooks
//=============================================================================================================

// ./index.php
//call_integration_hook('integrate_actions', array(&$actionArray));

function glossary_actions(&$actionArray)
{
	$actionArray = array_merge(
		array(
			'glossary' => array('Glossary.php', 'Glossary'),
		),
		$actionArray
	);
}

//=============================================================================================================

// ./Sources/Display.php
//call_integration_hook('integrate_display_topic', array(&$topic_selects, &$topic_tables, &$topic_parameters));

function glossary_displayTopic(&$topic_selects, &$topic_tables, &$topic_parameters)
{
	global $context;

	// A 'context' variable is used so the Glossary list is only created once per topic.
	$context['glossary_list'] = '';
}

//=============================================================================================================

// ./Sources/ManagePermissions.php
// call_integration_hook('integrate_load_illegal_guest_permissions');

function glossary_illegalPerms()
{
	global $context, $modSettings;

	if (empty($modSettings['glossary_mod_enable']))
		return;

	$context['non_guest_permissions'] = array_merge(
		array(
			'glossary_admin',
		),
		$context['non_guest_permissions']
	);
}

//=============================================================================================================

// ./Sources/ManagePermissions.php
// call_integration_hook('integrate_load_permissions', array(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions));

function glossary_loadPerms(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
	global $modSettings;

	if (empty($modSettings['glossary_mod_enable']))
		return;

	$permissionList['membergroup'] = array_merge(
		array(
			'glossary_tooltip' => array(false, 'glossary'),
			'glossary_bbcode' => array(false, 'glossary'),
			'glossary_view' => array(false, 'glossary'),
			'glossary_suggest' => array(false, 'glossary'),
			'glossary_admin' => array(false, 'glossary'),
		),
		$permissionList['membergroup']
	);
}

//=============================================================================================================

// ./Sources/ManageSettings.php
// call_integration_hook('integrate_modify_modifications', array(&$subActions));

function glossary_modifyMods(&$subActions)
{
	$subActions['glossary'] = 'Glossary_Settings';
}

function Glossary_Settings()
{
	global $context, $scripturl, $txt;

	$config_vars = array(
		array('check', 'glossary_mod_enable'),
		'',
		array('check', 'glossary_enable_tooltips'),
		array('check', 'glossary_tooltip_once'),
		array('check', 'glossary_case_sensitive'),
		'',
		array('check', 'glossary_enable_numeric'),
		array('check', 'glossary_enable_synonyms'),
		array('check', 'glossary_enable_groups'),
		array('check', 'glossary_tooltip_bbc'),
		array('text', 'glossary_separator', 1),
		'',
		array('check', 'glossary_show_used_chars'),
		array('check', 'glossary_show_author_admin'),
		array('check', 'glossary_show_author_all'),
		array('check', 'glossary_show_tooltips_default'),
		array('check', 'glossary_approve_keyword_default'),
		array('check', 'glossary_admin_context_menu'),
		array('int', 'glossary_word_width',4),
		array('int', 'glossary_definition_width',4),
	);

	// Saving?
	if (isset($_GET['save']))
	{
		checkSession();
		saveDBSettings($config_vars);
		redirectexit('action=admin;area=modsettings;sa=glossary');
	}

	$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=glossary';
	$context['settings_title'] = $txt['glossary'];

	prepareDBSettingContext($config_vars);
}

//=============================================================================================================

// ./Sources/Subs.php
// call_integration_hook('integrate_bbc_codes', array(&$codes, &$no_autolink_tags));

function glossary_bbcCodes(&$codes)
{
	// Add glossary BBCode
	$codes[] = array(
		'tag' => 'glossary',
		'type' => 'unparsed_content',
		'content' => '$1',
		'validate' => function(&$tag, &$data, $disabled)
		{
			global $sourcedir;
			require_once($sourcedir . '/Glossary.php');
			$data = Glossary_BBCode(addslashes(trim($data)));
		},
	);
}

function Glossary_BBCode($word)
{
	global $context, $modSettings, $smcFunc;

	if (empty($modSettings['glossary_enable_tooltips']))
		return $word;

	// Find glossary BBCodes that are not shown in tooltips.
	$data_glossary = $smcFunc['db_query']('', '
		SELECT word
		FROM {db_prefix}glossary
		WHERE word = {string:word} AND valid = {int:valid} AND show_in_message = {int:show_in_message}',
		array(
			'word' => $word,
			'valid' => 1,
			'show_in_message' => 0,
		)
	);
	if ($smcFunc['db_num_rows']($data_glossary) == 1)
		return addslashes(htmlspecialchars($word));
	else
		return stripslashes($word);
}

//=============================================================================================================

// ./Sources/Subs.php
// call_integration_hook('integrate_menu_buttons', array(&$buttons));

function glossary_menuButtons(&$buttons)
{
	global $modSettings, $scripturl, $txt;

	// Add the Glossary button
	$buttons = array_merge(
		array_slice($buttons, 0, array_search('mlist', array_keys($buttons), true) + 1),
			array (
			'glossary' => array(
				'title' => $txt['glossary'],
				'href' => $scripturl . '?action=glossary',
				'show' => allowedTo('glossary_view'),
				'icon' => 'glossary/glossary.png',
				'sub_buttons' => array(
				),
			),
			$buttons
		),
	);
}

//=============================================================================================================

// ./Sources/Subs.php
// call_integration_hook('integrate_pre_parsebbc', array(&$message, &$smileys, &$cache_id, &$parse_tags))

function glossary_preParseBBC(&$message)
{
	global $modSettings;

	// Display the keyword definition as a tooltip.
	if ($modSettings['glossary_mod_enable'] && $modSettings['glossary_enable_tooltips'] && allowedTo('glossary_tooltip'))
		$message = Glossary_Tooltip($message);
}

// Parse the message for showing the glossary keywords.
function Glossary_Tooltip($message)
{
	global $context, $modSettings, $smcFunc;

	// Return if the message does not contain a glossary BBCode.
	preg_match('/\[glossary\].*?\[\/glossary\]/', $message, $matches);
	if (empty($matches))
		return $message;

	// Remove 'https(s)://' from after the opening glossary BBCode so the tooltip displays correctly.
	if ($modSettings['glossary_tooltip_bbc'])
		$message = preg_replace('~\[glossary\]https?://~', '[glossary]', $message);

	// A 'context' variable is used so the Glossary list is only created once per topic.
	if (empty($context['glossary_list']))
	{
		// Build full glossary list.
		$context['glossary_list'] = array();
		$data_glossary = $smcFunc['db_query']('', '
			SELECT *
			FROM {db_prefix}glossary
			WHERE valid = {int:valid} AND show_in_message = {int:show_in_message}
			ORDER BY word ASC',
			array(
				'valid' => 1,
				'show_in_message' => 1,
			)
		);
		while ($res = $smcFunc['db_fetch_assoc']($data_glossary))
		{
			// If BBC in tooltips is enabled ...
			if ($modSettings['glossary_tooltip_bbc'])
			{
				// Remove 'https(s)://' from both the keyword and keyword definition.
				$res['word'] = preg_replace('~https?://~', '', $res['word']);
				$definition = trim(preg_replace('~https?://~', '', $res['definition']));

				// Convert the bold, italic, underline and strikethrough BBCodes to HTML.
				$definition = preg_replace('~\[b\](.*)\[\/b\]~', '<strong>$1</strong>', $definition);
				$definition = preg_replace('~\[i\](.*)\[\/i\]~', '<em>$1</em>', $definition);
				$definition = preg_replace('~\[u\](.*)\[\/u\]~', '<ins>$1</ins>', $definition);
				$definition = preg_replace('~\[s\](.*)\[\/s\]~', '<del>$1</del>', $definition);

				// Replace the separator character with a line break.
				if ($modSettings['glossary_separator'])
					$definition = str_replace($modSettings['glossary_separator'], '<br>', $definition);
			}
			else
				$definition = trim($res['definition']);

			$context['glossary_list'][trim($res['word'])] = $definition;

			// If synonyms are enabled and exist add them in the list.
			if ($modSettings['glossary_enable_synonyms'] && $res['synonyms'])
			{
				$synonyms = array_unique(explode(',', $res['synonyms']));
				foreach ($synonyms as $synonym)
					$context['glossary_list'][trim($synonym)] = $definition;
			}
		}
		$smcFunc['db_free_result']($data_glossary);
	}
	$words = $context['glossary_list'];

	// Add a prefix to keyword in definition.
	$arr_unique_words = array_keys($words);
	$arr_prefixed_words = array();
	$prefix = 'wgYBA1Mq@Xn8y#zWL';
	foreach ($arr_unique_words as $elem)
		array_push($arr_prefixed_words,$prefix.$elem);

	$arr_unique_defs = array_values($words);
	$arr_treated_defs = array();
	foreach ($arr_unique_defs as $def)
		array_push($arr_treated_defs,str_ireplace($arr_unique_words, $arr_prefixed_words, $def));

	$words = array();
	$x=0;
	foreach ($arr_unique_words as $elem)
	{
		$words[$elem] = $arr_treated_defs[$x];
		$x++;
	}

	// The value of this variable is used by the mod's javascript to return a a correctly formatted keyword definition.
	// If the variable value is changed the mod's Javascript file must also be updated.
	// Javascript file: ./Themes/default/scripts/glossary/glossary.jquery-ui.tooltip.js
	$fromGlossary = 'fr0mGl0ss@ry';

	// Search keywords in message.
	foreach ($words as $word => $definition)
	{
		// No sneaky or accidental html highlighting.
		$word = strtr(trim(un_htmlspecialchars($word)), array('<' => '', '&lt;' => '', '>' => '', '&gt;' => '', '=' => '', '/' => '', '\\' => ''));

		// If the keyword is now empty, skip this.
		if (!empty($word))
		{
			// Fix the international characters in the keyword too.
			$word = strtr($smcFunc['htmlspecialchars']($word), array('\\\'' => '\''));

			// Do the highlight in the message.
			$message = preg_replace_callback(
				'~((<a.+\/a>)|(\b'. preg_quote(strtr($word, array('\'' => '&#039;')), '/'). '(?=[^A-Za-z0-9&agrave;-??-??-?_\-s]))|(\b'.preg_quote(strtr($word, array('\'' => '&#039;')), '/').'\b))~'. ($modSettings['glossary_case_sensitive'] ? '' : 'i'),
				function ($matches) use ($definition, $fromGlossary)
				{
					return $matches[2] == $matches[1] ? stripslashes($matches[1]) : '<span class="glossary" title="' . addslashes($definition) . $fromGlossary . '">' . $matches[1] . '</span>';
				},
				$message,
				$modSettings['glossary_tooltip_once'] == 1 ? 1 : -1
			);
		}
	}

	// Remove prefix that was added earlier.
	$message = str_replace($prefix, '', $message);

	return $message;
}

//=============================================================================================================

// ./Sources/Subs-Editor.php
// call_integration_hook('integrate_bbc_buttons', array(&$context['bbc_tags'], &$editor_tag_map));

function glossary_bbcButtons(&$bbc_tags)
{
	global $context, $modSettings, $txt;

	if (empty($modSettings['glossary_mod_enable']) || !allowedTo('glossary_bbcode'))
		return;

	// Add the 'glossary' button after the 'quote' button.
	foreach ($context['bbc_tags'] as $row_num => $row)
	{
		$glossary_bbc = array();
		foreach ($row as $tag)
		{
			$glossary_bbc[] = $tag;
			if (isset($tag['code']) && $tag['code'] === 'quote')
			{
				$glossary_bbc[] = array(
					'image' => '../glossary/glossary',
					'code' => 'glossary',
					'before' => '[glossary]',
					'after' => '[/glossary]',
					'description' => $txt['glossary_bbc']
				);
			}
		}
		$context['bbc_tags'][$row_num] = $glossary_bbc;
	}
}

//=============================================================================================================

// ./Sources/Subs-Menu.php
// call_integration_hook('integrate_admin_areas', array(&$admin_areas)) via
// call_integration_hook('integrate_' . $menu_context['current_action'] . '_areas', array(&$menuData));
function glossary_adminAreas(&$admin_areas)
{
	global $txt;

	return $admin_areas['config']['areas']['modsettings']['subsections'] += array('glossary' => array(sprintf($txt['glossary'])));
}

?>
