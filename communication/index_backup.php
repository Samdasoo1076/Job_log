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

// ��������
//$b = "B_1_1";

$b = isset($_POST["b"]) && $_POST["b"] !== '' ? $_POST["b"] : (isset($_GET["b"]) ? $_GET["b"] : '');
$m_type = isset($_POST["m_type"]) && $_POST["m_type"] !== '' ? $_POST["m_type"] : (isset($_GET["m_type"]) ? $_GET["m_type"] : '');
$writer_id = isset($_GET['writer_id']) && $_GET['writer_id'] !== 'ALL' ? $_GET['writer_id'] : '';


if ($b == "B_1_1") {
	$_PAGE_NO = "21";
} else if ($b == "B_1_2") {
	$_PAGE_NO = "75";
} else if ($b == "B_1_3") {
	$_PAGE_NO = "24";
} else if ($b == "B_1_4") {
	$_PAGE_NO = "109";
} else if ($b == "B_1_5") {
	$_PAGE_NO = "22";
} else if($b == "B_1_11") {
	$_PAGE_NO = "112";
} else if($b =="B_1_12"){
	$_PAGE_NO = "114";
} else {
	$_PAGE_NO = "21";
}

/*
	if ($m_type == "EMP") {
		$_PAGE_NO = "13";
	} else if ($m_type == "INS") {
		$_PAGE_NO = "21";
	} else if ($m_type == "BU") {
		$_PAGE_NO = "94";
	} else if ($m_type == "CO") {
		$_PAGE_NO = "29";
	} else if ($m_type == "ACH") {
		$_PAGE_NO = "35";
	} else if ($m_type == "ALL") {
		$_PAGE_NO = "45";
	} else {
		$_PAGE_NO = "45";
	}
*/

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

//$arr_rs_top = listBoardTop($conn, $b, $m_type, $con_cate_02, $con_cate_03, $con_cate_04, $con_writer_id, $keyword, $con_reply_state, $con_use_tf, $con_del_tf, $f, $s, 5);

    // �̸� ����ŷ �Լ� ����
    function maskName($name) {
        $len = mb_strlen($name, 'UTF-8'); // �̸� ����
        if ($len <= 1) {
            // �̸��� �� ������ ��� �״�� ��ȯ
            return $name;
        } elseif ($len == 2) {
            // �̸��� �� ������ ���
            return mb_substr($name, 0, 1, 'UTF-8') . '*';
        } else {
            // �̸��� �� ���� �̻��� ���
            $first = mb_substr($name, 0, 1, 'UTF-8'); // ù ����
            $last = mb_substr($name, $len - 1, 1, 'UTF-8'); // ������ ����
            return $first . str_repeat('*', $len - 2) . $last; // ����� *�� ����ŷ
        }
    }

//$rssTotalCount = isset($_GET['rssTotalCount']) ? $_GET['rssTotalCount'] : 0;

