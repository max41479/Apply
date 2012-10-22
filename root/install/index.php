<?php
/**
* Apply Installer
* Powered by bbDkp (c) 2009 www.bbdkp.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 1.3.8
*
*/

/**
* @ignore
*/
define('UMIL_AUTO', true);
define('IN_PHPBB', true);
define('ADMIN_START', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
$user->session_begin();
$auth->acl($user->data);
$user->setup();
$user->add_lang ( array ('mods/apply'));

if (!file_exists($phpbb_root_path . 'umil/umil_auto.' . $phpEx))
{
	trigger_error('Please download the latest UMIL (Unified MOD Install Library) from: <a href="http://www.phpbb.com/mods/umil/">phpBB.com/mods/umil</a>', E_USER_ERROR);
}

if (!file_exists($phpbb_root_path . 'install/index.' . $phpEx))
{
    trigger_error('Warning! Install directory has wrong name. it must be ‘install‘. Please rename it and launch again.', E_USER_WARNING);
}

// The name of the mod to be displayed during installation.
$mod_name = 'Recruitment Application';

/*
* The name of the config variable which will hold the currently installed version
* You do not need to set this yourself, UMIL will handle setting and updating the version itself.
*/
$version_config_name = 'bbdkp_apply_version';

/*
* The language file which will be included when installing
* Language entries that should exist in the language file for UMIL (replace $mod_name with the mod's name you set to $mod_name above)
* $mod_name
* 'INSTALL_' . $mod_name
* 'INSTALL_' . $mod_name . '_CONFIRM'
* 'UPDATE_' . $mod_name
* 'UPDATE_' . $mod_name . '_CONFIRM'
* 'UNINSTALL_' . $mod_name
* 'UNINSTALL_' . $mod_name . '_CONFIRM'
*/
$language_file = 'mods/apply';

//check old version. if not then trigger error
check_oldversion();

/*
* Options to display to the user (this is purely optional, if you do not need the options you do not have to set up this variable at all)
* Uses the acp_board style of outputting information, with some extras (such as the 'default' and 'select_user' options)

$options = array(
	'test_username'	=> array('lang' => 'TEST_USERNAME', 'type' => 'text:40:255', 'explain' => true, 'default' => $user->data['username'], 'select_user' => true),
	'test_boolean'	=> array('lang' => 'TEST_BOOLEAN', 'type' => 'radio:yes_no', 'default' => true),
);
*/

/*
* Optionally we may specify our own logo image to show in the upper corner instead of the default logo.
* $phpbb_root_path will get prepended to the path specified
* Image height should be 50px to prevent cut-off or stretching.
*/
//$logo_img = 'styles/prosilver/imageset/site_logo.gif';

/*
* The array of versions and actions within each.
* You do not need to order it a specific way (it will be sorted automatically), however, you must enter every version, even if no actions are done for it.
*
* You must use correct version numbering.  Unless you know exactly what you can use, only use X.X.X (replacing X with an integer).
* The version numbering must otherwise be compatible with the version_compare function - http://php.net/manual/en/function.version-compare.php
*/
$announce = encode_announcement($user->lang['APPLY_INFO']);

$versions = array(
		'1.3.3' => array(

		// adding configs
		'config_add' => array(
			 array('bbdkp_apply_realm', 'Realmname', true),
			 array('bbdkp_apply_region', 'us'),
			 array('bbdkp_apply_guests', 'True', true),
			 array('bbdkp_apply_simplerecruit', 'True', true),			
	         array('bbdkp_apply_forum_id_private', '2', true),
	         array('bbdkp_apply_forum_id_public', '2', true),
	         array('bbdkp_apply_visibilitypref', '1', true),	
	         array('bbdkp_apply_pqcolor', '#68f3f8', true),
	         array('bbdkp_apply_pacolor', '#FFFFFF', true),
	         array('bbdkp_apply_fqcolor', '#68f3f8', true),
	         array('bbdkp_apply_forumchoice', '1', true),
	         array('bbdkp_apply_gchoice', '0', true),
			),
          			
		'module_add' => array(
				array('acp', 'ACP_DKP_MEMBER', array(
	           		'module_basename'	=> 'dkp_apply',
					'modes'				=> array('apply_settings')),
	           	 )
	           ),
            
	  'table_add' => array(
            array(
              		$table_prefix . 'bbdkp_apphdr' , array(
                    'COLUMNS'        => array(
                        'announcement_id'    	=> array('INT:8', NULL, 'auto_increment'),
                        'announcement_title' 	=> array('VCHAR_UNI:255', ''),
                        'announcement_msg'   	=> array('TEXT_UNI', ''),
              			'announcement_timestamp' => array('TIMESTAMP', 0),
						'bbcode_bitfield' 		=> array('VCHAR:255', ''),
						'bbcode_uid' 			=> array('VCHAR:8', ''),
              			'user_id'     			=> array('INT:8', 0),
              			'bbcode_options'		=> array('UINT', 7),
                    ),
                    'PRIMARY_KEY'    => 'announcement_id'), 
                ),	           
			array($table_prefix . 'bbdkp_apptemplate', array(
						'COLUMNS'		=> array(
							'id'		=> array('INT:8', NULL, 'auto_increment'),
							'qorder'	=> array('UINT', 0),
							'header'	=> array('VCHAR:255', ''),
							'question'	=> array('VCHAR:255', ''),
							'type'		=> array('VCHAR:255', ''),
							'mandatory'	=> array('VCHAR:255', ''),
							'options'	=> array('MTEXT_UNI', ''),
						),
						'PRIMARY_KEY'	=> 'id',),
				),
 		),
 		
		'table_row_insert' => array(
	        array($table_prefix . 'bbdkp_apphdr' ,
	           array(
	                  array(
	                  	'announcement_title' => $user->lang['APPLY'], 
	                  	'announcement_timestamp' => (int) time(),
	                  	'announcement_msg' => $announce['text'],
	                  	'bbcode_uid' => $announce['uid'],
	                  	'bbcode_bitfield' => $announce['bitfield'],
	                  	'user_id' => $user->data['user_id'] ),          
	           )),  		
				
			array($table_prefix . 'bbdkp_apptemplate', 
				array(
					
					array(
						'qorder'		=> 1,
						'header'		=> $user->lang['DEFAULT_H1'],
						'question'		=> $user->lang['DEFAULT_Q1'],
						'type'			=> 'Textboxbbcode',
						'mandatory'		=> 'False',
						'options'		=> ' ',
					),	
					
					array(
						'qorder'		=> 2,
						'header'		=> $user->lang['DEFAULT_H2'],
						'question'		=> $user->lang['DEFAULT_Q2'],
						'type'			=> 'Inputbox',
						'mandatory'		=> 'False',
						'options'		=> ' ',
					),

					array(
						'qorder'		=>  3,
						'header'		=> $user->lang['DEFAULT_H3'],
						'question'		=> $user->lang['DEFAULT_Q3'],
						'type'			=> 'Textboxbbcode',
						'mandatory'		=> 'False',
						'options'		=> ' ',
					),
					array(
						'qorder'		=> 4,
						'header'		=> $user->lang['DEFAULT_H4'],
						'question'		=> $user->lang['DEFAULT_Q4'],
						'type'			=> 'Textboxbbcode',
						'mandatory'		=> 'True',
						'options'		=> ' ',
					),

					array(
						'qorder'		=> 5,
						'header'		=> $user->lang['DEFAULT_H5'],
						'question'		=> $user->lang['DEFAULT_Q5'],
						'type'			=> 'Textbox',
						'mandatory'		=> 'False',
						'options'		=> ' ',
					),

					
					array(
						'qorder'		=> 6,
						'header'		=> $user->lang['DEFAULT_H6'],
						'question'		=> $user->lang['DEFAULT_Q6'],
						'type'			=> 'Textboxbbcode',
						'mandatory'		=> 'False',
						'options'		=> ' ',
					),

					array(
						'qorder'		=> 7,
						'header'		=> $user->lang['DEFAULT_H7'],
						'question'		=> $user->lang['DEFAULT_Q7'],
						'type'			=> 'Textbox',
						'mandatory'		=> 'False',
						'options'		=> ' ',
					),
										
					array(
						'qorder'		=> 8,
						'header'		=> $user->lang['DEFAULT_H8'],
						'question'		=> $user->lang['DEFAULT_Q8'],
						'type'			=> 'Checkboxes',
						'mandatory'		=> 'False',
						'options'		=> $user->lang['DEFAULT_O8'],
					),	
	
					array(
						'qorder'		=> 9,
						'header'		=> $user->lang['DEFAULT_H9'],
						'question'		=> $user->lang['DEFAULT_Q9'],
						'type'			=> 'Textbox',
						'mandatory'		=> 'False',
						'options'		=> ' ',
					),	
	

					array(
						'qorder'		=> 10,
						'header'		=> $user->lang['DEFAULT_H10'],
						'question'		=> $user->lang['DEFAULT_Q10'],
						'type'			=> 'Textbox',
						'mandatory'		=> 'False',
						'options'		=> ' ',
					),
									
					array(
						'qorder'		=> 11,
						'header'		=> $user->lang['DEFAULT_H11'],
						'question'		=> $user->lang['DEFAULT_Q11'],
						'type'			=> 'Radiobuttons',
						'mandatory'		=> 'False',
						'options'		=> $user->lang['DEFAULT_O11'],
					),	
				
				))
		), 
		
	),
	
	'1.3.4' => array(
		'custom' => array('applyupdater', 'bbdkp_caches'), 
	),

	'1.3.5' => array(
		'custom' => array('applyupdater', 'bbdkp_caches'),
		// add new type
			
	),
	
	'1.3.6' => array(
			
	  'table_add' => array(
            array(
              		$table_prefix . 'bbdkp_apptemplatelist' , array(
						'COLUMNS'		=> array(
							'template_id'	=> array('INT:8', NULL, 'auto_increment'),
							'template_name'	=> array('VCHAR_UNI:255', ''),
							'forum_id'		=> array('INT:8', 0),
							'guild_id'		=> array('INT:8', 1),
							'status'		=> array('BOOL', 0),
						),
						'PRIMARY_KEY'	=> 'template_id',),
				),
 		),
		
		'table_row_insert' => array(
			array($table_prefix . 'bbdkp_apptemplatelist' ,
					array(
							array(
									'template_id' => 1, 
									'template_name'	=> 'Default',
									'forum_id'	=> '2',
									'guild_id'  => get_guild_id(), 
									'status' => 1)
					)),
		),			
					
		'table_column_add' => array(
				array($table_prefix . 'bbdkp_apptemplate', 'template_id' , array('UINT', 0)),
				array($table_prefix . 'bbdkp_apptemplate', 'lineid' , array('UINT', 0)),
		),
		
		'config_remove' => array(
				array('bbdkp_apply_visibilitypref'),
				array('bbdkp_apply_simplerecruit' ),
				array('bbdkp_apply_forum_id_private'),
				array('bbdkp_apply_forum_id_public'),
				array('bbdkp_apply_forumchoice', ),
			),
			
		'custom' => array('tableupd' ),
		),
		
	'1.3.7' => array(
		'config_remove' => array(
				array('bbdkp_apply_gchoice', ),
			),
			
	),		

	'1.3.8' => array(
		'module_add' => array(
				array('acp', 'ACP_DKP_MEMBER', array(
					'module_basename'	=> 'dkp_apply',
					'modes'				=> array('apply_edittemplate')),
					)
			),
		'table_column_add' => array(
				array($table_prefix . 'bbdkp_apphdr', 'template_id' , array('UINT', 0)),
				array($table_prefix . 'bbdkp_apptemplatelist', 'question_color' , array('VCHAR:8', '')),
				array($table_prefix . 'bbdkp_apptemplatelist', 'answer_color' , array('VCHAR:8', '')),
				array($table_prefix . 'bbdkp_apptemplatelist', 'gchoice' , array('BOOL', 0)),
				array($table_prefix . 'bbdkp_apptemplate', 'showquestion' , array('BOOL', 1)),
		),

		'config_remove' => array(
				array('bbdkp_apply_pacolor'),
				array('bbdkp_apply_pqcolor'), 
				array('bbdkp_apply_fqcolor'), 
		),
		
	  // new template class for characters
	  'table_add' => array(
			array($table_prefix . 'bbdkp_chartemplate', array(
						'COLUMNS'		=> array(
							'id'		=> array('INT:8', NULL, 'auto_increment'),
							'qorder'	=> array('UINT', 0),
							'type'		=> array('VCHAR:255', ''),
							'mandatory'	=> array('VCHAR:255', ''),
							'template_id'	=> array('UINT', 0),
						),
						'PRIMARY_KEY'	=> 'id',),
				),
 		),
 		
			
		'custom' => array( 'tableupd', 'applyupdater', 'bbdkp_caches'),
		),		
		
		
);

// We include the UMIF Auto file and everything else will be handled automatically.
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);


