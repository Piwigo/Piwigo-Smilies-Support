{if not isset($BBCODE_PATH)}
{combine_script id="markitup" require='jquery' path=$SMILIES_PATH|@cat:"template/markitup/jquery.markitup.js"}
{combine_css path=$SMILIES_PATH|@cat:"template/markitup/style.markitup.css"}
{/if}

{footer_script require='jquery'}{literal}
if (jQuery('.markItUp').length == 0) {
  jQuery('#{/literal}{$SMILIES_ID}{literal}').markItUp({markupSet: []});
  jQuery('.markItUpHeader>ul').css('width', '22');
}
else {
  jQuery('.markItUpHeader>ul').css('width', '+=22');
}

jQuery('#SmiliesSupport').appendTo('.markItUpHeader ul:first-child');

jQuery('#allsmilies').hover(function(){ 
  jQuery('#{/literal}{$SMILIES_ID}{literal}').focus();
  jQuery('#smiliesdiv').css('display',''); 
});

jQuery('#smiliesdiv img').click(function() {
  emoticon = jQuery(this).attr("title");
  jQuery.markItUp({ replaceWith:emoticon });
  jQuery('#smiliesdiv').css('display','none');
  return false;
});
{/literal}{/footer_script}

{html_style}{literal}
#SmiliesSupport table img:hover {
  border:1px solid #08e;
  margin:-1px;
  border-radius:2px;
  cursor:pointer;
}
#allsmilies { background-size:contain;
{/literal}
background-image:url('{$ROOT_URL}{$REPRESENTANT}'); }
{/html_style}

<ul style="display:none;">
<li id="SmiliesSupport" class="markItUpButton markItUpDropMenu">
  <a id="allsmilies" title="{'Smilies'|@translate}"></a>

  <ul id="smiliesdiv">
    <li><table><tr>{strip}
    {foreach from=$smiliesfiles item=smileyfile} 
      <td><img src="{$ROOT_URL}{$smileyfile.PATH}" title="{$smileyfile.TITLE}"/></td>
      {$smileyfile.TR}
    {/foreach}
    {/strip}</tr></table></li>
  </ul>
</li>
</ul>