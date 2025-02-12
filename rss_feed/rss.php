<? session_start(); ?>
<?
# =============================================================================
# File Name    : notice_list.php
# Modlue       :
# Writer       : Park Chan Ho
# Create Date  : 2023-07-08
# Modify Date  :
#	Copyright : Copyright @Ucomp Corp. All Rights Reserved.
# =============================================================================

// 공지사항

$_PAGE_NO == "22";

#=====================================================================
# common_inc
#=====================================================================

require "../_common/common_inc.php";

if ($nPage == 0)
	$nPage = "1";
if ($nPage == "")
	$nPage = "1";

if (($nPage <> "") && ($b_board_type == "NOTICE")) {
	$cntPage = (int) ($nPage);
	$nPage = (int) ($nPage);
} else {
	$cntPage = (int) ($nPage);
	$nPage = (int) ($nPage);
}

if ($nPageSize <> "") {
	$nPageSize = (int) ($nPageSize);
} else {
	$nPageSize = 20;
}

if ($mobile_is_all == true) {
	$nPageBlock = 5;
} else {
	$nPageBlock = 5;
}

$con_use_tf = "Y";
$con_del_tf = "N";
$nListCnt = totalCntBoardFront($conn, $b, $m_type, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $con_reply_state, $con_use_tf, $con_del_tf, $f, $s);

$nTotalPage = (int) (($nListCnt - 1) / $nPageSize + 1);

if ((int) ($nTotalPage) < (int) ($nPage)) {
	$nPage = $nTotalPage;
}

$arr_rs = listBoardFront($conn, $b, $m_type, $con_cate_02, $con_cate_03, $con_cate_04, $writer_id, $keyword, $con_reply_state, $con_use_tf, $con_del_tf, $f, $s, $nPage, $nPageSize, $nListCnt);

    // 이름 마스킹 함수 정의
    function maskName($name) {
        $len = mb_strlen($name, 'UTF-8'); // 이름 길이
        if ($len <= 1) {
            // 이름이 한 글자일 경우 그대로 반환
            return $name;
        } elseif ($len == 2) {
            // 이름이 두 글자일 경우
            return mb_substr($name, 0, 1, 'UTF-8') . '*';
        } else {
            // 이름이 세 글자 이상일 경우
            $first = mb_substr($name, 0, 1, 'UTF-8'); // 첫 글자
            $last = mb_substr($name, $len - 1, 1, 'UTF-8'); // 마지막 글자
            return $first . str_repeat('*', $len - 2) . $last; // 가운데를 *로 마스킹
        }
    }

