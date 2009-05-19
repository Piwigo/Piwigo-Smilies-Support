<?php

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

global $conf, $template;

$conf_smiliessupport = explode("," , $conf['smiliessupport']);
// Enregistrement de la configuration
if (isset($_POST['submit']))
{
	if (!isset($_POST['text1'])) $_POST['text1'] = 'plugins/SmiliesSupport/smilies';
	if (!isset($_POST['text2'])) $_POST['text2'] = '5';
	if (!isset($_POST['text3'])) $_POST['text3'] = 'sourire.gif';
	
	$conf_smiliessupport=array(
		$_POST['text1'],
		$_POST['text2'],
		$_POST['text3']);
		
    $new_value_smiliessupport = implode ("," , $conf_smiliessupport);
    $query = '
UPDATE ' . CONFIG_TABLE . '
  SET value="' . $new_value_smiliessupport . '"
  WHERE param="smiliessupport"
  LIMIT 1';
    pwg_query($query);
    
	$smilies_file = PHPWG_ROOT_PATH.$conf_smiliessupport[0].'/smilies.txt';	    

	if (file_exists($smilies_file))
  {
		if  (@copy($smilies_file , get_filename_wo_extension($smilies_file).'.bak'))
    {
			$file = @fopen($smilies_file , "w");
			fwrite($file , stripslashes($content_file = $_POST['text']));
			fclose($file);        
			array_push($page['infos'], l10n('Configuration saved.'));
		}
    else
    {
		  array_push($page['errors'], l10n('Configuration not saved. (copy : '.$smilies_file.' to '.get_filename_wo_extension($smilies_file).'.bak').')' );
		}
	}
  else
  {
		array_push($page['errors'], l10n('Configuration not saved. (file exists : '.$smilies_file.')' ));
	}
}

$template->assign(array('TEXT1_VALUE' => $conf_smiliessupport[0]));
$template->assign(array('TEXT2_VALUE' => $conf_smiliessupport[1]));
$template->assign(array('TEXT3_VALUE' => $conf_smiliessupport[2]));

include_once(dirname(__FILE__) . '/smiliessupport.inc.php');
$template->assign('SMILIESSUPPORT_PAGE', SmiliesTable());

$smilies_file = PHPWG_ROOT_PATH.$conf_smiliessupport[0].'/smilies.txt';
$content_file = '';
if (file_exists($smilies_file))
{
	$content_file = file_get_contents($smilies_file);
	$template->assign(array('CONTENT_FILE' =>$content_file ));
}
	
$template->set_filename('smiliessupport_conf', dirname(__FILE__).'/smiliessupport_admin.tpl');
$template->assign_var_from_handle('ADMIN_CONTENT', 'smiliessupport_conf');

?>