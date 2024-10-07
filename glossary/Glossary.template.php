<?php

//============================================================================
// Original Glossary mod by Slinouille
// https://www.simplemachines.org/community/index.php?action=profile;u=68142
// https://custom.simplemachines.org/mods/index.php?mod=1525
//
// Updated and enhanced for SMF 2.1 by GL700Wing
// https://www.simplemachines.org/community/index.php?action=profile;u=112942
//============================================================================

function template_main()
{
	global $context, $modSettings, $scripturl, $settings, $txt;

	// Some variable defaults.
	$default_themeURL = $settings['default_theme_url'];
	$default_imagesGlossary = $default_themeURL . '/images/glossary';
	$groups_enabled = $modSettings['glossary_enable_groups'];

	// Load the SimpleModal and tooltip stuff for using in the Admin menu
	$simpleModal_js = $default_themeURL . '/scripts/glossary/glossary.jquery.simplemodal.js';
	$toolTip_js = $default_themeURL . '/scripts/glossary/glossary.jquery-ui.tooltip.js';
	$toolTip_css = $default_themeURL . '/css/glossary/glossary.jquery-ui.tooltip.css';

	echo '
		<script language="JavaScript" type="text/javascript" src="', $simpleModal_js, '"></script>
		<script language="JavaScript" type="text/javascript" src="', $toolTip_js, '"></script>
		<link rel="stylesheet" type="text/css" href="'. $toolTip_css. '" />';

	echo '
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
		<td width="100%" valign="top" style="padding-bottom: 10px;">
			<div class="tborder" style="margin-top: 1ex;">
				<div class="glossarytitle">
					<table width="100%"><tr>
						<td align="left" style="line-height: 30px;">',
							$groups_enabled
							? '<b>' . $txt['glossary_display_mode'] . ':</b>
							<div class="smalltext" style="display:inline;">
								<a href="'. $scripturl. '?action=glossary&sa=alphabetic">'. ($modSettings['glossary_enable_numeric'] ? $txt['glossary_by_alphanumeric'] : $txt['glossary_by_alphabetic']) . '</a>/<a href="'. $scripturl. '?action=glossary&sa=categories">'. $txt['glossary_by_groups']. '</a>
							</div>'
							: $txt['glossary'], '
						</td>
						<td align="right" class="smalltext">';

						if (allowedTo('glossary_suggest'))
							echo '
						<a href="#" title="'.$txt['glossary_new_word_title'].'" id="div_new_word-show"><img src="'. $default_imagesGlossary . '/glossary_blue_add.png"> '. $txt['glossary_action_add'] . '</a>';

						if (allowedTo('glossary_admin'))
						{
							echo '
						<a href="#" id="glossary_admin_menu_show"><img src="'. $default_imagesGlossary . '/glossary_wand.png"> '.$txt['glossary_action_admin'].'</a>
						<div>
							<div id="glossary_admin_menu">
								<a href="#" id="div_approve_all-show">'. $txt['glossary_action_approve_all'] . '<img src="'. $default_imagesGlossary . '/glossary_approved.png"></a><br>
								<a href="#" id="div_unapprove_all-show"> '. $txt['glossary_action_unapprove_all'] . '<img src="'. $default_imagesGlossary . '/glossary_unapproved.png"></a><br>
								<a href="#" id="div_tooltip_all-show">'. $txt['glossary_action_tooltip_all'] . '<img src="'. $default_imagesGlossary . '/glossary_bubble_enable_all.png"></a><br>
								<a href="#" id="div_untooltip_all-show">'. $txt['glossary_action_untooltip_all'] . '<img src="'. $default_imagesGlossary . '/glossary_bubble_disable_all.png"></a><br>';

							if ($groups_enabled)
							echo '
								<a href="#" id="div_change_group_all-show"> '. $txt['glossary_action_change_group_all'] . '<img src="'. $default_imagesGlossary . '/glossary_change_group.png"></a><br>
								<a href="#" id="div_manage_groups-show">'. $txt['glossary_action_add_category'] . '<img src="'. $default_imagesGlossary . '/glossary_category.png"></a><br>';

							echo '
								<a href="#" onclick="CheckboxSelect(\'select\')"> '. $txt['glossary_action_select_all'] . '<img src="'. $default_imagesGlossary . '/glossary_selectall.png"></a><br>
								<a href="#" onclick="CheckboxSelect(\'unselect\')"> '. $txt['glossary_action_unselect_all'] . '<img src="'. $default_imagesGlossary . '/glossary_unselectall.png"></a>
								<hr style="margin: 0;">
								<a href="#quit">'. $txt['glossary_new_word_close'] . '</a>
							</div>
						</div>';
						}

	echo '
						</td>
					</tr></table>
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
			<a href="#" title="Close" class="modalCloseX simplemodal-close">x</a>
			<div class="header"><span>'.$txt['glossary_new_word_title'].'</span></div>
			<form method="post" name="new_word_form" style="padding: 5px;">
				<div class="ModalContent">
				<label for="new_word">'.$txt['glossary_new_word'].'* </label>
				<input type="text" name="new_word" id="new_word" size="47" value=""><br>
				<label for="new_definition"', $context['glossary_tooltip_bbc'] ? 'title="'.$txt['glossary_bbcodes_allowed'].'"' : '', '>'.$txt['glossary_new_definition'].' ', $context['glossary_tooltip_bbc'] ? $txt['glossary_bbcodes_parsed'] : '', '* : </label>
				<textarea name="new_definition" id="new_definition" cols="55" rows="5"></textarea><br>';

				if ($groups_enabled)
				{
					echo '
					<label for="new_group">'.$txt['glossary_group'].'* </label>
					<select name="new_group" id="new_group" value="">
						<option value=""> --- '.$txt['glossary_group_none'].' --- </option>';

					foreach ($context['glossary_groups'] as $id=>$title)
					echo '
						<option value="'.$id.'">'.$title.'</option>';

					echo '</select><br>';
				}

	// Synonyms of this keyword
	echo '
				<label for="new_word_synonyms">'.$txt['glossary_synonyms'].' : </label>
				<input type="text" name="new_word_synonyms" id="new_word_synonyms" style="width:75%;" value="" title="'.$txt['glossary_synonyms_tip'].'"><br>';

	echo '
				<label for="new_word_show_in_message">'.$txt['glossary_show_tooltips'].'</label>
				<input type="checkbox" name="new_show_in_message"', $modSettings['glossary_show_tooltips_default'] ? ' checked' : '', '><br>',
				allowedTo('glossary_admin') ? '
				<label for="edit_publish_status">'.$txt['glossary_approve_keyword'].'</label>
				<input type="checkbox" name="new_valid" id="new_valid" ' . ($modSettings['glossary_approve_keyword_default'] ? ' checked' : '') . '/><br>' : '', '
				<input type="hidden" name="sc" value="', $context['session_id'], '" />
				<input type="hidden" name="submit_new_word" id="submit_new_word" value="" />
				</div>
			</form>
			<div class="buttons">
				<div onclick="$.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				<div onclick="SubmitNewWord(\'', $groups_enabled ? 'group_enabled' : '', '\')">'.$txt['glossary_new_word_button'].'</div>
			</div>
		</div>';

	// EDIT a word DIV
	echo '
		<div id="div_edit_word" style="display:none;">
			<a href="#" title="Close" class="modalCloseX simplemodal-close">x</a>
			<div class="header"><span>'.$txt['glossary_tip_edit'].'</span></div>
			<form method="post" name="edit_word_form" style="padding: 5px;">
				<div class="ModalContent">
				<label for="edit_word">'.$txt['glossary_new_word'].'* </label>
				<input type="text" name="edit_word" id="edit_word" value="',
					$context['glossary_action_status'] == 'edit' ? (empty($_POST['edit_word']) ? '' : $_POST['edit_word']) : ($context['glossary_action_status'] == 'check_new' ? (empty($_POST['new_word']) ? '' : $_POST['new_word']) : (empty($_POST['edit_word']) ? '' : $_POST['edit_word'])), '"><br>
				<label for="edit_definition"',
					$context['glossary_tooltip_bbc'] ? 'title="'.$txt['glossary_bbcodes_allowed'].'"' : '', '>'.$txt['glossary_new_definition'].' ', $context['glossary_tooltip_bbc'] ? $txt['glossary_bbcodes_parsed'] : '', '* : </label>
				<textarea name="edit_definition" id="edit_definition" cols="55" rows="5">', $context['glossary_action_status'] == 'edit' ? (empty($_POST['edit_definition']) ? '' : $_POST['edit_definition']) : ($context['glossary_action_status'] == 'check_new' ? (empty($_POST['new_definition']) ? '' : $_POST['new_definition']) : (empty($_POST['edit_definition']) ? '' : $_POST['edit_definition'])), '</textarea>';

				if ($groups_enabled)
				{
					echo '
					<label for="edit_group">'.$txt['glossary_group'].'* </label>
					<select name="edit_group" id="edit_group" value="">
						<option value="" selected="true" > --- '.$txt['glossary_group_none'].' --- </option>';

					foreach ($context['glossary_groups'] as $id=>$title)
						echo '
						<option value="'.$id.'"', $context['glossary_action_status'] == 'edit' ? (isset($_POST['edit_group']) && $_POST['edit_group'] == $id ? 'selected' : '') : ($context['glossary_action_status'] == 'check_new' ? ((isset($_POST['new_group']) && $_POST['new_group'] == $id) ? 'selected' : '') : ((isset($_POST['edit_group']) && $_POST['edit_group'] == $id) ? 'selected' : '')), '>'.$title.'</option>';

					echo '
					</select><br>';
				}

	// Synonyms of this keyword
	echo '
				<label for="edit_word_synonyms">'.$txt['glossary_synonyms'].' : </label>
				<input type="text" name="edit_word_synonyms" id="edit_word_synonyms" style="width:75%;" value="', $context['glossary_action_status'] == 'edit' ? (empty($_POST['edit_word_synonyms']) ? '' : $_POST['edit_word_synonyms']) : ($context['glossary_action_status'] == 'check_new' ? (empty($_POST['new_word_synonyms']) ? '' : $_POST['new_word_synonyms']) : (empty($_POST['edit_word_synonyms']) ? '' : $_POST['edit_word_synonyms'])), '" title="'.$txt['glossary_synonyms_tip'].'"><br>';

	echo '
				<label for="edit_show_in_message">'.$txt['glossary_show_tooltips'].'</label>
				<input type="checkbox" name="edit_show_in_message" id="edit_show_in_message" ', ($context['glossary_action_status'] == 'edit' && isset($_POST['edit_show_in_message'])) ? 'checked' : '', ' /><br>',
				(allowedTo('glossary_admin') && !empty($_POST['edit_valid'])) ? ('
				<label for="edit_valid">'.$txt['glossary_approve_keyword'].'</label>
				<input type="checkbox" name="edit_valid" id="edit_valid" checked /><br>') :
				(
				allowedTo('glossary_admin') ? ('
				<label for="edit_valid">'.$txt['glossary_approve_keyword'].'</label>
				<input type="checkbox" name="edit_valid" id="edit_valid" /><br>') :
				''
				), '
				<input type="hidden" name="edit_word_id" id="edit_word_id" size="47" value="', $context['glossary_action_status'] == 'edit' ? $_POST['edit_word_id'] : '', '">
				<input type="hidden" name="sc" value="', $context['session_id'], '" />
				<input type="hidden" name="submit_edit_word" id="submit_edit_word" value="" />
				<input type="hidden" name="is_author_of_word" id="is_author_of_word" value="" />
				</div>
			</form>
			<div class="buttons">
				<div onclick="javascript:if(document.getElementById(\'div_error_submit\')) document.getElementById(\'div_error_submit\').style.display=\'none\';$.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				<div onclick="SubmitEditWord(\'', $groups_enabled ? 'group_enabled' : '', '\')">'.$txt['glossary_edit_word_button'].'</div>
			</div>
		</div>';

	// DELETE a word DIV
	echo '
		<div id="div_delete_word" style="display:none;">
			<a href="#" title="Close" class="modalCloseX simplemodal-close">x</a>
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
			<a href="#" title="Close" class="modalCloseX simplemodal-close">x</a>
			<div class="header"><span>'.$txt['glossary_action_add_category'].'</span></div>
			<form method="post" name="manage_groups" style="padding: 5px;">
				<div class="ModalContent">
				<fieldset>
					<legend>'.$txt['glossary_add_new_group'].'</legend>
						<label for="manage_new_group">'.$txt['glossary_new_group_name'].' :</label>
						<input type="text" name="manage_new_group" id="manage_new_group" value="', $context['glossary_action_status'] == 'check_new_group' ? $_POST['manage_new_group'] : '', '" />
						<input type="button" class="button" onclick="AddNewGroup()" value="'.$txt['glossary_new_word_button'].'" style="float:right; margin-top:5px">
				</fieldset>
				<fieldset>',
					'<legend>'.$txt['glossary_modify_group'].'</legend>
						<label for="group_update">'.$txt['glossary_group'].' : </label>
						<select name="group_update" id="group_update">
							<option value="none"> --- '.$txt['glossary_group_none'].' --- </option>';

	foreach ($context['glossary_groups'] as $id=>$title)
		echo '
							<option value="'.$id.'"',($context['glossary_action_status'] == 'check_update_group' && !empty($_POST['group_update']))?'':'selected', '>'.$title.'</option>';

	echo '</select><br>
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
			<a href="#" title="Close" class="modalCloseX simplemodal-close">x</a>
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
			<a href="#" title="Close" class="modalCloseX simplemodal-close">x</a>
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
		<div id="div_tooltip_all" style="display:none;">
			<a href="#" title="Close" class="modalCloseX simplemodal-close">x</a>
			<div class="header"><span>'.$txt['glossary_action'].'</span></div>
			<div class="ModalContent">'.$txt['glossary_tooltip_all'].'</div>
			<div class="buttons">
				<div onclick="$.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				<div onclick="TooltipAll()">'.$txt['glossary_go_on'].'</div>
			</div>
		</div>';

	// UNTOOLTIP ALL DIV
	echo '
		<div id="div_untooltip_all" style="display:none;">
			<a href="#" title="Close" class="modalCloseX simplemodal-close">x</a>
			<div class="header"><span>'.$txt['glossary_action'].'</span></div>
			<div class="ModalContent">'.$txt['glossary_untooltip_all'].'</div>
			<div class="buttons">
				<div onclick="$.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				<div onclick="UntooltipAll()">'.$txt['glossary_go_on'].'</div>
			</div>
		</div>';

	// CHANGE GROUP SELECTION DIV
	echo '
		<div id="div_change_group_all" style="display:none;">
			<a href="#" title="Close" class="modalCloseX simplemodal-close">x</a>
			<div class="header"><span>'.$txt['glossary_action'].'</span></div>
			<div class="ModalContent">
				<label for="">'.$txt['glossary_change_group_all'].'</label><br>
				<select name="change_group_to_id" id="change_group_to_id">
					<option value="none"> --- '.$txt['glossary_group_none'].' --- </option>';

	foreach ($context['glossary_groups'] as $id=>$title)
		echo '
					<option value="'.$id.'">'.$title.'</option>';

	echo'
				</select>
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
	if (allowedTo('glossary_admin') && $modSettings['glossary_admin_context_menu'])
	{
		echo '
		<ul id="AdminContextMenu" class="contextMenu">
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_approve_all\')"><img src="'. $default_imagesGlossary . '/glossary_approved.png"> '. $txt['glossary_action_approve_all'] . '</a>
			</li>
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_unapprove_all\')"><img src="'. $default_imagesGlossary . '/glossary_unapproved.png"> '. $txt['glossary_action_unapprove_all'] . '</a>
			</li>
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_tooltip_all\')"><img src="'. $default_imagesGlossary . '/glossary_bubble_enable_all.png"> '. $txt['glossary_action_tooltip_all'] .'</a>
			</li>
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_untooltip_all\')"><img src="'. $default_imagesGlossary . '/glossary_bubble_disable_all.png"> '. $txt['glossary_action_untooltip_all'] . '</a>
			</li>';

		if ($groups_enabled)
		echo '
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_change_group_all\')"><img src="'. $default_imagesGlossary . '/glossary_change_group.png"> '. $txt['glossary_action_change_group_all'] . '</a>
			</li>
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_manage_groups\')"><img src="'. $default_imagesGlossary . '/glossary_category.png"> '. $txt['glossary_action_add_category'] . '</a>
			</li>';

		echo '
			<li>
			<a href="#" onclick="CheckboxSelect(\'select\')"><img src="'. $default_imagesGlossary . '/glossary_selectall.png"> '. $txt['glossary_action_select_all'] . '</a>
			</li>
			<li>
			<a href="#" onclick="CheckboxSelect(\'unselect\')"><img src="'. $default_imagesGlossary . '/glossary_unselectall.png"> '. $txt['glossary_action_unselect_all'] . '</a>
			</li>
			<li class="quit separator">
				<a href="#quit">'. $txt['glossary_new_word_close'] . '</a>
			</li>
		</ul>';

		echo '
		<script language="JavaScript" type="text/javascript" src="', $default_themeURL . '/scripts/glossary/glossary.jquery.contextMenu.js"></script>
		<script>jQuery(document).ready( function() { jQuery("#table_full_table").contextMenu({ menu: "AdminContextMenu" }); });</script>
		<link rel="stylesheet" type="text/css" href="'. $default_themeURL . '/css/glossary/glossary.jquery.contextMenu.css" />';
	}

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

	function EditWord(id,group_enabled,not_admin)
	{
		document.getElementById("edit_word").value=document.getElementById("word_"+id).innerHTML;
		document.getElementById("edit_definition").value=document.getElementById("definition_text_"+id).value;
		document.getElementById("edit_word_synonyms").value=document.getElementById("synonyms_"+id).value;

		if (document.getElementById("show_in_message_"+id).value == 1)
			document.getElementById("edit_show_in_message").checked = true;

		if (not_admin == "" && document.getElementById("valid_"+id).value == 1)
			document.getElementById("edit_valid").checked = true;

		if (group_enabled != "") {
			if (document.getElementById("group_id_"+id).value == 0)
				document.getElementById("edit_group").value="";
			else
				document.getElementById("edit_group").value=document.getElementById("group_id_"+id).value;
		}

		document.getElementById("edit_word_id").value=id;

		if (not_admin == "true")
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

	function UnApproveWord(id)
	{
		document.getElementById("id_word").value=id;
		document.getElementById("action_on_word").value="unapprove_word";
		document.form_action.submit();
	}

	function ApproveWord(id)
	{
		document.getElementById("id_word").value=id;
		document.getElementById("action_on_word").value="approve_word";
		document.form_action.submit();
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

	function TooltipAll()
	{
		// Get all selected
		var list_of_ids = "";
		var list = document.getElementById("full_list_of_ids").value.split(";");
		for (i = 1; i < list.length; i++)
			if (document.getElementById("glossary_cb_"+list[i]).checked == true)
				list_of_ids = list[i]+";"+list_of_ids;

		// Send them
		document.getElementById("list_of_ids").value=list_of_ids;
		document.getElementById("action_on_list").value="tooltip_selected";
		document.form_admin_action.submit();
	}

	function UntooltipAll() {
		// Get all selected
		var list_of_ids = "";
		var list = document.getElementById("full_list_of_ids").value.split(";");
		for (i = 1; i < list.length; i++)
			if (document.getElementById("glossary_cb_"+list[i]).checked == true)
				list_of_ids = list[i]+";"+list_of_ids;

		// Send them
		document.getElementById("list_of_ids").value=list_of_ids;
		document.getElementById("action_on_list").value="untooltip_selected";
		document.form_admin_action.submit();
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

	$(function() {
		$(".windowbg2 *").glossTooltip({delay: 0, track: false, showURL: false});
	});

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

	$("a#div_tooltip_all-show").click(function() {
		$("#div_tooltip_all").modal({
			close:false,
			overlayId:"ModalOverlay",
			containerId:"ModalContainer",
		});
	});

	$("a#div_untooltip_all-show").click(function() {
		$("#div_untooltip_all").modal({
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
