<script src="/ko_editor/ckeditor.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		//Replace the <textarea id="smart_content"> with a CKEditor
		//instance, using default configuration.
		if($("#smart_content").length > 0) {
			CKEDITOR.replace( 'smart_content' );
		}
		if($("#smart_content2").length > 0) {
			CKEDITOR.replace( 'smart_content2' );
		}
	});
</script>
