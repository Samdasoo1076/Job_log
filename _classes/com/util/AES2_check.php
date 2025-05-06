<?php

	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	// 기본 32자

	$secret_key = "JINHAKSAUCOMPENCKEYAFROM20220802";
	$iv_key = "JINHAKSAUCOMPENC";

	/*
	$data = "manager";

	$encrypted = encrypt($key, $iv, $data);

	printf("256-bit encrypted result:\n%s\n\n",$encrypted);

	echo "<br />";
*/

	function encrypt($secret_key, $iv_key, $plain_text) {
		return base64_encode(openssl_encrypt($plain_text, "aes-256-cbc", $secret_key, true, $iv_key));
	}

	function decrypt($secret_key, $iv_key, $encrypt_text) {
		return openssl_decrypt(base64_decode($encrypt_text), "aes-256-cbc", $secret_key, true, $iv_key);
	}


	$data = "ape5yb";

	$encrypted = encrypt($secret_key, $iv_key, $data);

	echo $encrypted;

	$decrypted = decrypt($secret_key, $iv_key, $encrypted);
	
	echo $decrypted;

?>