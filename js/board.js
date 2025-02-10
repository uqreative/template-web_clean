
$(document).ready(function() {
	selectDesign();
});

$(function(){ 
	$('.scrollbar-dynamic').scrollbar();
	$('.scrollbar-inner').scrollbar();
});
$(document).ready(function() {
	$('input[type="text"]').keydown(function() {
		if($(this).attr("name") != "keyword") {
			if (event.keyCode === 13) {
				event.preventDefault();
			}
		};
	});
});
$(function() {
  $( ".datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd',
    prevText: 'ì´ì „ ë‹¬',
    nextText: 'ë‹¤ìŒ ë‹¬',
    monthNames: ['1ì›”','2ì›”','3ì›”','4ì›”','5ì›”','6ì›”','7ì›”','8ì›”','9ì›”','10ì›”','11ì›”','12ì›”'],
    monthNamesShort: ['1ì›”','2ì›”','3ì›”','4ì›”','5ì›”','6ì›”','7ì›”','8ì›”','9ì›”','10ì›”','11ì›”','12ì›”'],
    dayNames: ['ì¼','ì›”','í™”','ìˆ˜','ëª©','ê¸ˆ','í† '],
    dayNamesShort: ['ì¼','ì›”','í™”','ìˆ˜','ëª©','ê¸ˆ','í† '],
    dayNamesMin: ['ì¼','ì›”','í™”','ìˆ˜','ëª©','ê¸ˆ','í† '],
    showMonthAfterYear: true,
    changeMonth: true,
    changeYear: true,
	showOn: "both",
	buttonText: "ë‹¬ë ¥ë³´ê¸°",
  });
});

function sms_send() {
	var phone_tmp1 = $("input[name='phone1']").val();
	var phone_tmp2 = $("input[name='phone2']").val();
	var phone_tmp3 = $("input[name='phone3']").val();

	if( phone_tmp1 == "" || phone_tmp2 == "" || phone_tmp3 == "" ){
		alert("íœ´ëŒ€í°ë²ˆí˜¸ë¥¼ ì˜¬ë°”ë¥´ê²Œ ìž…ë ¥í•´ì£¼ì„¸ìš”.");
		return false;
	}

	if( phone_tmp1.length != 3 || phone_tmp2.length != 4 || phone_tmp3.length != 4 ){
		alert("íœ´ëŒ€í°ë²ˆí˜¸ë¥¼ ì˜¬ë°”ë¥´ê²Œ ìž…ë ¥í•´ì£¼ì„¸ìš”.");
		return false;
	}

	var data = phone_tmp1 + "-" + phone_tmp2 + "-" + phone_tmp3; 

	$.ajax({
		type : "POST",
		url : "/ko_admin/inc/sms_ajax.php",
		data : {
			data_send : data
		},
		success : function(args) {
				alert(args);
		}
	});
}

function sms_auth() {
	var sms_auth = $("input[name='sms_auth']").val();

	if( sms_auth == ""){
		alert("ì¸ì¦ë²ˆí˜¸ë¥¼ ì˜¬ë°”ë¥´ê²Œ ìž…ë ¥í•´ì£¼ì„¸ìš”.");
		return false;
	}

	var phone_tmp1 = $("input[name='phone1']").val();
	var phone_tmp2 = $("input[name='phone2']").val();
	var phone_tmp3 = $("input[name='phone3']").val();

	if( phone_tmp1 == "" || phone_tmp2 == "" || phone_tmp3 == "" ){
		alert("íœ´ëŒ€í°ë²ˆí˜¸ë¥¼ ì˜¬ë°”ë¥´ê²Œ ìž…ë ¥í•´ì£¼ì„¸ìš”.");
		return false;
	}

	if( phone_tmp1.length != 3 || phone_tmp2.length != 4 || phone_tmp3.length != 4 ){
		alert("íœ´ëŒ€í°ë²ˆí˜¸ë¥¼ ì˜¬ë°”ë¥´ê²Œ ìž…ë ¥í•´ì£¼ì„¸ìš”.");
		return false;
	}

	var data = phone_tmp1 + "-" + phone_tmp2 + "-" + phone_tmp3; 

	$.ajax({
		type : "POST",
		dataType: 'json',
		url : "/ko_admin/inc/sms_auth_ajax.php",
		data : {
			sms_auth : sms_auth,
			data_send : data
		},
		success : function(request) {
			alert(request.message);
			$("#auth_message").html(request.tag);
			if(request.result == "true"){
				$("#sms_auth2").val("Y");
			}
		}
	});
}

