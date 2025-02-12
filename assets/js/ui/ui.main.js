$(function(){
	$('#emailPolicyModalLink').attr('data-bs-target', '#mainEmailPolicyModal');

    $('#fullpage').fullpage({
        // 옵션 설정
		responsiveWidth: 767,
        licenseKey: '69M18-T2I56-73XK9-PJ71I-XPBXQ',
        scrolloverflowmacstyle: true,
		normalScrollElements: '.modal',
        'onLeave' : function (index, nextIndex, direction){
			var $gnbarea = $(".gnb-area");
            // theme
			if ( nextIndex.index === 0 ){
				$('video.viewer')[0].play();
				$gnbarea.removeClass('active');
			} else if ( nextIndex.index === 1 ){
				$gnbarea.removeClass('active');
			} else if ( nextIndex.index === 2 ){
                $gnbarea.addClass('active');
            };

            // topbtn
            if ( nextIndex.index === 0){
                $('.topBtn').css('display', 'none');
            } else {
                $('.topBtn').css('display', 'flex');


            }

			// 예약현황 크기
			if ( nextIndex.index === 1 || nextIndex.index === 2) {
				$('.reservation-wrap').addClass('small');

			} else {
				$('.reservation-wrap').removeClass('small');
			};
        },
		// responsiveOverflow 재설정 방지
		afterResponsive: function (isResponsive) {
			if ($('.modal.show').length) {
				// 모달이 열려 있는 경우 overflow 설정을 막음
				$('body').css('overflow', 'hidden'); // 원하는 값으로 고정
			}
		}
    });
});

// 풀페이지 활성/비활성
function setFullpageEvent(id) {
	$(document).on('show.bs.modal', id, function () {
		if (typeof fullpage_api !== 'undefined') {
			fullpage_api.setAllowScrolling(false);
			console.log(id + " Fullpage: false");
		}
	}).on('hidden.bs.modal', id, function () {
		if (typeof fullpage_api !== 'undefined') {
			fullpage_api.setAllowScrolling(true);
			console.log(id + " Fullpage: true");
		}
	});
}

// 풀페이지 활성/비활성
setFullpageEvent('#mobileNavModal'); //모바일 전체메뉴
setFullpageEvent('#sitemap'); // PC 사이트맵
setFullpageEvent('#carendar'); // PC 사이트맵
