{combine_script id='jquery'}
{include file='include/autosize.inc.tpl'}

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
			<table><tr><td>{$SMILIESSUPPORT_PAGE}</td></tr></table>
		</li>
    </ul>
	</fieldset>
	
	<fieldset>
		<legend>smilies.txt</legend>
		<textarea rows="5" name="text">{$CONTENT_FILE}</textarea>
	</fieldset>	
	
	<p><input class="submit" type="submit" value="{'Submit'|@translate}" name="submit" /></p>
</form>
