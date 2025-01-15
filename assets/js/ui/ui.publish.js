/*-------------------------------------------------------------------
	분류순서
	- @ Publish	    : 레이아웃
	- @ Init	    : 초기실행
-------------------------------------------------------------------*/
$(function(){
	$(".header").each(function(){
		$(this).load("../../html/layout/include_header.html", function(){
			console.log('[INFO] 퍼블리싱 header.html 인클루드 완료');
			initUI();
		});
	})
	$(".footer").each(function(){
		$(this).load("../../html/layout/include_footer.html", function(){
			console.log('[INFO] 퍼블리싱 footer.html 인클루드 완료');
			$('.main-wrapper #emailPolicyModalLink').attr('data-bs-target', '#mainEmailPolicyModal');
			initUI();
		});
	});


	/* 전체메뉴 */
	$('.header .btn-gnb').click(function(event) {
        event.preventDefault(); // a 태그 기본 동작 방지
        $('.header .area-siteMap').show();
    });

    // .header 내의 .area-siteMap 안의 .menuClose 클릭 시 .area-siteMap 숨기기
    $('.header .area-siteMap .menuClose').click(function(event) {
        event.preventDefault(); // a 태그 기본 동작 방지
        $('.header .area-siteMap').hide();
    });
});

