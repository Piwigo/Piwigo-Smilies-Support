{combine_script id="jquery"}
{combine_script id="smiliessupport" path=$ROOT_URL|@cat:"plugins/SmiliesSupport/smiliessupport.js"}
{combine_css path=$ROOT_URL|@cat:"plugins/SmiliesSupport/smiliessupport_page.css"}

<div width="500pt">
	<img id="allsmilies" src="{$REPRESENTANT}" title="{'All Smilies'|@translate}" onmouseover="jQuery('#smiliesdiv').css('visibility','visible');">

	<div id="smiliesdiv" >
		<table class="smiliestable" 
		onmouseover="jQuery('#smiliesdiv').css('visibility','visible');"
		onmouseout="jQuery('#smiliesdiv').css('visibility','hidden');">
			<tr>
			{foreach from=$smiliesfiles item=smiliesfile} 
				<td><img src="{$smiliesfile.PATH}" title="{$smiliesfile.TITLE}" onclick="SmiliesWrite('{$form_name}','content','','{$smiliesfile.TITLE}',true); jQuery('#smiliesdiv').css('visibility','hidden');"> </td>	
				{$smiliesfile.TR}
			{/foreach}
			</tr>
		</table>
	</div>
</div>
