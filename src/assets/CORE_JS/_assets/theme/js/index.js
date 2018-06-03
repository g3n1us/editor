if(typeof CKEDITOR !== "undefined"){

	if(typeof ck_everything === "undefined"){
		window.ck_everything = {
		    "$1": {
		        elements: CKEDITOR.dtd,
		        attributes: ['role', 'data-*', 'src', 'href', 'title', 'aria-*', 'width', 'height', 'alt', 'id', 'rel'],
		        classes: true,
		        styles: true,
		    }
		};	
	}
	
	if(typeof window.disallowedContent === "undefined"){
		window.disallowedContent = 'script; *[on*]; *{*}; font; ';
	}
	
}


window.handlebars_templates = {};
window.ckeditor_templates = [];
window.extraPlugins = [];
window.plugins_to_add = {};
var $draggallery = $('#templates_dragger');
$('[type="text/template"]').each(function(){
	var id = $(this).attr('id').replace('template--', '');
	var markup = $(this).html();
	handlebars_templates[id] = Handlebars.compile($(this).html());
	var description = $(this).attr('title');
	var required_class = $(this).data('required_class');
	var data_editables = $(this).data('editables');
	if(!required_class || !data_editables)
		return;
// 		console.log(data_editables);
	var editables = {};
	data_editables.forEach(function(v){
		editables[v] = {
			selector: v,
			allowedContent: ck_everything,
			disallowedContent: disallowedContent,
		}
	});
// 		console.log(JSON.parse(editables));
	ckeditor_templates.push({
		title: id,
		image: "template1.gif",
		description: description,
		html: markup
	});
	if($draggallery.length){
		$draggallery.append('<div class="drag_template mb-4" draggable="true">' + markup + '</div>');
	}
	plugins_to_add[id] = function( editor ) {
		editor.widgets.add( id, {
			allowedContent: ck_everything,
			disallowedContent: disallowedContent,				
			requiredContent: 'div('+required_class+')',
			editables: editables,
			template: markup,
			button: description,
			upcast: function( element ) {
				return element.name == 'div' && element.hasClass( required_class );
			}
		});
	}

});

	


plugins_to_add['col'] = function( editor ) {
	editor.widgets.add( 'col', {
		allowedContent: ck_everything,
		requiredContent: 'div(col-md)',
		editables: {content: {
			selector: '.col-md',
			allowedContent: ck_everything,
		}},
		template: '<div class="col-md"><p>Content</p></div>',
		button: 'A column of content',
		upcast: function( element ) {
			return element.name == 'div' && element.hasClass( 'col-md' );
		}
	});
}

$('.drag_template').each(function(){
	$(this).find('img').attr('draggable', false);
});
var ev;
$(document).on('dragstart', function(e){
	ev = e;
// 	console.log(EventUtil.getCurrentTarget(e));
	$('body').addClass('grabbing');
});
$(document).on('dragend', function(e){
	ev = e;
// 	console.log(EventUtil.getCurrentTarget(e));
	$('body').removeClass('grabbing');
});

setTimeout(function(){
	$('.alerts .alert').css('visibility', 'hidden');
}, 8000);

// $('[rel="tooltip"]').tooltip();

// Push.create('Hello World!');

$(document).on('change', '.person_select', function(e){
    if($('.person_select:checked').length){
		$('.checkbox_and_submits button').addClass('invisible');
	    $('.edit_button').removeAttr('disabled').removeClass('invisible');	
	    var emailaddresses = [];
	    $('.person_select:checked').each(function(v,k){
		    var addr = $(this).data('email_address').trim();
		    if(addr.length) emailaddresses.push(addr);
	    });
		if(emailaddresses.length)
		    $('.email_button').removeAttr('disabled').removeClass('invisible').attr('href', 'mailto:'+emailaddresses.join());				    
    }
    else{
		$('.checkbox_and_submits button').removeClass('invisible');
	    $('.edit_button, .email_button').attr('disabled', 'disabled').addClass('invisible');
    }    
});


$(document).on('change', '.person_select_all', function(e){
	if($(this).is(':checked')){
		$('.person_select').prop('checked', 'checked').trigger('change');
	}
	else{
		$('.person_select').prop('checked', null).trigger('change');
	}
});

window.not_saved = false;

$(document).on('change', '.save_changes_warning form :input', function(){
	window.not_saved = true;
});

$(document).on('submit', 'form', function(){
	window.not_saved = false;
});

$(window).on('beforeunload', function(){
	if(window.not_saved) return "You have unsaved changes";
});