$(document).ready(function() {
	$('form').submit(function() {
		var chk = true;

		//ì…€ë ‰íŠ¸ ì²´í¬
		$('.required_select').each(function() {
			if (!$(this).val()) {			
				alert($(this).attr('title') + "ì„ ì„ íƒí•˜ì„¸ìš”.");
				$(this).focus();
				chk = false;
				return false;
			}
		});
		if (!chk) { return false; }
		
		// ë¼ë””ì˜¤ ë²„íŠ¼ ì²´í¬
		$('.required_radio').each(function() {
		/*
			if (!$(this).find('input:radio:checked').length) {			
				alert($(this).attr('title') + "ì„ ì„ íƒí•˜ì„¸ìš”.");
				$(this).find('input:radio').first().focus();
				chk = false;
				return false;
			}
		*/
			var radio_name = $(this).attr("name");
		
			if(!$("input[type=radio][name="+radio_name+"]").is(":checked")){
				alert($(this).attr('title') + "ì„ ì„ íƒí•˜ì„¸ìš”.");
				//$(this).find('input:radio').first().focus();
				$("input[type=radio][name="+radio_name+"]").focus();
				chk = false;
				return false;
			}

		});
		if (!chk) { return false; }
			
		// ì²´í¬ë°•ìŠ¤ ë²„íŠ¼ ì²´í¬
		$('.required_checkbox').each(function() {
		
			// ìµœì†Œ í•œê°œ ì„ íƒ
			if (!$(this).find('input:checkbox:checked').length) {			
				alert($(this).attr('title') + "ì„ ì„ íƒí•˜ì„¸ìš”.");
				$(this).find('input:checkbox').first().focus();
				chk = false;
				return false;
			}

			// ìµœëŒ€ ì„ íƒ
			if ($(this).find('input:checkbox:checked').length > $(this).attr('limit')) {			
				alert($(this).attr('title') + "ëŠ” " + $(this).attr('limit') + "ê°œê¹Œì§€ ì„ íƒì´ ê°€ëŠ¥í•©ë‹ˆë‹¤.");
				$(this).find('input:checkbox').first().focus();
				chk = false;
				return false;
			}
			/*
			// í•„ìˆ˜ ì„ íƒ ìˆ˜ ì ìš©
			if ($(this).find('input:checkbox:checked').length != $(this).attr('limit')) {			
				alert($(this).attr('title') + "ëŠ” " + $(this).attr('limit') + "ê°œê¹Œì§€ ì„ íƒí•˜ì…”ì•¼ í•©ë‹ˆë‹¤.");
				$(this).find('input:checkbox').first().focus();
				chk = false;
				return false;
			}
			*/

		});
		
		if (!chk) { return false; }

		$('.required').each(function() {
			if (!$(this).val()) {			
				alert($(this).attr('title') + "ì„ ìž…ë ¥í•˜ì„¸ìš”.");
				$(this).focus();
				chk = false;
				return false;
			}
		});
		
		if (!chk) { return false; }


		if("<?=$board[sms_auth]?>" == "Y" && "<?=$mode?>" != "modify"){
			$('.auth_required').each(function() {
				if ($(this).val() != "Y") {			
					alert("ì¸ì¦ì„ ì§„í–‰í•´ì£¼ì„¸ìš”.");
					chk = false;
					return false;
				}
			});
		
			if (!chk) { return false; }
		}
	});

});

/* ì „ì²´ê°€ ë‹¤ë˜ê¸°ë•Œë¬¸ì— ì£¼ì„í•¨ - khh 240613
$(document).on('click', 'a[href="#"]', function(e){
	e.preventDefault();
});
*/

