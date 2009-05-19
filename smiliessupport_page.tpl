{known_script id="smiliessupport" src=$ROOT_URL|@cat:"plugins/SmiliesSupport/smiliessupport.js"}
{html_head}
<link rel="stylesheet" type="text/css" href="{$ROOT_URL|@cat:"plugins/SmiliesSupport/smiliessupport_page.css"}" >
{/html_head}

<img id="allsmilies" src="{$REPRESENTANT}" title="All Smilies" onmouseover="document.getElementById('smiliesdiv').style.visibility='visible';" >
<div id="smiliesdiv" >
<table class="smiliestable" 
onmouseover="document.getElementById('smiliesdiv').style.visibility='visible';"
onmouseout="document.getElementById('smiliesdiv').style.visibility='hidden';" >
	<tr>
		{foreach from=$smiliesfiles item=smiliesfile} 
		<td><img src="{$smiliesfile.PATH}" title="{$smiliesfile.TITLE}" onclick="SmiliesWrite('addComment', 'content', '', '{$smiliesfile.TITLE}', true); document.getElementById('smiliesdiv').style.visibility='hidden';"> </td>	
		{$smiliesfile.TR}
		{/foreach}
	</tr>
</table>
</div>
