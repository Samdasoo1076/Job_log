<?session_start();?>
<?
header("x-xss-Protection:0");
header("Content-Type: text/html; charset=UTF-8");
# =============================================================================
# File Name    : pop_menu_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2019-05-20
# Modify Date  : 
#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");
	$menu_right = "AD004"; // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#==============================================================================
# Confirm right
#==============================================================================

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/menu/menu.php";
	require "../../_classes/biz/admin/admin.php";

#====================================================================
# Request Parameter
#====================================================================

	$group_name				= isset($_POST["group_name"]) && $_POST["group_name"] !== '' ? $_POST["group_name"] : (isset($_GET["group_name"]) ? $_GET["group_name"] : '');
	$group_no					= isset($_POST["group_no"]) && $_POST["group_no"] !== '' ? $_POST["group_no"] : (isset($_GET["group_no"]) ? $_GET["group_no"] : '');

	$group_no = trim($group_no);
	$group_no		= (int)$group_no;


	//$arr_rs = selectAdminGroup($conn, $group_no);

	//$rs_group_name = trim($arr_rs[0]["group_name"]); 

	$arr_rs_right = listAdminGroupMenuRight($conn, $group_no);
	
	$con_use_tf		= "Y";
	$con_del_tf		= "N";
	$search_field	= "";
	$search_str		= "";
	
	$nExist = "0";

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);


	$arr_rs = listAdminMenu($conn, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "관리자 권한 조회 (그룹 번호 : ".$group_no.") ", "List");

