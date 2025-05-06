<?
	if ($_SESSION['s_selected_group_idx'] == "") {
?>
<script>
	alert('장시간 사용 하지않아 접속이 종료 되었습니다. 다시 로그인 하셔야 합니다.');
	document.location = '/apply/';
</script>
<?
		db_close($conn);
		exit;
	}
?>