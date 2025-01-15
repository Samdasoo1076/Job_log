<!-- S: 고교 검색 Modal -->
<section class="modal" id="school_search_popup">
	<div class="modal__wrap" style="max-width: 65rem;width:95%;">
		<div class="modal__title">
			<h2><strong>고등학교 검색</strong></h2>
			<button type="button" class="btn-ico modal__close" title="닫기" id="school_search_popup_close"></button>
		</div>

		<!-- S: Modal Contents -->
		<div class="modal__contents">
			<div class="modal__sec">

				<div class="search-text w48">
					<input type="text" id="search_school_nm" name="search_school_nm" onkeyup="enterkey(event);" placeholder="고교명을 입력해주세요" />
					<button type="button" class="btn" onclick="search_school($('[name=search_school_nm]').val())">검색</button>
				</div>
				<br />
				<ul id="search_school_tbody" style="overflow-y:scroll;overflow-x:hidden;height:350px">
					<li>
						<span style="width:90%;font-size:1.6rem;">고교명으로 검색하세요.</span>
					</li>
				</ul>

			</div>
			<!--
			<div class="btn-group center">
				<button type="button" class="btn btn__positive" onclick="front.modal.hide(this)">확인</button>
			</div>
			-->
		</div>
		<!-- //E: Modal Contents -->
	</div>
</section>
<!-- E: 고교 검색 Modal -->

<!-- S: 비밀번호 확인 Modal -->
<section class="modal" id="modal-pw">
	<div class="modal__wrap">
		<div class="modal__title">
			<h2><strong>비밀번호 입력</strong></h2>
			<button type="button" class="btn-ico modal__close" title="닫기"></button>
		</div>

		<!-- S: Modal Contents -->
		<div class="modal__contents">
			<form class="popup-content-body" onSubmit="return false;">
			<div class="modal__sec">
				<div class="inp__pw">
					<input type="password" type="password" name="confirm_passwd" id="confirm_passwd" placeholder="비밀번호를 입력해 주세요" />
					<input type="hidden" name="confirm_mode" id="confirm_mode" title="">
				</div>
			</div>
			<div class="btn-group center">
				<button type="button" class="btn btn__positive" id="btn_confirm_passwd" onclick="js_confirm_passwd(this)">확인</button>
			</div>
			</form>
		</div>
		<!-- //E: Modal Contents -->
	</div>
</section>
<!-- E: 비밀번호 확인 Modal -->

<!-- S: 비디오형 Modal -->
<section class="modal modal__video" id="modal-video">
	<div class="modal__wrap">
		<!-- S: Modal Contents -->
		<div class="iframe-wrap" id="youtube_area">
			<!--영상영역-->
		</div>
		<button type="button" class="btn" id="youtube_close_btn" onclick="front.modal.hide(this)"></button>
		<!-- //E: Modal Contents -->
	</div>
</section>
<!-- E: 비디오형 Modal -->

