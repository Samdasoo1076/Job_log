<? session_start(); ?>
<?
# =============================================================================
# File Name    : notice_view.php
# Modlue       : 
# Writer       : Lee Ji Min
# Create Date  : 2025-01-10
# Modify Date  : 
#	Copyright : Copyright @Ucomp Corp. All Rights Reserved.
# =============================================================================
error_reporting(E_ALL & ~E_NOTICE);

// 공지사항 
#$b = "B_1_1";
$m = "read";

$b = isset($_POST["b"]) && $_POST["b"] !== '' ? $_POST["b"] : (isset($_GET["b"]) ? $_GET["b"] : '');
$m_type = isset($_POST["m_type"]) && $_POST["m_type"] !== '' ? $_POST["m_type"] : (isset($_GET["m_type"]) ? $_GET["m_type"] : '');

if ($b == "B_1_1") {
	$_PAGE_NO = "21";
} else if ($b == "B_1_2") {
	$_PAGE_NO = "75";
} else if ($b == "B_1_3") {
	$_PAGE_NO = "24";
} else if ($b == "B_1_4") {
	$_PAGE_NO = "109";
} else if ($b == "B_1_11"){
	$_PAGE_NO = "112";
} 
else {
	$_PAGE_NO = "21";
}

#=====================================================================
# common_inc
#=====================================================================

require "../_common/common_inc.php";

$rs_b_no = trim($arr_rs_read[0]["B_NO"]);
$rs_b_po = trim($arr_rs_read[0]["B_PO"]);
$rs_b_code = trim($arr_rs_read[0]["B_CODE"]);
$rs_title = SetStringFromDB($arr_rs_read[0]["TITLE"]);
$rs_title = check_html($rs_title);
$rs_writer_id = trim($arr_rs_read[0]["WRITER_ID"]);
$rs_writer_nm = SetStringFromDB($arr_rs_read[0]["WRITER_NM"]);
$rs_writer_pw = SetStringFromDB($arr_rs_read[0]["WRITER_PW"]);
$rs_email = trim($arr_rs_read[0]["EMAIL"]);
$rs_homepage = trim($arr_rs_read[0]["HOMEPAGE"]);
$rs_contents = SetStringFromDB($arr_rs_read[0]["CONTENTS"]);

#if($goupp_user_id=="lkkdangwon"){
$cc_i_arr = array("<form", "</form", "<input", "<textarea", "</textarea", "girin_comment", "javascript:gbc_");
$cc_o_arr = array("<orm", "</orm", "<nput", "<extarea", "</extarea", "glgln_comment", "javascript:gbcc_");
$rs_contents = replace_tag_parts($rs_contents, $cc_i_arr, $cc_o_arr);

$rs_recomm = trim($arr_rs_read[0]["RECOMM"]);
$rs_recommno = trim($arr_rs_read[0]["RECOMMNO"]);

$rs_cate_01 = trim($arr_rs_read[0]["CATE_01"]);
$rs_cate_02 = trim($arr_rs_read[0]["CATE_02"]);
$rs_cate_03 = trim($arr_rs_read[0]["CATE_03"]);
$rs_cate_04 = trim($arr_rs_read[0]["CATE_04"]);
$rs_keyword = trim($arr_rs_read[0]["KEYWORD"]);
$rs_reply = trim($arr_rs_read[0]["REPLY"]);
$rs_ref_ip = trim($arr_rs_read[0]["REF_IP"]);
$rs_top_tf = trim($arr_rs_read[0]["TOP_TF"]);
$rs_hit_cnt = trim($arr_rs_read[0]["HIT_CNT"]);
$rs_use_tf = trim($arr_rs_read[0]["USE_TF"]);
$rs_del_tf = trim($arr_rs_read[0]["DEL_TF"]);
$rs_reg_adm = trim($arr_rs_read[0]["REG_ADM"]);
$rs_reg_date = trim($arr_rs_read[0]["REG_DATE"]);

