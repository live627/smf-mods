
	function Display_glossary_for_letter(letter){
		const myarray = glossary_letters.split(",");
		for (let i=0; i<(myarray.length-1); i++) {
			if ( letter == "all" )
				document.getElementById("letter_"+myarray[i]).style.display = "";
			else {
				if ( myarray[i] == letter )
					document.getElementById("letter_"+myarray[i]).style.display = "";
				else
					document.getElementById("letter_"+myarray[i]).style.display = "none";
			}
		}
	}
	
	function SubmitNewWord(group_enabled){
		if ( group_enabled == "" ) group_enabled = "nok";else group_enabled = "ok";
		if ( group_enabled == "ok" ){
			if ( document.getElementById("new_word").value != "" && document.getElementById("new_definition").value != "" ){
				document.getElementById("submit_new_word").value = "ok"
				document.new_word_form.submit();
			}else{
				alert(txt.glossary_alert_submit_new_word);
			}
		}else{
			if ( document.getElementById("new_word").value != "" && document.getElementById("new_definition").value != "" ){
				document.getElementById("submit_new_word").value = "ok"
				document.new_word_form.submit();
			}else{
				alert(txt.glossary_alert_submit_new_word);
			}
		}
	}
	
	function SubmitEditWord(group_enabled){
		if ( group_enabled == "" ) group_enabled = "nok";else group_enabled = "ok";
		if ( group_enabled == "ok" ){
			if ( document.getElementById("edit_word").value != "" && document.getElementById("edit_definition").value != "" ){
				document.getElementById("submit_edit_word").value = "ok"
				document.edit_word_form.submit();
			}else{
				alert(txt.glossary_alert_submit_new_word);
			}
		}else{
			if ( document.getElementById("edit_word").value != "" && document.getElementById("edit_definition").value != "" ){
				document.getElementById("submit_edit_word").value = "ok"
				document.edit_word_form.submit();
			}else{
				alert(txt.glossary_alert_submit_new_word);
			}
		}
	}

	function EditWord(id,group_enabled,not_admin){
		if ( group_enabled == "" ) group_enabled = "nok";else group_enabled = "ok";
		document.getElementById("edit_word").value=document.getElementById("word_"+id).innerHTML;
		document.getElementById("edit_definition").value=document.getElementById("definition_text_"+id).value;
		document.getElementById("edit_word_synonyms").value=document.getElementById("synonyms_"+id).value;
		if ( document.getElementById("show_in_message_"+id).value == 1 ) document.getElementById("edit_show_in_message").checked = true;
		if ( not_admin == "" && document.getElementById("valid_"+id).value == 1 ) document.getElementById("edit_valid").checked = true;
		if ( group_enabled == "ok" ){
			document.getElementById("edit_group").value=document.getElementById("group_id_"+id).value;
		}
		document.getElementById("edit_word_id").value=id;
		if ( not_admin == "true" ) document.getElementById("is_author_of_word").value="true";
		showPopup("div_edit_word");
	}
	
	function DeleteWord(id){
		document.getElementById("id_word_to_delete").value=id;
		showPopup("div_delete_word");
	}
	
	function AddNewGroup(){
		if ( document.getElementById("manage_new_group").value != "" ){
			document.manage_groups.submit();
			CleanGroupForm();
		}else{
			alert(txt.glossary_alert_new_group);
		}
	}
	
	function DeleteGroup(){
		if ( document.getElementById("group_update").value != "none" ){
			document.manage_groups.submit();
			CleanGroupForm();
		}else{
			alert(txt.glossary_alert_group_delete);
		}
	}
	
	function UpdateGroup(){
		if ( document.getElementById("group_update").value != "none" && document.getElementById("update_category_title").value != "" ){
			document.manage_groups.submit();
			CleanGroupForm();
		}else{
			alert(txt.glossary_alert_group_update);
		}
	}
	
	function EnableTooltipWord(id){
		document.getElementById("id_word").value=id;
		document.getElementById("action_on_word").value="enable_tooltip";
		document.form_action.submit();
	}
	
	function DisableTooltipWord(id){
		document.getElementById("id_word").value=id;
		document.getElementById("action_on_word").value="disable_tooltip";
		document.form_action.submit();
	}
	
	function UnApproveWord(id){
		document.getElementById("id_word").value=id;
		document.getElementById("action_on_word").value="unapprove_word";
		document.form_action.submit();
	}
	
	function ApproveWord(id){
		document.getElementById("id_word").value=id;
		document.getElementById("action_on_word").value="approve_word";
		document.form_action.submit();
	}
	
	function CleanGroupForm(){
		if (document.getElementById("div_group_update_error")) document.getElementById("div_group_update_error").style.display = "none";
		if (document.getElementById("div_group_new_error")) document.getElementById("div_group_new_error").style.display = "none";
		document.getElementById("manage_new_group").value = "";
		document.getElementById("update_category_title").value = "";
	}
	
	function TooltipAll(){
		//get all selected
		let list_of_ids = "";
		const list = document.getElementById("full_list_of_ids").value.split(";");
		for (i = 1; i < list.length; i++){
			if ( document.getElementById("glossary_cb_"+list[i]).checked == true ) {
				list_of_ids = list[i]+";"+list_of_ids;
			}
		}
		//send them
		document.getElementById("list_of_ids").value=list_of_ids;
		document.getElementById("action_on_list").value="tooltip_selected";
		document.form_admin_action.submit();
	}
	
	function UntooltipAll(){
		//get all selected
		let list_of_ids = "";
		const list = document.getElementById("full_list_of_ids").value.split(";");
		for (i = 1; i < list.length; i++){
			if ( document.getElementById("glossary_cb_"+list[i]).checked == true ) {
				list_of_ids = list[i]+";"+list_of_ids;
			}
		}
		//send them
		document.getElementById("list_of_ids").value=list_of_ids;
		document.getElementById("action_on_list").value="untooltip_selected";
		document.form_admin_action.submit();
	}
	
	function ApproveAll(){
		//get all selected
		let list_of_ids = "";
		const list = document.getElementById("full_list_of_ids").value.split(";");
		for (i = 1; i < list.length; i++){
			if ( document.getElementById("glossary_cb_"+list[i]).checked == true ) {
				list_of_ids = list[i]+";"+list_of_ids;
			}
		}
		//send them
		document.getElementById("list_of_ids").value=list_of_ids;
		document.getElementById("action_on_list").value="approve_selected";
		document.form_admin_action.submit();
	}
	
	function UnapproveAll(){
		//get all selected
		let list_of_ids = "";
		const list = document.getElementById("full_list_of_ids").value.split(";");
		for (i = 1; i < list.length; i++){
			if ( document.getElementById("glossary_cb_"+list[i]).checked == true ) {
				list_of_ids = list[i]+";"+list_of_ids;
			}
		}
		//send them
		document.getElementById("list_of_ids").value=list_of_ids;
		document.getElementById("action_on_list").value="unapprove_selected";
		document.form_admin_action.submit();
	}
	
	function ChangeGroupAll(){
		//get all selected
		let list_of_ids = "";
		const list = document.getElementById("full_list_of_ids").value.split(";");
		for (i = 1; i < list.length; i++){
			if ( document.getElementById("glossary_cb_"+list[i]).checked == true ) {
				list_of_ids = list[i]+";"+list_of_ids;
			}
		}
		//send them
		document.getElementById("list_of_ids").value=list_of_ids;
		document.getElementById("action_on_list").value="change_group_selected";
		document.getElementById("group_id").value=document.getElementById("change_group_to_id").value;
		document.form_admin_action.submit();
	}
	
	function CheckboxSelect(type){
		const list = document.getElementById("full_list_of_ids").value.split(";");
		for (i = 1; i < list.length; i++){
			if ( type == "select") document.getElementById("glossary_cb_"+list[i]).checked = true;
			if ( type == "unselect") document.getElementById("glossary_cb_"+list[i]).checked = false;
		}
	}

const cover = document.createElement('div');
cover.hidden = true;
document.body.append(cover);

function showPopup(div) {
	const el = document.getElementById(div);
	el.hidden = false;
	cover.hidden = false;
cover.className = 'modal-dialog';
	cover.prepend(el);
	el.lastElementChild.children[0].addEventListener('click', () => {
		el.hidden = true;
		cover.hidden = true;
cover.className = '';
	});
}

window.addEventListener("DOMContentLoaded", () => {
	const divs = [
		"div_new_word",
		"div_manage_groups",
		"div_approve_all",
		"div_unapprove_all",
		"div_tooltip_all",
		"div_untooltip_all",
		"div_change_group_all"
	];

	for (const div of divs)
		document.getElementById(div + "-show").addEventListener('click', showPopup.bind(null, div));

	document.getElementById("glossary_admin_menu_show").addEventListener('click', () => {
		const el = document.getElementById("glossary_admin_menu");
		el.hidden = !el.hidden;
		el.addEventListener('click', () => {
			el.hidden = true;
		});
	});
});
