<?php

/*************************************************************************************************************
 * Glossary.php - Script for Glossary for SMF 2.1 mod (v1.2)
 **************************************************************************************************************
 * This mod is licensed under the 2-Clause BSD License, which can be found here:
 *    https://opensource.org/licenses/BSD-2-Clause
 **************************************************************************************************************
 * Copyright (c) 2008-2009 Slinouille, 2024 Kathy Leslie
 * Redistribution and use in source and binary forms, with or without modification, are permitted provided
 * that the following conditions are met:
 *    1.    Redistributions of source code must retain the above copyright notice, this list of conditions
 *        and the following disclaimer.
 *    2.    Redistributions in binary form must reproduce the above copyright notice, this list of conditions and
 *        the following disclaimer in the documentation and/or other materials provided with the distribution.
 * This software is provided by the copyright holders and contributors "as is" and any express or implied
 * warranties, including, but not limited to, the implied warranties of merchantability and fitness for a
 * particular purpose are disclaimed. In no event shall the copyright holder or contributors be liable for
 * any direct, indirect, incidental, special, exemplary, or consequential damages (including, but not
 * limited to, procurement of substitute goods or services; loss of use, data, or profits; or business
 * interruption) however caused and on any theory of liability, whether in contract, strict liability, or
 * tort (including negligence or otherwise) arising in any way out of the use of this software, even if
 * advised of the possibility of such damage.
 *************************************************************************************************************/

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

