<?php
/**
* language file Wow Application form French
* @author Sajaki
* @package bbDkp
* @copyright (c) 2009 bbDkp <http://code.google.com/p/bbdkp/>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 1.3.8
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
'APPLY_MENU' => 'Candidatures',
'APPLY_TITLE' => 'Formulaire de recrutement',
'APPLY_INFO' => '[size=150]Bienvenu à et merci de ton intérêt porté à notre Guilde. Afin de nous aider à mieux évaluer ta candidature, merci de remplir ce petit formulaire. Entre le nom de caractère exactement comme renseigné dans l’Armurerie.[/size]  ',
'APPLY_PUBLICQUESTION' => 'Candidature publique ?', 
'APPLY_REQUIRED'  => 'Les champs marqués ’*’ ne sont pas optionnels. ', 
'MANDATORY'	=> '*',	
'APPLY_REALM' => 'Serveur (vide pour ',
'APPLY_REALM1' => 'Serveur : ',
'APPLY_NAME' => ' Nom de caractère: ',
'APPLY_QUESTION'  => 'Question ',
'APPLY_ANSWER'  => 'Réponse ',
'APPLY_REALM1' => 'Royaume: ',
'APPLY_LEVEL'  => 'Niveau: ',
'APPLY_CLASS'  => 'Classe: ',
'APPLY_RACE'  => 'Race: ',
'APPLY_TALENT'  => 'Talents: ',
'APPLY_PROFF'  =>  'Proffessions: ',
'TEMPLATE'	=> 'Modèle', 
'CONFIRM_DELETE_TEMPLATE'	=> 'Confirme suppression du modèle %s. ', 
'ALERT_TEMPLATENAME_EMPTY'	=> 'Le nom de modèle ne peut être vide',

/***** ACP Armory settings *****/
'APPLY_ACP_TEMPLATESETTING'	=> 'Règlages gabarit',
'APPLY_WELCOME'				=> 'Message d’acceuil',
'APPLY_WELCOME_EXPLAIN'		=> 'bbcodes sont supportés.',
'APPLY_ACP_CHARNAME' 		=> 'Nom de Caractère',
'APPLY_ACP_REALM' 		=> 'Royaume',
'APPLY_ACP_REGION' 		=> 'Region',
'APPLY_ACP_APPTEMPLATELIST'	=> 'Modèles de formulaires', 
'APPLY_ACP_APPTEMPLATELIST_EXPLAIN'	=> 'L’id du modèle est utilisé comme paramètre dans apply.php : par example modèle 7 est appellé par url http://www.myguild.org/apply.php?template_id=7; fais autant de liens que tu as créés de modèles. ',
'TEMPLATE_ID'				=> 'Modèle', 

/***** ACP template settings ******/
'APPLY_ACP_FORUM_PREF_EXPLAIN'		=> 'décide dans quel forum la candidature sera écrite.',
'APPLY_ACP_FORUM_PUB'		=> 'Forum de recrutement (public) ',
'APPLY_ACP_PUBLIC'			=> 'public',
'APPLY_ACP_GUESTPOST' 		=> 'Permettre les invités de poster ? :',
'APPLY_ACP_GUESTPOST_EXPLAIN' 	=> 'Si cette option est activée, n’oublies pas l’option "Activer la confirmation Anti-spam  pour invités" à "oui".' ,  
'ACP_APPLY_MANDATORY'  		=> 'Obligatoire',
'ACP_APPLY_HEADER'  		=> 'Entête',
'ACP_APPLY_EXPLAIN'  		=> 'Explanation',
'ACP_APPLY_CONTENTS'  		=> 'Contenu',
'ACP_APPLY_WHATGUILD_EXPLAIN' 	 => 'Décide dans quelle Guilde la candidature sera suvegardée.',
'ACP_APPLY_GNONE'  			 => 'Ajouter à ‘Hors Guilde‘',
'ACP_APPLY_GSEL'  			 => 'Ajouter à sa guilde selectionnée.',


'ACP_DKP_APPLY_EXPLAIN'  => 'Ici tu peux saisir toutes les configurations du formulaire de recrutement.',
'APPLY_ACP_APPTEMPLATELINES'  => 'Lignes de modèle', 
'APPLY_CHGMAND' 			=> 'Autres questions existantes ici. ',
'APPLY_CHGMAND_EXPLAIN' 	=> 'Change le flag d’obligation, la séquence, la question et la métode de saisie. le séparateur des options est une virgule "," sans espace. Les permières 2 questions sont réservés.',
'APPLY_ACP_NEWQUESTION' 	=> 'Saisis les nouvelles questions ici.',
'APPLY_ACP_NEWQUESTION_EXPLAIN' => 'Controle si obligatoire, entre la sequence, la question et la métode de saisie. le séparateur des options est une virgule "," sans espace. ', 
'APPLY_ACP_INPUTBOX' 		=> 'champ de saisie',	
'APPLY_ACP_TXTBOX' 			=> 'Texte',
'APPLY_ACP_TXTBOXBBCODE'	=> 'Texte avec bbcode', 
'APPLY_ACP_SELECTBOX' 		=> 'Choix',
'APPLY_ACP_RADIOBOX' 		=> 'Option radio',
'APPLY_ACP_CHECKBOX' 		=> 'checkbox',

//warnings
'APPLY_ACP_RETURN' 		=> '<h3>Retour au formulaire de recrutement</h3>',
'APPLY_ACP_REALMBLANKWARN' 	=> 'Le champ Serveur le peut être vide.', 
'APPLY_ACP_SETTINGSAVED' 	=> 'Règlages enregistrées',
'APPLY_NO_GUILD'		=> 'Pas de Guilde trouvé.', 

