<?php
/**
* Application form created by Kapli (bbDKP developer)
* 
* @package bbDKP
* @copyright (c) 2009 bbDkp https://github.com/bbDKP/Apply
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @author Kapli, Malfate, Sajaki, Blazeflack, Twizted
* @version 1.3.8
*/


// do not change below this line
/**
* @ignore
*/ 
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
include($phpbb_root_path . 'includes/message_parser.' . $phpEx);
// Start session management
$user->session_begin();
$auth->acl($user->data);

$error = array();
$current_time = $user->time_now; 
$user->setup(array('posting', 'mcp', 'viewtopic', 'mods/apply', 'mods/dkp_common', 'mods/dkp_admin'), false);

// set apply template id from $_GET: 
$template_id = request_var('template_id', 0);
if($template_id == 0)
{
	//no parameters passed... go find the nearest template
	$result = $db->sql_query_limit ( 'SELECT * FROM ' . APPTEMPLATELIST_TABLE . ' ORDER BY template_id ASC', 1, 0);
	while ( $row = $db->sql_fetchrow ( $result ) )
	{
		$template_id = $row ['template_id']; 
	}
	$affected_rows = $db->sql_affectedrows();
	if($affected_rows == 0)
	{
		trigger_error($user->lang['ALERT_NOTEMPLATE'], E_USER_WARNING);
	}
	$db->sql_freeresult ($result);
	
}

$form_key = '2LK3aStYs8hu1V2PQ';

// declare captcha class
if (!class_exists('phpbb_captcha_factory'))
{
	include($phpbb_root_path . 'includes/captcha/captcha_factory.' . $phpEx);
}

// make captcha object
$captcha =& phpbb_captcha_factory::get_instance($config['captcha_plugin']);

// if "enable visual confirmation for guest postings" is set to "ON"
// and the user is not registered then set up captcha  
if ($config['enable_post_confirm'] && !$user->data['is_registered'])  
{
	$captcha->init(CONFIRM_POST);
}

//check if visitor can access the form
$post_data = check_apply_form_access($template_id);

//request variables
$submit	= (isset($_POST['post'])) ? true : false;

if ($submit)
{
	// anon user and "enable visual confirmation for guest postings" is set to "ON" and post mode ?
	if ($config['enable_post_confirm'] && in_array('post', array('post')) && !$user->data['is_registered'] )
	{
		// first validate captcha 
		$vc_response = $captcha->validate();
		if ($vc_response)
		{
			$error[] = $vc_response;
		}
	}
	
	if (!check_form_key($form_key))
	{
		$error[] = $user->lang['FORM_INVALID'];
	}
	
	//check if user forgot to enter a required field other than those covered with js
	$sql = "SELECT * FROM " . APPTEMPLATE_TABLE . " where mandatory = 'True' ORDER BY qorder   ";
	$result = $db->sql_query_limit($sql, 100, 1);
	while ( $row = $db->sql_fetchrow($result))
	{
		if ($row['type']=='Checkboxes')
		{
			if ( request_var('templatefield_' .$row['qorder'],  array('' => '')) == '') 
			{
				$error[] = $user->lang['APPLY_REQUIRED'];
			}
		}
		else 
		{
			if ( request_var('templatefield_' . $row['qorder'], '') == '') 
			{
				// return user to index
				$error[] = $user->lang['APPLY_REQUIRED'];
			}
		
		}
		
	}
	$db->sql_freeresult($result);

	$candidate_name = utf8_normalize_nfc(request_var('candidate_name', ' ', true));
	
	// check for validate name. name can only be alphanumeric without spaces or special characters
	// this is to keep gibberish out of our dkpmember database
	//if this preg_match returns true then there is something other than letters
   if (preg_match('/[^a-zA-ZàäåâÅÂçÇéèëËêÊïÏîÎíÍìÌæŒæÆÅóòÓÒöÖôÔøØüÜ\s]+/', $candidate_name  ))
   {
	  $error[] = $user->lang['APPLY_ERROR_NAME']. $candidate_name . ' ';  
   }
	 
	if (!sizeof($error))
	{
		// continue to posting
		make_apply_posting($post_data, $current_time, $candidate_name, $template_id);
	}
	
}