?>
<!DOCTYPE HTML>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link rel="shortcut icon" href="/manager/images/mobile.png" />
<link rel="apple-touch-icon" href="/manager/images/mobile.png" />
<link rel="stylesheet" type="text/css" href="../css/common.css" media="all" />
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../js/jquery.nicefileinput.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.js"></script>
<script type="text/javascript" src="../js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="../js/ui.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/httpRequest.js"></script> <!-- Ajax js -->
<script LANGUAGE="JavaScript">
<!--
	function Sendit() {
		var frm = document.frm; 

		var total = frm["menu_cd[]"].length;

		for(var i=0; i< total; i++) {
			if(frm["chk_read[]"][i].checked == true) {
				frm["read_chk[]"][i].value="Y";
			} else {
				frm["read_chk[]"][i].value="N";
			}
		}

		for(var i=0; i< total; i++) {
			if(frm["chk_reg[]"][i].checked == true) {
				frm["reg_chk[]"][i].value="Y";
			} else {
				frm["reg_chk[]"][i].value="N";
			}
		}

		for(var i=0; i< total; i++) {
			if(frm["chk_upd[]"][i].checked == true) {
				frm["upd_chk[]"][i].value="Y";
			} else {
				frm["upd_chk[]"][i].value="N";
			}
		}

		for(var i=0; i< total; i++) {
			if(frm["chk_del[]"][i].checked == true) {
				frm["del_chk[]"][i].value="Y";
			} else {
				frm["del_chk[]"][i].value="N";
			}
		}

		for(var i=0; i< total; i++) {
			if(frm["chk_file[]"][i].checked == true) {
				frm["file_chk[]"][i].value="Y";
			} else {
				frm["file_chk[]"][i].value="N";
			}
		}

		/*
		for(var i=0; i< total; i++) {
			if(frm["chk_top[]"][i].checked == true) {
				frm["top_chk[]"][i].value="Y";
			} else {
				frm["top_chk[]"][i].value="N";
			}
		}

		for(var i=0; i< total; i++) {
			if(frm["chk_main[]"][i].checked == true) {
				frm["main_chk[]"][i].value="Y";
			} else {
				frm["main_chk[]"][i].value="N";
			}
		}
		*/

		frm.submit();
	}

	function setFlag(menu_cd, cnt, kind) {

		var frm = document.frm; 
		var total = frm["menu_cd[]"].length;
				
		// 조회
		if (kind == "R") {
			
			if (frm["chk_read[]"][cnt].checked == true) {
			
				for(var i=0; i< total; i++) {
				
					// 대분류
					if (menu_cd.length == 2) { 
												
						if (frm["menu_cd[]"][i].value.substring(0,2) == menu_cd) {
							frm["read_chk[]"][i].value = "Y";	
							frm["chk_read[]"][i].checked = true;	
						}	
					}

					// 중분류
					if (menu_cd.length == 4) { 							
						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,2)) {
							frm["read_chk[]"][i].value = "Y";	
							frm["chk_read[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value.substring(0,4) == menu_cd) {
							frm["read_chk[]"][i].value = "Y";	
							frm["chk_read[]"][i].checked = true;	
						}	
					}
					
					// 소분류
					if (menu_cd.length == 6) { 							
						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,2)) {
							frm["read_chk[]"][i].value = "Y";	
							frm["chk_read[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,4)) {
							frm["read_chk[]"][i].value = "Y";	
							frm["chk_read[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value.substring(0,6) == menu_cd) {
							frm["read_chk[]"][i].value = "Y";	
							frm["chk_read[]"][i].checked = true;	
						}	
					}


				} 
			} else {
			
				for(var i=0; i< total; i++) {
				
					// 대분류
					if (menu_cd.length == 2) { 
												
						if (frm["menu_cd[]"][i].value.substring(0,2) == menu_cd) {
							frm["read_chk[]"][i].value = "N";	
							frm["chk_read[]"][i].checked = false;	
						}	
					}

					// 중분류
					if (menu_cd.length == 4) { 												
						if (frm["menu_cd[]"][i].value.substring(0,4) == menu_cd) {
							frm["read_chk[]"][i].value = "N";	
							frm["chk_read[]"][i].checked = false;	
						}	
					}				

				} 			
			}
		}			
		
		// 등록
		if (kind == "I") {
			
			if (frm["chk_reg[]"][cnt].checked == true) {
			
				for(var i=0; i< total; i++) {
				
					// 대분류
					if (menu_cd.length == 2) { 
												
						if (frm["menu_cd[]"][i].value.substring(0,2) == menu_cd) {
							frm["reg_chk[]"][i].value = "Y";	
							frm["chk_reg[]"][i].checked = true;	
						}	
					
					}

					// 중분류
					if (menu_cd.length == 4) { 							
						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,2)) {
							frm["reg_chk[]"][i].value = "Y";	
							frm["chk_reg[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value.substring(0,4) == menu_cd) {
							frm["reg_chk[]"][i].value = "Y";	
							frm["chk_reg[]"][i].checked = true;	
						}	
					}
					
					// 소분류
					if (menu_cd.length == 6) { 							
						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,2)) {
							frm["reg_chk[]"][i].value = "Y";	
							frm["chk_reg[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,4)) {
							frm["reg_chk[]"][i].value = "Y";	
							frm["chk_reg[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value.substring(0,6) == menu_cd) {
							frm["reg_chk[]"][i].value = "Y";	
							frm["chk_reg[]"][i].checked = true;	
						}	
					}


				} 
			} else {
			
				for(var i=0; i< total; i++) {
				
					
					// 대분류
					if (menu_cd.length == 2) { 
												
						if (frm["menu_cd[]"][i].value.substring(0,2) == menu_cd) {
							frm["reg_chk[]"][i].value = "N";	
							frm["chk_reg[]"][i].checked = false;	
						}	
					}

					// 중분류
					if (menu_cd.length == 4) { 												
						if (frm["menu_cd[]"][i].value.substring(0,4) == menu_cd) {
							frm["reg_chk[]"][i].value = "N";	
							frm["chk_reg[]"][i].checked = false;	
						}	
					}				

				} 			
			}
		}			


		// 수정
		if (kind == "U") {
			
			if (frm["chk_upd[]"][cnt].checked == true) {
			
				for(var i=0; i< total; i++) {
				
					// 대분류
					if (menu_cd.length == 2) { 
												
						if (frm["menu_cd[]"][i].value.substring(0,2) == menu_cd) {
							frm["upd_chk[]"][i].value = "Y";	
							frm["chk_upd[]"][i].checked = true;	
						}	
					}

					// 중분류
					if (menu_cd.length == 4) { 							
						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,2)) {
							frm["upd_chk[]"][i].value = "Y";	
							frm["chk_upd[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value.substring(0,4) == menu_cd) {
							frm["upd_chk[]"][i].value = "Y";	
							frm["chk_upd[]"][i].checked = true;	
						}	
					}
					
					// 소분류
					if (menu_cd.length == 6) { 							
						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,2)) {
							frm["upd_chk[]"][i].value = "Y";	
							frm["chk_upd[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,4)) {
							frm["upd_chk[]"][i].value = "Y";	
							frm["chk_upd[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value.substring(0,6) == menu_cd) {
							frm["upd_chk[]"][i].value = "Y";	
							frm["chk_upd[]"][i].checked = true;	
						}	
					}


				} 
			} else {
			
				for(var i=0; i< total; i++) {
				
					
					// 대분류
					if (menu_cd.length == 2) { 
												
						if (frm["menu_cd[]"][i].value.substring(0,2) == menu_cd) {
							frm["upd_chk[]"][i].value = "N";	
							frm["chk_upd[]"][i].checked = false;	
						}	
					}

					// 중분류
					if (menu_cd.length == 4) { 												
						if (frm["menu_cd[]"][i].value.substring(0,4) == menu_cd) {
							frm["upd_chk[]"][i].value = "N";	
							frm["chk_upd[]"][i].checked = false;	
						}	
					}				

				} 			
			}
		}			


		// 삭제
		if (kind == "D") {
			
			if (frm["chk_del[]"][cnt].checked == true) {
			
				for(var i=0; i< total; i++) {
				
					// 대분류
					if (menu_cd.length == 2) { 
												
						if (frm["menu_cd[]"][i].value.substring(0,2) == menu_cd) {
							frm["del_chk[]"][i].value = "Y";	
							frm["chk_del[]"][i].checked = true;	
						}	
					}

					// 중분류
					if (menu_cd.length == 4) { 							
						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,2)) {
							frm["del_chk[]"][i].value = "Y";	
							frm["chk_del[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value.substring(0,4) == menu_cd) {
							frm["del_chk[]"][i].value = "Y";	
							frm["chk_del[]"][i].checked = true;	
						}	
					}
					
					// 소분류
					if (menu_cd.length == 6) { 							
						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,2)) {
							frm["del_chk[]"][i].value = "Y";	
							frm["chk_del[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,4)) {
							frm["del_chk[]"][i].value = "Y";	
							frm["chk_del[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value.substring(0,6) == menu_cd) {
							frm["del_chk[]"][i].value = "Y";	
							frm["chk_del[]"][i].checked = true;	
						}	
					}


				} 
			} else {
			
				for(var i=0; i< total; i++) {
				
					
					// 대분류
					if (menu_cd.length == 2) { 
												
						if (frm["menu_cd[]"][i].value.substring(0,2) == menu_cd) {
							frm["del_chk[]"][i].value = "N";	
							frm["chk_del[]"][i].checked = false;	
						}	
					}

					// 중분류
					if (menu_cd.length == 4) { 												
						if (frm["menu_cd[]"][i].value.substring(0,4) == menu_cd) {
							frm["del_chk[]"][i].value = "N";	
							frm["chk_del[]"][i].checked = false;	
						}	
					}				

				} 			
			}
		}			

		// 파일
		if (kind == "F") {
			
			if (frm["chk_file[]"][cnt].checked == true) {
			
				for(var i=0; i< total; i++) {
				
					// 대분류
					if (menu_cd.length == 2) { 
												
						if (frm["menu_cd[]"][i].value.substring(0,2) == menu_cd) {
							frm["file_chk[]"][i].value = "Y";	
							frm["chk_file[]"][i].checked = true;	
						}	
					}

					// 중분류
					if (menu_cd.length == 4) { 							
						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,2)) {
							frm["file_chk[]"][i].value = "Y";	
							frm["chk_file[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value.substring(0,4) == menu_cd) {
							frm["file_chk[]"][i].value = "Y";	
							frm["chk_file[]"][i].checked = true;	
						}	
					}
					
					// 소분류
					if (menu_cd.length == 6) { 							
						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,2)) {
							frm["file_chk[]"][i].value = "Y";	
							frm["chk_file[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,4)) {
							frm["file_chk[]"][i].value = "Y";	
							frm["chk_file[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value.substring(0,6) == menu_cd) {
							frm["file_chk[]"][i].value = "Y";	
							frm["chk_file[]"][i].checked = true;	
						}	
					}
				} 
			} else {
			
				for(var i=0; i< total; i++) {
				
					
					// 대분류
					if (menu_cd.length == 2) { 
												
						if (frm["menu_cd[]"][i].value.substring(0,2) == menu_cd) {
							frm["file_chk[]"][i].value = "N";	
							frm["chk_file[]"][i].checked = false;	
						}	
					}

					// 중분류
					if (menu_cd.length == 4) { 												
						if (frm["menu_cd[]"][i].value.substring(0,4) == menu_cd) {
							frm["file_chk[]"][i].value = "N";	
							frm["chk_file[]"][i].checked = false;	
						}	
					}
				}
			}
		}
		// top 공지
		/**/
		if (kind == "T") {
			
			if (frm["chk_top[]"][cnt].checked == true) {
			
				for(var i=0; i< total; i++) {
				
					// 대분류
					if (menu_cd.length == 2) { 
												
						if (frm["menu_cd[]"][i].value.substring(0,2) == menu_cd) {
							frm["top_chk[]"][i].value = "Y";	
							frm["chk_top[]"][i].checked = true;	
						}	
					}

					// 중분류
					if (menu_cd.length == 4) { 							
						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,2)) {
							frm["top_chk[]"][i].value = "Y";	
							frm["chk_top[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value.substring(0,4) == menu_cd) {
							frm["top_chk[]"][i].value = "Y";	
							frm["chk_top[]"][i].checked = true;	
						}	
					}
					
					// 소분류
					if (menu_cd.length == 6) { 							
						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,2)) {
							frm["top_chk[]"][i].value = "Y";	
							frm["chk_top[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,4)) {
							frm["top_chk[]"][i].value = "Y";	
							frm["chk_top[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value.substring(0,6) == menu_cd) {
							frm["top_chk[]"][i].value = "Y";	
							frm["chk_top[]"][i].checked = true;	
						}	
					}
				} 
			} else {
			
				for(var i=0; i< total; i++) {
				
					
					// 대분류
					if (menu_cd.length == 2) { 
												
						if (frm["menu_cd[]"][i].value.substring(0,2) == menu_cd) {
							frm["top_chk[]"][i].value = "N";	
							frm["chk_top[]"][i].checked = false;	
						}	
					}

					// 중분류
					if (menu_cd.length == 4) { 												
						if (frm["menu_cd[]"][i].value.substring(0,4) == menu_cd) {
							frm["top_chk[]"][i].value = "N";	
							frm["chk_top[]"][i].checked = false;	
						}	
					}
				}
			}
		}
		
		// 메인
		/**/
		if (kind == "M") {
			
			if (frm["chk_main[]"][cnt].checked == true) {
			
				for(var i=0; i< total; i++) {
				
					// 대분류
					if (menu_cd.length == 2) { 
												
						if (frm["menu_cd[]"][i].value.substring(0,2) == menu_cd) {
							frm["main_chk[]"][i].value = "Y";	
							frm["chk_main[]"][i].checked = true;	
						}	
					}

					// 중분류
					if (menu_cd.length == 4) { 							
						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,2)) {
							frm["main_chk[]"][i].value = "Y";	
							frm["chk_main[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value.substring(0,4) == menu_cd) {
							frm["main_chk[]"][i].value = "Y";	
							frm["chk_main[]"][i].checked = true;	
						}	
					}
					
					// 소분류
					if (menu_cd.length == 6) { 							
						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,2)) {
							frm["main_chk[]"][i].value = "Y";	
							frm["chk_main[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value == menu_cd.substring(0,4)) {
							frm["main_chk[]"][i].value = "Y";	
							frm["chk_main[]"][i].checked = true;	
						}	

						if (frm["menu_cd[]"][i].value.substring(0,6) == menu_cd) {
							frm["main_chk[]"][i].value = "Y";	
							frm["chk_main[]"][i].checked = true;	
						}	
					}
				} 
			} else {
			
				for(var i=0; i< total; i++) {
				
					
					// 대분류
					if (menu_cd.length == 2) { 
												
						if (frm["menu_cd[]"][i].value.substring(0,2) == menu_cd) {
							frm["main_chk[]"][i].value = "N";	
							frm["chk_main[]"][i].checked = false;	
						}	
					}

					// 중분류
					if (menu_cd.length == 4) { 												
						if (frm["menu_cd[]"][i].value.substring(0,4) == menu_cd) {
							frm["main_chk[]"][i].value = "N";	
							frm["chk_main[]"][i].checked = false;	
						}	
					}
				}
			}

		}
			

		//alert(menu_cd);
		//alert(kind);
	}

