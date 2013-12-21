<?php
defined('SMILIES_ID') or die('Hacking attempt!');

// return an array with available smilies (name and path)
function get_smilies()
{
  global $conf;
  
  if ($handle = opendir(SMILIES_DIR.$conf['smiliessupport']['folder']))
  {
    $i = 1;
    while (false !== ($file = readdir($handle)))
    {
      if ($file != '.' and $file != '..' and
          in_array(get_extension($file), $conf['smiliessupport_ext'])
        )
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

function get_first_file($path, $ext=null)
{
  $path  = rtrim($path, '/').'/';
  $handle = opendir($path);
  
  while (false !== ($file=readdir($handle)))
  {
    if ($file!='.' and $file!='..' and is_file($path.$file) and
        (!is_array($ext) or in_array(get_extension($file), $ext))
      )
    {
      closedir($handle);
      return $file;
    }
  }
  
  closedir($handle);
  return null;
}