fill_application_form($form_key, $post_data, $submit, $error, $captcha, $template_id);

/**
 * makes a candidate object
 * 
 * @param dkp_character $candidate
 */
function build_candidate(dkp_character &$candidate, apply_post &$apply_post )
{
	global $config, $db, $user;
	
	$board_url = generate_board_url() . '/';
	
	switch ($apply_post->gchoice)
	{
		case '1':
			//get the lowest rank (in WoW rank 8 is lowest)
			$sql = "SELECT max(rank_id) as rank_id from " . MEMBER_RANKS_TABLE . " WHERE rank_id < 90 and guild_id = " . $apply_post->candidate_guild_id;
			$result = $db->sql_query($sql);
			$candidate->guildrank = max((int) $db->sql_fetchfield('rank_id'), 0);
			$candidate->guild_id = $apply_post->candidate_guild_id;
			$db->sql_freeresult($result);
			break;
		default:
			// don't add char to guild roster but insert anyways
			$candidate->guild_id = 0;
			$candidate->guildrank = 99;
			break;
	}
	
	$candidate->realm = trim(utf8_normalize_nfc(request_var('candidate_realm', $config['bbdkp_apply_realm'], true)));
	$candidate->level = utf8_normalize_nfc(request_var('candidate_level', ' ', true));
	$candidate->game = request_var('game_id', '');
	$candidate->genderid = request_var('candidate_gender', 0);
	$candidate->raceid = request_var('candidate_race_id', 0);
	
	//character class
	$sql_array = array(
			'SELECT'	=>	' r.race_id, r.image_female, r.image_male, l.name as race_name ',
			'FROM'		=> array(
					RACE_TABLE		=> 'r',
					BB_LANGUAGE		=> 'l',
			),
			'WHERE'		=> " l.game_id = r.game_id 
							AND r.race_id = '". $candidate->raceid ."' 
							AND r.game_id = '" . $candidate->game . "'
							AND l.attribute_id = r.race_id  
							AND l.language= '" . $config['bbdkp_lang'] . "' 
							AND l.attribute = 'race' ",
	);
	
	$sql = $db->sql_build_query('SELECT', $sql_array);
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	if(isset($row))
	{
		$candidate->race = $row['race_name'];
		$candidate->race_image = (string) (($candidate->genderid == 0) ? $row['image_male'] : $row['image_female']);
		$candidate->race_image = (strlen($candidate->race_image) > 1) ? $board_url . "images/race_images/" . $candidate->race_image . ".png" : '';
		$candidate->race_image_exists = (strlen($candidate->race_image) > 1) ? true : false;
	}
	unset($row);
	$db->sql_freeresult($result);
	
	$candidate->classid = request_var('candidate_class_id', 0);
	
	//character class
	$sql_array = array(
			'SELECT'	=>	' c.class_armor_type AS armor_type , c.colorcode, c.imagename,  c.class_id, l.name as class_name ',
			'FROM'		=> array(
					CLASS_TABLE		=> 'c',
					BB_LANGUAGE		=> 'l',
			),
			'WHERE'		=> " l.game_id = c.game_id 
							AND c.class_id = '". $candidate->classid ."' 
							AND c.game_id = '" . $candidate->game . "'
							AND l.attribute_id = c.class_id  
							AND l.language= '" . $config['bbdkp_lang'] . "' 
							AND l.attribute = 'class' ",
	);
	
	$sql = $db->sql_build_query('SELECT', $sql_array);
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	if(isset($row))
	{
		$candidate->class =	$row['class_name'];
		$candidate->class_color =  (strlen($row['colorcode']) > 1) ? $row['colorcode'] : '';
		$candidate->class_color_exists =  (strlen($row['colorcode']) > 1) ?  true : false;
		$candidate->class_image = 	strlen($row['imagename']) > 1 ? $board_url . "images/class_images/" . $row['imagename'] . ".png" : '';
		$candidate->class_image_exists =    (strlen($row['imagename']) > 1) ? true : false;
	}
	
	unset($row);
	$db->sql_freeresult($result);
	
}

/**
 * post application on forum
 *
 */
