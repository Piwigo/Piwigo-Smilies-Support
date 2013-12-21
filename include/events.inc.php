<?php
defined('SMILIES_ID') or die('Hacking attempt!');

function smiliessupport_admin_menu($menu) 
{
  $menu[] = array(
    'NAME' => 'Smilies Support',
    'URL' => SMILIES_ADMIN,
    );
  return $menu;
}

function add_smiliessupport() 
{
  global $page, $pwg_loaded_plugins, $template, $conf;
  
  if (script_basename() == 'picture') 
  {
    $prefilter = 'picture';
    $textarea_id = 'contentid';
  }
  else if (isset($page['section']))
  {
    if (
      script_basename() == 'index' and isset($pwg_loaded_plugins['Comments_on_Albums'])
      and $page['section'] == 'categories' and isset($page['category'])
      )
    {
      $prefilter = 'comments_on_albums';
      $textarea_id = 'contentid';
    }
    else if ($page['section'] == 'guestbook') 
    {
      $prefilter = 'guestbook';
      $textarea_id = 'contentid';
    }
    else if ($page['section'] == 'contact') 
    {
      $prefilter = 'contactform';
      $textarea_id = 'cf_content';
    }
  }
  
  if (!isset($prefilter))
  {
    return;
  }

  $template->assign(array(
    'SMILIES_PATH' => SMILIES_PATH,
    'SMILIES' => array(
      'textarea_id' => $textarea_id,
      'representant' => SMILIES_DIR . $conf['smiliessupport']['folder'] . '/' . $conf['smiliessupport']['representant'],
      'files' => get_smilies(),
      ),
    ));
    
  $template->set_filename('smiliessupport', realpath(SMILIES_PATH . 'template/smiliessupport_page.tpl'));
  $template->parse('smiliessupport');
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
      if ($file != "." && $file != ".." && in_array(get_extension($file), $conf['smiliessupport_ext'])) 
      {
        $filename = get_filename_wo_extension($file);
        $v = ':'.$filename.':'; 
        $s = '<img src="'.$root_path.$folder.$file.'" alt=":'.$filename.':"/>';
        $str = str_replace($v, $s, $str);
      }
    }
    
    closedir($handle);
  }
  
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
  else if ($_GET['action'] == 'ss_list')
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
      if ( $file != '.' && $file != '..' && in_array(get_extension($file), $conf['smiliessupport_ext']) )
      {
        $smilies[$file] = array('title'=>':'.get_filename_wo_extension($file).':', 'file'=>$file, 'short'=>@$short[$file]);
      }
    }
    closedir($handle);
    
    echo json_encode(array('path'=>$folder, 'smilies'=>$smilies));
  }
  
  exit;
}