/**
 * this function fills the plugin table.
 *
 * @param string $action
 * @param string $version
 * @return string
 */
function applyupdater($action, $version)
{
	global $db, $table_prefix, $user, $umil, $bbdkp_table_prefix, $phpbb_root_path, $phpEx;
	switch ($action)
	{
		case 'install' :
		case 'update' :
			$umil->db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_plugins WHERE name = 'apply'");	
			// We insert new data in the plugin table
			$umil->table_row_insert($table_prefix . 'bbdkp_plugins',
			array(
				array( 
					'name'  => 'apply', 
					'value'  => '1',
					'version'  => $version, 								
					'orginal_copyright'  => 'Kapli, Malfate', 				
					'bbdkp_copyright'  => 'bbDKP Team', 				
					),
			));
			
			return array('command' => sprintf($user->lang['APPLY_UPD_MOD'], $version) , 'result' => 'SUCCESS');
			
			break;
		
		case 'uninstall' :
			$umil->db->sql_query('DELETE FROM ' . $table_prefix . "bbdkp_plugins WHERE name = 'apply'");
			return array(
					'command' => sprintf($user->lang['APPLY_UNINSTALL_MOD'], $version) ,  
					'result' => 'SUCCESS');
			break;
	
	}
}


/**
 * encode announcement text
 *
 * @param unknown_type $text
 * @return unknown
 */
