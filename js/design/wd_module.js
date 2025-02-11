/* 
 * module js Document
*/ 

$(window).resize(function(){ 
	calendarH();
});

$(window).on('load',function(){
	calendarH();
	treeUI();
	productView();
});

//tree UI
function treeUI(){
	if(!($('.area_tree').length > 0)) return;
	$('.area_tree .list li').each(function(){
		if(!($(this).find("ul").length > 0)){
			$(this).children('.tree').removeClass('folder');
		}else{
			$(this).children('.tree').addClass('folder');
		}
	})
	
	$('.area_tree .list li a.folder').on('click',function(e){
		if($(this).parent().find('ul').length > 0){
			e.preventDefault();
			if($(this).parent().hasClass('active')){
				$(this).parent().removeClass('active');	
				if($(this).parent().find('ul').length > 0){
					$(this).parent().find('li').removeClass('active');
					$(this).text('열기');
				}
			}else{
				$(this).text('닫기');
				$(this).parent().addClass('active');
			}
		}else{
			$('.area_tree .list li a.tree').removeClass('on');
			$(this).addClass('on');
		}
	})
	
	$('.area_tree .btn.open').on('click',function(){
		$('.area_tree .list a.folder').parent().addClass('active');
		$(this).addClass('active');
		$('.area_tree .btn.close').removeClass('active');
		return false;
	});
	$('.area_tree .btn.close').on('click',function(){
		$('.area_tree .list a.folder').parent().removeClass('active');
		$('.area_tree .btn.open').removeClass('active');
		return false;
	});
}

//calendar
function calendarH(){	
	var calendarHeight = $('.area_calendar .calendar').outerHeight();
	$('.area_calendar .list').css('height',calendarHeight+'px');
}

//product
function productView(){
	if(!($('.productView').length > 0)) return;
	$('.productView .img ul a').hover(function(){
		var proSrc = $(this).children('img').attr('src');
		$('.productView .img ul a').removeClass('active');
		$(this).addClass('active');
		$('.productView .img span').children('img').attr('src', proSrc);
	});
}
