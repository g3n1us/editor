/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
 
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
	window.disallowedContent = 'script; *[on*]; *{*}; font;';
}


CKEDITOR.plugins.addExternal( '3-column-row', '/vendor/g3n1us_editor/CORE_JS/js/widgets/3-column-row/' );


CKEDITOR.editorConfig = function( config ) {
	config.toolbarGroups = [
		{ name: 'document', groups: [ 'document', 'mode', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	];

	config.removeButtons = 'HiddenField,Button,Select,Textarea,TextField,Radio,Checkbox,Form,Scayt,SelectAll,Find,Replace,Sourcedialog,Print,Preview,NewPage,Underline,Outdent,Indent,Language,BidiRtl,BidiLtr,Flash,PageBreak,Iframe,ShowBlocks,BGColor,TextColor,About,Font,Maximize,Superscript,Subscript,Strike,RemoveFormat,CopyFormatting,Redo,Undo,Paste,Copy,Cut,FontSize,Embed,Table,SpecialChar,Save';
	
	config.templates_replaceContent = false;
	config.disableNativeSpellChecker = false;
	config.uploadUrl = '/editor_dashboard/upload';
	config.stylesSet = 'asfs_styles:/vendor/g3n1us_editor/CORE_JS/js/styleset.js';
	config.embed_provider = '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}&api_key=2af12597b56249147b3e43';
// 	config.removePlugins = 'pastefromword';
	//config.removePlugins = 'forms,scayt,font,flash,colorbutton,specialchar,language,div,pagebreak,newpage,wsc,bidi,sourcedialog,print,find,replace,selectall,showblocks,anchor,iframe';
	config.contentsCss = ['/vendor/g3n1us_editor/CORE_JS/_assets/theme/dist/css/compiled.css', '/vendor/g3n1us_editor/CORE_JS/js/ckeditor_styles.css', ];
	if(!window.disableEmailAddressEncoding)
		config.emailProtection = 'encode';
	// config.stylesheetParser_skipSelectors = /(^body\.|^svg\.)/i;
	config.keystrokes = [[ CKEDITOR.CTRL + 83 /*S*/, 'save' ], ];
	config.extraPlugins = '3-column-row';
	config.extraAllowedContent = window.ck_everything;	
	config.disallowedContent = window.disallowedContent;	
	config.templates_files = [
	    '/vendor/g3n1us_editor/CORE_JS/js/templates/custom.js',
	    //'http://www.example.com/user_templates.js'
	];	
// 	config.baseHref = 'https://www.asfspta.com/';
	config.baseHref = '';
	config.entities = false;
	config.image2_prefillDimensions = false;
	config.justifyClasses = [ 'text-left', 'text-center', 'text-right', 'columns' ];
	config.filebrowserBrowseUrl = '/editor_dashboard/filemanager';
// 	config.pasteFilter = 'plain-text';
	
};


//"dialogui,dialog,about,a11yhelp,dialogadvtab,basicstyles,bidi,blockquote,button,toolbar,notification,clipboard,panelbutton,panel,floatpanel,colorbutton,colordialog,templates,menu,contextmenu,copyformatting,div,resize,elementspath,enterkey,entities,popup,filebrowser,find,fakeobjects,flash,floatingspace,listblock,richcombo,font,forms,format,horizontalrule,htmlwriter,iframe,wysiwygarea,image,indent,indentblock,indentlist,smiley,justify,menubutton,language,link,list,liststyle,magicline,maximize,newpage,pagebreak,pastetext,pastefromword,preview,print,removeformat,save,selectall,showblocks,showborders,sourcearea,specialchar,scayt,stylescombo,tab,table,tabletools,tableselection,undo,wsc,lineutils,widgetselection,widget,filetools,notificationaggregator,uploadwidget,uploadimage,image2,autolink,autoembed,uploadfile,autogrow,sourcedialog,embedbase,embed"

//"clipboard,form,tablecell,tablecellproperties,tablerow,tablecolumn,table,anchor,link,image,flash,checkbox,radio,textfield,hiddenfield,imagebutton,button,select,textarea,div"
