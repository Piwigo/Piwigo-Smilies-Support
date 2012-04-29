<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

global $conf, $template;
load_language('plugin.lang', SMILIES_PATH);

if (strpos($conf['smiliessupport'],',') !== false)
{
  include(SMILIES_PATH .'maintain.inc.php');
  plugin_activate();
}

$conf_smiliessupport = unserialize($conf['smiliessupport']);

// Enregistrement de la configuration
if (isset($_POST['submit']))
{
  // the smilies.txt file is not saved if the directory is changed
  if (isset($_POST['text1']) AND $_POST['text1'] != $conf_smiliessupport['folder']) 
  {
    $not_save_file = true;
    
    $handle = opendir(SMILIES_PATH.'smilies/'.$_POST['text1']);
    $i = 0;
    while (false !== ($file = readdir($handle)))
    {
      if ( $file != '.' AND $file != '..' AND in_array(get_extension($file), array('gif', 'jpg', 'png')) )
      {
        $_POST['text3'] = $file;
        closedir($handle);
        break;
      }
    }
  }
  
  // new configuration
  $conf_smiliessupport = array(
    'folder' => isset($_POST['text1']) ? $_POST['text1'] : 'crystal',
    'cols' => isset($_POST['text2']) ? $_POST['text2'] : '6',
    'representant' => isset($_POST['text3']) ? $_POST['text3'] : 'smile.png',
  );
  if (empty($_POST['text'])) $_POST['text'] = ':)    smile.png';
    
  conf_update_param('smiliessupport', serialize($conf_smiliessupport));
  array_push($page['infos'], l10n('Information data registered in database'));
  
  // new definitions file
  if (!isset($not_save_file)) 
  {
    $smilies_file = SMILIES_PATH.'smilies/'.$conf_smiliessupport['folder'].'/smilies.txt';      

    if (file_exists($smilies_file)) {
      @copy($smilies_file, get_filename_wo_extension($smilies_file).'.bak');
    }
    
    if (@!file_put_contents($smilies_file, stripslashes($_POST['text']))) {  
      array_push($page['errors'], l10n('File/directory read error').' : '.$smilies_file);
    }
  }
}

// check if the representant exists
if (!file_exists(SMILIES_PATH.'smilies/'.$conf_smiliessupport['folder'].'/'.$conf_smiliessupport['representant'])) {
  array_push($page['errors'], l10n('File/directory read error').' : smilies/'.$conf_smiliessupport['folder'].'/'.$conf_smiliessupport['representant']);
}

// get available sets
$sets = array();
$handle = opendir(SMILIES_PATH.'smilies/');
while (false !== ($file = readdir($handle)))
{ 
  if ( $file != '.' && $file != '..' && is_dir(SMILIES_PATH.'smilies/'.$file) )
  {
    $sets[$file] = $file;
  }
}
closedir($handle);

// get available smilies
$smilies_table = $smilies = array();
$handle = opendir(SMILIES_PATH.'smilies/'.$conf_smiliessupport['folder']);
$i = 1;
while (false !== ($file = readdir($handle)))
{
  if ( $file != '.' AND $file != '..' AND in_array(get_extension($file), array('gif', 'jpg', 'png')) )
  {
    $smilies[$file] = $file;
    $smilies_table[] = array(
      'PATH' => SMILIES_PATH.'smilies/'.$conf_smiliessupport['folder'].'/'.$file,
      'TITLE' => ':'.get_filename_wo_extension($file).':',
      'TR' => ($i>0 AND $i%$conf_smiliessupport['cols'] == 0) ? '</tr><tr>' : null,
    );
    $i++;
  }
}
closedir($handle);

$template->assign(array(
  'TEXT1_VALUE' => $conf_smiliessupport['folder'],
  'TEXT2_VALUE' => $conf_smiliessupport['cols'],
  'TEXT3_VALUE' => $conf_smiliessupport['representant'],
  'sets' => $sets,
  'smiliesfiles' => $smilies_table,
  'smilies' => $smilies,
));

// get the content of definitions file
$smilies_file = SMILIES_PATH.'smilies/'.$conf_smiliessupport['folder'].'/smilies.txt';
if (file_exists($smilies_file)) {
  $content_file = file_get_contents($smilies_file);
  $template->assign(array('CONTENT_FILE' => $content_file));
}
  
$template->assign('SMILIES_PATH', SMILIES_PATH);
$template->set_filename('smiliessupport_conf', dirname(__FILE__).'/template/smiliessupport_admin.tpl');
$template->assign_var_from_handle('ADMIN_CONTENT', 'smiliessupport_conf');

?>