//rss data �������� �޾Ƽ� ó��wwww
$rssTotalCount = isset($_SESSION['rssTotalCount']) ? $_SESSION['rssTotalCount'] : 0;

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
			<!-- �Խ��Ǹ�� ������ -->
			<div class="board-list-page">
				<!-- Ÿ��Ʋ ���� -->
				<div class="title-wrap">
					<h2 class="title"><?= $seo_title ?></h2>
				</div>
				<!-- // Ÿ��Ʋ ���� -->

				<!-- �Խ��Ǹ�� ���� --> 
				<div class="board-list-wrap">
					<div class="board-toolbar">
					 <? if ($b != "B_1_5") { ?>
						<p class="board-count">��ü <em><?= number_format($nListCnt) ?></em>��</p>
					<? } else if ($b == "B_1_5") { ?>
						<p class="board-count">��ü <em><?= number_format($rssTotalCount) ?></em>��</p>
					<? } ?>
						<form class="board-srch" id="form" name="frm" onSubmit="js_board_search();" method="get">
							<input type="hidden" name="b" id="b" value="<?= $b ?>" />
							<input type="hidden" name="bn" id="bn" value="" />
							<input type="hidden" name="nPage" id="nPage" value="<?= $nPage ?>" />
							<input type="hidden" name="m_type" id="m_type" value="<?= $m_type ?>" />

							<? if ($b == "B_1_5") { ?>
								<div class="frm-sel h-48">
									<select name="rss" id="rss" onChange="blur();" title="�˻� ����" class="sel">
										<option value="ALL" <? if ($f == "ALL") echo "selected"; ?>>��ü</option>
										<option value="<?= $_SESSION['rss'] ?>" <? if ($f == $_SESSION['rss']) echo "selected"; ?>>�����</option>
									</select>
								</div>
							<? } else if ($b == "B_1_11") { ?>
								<div class="frm-sel h-48">
									<select name="writer_id" id="writer_id" onChange="blur();" title="�˻� ����" class="sel">
										<option value="ALL" <? if ($f == "ALL") echo "selected"; ?>>��ü</option>
										<option value="<?= $_SESSION['m_id'] ?>" <? if ($writer_id == $_SESSION['m_id']) echo "selected"; ?>>�� ����</option>
									</select>
								</div>
								<div class="frm-sel h-48">
									<select name="f" id="f" onChange="blur();" title="�˻� ����" class="sel">
										<option value="ALL" <? if ($f == "ALL") echo "selected"; ?>>��ü</option>
										<option value="TITLE" <? if ($f == "TITLE") echo "selected"; ?>>����</option>
									</select>
								</div>
							<? } else { ?>
								<div class="frm-sel h-48">
									<select name="f" id="f" onChange="blur();" title="�˻� ����" class="sel">
										<option value="ALL" <? if ($f == "ALL") echo "selected"; ?>>��ü</option>
										<option value="TITLE" <? if ($f == "TITLE") echo "selected"; ?>>����</option>
										<option value="WRITER_ID" <? if ($f == "WRITER_ID") echo "selected"; ?>>�ۼ���</option>
										<option value="CONTENTS" <? if ($f == "CONTENTS") echo "selected"; ?>>����</option>
									</select>
								</div>
							<? } ?>

							<div class="frm-inp h-48 has-on-right">
								<input type="text" name="s" id="s" value="<?= $s ?>" autocomplete="off" placeholder="�˻�� �Է����ּ���." title="�˻� Ű����" class="inp" />
								<div class="on-right">
									<button type="button" class="btn-srch" onclick="js_board_search();">
										<span class="blind">�˻�</span>
									</button>
								</div>
							</div>
						</form>
					</div>
				

					<div class="board-list">
						<? if ($b == "B_1_1") { ?>
							<table class="tbl">
								<caption>
									<strong>�������� ���</strong>
									<p>No., ����, �ۼ���, �����, ��ȸ�� ������ ������</p>
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
										<th scope="col">����</th>
										<th scope="col">�ۼ���</th>
										<th scope="col">�����</th>
										<th scope="col">��ȸ��</th>
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
											$highlightedTitle = highlightKeyword($TITLE, $s); // �˻��� ���� ����

											switch (trim($CATE_01)) {
												case "":
													$COLOR_TAG = "<span class='badge badge__color1'>����</span>";
													break;
												case "ALL":
													$COLOR_TAG = "<span class='badge badge__color1'>����</span>";
													break;
												case "SUSI":
													$COLOR_TAG = "<span class='badge badge__color3'>����</span>";
													break;
												case "JEONGSI":
													$COLOR_TAG = "<span class='badge badge__color2'>����</span>";
													break;
												case "SUNGIN":
													$COLOR_TAG = "<span class='badge badge__color6'>���Ρ�������</span>";
													break;
												case "JEOEGUK":
													$COLOR_TAG = "<span class='badge badge__color4'>��ܱ���</span>";
													break;
												case "PYEONIP":
													$COLOR_TAG = "<span class='badge badge__color7'>������</span>";
													break;
												case "SCHOOL":
													$COLOR_TAG = "<span class='badge badge__color5'>������</span>";
													break;
												case "ETC":
													$COLOR_TAG = "<span class='badge badge__color6'>��Ÿ</span>";
													break;
											}

											$CATE_01 = str_replace("^", " & ", $CATE_01);

											$is_new = "";
											if ($REG_DATE >= date("Y-m-d H:i:s", (strtotime("0 day") - ($b_new_hour * 3600)))) {
												if ($MAIN_TF <> "N") {
													$is_new = "<img src='../images/bu/ic_new.png' alt='����' width='35'/> ";
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
													//$space .= "&nbsp;��";
													$space .= "&nbsp;";
													
												$space .= "&nbsp;";
											}

											?>
											<tr>
												<td class="tbl-no">
													<? if ($TOP_TF == "Y") { ?>
														<p class="noti">����</p><!-- Case �������� -->
													<? } else { ?>
														<p><?= $rn ?></p>
													<? } ?>
												</td>
												<td class="tbl-tit">
													<p>
														<a href="view.do?b=<?= $b ?>&bn=<?= $B_NO ?>&m_type=<?= $m_type ?>&nPage=<?= $nPage ?>&f=<?= $f ?>&s=<?= $s ?>"
															class="link"><?= $highlightedTitle ?> </a> <!-- ���� strip_tags($TITLE)-->
														<? if ($F_CNT > 0) { ?>
															<a href="view.do?b=<?= $b ?>&bn=<?= $B_NO ?>&m_type=<?= $m_type ?>&nPage=<?= $nPage ?>&f=<?= $f ?>&s=<?= $s ?>"
																class="file"><span class="blind">÷������</span></a>
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
												<p>�˻������ �����ϴ�.</p>
											</td>
										</tr>
									<?
									}
									?>
								</tbody>
							</table>
						</div> <? } else if ($b == "B_1_2") { ?>
						<? require "../communication/notice_list.php"; //�����ڷ� ?> 

					<? } ?>

					<? if ($b == "B_1_3") { ?>
						<? require "../communication/manage_nav.php"; //�濵���� ?> 
					<? } ?>

					<? if ($b == "B_1_4") { ?>
						<? require "../communication/reservation_list.php"; // �ü� �Ӵ� ?> 
					<? } ?>

					<? if ($b == "B_1_5") { ?>
						<? require "../communication/annou_list.php"; //���� �ȳ� ?>
					<? } ?>
						
					<? if ($b == "B_1_11") { ?>
						<? require "../communication/contact_list.php"; // ���� ���� ?> 
					<? } ?>
						
						<? if ($b == "B_1_12") { ?>
						<? require "../communication/manage_nav.php"; // ���� ���� ?> 
					<? } ?>
					

					<? if($b !== "B_1_11") {?>
					<? if (sizeof($arr_rs) > 0) { ?>
						<?
						# ==========================================================================
						#  ����¡ ó��
						# ==========================================================================
						$strParam = $strParam . "b=" . $b . "&m_type=" . $m_type . "&f=" . $f . "&s=" . $s;

						?>
						<?= Front_Image_PageList($_SERVER["PHP_SELF"], $nPage, $nTotalPage, $nPageBlock, $strParam) ?>


					<? } ?>
					
					<? } else { ?>
					<? if (sizeof($arr_rs) > 0) { ?>
						<?
						# ==========================================================================
						#  ����¡ ó��
						# ==========================================================================
						$strParam = $strParam . "b=" . $b . "&m_type=" . $m_type . "&f=" . $f . "&s=" . $s;

						?>
						<?= Front_Image_PageList_contact_list($_SERVER["PHP_SELF"], $nPage, $nTotalPage, $nPageBlock, $strParam) ?>


						<? } ?>
					<? } ?>

				</div>
				<!-- // �Խ��Ǹ�� ���� -->
			</div>
			<!-- // �Խ��Ǹ�� ������ -->
		</div>
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
		event.preventDefault();  // �⺻ ���� ����

		// �α��� ���� Ȯ��
		if (!<?= json_encode(isset($_SESSION['m_id'])) ?>) {
			var userConfirmed = confirm("�α����� �ʿ��մϴ�. �α��� �������� �̵� �Ͻðڽ��ϱ�?");
			if (userConfirmed) {
				var currentUrl = encodeURIComponent(window.location.href);
				window.location.href = '/member/login.do?redirect=' + currentUrl; // �α��� �������� ���𷺼�
			}
			return; // �Լ� ����
		}
		window.location.href = 'contact_write.do'
	}


</script>