$rs_file_nm = trim($arr_rs_read[0]["FILE_NM"]);
$rs_file_rnm = trim($arr_rs_read[0]["FILE_RNM"]);

$rs_thumb_img = trim($arr_rs_read[0]["THUMB_IMG"]);
$rs_reply = trim($arr_rs_read[0]["REPLY"]);
$rs_reply_adm = trim($arr_rs_read[0]["REPLY_ADM"]);
$rs_reply_state = trim($arr_rs_read[0]["REPLY_STATE"]);
$rs_reply_date = trim($arr_rs_read[0]["REPLY_DATE"]);
if (!empty($rs_reply_date)) {
    $rs_reply_date = date("Y.m.d", strtotime($rs_reply_date));
}
$rs_up_date = trim($arr_rs_read[0]["UP_DATE"]);

$con_use_tf = "Y";
$con_del_tf = "N";

# 이전글
$arr_rs_pre = selectPreBoardAsTop($conn, $b, $bn, $rs_reg_date, $m_type, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $ref_ip, $con_reply_state, $rs_top_tf, $con_use_tf, $con_del_tf, $f, $s);

$rs_pre_b_no = "";

if ($arr_rs_pre) {
	$rs_pre_b_code = trim($arr_rs_pre[0]["B_CODE"]);
	$rs_pre_b_no = trim($arr_rs_pre[0]["B_NO"]);
	$rs_pre_writer_id = trim($arr_rs_pre[0]["WRITER_ID"]);
	$rs_pre_cate_01 = trim($arr_rs_pre[0]["CATE_01"]);
	$rs_pre_hit_cnt = trim($arr_rs_pre[0]["HIT_CNT"]);
	$rs_pre_title = SetStringFromDB($arr_rs_pre[0]["TITLE"]);
	$rs_pre_title = check_html($rs_pre_title);
	$rs_pre_reg_date = trim($arr_rs_pre[0]["REG_DATE"]);
	if ($rs_pre_cate_01)
		if($b == "B_1_11") {
		$rs_pre_title = "[" . getDcodeName($conn, "MA_TYPE", $rs_pre_cate_01) . "] " . $rs_pre_title;
	} else if($b == "B_1_12") {
		$rs_pre_title = "[" . getDcodeName($conn, "RESEARCH_REPORT", $rs_pre_cate_01) . "] " . $rs_pre_title;
	}
	$rs_pre_reg_date = date("Y.m.d", strtotime($rs_pre_reg_date));
}

# 다음글
$arr_rs_post = selectPostBoardAsTop($conn, $b, $bn, $rs_reg_date, $m_type, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $ref_ip, $con_reply_state, $rs_top_tf, $con_use_tf, $con_del_tf, $f, $s);

$rs_post_b_no = "";

if ($arr_rs_post) {
	$rs_post_b_code = trim($arr_rs_post[0]["B_CODE"]);
	$rs_post_b_no = trim($arr_rs_post[0]["B_NO"]);
	$rs_post_writer_id = trim($arr_rs_post[0]["WRITER_ID"]);
	$rs_post_hit_cnt = trim($arr_rs_post[0]["HIT_CNT"]);
	$rs_post_cate_01 = trim($arr_rs_post[0]["CATE_01"]);
	$rs_post_title = SetStringFromDB($arr_rs_post[0]["TITLE"]);
	$rs_post_title = check_html($rs_post_title);
	$rs_post_reg_date = trim($arr_rs_post[0]["REG_DATE"]);
	if ($rs_post_cate_01) {
		if($b == "B_1_11") {
			$rs_post_title = "[" . getDcodeName($conn, "MA_TYPE", $rs_post_cate_01) . "] " . $rs_post_title;
		} else if($b == "B_1_12") {
		$rs_post_title = "[" . getDcodeName($conn, "RESEARCH_REPORT", $rs_post_cate_01) . "] " . $rs_post_title;
		}
	}
	
	$rs_post_reg_date = date("Y.m.d", strtotime($rs_post_reg_date));
}

