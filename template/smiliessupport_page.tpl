{if not isset($BBCODE_PATH)}
{combine_script id="markitup" require='jquery' path=$SMILIES_PATH|@cat:"template/markitup/jquery.markitup.js"}
{combine_css path=$SMILIES_PATH|@cat:"template/markitup/style.markitup.css"}
{/if}

{footer_script require='jquery'}
{literal}
jQuery(document).ready(function() {
  if (jQuery('.markItUp').length == 0) {
    jQuery('#contentid').markItUp({markupSet: []});
  }
  jQuery('#SmiliesSupport').appendTo('.markItUpHeader ul:first-child');
  jQuery('#allsmilies').hover(function(){ 
    jQuery("#contentid").focus();
    jQuery('#smiliesdiv').css('display',''); 
  });
  jQuery('#smiliesdiv a').click(function() {
    emoticon = jQuery(this).attr("href");
    jQuery.markItUp({ replaceWith:emoticon });
    jQuery('#smiliesdiv').css('display','none');
    return false;
  });
});
{/literal}
{/footer_script}

<ul style="display:none;">
<li id="SmiliesSupport" class="markItUpButton markItUpDropMenu">
  <a id="allsmilies" style="background-image:url('{$ROOT_URL}{$REPRESENTANT}');" title="{'All Smilies'|@translate}"></a>

  <ul id="smiliesdiv">
    <li><table><tr>{strip}
    {foreach from=$smiliesfiles item=smileyfile} 
      <td><a href="{$smileyfile.TITLE}"><img src="{$ROOT_URL}{$smileyfile.PATH}"/></a></td>
      {$smileyfile.TR}
    {/foreach}
    {/strip}</tr></table></li>
  </ul>
</li>
</ul>