//upd
'APPLY_ACP_TWOREALM' 		=> 'Seulement un nom de caractère est permis.', 
'APPLY_ACP_QUESTUPD' 		=> 'questions de formulaire enregistrées',

//addnew
'APPLY_ACP_ORDQUEST' 		=> 'tu dois remplir l’ordre, la question et les options avant d’ajouter.',
'APPLY_ACP_QUESTNOTADD' 	=> 'Erreur: nouvelle question n’a pas été sauvegardée !', 
'APPLY_ACP_QUESTNADD' 		=> 'Nouvelle question sauvegardée !',   
'APPLY_ACP_EXPLAINOPTIONS' 	=> 'Sépare les options avec une virgule "," sans espaces.',  
'APPLY_ACP_TEMPLATEADD' 	=> 'Nouveau modèle ajouté.', 
'REQUIRED'					=> 'Requis', 

/** ACP settings for posting template **/
'APPLY_COLORSETTINGS' 		=> 'Règlages Couleurs',
'APPLY_POST_ANSWERCOLOR' 	=> 'Couleur Réponses',
'APPLY_POST_QUESTIONCOLOR' 	=> 'Couleur Questions',
'APPLY_FORMCOLOR'		=> 'Couleur Questions du Formulaire',
'APPLY_POSTCOLOR'		=> 'Couleurs formulaire et messages de recrutement',
'APPLY_POSTCOLOR_EXPLAIN' 	=> 'Couleur des textes utilisées dans le formulaire et dans les messages. Donc si vous utilisez un style sombre, vous pourrez choisir une couleur qui contraste.',

/** posting template **/
'APPLY_CHAR_OVERVIEW' 		=> 'Application',
'APPLY_CHAR_MOTIVATION' 	=> 'Motivation',
'APPLY_CHAR_NAME' 	=> '[color=%s][b]Nom de Caractère : [/b][/color]%s',
'APPLY_CHAR_LEVEL' 	=> '[color=%s]Niveau : [/color]%s',  
'APPLY_CHAR_CLASS' 	=> '[color=%s]Classe: [/color]%s' ,
'APPLY_CHAR_PROFF' 	=> '[color=%s][u]Proffessions :[/u][/color]
%s',
'APPLY_CHAR_BUILD' 	=> '[color=%s][u]Spécialisation de talents : [/u][/color]%s',
'APPLY_CHAR_URL' => '[color=%s][/color][url=%s]Lien Armurerie[/url]', 
'APPLY_ERROR_NAME'  =>  'Erreur : Nom doit être alphabetique (a-zA-ZàäåâÅÂçÇéèëêïÏîÎæŒæÆÅóòÓÒöÖôÔøØüÜ sont permis). ',
'APPLY_REQUIRED_LEVEL'  => 'Niveau obligatoire',  
'APPLY_REQUIRED_NAME'	=> 'Nom obligatoire.', 
'RETURN_APPLY'  =>  'Retourne au formulaire.',

/****** installer ********/
'APPLY_INSTALL_MOD' =>  'Mod de Recrutement Version %s a été installé avec succes. ',
'APPLY_UNINSTALL_MOD' =>  'Mod de Recrutement Version %s a été déinstallé avec succès. ',
'APPLY_UPD_MOD' =>  'Mod de Recrutement actualisé à la version %s ',
'UMIL_CACHECLEARED' => 'La Cache Template, Theme, et Imageset sont vidées', 
'APPLY'		=> 'Recrute', 
'ERROR_MINIMUM133' => 'Version minimale pour mise à jour est 1.3.3',
'DEFAULT_Q1' => 'Veuillez nous raconter un peu de vous', 
'DEFAULT_Q2' => 'Veuillez nommer vos Alts.', 
'DEFAULT_Q3' => 'Raison d‘avoir quitté votre ancienne guilde ?', 
'DEFAULT_Q4' => 'Quest-ce que vous nous apporteriez et pourquoi on devrait vous inviter ?', 
'DEFAULT_Q5' => 'Commentez votre Charactère, Glyphs, et équipement.', 
'DEFAULT_Q6' => 'Décrivez votre experience Raid', 
'DEFAULT_Q7' => 'Lien vers un log de Raid.', 
'DEFAULT_Q8' => 'Cochez les jours auquelles vous êtes disponible', 
'DEFAULT_Q9' => 'Etes-vous d‘accord avec ces temps de raid ? 19:30 à 23:00 UTC+1 ?', 
'DEFAULT_Q10' => 'Est-ce que votre PC est assez puissant pour soutenir un FPS elevé ?' , 
'DEFAULT_Q11' => 'ëtes-vous majeur ? Cochez oui ou non.', 
'DEFAULT_H1' => 'Informations personelles',  
'DEFAULT_H2' => 'Alts',  
'DEFAULT_H3' => 'Histoire de guilde',  
'DEFAULT_H4' => 'Motivation',  
'DEFAULT_H5' => 'Construction, Glyphs, Equipement',  
'DEFAULT_H6' => 'Experience',  
'DEFAULT_H7' => 'Logs',  
'DEFAULT_H8' => 'Jours de raid',  
'DEFAULT_H9' => 'temps de raid',  
'DEFAULT_H10' => 'Ordinateur et connection',  
'DEFAULT_H11' => 'Age',  
'DEFAULT_O8' => 'lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',  
'DEFAULT_O11' => 'oui,non',  

));

?>
