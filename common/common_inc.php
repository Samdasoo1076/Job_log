<?
	header('Content-Type: text/html; charset=utf-8');

	/* 취약점 점검 관련 추가 header 2024-10-25 */
	header("X-Content-Type-Options: nosniff");
	header("X-XSS-Protection: 1; mode=block");
	header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
	header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
	header('Pragma: no-cache');
	header('ETag: "noetag"');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

    require __DIR__ . '/config.php';
?>


<?

#=====================================================================
# common head
#=====================================================================
require $_SERVER['DOCUMENT_ROOT'] . "/common/front_head.php";

?>