var searchfield = document.getElementById('searchfield');

if(searchfield) searchfield.select();

if(document.getElementById('ckeditor') !== null){
	
	var final_ckconfig = {};	
	if(typeof mail_template_url !== "undefined")
		final_ckconfig.contentsCss = ['/vendor/g3n1us_editor/CORE_JS/js/ckeditor_styles.css', mail_template_url];
		
	var editor = CKEDITOR.replace( 'ckeditor', final_ckconfig);
		
  function pushChanges(){
    console.log('changed');
  	var val = editor.getData();
  // 	var n = $(this).data('name');
  	var n = $(editor.element.$).data('name')
  	$('[name="'+ n +'"]').val(val);
  }
    
  $('#save_editing_form').on('submit', pushChanges);
    
	CKEDITOR.on( 'instanceReady', function(e) {
		console.log(e);

  	pushChanges();
		
		for(var i in plugins_to_add){
			plugins_to_add[i](editor);
		}
		editor.setMode('source');
		editor.setMode('wysiwyg');
		if($('#templates_dragger').length){
			CKEDITOR.document.getById( 'templates_dragger' ).on( 'dragstart', function( evt ) {
				window.testing = evt;
// 				console.error(evt);
				//  testing.data.dataTransfer._.data['text/html']
				var target = evt.data.getTarget().getAscendant( 'div', true );
				CKEDITOR.plugins.clipboard.initDragDataTransfer( evt );
				var dataTransfer = evt.data.dataTransfer;
				console.log(target.getHtml());
				dataTransfer.setData( 'text/html', target.getHtml() );
// 				evt.data.dataValue = target.getHtml(); 
			});
		}
		
		editor.on( 'fileUploadRequest', function( evt ) {
		    var xhr = evt.data.fileLoader.xhr;
		
		    xhr.setRequestHeader( 'X-CSRF-TOKEN', window.csrf_token );
		});	
		editor.on( 'paste', function( evt ) {
			console.log(evt);
			var custom_html = evt.data.dataTransfer.getData( 'text/html' );
			var text_content = evt.data.dataTransfer.getData('text');
			if ( !custom_html ) {
				return null;
			}
			else evt.data.dataValue = custom_html;
			pushChanges();
		});
		console.log('sdfeee')
    editor.on('change', pushChanges);		
    editor.on('blur', pushChanges);		
    
	});
	
}


	


$(document).on('change', '[data-toggle*="disabled"]', function(e){
	$targ = $($(this).attr('href') || $(this).data('target'));
	if($(this).prop('checked')){
		$targ.attr('disabled', 'disabled');
	}
	else
		$targ.removeAttr('disabled');
});


$('[data-toggle*="readonly"]').toggleReadonly();

$(document).on('change', '[data-toggle*="readonly"]', function(e){
	$(this).toggleReadonly();
});


if(typeof formErrors !== "undefined"){
	$.each(formErrors, function(k,v){
		var $field = $('[name="'+k+'"]');
		if(!$field.length)
			$field = $(dotToNameAttr(k));
		$field.addClass('form-control-danger');
		$field.parents('.form-group').addClass('has-danger');
		
		$.each(v, function(i,m){
			var $warntext = $('<div class="form-control-feedback" />');
			$warntext.text(m.replace(k, k.split('.').pop()));
			$field.parents('.form-group').append($warntext);		
		});
	
	});	
}



function dotToNameAttr(dotstring){
	var arr = dotstring.split('.');
	var f = arr.splice(0, 1);
	arr = arr.join('][');
	return '[name="' + f + '[' + arr + ']"]';
}

if($('#page_list').length){
	new Sortable($('#page_list')[0], {
		handle: '.fa-bars',
		onUpdate: function(e){
			$('#savesort').removeAttr('hidden');
		}
	});
	
	$(document).on('click', '#savesort', function(){
		var mapped_order = {};
		var $items = $('#page_list').find('[data-id]');
		$items.each(function(){
			mapped_order[$(this).data('id')] = $items.index(this);
		});
		var request = $.ajax({
			url: "/dashboard/save_page_order",
			type: "GET",
			contentType: "application/json",
			data: {
				pages : mapped_order
			}
		});
		
		request.done(function( data ) {
			console.log(data);
			alert(data.message);
		});
		
		request.fail(function( data, jqXHR, textStatus ) {
			console.log(data);
			alert( "Request failed: " + textStatus );
		});	
		
		console.log(mapped_order);
	});
}


// $('[data-toggle="popover"]').popover();