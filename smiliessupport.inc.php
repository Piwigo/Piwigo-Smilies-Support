<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

// add smilies button to the comment field
function set_smiliessupport($prefilter='picture')
{
  global $conf, $template, $page;
  
  load_language('plugin.lang', SMILIES_PATH);
  $conf_smiliessupport = unserialize($conf['smiliessupport']);

  $template->assign(array(
    'SMILIES_PATH' => SMILIES_PATH,
    'REPRESENTANT' => SMILIES_PATH.'smilies/'.$conf_smiliessupport['folder'].'/'.$conf_smiliessupport['representant'],
    'smiliesfiles' => get_smilies($conf_smiliessupport),
  ));
  
  $template->set_prefilter($prefilter, 'set_smiliessupport_prefilter');  
}

function set_smiliessupport_prefilter($content, &$smarty)
{
  $search = '#(<div id="guestbookAdd">|<div id="commentAdd">)#';
  $replace = file_get_contents(SMILIES_PATH.'/template/smiliessupport_page.tpl').'$1';
  return preg_replace($search, $replace, $content);
}

// return an array with available smilies (name and path) ## must received the unserialized configuration array
function get_smilies($conf_smiliessupport)
{
  $accepted_ext = array('gif', 'jpg', 'png');
  
  if ($handle = opendir(SMILIES_PATH.'smilies/'.$conf_smiliessupport['folder']))
  {
    $i = 1;
    while (false !== ($file = readdir($handle)))
    {
      if ($file != '.' AND $file != '..' AND in_array(get_extension($file), $accepted_ext))
      {
        $smilies[] = array(
          'PATH' => SMILIES_PATH.'smilies/'.$conf_smiliessupport['folder'].'/'.$file,
          'TITLE' => ':'.get_filename_wo_extension($file).':',
          'TR' => ($i>0 AND $i%$conf_smiliessupport['cols'] == 0) ? '</tr><tr>' : null,
        );
        $i++;
      }
    }
    
    closedir($handle);
    return $smilies;
  } 
  else 
  {
    return false;
  }
}

// parse smilies
function SmiliesParse($str)
{
  global $conf;

  $conf_smiliessupport = unserialize($conf['smiliessupport']);
  $folder = get_root_url().SMILIES_PATH.'smilies/'.$conf_smiliessupport['folder'];
  $def_path = SMILIES_PATH.'smilies/'.$conf_smiliessupport['folder'].'/smilies.txt';
  $accepted_ext = array('gif', 'jpg', 'png');
  $str = ' '.$str;
  
  if ($handle = opendir(SMILIES_PATH.'smilies/'.$conf_smiliessupport['folder']))
  {
    while (false !== ($file = readdir($handle)))
    { 
      if ($file != "." && $file != ".." && in_array(get_extension($file), $accepted_ext)) 
      {
        $filename = get_filename_wo_extension($file);
        $v = ':'.$filename.':'; 
        $s = '<img src="'.$folder.'/'.$file.'" alt=":'.$filename.':"/>';
        $str = str_replace($v, $s, $str);
      }
    }
    
    closedir($handle);
  }
  
  if (file_exists($def_path))
  {
    $def = file($def_path);
    foreach($def as $v)
    {
      $v = trim($v);
      if (preg_match('#^([^\t]+)[ \t]+(.+)$#', $v, $matches)) 
      {  
        $filename = get_filename_wo_extension($matches[2]);
        $v = '#([^"])'.preg_quote($matches[1],'/').'#';          
        $t = '$1<img src="'.$folder.'/'.$matches[2].'" alt=":'.$filename.':"/>';
        $str = preg_replace($v, $t, $str);
      }
    }
  } 
  
  return trim($str);
}

?>