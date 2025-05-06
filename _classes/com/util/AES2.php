<?php

	$key = "JINHAKSAUCOMPENCKEYAFROM20220802";
	$iv = "JINHAKSAUCOMPENC";

	function encrypt($secret_key, $iv_key, $plain_text) {
			return base64_encode(openssl_encrypt($plain_text, "aes-256-cbc", $secret_key, true, $iv_key));
	}

	function decrypt($secret_key, $iv_key, $encrypt_text) {
		return openssl_decrypt(base64_decode($encrypt_text), "aes-256-cbc", $secret_key, true, $iv_key);

	}

?>