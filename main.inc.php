<?php
/*
Plugin Name: Smilies Support
Version: auto
Description: Allow add Smilies for comments and descriptions.
Plugin URI: http://piwigo.org/ext/extension_view.php?eid=159
Author: Atadilo & P@t
*/

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

include_once(dirname(__FILE__).'/smiliessupport.inc.php');

add_event_handler('render_comment_content', 'SmiliesParse', 60);
add_event_handler('loc_begin_picture', 'set_smiliessupport_page');

if (script_basename() == 'admin')
{
  add_event_handler('get_admin_plugin_menu_links', 'smiliessupport_admin_menu');

  function smiliessupport_admin_menu($menu) {
      array_push($menu,
        array('NAME' => 'Smilies Support',
              'URL' => get_admin_plugin_menu_link(dirname(__FILE__) . '/smiliessupport_admin.php')));
      return $menu;
  }
}
?>
