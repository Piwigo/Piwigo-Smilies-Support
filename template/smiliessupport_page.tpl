{combine_script id="markitup" require='jquery' path=$SMILIES_PATH|@cat:"template/markitup/jquery.markitup.js"}
{combine_css path=$SMILIES_PATH|@cat:"template/markitup/style.markitup.css"}

{footer_script require='jquery'}
{literal}
$(document).ready(function() {
	if (jQuery('.markItUp').length == 0) {
		jQuery('#{/literal}{$form_name}{literal} textarea').markItUp({markupSet: []});
	}
	jQuery('#SmiliesSupport').appendTo('.markItUpHeader ul:first-child');
	jQuery('#allsmilies').mouseover(function(){ 
		jQuery("#{/literal}{$form_name}{literal} textarea").focus();
		jQuery('#smiliesdiv').css('display',''); 
	});
	jQuery('#smiliesdiv a').click(function() {
		emoticon = jQuery(this).attr("title");
		jQuery.markItUp({ replaceWith:emoticon });
		jQuery('#smiliesdiv').css('display','none');
		return false;
	});
});
{/literal}
{/footer_script}

<li id="SmiliesSupport" class="markItUpButton markItUpDropMenu">
	<a id="allsmilies" style="background-image:url({$REPRESENTANT});" title="{'All Smilies'|@translate}"></a>

	<ul id="smiliesdiv">
		<li><table><tr>
		{foreach from=$smiliesfiles item=smileyfile} 
			<td><a href="#" title="{$smileyfile.TITLE}"><img src="{$smileyfile.PATH}"/></a></td>
			{$smileyfile.TR}
		{/foreach}
		</tr></table></li>
	</ul>
</li>