<?session_start();?>

<?
  require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/member/member.php";
  require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/reservation/reservation.php";
  require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
?>      
      
      <!-- 예약내역팝업 START -->
					<div class="modal-dialog modal-basic">
						<div class="modal-content">
							<div class="modal-header">
								<!-- <h1 class="modal-title" id="exampleModalLabel">Modal title</h1> -->
								<button type="button" class="ui-btn btn-close" data-bs-dismiss="modal"
									aria-label="Close"><span class="blind">닫기</span></button>
							</div>
							<div class="modal-body">
								<div class="cont">
									<p class="tit"><em>woonju1234</em>님의 예약 내역입니다.</p>
									<div class="info top">
										<dl>
											<dt>예약번호</dt>
											<dd>241029C002468​</dd>
										</dl>
										<dl>
											<dt>연락처</dt>
											<dd>241029C002468​</dd>
										</dl>
										<dl>
											<dt>미승인</dt>
											<dd>시설 리모델링​</dd>
										</dl>
									</div>
									<div class="info">
										<dl>
											<dt>시설명</dt>
											<dd>컨퍼런스룸​</dd>
										</dl>
										<dl>
											<dt>사용 인원</dt>
											<dd>34명</dd>
										</dl>
										<dl>
											<dt>사용 목적</dt>
											<dd>미팅​</dd>
										</dl>
										<dl>
											<dt>시설 방문 날짜</dt>
											<dd>미2024-10-10</dd>
										</dl>
										<dl>
											<dt>이용 시간</dt>
											<dd>14:00~18:00 (오후 반일)</dd>
										</dl>
										<dl>
											<dt>감면대상자</dt>
											<dd>아니요</dd>
										</dl>
										<dl>
											<dt>대여기자재 사용</dt>
											<dd>아니요</dd>
										</dl>
									</div>
									<div class="info-txt">
										<p>사용안내글이 노출됩니다. 사용안내글이 노출됩니다. 사용안내글이 노출 됩니다. 사용안내글이 노출됩니다.사용안내글이 노출됩니다. 사용안내글이
											사용안내글이 노출됩니다. 사용안내글이 노출됩니다. 노출됩니다.</p>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<div class="btn-wrap">
									<button type="button" class="btn-basic h-56 primary center-w200">
										<span>PDF로 저장하기</span>
									</button>
								</div>
							</div>
						</div>
					</div>
				
				<!-- 예약내역팝업 END -->