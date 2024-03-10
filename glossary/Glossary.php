<?php

function Glossary() {
    global $db_prefix, $context, $txt, $smcFunc, $settings, $modSettings, $scripturl;
	
	//Define a by default width for column Definition
	if ( empty($modSettings['glossary_definition_width']) ) $modSettings['glossary_definition_width'] = "800";
    if ( empty($modSettings['glossary_word_width']) ) $modSettings['glossary_word_width'] = "100";
	
    // Get the template ready.... not really much else to do.
    loadLanguage('Glossary');
    loadTemplate('Glossary');
    $context['page_title'] = $txt['glossary'];
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=glossary',
		'name' => $txt['glossary'],
	);
			$context['allow_view_glossary'] = allowedTo('view_glossary');
			$context['allow_admin_glossary'] = allowedTo('admin_glossary');
			$context['allow_suggest_glossary'] = allowedTo('suggest_glossary');
	$context['glossary_new_group_error_submit'] = '';
	$context['glossary_update_group_error_submit'] ='';
    $context['glossary_error_submit_message'] = '';
	$context['glossary_action_status'] = '';
	$context['glossary_error_submit'] = '';
       
    //transmit bbccode strategy    
    if ( !empty($modSettings['enable_bbc_tooltip_glossary']) ) $context['enable_bbc_tooltip_glossary'] = $modSettings['enable_bbc_tooltip_glossary'];
    
	//NEW word
    if ( !empty($_POST['submit_new_word']) AND ( allowedTo('admin_glossary') == 1 OR allowedTo('suggest_glossary') == 1 ) ){
        //Security checks
        checkSession('post');
        	
		//Check if word doesn't exist
		$data_glossary = $smcFunc['db_query']('', '
			SELECT COUNT(*)
			FROM {db_prefix}glossary
			WHERE word = {string:new_word}',
			array(
			 'new_word' => addslashes(htmlspecialchars($_POST['new_word'],ENT_QUOTES)),
			)
		);
		$res = $smcFunc['db_fetch_row']($data_glossary);
		if ( $res[0] != 0 ) {
			//return error - word already exists
			$context['glossary_error_submit'] = true;
			$context['glossary_error_submit_message'] = $txt['glossary_submission_error_2'];
			$context['glossary_action_status'] = 'check_new';
		}else{
			//prepare the value for show_in_message
			if ( isset($_POST['new_show_in_message']) && $_POST['new_show_in_message'] == 'on' ) $show_in_message = 1; else $show_in_message = 0;
			//prepare the value for valid
			if ( isset($_POST['new_valid']) && $_POST['new_valid'] == 'on' ) $validword = 1; else $validword = 0;
			//store in database
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
					addslashes(htmlspecialchars($_POST['new_word'],ENT_QUOTES)), 
					addslashes(htmlspecialchars($_POST['new_definition'], ENT_QUOTES)), 
					(int)$context['user']['id'], 
					mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")),
					(int)$validword,
					addslashes(htmlspecialchars($_POST['new_word_synonyms'], ENT_QUOTES)),
					(int)$show_in_message,
					isset($_POST['new_group']) ? (int)$_POST['new_group'] : '0',
				),
				array()
			);
		}
    } 
    //UPDATE asked
    else if ( !empty($_POST['submit_edit_word']) AND ( allowedTo('admin_glossary') == 1 OR $_POST['is_author_of_word'] == "true" ) ){
        //Security checks
        checkSession('post');
        
		//prepare the value for show_in_message
		if ( isset($_POST['edit_show_in_message']) && $_POST['edit_show_in_message'] == 'on' ) $show_in_message = 1; else $show_in_message = 0;
		//prepare the value for valid
		if ( isset($_POST['edit_valid']) && $_POST['edit_valid'] == 'on' ) $validword = 1; else $validword = 0;
        
		//Check if a same word doesn't already exist
		$data_glossary = $smcFunc['db_query']('', '
			SELECT COUNT(*)
			FROM {db_prefix}glossary
			WHERE word = {string:word} AND id != {int:id} ',
			array(
				'id' => (int)$_POST['edit_word_id'],
				'word' => addslashes(htmlspecialchars($_POST['edit_word'],ENT_QUOTES)),
			)
		);
		$res = $smcFunc['db_fetch_row']($data_glossary);
		if ( $res[0] != 0 ) {
			//return error - word already exists
			$context['glossary_error_submit'] = true;
			$context['glossary_error_submit_message'] = $txt['glossary_submission_error_2'];
			$context['glossary_action_status'] = 'edit';
		}else{
			//update the word -  don't change the member's ID.
			if ( isset($_POST['is_author_of_word']) && $_POST['is_author_of_word'] == "true" ){
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}glossary
					SET
						definition = {string:definition},
						word = {string:word},
						date = {string:date},
						show_in_message = {string:show_in_message},
						group_id = {int:group_id},
						synonyms = {string:synonyms}
					WHERE id = {int:id}',
					array(
						'id' => (int)$_POST['edit_word_id'],
						'definition' => addslashes(htmlspecialchars($_POST['edit_definition'],ENT_QUOTES)),
						'date' => mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")),
						'word' => addslashes(htmlspecialchars($_POST['edit_word'],ENT_QUOTES)),
						'show_in_message' => (int)$show_in_message,
						'group_id' => isset($_POST['edit_group']) ? (int)$_POST['edit_group'] : '0',
						'synonyms' => addslashes(htmlspecialchars($_POST['edit_word_synonyms'],ENT_QUOTES)),
					)
				);
			}
			else 
			{
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}glossary
					SET
						definition = {string:definition},
						word = {string:word},
						date = {string:date},
						show_in_message = {string:show_in_message},
						valid = {int:valid},
						group_id = {int:group_id},
						synonyms = {string:synonyms}
					WHERE id = {int:id}',
					array(
						'id' => (int)$_POST['edit_word_id'],
						'definition' => addslashes(htmlspecialchars($_POST['edit_definition'],ENT_QUOTES)),
						'date' => mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")),
						'word' => addslashes(htmlspecialchars($_POST['edit_word'],ENT_QUOTES)),
						'show_in_message' => (int)$show_in_message,
						'valid' => (int)$validword,
						'group_id' => isset($_POST['edit_group']) ? (int)$_POST['edit_group'] : '0',
						'synonyms' => addslashes(htmlspecialchars($_POST['edit_word_synonyms'],ENT_QUOTES)),
					)
				);
			}
		}		
    }
    //DELETE a word
    else if ( !empty($_POST['submit_delete_word']) AND allowedTo('admin_glossary') == 1 AND !empty($_POST['id_word_to_delete']) ){
        //Security checks
        checkSession('post');
        
        $smcFunc['db_query']('', '
            DELETE FROM {db_prefix}glossary
            WHERE id = {int:id}',
            array(
                'id' => $_POST['id_word_to_delete'],
            )
        );
    }
    //APPROVE a word
    else if ( isset($_POST['action_on_word']) && $_POST['action_on_word'] == "approve_word" AND allowedTo('admin_glossary') == 1 AND !empty($_POST['id_word']) ){
        //Security checks
        checkSession('post');
        
        $smcFunc['db_query']('', '
            UPDATE {db_prefix}glossary
            SET
                valid = {int:valid}
            WHERE id = {int:id}',
            array(
                'valid' => '1',
                'id' => (int)$_POST['id_word'],
            )
        );
    }
    //UNAPPROVE a word
    else if ( isset($_POST['action_on_word']) && $_POST['action_on_word'] == "unapprove_word" AND allowedTo('admin_glossary') == 1 AND !empty($_POST['id_word']) ){
        //Security checks
        checkSession('post');
        
        $smcFunc['db_query']('', '
            UPDATE {db_prefix}glossary
            SET
                valid = {int:valid}
            WHERE id = {int:id}',
            array(
                'valid' => '0',
                'id' => (int)$_POST['id_word'],
            )
        );
    }
    //ENABLE TOOLTIP for a word
    else if ( isset($_POST['action_on_word']) && $_POST['action_on_word'] == "enable_tooltip" AND allowedTo('admin_glossary') == 1 AND !empty($_POST['id_word']) ){
        //Security checks
        checkSession('post');
        
        $smcFunc['db_query']('', '
            UPDATE {db_prefix}glossary
            SET
                show_in_message = {int:show_in_message}
            WHERE id = {int:id}',
            array(
                'show_in_message' => '1',
                'id' => (int)$_POST['id_word'],
            )
        );
    }
    //DISABLE TOOLTIP for a word
    else if ( isset($_POST['action_on_word']) && $_POST['action_on_word'] == "disable_tooltip" AND allowedTo('admin_glossary') == 1 AND !empty($_POST['id_word']) ){
        //Security checks
        checkSession('post');
        
        $smcFunc['db_query']('', '
            UPDATE {db_prefix}glossary
            SET
                show_in_message = {int:show_in_message}
            WHERE id = {int:id}',
            array(
                'show_in_message' => '0',
                'id' => (int)$_POST['id_word'],
            )
        );
    }
    //ENABLE TOOLTIP for a SELECTION
    else if ( isset($_POST['action_on_list']) && $_POST['action_on_list'] == "tooltip_selected" AND allowedTo('admin_glossary') == 1 AND !empty($_POST['list_of_ids']) ){
        //Security checks
        checkSession('post');
        
        $mylist = explode(";",$_POST['list_of_ids']);
        foreach($mylist as $newid){
            $smcFunc['db_query']('', '
                UPDATE {db_prefix}glossary
                SET
                    show_in_message = {int:show_in_message}
                WHERE id = {int:id}',
                array(
                    'show_in_message' => '1',
                    'id' => (int)$newid,
                )
            );
        }
    }
    //DISABLE TOOLTIP for a SELECTION
    else if ( isset($_POST['action_on_list']) && $_POST['action_on_list'] == "untooltip_selected" AND allowedTo('admin_glossary') == 1 AND !empty($_POST['list_of_ids']) ){
        //Security checks
        checkSession('post');
        
        $mylist = explode(";",$_POST['list_of_ids']);
        foreach($mylist as $newid){
            $smcFunc['db_query']('', '
                UPDATE {db_prefix}glossary
                SET
                    show_in_message = {int:show_in_message}
                WHERE id = {int:id}',
                array(
                    'show_in_message' => '0',
                    'id' => (int)$newid,
                )
            );
        }
    }
    //APPROVE a SELECTION
    else if ( isset($_POST['action_on_list']) && $_POST['action_on_list'] == "approve_selected" AND allowedTo('admin_glossary') == 1 AND !empty($_POST['list_of_ids']) ){
        //Security checks
        checkSession('post');
        
        $mylist = explode(";",$_POST['list_of_ids']);
        foreach($mylist as $newid){
            $smcFunc['db_query']('', '
                UPDATE {db_prefix}glossary
                SET
                    valid = {int:valid}
                WHERE id = {int:id}',
                array(
                    'valid' => '1',
                    'id' => (int)$newid,
                )
            );
        }
    }
    //UNAPPROVE a SELECTION
    else if ( isset($_POST['action_on_list']) && $_POST['action_on_list'] == "unapprove_selected" AND allowedTo('admin_glossary') == 1 AND !empty($_POST['list_of_ids']) ){
        //Security checks
        checkSession('post');
        
        $mylist = explode(";",$_POST['list_of_ids']);
        foreach($mylist as $newid){
            $smcFunc['db_query']('', '
                UPDATE {db_prefix}glossary
                SET
                    valid = {int:valid}
                WHERE id = {int:id}',
                array(
                    'valid' => '0',
                    'id' => (int)$newid,
                )
            );
        }
    }
    //CHANGE GROUP for SELECTION
    else if ( isset($_POST['action_on_list']) && $_POST['action_on_list'] == "change_group_selected" AND allowedTo('admin_glossary') == 1 AND !empty($_POST['list_of_ids']) AND !empty($_POST['group_id']) ){
        //Security checks
        checkSession('post');
        
        $mylist = explode(";",$_POST['list_of_ids']);
        foreach($mylist as $newid){
            $smcFunc['db_query']('', '
                UPDATE {db_prefix}glossary
                SET
                    group_id = {int:group_id}
                WHERE id = {int:id}',
                array(
                    'group_id' => (int)$_POST['group_id'],
                    'id' => (int)$newid,
                )
            );
        }
    }
	//ADD new GROUP
	else if ( !empty($_POST['manage_new_group']) AND allowedTo('admin_glossary') == 1 ){
		//Security checks
        checkSession('post');
		
		//Check if group doesn't exist
		$data_glossary = $smcFunc['db_query']('', '
			SELECT COUNT(*)
			FROM {db_prefix}glossary_groups
			WHERE title = {string:title}',
			array(
			 'title' => addslashes(htmlspecialchars($_POST['manage_new_group'], ENT_QUOTES)),
			)
		);
		$res = $smcFunc['db_fetch_row']($data_glossary);
		if ( $res[0] == 0 ) {
			//store in database
			$smcFunc['db_insert']('insert',
				'{db_prefix}glossary_groups',
				array(
					'title' => 'string-50', 
				),
				array(
					addslashes(htmlspecialchars($_POST['manage_new_group'], ENT_QUOTES)),
				),
				array()
			);
		}else{
			//fields are empty, return error
            $context['glossary_new_group_error_submit'] = true;
            $context['glossary_error_submit_message'] = $txt['glossary_submission_error_3'];
			$context['glossary_action_status'] = 'check_new_group';
		}
	}
	//DELETE a GROUP
	else if ( empty($_POST['update_category_title']) AND !empty($_POST['group_update']) AND allowedTo('admin_glossary') == 1 ){
		//Security checks
        checkSession('post');
		
		//delete from database
		$smcFunc['db_query']('', '
            DELETE FROM {db_prefix}glossary_groups
            WHERE id = {int:id}',
            array(
                'id' => (int)$_POST['group_update'],
            )
		);
	}
	//UPDATE a GROUP
	else if ( !empty($_POST['update_category_title']) AND !empty($_POST['group_update']) AND allowedTo('admin_glossary') == 1 ){
		//Security checks
        checkSession('post');
		
		//Check if group doesn't exist
		$data_glossary = $smcFunc['db_query']('', '
			SELECT COUNT(*)
			FROM {db_prefix}glossary_groups
			WHERE title = {string:title}',
			array(
			 'title' => addslashes(htmlspecialchars($_POST['update_category_title'], ENT_QUOTES)),
			)
		);
		$res = $smcFunc['db_fetch_row']($data_glossary);
		if ( $res[0] == 0 ) {
			//update in database
			$smcFunc['db_query']('', '
	            UPDATE {db_prefix}glossary_groups
	            SET
	                title = {string:title}
	            WHERE id = {int:id}',
	            array(
	                'title' => addslashes(htmlspecialchars($_POST['update_category_title'], ENT_QUOTES)),
	                'id' => (int)$_POST['group_update'],
	            )
	        );
		}else{
			//fields are empty, return error
            $context['glossary_update_group_error_submit'] = true;
            $context['glossary_error_submit_message'] = $txt['glossary_submission_error_3'];
			$context['glossary_action_status'] = 'check_update_group';
		}
	}
	
	//Build list of groups
	$context['glossary_groups'] = array();
	$data_groups = $smcFunc['db_query']('', '
		SELECT *
		FROM {db_prefix}glossary_groups
		ORDER BY title ASC',
		array()
	);
	while ($res = $smcFunc['db_fetch_row']($data_groups))
		$context['glossary_groups'][$res[0]] = $res[1];

    //init of some needed variables and arrays
    $full_words_list = '';        //contains the full listing of words to display
    $words_list = '';    //contains the words to display for one letter
    $ids_list = '';
    
    if ( isset($modSettings['enable_groups_in_glossary']) && $modSettings['enable_groups_in_glossary']==1 ) $group_enabled = 'ok';else $group_enabled = '';
    
	//==================================================================
    //Prepare glossary list    by ALPHABETIC ORDER
	if ( ( isset($_GET['sa']) && $_GET['sa'] == 'alphabetic') || !isset($_GET['sa']) ) {
	    $letter_in_progress = '';    //treated letter
	    $context['glossary_elements'] = '<table id="table_full_table">';    //contains what to display to the user
	    $context['glossary_letters'] =    '';    //String with letters used => sent to template for javascript stuff
	    $nb_words_for_letter_in_progress = 0;    //contains the number of words for treated letter or numeric
		$alphabet_list = array();    //array with existing letters
		
	    if ( allowedTo('admin_glossary') ) {
	        $data_glossary = $smcFunc['db_query']('', '
	            SELECT *
	            FROM {db_prefix}glossary 
	            ORDER BY word ASC',
	            array()
	        );
	    }else{
	        $data_glossary = $smcFunc['db_query']('', '
	            SELECT *
	            FROM {db_prefix}glossary
	            WHERE ( valid = 1 OR ( valid = 0 AND member_id = {int:member_id} ) )
	            ORDER BY word ASC',
	            array(
	             'member_id' => (int)$context['user']['id'],
	            )
	        );
	    }    
	    while ($res = $smcFunc['db_fetch_assoc']($data_glossary) ){
	        if ( (!empty($modSettings['enable_numeric_glossary']) && $modSettings['enable_numeric_glossary'] && is_numeric(substr($res ['word'],0,1))) || !is_numeric(substr($res ['word'],0,1)) ){  // only manage numeric if asked by admin
	            if ( strtoupper(substr($res ['word'],0,1)) != $letter_in_progress )
	            {
	                if ( $nb_words_for_letter_in_progress != 0 )
	                {
	                    //write the title and words
	                    $full_words_list .= '
	                        <tr id="letter_'.$letter_in_progress.'" style=""><td>
	                            <div class="letter_selection">&nbsp;'.$letter_in_progress.'</div>
	                            <table align="left">
	                                '.$words_list.'
	                            </table>
	                        </td></tr>';
	                    //Store the first letter => needed for building a dynamic alphabet list
	                    array_push($alphabet_list,$letter_in_progress);
	                    $context['glossary_letters']  .= $letter_in_progress.',';
	                }
	                $nb_words_for_letter_in_progress = 0;
	                $words_list = "";
	            }
	            //construct word and definition list
	            if ( allowedTo('admin_glossary') == 1 )
	                $words_list .= '
	                <tr>
	                    <td style="padding:3px;width:125px;" valign="top">'; 
				else
					$words_list .= '
					<tr>
						<td style="padding:3px;width:45px;" valign="top">'; 
	            //If admin glossary then add specific 'delete' and 'edit' icons
	            if ( allowedTo('admin_glossary') ) {
					$words_list .= '
                            <input type="checkbox" id="glossary_cb_'.$res['id'].'" title="'.$txt['glossary_tip_select'].'">
							<a href="javascript:EditWord(\''.$res['id'].'\',\''.$group_enabled.'\',\'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_edit'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_blue_edit.png"></a>
							<a style="cursor:pointer;" href="javascript:DeleteWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_delete'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_blue_delete.png"></a>';
					//Identify PENDING words
					if ( empty($res['valid']) || $res['valid'] == 0 )
						$words_list .= '
							<a href="javascript:ApproveWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_approve'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_approved.png"></a>';
					else
						$words_list .= '
							<a href="javascript:UnApproveWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_unapprove'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_unapproved.png"></a>';
					//Identify VISIBLE words
					if ( $res['show_in_message'] == 1 )
						$words_list .= '&nbsp;<a href="javascript:DisableTooltipWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_visible'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_visible.png"></a>';
					else
						$words_list .= '&nbsp;<a href="javascript:EnableTooltipWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_unvisible'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_unvisible.png"></a>';							
				}else{
					if ( $context['user']['id'] == $res['member_id'] && $res['valid'] == 0 )
						$words_list .= '<img src="'. $settings['default_theme_url']. '/images/glossary_approved.png">';
					else
						$words_list .= '<img src="'. $settings['default_theme_url']. '/images/glossary_blue.png">';
                    //Add button for editing if user is author of the word
                    if ( $context['user']['id'] == $res['member_id'] )
                        $words_list .= '
                            <a href="javascript:EditWord(\''.$res['id'].'\',\''.$group_enabled.'\',\'true\')" style="text-decoration:none;" title="'.$txt['glossary_tip_edit'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_blue_edit.png"></a>';
				}
                
                //If synonmys exist
                if ( !empty($res['synonyms']) ) 
                    $syn_list = '<div style="display:inline;">&nbsp;<img src="'. $settings['default_theme_url']. '/images/glossary_synonyms.png" title="'.addslashes($txt['glossary_synonyms']).' : '.$res ['synonyms'].'"></div>';
                else $syn_list = "";
	            
	            $words_list .= '
	                    </td>
	                    <td style="padding:3px; width:'.$modSettings['glossary_word_width'].'px;" valign="top"><b><div style="float:left;" id="word_'.$res['id'].'">'.$res ['word'].'</div></b>'.$syn_list.'</td>
	                    <td style="padding:3px; width:'.$modSettings['glossary_definition_width'].'px;" valign="top"><div style="float:left;" id="definition_'.$res['id'].'">';
	            if ( empty($modSettings['enable_bbc_tooltip_glossary']) )  $words_list .= $res['definition'];
	            else $words_list .= parse_bbc($res['definition']);
	            $words_list .= '</div>&nbsp;<div style="display:inline;">';
	            
	            //If word is pending then get the member's name (if admin) or inform the person you have made the suggestion
	            if ( empty($res['valid']) ) {
	                if ( allowedTo('admin_glossary') ) {
	                    $query_member = "SELECT member_name FROM ".$db_prefix."members WHERE id_member=".$res['member_id'];    //Don't show pending words to members
	                    $data_member = $smcFunc['db_query']('', $query_member,array());
	                    $res_member = $smcFunc['db_fetch_row']($data_member);
	                    $words_list .= ' [ <i>'.$txt['by'].' '.$res_member[0].'</i>]';
	                }else if ( $context['user']['id'] == $res['member_id'] ){
	                    $words_list .= ' [ <i>'.$txt['glossary_sugestion_you_made'].'</i>]';
	                }                    
	            }
	            
	            $words_list .= '
	                    </div>
						</td>
						<td width="10%" text-align="right">';
				
				if ( isset($context['glossary_groups'][$res['group_id']]) )
					$words_list .= '
							<u>'.$context['glossary_groups'][$res['group_id']].'</u>';
							
				$words_list .= '
	                    <input type="hidden" id="definition_text_'.$res['id'].'" value="'.$res ['definition'].'">
	                    <input type="hidden" id="show_in_message_'.$res['id'].'" value="'.$res ['show_in_message'].'">
	                    <input type="hidden" id="valid_'.$res['id'].'" value="'.$res ['valid'].'">
	                    <input type="hidden" id="group_id_'.$res['id'].'" value="'.$res ['group_id'].'">
	                    <input type="hidden" id="synonyms_'.$res['id'].'" value="'.$res ['synonyms'].'">
	                    </td>
	                </tr>';
	            
                //Get list of all ids
                $ids_list .= ';'.$res['id'];
                
	            //loop arguments
	            $letter_in_progress = strtoupper(substr($res ['word'],0,1  ));
	            $nb_words_for_letter_in_progress ++;
	        }
	    }
	    //Manage last entry
	    if ( $nb_words_for_letter_in_progress != 0 )
	    {
	        //write the title and words
	        $full_words_list .= '
	            <tr id="letter_'.$letter_in_progress.'" style=""><td>
	                <div class="letter_selection">&nbsp;'.$letter_in_progress.'</div>
	                <table align="left">
	                    '.$words_list.'
	                </table>
	             </td></tr>';
	        //Store the first letter => needed for building a dynamic alphabet list
	        array_push($alphabet_list,$letter_in_progress);
	        $context['glossary_letters']  .= $letter_in_progress.',';
	    }
	    //Free query results
	    $smcFunc['db_free_result']($data_glossary);
	    
	    //Prepare alphabet list
	    $context['glossary_elements'] .= '<tr><td colspan="3"><b><a href="javascript:Display_glossary_for_letter(\'all\')">'.$txt['glossary_all'].'</a></b> | ';
	    for ($i=ord("A");$i<=ord("Z");$i++)
	    {
	        if (!in_array(chr($i),$alphabet_list) )
	            $context['glossary_elements'] .= chr($i).' | ';
	        else
	            $context['glossary_elements'] .= '<b><a href="javascript:Display_glossary_for_letter(\''.chr($i).'\')">'.chr($i).'</a></b> | ';
	    }
	    //Manage also numerics if asked
	    if ( !empty($modSettings['enable_numeric_glossary']) && $modSettings['enable_numeric_glossary'] ){
	        for ($i=0;$i<10;$i++)
	        {
	            if (!in_array($i,$alphabet_list) )
	                $context['glossary_elements'] .= $i.' | ';
	            else
	                $context['glossary_elements'] .= '<b><a href="javascript:Display_glossary_for_letter(\''.$i.'\')">'.$i.'</a></b> | ';
	        }
	    }
	}
	//===============================================================================
	//Manage GROUP order
	else if ( isset($_GET['sa']) && $_GET['sa'] == 'categories') {
		$groups_list = array();
	    $context['glossary_letters'] =    '';    //String with letters used => sent to template for javascript stuff
		$words_list = "";
	    $context['glossary_elements'] = '<table><tr><td colspan="3"><b><a href="javascript:Display_glossary_for_letter(\'all\')">'.$txt['glossary_all'].'</a></b> | '; //Prepare groups list
		$last_group_id = 0;
		
		//Go through all groups
		$data_groups = $smcFunc['db_query']('', '
	            SELECT *
	            FROM {db_prefix}glossary_groups
	            ORDER BY title ASC',
	            array()
	        );	    
	    while ($res_groups = $smcFunc['db_fetch_assoc']($data_groups) ){
			//check if words are available for group
			if ( allowedTo('admin_glossary') ) {
		        $data_glossary = $smcFunc['db_query']('', '
		            SELECT COUNT(*)
		            FROM {db_prefix}glossary 
					WHERE ( group_id = {int:group_id} )
		            ORDER BY word ASC',
		            array(
						'group_id' => (int)$res_groups['id'],
					)
		        );
		    }else{
		        $data_glossary = $smcFunc['db_query']('', '
		            SELECT COUNT(*)
		            FROM {db_prefix}glossary
			        WHERE ( group_id = {int:group_id} AND ( valid = 1 OR ( valid = 0 AND member_id = {int:member_id} ) ) )
		            ORDER BY word ASC',
		            array(
						'member_id' => (int)$context['user']['id'],
						'group_id' => (int)$res_groups['id'],
		            )
		        );
		    }
			$res = $smcFunc['db_fetch_row']($data_glossary);
			if ( $res[0] > 0 ){
				//found list of words
				if ( allowedTo('admin_glossary') ) {
			        $data_glossary = $smcFunc['db_query']('', '
			            SELECT *
			            FROM {db_prefix}glossary 
						WHERE ( group_id = {int:group_id} )
			            ORDER BY word ASC',
			            array(
							'group_id' => (int)$res_groups['id'],
						)
			        );
			    }else{
			        $data_glossary = $smcFunc['db_query']('', '
			            SELECT *
			            FROM {db_prefix}glossary
			            WHERE ( group_id = {int:group_id} AND ( valid = 1 OR ( valid = 0 AND member_id = {int:member_id} ) ) )
			            ORDER BY word ASC',
			            array(
							'member_id' => (int)$context['user']['id'],
							'group_id' => (int)$res_groups['id'],
			            )
			        );
			    }
				while ($res = $smcFunc['db_fetch_assoc']($data_glossary) ){
					//Build list of words for this group
					if ( (!empty($modSettings['enable_numeric_glossary']) && $modSettings['enable_numeric_glossary'] && is_numeric(substr($res ['word'],0,1))) || !is_numeric(substr($res ['word'],0,1)) ){  // only manage numeric if asked by admin	            
			            //construct word and definition list
			            if ( allowedTo('admin_glossary') == 1 )
			                $words_list .= '
			                <tr>
			                    <td style="padding:3px;width:125px;" valign="top">'; 
			            else
			                $words_list .= '
			                <tr>
			                    <td style="padding:3px;width:45px;" valign="top">'; 
			            //If admin glossary then add specific 'delete' and 'edit' icons
			            if ( allowedTo('admin_glossary') ) {
							$words_list .= '
                                    <input type="checkbox" id="glossary_cb_'.$res['id'].'">
									<a href="javascript:EditWord(\''.$res['id'].'\',\''.$group_enabled.'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_edit'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_blue_edit.png"></a>
									<a style="cursor:pointer;" href="javascript:DeleteWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_delete'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_blue_delete.png"></a>';
							//Identify PENDING words
                            if ( empty($res['valid']) || $res['valid'] == 0 )
                                $words_list .= '
                                    <a href="javascript:ApproveWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_approve'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_approved.png"></a>';
                            else
                                $words_list .= '
                                    <a href="javascript:UnApproveWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_unapprove'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_unapproved.png"></a>';
                            //Identify VISIBLE words
                            if ( $res['show_in_message'] == 1 )
                                $words_list .= '&nbsp;<a href="javascript:DisableTooltipWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_visible'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_visible.png"></a>';
                            else
                                $words_list .= '&nbsp;<a href="javascript:EnableTooltipWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_unvisible'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_unvisible.png"></a>';                            
                        }else{
                            if ( $context['user']['id'] == $res['member_id'] && $res['valid'] == 0 )
                                $words_list .= '<img src="'. $settings['default_theme_url']. '/images/glossary_approved.png">';
                            else
                                $words_list .= '<img src="'. $settings['default_theme_url']. '/images/glossary_blue.png">';
                            //Add button for editing if user is author of the word
                            if ( $context['user']['id'] == $res['member_id'] )
                                $words_list .= '
                                    <a href="javascript:EditWord(\''.$res['id'].'\',\''.$group_enabled.'\',\'true\')" style="text-decoration:none;" title="'.$txt['glossary_tip_edit'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_blue_edit.png"></a>';
                        }
			            
			            //If synonmys exist
                        if ( !empty($res['synonyms']) ) 
							$syn_list = '<div style="display:inline;">&nbsp;<img src="'. $settings['default_theme_url']. '/images/glossary_synonyms.png" title="'.addslashes($txt['glossary_synonyms']).' : '.$res ['synonyms'].'"></div>';
						else $syn_list = "";
						
						$words_list .= '
								</td>
								<td style="padding:3px; width:'.$modSettings['glossary_word_width'].'px;" valign="top"><b><div style="float:left;" id="word_'.$res['id'].'">'.$res ['word'].'</div></b>'.$syn_list.'</td>             
			                    <td style="padding:3px; width:'.$modSettings['glossary_definition_width'].'px;" valign="top"><div style="float:left;" id="definition_'.$res['id'].'">';
			            if ( empty($modSettings['enable_bbc_tooltip_glossary']) )  $words_list .= $res['definition'];
			            else $words_list .= parse_bbc($res['definition']);
			            $words_list .= '</div>&nbsp;<div style="display:inline;">';
			            
			            //If word is pending then get the member's name (if admin) or inform the person you have made the suggestion
			            if ( empty($res['valid']) ) {
			                if ( allowedTo('admin_glossary') ) {
			                    $query_member = "SELECT member_name FROM ".$db_prefix."members WHERE id_member=".$res['member_id'];    //Don't show pending words to members
			                    $data_member = $smcFunc['db_query']('', $query_member,array());
			                    $res_member = $smcFunc['db_fetch_row']($data_member);
			                    $words_list .= ' [ <i>'.$txt['by'].' '.$res_member[0].'</i>]';
			                }else if ( $context['user']['id'] == $res['member_id'] ){
			                    $words_list .= ' [ <i>'.$txt['glossary_sugestion_you_made'].'</i>]';
			                }                    
			            }
			            
			            $words_list .= '
			                    </div>								
			                    <input type="hidden" id="definition_text_'.$res['id'].'" value="'.$res ['definition'].'">
			                    <input type="hidden" id="show_in_message_'.$res['id'].'" value="'.$res ['show_in_message'].'">
								<input type="hidden" id="valid_'.$res['id'].'" value="'.$res ['valid'].'">
			                    <input type="hidden" id="group_id_'.$res['id'].'" value="'.$res ['group_id'].'">
								<input type="hidden" id="synonyms_'.$res['id'].'" value="'.$res ['synonyms'].'">
			                    </td>
			                </tr>';
			            
					}		
				}
				//build new table
				$full_words_list .= '
				<tr id="letter_'.$res_groups['id'].'" style=""><td>
					<div class="letter_selection">&nbsp;'.$res_groups['title'].'</div>
					<table align="left">
						'.$words_list.'
					</table>
				</td></tr>';
				//Store the first letter => needed for building a dynamic alphabet list
		        $groups_list[$res_groups['id']] = $res_groups['title'];
		        $context['glossary_letters'] .= $res_groups['id'].',';
				$context['glossary_elements'] .= '<a href="javascript:Display_glossary_for_letter(\''.$res_groups['id'].'\')">'.$res_groups['title'].'</a></b> | ';
				$words_list = "";
			}else{
				//just add the group in list
				$context['glossary_elements'] .= $res_groups['title'].' | ';
			}
                
            //Get list of all ids
            $ids_list .= ';'.$res['id'];
		}
		
		//=======================================
		//Get a list of none categorized words
		if ( allowedTo('admin_glossary') ) {
			$data_glossary = $smcFunc['db_query']('', '
				SELECT *
				FROM {db_prefix}glossary 
				WHERE ( group_id = {int:group_id} )
				ORDER BY word ASC',
				array(
					'group_id' => 0,
				)
			);
		}else{
			$data_glossary = $smcFunc['db_query']('', '
				SELECT *
				FROM {db_prefix}glossary
				WHERE ( group_id = {int:group_id} AND ( valid = 1 OR ( valid = 0 AND member_id = {int:member_id} ) ) )
				ORDER BY word ASC',
				array(
					'member_id' => (int)$context['user']['id'],
					'group_id' => 0,
				)
			);
		}
		while ($res = $smcFunc['db_fetch_assoc']($data_glossary) ){
			//Build list of words for this group
			if ( (!empty($modSettings['enable_numeric_glossary']) && $modSettings['enable_numeric_glossary'] && is_numeric(substr($res ['word'],0,1))) || !is_numeric(substr($res ['word'],0,1)) ){  // only manage numeric if asked by admin	            
				//construct word and definition list
				if ( allowedTo('admin_glossary') == 1 )
					$words_list .= '
					<tr>
						<td style="padding:3px;width:125px;" valign="top">'; 
				else
					$words_list .= '
					<tr>
						<td style="padding:3px;width:45px;" valign="top">'; 
				//If admin glossary then add specific 'delete' and 'edit' icons
				if ( allowedTo('admin_glossary') ) {
					$words_list .= '
                            <input type="checkbox" id="glossary_cb_'.$res['id'].'">
							<a href="javascript:EditWord(\''.$res['id'].'\',\''.$group_enabled.'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_edit'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_blue_edit.png"></a>
							<a style="cursor:pointer;" href="javascript:DeleteWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_delete'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_blue_delete.png"></a>';
					//Identify PENDING words
					if ( empty($res['valid']) || $res['valid'] == 0 )
						$words_list .= '
							<a href="javascript:ApproveWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_approve'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_approved.png"></a>';
					else
						$words_list .= '
							<a href="javascript:UnApproveWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_unapprove'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_unapproved.png"></a>';
					//Identify VISIBLE words
					if ( $res['show_in_message'] == 1 )
						$words_list .= '&nbsp;<a href="javascript:DisableTooltipWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_visible'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_visible.png"></a>';
					else
						$words_list .= '&nbsp;<a href="javascript:EnableTooltipWord(\''.$res['id'].'\')" style="text-decoration:none;" title="'.$txt['glossary_tip_unvisible'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_unvisible.png"></a>';                            
				}else{
					if ( $context['user']['id'] == $res['member_id'] && $res['valid'] == 0 )
						$words_list .= '<img src="'. $settings['default_theme_url']. '/images/glossary_approved.png">';
					else
						$words_list .= '<img src="'. $settings['default_theme_url']. '/images/glossary_blue.png">';
					//Add button for editing if user is author of the word
					if ( $context['user']['id'] == $res['member_id'] )
						$words_list .= '
							<a href="javascript:EditWord(\''.$res['id'].'\',\''.$group_enabled.'\',\'true\')" style="text-decoration:none;" title="'.$txt['glossary_tip_edit'].'"><img src="'. $settings['default_theme_url']. '/images/glossary_blue_edit.png"></a>';
				}
				
				//If synonmys exist
                if ( !empty($res['synonyms']) ) 
                    $syn_list = '<div style="display:inline;">&nbsp;<img src="'. $settings['default_theme_url']. '/images/glossary_synonyms.png" title="'.addslashes($txt['glossary_synonyms']).' : '.$res ['synonyms'].'"></div>';
                else $syn_list = "";
	            
	            $words_list .= '
	                    </td>
	                    <td style="padding:3px; width:'.$modSettings['glossary_word_width'].'px;" valign="top"><b><div style="float:left;" id="word_'.$res['id'].'">'.$res ['word'].'</div></b>'.$syn_list.'</td>               
						<td style="padding:3px; width:'.$modSettings['glossary_definition_width'].'px;" valign="top"><div style="float:left;" id="definition_'.$res['id'].'">';
				if ( empty($modSettings['enable_bbc_tooltip_glossary']) )  $words_list .= $res['definition'];
				else $words_list .= parse_bbc($res['definition']);
				$words_list .= '</div>&nbsp;<div style="display:inline;">';
				
				//If word is pending then get the member's name (if admin) or inform the person you have made the suggestion
				if ( empty($res['valid']) ) {
					if ( allowedTo('admin_glossary') ) {
						$query_member = "SELECT member_name FROM ".$db_prefix."members WHERE id_member=".$res['member_id'];    //Don't show pending words to members
						$data_member = $smcFunc['db_query']('', $query_member,array());
						$res_member = $smcFunc['db_fetch_row']($data_member);
						$words_list .= ' [ <i>'.$txt['by'].' '.$res_member[0].'</i>]';
					}else if ( $context['user']['id'] == $res['member_id'] ){
						$words_list .= ' [ <i>'.$txt['glossary_sugestion_you_made'].'</i>]';
					}                    
				}
				
				$words_list .= '
						</div>								
						<input type="hidden" id="definition_text_'.$res['id'].'" value="'.$res ['definition'].'">
						<input type="hidden" id="show_in_message_'.$res['id'].'" value="'.$res ['show_in_message'].'">
						<input type="hidden" id="valid_'.$res['id'].'" value="'.$res ['valid'].'">
						<input type="hidden" id="group_id_'.$res['id'].'" value="'.$res ['group_id'].'">
						<input type="hidden" id="synonyms_'.$res['id'].'" value="'.$res ['synonyms'].'">
						</td>
					</tr>';				
			}
		}	
		//build new table
		if ( !empty($words_list) )
			$full_words_list .= '
			<tr id="letter_9999" style=""><td>
				<div class="letter_selection">&nbsp;'.$txt['glossary_not_categorized'].'</div>
				<table align="left">
					'.$words_list.'
				</table>
			</td></tr>';
		//Store the first letter => needed for building a dynamic alphabet list
		$groups_list[9999] = $txt['glossary_not_categorized'];
		$context['glossary_letters'] .= '9999,';
		$context['glossary_elements'] .= '<a href="javascript:Display_glossary_for_letter(\'9999\')">*</a></b> | ';
		$words_list = "";
	}
    
    //return the full glossary listing
    $context['glossary_elements'] .= '</td></tr>'.$full_words_list.'</table>';
    $context['glossary_elements'] .= '<input type="hidden" id="full_list_of_ids" value="'.$ids_list.'">';

}

?>
