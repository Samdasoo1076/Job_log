<? session_start(); ?>
<?
$_PAGE_NO = "101";

// 로그인 상태 확인
if (!isset($_SESSION['m_no']) || empty($_SESSION['m_no'])) {
	echo "<script>alert('로그인 상태가 아닙니다')</script>";
	echo "<script>window.location.href='login.do';</script>";
	exit;
}


$b = ""; // 페이지마다 세팅 해줘야됨
require_once $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
require $_SERVER['DOCUMENT_ROOT'] . "/_classes/biz/member/member.php";


$m_no = $_SESSION['m_no'];
$page_header_type = 'mypage';

// 사용자 조회
$user_data = mypageUserMember($conn, $m_no);

// 휴대폰 번호 중간 네 자리를 ****로 변환
function maskPhoneNumber($phone)
{
	if (preg_match('/^(\d{3})-(\d{4})-(\d{4})$/', $phone, $matches)) {
		return $matches[1] . '-****-' . $matches[3];
	}
	return $phone;
}

function maskEmail($email)
{
	if (strpos($email, '@') !== false) {
		list($username, $domain) = explode('@', $email, 2);

		// 사용자 이름 마스킹 (4번째 이후 문자부터 '*')
		if (strlen($username) > 4) {
			$masked = substr($username, 0, 4) . str_repeat('*', strlen($username) - 4);
		} else {
			$masked = str_repeat('*', strlen($username));
		}
		return $masked . '@' . $domain;
	}
	return $email; // 이메일 형식이 아닌 경우 원래 값 반환
}


// 비밀번호를 전체 마스킹 처리하는 함수
function maskPassword($password)
{
	if (!empty($password)) {
		return str_repeat('*', strlen($password)); // 비밀번호 길이만큼 *로 대체
	}
	return ''; // 비밀번호가 없을 경우 빈 문자열 반환
}

?>

<head>
	<script></script>
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
			<!-- 타이틀 영역 -->
			<div class="title-wrap">
				<h2 class="title">나의 회원정보</h2>
			</div>
			<!-- // 타이틀 영역 -->
			<!-- 본문 영역 -->
			<div class="mypage-wrap">
				<!-- 첫화면 -->
				<div class="list-cont">
					<div class="list-item">
						<div class="labels">
							아이디
						</div>
						<div class="value">
							<p class="bold"><?= $user_data['M_ID'] ?></p>
						</div>
					</div>
					<div class="list-item">
						<div class="labels">
							전화번호
						</div>
						<div class="value">
							<p><?= maskPhoneNumber(decrypt($key, $iv, $user_data['M_PHONE'])) #maskPhoneNumber($user_data['M_PHONE']) ?>
							</p>
						</div>
					</div>
					<div class="list-item">
						<div class="labels">
							이메일
						</div>
						<div class="value">
							<p><?= maskEmail(decrypt($key, $iv, $user_data['M_EMAIL'])); # maskEmail($user_data['M_EMAIL']) ?>
							</p>
						</div>
					</div>
					<div class="list-item">
						<div class="labels">
							비밀번호
						</div>
						<div class="value">
							<p><?= maskPassword($user_data['M_PWD']) ?></p>
						</div>
					</div>

					<div class="list-item">
						<div class="labels">
							회원구분
						</div>
						<div class="value">
							<div class="inp-wrap">
								<div class="frm-rdo">
									<input type="radio" name="m_gubun" id="m_gubun_p" <?= $user_data['M_GUBUN'] == 'P' ? 'checked' : '' ?> disabled>
									<label for="sRadio11"><span>개인회원</span></label>
								</div>
								<div class="frm-rdo">
									<input type="radio" name="m_gubun" id="m_gubun_c" <?= $user_data['M_GUBUN'] == 'C' ? 'checked' : '' ?> disabled>
									<label for="sRadio12"><span>기업회원</span></label>
								</div>
							</div>
						</div>
					</div>

					<? if ($user_data['M_GUBUN'] == 'P'): ?>
						<div class="list-item">
							<div class="labels">
								회원명
							</div>
							<div class="value">
								<p><?= $user_data['M_NAME'] ?></p>
							</div>
						</div>
					<? elseif ($user_data['M_GUBUN'] == 'C'): ?>
						<div class="list-item">
							<div class="labels">
								기관명
							</div>
							<div class="value">
								<p><?= $user_data['M_ORGAN_NAME'] ?></p>
							</div>
						</div>
					<? endif; ?>

					<div class="list-item">
						<div class="labels">
							회원가입날짜
						</div>
						<div class="value">
							<p><?= $user_data['REG_DATE'] ?></p>
						</div>
					</div>
					<div class="list-item" <?= $user_data['M_GUBUN'] == 'P' ? 'style="display: none;"' : '' ?>>
						<div class="labels">
							사업자등록번호
						</div>
						<div class="value">
							<p><?= $user_data['M_BIZ_NO'] ?></p>
						</div>
					</div>
					<div class="list-item">
						<div class="labels">
							주소
						</div>
						<div class="value">
							<p>
								<?= decrypt($key, $iv, $user_data['M_ADDR']); ?>
								<?= decrypt($key, $iv, $user_data['M_ADDR_DETAIL']); ?>
							</p>
						</div>
					</div>
					<div class="list-item">
						<div class="labels">
							표준산업분류
						</div>
						<div class="value">
							<p>
								<?= $user_data['M_KSIC']; ?> -
								[<?= $user_data['M_KSIC_DETAIL']; ?>]
							</p>
						</div>
					</div>
					<div class="list-item">
						<div class="labels">
							이메일 수신동의
						</div>
						<div class="value">
							<div class="inp-wrap">
								<div class="frm-rdo">
									<input type="radio" name="email_tf" id="email_tf_y"
										<?= $user_data['EMAIL_TF'] == 'Y' ? 'checked' : '' ?> disabled>
									<label for="sRadio33"><span>예</span></label>
								</div>
								<div class="frm-rdo">
									<input type="radio" name="email_tf" id="email_tf_n"
										<?= $user_data['EMAIL_TF'] == 'N' ? 'checked' : '' ?> disabled>
									<label for="sRadio44"><span>아니요</span></label>
								</div>
							</div>
						</div>
					</div>
					<div class="list-item">
						<div class="labels">
							문자 수신동의
						</div>
						<div class="value">
							<div class="inp-wrap">
								<div class="frm-rdo">
									<input type="radio" name="message_tf" id="message_tf_y"
										<?= $user_data['MESSAGE_TF'] == 'Y' ? 'checked' : '' ?> disabled>
									<label for="sRadio55"><span>예</span></label>
								</div>
								<div class="frm-rdo">
									<input type="radio" name="message_tf" id="message_tf_n"
										<?= $user_data['MESSAGE_TF'] == 'N' ? 'checked' : '' ?> disabled>
									<label for="sRadio66"><span>아니요</span></label>
								</div>
							</div>
						</div>
					</div>
					<div class="btn-wrap">
						<button type="button" class="btn-basic h-56 primary center-w360"
							onclick="location.href='/member/mypage_check.do'">
							<span>회원정보 수정하기</span>
						</button>
					</div>
				</div>
				<!-- //첫화면 -->
			</div>
			<!-- // 본문 영역 -->
		</div>
		<!-- // content-body -->
	</div>
	<!-- // content -->
</main>
<!-- // Container -->

<!-- include_footer.html -->
<footer class="footer">

	<?
	require_once "../_common/front_footer.php";
	?>
</footer>
<!-- // include_footer.html -->