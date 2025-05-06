			<div class="inner">
				<div class="util">
					<div class="user"><?=$_SESSION["s_adm_nm"]?> 님 </div>
					<button type="button" onClick="document.location = '/manager/admin/admin_modify.php'">내 정보조회</button>
					<button type="button" onClick="document.location = '/manager/login/logout.php'">로그아웃</button>
					<button type="button" onClick="form_url('<?=$g_site_url?>', '_blank')"><?=$g_front_title?></button>
				</div>
			</div>

<form name="frm_url" method="post">
</form>
<script>
	
	function form_url(url, target) {
		document.frm_url.action = url;
		document.frm_url.target = target;
		document.frm_url.submit();
	}
</script>