switch ($rs_cate_01) {
	case "":
		$COLOR_TAG = "<span class='badge badge__lg badge__color1'>공통</span>";
		break;
	case "ALL":
		$COLOR_TAG = "<span class='badge badge__lg badge__color1'>공통</span>";
		break;
	case "SUSI":
		$COLOR_TAG = "<span class='badge badge__lg badge__color3'>수시</span>";
		break;
	case "JEONGSI":
		$COLOR_TAG = "<span class='badge badge__lg badge__color2'>정시</span>";
		break;
	case "SUNGIN":
		$COLOR_TAG = "<span class='badge badge__lg badge__color6'>성인·재직자</span>";
		break;
	case "JEOEGUK":
		$COLOR_TAG = "<span class='badge badge__lg badge__color4'>재외국민</span>";
		break;
	case "PYEONIP":
		$COLOR_TAG = "<span class='badge badge__lg badge__color7'>편입학</span>";
		break;
	case "SCHOOL":
		$COLOR_TAG = "<span class='badge badge__lg badge__color5'>고교연계</span>";
		break;
	case "ETC":
		$COLOR_TAG = "<span class='badge badge__lg badge__color6'>기타</span>";
		break;
}

$rs_reg_date = date("Y.m.d", strtotime($rs_reg_date));

