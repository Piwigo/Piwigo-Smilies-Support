<?php
/*
Plugin Name: Smilies Support
Version: auto
Description: Allow add Smilies for comments and descriptions.
Plugin URI: http://piwigo.org/ext/extension_view.php?eid=159
Author: Atadilo & P@t & Mistic
*/

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

define('SMILIES_DIR',  basename(dirname(__FILE__)));
define('SMILIES_PATH', PHPWG_PLUGINS_PATH . SMILIES_DIR . '/');

include_once(SMILIES_PATH.'smiliessupport.inc.php');

add_event_handler('render_comment_content', 'SmiliesParse', 60);
add_event_handler('loc_after_page_header', 'add_smiliessupport');

function add_smiliessupport() 
{
  global $page, $pwg_loaded_plugins;
  
  // if BBCodeBar is installed let him manage smilies
  if (isset($pwg_loaded_plugins['bbcode_bar'])) return;
  
  if (isset($page['body_id']) AND $page['body_id'] == 'thePicturePage') 
  {
    $prefilter = 'picture';
  }
  else if (
    script_basename() == 'index' and isset($pwg_loaded_plugins['Comments_on_Albums'])
    and isset($page['section']) and $page['section'] == 'categories' and isset($page['category'])
    ) 
  {
    $prefilter = 'comments_on_albums';
  }
  else if (isset($_GET['/guestbook'])) 
  {
    $prefilter = 'index';
  }
  
  if (isset($prefilter))
  {
    set_smiliessupport($prefilter);
  }
}

if (script_basename() == 'admin')
{
  add_event_handler('get_admin_plugin_menu_links', 'smiliessupport_admin_menu');
  function smiliessupport_admin_menu($menu) 
  {
    array_push($menu, array(
      'NAME' => 'Smilies Support',
      'URL' => get_root_url().'admin.php?page=plugin-' . SMILIES_DIR
    ));
    return $menu;
  }
}
?>
