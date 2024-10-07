<?php

//============================================================================
// Original Glossary mod by Slinouille
// https://www.simplemachines.org/community/index.php?action=profile;u=68142
// https://custom.simplemachines.org/mods/index.php?mod=1525
//
// Updated and enhanced for SMF 2.1 by GL700Wing
// https://www.simplemachines.org/community/index.php?action=profile;u=112942
//============================================================================


global $helptxt, $modSettings;

$txt['glossary'] = 'Glossary';
$txt['glossary_action'] = 'Action';
$txt['glossary_action_add'] = 'Add a new keyword';
$txt['glossary_action_add_category'] = 'Manage categories';
$txt['glossary_action_admin'] = 'Administration';
$txt['glossary_action_approve_all'] = 'Approve keywords';
$txt['glossary_action_change_group_all'] = 'Assign to a category';
$txt['glossary_action_select_all'] = 'Select all';
$txt['glossary_action_tooltip_all'] = 'Enable tooltips';
$txt['glossary_action_unapprove_all'] = 'Unapprove keywords';
$txt['glossary_action_unselect_all'] = 'Unselect all';
$txt['glossary_action_untooltip_all'] = 'Disable tooltips';
$txt['glossary_add_new_group'] = 'Add new category';
$txt['glossary_alert_group_delete'] = 'You must select a category to delete it!';
$txt['glossary_alert_group_update'] = 'You must enter a name for the category';
$txt['glossary_alert_new_group'] = 'You must give a name to the new category!';
$txt['glossary_alert_submit_new_word'] = 'You must fill in all fields!';
$txt['glossary_all'] = 'All';
$txt['glossary_approve_all'] = 'Approve selected keywords?';
$txt['glossary_bbc'] = 'Insert Glossary keyword';
$txt['glossary_bbcodes_allowed'] = 'Only the bold, italic, underline and strikethrough BBCodes will be formatted in keyword definitions shown in tooltips.';
$txt['glossary_bbcodes_parsed'] = '(BBCodes parsed in tooltips)';
$txt['glossary_by_alphabetic'] = 'Alphabetic Order';
$txt['glossary_by_alphanumeric'] = 'Alphanumeric Order';
$txt['glossary_by_groups'] = 'By Categories';
$txt['glossary_change_group_all'] = 'Assign the selection to a category';
$txt['glossary_confirm_deleting'] = 'Please confirm the deletion of this keyword!';
$txt['glossary_delete_group_button'] = 'Delete category';
$txt['glossary_delete_word_button'] = 'Delete';
$txt['glossary_display_mode'] = 'Glossary index display mode';
$txt['glossary_edit_word_button'] = 'Update';
$txt['glossary_go_on'] = 'Go on';
$txt['glossary_group'] = 'Category';
$txt['glossary_group_none'] = 'No Category';
$txt['glossary_keyword_author'] = 'Added by';
$txt['glossary_modify_group'] = 'Modify categories';
$txt['glossary_new_definition'] = 'Definition';
$txt['glossary_new_group_name'] = 'Category name';
$txt['glossary_new_word'] = 'Keyword:';
$txt['glossary_new_word_button'] = 'Save';
$txt['glossary_new_word_close'] = 'Cancel';
$txt['glossary_new_word_title'] = 'Enter a new keyword';
$txt['glossary_not_approved'] = 'Not approved';
$txt['glossary_approve_keyword'] = 'Keyword is approved?';
$txt['glossary_show_tooltips'] = 'Show Glossary definitions as tooltips in messages';
$txt['glossary_submission_error_1'] = 'Error: This keyword already exists!';
$txt['glossary_submission_error_2'] = 'Error: This keyword is already being used as a synonym!';
$txt['glossary_submission_error_3'] = 'Error: The following synonyms are already being used as keywords: ';
$txt['glossary_submission_error_4'] = 'Error: The following synonyms are being used for other keywords: ';
$txt['glossary_submission_error_5'] = 'Error: This category already exists!';
$txt['glossary_suggestion_by'] = 'Keyword suggestion made by:';
$txt['glossary_suggestion_you_made'] = 'Suggestion you made ... pending approval!';
$txt['glossary_synonyms'] = 'Synonyms' . (empty($modSettings['glossary_enable_synonyms']) ? ' (Disabled)' : '');
$txt['glossary_synonyms_tip'] = 'Comma separated list of synonyms for this keyword.';
$txt['glossary_tip_approve'] = 'Approve keyword';
$txt['glossary_tip_delete'] = 'Delete keyword';
$txt['glossary_tip_edit'] = 'Edit keyword';
$txt['glossary_tip_select'] = 'Select';
$txt['glossary_tip_unapprove'] = 'Unapprove keyword';
$txt['glossary_tip_unvisible'] = 'Enable tooltip';
$txt['glossary_tip_visible'] = 'Disable tooltip';
$txt['glossary_tooltip_all'] = 'Enable tooltips for selected keywords?';
$txt['glossary_unapprove_all'] = 'Unapprove selected keywords?';
$txt['glossary_untooltip_all'] = 'Disable tooltips for selected keywords?';
$txt['glossary_update_group_button'] = 'Update category';
$txt['glossary_update_group_title'] = 'New name for category';


// Mod settings
$txt['glossary_mod_enable'] = 'Enable Glossary mod';
$txt['glossary_enable_tooltips'] = 'Show keyword definition in tooltips in messages';
$txt['glossary_tooltip_once'] = 'Only show Glossary definition in tooltip once per message';
$txt['glossary_case_sensitive'] = 'Keyword detection in messages is case sensitive';

