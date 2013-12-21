{combine_script id='markitup' require='jquery' load='footer' path=$SMILIES_PATH|cat:'template/markitup/jquery.markitup.js'}
{combine_css id='markitup' order=10 path=$SMILIES_PATH|cat:'template/markitup/style.markitup.css'}

{footer_script require='markitup'}
if (jQuery('.markItUp').length == 0) {
  jQuery('#{$SMILIES.textarea_id}').markItUp({
    markupSet: []
  });
  jQuery('.markItUpHeader>ul').css('width', '22');
}
else {
  jQuery('.markItUpHeader>ul').css('width', '+=22');
}

jQuery('#SmiliesSupport').appendTo('.markItUpHeader ul:first-child');

jQuery('#allsmilies').hover(function(){ 
  jQuery('#{$SMILIES.textarea_id}').focus();
  jQuery('#smiliesdiv').css('display', ''); 
});

jQuery('#smiliesdiv img').click(function() {
  var emoticon = jQuery(this).attr('title');
  jQuery.markItUp({
    replaceWith: emoticon
  });
  jQuery('#smiliesdiv').css('display', 'none');
  return false;
});
{/footer_script}

{html_style}
#SmiliesSupport table img:hover {
  border:1px solid #08e;
  margin:-1px;
  border-radius:2px;
  cursor:pointer;
}
#allsmilies {
  background-size:contain;
  background-image:url('{$ROOT_URL}{$SMILIES.representant}');
}
{/html_style}

<ul style="display:none;">
<li id="SmiliesSupport" class="markItUpButton markItUpDropMenu">
  <a id="allsmilies" title="{'Smilies'|translate}"></a>

  <ul id="smiliesdiv"><li>
    <table><tr>{strip}
    {foreach from=$SMILIES.files item=smileyfile} 
      <td><img src="{$ROOT_URL}{$smileyfile.PATH}" title="{$smileyfile.TITLE}"/></td>
      {$smileyfile.TR}
    {/foreach}
    {/strip}</tr></table>
  </li></ul>
</li>
</ul>