function make_apply_posting($post_data, $current_time, $candidate_name, $template_id)
{
	global $auth, $config, $db, $user, $phpbb_root_path, $phpEx, $captcha;
	
	if(!class_exists('apply_post'))
	{
		include($phpbb_root_path . 'includes/bbdkp/apply/dkp_character.' . $phpEx);
	}
	$apply_post = new apply_post();
	$candidate = new dkp_character();
	$candidate->name =  $candidate_name; 
	
	$sql = "SELECT * from " . APPTEMPLATELIST_TABLE . " WHERE template_id  = " . $template_id;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	if(isset($row))
	{
		$apply_post->questioncolor = $row['question_color'];
		$apply_post->answercolor = $row['answer_color'];
		$apply_post->gchoice = $row['gchoice'];
		$apply_post->candidate_guild_id = $row['guild_id'];
	}
	
	build_candidate($candidate, $apply_post);
	
	// if user belongs to group that can add a character then attempt to register a dkp character
	// guests should never be able to register characters (i.e user anonymous)
	if($auth->acl_get('u_dkp_charadd') )
	{
		register_bbdkp($candidate);
	}
		
	// build post
	$apply_post->message = '';
	
	$apply_post->message .= '[size=150][b]' .$user->lang['APPLY_CHAR_OVERVIEW'] . '[/b][/size]'; 
	$apply_post->message .= '<br /><br />';
	
	// name
	$apply_post->message .= '[color='. $apply_post->questioncolor .']' . $user->lang['APPLY_NAME'] . '[/color]';
	if($candidate->class_color_exists)
	{
		$apply_post->message .= '[b][color='. $candidate->class_color .']' . $candidate->name . '[/color][/b]' ;
	}
	else
	{
		$apply_post->message .= '[b]' . $candidate->name  . '[/b]' ;
	}
	$apply_post->message .= '<br />'; 

	//Realm
	$apply_post->message .= '[color='. $apply_post->questioncolor .']' . $user->lang['APPLY_REALM1'] . '[/color]' . '[color='. $apply_post->answercolor .']' . $candidate->realm . '[/color]' ;
	$apply_post->message .= '<br />'; 

	// level
	$apply_post->message .= '[color='. $apply_post->questioncolor .']' . $user->lang['APPLY_LEVEL'] . '[/color]' . '[color='. $apply_post->answercolor .']' . $candidate->level . '[/color]' ;
	$apply_post->message .= '<br />'; 
	
	// class
	$apply_post->message .= '[color='. $apply_post->questioncolor .']' . $user->lang['APPLY_CLASS'] . '[/color] ';
	if($candidate->class_image_exists )
	{
		$apply_post->message .= '[img]' .$candidate->class_image  . '[/img] ';
	}
	if($candidate->class_color_exists)
	{
		$apply_post->message .= ' [color='. $candidate->class_color .']' . $candidate->class . '[/color]' ;
	}
	else
	{
		$apply_post->message .= $candidate->class;
	}
	$apply_post->message .= '<br />'; 

	//race
	$apply_post->message .= '[color='. $apply_post->questioncolor .']' . $user->lang['APPLY_RACE'] . '[/color] ';
	if($candidate->race_image_exists )
	{
		$apply_post->message .= '[img]' .$candidate->race_image . '[/img] ';
	}
	if($candidate->class_color_exists)
	{
		$apply_post->message .= ' [color='. $apply_post->questioncolor .']' . $candidate->race . '[/color]' ;
	}
	else
	{
		$apply_post->message .= $candidate->race;
	}
	$apply_post->message .= '<br /><br />';

	
	// Motivation	
	$apply_post->message .= '[size=150][b]' .$user->lang['APPLY_CHAR_MOTIVATION'] . '[/b][/size]';
	$apply_post->message .= '<br /><br />';
	
	// complete with formatted questions and answers
	$sql = "SELECT * FROM " . APPTEMPLATE_TABLE . ' WHERE template_id = ' . $template_id .'  ORDER BY qorder' ;
	$result = $db->sql_query_limit($sql, 100, 0);
	while ( $row = $db->sql_fetchrow($result) )
	{
		if ( isset($_POST['templatefield_' . $row['qorder']]) )
		{
			
			switch ($row['type'])
			{
					
				case 'Checkboxes':
					 $cb_countis = count( request_var('templatefield_' . $row['qorder'], array(0 => 0)) );  
                     $cb_count = 0;
						                                           
                        $apply_post->message .= '[size=120][color='. $apply_post->questioncolor .'][b]' . $row['question'] . ': [/b][/color][/size]';
						$apply_post->message .= '<br />';
                        
                        $checkboxes = utf8_normalize_nfc( request_var('templatefield_' . $row['qorder'], array(0 => '') , true));
                        foreach($checkboxes as $value) 
                        {
                            $apply_post .= $value;
                            if ($cb_count < $cb_countis-1)
                            {
                                $apply_post->message .= ',  ';
                            }
                            $cb_count++;
                        }
                        $apply_post->message .= '<br /><br />';                         
					
					break;
				case 'Inputbox':
				case 'Textbox':
				case 'Textboxbbcode':					
				case 'Selectbox':					
				case 'Radiobuttons':			
					$fieldcontents = utf8_normalize_nfc(request_var('templatefield_' . $row['qorder'], ' ', true));	
						
					$apply_post->message .= '[size=120][color='. $apply_post->questioncolor .'][b]' . $row['question'] . ': [/b][/color][/size]';
					$apply_post->message .= '<br />';
					 
					$apply_post->message .=	$fieldcontents;
					
					$apply_post->message .= '<br /><br />'; 
					break;
					
					
			}

		}
	}
	$db->sql_freeresult($result);
	
	// variables to hold the parameters for submit_post
	$poll = $uid = $bitfield = $options = ''; 
	// parsed code
	generate_text_for_storage($apply_post->message, $uid, $bitfield, $options, true, true, true);

	// subject & username

	//$post_data['post_subject'] = utf8_normalize_nfc(request_var('headline', $user->data['username'], true));
	$post_subj	= (string) $candidate->name . " - " . $candidate->level . " " . $candidate->race . " ". $candidate->class;
	
	// Store message, sync counters
	
		$data = array( 
		'forum_id'			=> (int) $post_data['forum_id'],
		'topic_first_post_id'	=> 0,
		'topic_last_post_id'	=> 0,
		'topic_attachment'		=> 0,		
		'icon_id'			=> false,
		'enable_bbcode'		=> true,
		'enable_smilies'	=> true,
		'enable_urls'		=> true,
		'enable_sig'		=> true,
		'message'			=> $apply_post->message,
		'message_md5'		=> md5($apply_post->message),
		'bbcode_bitfield'	=> $bitfield,
		'bbcode_uid'		=> $uid,
		'post_edit_locked'	=> 0,
		'topic_title'		=> $post_subj,
		'notify_set'		=> false,
		'notify'			=> false,
		'post_time' 		=> $current_time,
		'poster_ip'			=> $user->ip,
		'forum_name'		=> '',
		'post_edit_locked'	=> 1,
		'enable_indexing'	=> true,
		'post_approved'        => 1,
		);
		
		
		//submit post
		$post_url = submit_post('post', $post_subj, $user->data['username'], POST_NORMAL, $poll, $data);
		
		$redirect_url = $post_url;
			
		if ($config['enable_post_confirm'] && (isset($captcha) && $captcha->is_solved() === true))
		{
			$captcha->reset();
		}
		
		//redirect to post
		meta_refresh(3, $redirect_url);

		$message = 'POST_STORED';
		$message = $user->lang[$message] . '<br /><br />' . sprintf($user->lang['VIEW_MESSAGE'], '<a href="' . $redirect_url . '">', '</a>');
		$message .= '<br /><br />' . sprintf($user->lang['RETURN_FORUM'], '<a href="' . append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $data['forum_id']) . '">', '</a>');
		trigger_error($message);

}


