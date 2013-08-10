// This will add the item to the display table after it 
// receives confirmation from the Ajax request
var txtFields;
function addTableItem(response) {
	if(response.success==0) {
		jQuery("#txtStatus").html("Article write failed: "+response.msg);
		return;
	} else {
		jQuery("#txtStatus").html("Article written successfully.");
	}
	if(document.clonenum==undefined) {
		document.clonenum = 0;
	}
	document.clonenum++;
	// Get the container of the entry form
	// Make a copy of the prototype row and inject it before the entry row
	var my_clone = jQuery('#proto_row').clone().appendTo('#entry_table');
	/*
		var $j = jQuery.noConflict();
	var clone_proto = $j('#proto_row').clone();
	var my_clone = $j('#entry_row').before(clone_proto);
	jQuery('#entry_row').before(jQuery('#proto_row').clone()).show();
	*/
	// Set the ID of the new row so it's different from the prototype
	my_clone.attr("id","cloneid"+document.clonenum); //rows.length;
	// Clear the style that hides the protorow so this one will be visible
	my_clone.show();
	// Get the text fields in the new row
	txtFields = my_clone;
	// Set the text fields with values from the Ajax return
	jQuery(my_clone)
		.find(".ai_id").html('<a target="_blank" href="'+response.url+'">'+
		    response.id+'</a>').end()
		.find(".ai_title").html(response.title).end()
		.find(".ai_text").html(response.text).end()
		.find(".ai_category").html(response.category).end()
		.find(".ai_published").html(response.published).end()
		;
	// Clear the text fields for the next entry
	jQuery('#article_title').val('');
	jQuery('#intro_text').val('');	
	jQuery('#category').val('');	
}

function ajaxInsert() {
	jQuery("#txtStatus").html("Posting new article...");
	jQuery.ajax({
		type: 'POST',
		url: '/index.php?option=com_articleinjector'+
			'&task=insert&format=raw',
		data: { 
			'title' : jQuery('#article_title').val(), 
			'text' : jQuery('#intro_text').val(), 
			'category' : jQuery('#category').val(), 
			'publish' : jQuery("#chkPublish").is(':checked') 
		},
		success: function(response) { 
			addTableItem(response); 
		}
	});	
}
