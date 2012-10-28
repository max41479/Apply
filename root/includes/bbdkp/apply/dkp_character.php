<?php
/**
* bbdkp Apply core class
*
* @package bbDkp.includes
* @version $Id$
* @copyright (c) 2010 bbDkp <http://code.google.com/p/bbdkp/>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @author Kapli, Malfate, Sajaki, Blazeflack, Twizted, Ethereal
*
*
**/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

class apply_post
{
	public $questioncolor = '';
	public $answercolor = '';
	public $gchoice = '';
	public $candidate_guild_id=0;
	public $message= ''; 
}



/**
 * This class describes an applicant
 */
class dkp_character
{
	// character definition
	public $name ='';
	public $region = '';
	public $realm = '';
	public $ModelViewURL;
	public $url;
	public $feedurl; 
	public $level ='';
	public $class = '';
	public $class_color = '';
	public $class_color_exists = '';
	public $classid = 0;
	public $class_image = '';
	public $class_image_exists = '';
	public $talents ='';
	public $race ='';
	public $raceid = 0;
	public $race_image ='';
	public $race_image_exists = '';
	public $game ='';
	
	public $talent1name ='';
	public $talent1 ='';
	public $talent2name ='';
	public $talent2 ='';
	public $professions ='';
	public $genderid = 0;
	public $faction = 0;
	public $guild = '';
	public $guild_id = 0;
	public $guildrank = 0;
	
	//stats in MOP
	
	public $health= 0;
	public $powerType= '';
	public $power=0;
	public $str=0;
	public $agi=0;
	public $sta	=0;
	public $int	=0;
	public $spr	=0;
	public $attackPower	=0;
	public $rangedAttackPower=0;
	public $mastery	=0.0;
	public $masteryRating	=0;
	public $crit=0.0;
	public $critRating	=0;
	public $hitPercent	=0.0;
	public $hitRating	=0;
	public $hasteRating	=0;
	public $expertiseRating	=0;
	public $spellPower	=0;
	public $spellPen	=0;
	public $spellCrit	=0.0;
	public $spellCritRating	=0;
	public $spellHitPercent	=0.0;
	public $spellHitRating	=0;
	public $mana5=0.0;
	public $mana5Combat=0.0;
	public $armor	=0;
	public $dodge	=0.0;
	public $dodgeRating	=0.0;
	public $parry=0.0;
	public $parryRating	=0;
	public $block	=0.0;
	public $blockRating	=0;
	public $pvpResilience	=0.0;
	public $pvpResilienceRating	=0;
	public $mainHandDmgMin	=0.0;
	public $mainHandDmgMax	=0.0;
	public $mainHandSpeed	=0.0;
	public $mainHandDps	=0.0;
	public $mainHandExpertise	=0.0;
	public $offHandDmgMin	=0.0;
	public $offHandDmgMax	=0.0;
	public $offHandSpeed	=0.0;
	public $offHandDps	=0.0;
	public $offHandExpertise	=0.0;
	public $rangedDmgMin=0.0;
	public $rangedDmgMax	=0.0;
	public $rangedSpeed	=0.0;
	public $rangedDps	=0.0;
	public $rangedExpertise	=0.0;
	public $rangedCrit	=0.0;
	public $rangedCritRating =0;
	public $rangedHitPercent=0.0;
	public $rangedHitRating	=0;
	public $pvpPower	=0.0;
	public $pvpPowerRating	=0;
	
	//gear	
	public $item = array();
	public $achievements;
	public $gear = array();
	public $ilvl = array();
	public $gems1 = array();
	public $gems2 = array();
	public $gems3 = array();
	public $ench = array();
	public $gearNameLink = array();
	
	//image
	public $modeltemplate; 
	public $portraitimg;
	
	
}

?>