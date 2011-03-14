<?php
/*
Plugin Name: Smilies Support
Version: 2.2.0
Description: Allow add Smilies for comments and descriptions.
Plugin URI: http://piwigo.org/ext/extension_view.php?eid=159
Author: Atadilo & P@t & Mistic
*/

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

define('SMILIES_DIR' , basename(dirname(__FILE__)));
define('SMILIES_PATH' , PHPWG_PLUGINS_PATH . SMILIES_DIR . '/');

include_once(SMILIES_PATH.'smiliessupport.inc.php');

add_event_handler('render_comment_content', 'SmiliesParse', 60);
add_event_handler('loc_begin_picture', 'set_smiliessupport_page');

if (script_basename() == 'admin')
{
	add_event_handler('get_admin_plugin_menu_links', 'smiliessupport_admin_menu');
	function smiliessupport_admin_menu($menu) 
	{
		array_push($menu, array(
			'NAME' => 'Smilies Support',
			 'URL' => get_root_url().'admin.php?page=plugin-' . SMILIES_DIR));
		return $menu;
	}
}
?>
