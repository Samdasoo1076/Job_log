$(document).ready(function(){
	var tabL = $('.tab_wrap.type02 .tabs').length;

	$(function() {
		 $('.tab_wrap.type02').addClass('tabs' + '0' + tabL);
	});

	$(".side_menu > ul > li:has('ul')").addClass('has');

/*
	$(".side_menu > ul > li.has > a").click(function(){
		if($(this).parent().hasClass('open')){
			$(this).parent().removeClass('open');
			$(this).parent().find('ul').slideUp();
		} else {
			$(".side_menu > ul > li").removeClass('open');
			$(".side_menu > ul > li.has ul").slideUp();
			$(this).parent().addClass('open');
			$(this).parent().find('ul').slideDown();
		}		
		return false;
	});
*/

	$(".one_depth_menu").click(function(){

		if($(this).parent().hasClass('open')){

			$(this).parent().removeClass('open');
			$(this).parent().find('ul').slideUp();

		} else {

			$(".one_depth_menu").parent().removeClass('open');
			$(".one_depth_menu").parent().find('ul').slideUp();

			$(this).parent().addClass('open');
			$(this).parent().find('ul').slideDown();
		}

		return false;
	});

	$("input[type=file].nicefileinput").nicefileinput();

	$('.tab_wrap.type02 .tabs').click(function(){
		if($(this).hasClass('sele')){
	
		} else {
			$('.tab_wrap.type02 .tabs').removeClass('sele');
			$('.tab_wrap.type02 .tab_cont').removeClass('sele');
			$(this).addClass('sele');
			$(this).next().addClass('sele');
		}

		return false;	
	});		
	
	$('.bxslider').bxSlider({
		pagerCustom: '#bx-pager'
	});

	$('.tab a, .tab .menu').hover(function(){
		$('.tab a').removeClass('open');
		$(this).addClass('open');
		$(this).parent().find('.menu').show();
	}, function(){
		$('.tab a').removeClass('open');
		$(this).parent().find('.menu').hide();
	});

	


	
});


// 설정하기, kr region callendar
$.datepicker.regional['kr'] = {
	closeText: '닫기', // 닫기 버튼 텍스트 변경
	currentText: '오늘', // 오늘 텍스트 변경
	monthNames: ['1 월','2 월','3 월','4 월','5 월','6 월','7 월','8 월','9 월','10 월','11 월','12 월'], // 개월 텍스트 설정
	monthNamesShort: ['1 월','2 월','3 월','4 월','5 월','6 월','7 월','8 월','9 월','10 월','11 월','12 월'], // 개월 텍스트 설정
	dayNames: ['월요일','화요일','수요일','목요일','금요일','토요일','일요일'], // 요일 텍스트 설정
	dayNamesShort: ['일','월','화','수','목','금','토'], // 요일 텍스트 축약 설정    
	dayNamesMin: ['일','월','화','수','목','금','토'], // 요일 최소 축약 텍스트 설정
	//dateFormat: 'dd/mm/yy' // 날짜 포맷 설정
};
$.datepicker.setDefaults($.datepicker.regional['kr']);
$( function() {
	$( ".datepicker" ).datepicker({
		changeMonth: true,
		changeYear: true,
		showMonthAfterYear: true,
		showButtonPanel: true,
		dateFormat: "yy-mm-dd",
		showOn: "button",
		buttonImage: "../images/icon_calendar.png",
		buttonImageOnly: true
	});
});
