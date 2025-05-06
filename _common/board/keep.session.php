<?session_start();?>
<?
	if (!$_SESSION['s_encrypt_str']) {
		echo "F";
	} else {
		echo "T";
	}
?>