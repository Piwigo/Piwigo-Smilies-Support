<?php
defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

class SmiliesSupport_maintain extends PluginMaintain
{
  private $installed = false;
  
  private $default_conf = array(
    'folder'       => 'crystal',
    'cols'         => '6',
    'representant' => 'smile.png',
    );

  function install($plugin_version, &$errors=array())
  {
    global $conf;
    
    if (isset($conf['smiliessupport']))
    {
      $conf['smiliessupport'] = serialize($this->default_conf);

      conf_update_param('smiliessupport', $conf['smiliessupport']);
    }
    
    $this->installed = true;
  }

  function activate($plugin_version, &$errors=array())
  {
    if (!$this->installed)
    {
      $this->install($plugin_version, $errors);
    }
  }

  function deactivate()
  {
  }

  function uninstall()
  {
    conf_delete_param('smiliessupport');
  }
}
