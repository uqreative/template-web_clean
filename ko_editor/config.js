/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	 config.language = 'ko';
	// config.uiColor = '#AADC6E';

	config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		'/',
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	];
	config.allowedContent = true;
	config.removeButtons = 'Save,NewPage,Preview,Templates,Cut,Find,SelectAll,Form,Radio,Checkbox,TextField,Textarea,Select,Button,ImageButton,HiddenField,Scayt,Replace,Blockquote,CreateDiv,Flash,PageBreak,Iframe,ShowBlocks,About,Image';


	config.height = 300;
	config.toolbarCanCollapse = true;
	config.enterMode = CKEDITOR.ENTER_BR;
	config.filebrowserBrowseUrl =  '/ko_finder/ckfinder.html';
	config.filebrowserUploadUrl = '/ko_finder/core/connector/php/connector.php?command=QuickUpload&type=Files';


};

