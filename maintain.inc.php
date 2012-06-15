<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

function plugin_install()
{
  $new_smiliessupport =  array(
    'folder'       => 'crystal',
    'cols'         => '6',
    'representant' => 'smile.png',
  );
  
  conf_update_param('smiliessupport', serialize($new_smiliessupport));
}

function plugin_activate()
{
  global $conf;
  
  if (strpos($conf['smiliessupport'],',') !== false)
  {
    $conf_smiliessupport = explode(',', $conf['smiliessupport']);
    
    switch ($conf_smiliessupport[0])
    {
      case 'plugins/SmiliesSupport/smilies': $conf_smiliessupport[0] = 'ipb'; break;
      case 'plugins/SmiliesSupport/smilies_2': $conf_smiliessupport[0] = 'sylvia'; break;
      default: $conf_smiliessupport[0] = 'crystal'; break;
    }
    
    $new_smiliessupport =  array(
      'folder'       => $conf_smiliessupport[0],
      'cols'         => $conf_smiliessupport[1],
      'representant' => $conf_smiliessupport[2],
    );
    
    conf_update_param('smiliessupport', serialize($new_smiliessupport));
  }
}

function plugin_uninstall()
{
  pwg_query('DELETE FROM ' . CONFIG_TABLE . ' WHERE param="smiliessupport" LIMIT 1;');
}

?>