//-->
</script>
</head>
<body id="popup">
<div class="popupwrap">
	<h1>관리자 메뉴 등록</h1>
	<div class="popcontents">
		<div class="tbl_style01 left">

<form name="frm" action="admingroup_right_dml.php" method="post">
<input type="hidden" name="group_no" value="<?=$group_no?>">	


			<table id='t'>
				<colgroup>
					<col width="40%">
					<col width="12%">
					<col width="12%">
					<col width="12%">
					<col width="12%">
					<col width="12%">
					<!--
					<col width="10%">
					<col width="10%">
					-->
				</colgroup>
				<thead>
					<tr>
						<th>메뉴명</th>
						<th>조회</th>
						<th>등록</th>
						<th>수정</th>
						<th>삭제</th>
						<th>파일</th>
						<!--
						<th>상단노출</th>
						<th>메인노출</th>
						-->
					</tr>
				</thead>
				<tbody>
				<?
					
					if (sizeof($arr_rs) > 0) {
				
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
					
							//MENU_NO, MENU_CD, MENU_NAME, MENU_URL, MENU_FLAG, MENU_SEQ01, MENU_SEQ02, MENU_SEQ03, MENU_RIGHT
					
							$MENU_NO				= trim($arr_rs[$j]["MENU_NO"]);
							$MENU_CD				= trim($arr_rs[$j]["MENU_CD"]);
							$MENU_NAME			= trim($arr_rs[$j]["MENU_NAME"]);
							$MENU_URL				= trim($arr_rs[$j]["MENU_URL"]);
							$MENU_FLAG			= trim($arr_rs[$j]["MENU_FLAG"]);
							$MENU_SEQ01			= trim($arr_rs[$j]["MENU_SEQ01"]);
							$MENU_SEQ02			= trim($arr_rs[$j]["MENU_SEQ02"]);
							$MENU_SEQ03			= trim($arr_rs[$j]["MENU_SEQ03"]);
							$MENU_RIGHT			= trim($arr_rs[$j]["MENU_RIGHT"]);
							//$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
							//$DEL_TF					= trim($arr_rs[$j]["DEL_TF"]);
							//$REG_DATE				= trim($arr_rs[$j]["REG_DT"]);

							//$REG_DATE = date("Y-m-d",strtotime($REG_DATE));

							if (strlen($MENU_CD) == 2) {
								$menu_str = "<font color='blue'>⊙ ".$MENU_NAME."</font>";
							} else {
								for ($menuspace = 0 ; $menuspace < strlen($MENU_CD) ;$menuspace++) {
									$menu_str = $menu_str ."&nbsp;";
								}

								if (strlen($MENU_CD) == 4) {
									$menu_str = $menu_str ."┗ <font color='navy'>".$MENU_NAME."</font>";
								} else if (strlen($MENU_CD) == 6) {
									$menu_str = $menu_str ."&nbsp;&nbsp;┗ <font color='gray'>".$MENU_NAME."</font>";
								}
							}

							//echo $MENU_CD;
				?>
					<tr height="25">
						<td>
							<?=$menu_str?>
							<input type="hidden" name="menu_right[]" value="<?=$MENU_RIGHT?>">
							<input type="hidden" name="menu_cd[]" value="<?=$MENU_CD?>">
							<input type="hidden" name="menu_url[]" value="<?=$MENU_URL?>">
						</td>
				<?
							if (sizeof($arr_rs_right) > 0) {
		
								for ($jk = 0 ; $jk < sizeof($arr_rs_right); $jk++) {

									$SUB_MENU_CD	= trim($arr_rs_right[$jk]["MENU_CD"]);
									$READ_FLAG		= trim($arr_rs_right[$jk]["READ_FLAG"]);
									$REG_FLAG			= trim($arr_rs_right[$jk]["REG_FLAG"]);
									$UPD_FLAG			= trim($arr_rs_right[$jk]["UPD_FLAG"]);
									$DEL_FLAG			= trim($arr_rs_right[$jk]["DEL_FLAG"]);
									$FILE_FLAG		= trim($arr_rs_right[$jk]["FILE_FLAG"]);
									$TOP_FLAG			= trim($arr_rs_right[$jk]["TOP_FLAG"]);
									$MAIN_FLAG		= trim($arr_rs_right[$jk]["MAIN_FLAG"]);
									
									//echo $SUB_MENU_CD."---".$MENU_CD."<br>";

									if ($MENU_CD == trim($SUB_MENU_CD)) { 
				
										$nExist = "1";

										if (trim($READ_FLAG) == "Y") {
				?>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_read[]" value="Y" checked onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','R');">
							<input type="hidden" name="read_chk[]" value="">
						</td>
				<?						
										} else { 
				?>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_read[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','R');">
							<input type="hidden" name="read_chk[]" value="">
						</td>
				<?
										}

										if (trim($REG_FLAG) == "Y") {
				?>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_reg[]" value="Y" checked onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','I');">
							<input type="hidden" name="reg_chk[]" value="">
						</td>
				<?						
										} else { 
				?>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_reg[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','I');">
							<input type="hidden" name="reg_chk[]" value="">
						</td>
				<?
										}

										if (trim($UPD_FLAG) == "Y") {
				?>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_upd[]" value="Y" checked onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','U');">
							<input type="hidden" name="upd_chk[]" value="">
						</td>
				<?						
										} else { 
				?>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_upd[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','U');">
							<input type="hidden" name="upd_chk[]" value="">
						</td>
				<?
										}

										if (trim($DEL_FLAG) == "Y") {
				?>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_del[]" value="Y" checked onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','D');">
							<input type="hidden" name="del_chk[]" value="">
						</td>
				<?						
										} else { 
				?>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_del[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','D');">
							<input type="hidden" name="del_chk[]" value="">
						</td>
				<?
										}

										if (trim($FILE_FLAG) == "Y") {
				?>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_file[]" value="Y" checked onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','F');">
							<input type="hidden" name="file_chk[]" value="">
						</td>
				<?						
										} else { 
				?>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_file[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','F');">
							<input type="hidden" name="file_chk[]" value="">
						</td>
				<?
				
										}

										if (trim($TOP_FLAG) == "Y") {
				?>
						<!--
						<td>
							<input type="checkbox" name="chk_top[]" value="Y" checked onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','T');">
							<input type="hidden" name="top_chk[]" value="">
						</td>
						-->
						
				<?						
										} else { 
				?>
						<!--
						<td>
							<input type="checkbox" name="chk_top[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','T');">
							<input type="hidden" name="top_chk[]" value="">
						</td>
						-->
				<?
				
										}

										if (trim($MAIN_FLAG) == "Y") {
				?>
						<!--
						<td>
							<input type="checkbox" name="chk_main[]" value="Y" checked onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','M');">
							<input type="hidden" name="main_chk[]" value="">
						</td>
						-->
				<?						
										} else { 
				?>
						<!--
						<td>
							<input type="checkbox" name="chk_main[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','M');">
							<input type="hidden" name="main_chk[]" value="">
						</td>
						-->
				<?
				
										}

									}
								}
				
								if ($nExist == "0")  {
				?>

						<td style="text-align:center;">
							<input type="checkbox" name="chk_read[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','R');">
							<input type="hidden" name="read_chk[]" value="">
						</td>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_reg[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','I');">
							<input type="hidden" name="reg_chk[]" value="">
						</td>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_upd[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','U');">
							<input type="hidden" name="upd_chk[]" value="">
						</td>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_del[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','D');">
							<input type="hidden" name="del_chk[]" value="">
						</td>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_file[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','F');">
							<input type="hidden" name="file_chk[]" value="">
						</td>
						<!--
						<td>
							<input type="checkbox" name="chk_top[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','T');">
							<input type="hidden" name="top_chk[]" value="">
						</td>
						<td>
							<input type="checkbox" name="chk_main[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','M');">
							<input type="hidden" name="main_chk[]" value="">
						</td>
						-->
				<?
				
								}
				
								$nExist = "0";
				
							} else {
				?>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_read[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','R');">
							<input type="hidden" name="read_chk[]" value="">
						</td>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_reg[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','I');">
							<input type="hidden" name="reg_chk[]" value="">
						</td>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_upd[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','U');">
							<input type="hidden" name="upd_chk[]" value="">
						</td>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_del[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','D');">
							<input type="hidden" name="del_chk[]" value="">
						</td>
						<td style="text-align:center;">
							<input type="checkbox" name="chk_file[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','F');">
							<input type="hidden" name="file_chk[]" value="">
						</td>
						<!--
						<td>
							<input type="checkbox" name="chk_top[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','T');">
							<input type="hidden" name="top_chk[]" value="">
						</td>
						<td>
							<input type="checkbox" name="chk_main[]" value="Y" onClick="setFlag('<?=$MENU_CD?>','<?=$j?>','M');">
							<input type="hidden" name="main_chk[]" value="">
						</td>
						-->
				<?
							}
				?>
					</tr>
				<?
								$menu_str = "";
							}
						} else {
				?>
					<tr align="center" bgcolor="#FFFFFF">
						<td height="25" colspan="7">등록 메뉴가 없습니다.<!--등록 메뉴가 없습니다.--></td>
					</tr>
				<?
						}
				?>
				</tbody>
			</table>
		</div>
		<div class="btn_wrap right">
			<? if (($sPageRight_I == "Y") && ($sPageRight_U == "Y") && ($sPageRight_D == "Y")) { ?>
			<button type="button" class="button" onClick="Sendit();">저장</button>
			<? } ?>
			<button type="button" class="button type03" onClick="self.close();">닫기</button>
		</div>
	</div>
</div>
</form>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	db_close($conn);
?>