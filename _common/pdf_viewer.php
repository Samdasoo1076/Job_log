<?session_start();?>
<?
# =============================================================================
# File Name    : enter_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2023-07-08
# Modify Date  : 
#	Copyright : Copyright @Ucomp Corp. All Rights Reserved.
# =============================================================================
	header('Content-Type: text/html; charset=utf-8');
	error_reporting(E_ALL & ~E_NOTICE);

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#=====================================================================
# common function
#=====================================================================
	require "../_common/config.php";

	require "../_classes/com/util/Util.php";
	require "../_classes/com/util/AES2.php";
	require "../_classes/com/etc/etc.php";
	require "../_classes/biz/enter/enter.php";

	$m_type	= isset($_POST["m_type"]) && $_POST["m_type"] !== '' ? $_POST["m_type"] : (isset($_GET["m_type"]) ? $_GET["m_type"] : '');
	$e_no		= isset($_POST["e_no"]) && $_POST["e_no"] !== '' ? $_POST["e_no"] : (isset($_GET["e_no"]) ? $_GET["e_no"] : '');

	$pdf_title		= "";
	$pdf_name			= ""; 
	$pdf_rname		= "";

	if ($m_type == "enterinfo") {

		$arr_rs = selectEnterInfo($conn, (int)$e_no);

		$pdf_title		= SetStringFromDB($arr_rs[0]["E_TITLE"]); 
		$pdf_name			= trim($arr_rs[0]["E_PDF"]); 
		$pdf_rname		= trim($arr_rs[0]["E_PDF_NM"]);

		$file_path = "/upload_data/enter/".$pdf_name;
	} 

?>
<!DOCTYPE html>
<html>
	<head>
		<title><?=$pdf_title?></title>
	</head>
	<script src="/assets/js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="/assets/js/pdfobject.js?v=20201111_4"></script>

	<body style="height: 100%; width: 100%; overflow: hidden; margin:0px; background-color: rgb(82, 86, 89);">
		<div id="pdf_wrap" class="pdf-view" style="padding:600px 0;margin: 0;paddong:0;overflow: hidden !important;border:1px solid #4d4d4d;"></div>
		<!--<embed name="2C1B471F6E24CBF739251127DAEE4735" style="position:absolute; left: 0; top: 0; width:100%; height:100%" src="<?=$file_path?>" type="application/pdf" internalid="2C1B471F6E24CBF739251127DAEE4735">-->
	</body>
<script>
	$(document).ready(function() {

		var myPDF;

		var options = {
			pdfOpenParams: { 
				navpanes: 0,
				toolbar: 0,
				statusbar: 0, 
				view: "FitV",
				pagemode: "none",
				page: 1
			},
			forcePDFJS: true,
			PDFJS_URL: "/pdfViewer/web/viewer.html"
		};

		myPDF = PDFObject.embed('<?=$file_path?>', '#pdf_wrap', options);

	});
</script>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	db_close($conn);
?>
