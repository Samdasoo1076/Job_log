<?session_start();?>
<?
header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");
# =============================================================================
# File Name    : post_search_popup.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-12-03
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#==============================================================================
# Confirm right
#==============================================================================

	$sPageRight_		= "Y";
	$sPageRight_R		= "Y";
	$sPageRight_I		= "Y";
	$sPageRight_U		= "Y";
	$sPageRight_D		= "Y";
	$sPageRight_F		= "Y";
	
#====================================================================
# common_header
#====================================================================
	require "../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";

?>
<!DOCTYPE HTML>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="/manager/css/common.css" media="all" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/manager/js/jquery.nicefileinput.min.js"></script>
<script type="text/javascript" src="/manager/js/jquery-ui.js"></script>
<script type="text/javascript" src="/manager/js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="/manager/js/ui.js"></script>
<script type="text/javascript" src="/manager/js/common.js"></script>
<style type="text/css">

	html { overflow:hidden; }
	body,div,p,img,span,input,label,a{padding:0; margin:0;}
	img{border:0;}

	body {
	margin-left: 0px;
	margin-top: 0px;
 }

</style>
<style type="text/css">
<!--
/*#pop_table {z-index: 1; left: 80; overflow: auto; width: 500; height: 220}*/
#pop_table_scroll { z-index: 1;  overflow: auto; height: 368px; }
-->
</style>
<script language="javascript">

	$(document).ready(function(){
		$("#addr").keyup(function(e) {
			if(e.keyCode == 13) {
				$("#btn_search_addr").trigger("click");
			}
		});
	});

	$(document).on("click", "#btn_search_addr", function() {
		
		if ($("#addr").val().trim() == "") {
			alert("검색어를 입력하세요.");
			return;
		}

		var addr_str = encodeURI($("#addr").val().trim());
		
		var request = $.ajax({
			url:"http://www.juso.go.kr/addrlink/addrLinkApi.do?currentPage=1&countPerPage=3000&keyword="+addr_str+"&confmKey=U01TX0FVVEgyMDE5MTEyNDE2MzAxMzEwOTIxNTU=&resultType=json",
			type:"GET",
			dataType:"json"
		});

		request.done(function(data) {

			var errCode				= data.results.common.errorCode;
			var errorMessage	= data.results.common.errorMessage;
			var countPerPage	= data.results.common.countPerPage;
			var totalCount		= data.results.common.totalCount;
			var currentPage		= data.results.common.currentPage;
			
			if (data.results.juso.length == 0) {
				$("#list_addr").html("<tr><td colspan='2' class='nocontents'>검색된 내용이 없습니다.</td></tr>");
			} else {

				$("#list_addr").html("");

				for (i = 0 ; i < data.results.juso.length ; i++) {
					addAddress(data.results.juso[i].zipNo, data.results.juso[i].roadAddr, data.results.juso[i].siNm, data.results.juso[i].sggNm);
				}
			}

		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			return false;
		});

	});

	function addAddress(zipNo,roadAddr,siNm,sggNm) {

		var str = "<tr><td style='text-align:center'><a href=\"javascript:setAddr('"+zipNo+"','"+roadAddr+"','"+siNm+"','"+sggNm+"')\">"+zipNo+"</a></td><td><a href=\"javascript:setAddr('"+zipNo+"','"+roadAddr+"','"+siNm+"','"+sggNm+"')\">"+roadAddr+"</a></td>";
		$("#list_addr").append(str);

	}
	
	function setAddr(zipNo,roadAddr,siNm,sggNm) {
		
		opener.setAddr(zipNo,roadAddr,siNm,sggNm);

		$("#addr").val("");
		$("#list_addr").html("");
		self.close();
	}

</script>

</head>
<body id="popup">
<div class="popupwrap">
	<h1>주소 검색</h1>
	<div class="popcontents">
		<div class="addr_inp">
			<div class="tit_h4 first"><h4>도로명, 건물명, 지번, 초성검색</h4></div>
			<div class="tbl_style01 left">
				<table>
					<colgroup>
						<col width="20%">
						<col width="30%">
						<col width="20%">
						<col width="30%">
					</colgroup>
					<tr>
						<td colspan="4" style="padding-top:8px;padding-bottom:8px">
							<input type="Text" name="addr" id="addr" value="" style="width:80%;IME-MODE:active" required class="txt" placeholder="예) 세종대로 209, 국립중앙박물관, 상암동 1595, 초성검색">
							<button type="button" class="button" id="btn_search_addr">주소검색</button>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="sp10"></div>
		<div class="addr_inp">
			<div id="pop_table_list">
				<div id="pop_table_scroll">
					<div class="tbl_style01 left">
						<table id='t'>
							<colgroup>
								<col width="20%">
								<col width="80%">
							</colgroup>
							<thead>
								<tr>
									<th>우편번호</th>
									<th>주소</th>
								</tr>
							</thead>
							<tbody id="list_addr">
								<tr>
									<td colspan="2" class="nocontents">검색된 내용이 없습니다.</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</body>
</html>
