{combine_css path='themes/default/js/plugins/jquery.tokeninput.css'}
{combine_script id='jquery.tokeninput' load='footer' path='themes/default/js/plugins/jquery.tokeninput.js'}

{combine_css path=$SMILIES_PATH|cat:'template/style.css'}


{footer_script}
(function(){
var data = {ldelim}},
    edit = false,
    edited = false;

// set changed
jQuery('select[name="folder"]').change(function() {
    if (edited) {
        var ok = confirm('{'If you change current set you will lost every shortcuts changes.'|translate|escape:javascript}');
        if (!ok) {
            jQuery(this).val(jQuery(this).data('selected'));
            return false;
        }
    }
    
    var image = jQuery(this).find(':selected').css('background-image');
    jQuery(this).css('background-image', image);
    jQuery(this).data('selected', jQuery(this).val());
    
    fetch();
});

// size changed
jQuery('input[name="cols"]').change(function() {
    update();
});

// switch preview/edit
jQuery('.edit').click(function() {
    if (edit) {
        $(this).html('{'Edit shorcuts'|translate|escape:javascript}');
    }
    else {
        $(this).html('{'Preview'|translate|escape:javascript}');
    }
    
    edit = !edit;
    update();
    return false;
});

// reset defaults
jQuery('.reset').click(function() {
    if (!confirm('{'Are you sure?'|translate|escape:javascript}')) {
        return false;
    }
    
    jQuery.ajax({
        url: 'admin.php',
        type: 'GET',
        dataType: 'json',
        data: {
            action: 'ss_reset',
            folder: jQuery('select[name="folder"]').val(),
        },
        success: function(result) {
            data = result;
            edited = false;
            update();
        }
    });
    
    return false;
});

// display edit form before submit
jQuery('#smiliesupport').submit(function() {
    if (!edit) {
        jQuery('.edit').click();
    }
    return true;
});

/* get smilies list */
function fetch() {
    jQuery.ajax({
        url: 'admin.php',
        type: 'GET',
        dataType: 'json',
        data: {
            action: 'ss_list',
            folder: jQuery('select[name="folder"]').val(),
        },
        success: function(result) {
            data = result;
            edited = false;
            update();
        }
    });
}

/* update preview/edit table */
function update() {
    var html = '', i=0;
    
    if (!edit) {
        html+= '<tr>';
        
        var cols = parseInt(jQuery('input[name="cols"]').val());
        
        for (var file in data.smilies) {
            var smiley = data.smilies[file];
            html+= '<td><a href="#" title="'+ smiley.title +'"><img src="'+ data.path + smiley.file +'"/></a></td>';
            html+= (i+1)%cols==0 ? '</tr><tr>' : '';
            i++;
        }
        
        html+= '</tr>';
        
        jQuery('.reset').hide();
    }
    else {
        html+= '<tr>'
            +'<th></th>'
            +'<th>{'Name'|translate}</th>'
            +'<th>{'Shortcuts'|translate}</th>'
            +'<th class="spacer"></th>'
            +'<th></th>'
            +'<th>{'Name'|translate}</th>'
            +'<th>{'Shortcuts'|translate}</th>'
          +'</tr>'
          
          +'<tr>';
     
        for (var file in data.smilies) {
            var smiley = data.smilies[file];
            
            html+= 
              '<td><img src="'+ data.path + smiley.file +'"/></td>'
              +'<td>'+ smiley.title +'</td>'
              +'<td data-file="'+ smiley.file +'">'
                +'<select name="shortcuts['+ smiley.file +']" class="shortcuts">';
              
              for (var j in smiley.short) {
                  html+= '<option value="'+ smiley.short[j] +'" selected>'+ smiley.short[j] +'</option>';
              }
                
            html+= 
                '</select>'
              +'</td>';
            
            html+= (i+1)%2==0 ? '</tr><tr>' : '<td></td>';
            i++;
        }
        
        html+= '</tr>';
        
        jQuery('.reset').show();
    }
    
    jQuery('#preview').html(html);
    
    // init tokeninput
    jQuery('.shortcuts').tokenInput([], {
        hintText: '{'Type in a new shortcut'|translate|escape:javascript}',
        newText: '',
        animateDropdown: false,
        preventDuplicates: true,
        allowFreeTagging: true,
        minChars: 2,
        searchDelay: 10,
        
        onAdd: function(item) {
            edited = true;
            var file = $(this).parent('td').data("file");
            
            if (data.smilies[file].short == null) {
                data.smilies[file].short = [item.name];
            }
            else {
                data.smilies[file].short.push(item.name);
            }
        },
        onDelete: function(item) {
          edited = true;
          var file = $(this).parent('td').data("file");
          
          for (var i in data.smilies[file].short) {
              if (data.smilies[file].short[i] == item.name) {
                  data.smilies[file].short.splice(i, 1);
              }
          }
        },
    });
    
    // prevent spaces
    jQuery('.token-input-input-token input').keydown(function(e) {
        if (e.keyCode == 32) {
            return false;
        }
    });
}

// init
fetch();
}());{/footer_script}


<div class="titrePage">
  <h2>Smilies Support</h2>
</div>

<form method="post" action="" class="properties" id="smiliesupport">

<fieldset>
  <legend>{'Configuration'|translate}</legend>  
  
  <ul>      
    <li>
      <b>{'Smilies set'|translate}</b>
      <select name="folder" style="background-image:url('{$SMILIES_PATH}smilies/{$FOLDER}/{$SETS[$FOLDER]}');" data-selected="{$FOLDER}">
      {foreach from=$SETS item=rep key=set}
        <option value="{$set}" style="background-image:url('{$SMILIES_PATH}smilies/{$set}/{$rep}');" {if $set==$FOLDER}selected{/if}>{$set}</option>
      {/foreach}
      </select>
    </li>
    <li>
      <b>{'Columns'|translate}</b>
      <input type="text" size="2" name="cols" value="{$COLS}">
    </li>
  </ul>
</fieldset>

<fieldset>
  <legend>{'Preview'|translate}</legend>  
  <a href="#" class="edit buttonLike">{'Edit shortcuts'|translate}</a>
  <table id="preview"></table>
  <a href="#" class="reset buttonLike" style="display:none;">{'Reset defaults'|translate}</a>
</fieldset>
  
<p class="formButtons"><input class="submit" type="submit" value="{'Submit'|translate}" name="submit" /></p>

</form>