class Glossary
{
	public static function Main()
	{
		global $context, $txt, $smcFunc, $settings, $modSettings, $scripturl;
		global $glossary_keyword, $glossary_definition, $glossary_synonyms;

		$context['linktree'][] = array(
			'url' => $scripturl . '?action=glossary',
			'name' => $txt['glossary'],
		);

		// Initialise the error messages.
		$context['glossary_action_status'] = '';
		$context['glossary_error_submit'] = '';
		$context['glossary_error_submit_message'] = '';

		// Find out if there are any keyword/synonym conflicts.
		if (allowedTo('glossary_admin'))
			$context['synonymCheck'] = self::synonymCheck('check');

		// Get the template ready.
		$context['glossary_tooltip_bbc'] = $modSettings['glossary_tooltip_bbc'];
		$context['page_title'] = $txt['glossary'];
		loadTemplate('Glossary');

		// ADD a keyword
		if ((allowedTo('glossary_admin') || allowedTo('glossary_suggest')) && !empty($_POST['submit_new_word'])) {
			// Security checks.
			checkSession('post');

			// Check for keyword/synonym conflicts.
			$synonymError = self::synonymCheck('new');

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
			if (empty($synonymError) && empty($res[0])) {
				// Do some checking on the keyword, definition and synonyms.
				self::keywordCheck($_POST['new_word'], $_POST['new_definition'], $_POST['new_synonyms']);

				// Add keyword to database.
				$validword = isset($_POST['new_valid']) && $_POST['new_valid'] == 'on' ? 1 : 0;
				$show_in_message = isset($_POST['new_show_in_message']) && $_POST['new_show_in_message'] == 'on' ? 1 : 0;
				$case_sensitive = isset($_POST['new_case_sensitive']) && $_POST['new_case_sensitive'] == 'on' ? 1 : 0;

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
						'case_sensitive' => 'int',
					),
					array(
						$glossary_keyword,
						$glossary_definition,
						(int)$context['user']['id'],
						mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y')),
						(int)$validword,
						$glossary_synonyms,
						(int)$show_in_message,
						isset($_POST['new_group']) ? (int)$_POST['new_group'] : '0',
						(int)$case_sensitive,
					),
					array()
				);
			} else {
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
		} // EDIT a keyword
		elseif ((allowedTo('glossary_admin') || (!empty($_POST['is_author_of_word']) && $_POST['is_author_of_word'] == 'true')) && !empty($_POST['submit_edit_word'])) {
			// Security checks.
			checkSession('post');

			// Check for keyword/synonym conflicts.
			$synonymError = self::synonymCheck('edit');

			// Check if the keyword already exists.
			$data_glossary = $smcFunc['db_query']('', '
				SELECT COUNT(*)
				FROM {db_prefix}glossary
				WHERE word = {string:edit_word}
					AND id != {int:id}',
				array(
					'id' => (int)$_POST['edit_word_id'],
					'edit_word' => $_POST['edit_word'],
				)
			);
			$res = $smcFunc['db_fetch_row']($data_glossary);
			if (empty($synonymError) && empty($res[0])) {
				// Do some checking on the keyword, definition and synonyms.
				self::keywordCheck($_POST['edit_word'], $_POST['edit_definition'], $_POST['edit_synonyms']);

				// Update the keyword.
				$validword = isset($_POST['edit_valid']) && $_POST['edit_valid'] == 'on' ? 1 : 0;
				$show_in_message = isset($_POST['edit_show_in_message']) && $_POST['edit_show_in_message'] == 'on' ? 1 : 0;
				$case_sensitive = isset($_POST['edit_case_sensitive']) && $_POST['edit_case_sensitive'] == 'on' ? 1 : 0;

				if (isset($_POST['is_author_of_word']) && $_POST['is_author_of_word'] == 'true')
					$smcFunc['db_query']('', '
						UPDATE {db_prefix}glossary
						SET word = {string:word},
							definition = {string:definition},
							date = {string:date},
							synonyms = {string:synonyms},
							show_in_message = {int:show_in_message},
							group_id = {int:group_id},
							case_sensitive = {int:case_sensitive}
						WHERE id = {int:id}',
						array(
							'id' => (int)$_POST['edit_word_id'],
							'word' => $glossary_keyword,
							'definition' => $glossary_definition,
							'date' => mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y')),
							'synonyms' => $glossary_synonyms,
							'show_in_message' => (int)$show_in_message,
							'group_id' => isset($_POST['edit_group']) ? (int)$_POST['edit_group'] : '0',
							'case_sensitive' => (int)$case_sensitive,
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
							show_in_message = {int:show_in_message},
							group_id = {int:group_id},
							case_sensitive = {int:case_sensitive}
						WHERE id = {int:id}',
						array(
							'id' => (int)$_POST['edit_word_id'],
							'word' => $glossary_keyword,
							'definition' => $glossary_definition,
							'date' => mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y')),
							'valid' => (int)$validword,
							'synonyms' => $glossary_synonyms,
							'show_in_message' => (int)$show_in_message,
							'group_id' => isset($_POST['edit_group']) ? (int)$_POST['edit_group'] : '0',
							'case_sensitive' => (int)$case_sensitive,
						)
					);
			} else {
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
		} // DELETE a keyword
		elseif (allowedTo('glossary_admin') && !empty($_POST['submit_delete_word']) && !empty($_POST['id_word_to_delete'])) {
			// Security checks.
			checkSession('post');

			$smcFunc['db_query']('', '
				DELETE FROM {db_prefix}glossary
				WHERE id = {int:id}',
				array(
					'id' => $_POST['id_word_to_delete'],
				)
			);
		} // APPROVE a keyword
		elseif (allowedTo('glossary_admin') && isset($_POST['action_on_word']) && $_POST['action_on_word'] == 'approve_word' && !empty($_POST['id_word'])) {
			// Security checks.
			checkSession('post');

			$smcFunc['db_query']('', '
				UPDATE {db_prefix}glossary
				SET valid = {int:valid}
				WHERE id = {int:id}',
				array(
					'valid' => '1',
					'id' => (int)$_POST['id_word'],
				)
			);
		} // UNAPPROVE a keyword
		elseif (allowedTo('glossary_admin') && isset($_POST['action_on_word']) && $_POST['action_on_word'] == 'unapprove_word' && !empty($_POST['id_word'])) {
			// Security checks.
			checkSession('post');

			$smcFunc['db_query']('', '
				UPDATE {db_prefix}glossary
				SET valid = {int:valid}
				WHERE id = {int:id}',
				array(
					'id' => (int)$_POST['id_word'],
					'valid' => '0',
				)
			);
		} // ENABLE TOOLTIP for a keyword
		elseif (allowedTo('glossary_admin') && isset($_POST['action_on_word']) && $_POST['action_on_word'] == 'enable_tooltip' && !empty($_POST['id_word'])) {
			// Security checks.
			checkSession('post');

			$smcFunc['db_query']('', '
				UPDATE {db_prefix}glossary
				SET show_in_message = {int:show_in_message}
				WHERE id = {int:id}',
				array(
					'id' => (int)$_POST['id_word'],
					'show_in_message' => '1',
				)
			);
		} // DISABLE TOOLTIP for a keyword
		elseif (allowedTo('glossary_admin') && isset($_POST['action_on_word']) && $_POST['action_on_word'] == 'disable_tooltip' && !empty($_POST['id_word'])) {
			// Security checks.
			checkSession('post');

			$smcFunc['db_query']('', '
				UPDATE {db_prefix}glossary
				SET show_in_message = {int:show_in_message}
				WHERE id = {int:id}',
				array(
					'id' => (int)$_POST['id_word'],
					'show_in_message' => '0',
				)
			);
		} // ENABLE 'CASE SENSITIVE' for a keyword
		elseif (allowedTo('glossary_admin') && isset($_POST['action_on_word']) && $_POST['action_on_word'] == 'enable_case_sensitive' && !empty($_POST['id_word'])) {
			// Security checks.
			checkSession('post');

			$smcFunc['db_query']('', '
				UPDATE {db_prefix}glossary
				SET case_sensitive = {int:case_sensitive}
				WHERE id = {int:id}',
				array(
					'id' => (int)$_POST['id_word'],
					'case_sensitive' => '1',
				)
			);
		} // DISABLE 'CASE SENSITIVE' for a keyword
		elseif (allowedTo('glossary_admin') && isset($_POST['action_on_word']) && $_POST['action_on_word'] == 'disable_case_sensitive' && !empty($_POST['id_word'])) {
			// Security checks.
			checkSession('post');

			$smcFunc['db_query']('', '
				UPDATE {db_prefix}glossary
				SET case_sensitive = {int:case_sensitive}
				WHERE id = {int:id}',
				array(
					'id' => (int)$_POST['id_word'],
					'case_sensitive' => '0',
				)
			);
		} // APPROVE a SELECTION
		elseif (allowedTo('glossary_admin') && isset($_POST['action_on_list']) && $_POST['action_on_list'] == 'approve_selected' && !empty($_POST['list_of_ids'])) {
			// Security checks.
			checkSession('post');

			$mylist = explode(';', $_POST['list_of_ids']);
			foreach ($mylist as $newid) {
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}glossary
					SET valid = {int:valid}
					WHERE id = {int:id}',
					array(
						'id' => (int)$newid,
						'valid' => '1',
					)
				);
			}
		} // UNAPPROVE a SELECTION
		elseif (allowedTo('glossary_admin') && isset($_POST['action_on_list']) && $_POST['action_on_list'] == 'unapprove_selected' && !empty($_POST['list_of_ids'])) {
			// Security checks.
			checkSession('post');

			$mylist = explode(';', $_POST['list_of_ids']);
			foreach ($mylist as $newid) {
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}glossary
					SET valid = {int:valid}
					WHERE id = {int:id}',
					array(
						'id' => (int)$newid,
						'valid' => '0',
					)
				);
			}
		} // ENABLE TOOLTIP for a SELECTION
		elseif (allowedTo('glossary_admin') && isset($_POST['action_on_list']) && $_POST['action_on_list'] == 'tooltip_on_selected' && !empty($_POST['list_of_ids'])) {
			// Security checks.
			checkSession('post');

			$mylist = explode(';', $_POST['list_of_ids']);
			foreach ($mylist as $newid) {
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}glossary
					SET show_in_message = {int:show_in_message}
					WHERE id = {int:id}',
					array(
						'id' => (int)$newid,
						'show_in_message' => '1',
					)
				);
			}
		} // DISABLE TOOLTIP for a SELECTION
		elseif (allowedTo('glossary_admin') && isset($_POST['action_on_list']) && $_POST['action_on_list'] == 'tooltip_off_selected' && !empty($_POST['list_of_ids'])) {
			// Security checks.
			checkSession('post');

			$mylist = explode(';', $_POST['list_of_ids']);
			foreach ($mylist as $newid) {
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}glossary
					SET show_in_message = {int:show_in_message}
					WHERE id = {int:id}',
					array(
						'id' => (int)$newid,
						'show_in_message' => '0',
					)
				);
			}
		} // ENABLE 'CASE SENSITIVE' for a SELECTION
		elseif (allowedTo('glossary_admin') && isset($_POST['action_on_list']) && $_POST['action_on_list'] == 'case_sensitive_on_selected' && !empty($_POST['list_of_ids'])) {
			// Security checks.
			checkSession('post');

			$mylist = explode(';', $_POST['list_of_ids']);
			foreach ($mylist as $newid) {
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}glossary
					SET case_sensitive = {int:case_sensitive}
					WHERE id = {int:id}',
					array(
						'id' => (int)$newid,
						'case_sensitive' => '1',
					)
				);
			}
		} // DISABLE 'CASE SENSITIVE' for a SELECTION
		elseif (allowedTo('glossary_admin') && isset($_POST['action_on_list']) && $_POST['action_on_list'] == 'case_sensitive_off_selected' && !empty($_POST['list_of_ids'])) {
			// Security checks.
			checkSession('post');

			$mylist = explode(';', $_POST['list_of_ids']);
			foreach ($mylist as $newid) {
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}glossary
					SET case_sensitive = {int:case_sensitive}
					WHERE id = {int:id}',
					array(
						'id' => (int)$newid,
						'case_sensitive' => '0',
					)
				);
			}
		} // CHANGE GROUP for a SELECTION
		elseif (allowedTo('glossary_admin') && isset($_POST['action_on_list']) && $_POST['action_on_list'] == 'change_group_selected' && !empty($_POST['list_of_ids']) && !empty($_POST['group_id'])) {
			// Security checks.
			checkSession('post');

			$mylist = explode(';', $_POST['list_of_ids']);
			foreach ($mylist as $newid) {
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}glossary
					SET group_id = {int:group_id}
					WHERE id = {int:id}',
					array(
						'id' => (int)$newid,
						'group_id' => (int)$_POST['group_id'],
					)
				);
			}
		} // ADD a new GROUP
		elseif (allowedTo('glossary_admin') && !empty($_POST['manage_new_group'])) {
			// Security checks.
			checkSession('post');

			// No "http(s)://", multiple spaces or trailing slashes in category names.
			$manage_new_group = preg_replace(array('~https?://~i', '~&#0?39;~', '/\s+/u'), array('', '\'', ' '), rtrim($_POST['manage_new_group'], '/'));
			$slashGroup = $manage_new_group . '/';
			$_POST['manage_new_group'] = rtrim($_POST['manage_new_group'], '/');

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
			if (empty($res[0])) {
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
			} else {
				// Category already exists - return error.
				$context['glossary_action_status'] = 'check_new_group';
				$context['glossary_error_submit'] = true;
				$context['glossary_error_submit_message'] = $txt['glossary_submission_error_5'];
			}
		} // DELETE a GROUP.
		elseif (allowedTo('glossary_admin') && empty($_POST['update_category_title']) && !empty($_POST['group_update'])) {
			// Security checks.
			checkSession('post');

			// Delete from database.
			$smcFunc['db_query']('', '
				DELETE FROM {db_prefix}glossary_groups
				WHERE id = {int:id}',
				array(
					'id' => (int)$_POST['group_update'],
				)
			);
		} // UPDATE a GROUP.
		elseif (allowedTo('glossary_admin') && !empty($_POST['update_category_title']) && !empty($_POST['group_update'])) {
			// Security checks.
			checkSession('post');

			// No "http(s)://", multiple spaces or trailing slashes in category names.
			$update_category_title = preg_replace(array('~https?://~i', '~&#0?39;~', '/\s+/u'), array('', '\'', ' '), rtrim($_POST['update_category_title'], '/'));
			$slashGroup = $update_category_title . '/';
			$_POST['update_category_title'] = rtrim($_POST['update_category_title'], '/');

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
			if (empty($res[0])) {
				// Update in database.
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}glossary_groups
					SET title = {string:title}
					WHERE id = {int:id}',
					array(
						'id' => (int)$_POST['group_update'],
						'title' => $update_category_title,
					)
				);
			} else {
				// Category already exists - return error.
				$context['glossary_action_status'] = 'check_update_group';
				$context['glossary_error_submit'] = true;
				$context['glossary_error_submit_message'] = $txt['glossary_submission_error_5'];
			}
		}

		// An error occurred - show the error message as an alert and reload the page.
		// (Need to reload the page to prevent alert showing on page refresh and weird font resizing).
		$glossary_sa = isset($_GET['sa']) ? ';sa=' . $_GET['sa'] : '';
		if ($context['glossary_error_submit'] == true)
			echo '
				<script language="JavaScript" type="text/javascript">
					alert("' . $context['glossary_error_submit_message'] . '");
					window.location.href="' . $scripturl . '?action=glossary' . $glossary_sa . '";
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

		// ==================================================================
		// Prepare glossary list by ALPHABETIC ORDER
		// ==================================================================
		if ((isset($_GET['sa']) && $_GET['sa'] == 'alphabetic') || !isset($_GET['sa'])) {
			$letter_in_progress = '';
			$context['glossary_letters'] = '';
			$nb_words_for_letter_in_progress = 0;
			$alphabet_list = array();

			// Start of what to show ...

			$context['glossary_elements'] = '<table id="table_full_table" width="100%">';

			$data_glossary = $smcFunc['db_query']('', '
				SELECT *
				FROM {db_prefix}glossary
				ORDER BY word ASC',
				array()
			);
			while ($res = $smcFunc['db_fetch_assoc']($data_glossary)) {
				if ($smcFunc['strtoupper']($smcFunc['substr']($res['word'], 0, 1)) != $letter_in_progress) {
					if ($nb_words_for_letter_in_progress != 0) {
						// Write the title and keywords.
						$full_words_list .= '
							<tr id="letter_' . $letter_in_progress . '" style=""><td>
								<div class="letter_selection">&nbsp;' . $letter_in_progress . '</div>
								<table align="left" style="border-collapse: collapse; width: 100%;">
									' . $words_list . '
								</table>
							</td></tr>';

						// Store the first letter => needed for building a dynamic alphabet list.
						array_push($alphabet_list, $letter_in_progress);
						$context['glossary_letters'] .= $letter_in_progress . ',';
					}
					$nb_words_for_letter_in_progress = 0;
					$words_list = '';
				}

				// Create the keyword/defintions table.
				$words_list = self::keywordList($res, $words_list);

				// Get list of all IDs.
				$ids_list .= ';' . $res['id'];

				// Loop arguments.
				$letter_in_progress = $smcFunc['strtoupper']($smcFunc['substr']($res['word'], 0, 1));
				$nb_words_for_letter_in_progress++;
			}
			$smcFunc['db_free_result']($data_glossary);

			// Manage last entry.
			if ($nb_words_for_letter_in_progress != 0) {
				// Write the title and keywords.
				$full_words_list .= '
					<tr id="letter_' . $letter_in_progress . '" style=""><td>
						<div class="letter_selection">&nbsp;' . $letter_in_progress . '</div>
						<table align="left" style="border-collapse: collapse; width: 100%;">
							' . $words_list . '
						</table>
					</td></tr>';

				// Store the first letter => needed for building a dynamic alphabet list.
				array_push($alphabet_list, $letter_in_progress);
				$context['glossary_letters'] .= $letter_in_progress . ',';
			}

			// Prepare alphabetic list.
			$show_usedChars = $modSettings['glossary_show_used_chars'];
			$context['glossary_elements'] .= '<tr><td colspan="3"><a href="javascript:Display_glossary_for_letter(\'all\')"><b><u>' . $txt['glossary_all'] . '</u></b></a>';
			for ($i = ord('A'); $i <= ord('Z'); $i++) {
				if (in_array(chr($i), $alphabet_list))
					$context['glossary_elements'] .= '<a href="javascript:Display_glossary_for_letter(\'' . chr($i) . '\')"> | <b><u>' . chr($i) . '</u></b></a>';

				// Hide unused alphabetic characters.
				if (!$show_usedChars && !in_array(chr($i), $alphabet_list))
					$context['glossary_elements'] .= '<i> | ' . chr($i) . '</i>';
			}

			// Add numeric list if required.
			if ($modSettings['glossary_enable_numeric']) {
				for ($i = 0; $i < 10; $i++) {
					if (in_array($i, $alphabet_list))
						$context['glossary_elements'] .= '<a href="javascript:Display_glossary_for_letter(\'' . $i . '\')"> | <b><u>' . $i . '</u></b></a>';

					// Hide unused numeric characters.
					if (!$show_usedChars && !in_array($i, $alphabet_list))
						$context['glossary_elements'] .= '<i> | ' . $i . '</i>';
				}
			}
		}
		// ===============================================================================
		// Manage GROUP order
		// ===============================================================================
		elseif (isset($_GET['sa']) && $_GET['sa'] == 'categories') {
			$groups_list = array();
			$context['glossary_letters'] = '';
			$words_list = '';
			$last_group_id = 0;

			// Prepare groups list
			$context['glossary_elements'] = '<table id="table_full_table" width="100%"><tr><td colspan="3"><a href="javascript:Display_glossary_for_letter(\'all\')"><b><u>' . $txt['glossary_all'] . '</u></b></a>';

			// Go through all groups.
			$data_groups = $smcFunc['db_query']('', '
				SELECT *
				FROM {db_prefix}glossary_groups
				ORDER BY title ASC',
				array()
			);
			while ($res_groups = $smcFunc['db_fetch_assoc']($data_groups)) {
				// Check if keywords are available for group.
				$data_glossary = $smcFunc['db_query']('', '
					SELECT COUNT(id)
					FROM {db_prefix}glossary
					WHERE (group_id = {int:group_id})
					GROUP BY id
					ORDER BY word ASC',
					array(
						'group_id' => (int)$res_groups['id'],
					)
				);
				$res_glossary = $smcFunc['db_fetch_row']($data_glossary);
				if (is_array($res_glossary) && $res_glossary[0] > 0) {
					// Found list of keywords.
					$data_glossary = $smcFunc['db_query']('', '
						SELECT *
						FROM {db_prefix}glossary
						WHERE (group_id = {int:group_id})
						ORDER BY word ASC',
						array(
							'group_id' => (int)$res_groups['id'],
						)
					);
					while ($res = $smcFunc['db_fetch_assoc']($data_glossary)) {
						// Create the keyword/defintions table.
						$words_list = self::keywordList($res, $words_list);

						// Get list of all IDs.
						$ids_list .= ';' . $res['id'];
					}
					$smcFunc['db_free_result']($data_glossary);

					if ($words_list) {
						// Store the group name => needed for building a dynamic category list.
						$groups_list[$res_groups['id']] = $res_groups['title'];
						$context['glossary_letters'] .= $res_groups['id'] . ',';
						$context['glossary_elements'] .= '<a href="javascript:Display_glossary_for_letter(\'' . $res_groups['id'] . '\')"> | <b><u>' . $res_groups['title'] . '</u></b></a>';

						// Build new table.
						$full_words_list .= '
						<tr id="letter_' . $res_groups['id'] . '" style=""><td>
							<div class="letter_selection">&nbsp;' . $res_groups['title'] . '</div>
							<table align="left" style="border-collapse: collapse; width: 100%;">
								' . $words_list . '
							</table>
						</td></tr>';
					}
					$words_list = '';
				} // Just add the group in list.
				else
					$context['glossary_elements'] .= '<i> | ' . $res_groups['title'] . '</i>';
			}

			// =======================================
			// Get a list of none categorized keywords
			// =======================================
			$data_glossary = $smcFunc['db_query']('', '
				SELECT *
				FROM {db_prefix}glossary
				WHERE (group_id = {int:group_id})
				ORDER BY word ASC',
				array(
					'group_id' => 0,
				)
			);
			while ($res = $smcFunc['db_fetch_assoc']($data_glossary)) {
				// Create the keyword/defintions table.
				$words_list = self::keywordList($res, $words_list);

				// Get list of all IDs.
				$ids_list .= ';' . $res['id'];
			}
			$smcFunc['db_free_result']($data_glossary);

			// Only add the 'No Category' link if there are keywords without a category.
			if ($words_list) {
				// Store the group name => needed for building a dynamic category list.
				$groups_list[9999] = ' --- ' . $txt['glossary_group_none'] . ' --- ';
				$context['glossary_letters'] .= '9999,';
				$context['glossary_elements'] .= '<a href="javascript:Display_glossary_for_letter(\'9999\')"> | <b><u>' . $groups_list[9999] . '</u></b></a>';

				$full_words_list .= '
				<tr id="letter_9999" style=""><td>
					<div class="letter_selection">&nbsp;' . $groups_list[9999] . '</div>
					<table align="left" style="border-collapse: collapse; width: 100%;">
						' . $words_list . '
					</table>
				</td></tr>';
			}
			$words_list = '';
		}

		// Return the full glossary listing.
		$context['glossary_elements'] .= '</td></tr>' . $full_words_list . '</table>';
		$context['glossary_elements'] .= '<input type="hidden" id="full_list_of_ids" value="' . $ids_list . '">';
	}

	public static function synonymCheck($checkNewEdit)
	{
		global $smcFunc, $txt;

		// An array of all the keywords currently saved in the DB
		$query_keywords = 'SELECT word FROM {db_prefix}glossary';
		$data_keywords = $smcFunc['db_query']('', $query_keywords, array());
		while ($keywords = $smcFunc['db_fetch_row']($data_keywords)) {
			$keywords[0] = trim(preg_replace('~&#0?39;~', '\'', $keywords[0]));
			$current_keywords[] = $keywords[0];
			$lower_keywords[] = $smcFunc['strtolower']($keywords[0]);
			$fixed_keywords[] = self::fixSynonym($keywords[0], false);
		}

		// No keywords yet.
		if (empty($current_keywords))
			return;

		// Unique keywords.
		$unique_keywords = array_unique($lower_keywords);

		// Just checking ...
		$synonymCheck = '';
		if ($checkNewEdit == 'check') {
			// Duplicate keywords.
			$duplicate_keywords = array_diff_key($current_keywords, $unique_keywords);
			if ($duplicate_keywords) {
				natcasesort($duplicate_keywords);
				$synonymCheck .= $txt['glossary_action_check_error_1'] . '<br><span class="glossary_heading">' . implode('<br>', $duplicate_keywords) . '</span><hr>';
			}

			// Suspect keywords.
			$suspect_keywords = array_diff($current_keywords, $fixed_keywords);
			if ($suspect_keywords)
				$synonymCheck .= $txt['glossary_action_check_error_2'] . '<br><span class="glossary_heading">' . implode('<br>', $suspect_keywords) . '</span><br><br>' . $txt['glossary_action_check_error_cssc_note'];

			// Keywords without valid definitions.
			$query_noDefinition = 'SELECT word FROM {db_prefix}glossary WHERE TRIM(definition) REGEXP "^(\\r\\n.*)*$"';
			$data_noDefinition = $smcFunc['db_query']('', $query_noDefinition, array());
			while ($noDefinition = $smcFunc['db_fetch_row']($data_noDefinition))
				$keywords_noDefinition[] = $noDefinition[0];

			if (!empty($keywords_noDefinition))
				$synonymCheck .= $txt['glossary_action_check_error_3'] . '<br><span class="glossary_heading">' . implode('<br>', $keywords_noDefinition) . '</span><hr>';
		}

		// An array of all the synonyms currently saved in the DB.
		$query_synonyms = 'SELECT synonyms FROM {db_prefix}glossary WHERE synonyms > \'\'';
		$data_synonyms = $smcFunc['db_query']('', $query_synonyms, array());
		while ($synonyms = $smcFunc['db_fetch_row']($data_synonyms))
			$current_synonyms[] = preg_replace('~&#0?39;~', '\'', $synonyms[0]);

		// Return if there are no synonyms defined.
		if (empty($current_synonyms) && empty($_POST[$checkNewEdit . '_synonyms']))
			return ($synonymCheck ? $synonymCheck . $txt['glossary_action_check_error_note'] . '<br><br>' : '');

		// Multiple synonyms are stored as a string for each keyword - convert them all to a string and then to an array.
		if (!empty($current_synonyms)) {
			foreach ($current_synonyms as $current_synonym => $synonym)
				$all_synonyms = empty($all_synonyms) ? $smcFunc['strtolower'](trim($synonym)) : $all_synonyms . ',' . $smcFunc['strtolower'](trim($synonym));
			$current_synonyms = explode(',', $all_synonyms);
			$fixed_synonyms = explode(',', self::fixSynonym($all_synonyms));
		}

		// Unique synonyms.
		$unique_synonyms = array_unique($current_synonyms);

		// Just checking ...
		if ($checkNewEdit == 'check') {
			if ($unique_synonyms) {
				// Synonyms as keywords.
				$synonyms_as_keywords = array_uintersect($unique_synonyms, $unique_keywords, 'strcasecmp');
				if ($synonyms_as_keywords)
					$synonymCheck .= $txt['glossary_action_check_error_4'] . '<br><span class="glossary_heading">' . implode('<br>', $synonyms_as_keywords) . '</span><hr>';

				// Duplicate synonyms.
				$duplicate_synonyms = array_unique(array_diff_key($current_synonyms, $unique_synonyms));
				if ($duplicate_synonyms) {
					natcasesort($duplicate_synonyms);
					$synonymCheck .= $txt['glossary_action_check_error_5'] . '<br><span class="glossary_heading">' . implode('<br>', $duplicate_synonyms) . '</span><hr>';
				}
			}

			// Suspect synonyms.
			$suspect_synonyms = array_diff($current_synonyms, $fixed_synonyms);
			if ($suspect_synonyms)
				$synonymCheck .= $txt['glossary_action_check_error_6'] . '<br><span class="glossary_heading">' . implode('<br>', $suspect_synonyms) . '</span><br><br>' . $txt['glossary_action_check_error_cssc_note'];

			return ($synonymCheck ? $synonymCheck . $txt['glossary_action_check_error_note'] . '<br><br>' : '');
		}

		// Now for some synonym error checking ...
		$synonymError = '';
		$add_errorNote = false;

		// Check if the keyword is already being used as a synonym.
		if ($unique_synonyms && in_array($smcFunc['strtolower'](trim(self::fixSynonym($_POST[$checkNewEdit . '_word']), false)), $unique_synonyms))
			$synonymError .= '\\n' . $txt['glossary_submission_error_2'] . '\\n';

		// If the keyword has new/updated synonyms check if they are already in use as keywords or synonyms.
		if (!empty($_POST[$checkNewEdit . '_synonyms'])) {
			// This is an existing keyword - check if it already has synonyms.
			if ($checkNewEdit == 'edit') {
				$query_synonyms = 'SELECT synonyms FROM {db_prefix}glossary WHERE id = ' . $_POST['edit_word_id'];
				$data_synonyms = $smcFunc['db_query']('', $query_synonyms, array());
				list($keyword_synonyms) = $smcFunc['db_fetch_row']($data_synonyms);

				// If this keyword has synonyms remove them from the unique synonyms array.
				if (trim($keyword_synonyms)) {
					$keyword_synonyms = array_unique(explode(',', $smcFunc['strtolower'](trim(self::fixSynonym($keyword_synonyms, true)))));
					$unique_synonyms = array_diff($unique_synonyms, $keyword_synonyms);
				}
			}

			$synonyms = array_unique(explode(',', $_POST[$checkNewEdit . '_synonyms']));
			foreach ($synonyms as $synonym) {
				$synonym = trim($synonym);

				// Check if this synonym is already being used as keyword.
				if (!empty($synonym) && in_array($smcFunc['strtolower']($synonym), $unique_keywords))
					$synonym_as_keyword = empty($synonym_as_keyword) ? $synonym : $synonym_as_keyword . ', ' . $synonym;

				// Check if this synonym is already being used as a synonym for another keyword.
				if (!empty($synonym) && (($unique_synonyms && in_array($smcFunc['strtolower']($synonym), $unique_synonyms))))
					$synonym_in_use = empty($synonym_in_use) ? $synonym : $synonym_in_use . ', ' . $synonym;
			}

			// Synonyms being used as keywords.
			if (!empty($synonym_as_keyword))
				$synonymError .= '\\n' . $txt['glossary_submission_error_3'] . '\\n' . $synonym_as_keyword . '\\n';

			// Synonyms being used as synonyms for other keywords.
			if (!empty($synonym_in_use))
				$synonymError .= '\\n' . $txt['glossary_submission_error_4'] . '\\n' . $synonym_in_use . '\\n';

			// Add some extra information about these errors.
			if (!empty($synonym_as_keyword) || !empty($synonym_in_use))
				$add_errorNote = true;
		}

		return ($synonymError && $add_errorNote ? $synonymError . '\\n\\n' . str_replace(array('<strong>', '</strong>'), '', $txt['glossary_action_check_error_note']) . '\\n\\n' . $txt['glossary_action_check_error_note_sc'] : $synonymError);
	}

	public static function keywordCheck($keyword, $definition, $synonyms)
	{
		global $smcFunc;
		global $glossary_keyword, $glossary_definition, $glossary_synonyms;

		// Remove all the stuff we don't want from the keyword, definition and synonyms (and make sure the synonyms are unique).
		$glossary_keyword = trim(un_htmlspecialchars(self::fixSynonym($keyword, false)));
		$glossary_definition = trim(preg_replace('~https?://~i', '', $definition));
		if ($synonyms)
			$glossary_synonyms = implode(',', array_unique(array_map('trim', explode(',', un_htmlspecialchars(self::fixSynonym($synonyms))))));

		// For the keyword definition.
		// Convert the bold, italic, underline and strikethrough BBCode tags to HTML tags.
		$glossary_definition = preg_replace('~\[b\](.*?)\[\/b\]~i', '<strong>$1</strong>', $glossary_definition);
		$glossary_definition = preg_replace('~\[i\](.*?)\[\/i\]~i', '<em>$1</em>', $glossary_definition);
		$glossary_definition = preg_replace('~\[u\](.*?)\[\/u\]~i', '<ins>$1</ins>', $glossary_definition);
		$glossary_definition = preg_replace('~\[s\](.*?)\[\/s\]~i', '<del>$1</del>', $glossary_definition);

		// Remove all remaining BBCode tags.
		$glossary_definition = preg_replace('~\[/?[^/\]]+/?\]~', '', $glossary_definition);

		// Convert the allowed HTML tags back to BBCode tags.
		$glossary_definition = preg_replace('~\<strong\>(.*?)\<\/strong\>~', '[b]$1[/b]', $glossary_definition);
		$glossary_definition = preg_replace('~\<em\>(.*?)\<\/em\>~', '[i]$1[/i]', $glossary_definition);
		$glossary_definition = preg_replace('~\<ins\>(.*?)\<\/ins\>~', '[u]$1[/u]', $glossary_definition);
		$glossary_definition = preg_replace('~\<del\>(.*?)\<\/del\>~', '[s]$1[/s]', $glossary_definition);

		// For the keyword, definition and synonym convert special characters to HTML entities.
		$glossary_keyword = $smcFunc['htmlspecialchars']($glossary_keyword, ENT_NOQUOTES);
		$glossary_definition = addslashes($smcFunc['htmlspecialchars']($glossary_definition, ENT_QUOTES));
		$glossary_synonyms = $glossary_synonyms ? $smcFunc['htmlspecialchars']($glossary_synonyms, ENT_NOQUOTES) : '';
	}

	public static function fixSynonym($keywordSynonym, $synonymList = true)
	{
		$keywordSynonym = preg_replace(array('~https?://~i', '~&#0?39;~', '~\s+~u'), array('', '\'', ' '), ltrim(str_replace(array('"', '<', '&lt;', '>', '&gt;', '=', '/', '\\'), '', trim($keywordSynonym)), '\''));

		// Replace single and remove double 'smart' quotes.
		$characterMap = array(
			'\xC2\x91' => '\'', // U+0091⇒U+2018 left single quotation mark
			'\xC2\x92' => '\'', // U+0092⇒U+2019 right single quotation mark
			'\xC2\x93' => '', // U+0093⇒U+201C left double quotation mark
			'\xC2\x94' => '', // U+0094⇒U+201D right double quotation mark
			'\xE2\x80\x98' => '\'', // U+2018 left single quotation mark
			'\xE2\x80\x99' => '\'', // U+2019 right single quotation mark
			'\xE2\x80\x9C' => '', // U+201C left double quotation mark
			'\xE2\x80\x9D' => '', // U+201D right double quotation mark
			'&ldquo;' => '', // HTML left double quote
			'&rdquo;' => '', // HTML right double quote
			'&lsquo;' => '\'', // HTML left sinqle quote
			'&rsquo;' => '\'', // HTML right single quote
		);

		$char_value = array_keys($characterMap);
		$char_replace = array_values($characterMap);
		$keywordSynonym = trim(str_replace($char_value, $char_replace, htmlentities($keywordSynonym, ENT_NOQUOTES)));

		// Trim leading apostrophes from synonyms.
		if ($synonymList) {
			$synonyms = explode(',', $keywordSynonym);
			$keywordSynonym = '';
			foreach ($synonyms as $synonym)
				$keywordSynonym .= ltrim(trim($synonym), '\'') . ',';
		}

		// No trailing comma (could be a list of synonyms).
		return rtrim($keywordSynonym, ',');
	}

	public static function keywordList($res, $words_list)
	{
		global $context, $modSettings, $scripturl, $settings, $smcFunc, $txt;

		$author_showAdmin = $modSettings['glossary_show_author_admin'];
		$author_showAll = $modSettings['glossary_show_author_all'];
		$author_exclude = $modSettings['glossary_author_exclude'];
		$keywordWidth = empty($modSettings['glossary_word_width']) ? 150 : $modSettings['glossary_word_width'];
		$categoryWidth = empty($modSettings['glossary_category_width']) ? 100 : $modSettings['glossary_category_width'];
		$groupsEnabled = isset($_GET['sa']) && $_GET['sa'] == 'alphabetic' && $modSettings['glossary_enable_groups'] ? 'ok' : '';

		// Construct keyword and definition list.
		$columnStyle = 'width: ' . (allowedTo('glossary_admin') ? '150px;' : ($context['user']['id'] && ((allowedTo('glossary_suggest') || allowedTo('glossary_view')) && $author_showAll) ? '75px;' : (allowedTo('glossary_suggest') ? '60px;' : '0; padding-right: 0;')));
		$words_list .= '
			<tr style="border-bottom: 1px solid black;">
				<td class="glossaryaction" style="' . $columnStyle . '">';

		// If admin glossary then add specific 'delete' and 'edit' icons
		if (allowedTo('glossary_admin')) {
			$words_list .= '
					<input type="checkbox" id="glossary_cb_' . $res['id'] . '">
					<a href="javascript:EditWord(\'' . $res['id'] . '\',\'' . $groupsEnabled . '\',\'true\')" data-title="' . $txt['glossary_tip_edit'] . '"><img src="' . $settings['default_theme_url'] . '/images/glossary/glossary_edit.png"></a>
					<a style="cursor: pointer;" href="javascript:DeleteWord(\'' . $res['id'] . '\')" data-title="' . $txt['glossary_tip_delete'] . '"><img src="' . $settings['default_theme_url'] . '/images/glossary/glossary_delete.png"></a>';

			// Identify UNAPPROVED keywords.
			if ($res['valid'])
				$words_list .= '
					<a href="javascript:UnApproveWord(\'' . $res['id'] . '\')" data-title="' . $txt['glossary_tip_unapprove'] . '"><img src="' . $settings['default_theme_url'] . '/images/glossary/glossary_approved.png"></a>';
			else
				$words_list .= '
					<a href="javascript:ApproveWord(\'' . $res['id'] . '\')" data-title="' . $txt['glossary_tip_approve'] . '"><img src="' . $settings['default_theme_url'] . '/images/glossary/glossary_unapproved.png"></a>';

			// Identify VISIBLE keywords.
			if ($res['show_in_message'])
				$words_list .= '
					<a href="javascript:DisableTooltipWord(\'' . $res['id'] . '\')" data-title="' . $txt['glossary_tip_tooltip_off'] . '"><img src="' . $settings['default_theme_url'] . '/images/glossary/glossary_tooltip_on.png"></a>';
			else
				$words_list .= '
					<a href="javascript:EnableTooltipWord(\'' . $res['id'] . '\')" data-title="' . $txt['glossary_tip_tooltip_on'] . '"><img src="' . $settings['default_theme_url'] . '/images/glossary/glossary_tooltip_off.png"></a>';

			// Identify 'CASE SENSITIVE' keywords.
			if ($res['case_sensitive'])
				$words_list .= '
					<a href="javascript:DisableCaseSensitive(\'' . $res['id'] . '\')" data-title="' . $txt['glossary_tip_case_sensitive_off'] . '"><img src="' . $settings['default_theme_url'] . '/images/glossary/glossary_case_sensitive_on.png"></a>';
			else
				$words_list .= '
					<a href="javascript:EnableCaseSensitive(\'' . $res['id'] . '\')" data-title="' . $txt['glossary_tip_case_sensitive_on'] . '"><img src="' . $settings['default_theme_url'] . '/images/glossary/glossary_case_sensitive_off.png"></a>';

			$addHR = '<hr class="glossary_hr">';
		} // Add button for editing if member is keyword author and it is not approved.
		elseif (allowedTo('glossary_suggest') && $context['user']['id'] == $res['member_id'] && empty($res['valid'])) {
			$words_list .= '
					<a href="javascript:EditWord(\'' . $res['id'] . '\',\'' . $groupsEnabled . '\',\'false\')" data-title="' . $txt['glossary_tip_edit'] . '"><img src="' . $settings['default_theme_url'] . '/images/glossary/glossary_edit.png"></a>
					<img src="' . $settings['default_theme_url'] . '/images/glossary/glossary_unapproved.png" data-title="' . $txt['glossary_not_approved'] . '">';

			$addHR = '<hr class="glossary_hr">';
		} else
			$addHR = '';

		// Show the name of the keyword author (and a link to their profile if they are a current member).
		if ($context['user']['id'] && ((allowedTo('glossary_admin') && $author_showAdmin) || ((allowedTo('glossary_suggest') || allowedTo('glossary_view')) && $author_showAll))) {
			$query_member = 'SELECT id_member, real_name FROM {db_prefix}members WHERE id_member=' . $res['member_id'];
			$data_member = $smcFunc['db_query']('', $query_member, array());
			$res_member = $smcFunc['db_fetch_row']($data_member);
			// If the real name is longer than 15 characters shorten it and add an ellipsis to the end.
			if (!empty($res_member[1]) && $smcFunc['strlen']($res_member[1]) > 15)
				$res_member[1] = trim($smcFunc['substr']($res_member[1], 0, 11)) . ' ...';

			if ($author_exclude && ($res_member[0] == $context['user']['id'] || $context['user']['is_guest']))
				$author = '';
			else
				$author = $addHR . '<p style="text-align: center;"><i class="glossary_heading"> ' . $txt['glossary_keyword_author'] . '<br></i><span class="glossary_item">' . (!empty($res_member[1]) ? (allowedTo('profile_view') ? '<a href="' . $scripturl . '?action=profile;u=' . $res_member[0] . '" data-title="' . $txt['glossary_tip_view_profile'] . '"><u>' : '') . $res_member[1] . (allowedTo('profile_view') ? '</u></a>' : '') : $txt['guest']) . ' </span></p>';

			$words_list .= $author;
		}

		// Get the synonyms for this keyword.
		$synonymsList = '';
		if (!empty($res['synonyms'])) {
			$res['synonyms'] = implode(',', array_unique(array_map('trim', explode(',', $res['synonyms']))));
			$synonymsList = '</b><br><hr class="glossary_hr"><i class="glossary_heading">' . $txt['glossary_synonyms'] . '</i><br>' . str_replace(',', '<br>', $res['synonyms']);
		}
		$words_list .= '
				</td>
				<td class="glossary_keyword_def" style="width: ' . $keywordWidth . 'px; padding-right: 15px;"><b><div id="word_' . $res['id'] . '">' . $res['word'] . '</div>' . $synonymsList . '</b></td>
				<td class="glossary_keyword_def"><div id="definition_' . $res['id'] . '">';

		// If the keyword is not approved.
		$pending = '';
		if (empty($res['valid'])) {
			// If keyword is UNAPPROVED get the name of the member who added it.
			$query_member = 'SELECT id_member, real_name FROM {db_prefix}members WHERE id_member=' . $res['member_id'];
			$data_member = $smcFunc['db_query']('', $query_member, array());
			$res_member = $smcFunc['db_fetch_row']($data_member);

			// If this is the member who made the suggestion.
			if ($context['user']['id'] == $res['member_id'])
				$pending = '<br><br>[<i> ' . $txt['glossary_suggestion_you_made'] . ' </i>]';
			else
				$pending = '<br><br>[<i> ' . $txt['glossary_suggestion_by'] . ' ' . (!empty($res_member[1]) ? (allowedTo('profile_view') ? '<a href="' . $scripturl . '?action=profile;u=' . $res_member[0] . '"><u>' : '') . $res_member[1] . (allowedTo('profile_view') ? '</u></a>' : '') : $txt['guest']) . ' </i>]';
		}
		$res['definition'] = trim(preg_replace(array('~https?://~i', '~&#0?39;~'), array('', '\''), $res['definition']));
		$words_list .= '<span style="white-space: pre-line;">' . ($context['glossary_tooltip_bbc'] ? parse_bbc($res['definition']) : $res['definition']) . $pending . '</span>';

		if ($groupsEnabled)
			$words_list .= '
				</div>&nbsp;<div style="display: inline;"></div>
				</td><td style="width: 15px;"></td>
				<td style="width: ' . $categoryWidth . 'px; vertical-align: top; text-align: center;">
				<i class="glossary_heading">' . $txt['glossary_group'] . '</i><br><span class="glossary_item">' . (!empty($context['glossary_groups'][$res['group_id']]) ? $context['glossary_groups'][$res['group_id']] : $txt['glossary_group_none']) . '</span>';

		$words_list .= '
					<input type="hidden" id="definition_text_' . $res['id'] . '" value="' . $res['definition'] . '">
					<input type="hidden" id="valid_' . $res['id'] . '" value="' . $res['valid'] . '">
					<input type="hidden" id="synonyms_' . $res['id'] . '" value="' . $res['synonyms'] . '">
					<input type="hidden" id="show_in_message_' . $res['id'] . '" value="' . $res['show_in_message'] . '">
					<input type="hidden" id="group_id_' . $res['id'] . '" value="' . $res['group_id'] . '">
					<input type="hidden" id="case_sensitive' . $res['id'] . '" value="' . $res['case_sensitive'] . '">
				</td>
			</tr>';

		return $words_list;
	}
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
			'glossary' => array('Glossary.php', 'Glossary::Main'),
		),
		$actionArray
	);
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

