<? session_start(); ?>
<?

$_PAGE_NO = "113";
$b_code = "B_1_11";
$b = isset($_POST["b"]) && $_POST["b"] !== '' ? $_POST["b"] : (isset($_GET["b"]) ? $_GET["b"] : '');
$m_type = isset($_POST["m_type"]) && $_POST["m_type"] !== '' ? $_POST["m_type"] : (isset($_GET["m_type"]) ? $_GET["m_type"] : '');
$mode = isset($_POST["mode"]) && $_POST["mode"] !== '' ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : '');

// 로그인 상태 확인
require "../_common/common_inc.php";
require "../_classes/biz/board/board.php";
require "../_classes/biz/member/member.php";


$rs_m = selectMember($conn, $_SESSION['m_no']);


if($mode == "registeQNA") {
	
    $title = trim($_POST['title']); //제목
    $content = trim($_POST['content']); //내용
	$cate_01 = isset($_POST["QNA_TYPE"]) && $_POST["QNA_TYPE"] !== '' ? $_POST["QNA_TYPE"] : (isset($_GET["QNA_TYPE"]) ? $_GET["QNA_TYPE"] : ''); //내용
	$writer_id = $rs_m[0]['M_ID']; // 아이디
	$m_pwd = $rs_m[0]['M_PWD']; // 비밀번호
	$email = $rs_m[0]['M_EMAIL']; // 이메일
	if(!$rs_m[0]['M_NAME']) {
		$writer_nm = $rs_m[0]['M_ORGAN_NAME']; // NAME
	}else {
		$writer_nm= $rs_m[0]['M_NAME']; // NAME
	}
	$ref_ip = $_SERVER['REMOTE_ADDR'];
	$reg_date = date("Y-m-d H:i:s", strtotime("0 day"));
	// 데이터 매핑
    $arr_data = array(
        "B_CODE" => "B_1_11", // 게시판 코드
        "TITLE" => $title, // 제목
		"CATE_01" => $cate_01, //문의 구분
        "WRITER_ID" => $writer_id, // 세션에서 작성자 ID 가져오기
		"WRITER_NM" => $writer_nm,
		"WRITER_NICK" => $writer_nm,   // 닉
		"WRITER_PW" => $m_pwd,
		"EMAIL" => $email,
        "CONTENTS" => $content, // 내용
		"REF_IP" => $ref_ip,
        "SECRET_TF" => "Y", // 비밀글 여부 (기본값: N)
		"COMMENT_TF" => "Y", // 답변 여부 (기본값: N)
        "TOP_TF" => "N",                 // 상단 고정 여부 (기본값: N)
		"USE_TF" => "Y",                 // 사용 여부 (기본값: Y)
		"DEL_TF" => "N",                  // 삭제 여부 (기본값: N)
		"DATE_USE_TF" => "N",			 // 게시 기간 사용 여부
		"REG_DATE" => $reg_date
    );
	
    // 게시글 등록
    try {
		$new_b_no = insertBoard($conn, $arr_data);
        if ($new_b_no) {
            echo "<script>alert('게시글이 성공적으로 등록되었습니다.'); window.location.href='/communication?b=B_1_11';</script>";
        } else {
            throw new Exception('게시글 등록에 실패했습니다.');
        }
    } catch (Exception $e) {
        echo "<script>alert('오류가 발생했습니다. 관리자에게 문의하세요.');</script>";
		echo "오류 메시지: " . $e->getMessage() . "\n";
    }
}
	
?>
<script>


	function js_list() {
		window.location.href = 'contact_write.php'
	}
	
	 function js_write() {
            const frm = document.frm;
            if (frm.title.value.trim() === "") {
			alert('문의 제목을 입력해주세요.');
			frm.title.focus();
			return false;
		}

		if (frm.content.value.trim() === "") {
			alert('문의 내용을 입력해주세요.');
			frm.content.focus();
			return false;
		}

		if (!frm.sCheck1.checked) {
			alert('개인정보 수집 및 이용에 대한 동의가 필요합니다.');
			frm.sCheck1.focus();
			return false;
		}

            frm.submit();
        }
