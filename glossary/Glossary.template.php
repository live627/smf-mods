<?php

/*************************************************************************************************************
* Glossary.template.php - Template file for Glossary for SMF 2.1 mod (v1.4)
**************************************************************************************************************
* This mod is licensed under the 2-Clause BSD License, which can be found here:
*	https://opensource.org/licenses/BSD-2-Clause
**************************************************************************************************************
* Copyright (c) 2008-2009 Slinouille, 2024 Kathy Leslie, 2024 John Rayes
* Redistribution and use in source and binary forms, with or without modification, are permitted provided
* that the following conditions are met:
*	1.	Redistributions of source code must retain the above copyright notice, this list of conditions
*		and the following disclaimer.
*	2.	Redistributions in binary form must reproduce the above copyright notice, this list of conditions and
*		the following disclaimer in the documentation and/or other materials provided with the distribution.
* This software is provided by the copyright holders and contributors "as is" and any express or implied
* warranties, including, but not limited to, the implied warranties of merchantability and fitness for a
* particular purpose are disclaimed. In no event shall the copyright holder or contributors be liable for
* any direct, indirect, incidental, special, exemplary, or consequential damages (including, but not
* limited to, procurement of substitute goods or services; loss of use, data, or profits; or business
* interruption) however caused and on any theory of liability, whether in contract, strict liability, or
* tort (including negligence or otherwise) arising in any way out of the use of this software, even if
* advised of the possibility of such damage.
*************************************************************************************************************/

//===========================================================================================================
// Original Glossary mod by Slinouille
// https://www.simplemachines.org/community/index.php?action=profile;u=68142
// https://custom.simplemachines.org/mods/index.php?mod=1525
//
// Updated and enhanced for SMF 2.1 by GL700Wing
// https://www.simplemachines.org/community/index.php?action=profile;u=112942
//
// Version 1.4: Amazing and incredibly fast parsing code in Glossary.php contributed by live627
// (https://www.simplemachines.org/community/index.php?action=profile;u=154736)
//===========================================================================================================

