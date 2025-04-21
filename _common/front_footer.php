<div class="footer-sec">
	<div class="footer-logo">
		<span class="blind">이지민 블로그</span>
	</div>

	<div class="footer-nav">
		<ul>
			<li><a href="../comm/terms.do" class="nav-link">이용약관</a></li>
			<li><a href="javascript:void(0)" class="nav-link" id="emailPolicyModalLink" data-bs-toggle="modal" data-bs-target="#emailPolicyModal">이메일무단수집거부</a></li>
			<li><a href="../comm/policy.do" class="nav-link bold">개인정보처리방침</a></li>
		</ul>
	</div>

	<div class="footer-contact">
		<ul>
			<li>이지민</li>
			<li>전화번호 : 010-4103-6966​</li>
		</ul>
	</div>

	<div class="footer-copyright">
		<p>Copyright ⓒ Lee Ji Min. All rights reserved.</p>
	</div>
</div>
<div class="topBtn">
	<a href="#" class="btn_top">
		<img src="/assets/images/main/icn-48-topbtn.svg" alt="">
		<!-- <img class="hover" src="../../assets/images/main/icn-48-topbtn.svg" alt=""> -->
		<span class="blind">TOP</span>
	</a>
</div>

<div class="modal fade policy-modal" id="emailPolicyModal" tabindex="-1" role="dialog" aria-labelledby="emailPolicyModalLabel" aria-hidden="true">
	<? require $_SERVER['DOCUMENT_ROOT'] . "/_common/email_policy_modal.php";?>
</div>
<?
#====================================================================
# DB Close
#====================================================================

	db_close($conn);
?>
