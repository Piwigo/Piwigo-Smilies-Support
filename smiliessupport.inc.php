<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

// add smilies button to the comment field
function set_smiliessupport()
{
  global $conf, $template, $page;
  
  load_language('plugin.lang', SMILIES_PATH);
  $conf_smiliessupport = explode(',' , $conf['smiliessupport']);
  
  $smilies = get_smilies($conf_smiliessupport);
  
  // edit field has different id
  // if (
    // (isset($_GET['action']) AND $_GET['action'] == 'edit_comment') 
    // OR (isset($page['body_id']) AND $page['body_id'] == 'theCommentsPage')
  // ) {
    // $template->assign('smilies_textarea', 'contenteditid');
  // } else {
    // $template->assign('smilies_textarea', 'contentid');
  // }
  $template->assign('smilies_textarea', 'contentid');
  
  $template->assign(array(
    'SMILIES_PATH' => SMILIES_PATH,
    'REPRESENTANT' => PHPWG_ROOT_PATH.$conf_smiliessupport[0].'/'.$conf_smiliessupport[2],
    'smiliesfiles' => $smilies,
  ));
  
  $template->set_prefilter('picture', 'set_smiliessupport_prefilter');  
}

function set_smiliessupport_prefilter($content, &$smarty)
{
  $search = "<label>{'Comment'|@translate}";
  $replace = file_get_contents(SMILIES_PATH.'/template/smiliessupport_page.tpl').$search;
  return str_replace($search, $replace, $content);
}

// return an array with available smilies (name and path) ## must received the unserialized configuration array
function get_smilies($conf_smiliessupport)
{
  $accepted_ext = array('gif', 'jpg', 'png');
  
  if ($handle = opendir(PHPWG_ROOT_PATH.$conf_smiliessupport[0]))
  {
    $i = 1;
    while (false !== ($file = readdir($handle)))
    {
      if ($file != '.' AND $file != '..' AND in_array(get_extension($file), $accepted_ext))
      {
        $smilies[] = array(
          'PATH' => PHPWG_ROOT_PATH.$conf_smiliessupport[0].'/'.$file,
          'TITLE' => ':'.get_filename_wo_extension($file).':',
          'TR' => ($i>0 AND $i%$conf_smiliessupport[1] == 0) ? '</tr><tr>' : null,
        );
        $i++;
      }
    }
    
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

  $conf_smiliessupport = explode("," , $conf['smiliessupport']);
  $def_path = $conf_smiliessupport[0].'/smilies.txt';
  $accepted_ext = array('gif', 'jpg', 'png');
  $str = ' '.$str;
  
  if ($handle = opendir(PHPWG_ROOT_PATH.$conf_smiliessupport[0]))
  {
    while (false !== ($file = readdir($handle)))
    { 
      if ($file != "." && $file != ".." && in_array(get_extension($file), $accepted_ext)) 
      {
        $filename = get_filename_wo_extension($file);
        $v = ':'.$filename.':'; 
        $s = '<img src="'.$conf_smiliessupport[0].'/'.$file.'" alt=":'.$filename.':"/>';
        $str = str_replace($v, $s, $str);
      }
    }
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
        $v = '#[^"]'.preg_quote($matches[1],'/').'#';          
        $t = '<img src="'.$conf_smiliessupport[0].'/'.$matches[2].'" alt=":'.$filename.':"/>';
        $str = preg_replace($v, $t, $str);
      }
    }
  } 
  
  return trim($str);
}

?>