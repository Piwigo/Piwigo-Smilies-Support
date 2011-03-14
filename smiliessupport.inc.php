<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

function set_smiliessupport_page()
{
	global $template, $lang, $pwg_loaded_plugins;

	if (!isset($pwg_loaded_plugins['bbcode_bar']))
	{
		$lang['Comment'] .= SmiliesTable();
	}
}

function SmiliesTable($new_conf=null)
{
	global $conf, $template;

	// this is for live update on admin page
	if (empty($new_conf)) 
		$conf_smiliessupport = explode("," , $conf['smiliessupport']);
	else
		$conf_smiliessupport = $new_conf;

	// edit field has a different id
	if (isset($_GET['action']) AND $_GET['action'] == 'edit_comment')
		$template->assign('form_name', 'editComment');
	else
		$template->assign('form_name', 'addComment');

	$cnt = 1;
	$template->set_filename('smiliessupport_page', dirname(__FILE__).'/smiliessupport_page.tpl');
	$template->assign(array('REPRESENTANT' => PHPWG_ROOT_PATH.$conf_smiliessupport[0].'/'.$conf_smiliessupport[2]));

	if ($handle = opendir(PHPWG_ROOT_PATH.$conf_smiliessupport[0]))
	{
		while (false !== ($file = readdir($handle)))
		{
			$trvalue = '';

			if ($file != "." && $file != ".." && ( get_extension($file) == "gif" || get_extension($file) == "png"))
			{
				if (( $cnt > 0 ) && ( $cnt % $conf_smiliessupport[1] == 0 ))
				{
					$trvalue = '</tr><tr>';
				}
				$cnt = $cnt + 1;
				$template->append('smiliesfiles',
				array('PATH' => PHPWG_ROOT_PATH.$conf_smiliessupport[0].'/'.$file,
				'TITLE' => ':'.get_filename_wo_extension($file).':',
				'TR'=>$trvalue));
			}
		}
	}
	else
	{
		array_push($page['errors'], l10n('opendir failed : '.PHPWG_ROOT_PATH.$conf_smiliessupport[0].')' ));
	}
	
	return $template->parse('smiliessupport_page', true);
}

function SmiliesParse($str)
{
	global $conf;

	$conf_smiliessupport = explode("," , $conf['smiliessupport']);
	$def_path = $conf_smiliessupport[0].'/smilies.txt';
	
	if ($handle = opendir(PHPWG_ROOT_PATH.$conf_smiliessupport[0]))
	{
		while (false !== ($file = readdir($handle)))
		{ 
			if ($file != "." && $file != ".." && ( get_extension($file) == "gif" || get_extension($file) == "png")) {
				$v = ':'.get_filename_wo_extension($file).':'; 
				$s = '<img src="'.$conf_smiliessupport[0].'/'.$file.'" alt=":'.get_filename_wo_extension($file).':" title=":'.get_filename_wo_extension($file).':"/>';
				$str = str_replace($v, $s, $str);
			}
		}
	}
	
	if ( file_exists($def_path) )
	{
		$def = file($def_path);
		foreach($def as $v)
		{
			$v = trim($v);
			if (preg_match('|^([^\t]*)[\t]+(.*)$|',$v,$matches))
			{	
				$r = '#'.preg_quote($matches[1],'/').'#';					
				$t = '<img src="'.$conf_smiliessupport[0].'/'.$matches[2].'" alt=":'.get_filename_wo_extension($matches[2]).':" title=":'.get_filename_wo_extension($matches[2]).':"/>';
				$str = preg_replace($r, $t, $str);
			}
		}
	} 
	
	return $str;
}

?>