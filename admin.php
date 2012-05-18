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
  if (isset($_POST['folder']) AND $_POST['folder'] != $conf_smiliessupport['folder']) 
  {
    $not_save_file = true;
    
    if (!file_exists(SMILIES_PATH.'smilies/'.$_POST['folder'].'/'.$_POST['representant']))
    {
      $handle = opendir(SMILIES_PATH.'smilies/'.$_POST['folder']);
      $i = 0;
      while (false !== ($file = readdir($handle)))
      {
        if ( $file != '.' AND $file != '..' AND in_array(get_extension($file), array('gif', 'jpg', 'png')) )
        {
          $_POST['representant'] = $file;
          closedir($handle);
          break;
        }
      }
    }
  }
  
  // new configuration
  $conf_smiliessupport = array(
    'folder' => isset($_POST['folder']) ? $_POST['folder'] : 'crystal',
    'cols' => isset($_POST['cols']) ? $_POST['cols'] : '6',
    'representant' => isset($_POST['representant']) ? $_POST['representant'] : 'smile.png',
  );
  if (empty($_POST['text'])) $_POST['text'] = '';
    
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
  'FOLDER' => $conf_smiliessupport['folder'],
  'COLS' => $conf_smiliessupport['cols'],
  'REPRESENTANT' => $conf_smiliessupport['representant'],
  'sets' => $sets,
  'smiliesfiles' => $smilies_table,
  'smilies' => $smilies,
));

// get the content of definitions file
$smilies_file = SMILIES_PATH.'smilies/'.$conf_smiliessupport['folder'].'/smilies.txt';
if (file_exists($smilies_file))
{
  $content_file = file_get_contents($smilies_file);
  $template->assign(array('CONTENT_FILE' => $content_file));
}
  
$template->assign('SMILIES_PATH', SMILIES_PATH);
$template->set_filename('smiliessupport_conf', dirname(__FILE__).'/template/smiliessupport_admin.tpl');
$template->assign_var_from_handle('ADMIN_CONTENT', 'smiliessupport_conf');

?>