/**
 * registers a bbDKP character 
 *
 * @param dkp_character $candidate
 */
function register_bbdkp(dkp_character $candidate)
{
	global $db, $auth, $user, $config, $phpbb_root_path, $phpEx;
	
	// check if user exceeded allowed character count, to prevent alt spamming
	$sql = 'SELECT count(*) as charcount
			FROM ' . MEMBER_LIST_TABLE . '	
			WHERE phpbb_user_id = ' . (int) $user->data['user_id'];
	$result = $db->sql_query($sql);
	$countc = $db->sql_fetchfield('charcount');
	$db->sql_freeresult($result);
	if ($countc >= $config['bbdkp_maxchars'])
	{
		//do nothing
		return;
	}
	
	// check if membername exists
	$sql = 'SELECT count(*) as memberexists 
			FROM ' . MEMBER_LIST_TABLE . "	
			WHERE ucase(member_name)= ucase('" . $db->sql_escape($candidate->name) . "')"; 
	$result = $db->sql_query($sql);
	$countm = $db->sql_fetchfield('memberexists');
	$db->sql_freeresult($result);
	if ($countm != 0)
	{
		// give a nice alert and stop right here.
		 trigger_error($user->lang['ERROR_MEMBEREXIST'], E_USER_WARNING);
	}
	
	$member_comment = 'candidate'; 
	
	// add the char
	if (! class_exists ( 'acp_dkp_mm' ))
	{
		include ($phpbb_root_path . 'includes/acp/acp_dkp_mm.' . $phpEx);
	}
	$acp_dkp_mm = new acp_dkp_mm ( );
		
	$member_id = $acp_dkp_mm->insertnewmember(
		$candidate->name,
		 1,
		$candidate->level,
		$candidate->raceid,
		$candidate->classid,
		$candidate->guildrank,
		$member_comment, 
		time(), 
		0, 
		$candidate->guild, 
		$candidate->genderid, 
		0, 
		' ',
		' ', 
		$candidate->realm, 
		$candidate->game, 
		$user->data['user_id']
	);
	
	return $member_id;
	
}

