<?php
/*
Plugin Name: Smilies Support
Version: auto
Description: Allow add Smilies for comments and descriptions.
Plugin URI: auto
Author: Atadilo & P@t & Mistic
*/

defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

define('SMILIES_ID',      basename(dirname(__FILE__)));
define('SMILIES_PATH',    PHPWG_PLUGINS_PATH . SMILIES_ID . '/');
define('SMILIES_DIR',     SMILIES_PATH . 'smilies/');
define('SMILIES_ADMIN',   get_root_url() . 'admin.php?page=plugin-' . SMILIES_ID);
define('SMILIES_VERSION', 'auto');


include_once(SMILIES_PATH.'include/functions.inc.php');
include_once(SMILIES_PATH.'include/events.inc.php');


add_event_handler('init', 'init_smiliessupport');

if (defined('IN_ADMIN'))
{
  add_event_handler('init', 'smiliessupport_action');
  add_event_handler('get_admin_plugin_menu_links', 'smiliessupport_admin_menu');
}
else
{
  add_event_handler('loc_after_page_header', 'add_smiliessupport', EVENT_HANDLER_PRIORITY_NEUTRAL+2);
}

add_event_handler('render_comment_content', 'SmiliesParse', EVENT_HANDLER_PRIORITY_NEUTRAL+10);
add_event_handler('render_contact_content', 'SmiliesParse');


function init_smiliessupport()
{
  global $conf;
  
  include_once(SMILIES_PATH . 'maintain.inc.php');
  $maintain = new SmiliesSupport_maintain(SMILIES_ID);
  $maintain->autoUpdate(SMILIES_VERSION, 'install');
  
  $conf['smiliessupport'] = unserialize($conf['smiliessupport']);
  $conf['smiliessupport_ext'] = array('gif', 'jpg', 'png', 'GIF', 'JPG', 'PNG');
  
  load_language('plugin.lang', SMILIES_PATH);
}
