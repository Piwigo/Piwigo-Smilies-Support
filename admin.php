<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

global $conf, $template;
load_language('plugin.lang', SMILIES_PATH);
$conf_smiliessupport = explode("," , $conf['smiliessupport']);

// Enregistrement de la configuration
if (isset($_POST['submit']))
{
	// the smilies.txt file is not saved if the directory is changed
	if (isset($_POST['text1']) AND $_POST['text1'] != $conf_smiliessupport[0]) 
	{
		$not_save_file = true;
	}
	
	// new configuration
	$conf_smiliessupport = array(
		isset($_POST['text1']) ? $_POST['text1'] : 'plugins/SmiliesSupport/smilies_1',
		isset($_POST['text2']) ? $_POST['text2'] : '6',
		isset($_POST['text3']) ? $_POST['text3'] : 'smile.png',
	);
	if (empty($_POST['text'])) $_POST['text'] = ':)		smile.png';
		
    $new_value_smiliessupport = implode(",", $conf_smiliessupport);
    $query = "UPDATE " . CONFIG_TABLE . "
		SET value='" . $new_value_smiliessupport . "'
		WHERE param='smiliessupport'";
    pwg_query($query);
	
	// new definitions file
	if (!isset($not_save_file)) 
	{
		$smilies_file = PHPWG_ROOT_PATH.$conf_smiliessupport[0].'/smilies.txt';	    

		if (file_exists($smilies_file)) {
			@copy($smilies_file, get_filename_wo_extension($smilies_file).'.bak');
		}
		
		if (@file_put_contents($smilies_file, stripslashes($_POST['text']))) {  
			$page['infos'][] = l10n('Information data registered in database');
		} else {
			$page['errors'][] = l10n('File/directory read error').' : '.$smilies_file;
		}
	}
}

// check if the representant exists
if (!file_exists(PHPWG_ROOT_PATH.$conf_smiliessupport[0].'/'.$conf_smiliessupport[2])) {
	$page['errors'][] = l10n('File/directory read error').' : '.$conf_smiliessupport[0].'/'.$conf_smiliessupport[2];
}

$template->assign(array(
	'TEXT1_VALUE' => $conf_smiliessupport[0],
	'TEXT2_VALUE' => $conf_smiliessupport[1],
	'TEXT3_VALUE' => $conf_smiliessupport[2],
));

// build the table of smilies
include_once(SMILIES_PATH . '/smiliessupport.inc.php');
$template->assign('smiliesfiles', get_smilies($conf_smiliessupport));

// get the content of definitions file
$smilies_file = PHPWG_ROOT_PATH.$conf_smiliessupport[0].'/smilies.txt';
if (file_exists($smilies_file)) {
	$content_file = file_get_contents($smilies_file);
	$template->assign(array('CONTENT_FILE' => $content_file));
}
	
$template->assign('SMILIES_PATH', SMILIES_PATH);
$template->set_filename('smiliessupport_conf', dirname(__FILE__).'/template/smiliessupport_admin.tpl');
$template->assign_var_from_handle('ADMIN_CONTENT', 'smiliessupport_conf');

?>