</script>

	<div class="wrapper sub-wrapper">
<main role="main" class="container">
			<!-- content -->
			<div id="content" class="content notice-list-page">
				<!-- content-header -->
				<div class="content-header">
				<?
					require "../_common/content-header.php";
				?>
				</div>
				<!-- // content-header -->
				<!-- content-body -->
				<div class="content-body">
					<!-- 게시판 등록 페이지 -->
					<div class="board-write-page">
						<!-- 타이틀 영역 -->
						<div class="title-wrap">
								<h2 class="title"><?= $seo_title ?></h2>
						</div>
						<!-- // 타이틀 영역 -->
						<!-- 게시판목록 영역 -->
						<div class="write-wrap">
							<form class="write-form" id="form" name="frm" method="post" action="contact_write.do">
							<input type="hidden" name="mode" value ="registeQNA">
								<p class="req">필수입력사항</p>
								<div class="field-wrap">
									<div class="field">
										<div class="labels"><span class="txt require">문의 구분</span></div>
										<div class="info-inp-wrap">
											<div class="inp-wrap classType">
												<div class="frm-sel h-48">
												<?= makeSelectBoxOnChange2($conn, "QNA_TYPE", "QNA_TYPE", "", "", "", $rs_m_email) ?>
												</div>
											</div>
										</div><p class="info-txt" id="pwdChkMessage"></p>
									</div>
									<div class="field">
										<div class="labels">
											<label class="txt require">문의 제목</label><!-- 필수입력 시 : require -->
										</div>
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-inp h-48">
													<input type="text" name="title" id="title" placeholder="제목을 입력해주세요" title="" class="inp">
												</div>
											</div>
										</div>
									</div>
									<div class="field">
										<div class="labels">
											<span class="txt require">문의 내용</span><!-- 필수입력 시 : require -->
										</div>
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-textarea h-48">
													<textarea name="content" id="content" cols="50" rows="8" class="textarea" placeholder="내용을 입력해주세요"></textarea>
												</div>
											</div>
										</div>
									</div>

									<div class="agree-box">
										<div class="info-inp-wrap">
											<div class="inp-wrap">
												<div class="frm-chk agree">
													<input type="checkbox" name="sCheck1" id="sCheck12">
													<label for="sCheck12"><span class="bold">개인정보 수집 및 이용에 대한 동의</span></label>
												</div>
											</div>
											<div class="text-box-wrap">
												<div class="text-box">
													<div class="text-content">
														<p>문의 게시판 운영을 위한 최소한의 개인정보 수집&middot;이용이 필요합니다.</p>
														<p>입력된 정보는 문의 게시판 운영 이외의 용도로는 이용되지 않습니다.</p>

														<div class="partition">
															<p class="bold">개인정보 수집 및 이용에 대한 동의</p>
															<p>1. 수집목적 : 게시물 작성</p>
															<p>2. 수집항목 : 작성자명</p>
															<p>3. 보유 및 보유기간 : 본인 게시물 삭제 요청 시 또는 게시후 2년마다 일괄 삭제</p>
														</div>
														<br>
														<p>동의를 거부하실 수 있습니다. 동의 거부 시에는 게시물을 올릴 수 없습니다.</p>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="btn-wrap">
										<button type="button" class="btn-basic h-56" onclick="js_list();">
											<span>목록</span>
										</button>
										<button type="button" class="btn-basic h-56 primary" onclick="js_write();">
											<span>등록</span>
										</button>
									</div>
								</div>
							</form>
						</div>
						<!-- // 게시판목록 영역 -->
					</div>
					<!-- // 게시판목록 페이지 -->
				</div>
				<!-- // content-body -->
			</div>
			<!-- // content -->
		</main>
		<footer class="footer">
			<?
				require "../_common/front_footer.php";
			?>
		</footer>
		</div>