/*
$(function(){
	$('.btn_del').click(function(){
		$("."+$(this).attr('id')).toggle('show');
		return false;
	});

	$('.btn_reply').click(function(){
		$("."+$(this).attr('id')).toggle('show');
		return false;
	});

	$('.hide_all').click(function(){
		//$("."+$(this).attr('id')).toggle('hide');
		$(this).closest("div").toggle('hide');
		return false;
	});
});
*/

	function reply_check(idx){
		var chk = true; 

		if($("#"+"re_comment_name_"+idx).val() == "") {
			alert("ì´ë¦„ì„ ìž‘ì„±í•˜ì„¸ìš”");
			chk = false;
			return false;
		}
		if (!chk) { return false; }


		if($("#"+"re_comment_pw_"+idx).val() == "") {
			alert("ë¹„ë°€ë²ˆí˜¸ë¥¼ ìž‘ì„±í•˜ì„¸ìš”");
			chk = false;
			return false;
		}
		if (!chk) { return false; }

		if($("#"+"re_comment_text_"+idx).val() == "") {
			alert("ë‚´ìš©ì„ ìž‘ì„±í•˜ì„¸ìš”");
			chk = false;
			return false;
		}
		if (!chk) { return false; }
	}



$(document).ready(function() {
	var chk = true;
	$(".comment_del_form").submit(function() {
		if($(this).children("input[type=password]").val() == ""){
			alert($(this).children('input[type=password]').attr('title')+"ì„ ìž…ë ¥í•˜ì„¸ìš”");
			chk = false;
			return false;
		}
		if (!chk) { return false; }
	});

	$(".comment_append_form").submit(function() {
		if($(this).children("input[type=password]").val() == ""){
			alert($(this).children('input[type=password]').attr('title')+"ì„ ìž…ë ¥í•˜ì„¸ìš”");
			chk = false;
			return false;
		}
		if (!chk) { return false; }
	});

	$(".comment_write").submit(function() {

		if($(this).children("input[type=comm_name]").val() == ""){
			alert($(this).children('input[type=comm_name]').attr('title')+"ì„ ìž…ë ¥í•˜ì„¸ìš”");
			chk = false;
			return false;
		}
		if (!chk) { return false; }
 
		if($(this).children("input[type=comm_password]").val() == ""){
			alert($(this).children('input[type=comm_password]').attr('title')+"ì„ ìž…ë ¥í•˜ì„¸ìš”");
			chk = false;
			return false;
		}
		if (!chk) { return false; }

		if($(this).children("textarea[type=comm_comments]").val() == ""){
			alert($(this).children('textarea[type=comm_comments]').attr('title')+"ì„ ìž…ë ¥í•˜ì„¸ìš”");
			chk = false;
			return false;
		}
		if (!chk) { return false; }
	});



});

function selectDesign(){
	$('.designSelect select').each(function(){
		var $this = $(this), numberOfOptions = $(this).children('option').length;

		$this.addClass('select-hidden');
		$this.wrap('<div class="select"></div>');
		$this.after('<div class="selectbox"></div>');

		var $styledSelect = $this.next('div.selectbox');
		$styledSelect.text($this.children('option').eq(0).text());

		var $list = $('<ul />', {
			'class': 'selectoption'
		}).insertAfter($styledSelect);

		for (var i = 0; i < numberOfOptions; i++) {
			$('<li />', {
				text: $this.children('option').eq(i).text(),
				rel: $this.children('option').eq(i).val()
			}).appendTo($list);
			if($this.children('option').eq(i).is(':selected')){
				var tmp_this = $this.children('option').eq(i);
				$styledSelect.text(tmp_this.text()).removeClass('active');
			}
		}

		var $listItems = $list.children('li');

		$styledSelect.click(function(e) {
			$(this).parent("div").toggleClass('active');
			//$(".designSelect .select").addClass('active');
			e.stopPropagation();
			$('div.selectbox.active').not(this).each(function(){
				$(this).removeClass('active').next('ul.selectoption').slideToggle();
			});
			$(this).toggleClass('active').next('ul.selectoption').slideToggle();			

			$( ".designSelect" ).mouseleave(function() {
				 $styledSelect.removeClass('active').addClass('trans');
				 $(".designSelect .select").removeClass('active');
				$list.slideUp();
			});
		});

		$listItems.click(function(e) {	
			$list.children('li').removeClass("selected");		
			$styledSelect.removeClass('trans');
			$(this).addClass("selected");
			e.stopPropagation();
			$styledSelect.text($(this).text()).removeClass('active');
			$this.val($(this).attr('rel'));
			$list.hide();		
			$(".designSelect .select").removeClass('active');	
		});		
	});	
}