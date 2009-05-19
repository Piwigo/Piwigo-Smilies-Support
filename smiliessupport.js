var theSelection = false;

function SmiliesWrite(form, field, start, end, force) {
	var textarea = document.forms[form].elements[field];	
	
	storeCaret(textarea);
	
	if (textarea.caretPos) {
	  textarea.focus();
		// Attempt to create a text range (IE).
		theSelection = document.selection.createRange().text;		
		if (force || theSelection != '') {
			document.selection.createRange().text = start + theSelection + end;
			textarea.focus();
			return true;
		}
	} else if (typeof(textarea.selectionStart) != "undefined") {
		// Mozilla text range replace.
		var text = new Array();
		text[0] = textarea.value.substr(0, textarea.selectionStart);
		text[1] = textarea.value.substr(textarea.selectionStart, textarea.selectionEnd-textarea.selectionStart);
		text[2] = textarea.value.substr(textarea.selectionEnd);
		caretPos = textarea.selectionEnd+start.length+end.length;
		if (force || text[1] != '') {
			textarea.value = text[0]+start+text[1]+end+text[2];
			if (textarea.setSelectionRange) {
				textarea.focus();
				textarea.setSelectionRange(caretPos, caretPos);
			}
			return true;
		}
	} else if (force) {		
		// Just put it on the end.
		textarea.value += start+end;
		textarea.focus(textarea.value.length-1);
		return true;
	}
	return false;
}

function storeCaret(text) {
	if (text.createTextRange) text.caretPos = document.selection.createRange().duplicate();
}
