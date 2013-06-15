<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

// add smilies button to the comment field
function set_smiliessupport($prefilter='picture', $textarea_id='contentid')
{
  global $conf, $template;

  $template->assign(array(
    'SMILIES_PATH' => SMILIES_PATH,
    'SMILIES_ID' =>   $textarea_id,
    'REPRESENTANT' => SMILIES_DIR.$conf['smiliessupport']['folder'].'/'.$conf['smiliessupport']['representant'],
    'smiliesfiles' => get_smilies(),
  ));
  
  $template->set_prefilter($prefilter, 'set_smiliessupport_prefilter');  
}

function set_smiliessupport_prefilter($content, &$smarty)
{
  $search = '#(<div id="guestbookAdd">|<div id="commentAdd">|<div class="contact">)#';
  $replace = file_get_contents(SMILIES_PATH.'/template/smiliessupport_page.tpl').'$1';
  return preg_replace($search, $replace, $content);
}

// return an array with available smilies (name and path)
function get_smilies()
{
  global $conf;
  
  if ($handle = opendir(SMILIES_DIR.$conf['smiliessupport']['folder']))
  {
    $i = 1;
    while (false !== ($file = readdir($handle)))
    {
      if ($file != '.' and $file != '..' and in_array(get_extension($file), $conf['smiliessupport']['ext']))
      {
        $smilies[] = array(
          'PATH' => SMILIES_DIR.$conf['smiliessupport']['folder'].'/'.$file,
          'TITLE' => ':'.get_filename_wo_extension($file).':',
          'TR' => ($i>0 and $i%$conf['smiliessupport']['cols'] == 0) ? '</tr><tr>' : null,
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
  
  $root_path = get_absolute_root_url();
  $folder = SMILIES_DIR.$conf['smiliessupport']['folder'].'/';
  $str = ' '.$str;
  
  if ($handle = opendir($folder))
  {
    while (false !== ($file = readdir($handle)))
    { 
      if ($file != "." && $file != ".." && in_array(get_extension($file), $conf['smiliessupport']['ext'])) 
      {
        $filename = get_filename_wo_extension($file);
        $v = ':'.$filename.':'; 
        $s = '<img src="'.$root_path.$folder.$file.'" alt=":'.$filename.':"/>';
        $str = str_replace($v, $s, $str);
      }
    }
    
    closedir($handle);
  }
  
  if (file_exists($folder.'smilies.txt'))
  {
    foreach (file($folder.'smilies.txt', FILE_IGNORE_NEW_LINES) as $v)
    {
      $v = trim($v);
      if (preg_match('#^([^\s]+)[\s]+(.+)$#', $v, $matches)) 
      {  
        $filename = get_filename_wo_extension($matches[2]);
        $v = '#([^"])'.preg_quote($matches[1],'/').'#';          
        $t = '$1<img src="'.$root_path.$folder.$matches[2].'" alt=":'.$filename.':"/>';
        $str = preg_replace($v, $t, $str);
      }
    }
  } 
  
  return trim($str);
}

?>