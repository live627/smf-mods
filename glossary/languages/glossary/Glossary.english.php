<?php

/*************************************************************************************************************
* Glossary.english.php - Language file for Glossary for SMF 2.1 mod (v1.4)
**************************************************************************************************************
* This mod is licensed under the 2-Clause BSD License, which can be found here:
*	https://opensource.org/licenses/BSD-2-Clause
**************************************************************************************************************
* Copyright (c) 2008-2009 Slinouille, 2024 Kathy Leslie
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

global $helptxt, $modSettings;

loadLanguage('index');
loadLanguage('Admin');
loadLanguage('ManageBoards');

$synonyms_disabled = $modSettings['glossary_enable_synonyms'] ? '' : '[Not in Use]';

$txt['glossary'] = 'Glossary';
$txt['glossary_action'] = 'Action';
$txt['glossary_action_add'] = 'Add a new keyword';
$txt['glossary_action_add_category'] = 'Manage categories';
$txt['glossary_action_admin'] = 'Administration';
$txt['glossary_action_approve_all'] = 'Approve keywords';
$txt['glossary_action_case_sensitive_off_all'] = 'Disable case sensitivity';
$txt['glossary_action_case_sensitive_on_all'] = 'Enable case sensitivity';
$txt['glossary_action_change_group_all'] = 'Assign to a category';
$txt['glossary_action_check'] = 'Keyword/synonym issues detected!';
$txt['glossary_action_check_error_cssc'] = 'below contain multiple consecutive spaces and/or special characters that may not be parsed correctly:</strong>';
$txt['glossary_action_check_error_cssc_note'] = '<strong>Note:</strong> If a keyword/synonym containing special characters is being parsed correctly you can ignore the warning for that particular keyword/synonym.<hr>';
$txt['glossary_action_check_error_1'] = '<strong>The keywords below are being used more than once:</strong>';
$txt['glossary_action_check_error_2'] = '<strong>The keywords ' . $txt['glossary_action_check_error_cssc'];
$txt['glossary_action_check_error_3'] = '<strong>The keywords below do not have a valid definition:</strong>';
$txt['glossary_action_check_error_4'] = '<strong>The synonyms below are also being used as keywords:</strong>';
$txt['glossary_action_check_error_5'] = '<strong>The synonyms below are being used more than once:</strong>';
$txt['glossary_action_check_error_6'] = '<strong>The synonyms ' . $txt['glossary_action_check_error_cssc'];
$txt['glossary_action_check_error_note'] = '<strong>Note:</strong> The actual character case of the keywords/synonyms may be different to what is shown above.';
$txt['glossary_action_check_error_note_sc'] = 'In addition, the new/existing keywords/synonyms above may contain special characters that are not shown because they are not supported.';
$txt['glossary_action_select_all'] = 'Select all';
$txt['glossary_action_suggest'] = 'Suggest a new keyword';
$txt['glossary_action_tag_only_off_all'] = 'Disable BBCode tag only';
$txt['glossary_action_tag_only_on_all'] = 'Enable BBCode tag only';
$txt['glossary_action_tooltip_off_all'] = 'Disable tooltips';
$txt['glossary_action_tooltip_on_all'] = 'Enable tooltips';
$txt['glossary_action_unapprove_all'] = 'Unapprove keywords';
$txt['glossary_action_unselect_all'] = 'Unselect all';
$txt['glossary_add_new_group'] = 'Add new category';
$txt['glossary_alert_group_delete'] = 'You must select a category to delete it!';
$txt['glossary_alert_group_update'] = 'You must enter a name for the category';
$txt['glossary_alert_new_group'] = 'You must give a name to the new category!';
$txt['glossary_alert_submit_new_word'] = 'You must fill in all fields!';
$txt['glossary_all'] = 'All';
$txt['glossary_approve_all'] = 'Approve selected keywords?';
$txt['glossary_approve_keyword'] = 'Approve keyword for use';
$txt['glossary_bbc'] = 'Insert Glossary keyword';
$txt['glossary_bbcodes_allowed'] = 'Only the bold, italic, underline and strikethrough BBCodes are allowed in keyword definitions.';
$txt['glossary_bbcodes_parsed'] = '[BBCodes are parsed in tooltips]';
$txt['glossary_by_alphabetic'] = 'Alphabetic Order';
$txt['glossary_by_alphanumeric'] = 'Alphanumeric Order';
$txt['glossary_by_groups'] = 'By Categories';
$txt['glossary_case_sensitive_off_all'] = 'Disable case sensitivity for selected keywords?';
$txt['glossary_case_sensitive_on_all'] = 'Enable case sensitivity for selected keywords?';
$txt['glossary_change_group_all'] = 'Assign the selection to a category';
$txt['glossary_confirm_deleting'] = 'Please confirm the deletion of this keyword!';
$txt['glossary_count_keyword'] = 'Keywords: ';
$txt['glossary_count_synonym'] = 'Synonyms: ';
$txt['glossary_definition_keyword'] = 'Keyword: ';
$txt['glossary_definition_synonyms'] = 'Synonyms: ';
$txt['glossary_delete_group_button'] = 'Delete category';
$txt['glossary_delete_word_button'] = 'Delete';
$txt['glossary_display_mode'] = 'Glossary list display mode';
$txt['glossary_edit_word_button'] = 'Update';
$txt['glossary_go_on'] = 'Go on';
$txt['glossary_group'] = 'Category';
$txt['glossary_group_none'] = 'No Category';
$txt['glossary_keyword_author'] = 'Added by';
$txt['glossary_keyword_case_sensitive'] = 'Keyword/synonym detection is case sensitive';
$txt['glossary_keyword_tag_only'] = 'Keyword/synonym detection in BBCode tag only';
$txt['glossary_keyword_list'] = 'Glossary Keyword List';
$txt['glossary_list_caseSensitive'] = 'Case Sensitive';
$txt['glossary_list_notApproved'] = 'Not Approved';
$txt['glossary_list_noTooltip'] = 'No Tooltip';
$txt['glossary_list_tagOnly'] = 'BBCode Tag Only';
$txt['glossary_modify_group'] = 'Modify categories';
$txt['glossary_new_definition'] = 'Definition (required)';
$txt['glossary_new_group_name'] = 'Category name';
$txt['glossary_new_word'] = 'Keyword (required)';
$txt['glossary_new_word_button'] = 'Save';
$txt['glossary_new_word_close'] = 'Cancel';
$txt['glossary_new_word_add'] = 'Add a new keyword and definition';
$txt['glossary_new_word_suggest'] = 'Suggest a new keyword and definition';
$txt['glossary_not_approved'] = 'Not approved';
$txt['glossary_show_tooltips'] = 'Enable tooltip for this keyword';
$txt['glossary_submission_error_1'] = 'Error: This keyword already exists!';
$txt['glossary_submission_error_2'] = 'Error: This keyword is already being used as a synonym!';
$txt['glossary_submission_error_3'] = 'Error: The following synonyms are already being used as keywords: ';
$txt['glossary_submission_error_4'] = 'Error: One or more of the following synonyms are already being used for other keywords: ';
$txt['glossary_submission_error_5'] = 'Error: This category already exists!';
$txt['glossary_suggestion_by'] = 'Unapproved keyword suggestion made by:';
$txt['glossary_suggestion_you_made'] = 'Unapproved keyword suggestion you made';
$txt['glossary_synonyms'] = 'Synonyms' . ($synonyms_disabled ? '<br>' . $synonyms_disabled : '');
$txt['glossary_synonyms_edit'] = 'Synonyms (optional) ' . $synonyms_disabled;
$txt['glossary_synonyms_tip'] = 'Comma separated list of synonyms for this keyword.';
$txt['glossary_tag_only_off_all'] = 'Disable BBCode tag only for selected keywords?';
$txt['glossary_tag_only_on_all'] = 'Enable BBCode tag only for selected keywords?';
$txt['glossary_tip_approve'] = 'Approve keyword';
$txt['glossary_tip_case_sensitive_off'] = 'Disable case sensitivity';
$txt['glossary_tip_case_sensitive_on'] = 'Enable case sensitivity';
$txt['glossary_tip_delete'] = 'Delete keyword';
$txt['glossary_tip_edit'] = 'Edit keyword';
$txt['glossary_tip_select'] = 'Select';
$txt['glossary_tip_tag_only_off'] = 'Disable BBCode tag only';
$txt['glossary_tip_tag_only_on'] = 'Enable BBCode tag only';
$txt['glossary_tip_tooltip_off'] = 'Disable tooltip';
$txt['glossary_tip_tooltip_on'] = 'Enable tooltip';
$txt['glossary_tip_unapprove'] = 'Unapprove keyword';
$txt['glossary_tip_view_profile'] = 'View profile';
$txt['glossary_tooltip_off_all'] = 'Disable tooltips for selected keywords?';
$txt['glossary_tooltip_on_all'] = 'Enable tooltips for selected keywords?';
$txt['glossary_unapprove_all'] = 'Unapprove selected keywords?';
$txt['glossary_update_group_button'] = 'Update category';
$txt['glossary_update_group_title'] = 'New name for category';


// Mod settings
$txt['glossary_mod_enable'] = 'Enable Glossary mod';

$txt['glossary_enable_tooltips'] = 'Enable tooltips for keywords in messages';
$txt['glossary_tooltip_signature'] = 'Also show tooltips in signatures';
$txt['glossary_tooltip_pm'] = 'Also show tooltips in PMs';
$txt['glossary_tooltip_news'] = 'Also show tooltips in <em>"News"</em> items';

$txt['glossary_bbcode_only_mode'] = 'Only show tooltips for keywords/synonyms inside the Glossary BBCode tag (eg,&nbsp;[glossary]Keyword[/glossary], [glossary]Synonym[/glossary])';
$txt['glossary_boards_to_exclude'] = 'Board IDs to exclude from Glossary use';
$txt['glossary_enable_boards_to_exclude'] = 'Enable <em>\'' . $txt['glossary_boards_to_exclude'] . '\'?</em>';
$txt['glossary_boards_to_exclude'] .= '<br><span class="smalltext">Note: Board IDs must be separated by a comma (eg, 1,2,5,12)</span>';
$txt['glossary_show_board_ids'] = '<span class="smalltext" style="margin-left: 3.5%;">Show board IDs in <em>\'' . $txt['boards_and_cats'] . ' > ' . $txt['boards_edit'] . '\'</em> list?</span>';

$txt['glossary_tooltip_bbc'] = 'Parse BBCodes in keyword definitions in tooltips<br><span class="smalltext">Note: ' . $txt['glossary_bbcodes_allowed'] . '</span>';
$txt['glossary_tooltip_once'] = 'Only show tooltip <u>once</u> per message for each keyword';
$txt['glossary_case_sensitive'] = 'Keyword detection is case sensitive for <u>all</u> keywords';
$txt['glossary_separator'] = 'Separator character(s) used in keyword definition to insert line break in tooltip text';
$txt['glossary_separator_convert'] = 'Convert separator characters to line breaks in the Glossary list';

$txt['glossary_enable_numeric'] = 'Allow numeric keywords to be added to Glossary';
$txt['glossary_enable_synonyms'] = 'Allow keywords to have synonyms';
$txt['glossary_show_synonyms'] = 'Show keyword and synonyms below definition in tooltip';
$txt['glossary_enable_groups'] = 'Enable categories for keywords';

$txt['glossary_show_used_chars'] = 'Only show alphanumeric characters with an associated keyword in the Glossary list';
$txt['glossary_show_author_admin'] = 'Show keyword author to Glossary admins in the Glossary list';
$txt['glossary_show_author_all'] = 'Show keyword author to everyone who can view the Glossary list';
$txt['glossary_author_exclude'] = 'Exclude keyword author and guests from keyword author view';
$txt['glossary_show_tooltips_default'] = 'Enable <em>\'' . $txt['glossary_show_tooltips'] . '\'</em> checkbox when adding a <u>new</u> keyword';
$txt['glossary_approve_keyword_default'] = 'Enable <em>\'' . $txt['glossary_approve_keyword'] . '\'</em> checkbox when adding a <u>new</u> keyword';
$txt['glossary_admin_context_menu'] = 'Enable <em> \'right-click\' </em> context Administration menu in the Glossary list';
$txt['glossary_word_width'] = 'Width (in pixels) of <em>"Keyword"</em> column in the Glossary list';
$txt['glossary_category_width'] = 'Width (in pixels) of <em>"Category"</em> column in the Glossary list';

// Mod settings help text
$helptxt['glossary_mod_enable'] = 'Note: If the mod is disabled Glossary admins will still have access to the Glossary list - this will enable them to view, add, delete, update, approve and/or unapprove Glossary keywords, definitions, and synonyms.<br>Also, if categories are enabled, Glossary admins will also be able to view, add, delete and/or update categories and add keywords to/remove keywords from categories.';
$helptxt['glossary_enable_tooltips'] = 'If this option is enabled the keyword will be displayed as coloured text and the keyword definition will be displayed in a tooltip.';
$helptxt['glossary_tooltip_signature'] = $helptxt['glossary_tooltip_pm'] = $helptxt['glossary_tooltip_news'] = 'Note: This setting can only be enabled if the <em> \'' . $txt['glossary_enable_tooltips'] . '\' </em> setting is enabled.';

$helptxt['glossary_bbcode_only_mode'] = 'If this option is <strong>enabled</strong>:<br>ONLY keywords inside a Glossary BBCode tag (eg,&nbsp;[glossary]Keyword[/glossary]) will have a tooltip.<br><br>If this option is <strong>disabled</strong>:<br>ANY word/phrase in a message body that matches a Glossary keyword will have a tooltip.';

$helptxt['glossary_enable_boards_to_exclude'] = 'When the use of the Glossary is excluded for a board tooltips will <strong>NOT</strong> be shown in the message body or in signatures for any messages in that board.';
$helptxt['glossary_show_board_ids'] = 'Board IDs will only be shown in the <em>\'' . $txt['boards_and_cats'] . ' > ' . $txt['boards_edit'] . '\'</em> list when this mod, the \'' . $txt['glossary_enable_boards_to_exclude'] . '\' option and this option are all enabled.</span>';

$helptxt['glossary_tooltip_once'] = 'By default if a keyword is used multiple times in a message a tooltip will be displayed each time. If this setting is enabled a tooltip will only be shown for the first occurrence of a keyword.';
$helptxt['glossary_case_sensitive'] = 'If enabled, a tooltip will <strong>only</strong> be displayed for keywords that have an <strong>exact</strong> upper/lower case name match.<br><br><strong>Note: </strong>This is a global setting - there is also an option via Glossary list administration to enable/disable this requirement for individual keywords.';
$helptxt['glossary_separator'] = '<strong>Note: This setting is provided for backward compatibility for Glossary lists imported from SMF 2.0.<br><br>With this version of the mod line breaks can be inserted in descriptions by pressing the [ENTER] key.<br><br>To convert the separator character(s) to line breaks when a keyword is edited enable the <em>\'' . $txt['glossary_separator_convert'] . '\'</em> option.</strong><br><hr>Enter one or two characters to use to indicate were line breaks should occur when a keyword definition is displayed in a tooltip.<br><br>Note: If the separator is used elsewhere in the body of a keyword definition line breaks will be inserted in these locations.<br><br>If specifying a single character separator consider using a character that is not commonly used in text (eg, ~, ^, \, |, etc).<br><br>While a two character separator can be <strong>ALMOST</strong> any sequence of characters (eg, ;;, ::, ||, etc, but <strong>NOT</strong> << or >>) you should test it to confirm that it works as expected.';
$helptxt['glossary_separator_convert'] = 'Enter the separator character(s) to be converted to line breaks when a keyword is edited in the <em>\'' . $txt['glossary_separator'] . '\'</em> setting.';

$helptxt['glossary_enable_synonyms'] = 'Allow keywords to also be referenced by one or more synonyms.<br><br>Note: To be valid a synonym cannot already be in use as a keyword or as a synonym for another keyword.<br><br>If this setting is not enabled synonyms will not be displayed as tooltips and <em> ' . $synonyms_disabled . ' </em> will be shown with synonyms in the Glossary list';
$helptxt['glossary_show_synonyms'] = 'If a keyword has synonyms show the keyword and synonyms below the definition in the tooltip.<br><br>Note: This setting can only be enabled if the <em> \'' . $txt['glossary_enable_synonyms'] . '\' </em> setting is enabled.';
$helptxt['glossary_enable_groups'] = 'Create categories for keywords in the Glossary list - this can be helpful for managing large Glossary lists.';

$helptxt['glossary_show_used_chars'] = 'If this option is enabled alphabetic/numeric characters that do not have an associated keyword will be not be shown in the Glossary list.';
$helptxt['glossary_show_author_admin'] = 'For Glossary admins: in the Glossary list show the name and a link to the profile of the member who added the keyword to the Glossary.<br><br>If the keyword was added by a forum visitor or someone who is no longer a member the name <em>\'' . $txt['guest'] . '\'</em> will be shown.';
$helptxt['glossary_show_author_all'] = 'For those who can view the Glossary list: show the name and a link to the profile of the member who added the keyword to the Glossary.<br><br>If the keyword was added by a forum visitor or someone who is no longer a member the name <em>\'' . $txt['guest'] . '\'</em> will be shown.';
$helptxt['glossary_author_exclude'] = 'Only show the names of other keyword authors who are current forum members (ie, not former members or guests).';


// Mod permissions
$txt['glossary_admin'] = 'Glossary';
$txt['permissiongroup_glossary'] = 'Glossary';
$txt['permissionname_glossary_tooltip'] = 'View keyword definitions as tooltips';
$txt['permissionname_glossary_bbcode'] = 'Insert Glossary BBCode';
$txt['permissionname_glossary_view'] = 'View Glossary list';
$txt['permissionname_glossary_suggest'] = 'Suggest new keywords/definitions';
$txt['permissionname_glossary_admin'] = ' Glossary Administrator';

// Mod permissions help text
$txt['permissionhelp_glossary_tooltip'] = 'Allow keyword definitions to be viewed in tooltips in messages.<br><br>Requires the mod setting <em> "' . $txt['glossary_enable_tooltips'] . '" </em> to be enabled.';
$txt['permissionhelp_glossary_bbcode'] = 'Allow member to use the Glossary BBCode to insert Glossary keywords in messages.';
$txt['permissionhelp_glossary_view'] = 'Allow member to view keywords and keyword definitions in the Glossary list.';
$txt['permissionhelp_glossary_suggest'] = 'Allow member to suggest new Glossary keywords and definitions.';
$txt['permissionhelp_glossary_admin'] = 'Allow member to administer/manage the Glossary.<br><br>Glossary admins can add, remove, edit, approve, unapprove, and/or assign synonyms to keywords.<br><br>If the mod setting <em> "' . $txt['glossary_enable_tooltips'] . '" </em> is enabled a Glossary admin can enable/disable tooltips for individual keywords.<br><br>If the mod setting <em> "' . $txt['glossary_enable_groups'] . '" </em> is enabled a Glossary admin can add, remove, and/or edit categories and change the categories to which individual keywords are assigned.';

?>
