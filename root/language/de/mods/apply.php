<?php
/**
* language file Application form German
* @author Sheeper, Sajaki
* @package bbDkp
* @copyright (c) 2009 bbDkp <http://code.google.com/p/bbdkp/>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 1.3.7
* @translation various authors, killerpommes
* 
*/
 
/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* DO NOT CHANGE
*/
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

/*  here you change the fixed strings  for the recruitment page */

$lang = array_merge($lang, array(

/***** Questionnaire ******/
'APPLY_MENU' => 'Bewerbungen',
'APPLY_TITLE' => 'Bewerbungs-Formular',
'APPLY_INFO' => '[size=150]Willkommen und schön, dass du dich entschieden hast, bei uns ein neues zu Hause für deinen Charakter zu suchen. 
Um dich bei uns zu bewerben, beantworte bitte die unten aufgeführten Fragen. Gib deinen Charakternamen exakt an.[/size]  ',
'APPLY_PUBLICQUESTION' => 'Öffentliche Bewerbung ?', 
'APPLY_REQUIRED'  => 'Du musst alle Pflichtfelder ausfüllen. ', 
'MANDATORY'	=> 'Pflichtfeld',	
'APPLY_REALM' => 'Server (leer für ',
'APPLY_NAME' => ' Charaktername: ',
'APPLY_QUESTION'  => 'Frage ',
'APPLY_ANSWER'  => 'Antwort ',
'APPLY_REALM1' => 'Server: ',
'APPLY_LEVEL'  => 'Level: ',
'APPLY_CLASS'  => 'Klasse: ',
'APPLY_RACE'  => 'Rasse: ',
'APPLY_TALENT'  => 'Talente: ',
'APPLY_PROFF'  =>  'Berufe: ',
'TEMPLATE'	=> 'Schablone', 
'CONFIRM_DELETE_TEMPLATE'	=> 'Bitte bestätige die Löschung von Schablone %s. ', 
'ALERT_TEMPLATENAME_EMPTY'	=> 'Name der Vorlage kann nicht leer sein',

/***** ACP Armory settings *****/
'APPLY_ACP_TEMPLATESETTING'	=> 'Formular Einstellungen',
'APPLY_WELCOME'			=> 'Einladungstext',
'APPLY_WELCOME_EXPLAIN'		=> 'BBcodes werden unterstützt. ',
'APPLY_ACP_CHARNAME' 		=> 'Charaktername',
'APPLY_ACP_REALM' 		=> 'Server',
'APPLY_ACP_REGION' 			=> 'Region',
'APPLY_ACP_APPTEMPLATEUPD'	=> 'Aktualisiere Bewerbungsbogen',
'APPLY_ACP_APPTEMPLATELIST_EXPLAIN'	=> 'Die Vorlage ID wird benötigt als Parameter für apply.php : zb Schablone id 7 wird aufgerufen bei url http://www.myguild.org/apply.php?template_id=7; mache zoviel Schnittstellen wie du Vorlagen angemacht hast. ',
'TEMPLATE_ID'				=> 'Template ID', 

/***** ACP template settings *****/
'APPLY_ACP_FORUM_PREF_EXPLAIN'		=> 'Entscheidet in welches Forum die Bewerbung geschrieben wird.',
'APPLY_ACP_FORUM_PUB'		=> 'Bewerbungs Forum (öffentlich) ',
'APPLY_ACP_PUBLIC'			=> 'öffentlich',
'APPLY_ACP_GUESTPOST' 		=> 'Können Gäste schreiben? :',
'APPLY_ACP_GUESTPOST_EXPLAIN' 	=> 'Wenn die Option aktiviert ist, vergiss nicht die Option "Aktiviere visuelle Bestätigung für Gast Beiträge:" auf "Ja" zu setzen.' ,  
'ACP_APPLY_MANDATORY'  		=> 'erforderlich',
'ACP_APPLY_HEADER'  		=> 'Kopfzeile',
'ACP_APPLY_QUESTION'  		 => 'Frage',
'ACP_APPLY_CONTENTS'  		=> 'Wähle Optionen',
'ACP_APPLY_WHATGUILD_EXPLAIN' => 'Bewerber bekommt den niedrigsten Rank falls er zur Gilde hinzugefügt wird.',
'ACP_APPLY_GNONE'  			 => 'zu Keine hinzufügen',
'ACP_APPLY_GSEL'  			 => 'zu ausgewählte Gilde hinzufügen',
'ACP_DKP_APPLY_EXPLAIN'  	=> 'Hier kannst du alle Einstellungen zum Bewerbungsformular vornehmen.',
'APPLY_ACP_APPTEMPLATENEW'  => 'Bewerbungsvorlage für neue Frage', 
'APPLY_CHGMAND' 			=> 'Ändere bestehende Fragen hier. ',
'APPLY_CHGMAND_EXPLAIN' 	=> 'Ändere die Pflichtprüfung, Reihenfolge, Frage und Art der Eingabe. Grenze verschiedene Optionen durch Komma "," ohne Leerzeichen voneinander ab. Die ersten beiden Fragen sind reserviert.',
'APPLY_ACP_NEWQUESTION' 	=> 'Trage hier neue Fragen ein.',
'APPLY_ACP_NEWQUESTION_EXPLAIN' => 'Prüfe ob Pflichtfeld, gib die Ordnungszahl, Frage und Eingabeart an. Grenze verschiedene Optionen durch Komma "," ohne Leerzeichen voneinander ab.', 
'APPLY_ACP_INPUTBOX' 		=> 'Eingabefeld',	
'APPLY_ACP_TXTBOX' 			=> 'Textbox', 
'APPLY_ACP_TXTBOXBBCODE'	=> 'Textbox mit bbcode',
'APPLY_ACP_SELECTBOX' 		=> 'Auswahlbox (selectbox)',
'APPLY_ACP_RADIOBOX' 		=> 'Auswahlknöpfe (radiobutton)',
'APPLY_ACP_CHECKBOX' 		=> 'Kontrollkästchen (checkbox)',

//warnings
'APPLY_ACP_RETURN' 		=> '<h3>Zurück zur Bewerbungskonfiguration.</h3>',
'APPLY_ACP_REALMBLANKWARN' 	=> 'Server Feld darf nicht leer sein.', 
'APPLY_ACP_SETTINGSAVED' 	=> 'allgemeine Bewerbungseinstellungen gespeichert',
'APPLY_NO_GUILD'		=> 'keine Gilde', 
//upd
'APPLY_ACP_TWOREALM' 		=> 'Du kannst keine 2 Server oder Charakternamen einrichten.', 
'APPLY_ACP_QUESTUPD' 		=> 'Bewerbungsfragen aktualisiert',
//addnew
Ver'APPLY_ACP_ORDQUEST' 		=> 'Du musst die Reihenfolge, Fragen und Optionen ausfüllen bevor die Frage gespeichert werden darf.',
'APPLY_ACP_QUESTNOTADD' 	=> 'Fehler : Frage wurde nicht gespeichert !', 
'APPLY_ACP_QUESTNADD' 		=> 'Neue Frage wurde gespeichert !',   
'APPLY_ACP_EXPLAINOPTIONS' 	=> 'Begrenze einzelne Optionen mit Komma "," ohne Leerzeichen.',  
'APPLY_ACP_TEMPLATEADD' 	=> 'Neue Schablone gespeichert.', 
'REQUIRED'					=> 'Erforderlich', 

/** ACP settings for posting template **/
'APPLY_COLORSETTINGS' 		=> 'Farbinstellungen',
'APPLY_POST_ANSWERCOLOR' 	=> 'Antwortfarbe',
'APPLY_POST_QUESTIONCOLOR' 	=> 'Fragenfarbe',
'APPLY_FORMCOLOR'			=> 'Fragebogenfarbe',
'APPLY_POSTCOLOR'			=> 'Farben für Fragebogen und Bewerbungsbeitrage',
'APPLY_POSTCOLOR_EXPLAIN' 	=> 'Farben angezeigt im Fragebogen und in den Beitragen. Wenn du einen eher dunkler Style gebrauchst, kann hier eine Kontrastierende Farbe gewählt werden.',

/** posting template **/
'APPLY_CHAR_OVERVIEW' 		=> 'Charakter',
'APPLY_CHAR_MOTIVATION' 	=> 'Motivation',

'APPLY_CHAR_NAME' 	=> '[color=%s][b]Charaktername : [/b][/color]%s',
'APPLY_CHAR_LEVEL' 	=> '[color=%s]Charakterlevel : [/color]%s',  
'APPLY_CHAR_CLASS' 	=> '[color=%s]Charakterklasse : [/color]%s' ,
'APPLY_CHAR_PROFF' 	=> '[color=%s][u]Berufe des Charakters:[/u][/color]
%s',
'APPLY_CHAR_BUILD' 	=> '[color=%s][u]Talent Verteilung : [/u][/color]%s',
'APPLY_CHAR_URL' => '[color=%s][/color][url=%s]WoW Armory Link[/url]', 
'APPLY_ERROR_NAME'  =>  'Fehler : Name muss alphabetisch (a-zA-ZàäåâÅÂçÇéèëêïÏîÎæŒæÆÅóòÓÒöÖôÔøØüÜ sind erlaubt). ',
'APPLY_REQUIRED_LEVEL'  => 'Level ist erforderlich. ', 
'APPLY_REQUIRED_NAME'	=> 'Name ist erforderlich. ', 

'RETURN_APPLY'  =>  'Zurück zum Fragebogen.',

/** installer **/
'APPLY_INSTALL_MOD' =>  'Bewerbungs Mod Version %s erfolgreich installiert. ',
'APPLY_UNINSTALL_MOD' =>  'Bewerbungs Mod Version %s erfolgreich deinstalliert. ',
'APPLY_UPD_MOD' =>  'Bewerbungs Mod erfolgreich zu Version %s aktualisiert',
'UMIL_CACHECLEARED' => 'Template, Theme, Imageset Caches geleert', 
'APPLY'		=> 'Bewerbe', 
'ERROR_MINIMUM133' => 'Minimum Version benötigt ist 1.3.3',
'DEFAULT_Q1' => 'Kannst du uns etwas über dich erzählen ?', 
'DEFAULT_Q2' => 'Bitte nenne deine Alts.', 
'DEFAULT_Q3' => 'Grund zum verlassen deiner vorherige Gilde ?', 
'DEFAULT_Q4' => 'Was bringst du uns und weshalb sollten wir dir einladen ?', 
'DEFAULT_Q5' => 'Kommentiere deinen Charakteraufbau, Glyphs, und Ausrüstung.', 
'DEFAULT_Q6' => 'Beschreibe deine Raiderfahrung', 
'DEFAULT_Q7' => 'Füge ein link zur Raid logs zu.', 
'DEFAULT_Q8' => 'Kreuze die Tage an bei welche du generell verfügbar bist', 
'DEFAULT_Q9' => 'Bist du enverstanden mit folgende Raidzeiten 19:30 bis 23:00 Serverzeit (UTC+1) ?', 
'DEFAULT_Q10' => 'Ist es gut genug für ein hohes FPS ? Nenne den Spec' , 
'DEFAULT_Q11' => 'Bist du mehrjärig ? Kreuz Ja oder Nein an.', 
'DEFAULT_H1' => 'Informationen zur Person',  
'DEFAULT_H2' => 'Alts',  
'DEFAULT_H3' => 'Gildenhistorie',  
'DEFAULT_H4' => 'Motivation',  
'DEFAULT_H5' => 'Aufbau, Glyphs, Ausrüstung',  
'DEFAULT_H6' => 'Raiderfahrung',  
'DEFAULT_H7' => 'Raking und Wol Logs',  
'DEFAULT_H8' => 'Raid Tage',  
'DEFAULT_H9' => 'Raidzeiten',  
'DEFAULT_H10' => 'Computer Connection info',  
'DEFAULT_H11' => 'Alter',  
'DEFAULT_O8' => 'montag,dienstag,mittwoch,donnerstag,freitag,samstag,sonntag',  
'DEFAULT_O11' => 'ja,nein',  


));

?>
