<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

function get_first_file($path, $ext=null)
{
  $path  = rtrim($path, '/').'/';
  $handle = opendir($path);
  
  while ( false !== ($file=readdir($handle)) )
  {
    if ( $file!='.' && $file!='..' && is_file($path.$file) && (!is_array($ext) || in_array(get_extension($file), $ext)) )
    {
      closedir($handle);
      return $file;
    }
  }
  
  closedir($handle);
  return null;
}

function smiliessupport_action()
{
  if (!isset($_GET['action'])) return;
  if (strpos($_GET['action'], 'ss_') !== 0) return;
  
  global $conf;
  
  $folder = SMILIES_DIR . ltrim($_GET['folder'], '/') . '/';
  
  if ($_GET['action'] == 'ss_reset')
  {
    @unlink($folder.'smilies-custom.txt');
    $_GET['action'] = 'ss_list';
  }
  
  if ($_GET['action'] == 'ss_list')
  {
    $short = array();
    if (file_exists($folder.'smilies-custom.txt'))
    {
      $file = file($folder.'smilies-custom.txt', FILE_IGNORE_NEW_LINES);
    }
    else if (file_exists($folder.'smilies.txt'))
    {
      $file = file($folder.'smilies.txt', FILE_IGNORE_NEW_LINES);
    }
    if (!empty($file))
    {
      foreach ($file as $v)
      {
        if (preg_match('#^([^\s]+)[\s]+(.+)$#', trim($v), $matches)) 
        {
          $short[ $matches[2] ][] = $matches[1];
        }
      }
    }

    $smilies = array();
    $handle = opendir($folder);
    while (false !== ($file = readdir($handle)))
    {
      if ( $file != '.' && $file != '..' && in_array(get_extension($file), $conf['smiliessupport']['ext']) )
      {
        $smilies[$file] = array('title'=>':'.get_filename_wo_extension($file).':', 'file'=>$file, 'short'=>@$short[$file]);
      }
    }
    closedir($handle);
    
    echo json_encode(array('path'=>$folder, 'smilies'=>$smilies));
  }
  
  exit;
}

?>