function template_main()
{
	global $context, $modSettings, $scripturl, $settings, $smcFunc, $txt;

	// Some variable defaults.
	$glossary_sa = isset($_GET['sa']) ? ';sa='.$_GET['sa'] : '';
	$themeURL = $settings['default_theme_url'];
	$imagesGlossary = $themeURL . '/images/glossary';
	$groupsEnabled = $modSettings['glossary_enable_groups'];

	// Version 1.4: Show total number of keywords and synonyms on Glossary list.
	// Keywords that are not approved, not shown in tooltips, or are case sensitive.
	$notApproved = $txt['glossary_list_notApproved'];
	$noTooltip = $txt['glossary_list_noTooltip'];
	$caseSensitive = $txt['glossary_list_caseSensitive'];
	$tagOnly = $txt['glossary_list_tagOnly'];
	$keyword_notApproved = $keyword_noTooltip = $keyword_caseSensitive = $keyword_tagOnly = '';

	// Total number of keywords.
	$query_word = $query_select_count = 'SELECT COUNT(word) FROM {db_prefix}glossary';
	$data_word = $smcFunc['db_query']('', $query_word, array());
	list($keywords) = $smcFunc['db_fetch_row']($data_word);
	$keyword_count = $txt['glossary_count_keyword'] . $keywords;

	// Number of keywords that are not approved.
	$keyword_other = '';
	$query_word = $query_select_count . ' WHERE valid = 0';
	$data_word = $smcFunc['db_query']('', $query_word, array());
	list($keywords) = $smcFunc['db_fetch_row']($data_word);
	$keyword_notApproved = $keyword_other = $keywords ? $notApproved . ': ' . $keywords : '';

	// Number of keywords that are not shown in tooltips.
	$query_word = $query_select_count . ' WHERE show_in_message = 0';
	$data_word = $smcFunc['db_query']('', $query_word, array());
	list($keywords) = $smcFunc['db_fetch_row']($data_word);
	$keyword_noTooltip = $keyword_other .= $keywords ? ($keyword_other ? ', ' : '') . $noTooltip . ': ' . $keywords : '';

	// Number of keywords that are case sensitive.
	$query_word = $query_select_count . ' WHERE case_sensitive = 1';
	$data_word = $smcFunc['db_query']('', $query_word, array());
	list($keywords) = $smcFunc['db_fetch_row']($data_word);
	$keyword_caseSensitive = $keyword_other .= $keywords ? ($keyword_other ? ', ' : '') . $caseSensitive . ': ' . $keywords : '';

	// Number of keywords that are BBCode tag only.
	$query_word = $query_select_count . ' WHERE tag_only = 1';
	$data_word = $smcFunc['db_query']('', $query_word, array());
	list($keywords) = $smcFunc['db_fetch_row']($data_word);
	$keyword_tagOnly = $keyword_other .= $keywords ? ($keyword_other ? ', ' : '') . $tagOnly . ': ' . $keywords : '';

	// Total number of synonyms.
	$synonyms[0] = '';
	$query_synonyms = $query_select_synonyms = 'SELECT synonyms FROM {db_prefix}glossary WHERE synonyms != ""';
	$data_synonyms = $smcFunc['db_query']('', $query_synonyms, array());
	while ($res = $smcFunc['db_fetch_row']($data_synonyms))
		$synonyms[0] .= $res[0] . ',';
	$synonym_count = $synonyms[0] ? $txt['glossary_count_synonym'] . substr_count($synonyms[0], ',') : '';

	// Find out how many corresponding synonyms are not approved, not shown in tooltips or are case sensitive.
	$synonym_other = '';
	if ($keyword_other)
	{
		if ($keyword_notApproved)
		{
			// Number of synonyms that are not approved.
			$synonyms[0] = '';
			$query_synonyms = $query_select_synonyms . ' AND valid = 0';
			$data_synonyms = $smcFunc['db_query']('', $query_synonyms, array());
			while ($res = $smcFunc['db_fetch_row']($data_synonyms))
				$synonyms[0] .= $res[0] . ',';
			$synonym_other = $synonyms[0] ? $notApproved . ': ' . substr_count($synonyms[0], ',') : '';
		}

		if ($keyword_noTooltip)
		{
			// Number of synonyms that are not shown in tooltips.
			$synonyms[0] = '';
			$query_synonyms = $query_select_synonyms . ' AND show_in_message = 0';
			$data_synonyms = $smcFunc['db_query']('', $query_synonyms, array());
			while ($res = $smcFunc['db_fetch_row']($data_synonyms))
				$synonyms[0] .= $res[0] . ',';
			$synonym_other .= $synonyms[0] ? ($synonym_other ? ', ' : '') . $noTooltip . ': ' . substr_count($synonyms[0], ',') : '';
		}

		if ($keyword_caseSensitive)
		{
			// Number of synonyms that are case sensitive.
			$synonyms[0] = '';
			$query_synonyms = $query_select_synonyms . ' AND case_sensitive = 1';
			$data_synonyms = $smcFunc['db_query']('', $query_synonyms, array());
			while ($res = $smcFunc['db_fetch_row']($data_synonyms))
				$synonyms[0] .= $res[0] . ',';
			$synonym_other .= $synonyms[0] ? ($synonym_other ? ', ' : '') . $caseSensitive . ': ' . substr_count($synonyms[0], ',') : '';
		}

		if ($keyword_tagOnly)
		{
			// Number of synonyms that are BBCode tag only.
			$synonyms[0] = '';
			$query_synonyms = $query_select_synonyms . ' AND tag_only = 1';
			$data_synonyms = $smcFunc['db_query']('', $query_synonyms, array());
			while ($res = $smcFunc['db_fetch_row']($data_synonyms))
				$synonyms[0] .= $res[0] . ',';
			$synonym_other .= $synonyms[0] ? ($synonym_other ? ', ' : '') . $tagOnly . ': ' . substr_count($synonyms[0], ',') : '';
		}
	}

	// Total number of keywords and synonyms to show on the Glossary list.
	if ($keyword_other)
	{
		$keyword_other = ' (' . $keyword_other . ')';
		$synonym_other = $synonym_other ? ' (' . $synonym_other . ')' : '';
	}
	$keyword_synonym_count = '<span class="smalltext">' . $keyword_count . $keyword_other . ($synonym_count ? '<br>' . $synonym_count . $synonym_other : '') . '</span>';

	// The Glossary keyword list ...
	echo '
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
		<td width="100%" valign="top" style="padding-bottom: 10px;">
			<div class="tborder" style="margin-top: 1ex;">
				<div class="glossarytitle">
					<table width="100%"><tr>
						<td align="left" style="line-height: 30px;">',
							$groupsEnabled
							? '<b>' . $txt['glossary_display_mode'] . ':</b>
							<div class="smalltext" style="display:inline;">
								<a href="'. $scripturl. '?action=glossary&sa=alphabetic">'. ($modSettings['glossary_enable_numeric'] ? $txt['glossary_by_alphanumeric'] : $txt['glossary_by_alphabetic']) . '</a>/<a href="'. $scripturl. '?action=glossary&sa=categories">'. $txt['glossary_by_groups']. '</a>
							</div>'
							: $txt['glossary'], '
						</td>
						<td align="right" class="smalltext">';

						// If keyword/synonym issues have been detected.
						if (allowedTo('glossary_admin') && !empty($context['synonymCheck']))
							echo '<button id="opener"><span class="glossary_alert" id="synonymCheck"><img src="'.$imagesGlossary.'/glossary_check.png"> '.$txt['glossary_action_check'].'</span></button><div id="dialog" title="'.$txt['glossary_action_check'].'">'.$context['synonymCheck'].'</div>';

						if (allowedTo('glossary_admin') || allowedTo('glossary_suggest'))
							echo '
						<a href="#" id="div_new_word-show"><img src="'. $imagesGlossary . '/glossary_add.png"> '. (allowedTo('glossary_admin') ? $txt['glossary_action_add'] : $txt['glossary_action_suggest']) . '</a>';

						// Version 1.2: Added "case_sensitive" option to only show keywords with exact case match.
						// Version 1.4: Added "tag_only" option to only process individual keywords in BBCode tags.
						if (allowedTo('glossary_admin'))
						{
							echo '
						<a href="#" id="glossary_admin_menu_show"><img src="'. $imagesGlossary . '/glossary_wand.png"> '.$txt['glossary_action_admin'].'</a>
						<div>
							<div id="glossary_admin_menu">
								<a href="#" id="div_approve_all-show">'. $txt['glossary_action_approve_all'] . '<img src="'. $imagesGlossary . '/glossary_approved.png"></a><br>
								<a href="#" id="div_unapprove_all-show"> '. $txt['glossary_action_unapprove_all'] . '<img src="'. $imagesGlossary . '/glossary_unapproved.png"></a><br>
								<a href="#" id="div_tooltip_on_all-show">'. $txt['glossary_action_tooltip_on_all'] . '<img src="'. $imagesGlossary . '/glossary_tooltip_on.png"></a><br>
								<a href="#" id="div_tooltip_off_all-show">'. $txt['glossary_action_tooltip_off_all'] . '<img src="'. $imagesGlossary . '/glossary_tooltip_off.png"></a><br>
								<a href="#" id="div_case_sensitive_on_all-show">'. $txt['glossary_action_case_sensitive_on_all'] . '<img src="'. $imagesGlossary . '/glossary_case_sensitive_on.png"></a><br>
								<a href="#" id="div_case_sensitive_off_all-show">'. $txt['glossary_action_case_sensitive_off_all'] . '<img src="'. $imagesGlossary . '/glossary_case_sensitive_off.png"></a><br>
								<a href="#" id="div_tag_only_on_all-show">'. $txt['glossary_action_tag_only_on_all'] . '<img src="'. $imagesGlossary . '/glossary_tag_only_on.png"></a><br>
								<a href="#" id="div_tag_only_off_all-show">'. $txt['glossary_action_tag_only_off_all'] . '<img src="'. $imagesGlossary . '/glossary_tag_only_off.png"></a><br>';


							if ($groupsEnabled)
							echo '
								<a href="#" id="div_change_group_all-show"> '. $txt['glossary_action_change_group_all'] . '<img src="'. $imagesGlossary . '/glossary_change_category.png"></a><br>
								<a href="#" id="div_manage_groups-show">'. $txt['glossary_action_add_category'] . '<img src="'. $imagesGlossary . '/glossary_manage_category.png"></a><br>';

							echo '
								<a href="#" onclick="CheckboxSelect(\'select\')"> '. $txt['glossary_action_select_all'] . '<img src="'. $imagesGlossary . '/glossary_selectall.png"></a><br>
								<a href="#" onclick="CheckboxSelect(\'unselect\')"> '. $txt['glossary_action_unselect_all'] . '<img src="'. $imagesGlossary . '/glossary_unselectall.png"></a>
								<hr style="margin: 0;">
								<a href="#quit">'. $txt['glossary_new_word_close'] . '</a>
							</div>
						</div>';
						}

	echo '
						</td>
					</tr></table>';

	// Version 1.4: Show the number of keywords and synonyms (including non-default options) on the Glossary keyword list.
	echo '
					<table style="width: 100%;"><tr><td>', $keyword_synonym_count, '</td></tr></table>';

	echo '
				</div>
				<div class="windowbg2">
					<table style="margin: 10px auto auto auto" width="95%">
						<tr>
							<td> '.$context['glossary_elements'].'</td>
						</tr>
					</table>
				</div>
			</div>
		</td></tr>
	</table>';

	// ADD a new word DIV
	echo '
		<div id="div_new_word" style="display:none;">
			<div class="header"><span>'.(allowedTo('glossary_admin') ? $txt['glossary_new_word_add'] : $txt['glossary_new_word_suggest']).'</span></div>
			<form method="post" name="new_word_form" style="padding: 5px;">
				<div class="ModalContent">
				<label>'.$txt['glossary_new_word'].'
				<input type="text" name="new_word" id="new_word" size="50" value=""></label><br>
				<label', $context['glossary_tooltip_bbc'] ? ' title="'.$txt['glossary_bbcodes_allowed'].'"' : '', '>'.$txt['glossary_new_definition'].' ', $context['glossary_tooltip_bbc'] ? $txt['glossary_bbcodes_parsed'] : '', '
				<textarea name="new_definition" id="new_definition" cols="55" rows="5"></textarea></label><br>';

				if ($groupsEnabled)
				{
					echo '
					<label>'.$txt['glossary_group'].'
					<select name="new_group" id="new_group" value="">
						<option value=""> --- '.$txt['glossary_group_none'].' --- </option>';

					foreach ($context['glossary_groups'] as $id=>$title)
					echo '
						<option value="'.$id.'">'.$title.'</option>';

					echo '</select></label><br>';
				}

	// Synonyms of this keyword
	// Version 1.2: Added "case_sensitive" option to only show keywords with exact case match.
	// Version 1.4: Added "tag_only" option to only process individual keywords in BBCode tags.
	echo '
				<label>'.$txt['glossary_synonyms_edit'].'
				<input type="text" name="new_synonyms" id="new_synonyms" size="50" value="" title="'.$txt['glossary_synonyms_tip'].'"></label><br>',
				(allowedTo('glossary_admin') ? '
				<label>'.$txt['glossary_approve_keyword'].'
				<input type="checkbox" name="new_valid" id="new_valid" '.($modSettings['glossary_approve_keyword_default'] ? ' checked' : '').'/></label><br>
				<label>'.$txt['glossary_show_tooltips'].'
				<input type="checkbox" name="new_show_in_message"'.($modSettings['glossary_show_tooltips_default'] ? ' checked' : '').'></label><br>' : ''), '
				<label>'.$txt['glossary_keyword_case_sensitive'].'
				<input type="checkbox" name="new_case_sensitive"></label><br>
				<label>'.$txt['glossary_keyword_tag_only'].'
				<input type="checkbox" name="new_tag_only"></label><br>
				<input type="hidden" name="sc" value="', $context['session_id'], '" />
				<input type="hidden" name="submit_new_word" id="submit_new_word" value="" />
				</div>
			</form>
			<div class="buttons">
				<div onclick="$.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				<div onclick="SubmitNewWord(\'', $groupsEnabled ? 'group_enabled' : '', '\')">'.$txt['glossary_new_word_button'].'</div>
			</div>
		</div>';

	// EDIT a word DIV
	echo '
		<div id="div_edit_word" style="display:none;">
			<div class="header"><span>'.$txt['glossary_tip_edit'].'</span></div>
			<form method="post" name="edit_word_form" style="padding: 5px;">
				<div class="ModalContent">
				<label>'.$txt['glossary_new_word'].'
				<input type="text" name="edit_word" id="edit_word" value="', $context['glossary_action_status'] == 'edit' ? (empty($_POST['edit_word']) ? '' : $_POST['edit_word']) : ($context['glossary_action_status'] == 'check_new' ? (empty($_POST['new_word']) ? '' : $_POST['new_word']) : (empty($_POST['edit_word']) ? '' : $_POST['edit_word'])), '"></label><br>
				<label"', $context['glossary_tooltip_bbc'] ? 'title="'.$txt['glossary_bbcodes_allowed'].'"' : '', '>'.$txt['glossary_new_definition'].' ', $context['glossary_tooltip_bbc'] ? $txt['glossary_bbcodes_parsed'] : '', '
				<textarea name="edit_definition" id="edit_definition" cols="55" rows="5">', $context['glossary_action_status'] == 'edit' ? (empty($_POST['edit_definition']) ? '' : $_POST['edit_definition']) : ($context['glossary_action_status'] == 'check_new' ? (empty($_POST['new_definition']) ? '' : $_POST['new_definition']) : (empty($_POST['edit_definition']) ? '' : $_POST['edit_definition'])), '</textarea></label><br>';

				if ($groupsEnabled)
				{
					echo '
					<label>'.$txt['glossary_group'].'
					<select name="edit_group" id="edit_group" value="">
						<option value="" selected="true" > --- '.$txt['glossary_group_none'].' --- </option>';

					foreach ($context['glossary_groups'] as $id=>$title)
						echo '
						<option value="'.$id.'"', $context['glossary_action_status'] == 'edit' ? (isset($_POST['edit_group']) && $_POST['edit_group'] == $id ? 'selected' : '') : ($context['glossary_action_status'] == 'check_new' ? ((isset($_POST['new_group']) && $_POST['new_group'] == $id) ? 'selected' : '') : ((isset($_POST['edit_group']) && $_POST['edit_group'] == $id) ? 'selected' : '')), '>'.$title.'</option>';

					echo '
					</select></label><br>';
				}

	// Synonyms of this keyword
	// Version 1.2: Added "case_sensitive" option to only show keywords with exact case match.
	// Version 1.4: Added "tag_only" option to only process individual keywords in BBCode tags.
	echo '
				<label for="edit_synonyms">'.$txt['glossary_synonyms_edit'].'
				<input type="text" name="edit_synonyms" id="edit_synonyms" size="50" value="', $context['glossary_action_status'] == 'edit' ? (empty($_POST['edit_synonyms']) ? '' : $_POST['edit_synonyms']) : ($context['glossary_action_status'] == 'check_new' ? (empty($_POST['new_synonyms']) ? '' : $_POST['new_synonyms']) : (empty($_POST['edit_synonyms']) ? '' : $_POST['edit_synonyms'])), '" title="'.$txt['glossary_synonyms_tip'].'"></label><br>',
				(allowedTo('glossary_admin') ? '
				<label for="edit_show_in_message">'.$txt['glossary_show_tooltips'].'
				<input type="checkbox" name="edit_show_in_message" id="edit_show_in_message" '. ($context['glossary_action_status'] == 'edit' && isset($_POST['edit_show_in_message']) ? 'checked' : ''). ' /></label><br>' : ''),
				(allowedTo('glossary_admin') && !empty($_POST['edit_valid']) ? ('
				<label for="edit_valid">'.$txt['glossary_approve_keyword'].'
				<input type="checkbox" name="edit_valid" id="edit_valid" checked /></label><br>') :
				(allowedTo('glossary_admin') ? ('
				<label for="edit_valid">'.$txt['glossary_approve_keyword'].'
				<input type="checkbox" name="edit_valid" id="edit_valid" /></label><br>') : '')), '
				<label for="edit_case_sensitive">'.$txt['glossary_keyword_case_sensitive'] . '
				<input type="checkbox" name="edit_case_sensitive" id="edit_case_sensitive" ', ($context['glossary_action_status'] == 'edit' && isset($_POST['edit_case_sensitive'])) ? 'checked' : '', '></label><br>
				<label for="edit_tag_only">'.$txt['glossary_keyword_tag_only'] . '
				<input type="checkbox" name="edit_tag_only" id="edit_tag_only" ', ($context['glossary_action_status'] == 'edit' && isset($_POST['edit_tag_only'])) ? 'checked' : '', '></label><br>
				<input type="hidden" name="edit_word_id" id="edit_word_id" size="50" value="', $context['glossary_action_status'] == 'edit' ? $_POST['edit_word_id'] : '', '">
				<input type="hidden" name="sc" value="', $context['session_id'], '" />
				<input type="hidden" name="submit_edit_word" id="submit_edit_word" value="" />
				<input type="hidden" name="is_author_of_word" id="is_author_of_word" value="" />
				</div>
			</form>
			<div class="buttons">
				<div onclick="javascript:if(document.getElementById(\'div_error_submit\')) document.getElementById(\'div_error_submit\').style.display=\'none\';$.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				<div onclick="SubmitEditWord(\'', $groupsEnabled ? 'group_enabled' : '', '\')">'.$txt['glossary_edit_word_button'].'</div>
			</div>
		</div>';

	// DELETE a word DIV
	echo '
		<div id="div_delete_word" style="display:none;">
			<div class="header"><span>'.$txt['glossary_tip_delete'].'</span></div>
			<form method="post" name="form_delete_word" style="padding: 5px;">
				<div class="ModalContent">
					<input type="hidden" name="id_word_to_delete" id="id_word_to_delete" value="">
					'.$txt['glossary_confirm_deleting'].'
					<input type="hidden" name="sc" value="', $context['session_id'], '" />
					<input type="hidden" name="submit_delete_word" value="true" />
				</div>
			<div class="buttons">
				<div onclick="$.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				<div onclick="document.form_delete_word.submit()">'.$txt['glossary_delete_word_button'].'</div>
			</div>
			</form>
		</div>';

	// MANAGE categories DIV
	echo '
		<div id="div_manage_groups" style="display:none;">
			<div class="header"><span>'.$txt['glossary_action_add_category'].'</span></div>
			<form method="post" name="manage_groups" style="padding: 5px;">
				<div class="ModalContent">
				<fieldset>
					<legend>'.$txt['glossary_add_new_group'].'</legend>
						<label>'.$txt['glossary_new_group_name'].' :
						<input type="text" name="manage_new_group" id="manage_new_group" value="', $context['glossary_action_status'] == 'check_new_group' ? $_POST['manage_new_group'] : '', '" />
						<input type="button" class="button" onclick="AddNewGroup()" value="'.$txt['glossary_new_word_button'].'" style="float:right; margin-top:5px"></label>
				</fieldset>
				<fieldset>',
					'<legend>'.$txt['glossary_modify_group'].'</legend>
						<label>'.$txt['glossary_group'].' :
						<select name="group_update" id="group_update">
							<option value="none"> --- '.$txt['glossary_group_none'].' --- </option>';

	foreach ($context['glossary_groups'] as $id=>$title)
		echo '
							<option value="'.$id.'"',($context['glossary_action_status'] == 'check_update_group' && !empty($_POST['group_update']))?'':'selected', '>'.$title.'</option>';

	echo '</select></label><br>
						'.$txt['glossary_update_group_title'].' :
						<input type="text" name="update_category_title" id="update_category_title" value="', $context['glossary_action_status'] == 'check_update_group' ? $_POST['update_category_title'] : '', '" style="margin: 8px 0;" /><br>
						<input type="button" class="button" onclick="DeleteGroup()" value="'.$txt['glossary_delete_group_button'].'">
						<input type="button" class="button" onclick="UpdateGroup()" value="'.$txt['glossary_update_group_button'].'" style="float:right;">
				</fieldset>
				<input type="hidden" name="sc" value="', $context['session_id'], '" />
				</div>
				<div class="buttons">
					<div onclick="javascript:CleanGroupForm();$.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				</div>
			</form>
		</div>';

	// APPROVE ALL DIV
	echo '
		<div id="div_approve_all" style="display:none;">
			<div class="header"><span>'.$txt['glossary_action'].'</span></div>
			<div class="ModalContent">'.$txt['glossary_approve_all'].'</div>
			<div class="buttons">
				<div onclick="$.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				<div onclick="ApproveAll()">'.$txt['glossary_go_on'].'</div>
			</div>
			</form>
		</div>';

	// UNAPPROVE ALL DIV
	echo '
		<div id="div_unapprove_all" style="display:none;">
			<div class="header"><span>'.$txt['glossary_action'].'</span></div>
			<div class="ModalContent">'.$txt['glossary_unapprove_all'].'</div>
			<div class="buttons">
				<div onclick="$.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				<div onclick="UnapproveAll()">'.$txt['glossary_go_on'].'</div>
			</div>
			</form>
		</div>';

	// TOOLTIP ALL DIV
	echo '
		<div id="div_tooltip_on_all" style="display:none;">
			<div class="header"><span>'.$txt['glossary_action'].'</span></div>
			<div class="ModalContent">'.$txt['glossary_tooltip_on_all'].'</div>
			<div class="buttons">
				<div onclick="$.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				<div onclick="TooltipOnAll()">'.$txt['glossary_go_on'].'</div>
			</div>
		</div>';

	// UNTOOLTIP ALL DIV
	echo '
		<div id="div_tooltip_off_all" style="display:none;">
			<div class="header"><span>'.$txt['glossary_action'].'</span></div>
			<div class="ModalContent">'.$txt['glossary_tooltip_off_all'].'</div>
			<div class="buttons">
				<div onclick="$.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				<div onclick="TooltipOffAll()">'.$txt['glossary_go_on'].'</div>
			</div>
		</div>';

	// Version 1.2: Added "case_sensitive" option to only show keywords with exact case match.
	// CASE SENSITIVE ALL DIV
	echo '
		<div id="div_case_sensitive_on_all" style="display:none;">
			<div class="header"><span>'.$txt['glossary_action'].'</span></div>
			<div class="ModalContent">'.$txt['glossary_case_sensitive_on_all'].'</div>
			<div class="buttons">
				<div onclick="$.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				<div onclick="CaseSensitiveOnAll()">'.$txt['glossary_go_on'].'</div>
			</div>
		</div>';

	// Version 1.2: Added "case_sensitive" option to only show keywords with exact case match.
	// NOT CASE SENSITIVE ALL DIV
	echo '
		<div id="div_case_sensitive_off_all" style="display:none;">
			<div class="header"><span>'.$txt['glossary_action'].'</span></div>
			<div class="ModalContent">'.$txt['glossary_case_sensitive_off_all'].'</div>
			<div class="buttons">
				<div onclick="$.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				<div onclick="CaseSensitiveOffAll()">'.$txt['glossary_go_on'].'</div>
			</div>
		</div>';

	// Version 1.4: Added "tag_only" option to only process individual keywords in BBCode tags.
	// TAG ONLY ALL DIV
	echo '
		<div id="div_tag_only_on_all" style="display:none;">
			<div class="header"><span>'.$txt['glossary_action'].'</span></div>
			<div class="ModalContent">'.$txt['glossary_tag_only_on_all'].'</div>
			<div class="buttons">
				<div onclick="$.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				<div onclick="TagOnlyOnAll()">'.$txt['glossary_go_on'].'</div>
			</div>
		</div>';

	// Version 1.4: Added "tag_only" option to only process individual keywords in BBCode tags.
	// NOT TAG ONLY ALL DIV
	echo '
		<div id="div_tag_only_off_all" style="display:none;">
			<div class="header"><span>'.$txt['glossary_action'].'</span></div>
			<div class="ModalContent">'.$txt['glossary_tag_only_off_all'].'</div>
			<div class="buttons">
				<div onclick="$.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				<div onclick="TagOnlyOffAll()">'.$txt['glossary_go_on'].'</div>
			</div>
		</div>';

	// CHANGE GROUP SELECTION DIV
	echo '
		<div id="div_change_group_all" style="display:none;">
			<div class="header"><span>'.$txt['glossary_action'].'</span></div>
			<div class="ModalContent">
				<label>'.$txt['glossary_change_group_all'].'<br>
				<select name="change_group_to_id" id="change_group_to_id">
					<option value="none"> --- '.$txt['glossary_group_none'].' --- </option>';

	foreach ($context['glossary_groups'] as $id=>$title)
		echo '
					<option value="'.$id.'">'.$title.'</option>';

	echo'
				</select></label>
			</div>
			<div class="buttons">
				<div onclick="$.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				<div onclick="ChangeGroupAll()">'.$txt['glossary_go_on'].'</div>
			</div>
		</div>';

	// FORM for actions
	echo '
		<form method="post" name="form_action">
			<input type="hidden" name="id_word" id="id_word" value="">
			<input type="hidden" name="action_on_word" id="action_on_word" value="">
			<input type="hidden" name="sc" value="', $context['session_id'], '" />
		</form>
		<form method="post" name="form_admin_action">
			<input type="hidden" name="list_of_ids" id="list_of_ids" value="">
			<input type="hidden" name="action_on_list" id="action_on_list" value="">
			<input type="hidden" name="group_id" id="group_id" value="">
			<input type="hidden" name="sc" value="', $context['session_id'], '" />
		</form>';

	// CONTEXTUAL MENU
	// Version 1.2: Added "case_sensitive" option to only show keywords with exact case match.
	// Version 1.4: Added "tag_only" option to only process individual keywords in BBCode tags.
	if (allowedTo('glossary_admin') && $modSettings['glossary_admin_context_menu'])
	{
		echo '
		<ul id="AdminContextMenu" class="contextMenu">
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_approve_all\')"><img src="'. $imagesGlossary . '/glossary_approved.png"> '. $txt['glossary_action_approve_all'] . '</a>
			</li>
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_unapprove_all\')"><img src="'. $imagesGlossary . '/glossary_unapproved.png"> '. $txt['glossary_action_unapprove_all'] . '</a>
			</li>
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_tooltip_on_all\')"><img src="'. $imagesGlossary . '/glossary_tooltip_on.png"> '. $txt['glossary_action_tooltip_on_all'] .'</a>
			</li>
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_tooltip_off_all\')"><img src="'. $imagesGlossary . '/glossary_tooltip_off.png"> '. $txt['glossary_action_tooltip_off_all'] . '</a>
			</li>
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_case_sensitive_on_all\')"><img src="'. $imagesGlossary . '/glossary_case_sensitive_on.png"> '. $txt['glossary_action_case_sensitive_on_all'] .'</a>
			</li>
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_case_sensitive_off_all\')"><img src="'. $imagesGlossary . '/glossary_case_sensitive_off.png"> '. $txt['glossary_action_case_sensitive_off_all'] . '</a>
			</li>
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_tag_only_on_all\')"><img src="'. $imagesGlossary . '/glossary_tag_only_on.png"> '. $txt['glossary_action_tag_only_on_all'] .'</a>
			</li>
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_tag_only_off_all\')"><img src="'. $imagesGlossary . '/glossary_tag_only_off.png"> '. $txt['glossary_action_tag_only_off_all'] . '</a>
			</li>';

		if ($groupsEnabled)
		echo '
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_change_group_all\')"><img src="'. $imagesGlossary . '/glossary_change_category.png"> '. $txt['glossary_action_change_group_all'] . '</a>
			</li>
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_manage_groups\')"><img src="'. $imagesGlossary . '/glossary_manage_category.png"> '. $txt['glossary_action_add_category'] . '</a>
			</li>';

		echo '
			<li>
			<a href="#" onclick="CheckboxSelect(\'select\')"><img src="'. $imagesGlossary . '/glossary_selectall.png"> '. $txt['glossary_action_select_all'] . '</a>
			</li>
			<li>
			<a href="#" onclick="CheckboxSelect(\'unselect\')"><img src="'. $imagesGlossary . '/glossary_unselectall.png"> '. $txt['glossary_action_unselect_all'] . '</a>
			</li>
			<li class="quit separator">
				<a href="#quit">'. $txt['glossary_new_word_close'] . '</a>
			</li>
		</ul>';

		// The Javascript and CSS stuff for the 'right-click' context menu.
		echo '
			<script language="JavaScript" type="text/javascript" src="' . $themeURL . '/scripts/glossary/glossary.contextMenu.js"></script>
			<script>$( document ).ready( function() { $( "#table_full_table" ).contextMenu({ menu: "AdminContextMenu" }); });</script>
			<link rel="stylesheet" type="text/css" href="'. $themeURL . '/css/glossary/glossary.contextMenu.css" />';
	}

	// If keyword/synonym issues have been detected.
	if (allowedTo('glossary_admin') && !empty($context['synonymCheck']))
	echo '
		<script language="JavaScript" type="text/javascript" src="' . $themeURL . '/scripts/glossary/glossary.ui.tooltip.js"></script>
		<link rel="stylesheet" type="text/css" href="'. $themeURL . '/css/glossary/glossary.ui.tooltip.css" />
		<script>
			$( "#dialog" ).glossDialog({ autoOpen: false });
			$( "#opener" ).click( function() { $( "#dialog" ).glossDialog( "open" ); });
		</script>';

	echo '<script>

	function Display_glossary_for_letter(letter)
	{
		var mystring = "',isset($context['glossary_letters']) ? $context['glossary_letters'] : '','";
		var myarray=mystring.split(",");
		for (var i=0; i<(myarray.length-1); i++)
		{
			if (letter == "all")
				document.getElementById("letter_"+myarray[i]).style.display = "";
			else
			{
				if (myarray[i] == letter)
					document.getElementById("letter_"+myarray[i]).style.display = "";
				else
					document.getElementById("letter_"+myarray[i]).style.display = "none";
			}
		}
	}

	function SubmitNewWord(group_enabled)
	{
		if (group_enabled == "")
		{
			if (document.getElementById("new_word").value != "" && document.getElementById("new_definition").value != "")
			{
				document.getElementById("submit_new_word").value = "ok"
				document.new_word_form.submit();
			}
			else
				alert("'.$txt['glossary_alert_submit_new_word'].'");
		}
		else
		{
			if (document.getElementById("new_word").value != "" && document.getElementById("new_definition").value != "")
			{
				document.getElementById("submit_new_word").value = "ok"
				document.new_word_form.submit();
			}
			else
				alert("'.$txt['glossary_alert_submit_new_word'].'");
		}
	}

	function SubmitEditWord(group_enabled)
	{
		if (group_enabled == "")
		{
			if (document.getElementById("edit_word").value != "" && document.getElementById("edit_definition").value != "")
			{
				document.getElementById("submit_edit_word").value = "ok"
				document.edit_word_form.submit();
			}
			else
				alert("'.$txt['glossary_alert_submit_new_word'].'");
		}
		else
		{
			if (document.getElementById("edit_word").value != "" && document.getElementById("edit_definition").value != "")
			{
				document.getElementById("submit_edit_word").value = "ok"
				document.edit_word_form.submit();
			}
			else
				alert("'.$txt['glossary_alert_submit_new_word'].'");
		}
	}

	// Version 1.2: Added "case_sensitive" option to only show keywords with exact case match.
	// Version 1.4: Added "tag_only" option to only process individual keywords in BBCode tags.
	function EditWord(id,group_enabled,admin)
	{
		document.getElementById("edit_word").value=document.getElementById("word_"+id).innerHTML;
		document.getElementById("edit_definition").value=document.getElementById("definition_text_"+id).value;
		document.getElementById("edit_synonyms").value=document.getElementById("synonyms_"+id).value;

		if (document.getElementById("show_in_message_"+id).value == 1)
			document.getElementById("edit_show_in_message").checked = true;

		if (document.getElementById("case_sensitive"+id).value == 1)
			document.getElementById("edit_case_sensitive").checked = true;

		if (document.getElementById("tag_only"+id).value == 1)
			document.getElementById("edit_tag_only").checked = true;

		if (admin == "true" && document.getElementById("valid_"+id).value == 1)
			document.getElementById("edit_valid").checked = true;

		if (group_enabled != "") {
			if (document.getElementById("group_id_"+id).value == 0)
				document.getElementById("edit_group").value="";
			else
				document.getElementById("edit_group").value=document.getElementById("group_id_"+id).value;
		}

		document.getElementById("edit_word_id").value=id;

		if (admin == "false")
			document.getElementById("is_author_of_word").value="true";

		$("#div_edit_word").modal(
		{
			close:false,
			overlayId:"ModalOverlay",
			containerId:"ModalContainer",
		});
	}

	function DeleteWord(id)
	{
		document.getElementById("id_word_to_delete").value=id;
		$("#div_delete_word").modal(
		{
			close:false,
			overlayId:"ModalOverlay",
			containerId:"ModalContainer",
		});
	}

	function ApproveWord(id)
	{
		document.getElementById("id_word").value=id;
		document.getElementById("action_on_word").value="approve_word";
		document.form_action.submit();
	}

	function UnApproveWord(id)
	{
		document.getElementById("id_word").value=id;
		document.getElementById("action_on_word").value="unapprove_word";
		document.form_action.submit();
	}

	function EnableTooltipWord(id)
	{
		document.getElementById("id_word").value=id;
		document.getElementById("action_on_word").value="enable_tooltip";
		document.form_action.submit();
	}

	function DisableTooltipWord(id)
	{
		document.getElementById("id_word").value=id;
		document.getElementById("action_on_word").value="disable_tooltip";
		document.form_action.submit();
	}

	// Version 1.2: Added "case_sensitive" option to only show keywords with exact case match.
	function EnableCaseSensitive(id)
	{
		document.getElementById("id_word").value=id;
		document.getElementById("action_on_word").value="enable_case_sensitive";
		document.form_action.submit();
	}

	// Version 1.2: Added "case_sensitive" option to only show keywords with exact case match.
	function DisableCaseSensitive(id)
	{
		document.getElementById("id_word").value=id;
		document.getElementById("action_on_word").value="disable_case_sensitive";
		document.form_action.submit();
	}

	// Version 1.4: Added "tag_only" option to only process individual keywords in BBCode tags.
	function EnableTagOnly(id)
	{
		document.getElementById("id_word").value=id;
		document.getElementById("action_on_word").value="enable_tag_only";
		document.form_action.submit();
	}

	// Version 1.4: Added "tag_only" option to only process individual keywords in BBCode tags.
	function DisableTagOnly(id)
	{
		document.getElementById("id_word").value=id;
		document.getElementById("action_on_word").value="disable_tag_only";
		document.form_action.submit();
	}

	function AddNewGroup()
	{
		if (document.getElementById("manage_new_group").value != "")
		{
			document.manage_groups.submit();
			CleanGroupForm();
		}
		else
			alert("'.$txt['glossary_alert_new_group'].'");
	}

	function DeleteGroup()
	{
		if (document.getElementById("group_update").value != "none")
		{
			document.manage_groups.submit();
			CleanGroupForm();
		}
		else
			alert("'.$txt['glossary_alert_group_delete'].'");
	}

	function UpdateGroup()
	{
		if (document.getElementById("group_update").value != "none" && document.getElementById("update_category_title").value != "")
		{
			document.manage_groups.submit();
			CleanGroupForm();
		}
		else
			alert("'.$txt['glossary_alert_group_update'].'");
	}

	function CleanGroupForm()
	{
		if (document.getElementById("div_group_update_error"))
			document.getElementById("div_group_update_error").style.display = "none";

		if (document.getElementById("div_group_new_error"))
			document.getElementById("div_group_new_error").style.display = "none";

		document.getElementById("manage_new_group").value = "";
		document.getElementById("update_category_title").value = "";
	}

	function ApproveAll() {
		// Get all selected
		var list_of_ids = "";
		var list = document.getElementById("full_list_of_ids").value.split(";");
		for (i = 1; i < list.length; i++)
			if (document.getElementById("glossary_cb_"+list[i]).checked == true)
				list_of_ids = list[i]+";"+list_of_ids;

		// Send them
		document.getElementById("list_of_ids").value=list_of_ids;
		document.getElementById("action_on_list").value="approve_selected";
		document.form_admin_action.submit();
	}

	function UnapproveAll() {
		// Get all selected
		var list_of_ids = "";
		var list = document.getElementById("full_list_of_ids").value.split(";");
		for (i = 1; i < list.length; i++)
			if (document.getElementById("glossary_cb_"+list[i]).checked == true)
				list_of_ids = list[i]+";"+list_of_ids;

		// Send them
		document.getElementById("list_of_ids").value=list_of_ids;
		document.getElementById("action_on_list").value="unapprove_selected";
		document.form_admin_action.submit();
	}

	function TooltipOnAll()
	{
		// Get all selected
		var list_of_ids = "";
		var list = document.getElementById("full_list_of_ids").value.split(";");
		for (i = 1; i < list.length; i++)
			if (document.getElementById("glossary_cb_"+list[i]).checked == true)
				list_of_ids = list[i]+";"+list_of_ids;

		// Send them
		document.getElementById("list_of_ids").value=list_of_ids;
		document.getElementById("action_on_list").value="tooltip_on_selected";
		document.form_admin_action.submit();
	}

	function TooltipOffAll() {
		// Get all selected
		var list_of_ids = "";
		var list = document.getElementById("full_list_of_ids").value.split(";");
		for (i = 1; i < list.length; i++)
			if (document.getElementById("glossary_cb_"+list[i]).checked == true)
				list_of_ids = list[i]+";"+list_of_ids;

		// Send them
		document.getElementById("list_of_ids").value=list_of_ids;
		document.getElementById("action_on_list").value="tooltip_off_selected";
		document.form_admin_action.submit();
	}

	// Version 1.2: Added "case_sensitive" option to only show keywords with exact case match.
	function CaseSensitiveOnAll() {
		// Get all selected
		var list_of_ids = "";
		var list = document.getElementById("full_list_of_ids").value.split(";");
		for (i = 1; i < list.length; i++)
			if (document.getElementById("glossary_cb_"+list[i]).checked == true)
				list_of_ids = list[i]+";"+list_of_ids;

		// Send them
		document.getElementById("list_of_ids").value=list_of_ids;
		document.getElementById("action_on_list").value="case_sensitive_on_selected";
		document.form_admin_action.submit();
	}

	// Version 1.2: Added "case_sensitive" option to only show keywords with exact case match.
	function CaseSensitiveOffAll() {
		// Get all selected
		var list_of_ids = "";
		var list = document.getElementById("full_list_of_ids").value.split(";");
		for (i = 1; i < list.length; i++)
			if (document.getElementById("glossary_cb_"+list[i]).checked == true)
				list_of_ids = list[i]+";"+list_of_ids;

		// Send them
		document.getElementById("list_of_ids").value=list_of_ids;
		document.getElementById("action_on_list").value="case_sensitive_off_selected";
		document.form_admin_action.submit();
	}

	// Version 1.4: Added "tag_only" option to only process individual keywords in BBCode tags.
	function TagOnlyOnAll() {
		// Get all selected
		var list_of_ids = "";
		var list = document.getElementById("full_list_of_ids").value.split(";");
		for (i = 1; i < list.length; i++)
			if (document.getElementById("glossary_cb_"+list[i]).checked == true)
				list_of_ids = list[i]+";"+list_of_ids;

		// Send them
		document.getElementById("list_of_ids").value=list_of_ids;
		document.getElementById("action_on_list").value="tag_only_on_selected";
		document.form_admin_action.submit();
	}

	// Version 1.4: Added "tag_only" option to only process individual keywords in BBCode tags.
	function TagOnlyOffAll() {
		// Get all selected
		var list_of_ids = "";
		var list = document.getElementById("full_list_of_ids").value.split(";");
		for (i = 1; i < list.length; i++)
			if (document.getElementById("glossary_cb_"+list[i]).checked == true)
				list_of_ids = list[i]+";"+list_of_ids;

		// Send them
		document.getElementById("list_of_ids").value=list_of_ids;
		document.getElementById("action_on_list").value="tag_only_off_selected";
		document.form_admin_action.submit();
	}

	function ChangeGroupAll() {
		// Get all selected
		var list_of_ids = "";
		var list = document.getElementById("full_list_of_ids").value.split(";");
		for (i = 1; i < list.length; i++)
			if (document.getElementById("glossary_cb_"+list[i]).checked == true)
				list_of_ids = list[i]+";"+list_of_ids;

		// Send them
		document.getElementById("list_of_ids").value=list_of_ids;
		document.getElementById("action_on_list").value="change_group_selected";
		document.getElementById("group_id").value=document.getElementById("change_group_to_id").value;
		document.form_admin_action.submit();
	}

	function CheckboxSelect(type) {
		var list = document.getElementById("full_list_of_ids").value.split(";");
		for (i = 1; i < list.length; i++)
		{
			if (type == "select")
				document.getElementById("glossary_cb_"+list[i]).checked = true;

			if (type == "unselect")
				document.getElementById("glossary_cb_"+list[i]).checked = false;
		}
	}

	function ActionContextMenu(div) {
		$("#"+div).modal({
			close:false,
			overlayId:"ModalOverlay",
			containerId:"ModalContainer",
		});
	}

	$("a#div_new_word-show").click(function() {
		$("#div_new_word").modal({
			close:false,
			overlayId:"ModalOverlay",
			containerId:"ModalContainer",
		});
	});

	$("a#div_manage_groups-show").click(function() {
		$("#div_manage_groups").modal({
			close:false,
			overlayId:"ModalOverlay",
			containerId:"ModalContainer",
		});
	});

	$("a#div_approve_all-show").click(function() {
		$("#div_approve_all").modal({
			close:false,
			overlayId:"ModalOverlay",
			containerId:"ModalContainer",
		});
	});

	$("a#div_unapprove_all-show").click(function() {
		$("#div_unapprove_all").modal({
			close:false,
			overlayId:"ModalOverlay",
			containerId:"ModalContainer",
		});
	});

	$("a#div_tooltip_on_all-show").click(function() {
		$("#div_tooltip_on_all").modal({
			close:false,
			overlayId:"ModalOverlay",
			containerId:"ModalContainer",
		});
	});

	$("a#div_tooltip_off_all-show").click(function() {
		$("#div_tooltip_off_all").modal({
			close:false,
			overlayId:"ModalOverlay",
			containerId:"ModalContainer",
		});
	});

	// Version 1.2: Added "case_sensitive" option to only show keywords with exact case match.
	$("a#div_case_sensitive_on_all-show").click(function() {
		$("#div_case_sensitive_on_all").modal({
			close:false,
			overlayId:"ModalOverlay",
			containerId:"ModalContainer",
		});
	});

	// Version 1.2: Added "case_sensitive" option to only show keywords with exact case match.
	$("a#div_case_sensitive_off_all-show").click(function() {
		$("#div_case_sensitive_off_all").modal({
			close:false,
			overlayId:"ModalOverlay",
			containerId:"ModalContainer",
		});
	});

	// Version 1.4: Added "tag_only" option to only process individual keywords in BBCode tags.
	$("a#div_tag_only_on_all-show").click(function() {
		$("#div_tag_only_on_all").modal({
			close:false,
			overlayId:"ModalOverlay",
			containerId:"ModalContainer",
		});
	});

	// Version 1.4: Added "tag_only" option to only process individual keywords in BBCode tags.
	$("a#div_tag_only_off_all-show").click(function() {
		$("#div_tag_only_off_all").modal({
			close:false,
			overlayId:"ModalOverlay",
			containerId:"ModalContainer",
		});
	});

	$("a#div_change_group_all-show").click(function() {
		$("#div_change_group_all").modal({
			close:false,
			overlayId:"ModalOverlay",
			containerId:"ModalContainer",
		});
	});

	$("#glossary_admin_menu_show").click(function() {
		$("#glossary_admin_menu").slideToggle("slow");
	});

	$("#glossary_admin_menu").mousedown(function() {
		$("#glossary_admin_menu").slideToggle("slow");
	});

	</script>';
}

?>
