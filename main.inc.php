<?php
/*
Plugin Name: Smilies Support
Version: auto
Description: Allow add Smilies for comments and descriptions.
Plugin URI: auto
Author: Atadilo & P@t & Mistic
*/

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

define('SMILIES_ID',   basename(dirname(__FILE__)));
define('SMILIES_PATH', PHPWG_PLUGINS_PATH . SMILIES_ID . '/');
define('SMILIES_DIR',  SMILIES_PATH . 'smilies/');

include_once(SMILIES_PATH.'include/functions.inc.php');
include_once(SMILIES_PATH.'include/smiliessupport.inc.php');

add_event_handler('init', 'init_smiliessupport');
add_event_handler('render_comment_content', 'SmiliesParse', 60);
add_event_handler('render_contact_content', 'SmiliesParse');
add_event_handler('loc_after_page_header', 'add_smiliessupport');


function init_smiliessupport()
{
  global $conf;
  
  $conf['smiliessupport'] = unserialize($conf['smiliessupport']);
  $conf['smiliessupport']['ext'] = array('gif', 'jpg', 'png', 'GIF', 'JPG', 'PNG');
  
  load_language('plugin.lang', SMILIES_PATH);
}

function add_smiliessupport() 
{
  global $page, $pwg_loaded_plugins;
  
  // if BBCodeBar is installed let him manage smilies
  if (isset($pwg_loaded_plugins['bbcode_bar'])) return;
  
  if (isset($page['body_id']) AND $page['body_id'] == 'thePicturePage') 
  {
    $prefilter = 'picture';
    $textarea_id = 'contentid';
  }
  else if (
    script_basename() == 'index' and isset($pwg_loaded_plugins['Comments_on_Albums'])
    and isset($page['section']) and $page['section'] == 'categories' and isset($page['category'])
    ) 
  {
    $prefilter = 'comments_on_albums';
    $textarea_id = 'contentid';
  }
  else if (isset($page['section']) and $page['section'] == 'guestbook') 
  {
    $prefilter = 'index';
    $textarea_id = 'contentid';
  }
  else if (isset($page['section']) and $page['section'] == 'contact') 
  {
    $prefilter = 'index';
    $textarea_id = 'cf_content';
  }
  
  if (isset($prefilter))
  {
    set_smiliessupport($prefilter, $textarea_id);
  }
}

if (script_basename() == 'admin')
{
  add_event_handler('get_admin_plugin_menu_links', 'smiliessupport_admin_menu');
  add_event_handler('init', 'smiliessupport_action');
  
  function smiliessupport_admin_menu($menu) 
  {
    array_push($menu, array(
      'NAME' => 'Smilies Support',
      'URL' => get_root_url().'admin.php?page=plugin-' . SMILIES_ID
    ));
    return $menu;
  }
}

?>