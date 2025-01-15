<div class="footer-sec">
	<div class="footer-logo">
		<span class="blind">WFI (재)원주미래산업진흥원</span>
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
			<li>주소 : 강원 원주시 마재2로 10 2층 (재)원주미래산업진흥원​</li>
			<li>대표자 : 조영희</li>
			<li>대표번호 : 033-764-3160​</li>
		</ul>
	</div>

	<div class="footer-copyright">
		<p>Copyright ⓒ WFI. All rights reserved.</p>
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
