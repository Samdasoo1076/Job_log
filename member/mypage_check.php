<? session_start();?>
<?
	$_PAGE_NO = "101";
	$b = ""; // 페이지마다 세팅 해줘야됨

	require_once $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/member/member.php";

	// 로그인 상태 확인
if (!isset($_SESSION['m_no']) || empty($_SESSION['m_no'])) {
    header("Location: /member/login.do");
    exit;
}
	
	$m_no = $_SESSION['m_no'];
	$page_header_type = 'mypage';


?>





<script>
$(document).ready(function () {
    // 비밀번호 입력 필드에 이벤트 추가
    $('.frm-inp input[type="password"]').on('input', function () {
        const password = $(this).val();

        if (password.trim() !== '') {
            // 입력된 값이 있으면 버튼 활성화
            $('.btn-basic').prop('disabled', false);
        } else {
            // 입력된 값이 없으면 버튼 비활성화
            $('.btn-basic').prop('disabled', true);
        }
    });

	$("input").on("keypress", function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            $(".btn-basic").trigger("click");
        }
    });

    // 확인 버튼 클릭 이벤트
    $('.btn-basic').on('click', function () {
        const password = $('.frm-inp input[type="password"]').val();

        if (password.trim() === '') {
            alert('비밀번호를 입력해주세요.');
            return;
        }

        // 서버로 비밀번호 전송 (AJAX 요청)
        $.ajax({
            type: 'POST',
            url: '/_common/ajax_mypage_check_dml.php', // 비밀번호 확인용 파일 경로
            data: { password },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // 비밀번호가 확인되면 마이페이지 수정 페이지로 이동
                    window.location.href = '/member/mypage_modify.do';
                } else {
                    // 비밀번호가 틀리면 에러 메시지 표시
                    alert(response.message || '비밀번호가 일치하지 않습니다.');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX 요청 실패:', error);
                alert('서버 요청에 실패했습니다. 다시 시도해주세요.');
            }
        });
    });
});
</script>

</head>


		<!-- Container -->
		<main role="main" class="container">
			<!-- content -->
			<div id="content" class="content notice-list-page">
				<!-- content-header -->
				<div class="content-header">
				<?
					require $_SERVER['DOCUMENT_ROOT'] . "/_common/hard_content_header.php";
				?>
				</div>
				<!-- // content-header -->
				<!-- content-body -->
				<div class="content-body">
					<!-- 게시판목록 페이지 -->
					<div class="member-page">
						<!-- 타이틀 영역 -->
						<!-- <div class="title-wrap">
							<h2 class="title">나의 회원정보</h2>
						</div> -->
						<!-- // 타이틀 영역 -->

						<div class="mypage-wrap">
							<!-- 비밀번호 확인 -->
							<div class="login-form-tit-has">
								<h3 class="title">보안을 위해 비밀번호를 한 번 더 입력해 주세요.</h3>
								<div class="login-form-wrap pad-b60 bg">
									<div class="login-form">
										<div class="info-inp-wrap login-filed">
											<div class="labels"><span class="txt">비밀번호</span></div>
											<div class="inp-wrap">
												<div class="frm-inp h-48">
													<input type="password" name="" id="" placeholder="대/소문자를 구분하여 입력해주세요​" title="" class="inp">
												</div>
											</div>
											<!-- <p class="error-txt">에러메시지 입니다.</p> error 일경우, .error-txt 추가 -->
										</div>
										
										<button type="button" class="btn-basic h-56 primary fluid login" disabled> 
											<span>확인</span>
										</button>
									</div>
								</div>
							</div>
							<!-- //비밀번호 확인 -->
						</div>
						
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
			#====================================================================
			# layout header
			#====================================================================
			require "../layout/include_footer.php";
			?>
		</footer>
		<!-- // include_footer.html -->
	</div>
	</div>
</body>

</html>