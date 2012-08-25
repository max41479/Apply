<?php
/**
* This acp manages Guild Applications 
* Application form created by Kapli (bbDKP developer)
*
* @package bbDkp.acp
* @author Kapli
* @copyright (c) 2009 bbdkp http://code.google.com/p/bbdkp/
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 1.3.6
* 
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}
if (! defined('EMED_BBDKP')) 
{
    trigger_error($user->lang['BBDKPDISABLED'], E_USER_WARNING);
}

class acp_dkp_apply extends bbDkp_Admin
{
   public $u_action;
   
   function main($id, $mode)
   {
      global $db, $user, $template, $cache;
      global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx; 
      
      $user->add_lang(array('common'));
	  $user->add_lang(array('mods/dkp_admin'));
	  $user->add_lang(array('mods/dkp_common'));
	  $user->add_lang(array('mods/apply'));
	  
	  $template_id_hidden = request_var('template_id_hidden', 0); 
	  $form_key = 'dkp_apply';
	  add_form_key($form_key);
	  
	  switch($mode)
      {
       		/***
             * APPLICATIONCONFIG
             * 
             */
            case 'apply_settings' :
                $link = '<br /><a href="' . append_sid("index.$phpEx", "i=dkp_apply&amp;mode=apply_settings") . '">' . $user->lang['APPLY_ACP_RETURN'] . '</a>';
                $appformsettings = (isset($_POST['appformsettings'])) ? true : false;

                $apptemplatedelete = (isset($_GET['apptemplatedelete'])) ? true : false;
				$apptemplateadd = (isset($_POST['apptemplateadd'])) ? true : false;
				
				$appquestionmove_up = (isset($_GET['appquestionmove_up'])) ? true : false;
				$appquestionmove_down = (isset($_GET['appquestionmove_down'])) ? true : false;
				$appquestiondelete = (isset($_GET['appquestiondelete'])) ? true : false;  

				$appformquestionadd = (isset($_POST['appformquestionadd'])) ? true : false;
                $appformquestionupdate = (isset($_POST['appformquestionupdate'])) ? true : false;
				
				$colorsettings = (isset($_POST['updatecolor'])) ? true : false;
				
				/*
                 * appform settings
                 */
               if($appformsettings)
               {
               		if (!check_form_key($form_key))
					{
						trigger_error($user->lang['FORM_INVALID'] . adm_back_link($this->u_action), E_USER_WARNING);
					}
					
                    if (!isset($_POST['realm']) or request_var('realm', '') == '') 
                    {
                        trigger_error( $user->lang['APPLY_ACP_REALMBLANKWARN'] . adm_back_link($this->u_action), E_USER_WARNING);
                    }
                    
               		$welcometext = utf8_normalize_nfc(request_var('welcome_message', '', true));
               		
					$uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
					$allow_bbcode = $allow_urls = $allow_smilies = true;
					generate_text_for_storage($welcometext, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
					$sql = 'UPDATE ' . APPHEADER_TABLE . " SET 
							announcement_msg = '" . (string) $db->sql_escape($welcometext) . "' , 
							announcement_timestamp = ".  (int) time() ." , 
							bbcode_bitfield = 	'".  (string) $bitfield ."' , 
							bbcode_uid = 		'".  (string) $uid ."'  
							WHERE announcement_id = 1";
					$db->sql_query($sql);
					
                    set_config('bbdkp_apply_guests', request_var('guests', ''), true );	
                    set_config('bbdkp_apply_realm', utf8_normalize_nfc(str_replace(" ", "+", request_var('realm','', true)))  , true );	
                    set_config('bbdkp_apply_region', request_var('region', ''), true );	
					set_config('bbdkp_apply_gchoice', request_var('guild_choice', ''), true );	
                    $cache->destroy('config');
                    trigger_error($user->lang['APPLY_ACP_SETTINGSAVED'] . $link);
               }
               
               
               if($apptemplatedelete)
               {
               	
					if (confirm_box ( true )) 
					{
               			$hiddentemplateid = request_var('hidden_template_id', 0);
               		
	               		//delete template
			            $db->sql_query("DELETE FROM " . APPTEMPLATELIST_TABLE . " WHERE template_id = '" . $hiddentemplateid . "'");
			            $db->sql_query("DELETE FROM " . APPTEMPLATE_TABLE . " WHERE template_id = '" . $hiddentemplateid . "'");
			            trigger_error("Template " . $hiddentemplateid . " deleted", E_USER_WARNING);
					}
					else 
					{
						$s_hidden_fields = build_hidden_fields ( array (
							'apptemplatedelete' 		=> true, 
							'hidden_template_id'		=> request_var('template_id', 0),
							));
						
						$template->assign_vars ( array ('S_HIDDEN_FIELDS' => $s_hidden_fields ) );
						confirm_box ( false, $user->lang ['CONFIRM_DELETE_TEMPLATE'], $s_hidden_fields );
					}
               }
               
               if($apptemplateadd)
               {
                    $sql_ary = array(
                        'status'     	=> 1, 
    				 	'template_name' => utf8_normalize_nfc (request_var('template_name', ' ', true )),
                    	'forum_id'   	=> request_var('new_applyforum_id', 0)
                    );
                    
                    // insert new question
                    $sql = 'INSERT INTO ' . APPTEMPLATELIST_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
                    $db->sql_query($sql);
                    
                    trigger_error( $user->lang['APPLY_ACP_TEMPLATEADD']  .  $link, E_USER_NOTICE);
               }
               
		       /**
		        * template questions
		        */
                if ($appformquestionadd) 
                {
					if (!check_form_key($form_key))
					{
						trigger_error($user->lang['FORM_INVALID'] . adm_back_link($this->u_action), E_USER_WARNING);
					}
					
                    $sql = 'SELECT max(qorder) + 1 as maxorder, lineid + 1 as maxline_id 
                     	FROM ' . APPTEMPLATE_TABLE . ' WHERE template_id= ' . $template_id_hidden; 
					$result = $db->sql_query($sql);
					$max_order = (int) $db->sql_fetchfield('maxorder', 0, $result);
					$maxline_id = (int) $db->sql_fetchfield('maxline_id', 0, $result);
					
					$db->sql_freeresult($result);
					
                    $sql_ary = array(
                        'qorder'     	=> $max_order,
                    	'template_id'	=> $template_id_hidden, 
                    	'lineid'		=> $maxline_id,  
    				 	'header'   		=> utf8_normalize_nfc (request_var('app_add_title', ' ', true )),
                    	'question'   	=> utf8_normalize_nfc (request_var('app_add_question', ' ', true )),
                        'options'   	=> utf8_normalize_nfc (request_var('app_add_options', ' ', true )),                    
                        'type'       	=> utf8_normalize_nfc (request_var('app_add_type', ' ', true )),
                        'mandatory' 	=> (isset($_POST['app_add_mandatory']) ? 'True': 'False')
                    );
                    
                    // insert new question
                    $sql = 'INSERT INTO ' . APPTEMPLATE_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
                    $db->sql_query($sql);
                    
                    trigger_error( $user->lang['APPLY_ACP_QUESTNADD']  .  $link, E_USER_NOTICE);
                    
                }
               
               $qid = request_var('id', 0);
      		   //user pressed question order arrows
               if ($appquestionmove_down or $appquestionmove_up)
				{
					//find order of clicked line
					$sql = 'SELECT qorder FROM ' . APPTEMPLATE_TABLE . ' where id =  ' . $qid; 
					$result = $db->sql_query($sql);
					$current_order = (int) $db->sql_fetchfield('qorder', 0, $result);
					$db->sql_freeresult($result);
	
					if ($appquestionmove_down)
					{
						$new_order = $current_order + 1; 
					}
					elseif($appquestionmove_up) 
					{
						$new_order = $current_order - 1;
					}
	
					// find current id with new order and move that one notch, if any
					$sql = 'UPDATE  ' . APPTEMPLATE_TABLE . ' SET qorder = ' . $current_order . ' WHERE qorder = ' . $new_order;
					$db->sql_query($sql);
					
					// now increase old order
					$sql = 'UPDATE  ' . APPTEMPLATE_TABLE . ' set qorder = ' . $new_order . ' where id = ' . $qid; 
					$db->sql_query($sql);
					
					meta_refresh(1, $this->u_action);			
				}

                      
               if($appquestiondelete)
               {
					//delete question handler
					$sql = "DELETE FROM " . APPTEMPLATE_TABLE . " WHERE id = '" . $qid . "'";
		            $db->sql_query($sql);
		            trigger_error("Question " . $qid . " deleted" . $link, E_USER_WARNING);
               }
               				
				//user pressed update questions
				if ($appformquestionupdate) 
                {
					if (!check_form_key($form_key))
					{
						trigger_error($user->lang['FORM_INVALID'] . adm_back_link($this->u_action), E_USER_WARNING);
					}        
                	
					$q_types = utf8_normalize_nfc(request_var('q_type', array( 0 => ''), true));   
					$q_headers = utf8_normalize_nfc(request_var('q_header', array( 0 => ''), true));
					$q_questions = utf8_normalize_nfc(request_var('q_question', array( 0 => ''), true));
					$q_options = utf8_normalize_nfc(request_var('q_options', array( 0 => ''), true));

					foreach ($q_questions as $key => $arrvalues) 
					{

						$data = array(
							'mandatory' => isset ( $_POST ['q_mandatory'][$key] ) ? 'True' : 'False',		
						);
						$sql = 'UPDATE ' . APPTEMPLATE_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $data) . ' WHERE id = '. $key ;
						$db->sql_query($sql);
						
	                   /* updating questions */
						$data = array(
								'type' 		=> $q_types[$key],
								'header' 	=> $q_headers[$key],
								'question' 	=> $q_questions[$key],
								'options' 	=> $q_options[$key],
						);
						
						$sql = 'UPDATE ' . APPTEMPLATE_TABLE . ' set ' . $db->sql_build_array('UPDATE', $data) . ' WHERE id = ' . $key;								
						$db->sql_query($sql);	
							
					}
					
                    trigger_error( $user->lang['APPLY_ACP_QUESTUPD']  . $link);    
                }
                
                
         		/*
                 * color settings handler
                 */
               if($colorsettings)
               {
					if (!check_form_key($form_key))
					{
						trigger_error($user->lang['FORM_INVALID'] . adm_back_link($this->u_action), E_USER_WARNING);
					}        
					$colorid = request_var('app_textcolors', ''); 
					$newcolor = request_var('applyquestioncolor', '');
					switch 	($colorid)
					{
						case 'postqcolor':
		               		set_config('bbdkp_apply_pqcolor', $newcolor, true );	
							break;
						case 'postacolor':
		               		set_config('bbdkp_apply_pacolor', $newcolor, true );	
		               		break;
						case 'formqcolor':
		               		set_config('bbdkp_apply_fqcolor', $newcolor, true );	
							break;
					}
                    $cache->destroy('config');
               }
                
                /*
				 * loading config
				 */
				// get welcome msg
				$sql = 'SELECT announcement_msg, bbcode_bitfield, bbcode_uid FROM ' . APPHEADER_TABLE;
				$result = $db->sql_query($sql);
				while ( $row = $db->sql_fetchrow($result) )
				{
					$text = $row['announcement_msg'];
					$bitfield = $row['bbcode_bitfield'];
					$uid = $row['bbcode_uid'];
				}
				$db->sql_freeresult($result);
				
				$textarr = generate_text_for_edit($text, $uid, $bitfield, 7);
				$applytemplate_id = request_var('applytemplate_id', 0); 
				$result = $db->sql_query('SELECT * FROM ' . APPTEMPLATELIST_TABLE);
				while ( $row = $db->sql_fetchrow($result) )
				{
					 $template->assign_block_vars('apptemplatelist', array(
						'ID'					=> $row['template_id'],
						'STATUS'				=> $row['status'],
					 	'TEMPLATE_NAME'			=> $row['template_name'],
					 	'FORUMID'				=> $row['forum_id'],
					 	'SELECTED'				=> ($applytemplate_id == $row['template_id']) ? ' selected = "selected"' : '', 
						'FORUM_OPTIONS' 		=> make_forum_select($row['forum_id'],false, false, true),
					 	'U_DELETE_TEMPLATE'		=> append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_apply&amp;mode=apply_settings&amp;apptemplatedelete=1&amp;template_id={$row['template_id']}"),
					 ));
				}
				$db->sql_freeresult($result);
				
			    $template->assign_vars(array(
			    	'TEMPLATE_ID'			=> $applytemplate_id, 
			    	'ADDTEMPLATEFORUM_OPTIONS' 	=> make_forum_select(0,false, false, true),
                	'WELCOME_MESSAGE' 		=> $textarr['text'],
                	'REALM'        			=> str_replace("+", " ", $config['bbdkp_apply_realm']), 
                	'PUBLIC_YES_CHECKED' 	=> ( $config['bbdkp_apply_visibilitypref'] == '1' ) ? ' checked="checked"' : '',
    				'PUBLIC_NO_CHECKED'  	=> ( $config['bbdkp_apply_visibilitypref'] == '0' ) ? ' checked="checked"' : '', 
                	'FORUM_CHOICE_YES_CHECKED' 	=> ( $config['bbdkp_apply_forumchoice'] == '1' ) ? ' checked="checked"' : '',
    				'FORUM_CHOICE_NO_CHECKED' 	=> ( $config['bbdkp_apply_forumchoice'] == '0' ) ? ' checked="checked"' : '',                 
                	'APPLY_VERS' 		 	=> $config['bbdkp_apply_version'], 
      				'POSTQCOLOR'			=> $config['bbdkp_apply_pqcolor'],
	                'POSTACOLOR'			=> $config['bbdkp_apply_pacolor'],
	                'FORMQCOLOR'			=> $config['bbdkp_apply_fqcolor'], 
                ));

                //region
                $template->assign_block_vars('region', array(
                	'VALUE' 	=> 'EU' , 
                	'SELECTED' 	=> ('EU' == $config['bbdkp_apply_region']) ? ' selected="selected"' : '' , 
                	'OPTION' 	=> 'EU'));
                
                $template->assign_block_vars('region', array(
                	'VALUE' 	=> 'US' , 
                	'SELECTED' 	=> ('US' == $config['bbdkp_apply_region']) ? ' selected="selected"' : '' , 
                	'OPTION' 	=> 'US'));
                
                //guests
				$template->assign_block_vars('guests', array(
                	'VALUE' 	=> 'True' , 
                	'SELECTED' 	=> ('True' == $config['bbdkp_apply_guests']) ? ' selected="selected"' : '' , 
                	'OPTION' 	=> 'True'));
                
                $template->assign_block_vars('guests', array(
                	'VALUE' 	=> 'False' , 
                	'SELECTED' 	=> ('False' == $config['bbdkp_apply_guests']) ? ' selected="selected"' : '' , 
                	'OPTION' 	=> 'False'));
                
                // where do we put candidate ? 
                 $template->assign_block_vars('guild', array(
                	'VALUE' 	=> '0' , 
                	'SELECTED' 	=> ('0' == $config['bbdkp_apply_gchoice']) ? ' selected="selected"' : '' , 
                	'OPTION' 	=> $user->lang['ACP_APPLY_GNONE']));
                
                $template->assign_block_vars('guild', array(
                	'VALUE' 	=> '1' , 
                	'SELECTED' 	=> ('1' == $config['bbdkp_apply_gchoice']) ? ' selected="selected"' : '' , 
                	'OPTION' 	=> $user->lang['ACP_APPLY_GSEL']));
                
               /*
                * loading questions
                */
                
                $type = array(
                		'Inputbox' => $user->lang['APPLY_ACP_INPUTBOX'],
                		'Textbox' => $user->lang['APPLY_ACP_TXTBOX'],
                		'Textboxbbcode' => $user->lang['APPLY_ACP_TXTBOXBBCODE'],
                		'Selectbox'=> $user->lang['APPLY_ACP_SELECTBOX'],
                		'Radiobuttons' => $user->lang['APPLY_ACP_RADIOBOX'],
                		'Checkboxes' => $user->lang['APPLY_ACP_CHECKBOX']
                );
                
                foreach ($type as $key => $value)
                {
                	$template->assign_block_vars('template_type', array(
                			'TYPE' => $key ,
                			'VALUE' => $value,
                			'SELECTED' => ($key == $row['type']) ? ' selected="selected"' : '' ,
                	));
                }
                
                $sql = 'SELECT * FROM ' . APPTEMPLATE_TABLE . ' WHERE template_id = ' . $applytemplate_id . '  ORDER BY qorder ';
                $result = $db->sql_query($sql);
                while ($row = $db->sql_fetchrow($result)) 
                {
                    $checked = '';
                    if ($row['mandatory'] == 'True') 
                    {
                        $checked = ' checked="checked"';
                    }
                    
                    $template->assign_block_vars('apptemplate', array(
                    	'QORDER'         => $row['qorder'] , 
                    	'HEADER'      	 => $row['header'] ,
                    	'QUESTION'       => $row['question'] , 
                    	'MANDATORY'      => $row['mandatory'] , 
                        'OPTIONS'        => $row['options'] ,
                    	'CHECKED'        => $checked,
                    	'ID'			 => $row['id'] ,
	                    'U_APPQUESTIONMOVE_UP'		 => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_apply&amp;mode=apply_settings&amp;appquestionmove_up=1&amp;id={$row['id']}"), 
						'U_APPQUESTIONMOVE_DOWN'	 => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_apply&amp;mode=apply_settings&amp;appquestionmove_down=1&amp;id={$row['id']}"),
                    	'U_APPQUESTIONDELETE'		 => append_sid("{$phpbb_admin_path}index.$phpEx", "i=dkp_apply&amp;mode=apply_settings&amp;appquestiondelete=1&amp;id={$row['id']}"),
                      ));
                    
                    foreach ($type as $key => $value) 
                    {
                        $template->assign_block_vars('apptemplate.template_type', array(
                        	'TYPE' => $key , 
                        	'VALUE' => $value,
                        	'SELECTED' => ($key == $row['type']) ? ' selected="selected"' : '' , 
                        	));
                    }
                }
                $db->sql_freeresult($result);
                
                
                $this->page_title = $user->lang['ACP_DKP_APPLY']; 
                $this->tpl_name = 'dkp/acp_' . $mode;
                break;
      }
   }
 
}

?>