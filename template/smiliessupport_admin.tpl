{combine_script id='jquery.tokeninput' load='footer' path='themes/default/js/plugins/jquery.tokeninput.js'}
{combine_css path=$SMILIES_PATH|cat:'template/style.css'}


{footer_script}{literal}
var data = {};
var edit = false;
var edited = false;

// set changed
jQuery("select[name='folder']").change(function() {
    if (edited) {
        var ok = confirm("{/literal}{'If you change current set you will lost every shortcuts changes.'|@translate}{literal}");
        if (!ok) {
            jQuery(this).val(jQuery(this).data("selected"));
            return false;
        }
    }
    
    var image = jQuery(this).find(":selected").css("background-image");
    jQuery(this).css("background-image", image);
    jQuery(this).data("selected", jQuery(this).val());
    
    fetch();
});

// size changed
jQuery("input[name='cols']").change(function() {
    update();
});

// switch preview/edit
jQuery(".edit").click(function() {
    if (edit) {
        $(this).html("{/literal}{'Edit shorcuts'|@translate}{literal}");
    }
    else {
        $(this).html("{/literal}{'Preview'|@translate}{literal}");
    }
    
    edit = !edit;
    update();
    return false;
});

// display edit form before submit
jQuery("#smiliesupport").submit(function() {
    if (!edit) jQuery(".edit").click();
    return true;
});

/* get smilies list */
function fetch() {
    jQuery.ajax({
        url: 'admin.php',
        type: 'GET',
        dataType: 'json',
        data: {
            action: 'ss_preview',
            folder: jQuery("select[name='folder']").val(),
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
    var html = '';
    
    if (!edit) {
        html+= '<tr>';
        var cols = parseInt(jQuery("input[name='cols']").val());
        var i=0;
        
        for (var file in data.smilies) {
            var smiley = data.smilies[file];
            html+= '<td><a href="#" title="'+ smiley.title +'"><img src="'+ data.path + smiley.file +'"/></a></td>';
            if ((parseInt(i)+1)%cols == 0) html+= '</tr><tr>';
            i++;
        }
        
        html+= '</tr>';
    }
    else {
    {/literal}
        html+= '<tr>'
            +'<th>{'Smiley'|@translate}</th>'
            +'<th>{'Name'|@translate}</th>'
            +'<th>{'Shortcuts'|@translate}</th>'
          +'</tr>';
     {literal}
     
        for (var file in data.smilies) {
            var smiley = data.smilies[file];
            html+= '<tr data-file="'+ smiley.file +'">'
                +'<td><img src="'+ data.path + smiley.file +'"/></td>'
                +'<td>'+ smiley.title +'</td>'
                +'<td>'
                  +'<select name="shortcuts['+ smiley.file +']" class="shortcuts">';
                
                for (var j in smiley.short) {
                    html+= '<option value="'+ smiley.short[j] +'" selected>'+ smiley.short[j] +'</option>';
                }
                  
                html+= '</select>'
                +'</td>'
              +'</tr>';
        }
    }
    
    jQuery("#preview").html(html);
    
    // init tokeninput
    jQuery(".shortcuts").tokenInput([], {
        hintText: '{/literal}{'Type in a new shortcut'|@translate}{literal}',
        newText: '',
        animateDropdown: false,
        preventDuplicates: true,
        caseSensitive: true,
        allowCreation: true,
        minChars: 2,
        searchDelay: 10,
        
        onAdd: function(item) {
            edited = true;
            var file = $(this).parents("tr").data("file");
            
            if (data.smilies[file].short == null) {
                data.smilies[file].short = [item.name];
            }
            else {
                data.smilies[file].short.push(item.name);
            }
        },
        onDelete: function(item) {
          edited = true;
          var file = $(this).parents("tr").data("file");
          
          for (var i in data.smilies[file].short) {
              if (data.smilies[file].short[i] == item.name) {
                  data.smilies[file].short.splice(i, 1);
              }
          }
        },
    });
    
    // prevent spaces
    jQuery(".token-input-input-token input").keydown(function(e) {
        if (e.keyCode == 32) {
            return false;
        }
    });
}

// init
fetch();
{/literal}{/footer_script}


<div class="titrePage">
  <h2>Smilies Support</h2>
</div>

<form method="post" action="" class="properties" id="smiliesupport">

<fieldset>
  <legend>{'Configuration'|@translate}</legend>  
  
  <ul>      
    <li>
      <b>{'Smilies set'|@translate}</b>
      <select name="folder" style="background-image:url('{$SMILIES_PATH}smilies/{$FOLDER}/{$SETS[$FOLDER]}');" data-selected="{$FOLDER}">
      {foreach from=$SETS item=rep key=set}
        <option value="{$set}" style="background-image:url('{$SMILIES_PATH}smilies/{$set}/{$rep}');" {if $set==$FOLDER}selected{/if}>{$set}</option>
      {/foreach}
      </select>
    </li>
    <li>
      <b>{'Columns'|@translate}</b>
      <input type="text" size="2" name="cols" value="{$COLS}">
    </li>
  </ul>
</fieldset>

<fieldset>
  <legend>{'Preview'|@translate}</legend>  
  <a href="#" class="edit buttonLike">{'Edit shorcuts'|@translate}</a>
  <table id="preview"></table>
</fieldset>
  
<p class="formButtons"><input class="submit" type="submit" value="{'Submit'|@translate}" name="submit" /></p>

</form>
