<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

function plugin_install()
{
	global $conf;

	if (!isset($conf['smiliessupport']))
	{
		$q = 'INSERT INTO ' . CONFIG_TABLE . ' (param,value,comment)
			VALUES ("smiliessupport","plugins/SmiliesSupport/smilies,5,sourire.gif","Parametres SmiliesSupport");';
		pwg_query($q);
	}
}

function plugin_uninstall()
{
	global $conf;

	if (isset($conf['smiliessupport']))
	{
		pwg_query('DELETE FROM ' . CONFIG_TABLE . ' WHERE param="smiliessupport" LIMIT 1;');
	}
}

?>