<?php
defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

class SmiliesSupport_maintain extends PluginMaintain
{
  private $default_conf = array(
    'folder'       => 'crystal',
    'cols'         => '6',
    'representant' => 'smile.png',
    );

  function install($plugin_version, &$errors=array())
  {
    global $conf;
    
    if (!isset($conf['smiliessupport']))
    {
      conf_update_param('smiliessupport', $this->default_conf, true);
    }
  }

  function update($old_version, $new_version, &$errors=array())
  {
    $this->install($new_version, $errors);
  }

  function uninstall()
  {
    conf_delete_param('smiliessupport');
  }
}