$txt['glossary_enable_numeric'] = 'Allow numeric keywords to be added to Glossary';
$txt['glossary_enable_synonyms'] = 'Allow keywords to have synonyms';
$txt['glossary_enable_groups'] = 'Enable categories for keywords';

$txt['glossary_tooltip_bbc'] = 'Parse BBCodes in keyword definitions in tooltips<br><span class="smalltext">Note: ' . $txt['glossary_bbcodes_allowed'];
$txt['glossary_separator'] = 'Character used in keyword definition to insert line break in tooltip text';

$txt['glossary_show_used_chars'] = 'Only show alphanumeric characters with an associated keyword in Glossary index';
$txt['glossary_show_author_admin'] = 'Show keyword author to Glossary admins';
$txt['glossary_show_author_all'] = 'Show keyword author to members who can view Glossary';
$txt['glossary_show_tooltips_default'] = 'Enable <em>\'' . $txt['glossary_show_tooltips'] . '\'</em> checkbox when adding/updating a keyword';
$txt['glossary_approve_keyword_default'] = 'Enable <em>\'' . $txt['glossary_approve_keyword'] . '\'</em> checkbox when adding/updating a keyword';
$txt['glossary_admin_context_menu'] = 'Enable <em> \'right-click\' </em> context menu in Glossary index';
$txt['glossary_word_width'] = 'Width (in pixels) of <em>"Keyword"</em> column in Glossary index';
$txt['glossary_definition_width'] = 'Width (in pixels) of <em>"Glossary definition"</em> column in Glossary index';

// Mod settings help text
$helptxt['glossary_enable_tooltips'] = 'If this option is enabled the keyword will be displayed as coloured text and the keyword definition will be displayed in a tooltip when the keyword is hovered over.';
$helptxt['glossary_tooltip_once'] = 'If you want, you decide to show only once per post the tooltip of one keyword. This in order to prevent multiple tooltips for a keyword written several times in one post.';
$helptxt['glossary_case_sensitive'] = 'If enabled, the tooltip will be displayed on all keywords without checking the characters case.';
$helptxt['glossary_enable_synonyms'] = 'Allow keywords to also be referenced by one or more synonyms.<br><br>Note: To be valid a synonym cannot already be in use as a keyword or as a synonym for another keyword.<br><br>If this setting is not enabled synonyms will not be displayed as tooltips and the word \'(Disabled)\' will be shown for synonyms in the Glossary index ';
$helptxt['glossary_enable_groups'] = 'You can decide to manage one level of categories in the Glossary. This permits to categorize the keywords for a better understanding.';
$helptxt['glossary_tooltip_bbc'] = 'Even though o' . substr($txt['glossary_bbcodes_allowed'], 1, -1) . ' the keyword definition with all BBCodes formatted can be seen in the Glossary index by members who are allowed to view the Glossary index.';
$helptxt['glossary_separator'] = 'Enter a character to use to indicate were line breaks should occur when a keyword definition is displayed in a tooltip.<br><br>Note: If you specify a character that is used elsewhere in the body of a keyword definition line breaks will be inserted in these  locations.  Consider using a character that is not commonly used in text (eg, ~, ^, \, |, etc).';
$helptxt['glossary_show_used_chars'] = 'If this option is enabled alphabetic/numeric characters that do not have an associated keyword will be not be shown in the Glossary index.';
$helptxt['glossary_show_author_admin'] = 'For members who are allowed to administer/manager the Glossary: in the Glossary index show the name and a link to the profile of the member who added the keyword to the Glossary.<br><br>If the keyword was added by a forum visitor or someone who is no longer a member the name <em>\'' . $txt['guest'] . '\'</em> will be shown.';
$helptxt['glossary_show_author_all'] = 'For members who are allowed to view the Glossary: in the Glossary index show the name and a link to the profile of the member who added the keyword to the Glossary.<br><br>If the keyword was added by a forum visitor or someone who is no longer a member the name <em>\'' . $txt['guest'] . '\'</em> will be shown.';


// Mod permissions
$txt['glossary_admin'] = 'Glossary';
$txt['permissiongroup_glossary'] = 'Glossary';
$txt['permissionname_glossary_admin'] = 'Administer/manage Glossary';
$txt['permissionname_glossary_bbcode'] = 'Insert Glossary BBCode in messages';
$txt['permissionname_glossary_suggest'] = 'Suggest new Glossary keywords/definitions';
$txt['permissionname_glossary_tooltip'] = 'View Glossary definitions as tooltips in messages';
$txt['permissionname_glossary_view'] = 'View Glossary index';

// Mod permissions help text
$txt['permissionhelp_glossary_admin'] = 'Allow member to administer/manage the Glossary.<br><br>Glossary admins can add, remove, edit, approve, unapprove, and/or assign synonyms to keywords.<br><br>If the mod setting <em> "' . $txt['glossary_enable_tooltips'] . '" </em> is enabled a Glossary admin can enable/disable tooltips for individual keywords.<br><br>If the mod setting <em> "' . $txt['glossary_enable_groups'] . '" </em> is enabled a Glossary admin can add, remove, and/or edit categories and change the categories to which individual keywords are assigned.';
$txt['permissionhelp_glossary_bbcode'] = 'Allow member to use the Glossary BBCode to insert Glossary keywords in messages.';
$txt['permissionhelp_glossary_suggest'] = 'Allow member to suggest new Glossary keywords and definitions.';
$txt['permissionhelp_glossary_tooltip'] = 'Allow keyword definitions to be viewed in tooltips in messages.<br><br>Requires the mod setting <em> "' . $txt['glossary_enable_tooltips'] . '" </em> to be enabled.';
$txt['permissionhelp_glossary_view'] = 'Allow member to view keywords and keyword definitions in Glossary index.';

?>