/**
 *  build Application form 
 *
 */
function fill_application_form($form_key, $post_data, $submit, $error, $captcha, $template_id)
{
	global $user, $template, $config, $phpbb_root_path, $phpEx, $auth, $db;
	
	// Page title & action URL, include session_id for security purpose
	$s_action = append_sid("{$phpbb_root_path}apply.$phpEx", "", true, $user->session_id);
	
	$page_title = $user->lang['APPLY_MENU'];

	//check if there are questions
	$result = $db->sql_query_limit ( 'SELECT * FROM ' . APPTEMPLATE_TABLE . ' WHERE template_id = ' . $template_id, 1, 0);
	while ( $row = $db->sql_fetchrow ($result) )
	{
		$template_id = $row ['template_id'];
	}

	$affected_rows = $db->sql_affectedrows();
	if($affected_rows == 0)
	{
		trigger_error(sprintf($user->lang['ALERT_NOQUESTIONS'], $template_id), E_USER_WARNING); 
	}
	$db->sql_freeresult ($result);
	
	// get WELCOME_MSG
	$sql = 'SELECT announcement_msg, bbcode_uid, bbcode_bitfield, bbcode_options FROM ' . APPHEADER_TABLE . ' WHERE template_id = ' . $template_id ;
	$db->sql_query($sql);
	$result = $db->sql_query($sql);
	while ( $row = $db->sql_fetchrow($result) )
	{
		$welcome_message = $row['announcement_msg'];
		$bbcode_uid = $row['bbcode_uid'];
		$bbcode_bitfield = $row['bbcode_bitfield'];
		$bbcode_options = $row['bbcode_options'];
	}
	$welcome_message = generate_text_for_display($welcome_message, $bbcode_uid, $bbcode_bitfield, $bbcode_options);
	$db->sql_freeresult($result);
		
	if ($config['enable_post_confirm'] && !$user->data['is_registered'] ) 
    {
    	if ((!$submit || !$captcha->is_solved()) )
    	{
	        // ... display the CAPTCHA
	        $template->assign_vars(array(
	            'S_CONFIRM_CODE'                => true,
	            'CAPTCHA_TEMPLATE'              => $captcha->get_template(),
	        ));
    	}
    }
	
	$s_hidden_fields =array(); 
	// Add the confirm id/code pair to the hidden fields, else an error is displayed on next submit/preview
	if (isset($captcha))
	{
		if ($captcha->is_solved() !== false)
		{
			$s_hidden_fields .= build_hidden_fields($captcha->get_hidden_fields());
		}
	}
	
	// get list of possible games */ 
	if (!class_exists('bbDKP_Admin'))
	{
		require("{$phpbb_root_path}includes/bbdkp/bbdkp.$phpEx");
	}
	$bbdkp = new bbDKP_Admin();
	$installed_games = array();
	$i=0;
	foreach($bbdkp->games as $gameid => $gamename)
	{
		if ($config['bbdkp_games_' . $gameid] == 1)
		{
			$installed_games[$gameid] = $gamename;
			
			if($i==0) $gamepreset =  $gameid;	
			$i+=1;
			
			$template->assign_block_vars('game_row', array(
				'VALUE' => $gameid,
				'SELECTED' => ((isset($member['game_id']) ? $member['game_id'] : '') == $gameid ) ? ' selected="selected"' : '',
				'OPTION'   => $gamename, 
			));
		}
			
	}
     
	// Race dropdown
	// reloading is done from ajax to prevent redraw
	$sql_array = array(
	'SELECT'	=>	'  r.race_id, l.name as race_name ', 
	'FROM'		=> array(
			RACE_TABLE		=> 'r',
			BB_LANGUAGE		=> 'l',
				),
	'WHERE'		=> " r.race_id = l.attribute_id 
					AND r.game_id = '" . $gamepreset . "' 
					AND l.attribute='race' 
					AND l.game_id = r.game_id 
					AND l.language= '" . $config['bbdkp_lang'] ."'",
	);
	$sql = $db->sql_build_query('SELECT', $sql_array);
	$result = $db->sql_query($sql);
	while ( $row = $db->sql_fetchrow($result) )
	{
		$template->assign_block_vars('race_row', array(
		'VALUE' => $row['race_id'],
		'SELECTED' =>  '',
		'OPTION'   => ( !empty($row['race_name']) ) ? $row['race_name'] : '(None)')
		);
	}

	// Class dropdown
	// reloading is done from ajax to prevent redraw
	$sql_array = array(
		'SELECT'	=>	' c.class_id, l.name as class_name, c.class_hide,
						  c.class_min_level, class_max_level, c.class_armor_type , c.imagename ', 
		'FROM'		=> array(
			CLASS_TABLE		=> 'c',
			BB_LANGUAGE		=> 'l', 
			),
		'WHERE'		=> " l.game_id = c.game_id  AND c.game_id = '" . $gamepreset . "' 
		AND l.attribute_id = c.class_id  AND l.language= '" . $config['bbdkp_lang'] . "' AND l.attribute = 'class' ",					 
	);
	
	$sql = $db->sql_build_query('SELECT', $sql_array);					
	$result = $db->sql_query($sql);
	while ( $row = $db->sql_fetchrow($result) )
	{
		if ( $row['class_min_level'] <= 1  ) 
		{
			 $option = ( !empty($row['class_name']) ) ? $row['class_name'] . " 
			 Level (". $row['class_min_level'] . " - ".$row['class_max_level'].")" : '(None)';
		}
		else
		{
			 $option = ( !empty($row['class_name']) ) ? $row['class_name'] . " 
			 Level ". $row['class_min_level'] . "+" : '(None)';
		}
		
		$template->assign_block_vars('class_row', array(
		'VALUE' => $row['class_id'],
		'SELECTED' => '',
		'OPTION'   => $option ));
		
	}
	$db->sql_freeresult($result);
             	
	// Start assigning vars for main posting page ...
	// main questionnaire 
	$sql = "SELECT a.id, a.qorder, a.header, a.question, a.type, a.mandatory, a.options, a.template_id, a.lineid, a.showquestion,  
			b.template_name, b.forum_id  
		FROM " . APPTEMPLATE_TABLE . ' a, ' . 
			APPTEMPLATELIST_TABLE . ' b 
			WHERE a.template_id = b.template_id 
			AND a.template_id = ' . $template_id . '
			ORDER BY a.qorder ASC ';
	$result = $db->sql_query($sql);
					
	while ( $row = $db->sql_fetchrow($result) )
	{
		$template->assign_block_vars('apptemplate', array(
				'QORDER'			=> $row['qorder'],
				'S_MANDATORY'		=> ($row['mandatory'] =='True') ? true:false ,
				'FORUM_ID'			=> $row['forum_id'], 
				'TITLE'				=> $row['header'],
				'TYPE'   			=> $row['type'],
				'QUESTION'			=> ((int) $row['showquestion'] == 1) ? $row['question']:'',
				'S_SHOWQUESTION'	=> ((int) $row['showquestion'] == 1) ? true:false,
				'DOMNAME'			=> 'templatefield_' . $row['qorder'],
				'TABINDEX'			=> $row['qorder'],
				)
		);
		
		switch($row['type'])
		{
			case 'Selectbox':
			         $select_option = explode(',', $row['options']);
			         foreach($select_option as  $key =>  $value) 
			         {
			         	$template->assign_block_vars('apptemplate.selectboxoptions', array(
		         			'KEY'		=> $value,
		         			'VALUE'		=> $value,
			         	));
			         }           
				break;
			case 'Radiobuttons':
				$radio_option = explode(',', $row['options']);
				foreach($radio_option as $key => $value)
				{
					$template->assign_block_vars('apptemplate.radiobuttonoptions', array(
							'KEY'		=> $value,
							'VALUE'		=> $value,
					));
				}
				break;
			case 'Checkboxes':
				$check_option = explode(',', $row['options']);
				foreach($check_option as  $key => $value)
				{
					$template->assign_block_vars('apptemplate.checkboxoptions', array(
							'KEY'		=> $value,
							'VALUE'		=> $value,
					));
				}
				break;
		}
	}
	$db->sql_freeresult($result);
	
	$form_enctype = (@ini_get('file_uploads') == '0' || strtolower(@ini_get('file_uploads')) == 'off' || !$config['allow_attachments'] || !$auth->acl_get('u_attach') || !$auth->acl_get('f_attach', $post_data['forum_id'])) ? '' : ' enctype="multipart/form-data"';
	add_form_key($form_key);
	
	// assign global template vars to questionnaire
	$template->assign_vars(array(
		'WELCOME_MSG'			=> $welcome_message,	
		'MALE_CHECKED'			=> ' checked="checked"',
		'L_POST_A'				=> $page_title,
		'ERROR'					=> (sizeof($error)) ? implode('<br />', $error) : '',
		'S_POST_ACTION'     	=> $s_action,
		'S_HIDDEN_FIELDS'   	=> $s_hidden_fields,
		'APPLY_REALM'			=> str_replace("+", " ", $config['bbdkp_apply_realm']), 
		'S_FORM_ENCTYPE'		=> $form_enctype,
		// javascript
		'LA_ALERT_AJAX'		  => $user->lang['ALERT_AJAX'],
		'LA_ALERT_OLDBROWSER' => $user->lang['ALERT_OLDBROWSER'],
		'LA_MSG_NAME_EMPTY'	  => $user->lang['APPLY_REQUIRED_NAME'],
		'LA_MSG_LEVEL_EMPTY'  => $user->lang['APPLY_REQUIRED_LEVEL'],	
		)
	);
		
	// Output application form
	page_header($page_title);
	
	$template->set_filenames(array(
		'body' => 'dkp/application.html')
	);
	
	page_footer();
	
}
	
