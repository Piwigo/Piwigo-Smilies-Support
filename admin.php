<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

global $conf, $template;
load_language('plugin.lang', SMILIES_PATH);
$conf_smiliessupport = explode("," , $conf['smiliessupport']);

// Enregistrement de la configuration
if (isset($_POST['submit']))
{
	// the smilies.txt file is not saved if the directory is changed
	if (isset($_POST['text1']) AND $_POST['text1'] != $conf_smiliessupport[0]) {
		$not_save_file = true;
	}
	
	$conf_smiliessupport = array(
		isset($_POST['text1']) ? $_POST['text1'] : 'plugins/SmiliesSupport/smilies',
		isset($_POST['text2']) ? $_POST['text2'] : '5',
		isset($_POST['text3']) ? $_POST['text3'] : 'sourire.gif',
	);
	
	if (empty($_POST['text'])) $_POST['text'] = ':)		sourire.gif';
		
    $new_value_smiliessupport = implode (",", $conf_smiliessupport);
    $query = 'UPDATE ' . CONFIG_TABLE . '
		SET value="' . $new_value_smiliessupport . '"
		WHERE param="smiliessupport"';
    pwg_query($query);
    
	if (!isset($not_save_file)) {
		$smilies_file = PHPWG_ROOT_PATH.$conf_smiliessupport[0].'/smilies.txt';	    

		if (file_exists($smilies_file)) {
			@copy($smilies_file, get_filename_wo_extension($smilies_file).'.bak');
		}
		
		if (@file_put_contents($smilies_file, stripslashes($_POST['text']))) {  
			$page['infos'][] = l10n('Information data registered in database');
		} else {
			$page['errors'][] = l10n('File/directory read error').' &nbsp; '.$smilies_file;
		}
	}
}

$template->assign(array('TEXT1_VALUE' => $conf_smiliessupport[0]));
$template->assign(array('TEXT2_VALUE' => $conf_smiliessupport[1]));
$template->assign(array('TEXT3_VALUE' => $conf_smiliessupport[2]));

include_once(SMILIES_PATH . '/smiliessupport.inc.php');
$template->assign('SMILIESSUPPORT_PAGE', SmiliesTable($conf_smiliessupport));

$smilies_file = PHPWG_ROOT_PATH.$conf_smiliessupport[0].'/smilies.txt';
$content_file = null;

if (file_exists($smilies_file)) {
	$content_file = file_get_contents($smilies_file);
	$template->assign(array('CONTENT_FILE' => $content_file));
}
	
$template->set_filename('smiliessupport_conf', dirname(__FILE__).'/smiliessupport_admin.tpl');
$template->assign_var_from_handle('ADMIN_CONTENT', 'smiliessupport_conf');

?>