?>
<!-- Container -->
<main role="main" class="container">
	<!-- content -->
	<div id="content" class="content notice-list-page">
		<!-- content-header -->
		<?
		require "../_common/content-header.php";

		?>
		<? if ($b == "B_1_3") {
			require "../communication/manage_disc.php";
		} ?> 
		
		<? if ($b == "B_1_12") {
			require "../communication/report_disc.php";
		} ?> 
		<!-- // content-header -->

		<!-- content-body -->
		<div class="content-body">
			<!-- 게시판목록 페이지 -->
			<div class="board-list-page">
				<!-- 타이틀 영역 -->
				<div class="title-wrap">
					<h2 class="title"><?= $seo_title ?></h2>
				</div>
				<!-- // 타이틀 영역 -->

				<!-- 게시판목록 영역 -->
				<div class="board-list-wrap">
					<div class="board-toolbar">
						<p class="board-count">전체 <em><?= number_format($nListCnt) ?></em>건</p>
						<form class="board-srch" id="form" name="frm" onSubmit="js_board_search();" method="get">
							<input type="hidden" name="b" id="b" value="<?= $b ?>" />
							<input type="hidden" name="bn" id="bn" value="" />
							<input type="hidden" name="nPage" id="nPage" value="<?= $nPage ?>" />
							<input type="hidden" name="m_type" id="m_type" value="<?= $m_type ?>" />
						<? if($b == "B_1_11") {?>
						<div class="frm-sel h-48">
							<select name="writer_id" id="writer_id" onChange="blur();" title="검색 구분" class="sel">
									<option value="ALL" <? if ($f == "ALL")
										echo "selected"; ?>>전체</option>
									<option value="<?= $_SESSION['m_id']?>" <? if ($writer_id == $_SESSION['m_id'])
										echo "selected"; ?>>내 문의</option>
									
								</select>
								</div>
								
						<div class="frm-sel h-48">
							<?if($b == "B_1_11") {?>
							<select name="f" id="f" onChange="blur();" title="검색 구분" class="sel">
									<option value="ALL" <? if ($f == "ALL")
										echo "selected"; ?>>전체</option>
									<option value="TITLE" <? if ($f == "TITLE")
										echo "selected"; ?>>제목</option>
								</select>
							<? } else { ?>
							<select name="f" id="f" onChange="blur();" title="검색 구분" class="sel">
									<option value="ALL" <? if ($f == "ALL")
										echo "selected"; ?>>전체</option>
									<option value="TITLE" <? if ($f == "TITLE")
										echo "selected"; ?>>제목</option>
									<option value="WRITER_ID" <? if ($f == "WRITER_ID")
										echo "selected"; ?>>작성자
									</option>
									<option value="CONTENTS" <? if ($f == "CONTENTS")
										echo "selected"; ?>>내용</option>
								</select>
								<? } ?>
							</div>
							
							
							<? }  else { ?>
								
							<div class="frm-sel h-48">
								<select name="f" id="f" onChange="blur();" title="검색 구분" class="sel">
									<option value="ALL" <? if ($f == "ALL")
										echo "selected"; ?>>전체</option>
									<option value="TITLE" <? if ($f == "TITLE")
										echo "selected"; ?>>제목</option>
									<option value="WRITER_ID" <? if ($f == "WRITER_ID")
										echo "selected"; ?>>작성자
									</option>
									<option value="CONTENTS" <? if ($f == "CONTENTS")
										echo "selected"; ?>>내용</option>
								</select>
							</div>
								<? }?>
							
							<div class="frm-inp h-48 has-on-right">
								<input type="text" name="s" id="s" value="<?= $s ?>" autocomplete="off"
									placeholder="검색어를 입력해주세요." title="검색 키워드" class="inp" />
								<div class="on-right">
									<button type="button" class="btn-srch" onclick="js_board_search();">
										<span class="blind">검색</span>
									</button>
								</div>
							</div>
						</form>
					</div>

					<div class="board-list">
						<? if ($b == "B_1_1") { ?>
							<table class="tbl">
								<caption>
									<strong>공지사항 목록</strong>
									<p>No., 제목, 작성자, 등록일, 조회수 순서로 구성됨</p>
								</caption>
								<colgroup>
									<col style="width:120rem" />
									<col style="width:auto" />
									<col style="width:200rem" />
									<col style="width:120rem" />
									<col style="width:120rem" />
								</colgroup>
								<thead>
									<tr>
										<th scope="col">No.</th>
										<th scope="col">제목</th>
										<th scope="col">작성자</th>
										<th scope="col">등록일</th>
										<th scope="col">조회수</th>
									</tr>
								</thead>
								<tbody>
									<?
									$nCnt = 0;
									if (sizeof($arr_rs) > 0) {

										for ($j = 0; $j < sizeof($arr_rs); $j++) {

											$rn = trim($arr_rs[$j]["rn"]);
											$B_NO = trim($arr_rs[$j]["B_NO"]);
											$B_RE = trim($arr_rs[$j]["B_RE"]);
											$B_PO = trim($arr_rs[$j]["B_PO"]);
											$B_CODE = trim($arr_rs[$j]["B_CODE"]);
											$CATE_01 = trim($arr_rs[$j]["CATE_01"]);
											$CATE_02 = trim($arr_rs[$j]["CATE_02"]);
											$CATE_03 = trim($arr_rs[$j]["CATE_03"]);
											$CATE_04 = trim($arr_rs[$j]["CATE_04"]);
											$WRITER_NM = trim($arr_rs[$j]["WRITER_NM"]);
											$TITLE = SetStringFromDB($arr_rs[$j]["TITLE"]);
											$REG_ADM = trim($arr_rs[$j]["REG_ADM"]);
											$HIT_CNT = trim($arr_rs[$j]["HIT_CNT"]);
											$REF_IP = trim($arr_rs[$j]["REF_IP"]);
											$MAIN_TF = trim($arr_rs[$j]["MAIN_TF"]);
											$USE_TF = trim($arr_rs[$j]["USE_TF"]);
											$COMMENT_TF = trim($arr_rs[$j]["COMMENT_TF"]);
											$REG_DATE = trim($arr_rs[$j]["REG_DATE"]);
											$SECRET_TF = trim($arr_rs[$j]["SECRET_TF"]);
											$F_CNT = trim($arr_rs[$j]["F_CNT"]);
											$REPLY = trim($arr_rs[$j]["REPLY"]);
											$REPLY_DATE = trim($arr_rs[$j]["REPLY_DATE"]);
											$REPLY_STATE = trim($arr_rs[$j]["REPLY_STATE"]);
											$REPLY_ADM = trim($arr_rs[$j]["REPLY_ADM"]);
											$TOP_TF = trim($arr_rs[$j]["TOP_TF"]);

											$COLOR_TAG = "";
											$highlightedTitle = highlightKeyword($TITLE, $s); // 검색어 강조 적용

											$is_new = "";
											if ($REG_DATE >= date("Y-m-d H:i:s", (strtotime("0 day") - ($b_new_hour * 3600)))) {
												if ($MAIN_TF <> "N") {
													$is_new = "<img src='../images/bu/ic_new.png' alt='새글' width='35'/> ";
												}
											}

											$REG_DATE = date("Y.m.d", strtotime($REG_DATE));

											$space = "";

											$DEPTH = strlen($B_PO);

											for ($l = 0; $l < $DEPTH; $l++) {
												if ($l != 1)
													$space .= "&nbsp;";
												else
													$space .= "&nbsp;";

												if ($l == ($DEPTH - 1))
													$space .= "&nbsp;┗";

												$space .= "&nbsp;";
											}

											?>
											<tr>
												<td class="tbl-no">
													<? if ($TOP_TF == "Y") { ?>
														<p class="noti">공지</p><!-- Case 공지사항 -->
													<? } else { ?>
														<p><?= $rn ?></p>
													<? } ?>
												</td>
												<td class="tbl-tit">
													<p>
														<a href="view.do?b=<?= $b ?>&bn=<?= $B_NO ?>&m_type=<?= $m_type ?>&nPage=<?= $nPage ?>&f=<?= $f ?>&s=<?= $s ?>"
															class="link"><?= $highlightedTitle ?> </a> <!-- 기존 strip_tags($TITLE)-->
														<? if ($F_CNT > 0) { ?>
															<a href="view.do?b=<?= $b ?>&bn=<?= $B_NO ?>&m_type=<?= $m_type ?>&nPage=<?= $nPage ?>&f=<?= $f ?>&s=<?= $s ?>"
																class="file"><span class="blind">첨부파일</span></a>
														<? } ?>
													</p>
												</td>
												<td class="tbl-writer">
													<p><?= $WRITER_NM ?></p>
												</td>
												<td class="tbl-date">
													<p><?= $REG_DATE ?></p>
												</td>
												<td class="tbl-views">
													<p><?= number_format($HIT_CNT) ?></p>
												</td>
											</tr>
										<?
										}
									} else {
										?>
										<tr>
											<td colspan="5" class="no-list">
												<p>검색결과가 없습니다.</p>
											</td>
										</tr>
									<?
									}
									?>
								</tbody>
							</table>
						</div> <? } else if ($b == "B_1_2") { ?>
						<? require "../communication/notice_list.php"; //보도자료 ?> 

					<? } ?>

					<? if ($b == "B_1_3") { ?>
						<? require "../communication/manage_nav.php"; //경영공시 ?> 
					<? } ?>

					<? if ($b == "B_1_4") { ?>
						<? require "../communication/reservation_list.php"; // 시설 임대 ?> 
					<? } ?>

					<? if ($b == "B_1_5") { ?>
						<? require "../communication/annou_list.php"; //공고 안내 ?>
					<? } ?>
						
					<? if ($b == "B_1_11") { ?>
						<? require "../communication/contact_list.php"; // 문의 사항 ?> 
					<? } ?>
						
						<? if ($b == "B_1_12") { ?>
						<? require "../communication/manage_nav.php"; // 문의 사항 ?> 
					<? } ?>
					

					<? if($b !== "B_1_11") {?>
					<? if (sizeof($arr_rs) > 0) { ?>
						<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						$strParam = $strParam . "b=" . $b . "&m_type=" . $m_type . "&f=" . $f . "&s=" . $s;

						?>
						<?= Front_Image_PageList($_SERVER["PHP_SELF"], $nPage, $nTotalPage, $nPageBlock, $strParam) ?>


					<? } ?>
					
					<? } else { ?>
					<? if (sizeof($arr_rs) > 0) { ?>
						<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						$strParam = $strParam . "b=" . $b . "&m_type=" . $m_type . "&f=" . $f . "&s=" . $s;

						?>
						<?= Front_Image_PageList_contact_list($_SERVER["PHP_SELF"], $nPage, $nTotalPage, $nPageBlock, $strParam) ?>


						<? } ?>
					<? } ?>

				</div>
				<!-- // 게시판목록 영역 -->
			</div>
			<!-- // 게시판목록 페이지 -->
		</div>
		<!-- // content-body -->
	</div>
	<!-- // content -->
</main>
<!-- // Container -->

<!-- include_footer.html -->
<footer class="footer">
	<?
	require "../_common/front_footer.php";
	?>
</footer>
<!-- // include_footer.html -->
</div>
</body>

</html>

<script>

	function js_board_search() {
		var frm = document.frm
		frm.nPage.value = "1";
		frm.submit();
	}
	
	function js_desk() {
		event.preventDefault();  // 기본 동작 방지

		// 로그인 상태 확인
		if (!<?= json_encode(isset($_SESSION['m_id'])) ?>) {
			var userConfirmed = confirm("로그인이 필요합니다. 로그인 페이지로 이동 하시겠습니까?");
			if (userConfirmed) {
				var currentUrl = encodeURIComponent(window.location.href);
				window.location.href = '/member/login.do?redirect=' + currentUrl; // 로그인 페이지로 리디렉션
			}
			return; // 함수 종료
		}
		window.location.href = 'contact_write.do'
	}


</script>