/**
 * check form access before even posting. 
 *
 * @return array $post_data
 */
function check_apply_form_access($template_id)
{
	global $auth, $db, $config, $user;		
	
	$user->add_lang(array('posting'));
	
	$sql = 'SELECT a.* FROM ' . FORUMS_TABLE . ' a, ' . APPTEMPLATELIST_TABLE .' b 
				WHERE a.forum_id = b.forum_id 
				AND b.template_id = ' . $template_id;
	$result = $db->sql_query($sql);
	$post_data = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	// Check permissions
	if ($user->data['is_bot'])
	{
		redirect(append_sid("{$phpbb_root_path}index.$phpEx"));
	}
		
	//set up style vars
	$user->setup(false, $post_data['forum_style']);	
	
	// check authorisations
	$is_authed = false;
	// user has posting permission to the forum ?  
	if ($auth->acl_get('f_post', $post_data['forum_id']))
	{
		//user is authorised for the forum
		$is_authed = true;
	}
	else
	{
		//user has no posting rights in the requested forum (template lang from mcp)
		if ($user->data['is_registered'])
		{
			trigger_error('USER_CANNOT_POST');
		}
		
		//it's a guest and theres no guest access for the forum so ask for a valid login
		login_box('', $user->lang['LOGIN_EXPLAIN_POST']);
	}
	
	// even if guest user has posting rights, we still want to check in our config 
	// if he actually may use the application
	if ($config['bbdkp_apply_guests'] == 'False' && !$user->data['is_registered'])
	{
		$is_authed = false;
	}
	
	// Is the user able to post within this forum? (i.e it's a category)
	if ($post_data['forum_type'] != FORUM_POST)
	{
		trigger_error('USER_CANNOT_FORUM_POST');
	}
	
	// is Forum locked ?
	if (($post_data['forum_status'] == ITEM_LOCKED || (isset($post_data['topic_status']) && $post_data['topic_status'] == ITEM_LOCKED)) && !$auth->acl_get('m_edit', $forum_id))
	{
		trigger_error(($post_data['forum_status'] == ITEM_LOCKED) ? 'FORUM_LOCKED' : 'TOPIC_LOCKED');
	}
	
	return $post_data;
}

