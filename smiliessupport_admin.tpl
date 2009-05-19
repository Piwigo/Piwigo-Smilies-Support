<div class="titrePage">
	<h2>Smilies Support</h2>
</div>
<form method="post" action="" class="properties"  ENCTYPE="multipart/form-data"> 
<div align="center">
	<fieldset>
      <legend>SmiliesSupport</legend>	  
	  <UL>			
		<LI>Location : <input type="text" size="40" name="text1" value="{$TEXT1_VALUE}" /> </LI>
		<LI>Nb Column : <input type="text" size="20" name="text2" value="{$TEXT2_VALUE}" /> </LI>		
		<LI>Representant : <input type="text" size="20" name="text3" value="{$TEXT3_VALUE}" /> </LI>		
	  <LI>{$SMILIESSUPPORT_PAGE}</LI>
    </UL>
	</fieldset>
	<fieldset>
		<legend>smilies.txt</legend>
		<textarea rows="30" name="text" cols="100">{$CONTENT_FILE}</textarea>
	</fieldset>	
	<input class="submit" type="submit" value="{'Submit'|@translate}" name="submit" />
</div>
</form>
