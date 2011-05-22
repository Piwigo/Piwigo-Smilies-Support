{combine_script id='jquery.cluetip' require='jquery' path='themes/default/js/plugins/jquery.cluetip.js'}
{include file='include/autosize.inc.tpl'}

{footer_script require="jquery.cluetip"}
{literal}
  jQuery().ready(function(){
    // Cluetip
    jQuery('.cluetip').cluetip({
      width: 550,
      splitTitle: '|'
    });
  });
{/literal}
{/footer_script}

{html_head}
<style type="text/css">
  legend .cluetip {ldelim}
    text-align:center;
    margin:20px 0 -10px 0;
    font-size:1.2em;
  }
  .cluetip:after {ldelim}
    margin-left:5px;
    vertical-align:top;
    content:url('{$themeconf.admin_icon_dir}/help.png');
  }
  .properties textarea {ldelim}
    width:60%;
    margin:0 20%;
  }
</style>
{/html_head}

<div class="titrePage">
  <h2>Smilies Support</h2>
</div>

<form method="post" action="" class="properties" ENCTYPE="multipart/form-data"> 
  <fieldset>
      <legend>{'Configuration'|@translate}</legend>    
    <ul>      
    <li>
      <span class="property">{'smilies_dir'|@translate}</span>
      <input type="text" size="40" name="text1" value="{$TEXT1_VALUE}" />
    </li>
    <li>
      <span class="property">{'nb_columns'|@translate}</span>
      <input type="text" size="3" name="text2" value="{$TEXT2_VALUE}" />
    </li>    
    <li>
      <span class="property">{'representant'|@translate}</span>
      <input type="text" size="20" name="text3" value="{$TEXT3_VALUE}" />
    </li>
    <li>
      <table><tr>
      {foreach from=$smiliesfiles item=smileyfile} 
        <td><a href="#" title="{$smileyfile.TITLE}"><img src="{$smileyfile.PATH}"/></a></td>
        {$smileyfile.TR}
      {/foreach}
      </tr></table>
    </li>
    </ul>
  </fieldset>
  
  <fieldset>
    <legend><span class="cluetip" title="smilies.txt|{'smilies_file_help'|@translate}">smilies.txt</legend>
    <textarea rows="5" name="text">{$CONTENT_FILE}</textarea>
  </fieldset>  
  
  <p><input class="submit" type="submit" value="{'Submit'|@translate}" name="submit" /></p>
</form>