function encode_announcement($text)
{
	$uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
	$allow_bbcode = $allow_urls = $allow_smilies = true;
	generate_text_for_storage($text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
	$announce['text']=$text;
	$announce['uid']=$uid;
	$announce['bitfield']=$bitfield;
	return $announce;
}

/**************************************
 * global function for clearing cache
 */
function clearcaches($action, $version)
{
    global $db, $table_prefix, $umil, $bbdkp_table_prefix;
    
    $umil->cache_purge();
    $umil->cache_purge('imageset');
    $umil->cache_purge('template');
    $umil->cache_purge('theme');
    $umil->cache_purge('auth');
    
    return 'UMIL_CACHECLEARED';
}

/***
 * checks if there is an older install
 */
function check_oldversion()
{
	global $db, $table_prefix, $umil, $config, $phpbb_root_path, $phpEx;
	
	include($phpbb_root_path . 'umil/umil.' . $phpEx);
	$umil=new umil;
	
	// check config		
	if($umil->config_exists('bbdkp_apply_version'))
    {
		if(version_compare($config['bbdkp_apply_version'], '1.3.3') == -1 )
		{
			//stop here, the version is less than 1.3.3
			trigger_error( $user->lang['ERROR_MINIMUM133'], E_USER_WARNING);  
		}
    }   	
}

/**
 * version 1.3.6 : adds a new double pk to template table
 * version 1.3.8 : install one header per template
 */
function tableupd($action, $version)
{
	global $user, $umil, $config, $db, $table_prefix;
	
	switch ($action)
	{
		case 'install' :
		case 'update' :
			switch ($version)
			{
				case '1.3.6':
					//insert values in new columns
					$db->sql_query('UPDATE ' . $table_prefix . 'bbdkp_apptemplate SET template_id = 1, lineid = id');
					// make new unique composite key
					$db->sql_query('CREATE UNIQUE INDEX template ON ' . $table_prefix . 'bbdkp_apptemplate (template_id, lineid) ');
					break;
				case '1.3.8':
					
					$sql='SELECT * FROM ' . $table_prefix . 'bbdkp_apphdr'; 
					$result = $db->sql_query($sql);
					$titleinfo = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);
					
					$sql='DELETE FROM ' . $table_prefix . 'bbdkp_apphdr';
					$db->sql_query($sql);
					
					$sql='SELECT template_id FROM ' . $table_prefix . 'bbdkp_apptemplatelist';
					$result = $db->sql_query($sql);
					$templatelist = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);
					
					foreach ( $templatelist as $key => $thistemplate )
					{
						$bbdkp_apphdr[$thistemplate['template_id']] = $titleinfo[0];
						$bbdkp_apphdr[$thistemplate['template_id']]['template_id'] = $thistemplate['template_id'];

						$sql = 'INSERT INTO ' . $table_prefix . 'bbdkp_apphdr' . ' ' . $db->sql_build_array('INSERT', $bbdkp_apphdr[$thistemplate['template_id']]);
						$db->sql_query($sql);
					}
					
					$sql="UPDATE " . $table_prefix . "bbdkp_apptemplatelist  SET gchoice=1, question_color = '#a9192d', answer_color = '#8492d7'";  
					$db->sql_query($sql);
					
					break;
			}
			break;
		case 'uninstall':
			switch ($version)
			{
				case '1.3.6':
					$db->sql_query('TRUNCATE TABLE ' . $table_prefix . "bbdkp_apptemplate ");
			}
			break;
	}
}

/**
 * gets the default guildid to create a template for. 
 */
function get_guild_id()
{
	global $db;
	
	$sql_array = array(
			'SELECT'    => 'a.id',
			'FROM'      => array(
					GUILD_TABLE => 'a',
					MEMBER_LIST_TABLE => 'b'
			),
			'WHERE'     =>  'a.id = b.member_guild_id and id != 0',
			'GROUP_BY'  =>  'a.id, a.name, a.realm, a.region',
			'ORDER_BY'	=>  'a.id ASC'
	);
	$sql = $db->sql_build_query('SELECT', $sql_array);
	$result = $db->sql_query($sql);
	
	$i=0;
	$guild_id = 0;
	while ( $row = $db->sql_fetchrow($result) )
	{
		$guild  = $row['id'];
	}
	$db->sql_freeresult($result);
	return $guild;
}
?>