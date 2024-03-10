<?php
// Glossary Mod
// by
// Slinouille

function template_main() {
	global $context, $modSettings, $scripturl, $txt, $settings, $user_info;
		
	echo '
	<script defer src="', $settings['default_theme_url'], '/scripts/glossary.js"></script>
	<link rel="stylesheet" href="'. $settings['default_theme_url']. '/css/glossary.css" />';
	
echo '
						<div class="cat_bar"> 
							<h3 class="catbg">
						', $txt['glossary'], ' ',
						!empty($modSettings['enable_groups_in_glossary']) ? '
						<small class="righttext">
							&nbsp;<a href="'. $scripturl. '?action=glossary&sa=alphabetic">'. $txt['glossary_by_alphabetic']. '</a>
							&nbsp;|&nbsp;<a href="'. $scripturl. '?action=glossary&sa=categories">'. $txt['glossary_by_groups']. '</a>
						</small>
						'
						:
						'', '
							</h3>
						</div>
						<span class="upperframe"><span></span></span>
						<div class="roundframe noup">';
					if ( isset($context['allow_suggest_glossary']) && $context['allow_suggest_glossary'] == 1 )
						echo ' 
					<a href="#" title="'.$txt['glossary_new_word_title'].'" id="div_new_word-show"><img src="'. $settings['default_theme_url']. '/images/glossary_blue_add.png"></a> '. $txt['glossary_action_add'];
					
					if ( isset($context['allow_admin_glossary']) && $context['allow_admin_glossary'] == 1 ) {
						echo '
					&nbsp;&nbsp;
					<a href="#" id="glossary_admin_menu_show"><img src="'. $settings['default_theme_url']. '/images/glossary_wand.png"> '.$txt['glossary_action_admin'].'</a>
					<div>
						<div id="glossary_admin_menu" hidden>';
							if ( isset($modSettings['enable_groups_in_glossary']) && $modSettings['enable_groups_in_glossary'] == 1 ) 
								echo '
							<a href="#" id="div_manage_groups-show">'. $txt['glossary_action_add_category'] . ' <img src="'. $settings['default_theme_url']. '/images/glossary_category.png"></a>
							<br />';
							echo '
							<a href="#" id="div_tooltip_all-show">'. $txt['glossary_action_tooltip_all'] .' <img src="'. $settings['default_theme_url']. '/images/glossary_bubble_enable_all.png"></a>
							<br />
							<a href="#" id="div_untooltip_all-show">'. $txt['glossary_action_untooltip_all'] . ' <img src="'. $settings['default_theme_url']. '/images/glossary_bubble_disable_all.png"></a>
							<br />
							<a href="#" id="div_approve_all-show">'. $txt['glossary_action_approve_all'] . ' <img src="'. $settings['default_theme_url']. '/images/glossary_unapproved.png"></a>
							<br />
							<a href="#" id="div_unapprove_all-show"> '. $txt['glossary_action_unapprove_all'] . ' <img src="'. $settings['default_theme_url']. '/images/glossary_approved.png"></a>
							<br />';
							if ( isset($modSettings['enable_groups_in_glossary']) && $modSettings['enable_groups_in_glossary'] == 1 ) 
								echo '
							<a href="#" id="div_change_group_all-show"> '. $txt['glossary_action_change_group_all'] . ' <img src="'. $settings['default_theme_url']. '/images/glossary_change_group.png"></a>
							<br />';
							echo '
							<a href="#" onclick="CheckboxSelect(\'select\')"> '. $txt['glossary_action_select_all'] . ' <img src="'. $settings['default_theme_url']. '/images/glossary_selectall.png"></a>
							<br />
							<a href="#" onclick="CheckboxSelect(\'unselect\')"> '. $txt['glossary_action_unselect_all'] . ' <img src="'. $settings['default_theme_url']. '/images/glossary_unselectall.png"></a>                            
						</div>
					</div>';
					}
					echo '
				<table style="margin : 10px auto auto auto; ">
					<tr>    
						<td>
							'.$context['glossary_elements'].'
						</td>
					</tr>
				</table>                    
			</div>';
	
	//ADD a new word DIV
	echo '
		<div class="windowbg" id="div_new_word" hidden>
			<a href="#" title="Close" class="modalCloseX simplemodal-close">x</a>
			<div class="title_bar"><span class="titlebg">'.$txt['glossary_new_word_title'].'</span></div>
			<form method="post" name="new_word_form" style="padding: 5px;">
				<div class="ModalContent">
				
				<label for="new_word">'.$txt['glossary_new_word'].'* </label>
				<input type="text" name="new_word" id="new_word" size="47" value="">
				
				<label for="new_definition">'.$txt['glossary_new_definition'].' ', empty($context['enable_bbc_tooltip_glossary']) ? '':$txt['glossary_bbccode_activated'], '* : </label>
				<textarea cols="55" rows="5" name="new_definition" id="new_definition"></textarea>
				<br />';
				if ( isset($modSettings['enable_groups_in_glossary']) && $modSettings['enable_groups_in_glossary'] == 1){
					echo '
					<label for="new_group">'.$txt['glossary_group'].'* </label>
					<select name="new_group" id="new_group" value="">
						<option value="">'.$txt['glossary_group_none'].'</option>';
					foreach ( $context['glossary_groups'] as $id=>$title)
						echo '<option value="'.$id.'">'.$title.'</option>';
					echo '</select>
					<br />';
				}
				//synonyms of this key word
				echo '
				<label for="new_word_synonyms">'.$txt['glossary_synonyms'].' : </label>
				<input type="text" name="new_word_synonyms" id="new_word_synonyms" style="width:75%;" value="" title="'.$txt['glossary_synonyms_tip'].'"><br />';
				
				echo 
				'<label for="new_word_show_in_message">'.$txt['glossary_show_in_message'].'</label>
				<input type="checkbox" name="new_show_in_message"', isset($modSettings['glossary_show_in_message_default']) ? ' checked' : '', '>
				<br />' , 
				(isset($context['allow_admin_glossary']) && $context['allow_admin_glossary'] == 1) ? '
				<label for="edit_publish_status">'.$txt['glossary_publish_status'].'</label>
				<input type="checkbox" name="new_valid" id="new_valid" >
				<br />' : '', '
				
				<input type="hidden" name="sc" value="', $context['session_id'], '" />
				<input type="hidden" name="submit_new_word" id="submit_new_word" value="" />
				
				</div>
			</form>
			<div class="righttext">
				<button class="button">'.$txt['glossary_new_word_close'].'</button>
				<div onclick="SubmitNewWord(\'', (isset($modSettings['enable_groups_in_glossary']) && $modSettings['enable_groups_in_glossary']==1) ? 'group_enabled' : '', '\')">'.$txt['glossary_new_word_button'].'</div>
			</div>
		</div>';
	
	//EDIT a word DIV
	echo '
		<div class="windowbg" id="div_edit_word" hidden>
			<a href="#" title="Close" class="modalCloseX simplemodal-close">x</a>
			<div class="title_bar"><span class="titlebg">'.$txt['glossary_tip_edit'].'</span></div>
			', $context['glossary_error_submit'] == true ? '<div style="color:red;font-weight:bold;align-text:center;margin-bottom:7px;" id="div_error_submit"><img src="'.$settings['default_theme_url'].'/images/error.png">&nbsp;'.$context['glossary_error_submit_message'].'</div>' : '', '
			<form method="post" name="edit_word_form" style="padding: 5px;">
				<div class="ModalContent">
				<label for="edit_word">'.$txt['glossary_new_word'].'* </label>
				<input type="text" name="edit_word" id="edit_word" value="', $context['glossary_action_status']=="edit" ? (empty($_POST['edit_word']) ? '' : $_POST['edit_word']) : ( $context['glossary_action_status']=="check_new" ? (empty($_POST['new_word']) ? '' : $_POST['new_word']) : (empty($_POST['edit_word']) ? '' : $_POST['edit_word'])), '">
				
				<label for="edit_definition">'.$txt['glossary_new_definition'].'
					', empty($context['enable_bbc_tooltip_glossary']) ? '':$txt['glossary_bbccode_activated'], '* : </label>
				<textarea cols="55" rows="5" name="edit_definition" id="edit_definition">', $context['glossary_action_status']=="edit" ? (empty($_POST['edit_definition']) ? '' : $_POST['edit_definition']) : ( $context['glossary_action_status']=="check_new" ? (empty($_POST['new_definition']) ? '' : $_POST['new_definition']) : (empty($_POST['edit_definition']) ? '' : $_POST['edit_definition'])), '</textarea>';
				
				if ( isset($modSettings['enable_groups_in_glossary']) && $modSettings['enable_groups_in_glossary']==1 ){
					echo '
					<label for="edit_group">'.$txt['glossary_group'].'* </label>
					<select name="edit_group" id="edit_group" value="">
						<option value="">'.$txt['glossary_group_none'].'</option>';
					foreach ( $context['glossary_groups'] as $id=>$title)
						echo '<option value="'.$id.'"',  $context['glossary_action_status']=="edit" ? ($_POST['edit_group']==$id ? 'selected' : '') : ( $context['glossary_action_status']=="check_new" ? ( (isset($_POST['new_group']) && $_POST['new_group']==$id) ? 'selected' : '') : ( (isset($_POST['edit_group']) && $_POST['edit_group']==$id) ? 'selected' : '')), '>'.$title.'</option>';
					echo '
					</select><br />';
				}
				
				//synonyms of this key word
				echo '
				<label for="edit_word_synonyms">'.$txt['glossary_synonyms'].' : </label>
				<input type="text" name="edit_word_synonyms" id="edit_word_synonyms" style="width:75%;" value="', $context['glossary_action_status']=="edit" ? (empty($_POST['edit_word_synonyms']) ? '' : $_POST['edit_word_synonyms']) : ( $context['glossary_action_status']=="check_new" ? (empty($_POST['new_word_synonyms']) ? '' : $_POST['new_word_synonyms']) : (empty($_POST['edit_word_synonyms']) ? '' : $_POST['edit_word_synonyms'])), '" title="'.$txt['glossary_synonyms_tip'].'"><br />';
				
				echo '
				<label for="edit_show_in_message">'.$txt['glossary_show_in_message'].'</label>
				<input type="checkbox" name="edit_show_in_message" id="edit_show_in_message" ', ($context['glossary_action_status']=="edit" AND isset($_POST['edit_show_in_message']) ) ? 'checked' : '', ' /><br />',
						
				($context['allow_admin_glossary'] AND !empty($_POST['edit_valid'])) ? ('
				<label for="edit_valid">'.$txt['glossary_publish_status'].'</label>
				<input type="checkbox" name="edit_valid" id="edit_valid" checked /><br />') :
				(
				$context['allow_admin_glossary'] ? ('
				<label for="edit_valid">'.$txt['glossary_publish_status'].'</label>
				<input type="checkbox" name="edit_valid" id="edit_valid" /><br />') :
				''
				), '
				
				<input type="hidden" name="edit_word_id" id="edit_word_id" size="47" value="', $context['glossary_action_status']=="edit" ? $_POST['edit_word_id'] : '', '">
				<input type="hidden" name="sc" value="', $context['session_id'], '" />
				<input type="hidden" name="submit_edit_word" id="submit_edit_word" value="" />
				<input type="hidden" name="is_author_of_word" id="is_author_of_word" value="" />
				</div>
			</form>
			<div class="righttext">
				<div onclick="javascript:if(document.getElementById(\'div_error_submit\')) document.getElementById(\'div_error_submit\').style.display=\'none\';JQ.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				<div onclick="SubmitEditWord(\'', ( isset($modSettings['enable_groups_in_glossary']) && $modSettings['enable_groups_in_glossary']==1) ? 'group_enabled' : '', '\')">'.$txt['glossary_edit_word_button'].'</div>
			</div>
		</div>';    
	
	//DELETE a word DIV
	echo '
		<div class="windowbg" id="div_delete_word" hidden>
			<a href="#" title="Close" class="modalCloseX simplemodal-close">x</a>
			<div class="title_bar"><span class="titlebg">'.$txt['glossary_tip_delete'].'</span></div>
			', $context['glossary_error_submit'] == true ? '<div style="color:red;font-weight:bold;align-text:center;margin-bottom:7px;" id="div_error_submit"><img src="'.$settings['default_theme_url'].'/images/error.png">&nbsp;'.$context['glossary_error_submit_message'].'</div>' : '', '
			<form method="post" style="padding: 5px;" name="form_delete_word">
				<div class="ModalContent">
					<input type="hidden" name="id_word_to_delete" id="id_word_to_delete" value="">
					'.$txt['glossary_confirm_deleting'].'
					<input type="hidden" name="sc" value="', $context['session_id'], '" />
					<input type="hidden" name="submit_delete_word" value="true" />
				</div>
			<div class="righttext">
				<button class="button">'.$txt['glossary_new_word_close'].'</button>
				<div onclick="document.form_delete_word.submit()">'.$txt['glossary_delete_word_button'].'</div>
			</div>
			</form>
		</div>';
	
	//MANAGE categories DIV
	echo '
		<div class="windowbg" id="div_manage_groups" hidden>
			<a href="#" title="Close" class="modalCloseX simplemodal-close">x</a>
			<div class="title_bar"><span class="titlebg">'.$txt['glossary_action_add_category'].'</span></div>
			', $context['glossary_new_group_error_submit'] == true ? '<div style="color:red;font-weight:bold;align-text:center;margin-bottom:7px;" id="div_group_new_error"><img src="'.$settings['default_theme_url'].'/images/error.png">&nbsp;'.$context['glossary_error_submit_message'].'</div>' : '',
					'
			<form method="post" name="manage_groups" style="padding: 5px;">
				<div class="ModalContent">
				<fieldset>
					<legend>'.$txt['glossary_add_new_group'].'</legend>
						<label for="manage_new_group">'.$txt['glossary_new_group_name'].' :</label> 
						<input type="text" name="manage_new_group" id="manage_new_group" value="', $context['glossary_action_status'] == 'check_new_group' ? $_POST['manage_new_group'] : '', '" />
						&nbsp;&nbsp;
						<input type="button" onclick="AddNewGroup()" value="'.$txt['glossary_new_word_button'].'">
				</fieldset>
				
				<fieldset>
					', $context['glossary_update_group_error_submit'] == true ? '<div style="color:red;font-weight:bold;align-text:center;margin-bottom:7px;" id="div_group_update_error"><img src="'.$settings['default_theme_url'].'/images/error.png">&nbsp;'.$context['glossary_error_submit_message'].'</div>' : '',
					'<legend>'.$txt['glossary_modify_group'].'</legend>
						<label for="group_update">'.$txt['glossary_group'].' : </label>
						<select name="group_update" id="group_update">
							<option value="none">'.$txt['glossary_group_none'].'</option>';
						foreach ( $context['glossary_groups'] as $id=>$title)
							echo '<option value="'.$id.'"',($context['glossary_action_status'] == 'check_update_group' AND !empty($_POST['group_update']))?'':'selected' , '>'.$title.'</option>';
						echo '</select>
						<br />
						'.$txt['glossary_update_group_title'].' : 
						<input type="text" name="update_category_title" id="update_category_title"  value="', $context['glossary_action_status'] == 'check_update_group' ? $_POST['update_category_title'] : '', '" />
						<br />
						<input type="button" onclick="DeleteGroup()" value="'.$txt['glossary_delete_group_button'].'">
						<input type="button" onclick="UpdateGroup()" value="'.$txt['glossary_update_group_button'].'">
				</fieldset>
				<input type="hidden" name="sc" value="', $context['session_id'], '" />
				</div>
				<div class="righttext">
					<div onclick="javascript:CleanGroupForm();JQ.modal.close();">'.$txt['glossary_new_word_close'].'</div>
				</div>
			</form>
		</div>';
		
		//APPROVE ALL DIV
		echo '
		<div class="windowbg" id="div_approve_all" hidden>
			<div class="title_bar"><span class="titlebg">'.$txt['glossary_action'].'</span></div>
			<div class="ModalContent">
				'.$txt['glossary_approve_all'].'
			</div>
			<div class="righttext">
				<button class="button">'.$txt['glossary_new_word_close'].'</button>
				<div onclick="ApproveAll()">'.$txt['glossary_go_on'].'</div>
			</div>
			</form>
		</div>';
		
		//UNAPPROVE ALL DIV
		echo '
		<div class="windowbg" id="div_unapprove_all" hidden>
			<div class="title_bar"><span class="titlebg">'.$txt['glossary_action'].'</span></div>
			<div class="ModalContent">
				'.$txt['glossary_unapprove_all'].'
			</div>
			<div class="righttext">
				<button class="button">'.$txt['glossary_new_word_close'].'</button>
				<div onclick="UnapproveAll()">'.$txt['glossary_go_on'].'</div>
			</div>
			</form>
		</div>';
		
		//TOOLTIP ALL DIV
		echo '
		<div class="windowbg" id="div_tooltip_all" hidden>
			<div class="title_bar"><span class="titlebg">'.$txt['glossary_action'].'</span></div>
			<div class="ModalContent">
				'.$txt['glossary_tooltip_all'].'
			</div>
			<div class="righttext">
				<button class="button">'.$txt['glossary_new_word_close'].'</button>
				<div onclick="TooltipAll()">'.$txt['glossary_go_on'].'</div>
			</div>
		</div>';
		
		//UNTOOLTIP ALL DIV
		echo '
		<div class="windowbg" id="div_untooltip_all" hidden>
			<div class="title_bar"><span class="titlebg">'.$txt['glossary_action'].'</span></div>
			<div class="ModalContent">
				'.$txt['glossary_untooltip_all'].'
			</div>
			<div class="righttext">
				<button class="button">'.$txt['glossary_new_word_close'].'</button>
				<div onclick="UntooltipAll()">'.$txt['glossary_go_on'].'</div>
			</div>
		</div>';
		
		//CHANGE GROUP SELECTION DIV
		echo '
		<div class="windowbg" id="div_change_group_all" hidden>
			<div class="title_bar"><span class="titlebg">'.$txt['glossary_action'].'</span></div>
			<div class="ModalContent">
				<label for="">'.$txt['glossary_change_group_all'].'</label>
				<select name="change_group_to_id" id="change_group_to_id">
					<option value="none">'.$txt['glossary_group_none'].' : </option>';
				foreach ( $context['glossary_groups'] as $id=>$title)
					echo '<option value="'.$id.'">'.$title.'</option>';
				echo '</select>
			</div>
			<div class="righttext">
				<button class="button">'.$txt['glossary_new_word_close'].'</button>
				<div onclick="ChangeGroupAll()">'.$txt['glossary_go_on'].'</div>
			</div>
		</div>';
		
		
	echo '
	</table>';
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
	
	//Open DIV error returned        
	if ( $context['glossary_error_submit'] == true ){
		echo '<script>
		imgLoader = new Image();// preload image
		imgLoader.src = "images/loadingAnimation.gif";
		tb_show("'.$txt['glossary_edit_word_title'].'","#TB_inline?height=300&width=400&inlineId=div_edit_word&modal=false",false);
		</script>';
	}else if ( $context['glossary_new_group_error_submit'] == true || $context['glossary_update_group_error_submit'] == true ){
		echo '<script>
		imgLoader = new Image();// preload image
		imgLoader.src = "images/loadingAnimation.gif";
		tb_show("'.$txt['glossary_edit_word_title'].'","#TB_inline?height=280&width=400&inlineId=div_manage_groups&modal=true",false);
		</script>';
	}
	
	//CONTEXTUAL MENU
	if ( isset($modSettings['glossary_admin_context_menu']) && $modSettings['glossary_admin_context_menu'] == 1 &&  isset($context['allow_view_glossary']) && !empty($modSettings['enable_glossary_mod']) ){
		echo '
		<ul id="AdminContextMenu" class="contextMenu">
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_manage_groups\')"><img src="'. $settings['default_theme_url']. '/images/glossary_category.png"> '. $txt['glossary_action_add_category'] . '</a>
			</li>
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_tooltip_all\')"><img src="'. $settings['default_theme_url']. '/images/glossary_bubble_enable_all.png"> '. $txt['glossary_action_tooltip_all'] .'</a>
			</li>
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_untooltip_all\')"><img src="'. $settings['default_theme_url']. '/images/glossary_bubble_disable_all.png"> '. $txt['glossary_action_untooltip_all'] . '</a>
			</li>
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_approve_all\')"><img src="'. $settings['default_theme_url']. '/images/glossary_unapproved.png"> '. $txt['glossary_action_approve_all'] . '</a>
			</li>
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_unapprove_all\')"><img src="'. $settings['default_theme_url']. '/images/glossary_approved.png"> '. $txt['glossary_action_unapprove_all'] . '</a>
			</li>
			<li>
			<a href="#" onclick="ActionContextMenu(\'div_change_group_all\')"><img src="'. $settings['default_theme_url']. '/images/glossary_change_group.png"> '. $txt['glossary_action_change_group_all'] . '</a>
			</li>
			<li>
			<a href="#" onclick="CheckboxSelect(\'select\')"><img src="'. $settings['default_theme_url']. '/images/glossary_selectall.png"> '. $txt['glossary_action_select_all'] . '</a>
			</li>
			<li>
			<a href="#" onclick="CheckboxSelect(\'unselect\')"><img src="'. $settings['default_theme_url']. '/images/glossary_unselectall.png"> '. $txt['glossary_action_unselect_all'] . '</a>
			</li>
			<li class="quit separator">
				<a href="#quit">'. $txt['glossary_new_word_close'] . '</a>
			</li>
		</ul>';
	}
			
	echo '<script>
		var glossary_letters = "', $context['glossary_letters'] ?? '' ,'";
		var txt ={
		glossary_alert_submit_new_word:"'.$txt['glossary_alert_submit_new_word'].'",
		glossary_alert_new_group:"'.$txt['glossary_alert_new_group'].'",
		glossary_alert_group_delete:"'.$txt['glossary_alert_group_delete'].'",
	};
	</script>';
}

?>