/**
 * sends a personal message with the contents of the form
 */
function pm_sendform($message, $user_id = 2, $sender_id = 2)
{
	global $user, $config;
	global $phpEx, $phpbb_root_path;

	include_once($phpbb_root_path . 'includes/functions_privmsgs.' . $phpEx);
	include_once($phpbb_root_path . 'includes/message_parser.' . $phpEx);
	$sender = $this->get_user_info($sender_id);
	
	$message_parser = new parse_message(); 
  
	$data=array();
	$messenger->template('raidplan_delete', $row['user_lang']);
	$subject =  '[' . $user->lang['RAIDPLANNER']  . '] ' . 
	$user->lang['DELRAID'] . ': ' . $this->eventlist->events[$this->event_type]['event_name'] . ' ' . 
	$user->format_date($this->start_time, $config['rp_date_time_format'], true);
	 
	$userids = array($this->poster);
	$rlname = array();
	user_get_id_name($userids, $rlname);
	 
	$messenger->assign_vars(array(
			'RAIDLEADER'		=> $rlname[$this->poster],
			'USERNAME'			=> htmlspecialchars_decode($row['username']),
			'EVENT_SUBJECT'		=> $subject,
			'EVENT'				=> $this->eventlist->events[$this->event_type]['event_name'],
			'INVITE_TIME'		=> $user->format_date($this->invite_time, $config['rp_date_time_format'], true),
			'START_TIME'		=> $user->format_date($this->start_time, $config['rp_date_time_format'], true),
			'END_TIME'			=> $user->format_date($this->end_time, $config['rp_date_time_format'], true),
			'TZ'				=> $user->lang['tz'][(int) $user->data['user_timezone']],
			'U_RAIDPLAN'		=> generate_board_url() . "/dkp.$phpEx?page=planner&amp;view=raidplan&amp;raidplanid=".$this->id
	));
		
	$messenger->msg = trim($messenger->tpl_obj->assign_display('body'));
	$messenger->msg = str_replace("\r\n", "\n", $messenger->msg);
		
	$messenger->msg = utf8_normalize_nfc($messenger->msg);
	$uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
	$allow_bbcode = $allow_smilies = $allow_urls = true;
	generate_text_for_storage($messenger->msg, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
	$messenger->msg = generate_text_for_display($messenger->msg, $uid, $bitfield, $options);

	$data = array(
			'address_list'      => array('u' => array($row['user_id'] => 'to')),
			'from_user_id'      => $user->data['user_id'],
			'from_username'     => $user->data['username'],
			'icon_id'           => 0,
			'from_user_ip'      => $user->data['user_ip'],

			'enable_bbcode'     => true,
			'enable_smilies'    => true,
			'enable_urls'       => true,
			'enable_sig'        => true,
				
			'message'           => $messenger->msg,
			'bbcode_bitfield'   => $this->bbcode['bitfield'],
			'bbcode_uid'        => $this->bbcode['uid'],
	);
		
	if($config['rp_pm_rpchange'] == 1 &&  (int) $row['user_allow_pm'] == 1)
	{
		// send a PM
		submit_pm('post',$subject, $data, false);
	}
	

}