// ./Sources/Load.php
// call_integration_hook('integrate_load_theme');

function glossary_loadTheme()
{
	global $context, $modSettings, $settings;

	if (empty($modSettings['glossary_mod_enable']) && !allowedTo('glossary_admin'))
		return;

	// Load the main CSS file.
	loadCSSFile('glossary/glossary.css', array('default_theme' => true, 'minimize' => true));

	// Only load the tooltip JavaScript/CSS for messages, PMs and/or News items
	$tooltips_on = $modSettings['glossary_enable_tooltips'] && allowedTo('glossary_tooltip');
	$tooltip_message = $tooltips_on && (!empty($_GET['topic']) || (isset($_GET['action']) && $_GET['action'] == 'post2'));
	$tooltip_pm = $tooltips_on && $modSettings['glossary_tooltip_pm'] && (isset($_GET['action']) && $_GET['action'] == 'pm');
	$tooltip_news = $tooltips_on && $modSettings['glossary_tooltip_news'] && !empty($modSettings['news']) && (!empty($settings['enable_news']) || !empty($settings['show_newsfader']));
	if ($tooltip_message || $tooltip_pm || $tooltip_news) {
		loadJavaScriptFile('glossary/glossary.ui.tooltip.js', array('default_theme' => true, 'minimize' => true));
		loadCSSFile('glossary/glossary.ui.tooltip.css', array('default_theme' => true, 'minimize' => true));
		addInlineJavaScript('$(function(){ $(".glossary, #postbuttons img").glossTooltip(); });');
	}

	// JavaScript/CSS for adding/editing Glossary list items.
	if (isset($_GET['action']) && $_GET['action'] == 'glossary' && (allowedTo('glossary_admin') || allowedTo('glossary_suggest')))
		loadJavaScriptFile('glossary/glossary.simplemodal.js', array('default_theme' => true, 'minimize' => true));
}