if($b == "B_1_11") {
	if($_SESSION['m_id'] !== $rs_writer_id) {
		echo "<script>alert('권한이 없습니다.'); history.back();</script>";
        exit;
	}
}
?>
<!-- S: Container -->
<section id="container">
	<?
	#require "../_common/pagenavi.php"; 
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
			<!-- // content-header -->

			<!-- content-body -->
			<div class="content-body">
				<!-- 게시판상세 페이지 -->
				<div class="board-view-page">
					<!-- 타이틀 영역 -->
					<div class="view-title-wrap">
						<h2 class="title"><?= $rs_title ?></h2>
						<div class="info">
							<span class="date"><?= $rs_reg_date ?></span>
							<span class="form"><?= $rs_writer_nm ?></span>
						</div>
					</div>
					<!-- // 타이틀 영역 -->
					<!-- 게시판상세 영역 -->
					<div class="board-view-wrap">
						<div class="board-view">
							<div class="board-view-cont">
								<div class="file-area">
									<?
									for ($j = 0; $j < sizeof($arr_rs_files); $j++) {
										$RS_FILE_NO = trim($arr_rs_files[$j]["FILE_NO"]);
										$RS_FILE_RNM = trim($arr_rs_files[$j]["FILE_RNM"]);
										?>
										<div class="link-item">
											<a
												href="/_common/new_download_file.php?menu=boardfile&file_no=<?= $RS_FILE_NO ?>"><?= $RS_FILE_RNM ?></a>
										</div>
									<?
									}
									?>
									<!-- <div class="link-item"><a href="공지사항_안내_별첨문서_01_241101.pdf" download="">공지사항_안내_별첨문서_01_241101.pdf</a></div>
									<div class="link-item"><a href="공지사항_안내_별첨문서_01_241101.pdf" download="">공지사항_안내_별첨문서_01_241101.pdf</a></div>
									<div class="link-item"><a href="공지사항_안내_별첨문서_01_241101.pdf" download="">공지사항_안내_별첨문서_01_241101.pdf</a></div> -->
								</div>
								
								

								<div class="view-area">
									<div class="cont">
										<? $rs_contents = str_replace("&quot;", "\"", $rs_contents); ?>
										<?= $rs_contents ?>
									</div>
								</div>
								
								<!-- 문의 사항 답변 -->
								<? if ($rs_reply_state == "Y") {?>
								<div class="feedback-area">
										<div class="feedback-title">
											<h3 class="title">[답변] <?= $rs_title ?></h3>
											<div class="info">
												<span class="date"><?= $rs_reply_date ?></span>
												<span class="form"><?= getAdminName($conn, $rs_reply_adm); ?></span>
											</div>
										</div>
										<div class="feedback-cont">
											<?= $rs_reply ?>
										</div>
									</div>
								<? }?>
								<!--문의사항 답변 -->
								
								<div class="btn-list">
									<a href="javascript:void(0);" class="btn"
										onClick="document.location='/communication/?b=<?= $b ?>&nPage=<?= $nPage ?>&m_type=<?= $m_type ?>&f=<?= $f ?>&s=<?= $s ?>'">목록</a>
									<!-- <a href="document.location='/notice/?nPage=<?= $nPage ?>&m_type=<?= $m_type ?>&f=<?= $f ?>&s=<?= $s ?>'">목록</a> -->
								</div>
							</div>
						</div>
						<div class="board-view-page">

							<div class="pager<? if ($rs_post_b_no == "") { ?> disabled<? } ?>">
								<span class="prev-view"><span class="txt">이전글</span></span>
								<? if ($rs_post_b_no == "") { ?>
									<a class="link">이전글이 없습니다.</a>
										<? } else { ?>
										
										<? if ($b == "B_1_11") { ?>
										<a href="<? if ($_SESSION['m_id'] == $rs_post_writer_id) { ?>
												view.do?b=<?= $rs_post_b_code ?>&bn=<?= $rs_post_b_no ?>&m_type=<?= $m_type ?>&nPage=<?= $nPage ?>&f=<?= $f ?>&s=<?= $s ?>
											<? } else { ?>
												javascript:void(0);
											<? } ?>" 
											class="link"
											<? if ($_SESSION['m_id'] != $rs_post_writer_id) { ?>
												onclick="alert('해당 글을 볼 권한이 없습니다.'); return false;"
											<? } ?>>
											<?= $rs_post_title ?>
										</a>
										<span class="date"><?= $rs_post_reg_date ?></span>
										
								<? } else { ?>
									<span class="txt"></span>
									<!-- b=B_1_1&bn=5652&m_type=&nPage=0&f=&s= -->
									<a href="view.do?b=<?= $rs_post_b_code ?>&bn=<?= $rs_post_b_no ?>&m_type=<?= $m_type ?>&nPage=<?= $nPage ?>&f=<?= $f ?>&s=<?= $s ?>"><?= $rs_post_title ?></a>
									<span class="date"><?= $rs_post_reg_date ?></span>
									<? } ?>
								<? } ?>
							</div>
							
							<div class="pager<? if ($rs_pre_b_no == "") { ?> disabled<? } ?>">
    <span class="next-view"><span class="txt">다음글</span></span>
						<? if ($rs_pre_b_no == "") { ?>
							<a class="link">다음글이 없습니다.</a>
								<? } else { ?>
										
								<? if ($b == "B_1_11") { ?>
								<a href="<? if ($_SESSION['m_id'] == $rs_pre_writer_id) { ?>
										view.do?b=<?= $rs_pre_b_code ?>&bn=<?= $rs_pre_b_no ?>&m_type=<?= $m_type ?>&nPage=<?= $nPage ?>&f=<?= $f ?>&s=<?= $s ?>
									<? } else { ?>
										javascript:void(0);
									<? } ?>" 
									class="link"
									<? if ($_SESSION['m_id'] != $rs_pre_writer_id) { ?>
										onclick="alert('해당 글을 볼 권한이 없습니다.'); return false;"
									<? } ?>>
									<?= $rs_pre_title ?>
								</a>
								<span class="date"><?= $rs_pre_reg_date ?></span>
							<? } else { ?>
								<a href="view.do?b=<?= $rs_pre_b_code ?>&bn=<?= $rs_pre_b_no ?>&m_type=<?= $m_type ?>&nPage=<?= $nPage ?>&f=<?= $f ?>&s=<?= $s ?>" class="link"><?= $rs_pre_title ?></a>
								<span class="date"><?= $rs_pre_reg_date ?></span>
							<? } ?>
						<? } ?>
					</div>
							<!-- //글이 없을경우 -->
						</div>
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