<section class="modal" id="modal-notice">
	<div class="modal__notice pop-notice">
		<div class="pop-notice__top">
			<div class="pop-notice__mark">Notice</div>
			<div class="pop-notice__title">
				건국대학교
				<br />
				글로컬캠퍼스의
				<br />
				주요 소식을 알려드려요
			</div>
		</div>
		<!--
			⛔ pop-notice__swiper 주의사항⛔
			- PC는 class="swiper-slide" 내 class="pop-notice__item" 4개씩 한 세트
			- MOBILE은 class="swiper-slide" 내 class="pop-notice__item" 1개씩 한 세트
		-->
		<div class="pop-notice__swiper">
			<div class="pop-notice__controls">
				<button type="button" class="swiper-button-prev"></button>
				<button type="button" class="swiper-button-next"></button>
			</div>
			<div class="swiper-wrapper">
				<?
					require_once "../_classes/biz/popup/quick.php";

					$arr_quick_top = mainlistQuick($conn, "TOP", "");
					
					if (sizeof($arr_quick_top) > 0) {
						for ($j = 0 ; $j < sizeof($arr_quick_top); $j++) {

							$Q_NO							= trim($arr_quick_top[$j]["Q_NO"]);
							$Q_TYPE						= trim($arr_quick_top[$j]["Q_TYPE"]);
							$Q_COLOR					= trim($arr_quick_top[$j]["Q_COLOR"]);
							$Q_TITLE					= trim($arr_quick_top[$j]["Q_TITLE"]);
							$Q_SUBTITLE				= trim($arr_quick_top[$j]["Q_SUBTITLE"]);
							$Q_DESCRIPTION		= trim($arr_quick_top[$j]["Q_DESCRIPTION"]);
							$Q_URL						= trim($arr_quick_top[$j]["Q_URL"]);
							$Q_TARGET					= trim($arr_quick_top[$j]["Q_TARGET"]);

							$DISP_SEQ					= trim($arr_quick_top[$j]["DISP_SEQ"]);
							$USE_TF						= trim($arr_quick_top[$j]["USE_TF"]);
							$REG_DATE					= trim($arr_quick_top[$j]["REG_DATE"]);
							$UP_DATE					= trim($arr_quick_top[$j]["UP_DATE"]);

							switch ($Q_TYPE) {
								case "ALL" : $COLOR_TAG = "<span class='badge'>공통</span>"; break;
								case "SUSI" : $COLOR_TAG = "<span class='badge badge__color3'>수시</span>"; break;
								case "JEONGSI" : $COLOR_TAG = "<span class='badge badge__color2'>정시</span>"; break;
								case "JEOEGUK" : $COLOR_TAG = "<span class='badge badge__color4'>재외국민</span>"; break;
								case "PYEONIP" : $COLOR_TAG = "<span class='badge badge__color7'>편입학</span>"; break;
								case "SCHOOL" : $COLOR_TAG = "<span class='badge badge__color5'>고교연계</span>"; break;
								case "ETC" : $COLOR_TAG = "<span class='badge badge__color6'>기타</span>"; break;
							}
				?>
				<div class="swiper-slide">
					<a href="<?=$Q_URL?>" class="card pop-notice__item" <? if ($Q_TARGET =="Y") { ?>target="_blank"<? } ?>>
						<div class="card-inner">
							<div class="card__detail">
								<?=$COLOR_TAG?>
								<div class="title">
									<?=$Q_TITLE?>
								</div>
							</div>
							<!--
							<div class="card__bottom">
								<span class="date">2023.06.15</span>
							</div>
							-->
						</div>
					</a>
				</div>
				<?
						}
					}
				?>
			</div>
			<div class="swiper-scrollbar"></div>
		</div>
	</div>
	<button type="button" title="메뉴 닫기" class="btn_ico btn-ico__notice-close"><span class="blind">닫기</span></button>
</section>

<script>


	$(document).on("click", ".popMovieModalShow", function() {

		let youtube_src = "<iframe frameborder='0' src='https://www.youtube.com/embed/"+$(this).attr("data-link")+"' style='width: 100%; height: 100%;' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
		//let youtube_src = "<iframe frameborder='0' src='https://www.youtube.com/embed/SsG6XeDuVyM' style='width: 100%; height: 100%;' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";

		$("#youtube_area").html(youtube_src);
		$("#modal-video").addClass('modal--is-active');
		$('body').css("overflow-y", "hidden");

		//front.modal.show('modal-video');

	});

	$(document).on("click", "#youtube_close_btn", function() {
		$("#youtube_area").html("");
		//$("#movie_area").removeClass("is-active");
		$("#youtube_close_btn").trigger("click");
	});

	function school_pop_open() {
		$("#school_search_popup").addClass('modal--is-active');
		$('body').css("overflow-y", "hidden");
	}
	
	function enterkey(e) {
		var keyCode = "";

		if (window.event) {    //IE & Safari
			keyCode = e.keyCode;
		} else if (e.which) {    // Netscape/Firefox/Opera
			keyCode = e.which;
		}

		if (keyCode == 13) {
			search_school($('[name=search_school_nm]').val(), 'no_pop');
		}
	}

	function search_school(search_text) {

		//$(".school_scroll").show();

		if (search_text == "") return;

		var request = $.ajax({
			url:"/_common/ajax_search_school.php",
			type:"POST",
			data:{search_text:search_text},
			dataType:"json"
		});

		request.done(function(data) {

			//console.log(data);

			if (parseInt(data.total) == 0) { 
				$("#search_school_tbody").html("<li><span style='width:90%;font-size:1.6rem;'>검색된 고교가 없습니다.</span></li>");
			} else {
				$("#search_school_tbody").html(data.list);
			}
	
		});
	}

	function search_school_return(sch_code, sch_nm, sch_area) {

		$("#apply_school").val(sch_nm);
		$("#sch_code").val(sch_code);
		$("#sch_area").val(sch_area);
		$("#search_school_nm").val("");
		$("#search_school_tbody").html("<li><span style='width:90%;font-size:1.6rem;'>고교명으로 검색하세요.</span></li>");

		$("#school_search_popup_close").trigger("click");

	}


</script>