//=============================================================================================================

// ./Sources/ManageBoards.php
// call_integration_hook('integrate_boards_main');
function glossary_boardsMain()
{
	global $context, $modSettings;

	// Nothing to do if the mod, the option to exclude boards or the option to show board IDs is disabled.
	if (empty($modSettings['glossary_mod_enable']) || empty($modSettings['glossary_enable_boards_to_exclude']) || empty($modSettings['glossary_show_board_ids']))
		return;

	// Show the board ID before the board name in the 'Modify Boards' list.
	foreach ($context['categories'] as $category)
		foreach ($category['boards'] as $board)
			$board['name'] = '(ID: ' . $board['id'] . ')&nbsp;&nbsp;' . $board['name'];
}

//=============================================================================================================

// ./Sources/ManagePermissions.php
// call_integration_hook('integrate_load_permissions', array(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions));

function glossary_loadPerms(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
	global $modSettings;

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
	global $context, $modSettings, $scripturl, $smcFunc, $txt;

	$config_vars = array(
		array('check', 'glossary_mod_enable'),
		'',
		array('check', 'glossary_enable_tooltips'),
		array('check', 'glossary_tooltip_signature'),
		array('check', 'glossary_tooltip_pm'),
		array('check', 'glossary_tooltip_news'),
		'',
		array('check', 'glossary_bbcode_only_mode'),
		'',
		// Version 1.2: Added boards_to_exclude.
		array('check', 'glossary_enable_boards_to_exclude'),
		array('check', 'glossary_show_board_ids'),
		array('text', 'glossary_boards_to_exclude'),
		'',
		array('check', 'glossary_tooltip_bbc'),
		array('check', 'glossary_tooltip_once'),
		array('check', 'glossary_case_sensitive'),
		array('text', 'glossary_separator', 1),
		'',
		array('check', 'glossary_enable_numeric'),
		array('check', 'glossary_enable_synonyms'),
		array('check', 'glossary_show_synonyms'),
		array('check', 'glossary_enable_groups'),
		'',
		array('check', 'glossary_show_used_chars'),
		array('check', 'glossary_show_author_admin'),
		array('check', 'glossary_show_author_all'),
		array('check', 'glossary_author_exclude'),
		array('check', 'glossary_show_tooltips_default'),
		array('check', 'glossary_approve_keyword_default'),
		array('check', 'glossary_admin_context_menu'),
		array('int', 'glossary_word_width', 4),
		array('int', 'glossary_category_width', 4),
	);

	// Saving?
	if (isset($_GET['save'])) {
		// If tooltips are not enabled for posts disable them for signatures, PMs and News items.
		if (empty($_POST['glossary_enable_tooltips']))
			$_POST['glossary_tooltip_signature'] = $_POST['glossary_tooltip_pm'] = $_POST['glossary_tooltip_news'] = 0;

		// If synonyms are not enabled disable the option to show them below keyword definitions.
		if (empty($_POST['glossary_enable_synonyms']))
			$_POST['glossary_show_synonyms'] = 0;

		// Make sure the glossary_separator is no more than two characters long.
		$_POST['glossary_separator'] = $smcFunc['substr']($_POST['glossary_separator'], 0, 2);

		// Version 1.2: If some boards should be excluded from Glossary use
		// make sure all the specified board IDs is valid.
		if (!empty($_POST['glossary_enable_boards_to_exclude'])) {
			// Disable the option to exclude boards from Glossary use if no board IDs have been specified.
			if (empty($_POST['glossary_boards_to_exclude']))
				$_POST['glossary_enable_boards_to_exclude'] = 0;

			// Make sure all the specified board IDs are valid.
			elseif (!empty($_POST['glossary_boards_to_exclude'])) {
				// Sanitise the input to ensure only unique numbers separated by a single comma.
				$post_ExcludedBoards = explode(',', preg_replace(array('/[^\d,]/', '/(?<=,),+/', '/^,+/', '/,+$/'), '', $_POST['glossary_boards_to_exclude']));

				// Get a list of current board IDs in ascending order.
				$request = $smcFunc['db_query']('', '
					SELECT id_board
					FROM {db_prefix}boards
					ORDER BY id_board',
					array()
				);
				while ($row = $smcFunc['db_fetch_assoc']($request))
					$allBoards[] = (int)$row['id_board'];
				$smcFunc['db_free_result']($request);

				// Work out which of the specified board IDs are valid and update
				// 'glossary_boards_to_exclude' with a comma separated string.
				$save_excludedBoards = array_intersect($allBoards, $post_ExcludedBoards);
				$_POST['glossary_boards_to_exclude'] = implode(',', $save_excludedBoards);
			}
		} else
			// Otherwise disable the option to show board IDs in the
			// 'Manage Boards and Categories > Modify Boards' list.
			$_POST['glossary_show_board_ids'] = 0;

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
		'validate' => function (&$tag, &$data, $disabled) {
			global $context, $modSettings, $smcFunc;

			if (!empty($modSettings['glossary_enable_tooltips'])) {
				static $once;
				if ($once != true) {
					$once = true;
					// Find keywords that are not shown in tooltips.
					$data_glossary = $smcFunc['db_query']('', '
						SELECT definition, word, synonyms, case_sensitive
						FROM {db_prefix}glossary
						WHERE valid = 1 AND show_in_message = 0');
					$results = $smcFunc['db_fetch_all']($data_glossary);
					$context['glossary_list_bbc'] = GlossaryParser::parseKeywords($results, false);
					$smcFunc['db_free_result']($data_glossary);
					$context['glossary_list_bbc_map'] = array();
					foreach ($results as $res) {
						if ($res['case_sensitive'] == 0)
							$context['glossary_list_bbc_map'][strtolower($res['word'])] = $res['word'];
					}
				}
				$value = $smcFunc['htmltrim']($data);
				$value = isset($context['glossary_list_bbc_map'][$value])
					? $context['glossary_list_bbc'][$context['glossary_list_bbc_map'][$value]]
					: $context['glossary_list_bbc'][$value] ?? '';
				if ($value !== '')
					$data = '<span class="glossary highlight" title="' . $value . '">' . $data . '</span>';
			}
		},
	);
}

//=============================================================================================================

// ./Sources/Subs.php
// call_integration_hook('integrate_menu_buttons', array(&$buttons));

function glossary_menuButtons(&$buttons)
{
	global $modSettings, $scripturl, $txt;

	// If the mod is disabled only show the Glossary list to Glossary admins.
	if (empty($modSettings['glossary_mod_enable']) && !allowedTo('glossary_admin'))
		return;

	$counter = 0;
	foreach ($buttons as $area => $dummy)
		if (++$counter && $area == 'mlist')
			break;

	// Add the Glossary button to the main menu bar.
	$buttons = array_merge(
		array_slice($buttons, 0, $counter),
		array(
			'glossary' => array(
				'title' => $txt['glossary'],
				'href' => $scripturl . '?action=glossary',
				'show' => allowedTo('glossary_admin') || allowedTo('glossary_view'),
				'icon' => 'glossary/glossary.png',
				'sub_buttons' => array(),
			),
		),
		array_slice($buttons, $counter)
	);
}

//=============================================================================================================

// ./Sources/Subs.php
// call_integration_hook('integrate_pre_parsebbc', array(&$message, &$smileys, &$cache_id, &$parse_tags))

function glossary_preParseBBC(&$message, &$smileys, &$cache_id)
{
	add_integration_function('integrate_post_parsebbc', 'glossary_postParseBBC', false);
	return;
	global $context, $memberContext, $modSettings, $smcFunc, $settings, $txt;

	// Definitions in the Glossary list don't get tooltips.
	if (isset($_GET['action']) && $_GET['action'] == 'glossary')
		return;

	// Return if this is a News item and News items don't get tooltips.
	$tt_news = $modSettings['glossary_tooltip_news'] && !empty($modSettings['news']) && (!empty($settings['enable_news']) || !empty($settings['show_newsfader'])) && $smcFunc['substr']($cache_id, 0, 4) == 'news';
	if (!$tt_news && in_array($message, array_filter(explode("\n", str_replace("\r", '', trim(addslashes($modSettings['news']))))), true)) {
		$tt_news = false;
		return;
	} // Return if this is a signature and signatures don't get tooltips.
	elseif (empty($modSettings['glossary_tooltip_signature']) && substr($cache_id, 0, 3) == 'sig') {
		$tt_sig = false;
		return;
	} // Return if this is a PM and PMs don't get tooltips.
	elseif (empty($modSettings['glossary_tooltip_pm']) && substr($cache_id, 0, 2) == 'pm') {
		$tt_pm = false;
		return;
	} // Return if none of the above and this is not an existing post or a post that is being previewed.
	elseif ((empty($tt_news) && empty($tt_sig) && empty($tt_pm)) && (empty($_GET['topic']) || ((isset($_GET['action']) && $_GET['action'] != 'post2'))))
		return;

	// Version 1.2: Return if the message is in a board that is excluded from Glossary use.
	$excludeGlossaryBoards = $modSettings['glossary_enable_boards_to_exclude'] && !empty($modSettings['glossary_enable_boards_to_exclude']);
	if (is_numeric($cache_id) && !empty($_GET['board']) && $excludeGlossaryBoards && in_array($_GET['board'], explode(',', $modSettings['glossary_boards_to_exclude'])))
		return;

}

function glossary_postParseBBC(&$message, &$smileys, &$cache_id)
{
	global $context, $modSettings, $smcFunc, $txt;

	// Return if the mod is disabled, Glossary tooltips are not enabled for messages, or the user is not allowed to view tooltips.
	if (empty($modSettings['glossary_mod_enable']) || empty($modSettings['glossary_enable_tooltips']) || !allowedTo('glossary_tooltip'))
		return;

	// If only keywords inside a Glossary BBCode tag are allowed return.
	if ($modSettings['glossary_bbcode_only_mode'])
		return;

	// Definitions in the Glossary list don't get tooltips.
	if (isset($_GET['action']) && $_GET['action'] == 'glossary')
		return;

	// Return if this is a News item and News items don't get tooltips.
	$tt_news = $modSettings['glossary_tooltip_news'] && !empty($modSettings['news']) && (!empty($settings['enable_news']) || !empty($settings['show_newsfader'])) && $smcFunc['substr']($cache_id, 0, 4) == 'news';
	if (!$tt_news && in_array($message, array_filter(explode("\n", str_replace("\r", '', trim(addslashes($modSettings['news']))))), true)) {
		$tt_news = false;
		return;
	} // Return if this is a signature and signatures don't get tooltips.
	elseif (empty($modSettings['glossary_tooltip_signature']) && substr($cache_id, 0, 3) == 'sig') {
		$tt_sig = false;
		return;
	} // Return if this is a PM and PMs don't get tooltips.
	elseif (empty($modSettings['glossary_tooltip_pm']) && substr($cache_id, 0, 2) == 'pm') {
		$tt_pm = false;
		return;
	} // Return if none of the above and this is not an existing post or a post that is being previewed.
	elseif ((empty($tt_news) && empty($tt_sig) && empty($tt_pm)) && (empty($_GET['topic']) || ((isset($_GET['action']) && $_GET['action'] != 'post2'))))
		return;

	// Version 1.2: Return if the message is in a board that is excluded from Glossary use.
	$excludeGlossaryBoards = $modSettings['glossary_enable_boards_to_exclude'] && !empty($modSettings['glossary_enable_boards_to_exclude']);
	if (is_numeric($cache_id) && !empty($_GET['board']) && $excludeGlossaryBoards && in_array($_GET['board'], explode(',', $modSettings['glossary_boards_to_exclude'])))
		return;

	if (empty(GlossaryParser::$glossary_list)) {
		GlossaryParser::build();
	}

	// No words in the Glossary yet?
	if (empty(GlossaryParser::$glossary_list)) {
		return;
	}

	// Don't mess with the content of HTML tags.
	$parts = preg_split('/(<[^>]+>)/', $message, -1, PREG_SPLIT_DELIM_CAPTURE);
	$message = GlossaryParser::parseMessage($message);
}

class GlossaryParser
{
	public static $glossary_list = array();
	public static $glossary_map = array();
	public static $regex_words = '';

	public static function build()
	{
		global $context, $smcFunc;

		$glossary_list = array();
		self::$regex_words = '';
		self::$glossary_map = array();
		self::$glossary_list = array();
		$data_glossary = $smcFunc['db_query']('', '
			SELECT definition, word, synonyms, case_sensitive
			FROM {db_prefix}glossary
			WHERE valid = 1 AND show_in_message = 1');
		$results = $smcFunc['db_fetch_all']($data_glossary);
		self::$glossary_list = self::parseKeywords($results, true);
		$smcFunc['db_free_result']($data_glossary);

		foreach ($results as $res) {
			if ($res['case_sensitive'] == 0)
				self::$glossary_map[un_htmlspecialchars(mb_strtolower($res['word']))] = un_htmlspecialchars($res['word']);
			else
				$glossary_list[] = un_htmlspecialchars($res['word']);
		}

		if ($glossary_list !== array()) {
			self::$regex_words = build_regex($glossary_list, '/');
		}

		if (self::$glossary_map !== array()) {
			if (self::$regex_words !== '')
				self::$regex_words = '(?:' . self::$regex_words . '|(?i:' . build_regex(array_keys(self::$glossary_map), '/') . '))';
			else
				self::$regex_words = '(?i:' . build_regex(array_keys(self::$glossary_map), '/') . ')';
		}
	}

	public static function parseMessage(string $message): string
	{
		global $modSettings;

		// Don't mess with the content of HTML tags.
		$parts = preg_split('/(<[^>]+>)/', $message, -1, PREG_SPLIT_DELIM_CAPTURE);
		$message = '';

		for ($i = 0, $n = count($parts); $i < $n; $i++) {
			if ($i % 2 === 0) {
				$message .= preg_replace_callback(
					'/\b(' . self::$regex_words . ')(\s|$|[^\w])/' . (!empty($modSettings['glossary_case_sensitive']) ? 'i' : ''),
					function ($matches) {
						$lower_match = mb_strtolower($matches[1]);
						$value = isset(self::$glossary_map[$lower_match])
							? self::$glossary_list[self::$glossary_map[$lower_match]]
							: self::$glossary_list[$matches[1]] ?? null;

						return $value === null ? $matches[0] : '<span class="glossary highlight" title="' . $value . '">' . $matches[1] . '</span>' . $matches[2];
					},
					un_htmlspecialchars($parts[$i]),
					!empty($modSettings['glossary_tooltip_once']) ? 1 : -1
				);
			} else {
				$message .= $parts[$i];
			}
		}

		return $message;
	}

	public static function parseKeywords(array $result_list, bool $parse_synonyms): array
	{
		global $modSettings, $txt;

		$glossary_list = array();
		foreach ($result_list as $res) {
			$keyword = un_htmlspecialchars($res['word']);

			// If BBC in tooltips is enabled.
			$definition = trim(preg_replace(array('~https?://~i', '~&#0?39;~', '/"/'), array('', '\'', '&quot;'), trim($res['definition'])));
			if ($modSettings['glossary_tooltip_bbc']) {
				// Convert the bold, italic, underline and strikethrough BBCode tags to HTML tag.
				$definition = preg_replace('~\[b\](.*?)\[\/b\]~i', '<strong>$1</strong>', $definition);
				$definition = preg_replace('~\[i\](.*?)\[\/i\]~i', '<em>$1</em>', $definition);
				$definition = preg_replace('~\[u\](.*?)\[\/u\]~i', '<ins>$1</ins>', $definition);
				$definition = preg_replace('~\[s\](.*?)\[\/s\]~i', '<del>$1</del>', $definition);
			}

			// Remove all remaining BBCode tags from the keyword definition.
			$definition = preg_replace('~\[/?[^/\]]+/?\]~', '', $definition);

			// Replace the separator character with a line break.
			if ($modSettings['glossary_separator'])
				$definition = str_replace($modSettings['glossary_separator'], '<br>', $definition);

			// If synonyms are enabled and this keyword has some add them to the list after adding the keyword.
			if ($modSettings['glossary_enable_synonyms'] && $res['synonyms']) {
				// Remove all the stuff we don't want to match from the synonyms.
				$synonyms = array_unique(array_map('trim', explode(',', un_htmlspecialchars($res['synonyms']))));

				// If the keyword and synonyms should be shown below the defintion in the tooltip.
				if ($modSettings['glossary_show_synonyms']) {
					$definition .= '<br><br><hr><span class=glossary_heading>' . $txt['glossary_definition_keyword'] . '</span>' . $keyword . '<br><span class=glossary_heading>' . $txt['glossary_definition_synonyms'] . '</span>' . implode(', ', $synonyms);
				}

				if ($parse_synonyms) {
					foreach ($synonyms as $synonym) {
						$glossary_list[$synonym] = $definition;
					}
				}
			}

			$glossary_list[$keyword] = $definition;
		}

		return $glossary_list;
	}
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
	foreach ($context['bbc_tags'] as $row_num => $row) {
		$glossary_bbc = array();
		foreach ($row as $tag) {
			$glossary_bbc[] = $tag;
			if (isset($tag['code']) && $tag['code'] === 'quote') {
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