<?session_start();?>
<?
# =============================================================================
# File Name    : banner_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2020-11-17
# Modify Date  : 
#	Copyright    : Copyright @UCOM Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "PO005"; // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
	include "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/biz/admin/admin.php";
	require "../../_classes/biz/banner/banner.php";

#====================================================================
# DML Process
#====================================================================

	$mode							= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$banner_no				= $_POST['banner_no']!=''?$_POST['banner_no']:$_GET['banner_no'];
	$banner_nm				= $_POST['banner_nm']!=''?$_POST['banner_nm']:$_GET['banner_nm'];
	$banner_img				= $_POST['banner_img']!=''?$_POST['banner_img']:$_GET['banner_img'];
	$banner_url				= $_POST['banner_url']!=''?$_POST['banner_url']:$_GET['banner_url'];
	$url_type					= $_POST['url_type']!=''?$_POST['url_type']:$_GET['url_type'];
	$banner_button		= $_POST['banner_button']!=''?$_POST['banner_button']:$_GET['banner_button'];

	$banner_real_img	= $_POST['banner_real_img']!=''?$_POST['banner_real_img']:$_GET['banner_real_img'];
	$old_banner_img		= $_POST['old_banner_img']!=''?$_POST['old_banner_img']:$_GET['old_banner_img'];

	$use_tf						= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$disp_seq					= $_POST['disp_seq']!=''?$_POST['disp_seq']:$_GET['disp_seq'];

	$s_date						= $_POST['s_date']!=''?$_POST['s_date']:$_GET['s_date'];
	$s_hour						= $_POST['s_hour']!=''?$_POST['s_hour']:$_GET['s_hour'];
	$s_min						= $_POST['s_min']!=''?$_POST['s_min']:$_GET['s_min'];
	$e_hour						= $_POST['e_hour']!=''?$_POST['e_hour']:$_GET['e_hour'];
	$e_min			 			= $_POST['e_min']!=''?$_POST['e_min']:$_GET['e_min'];
	$e_date						= $_POST['e_date']!=''?$_POST['e_date']:$_GET['e_date'];

	$reg_date					= $_POST['reg_date']!=''?$_POST['reg_date']:$_GET['reg_date'];

//	$result_date			= $_POST['result_date']!=''?$_POST['result_date']:$_GET['result_date'];
//	$result_time			= $_POST['result_time']!=''?$_POST['result_time']:$_GET['result_time'];

	#====================================================================
	$savedir1 = $g_physical_path."upload_data/banner";
	#====================================================================

	$result = false;


	$mode						= SetStringToDB($mode);
	$banner_nm			= SetStringToDB($banner_nm);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);

	$REG_INSERT_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

	$max_allow_file_size = $allow_file_size * 1024 * 1024;


	if ($mode == "I") {

		$banner_img				= upload($_FILES["banner_img"], $savedir1, 100 , array('gif', 'jpeg', 'jpg','png'));
		
		$arr_data = array("BANNER_NM"=>$banner_nm,
											"BANNER_IMG"=>$banner_img,
											"BANNER_REAL_IMG"=>$banner_real_img,
											"BANNER_URL"=>$banner_url,
											"BANNER_BUTTON"=>$banner_button,
											"USE_TF"=>$use_tf,
											"URL_TYPE"=>$url_type,
											"DISP_SEQ"=>0,
											"REG_ADM"=>$_SESSION["s_adm_no"],
											"S_DATE"=>$s_date,
											"S_HOUR"=>$s_hour,
											"S_MIN"=>$s_min,
											"E_DATE"=>$e_date,
											"E_HOUR"=>$e_hour,
											"E_MIN"=>$e_min,
											"UP_DATE"=>$REG_INSERT_DATE
										);


		$new_banner_no = insertBanner($conn, $arr_data);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "배너 등록 (제목 : ".$banner_nm.") ", "Insert");

	}
	
	if ($mode == "U") {

		$arr_data = array"BANNER_NM"=>$banner_nm,
											"BANNER_IMG"=>$banner_img,
											"BANNER_REAL_IMG"=>$banner_real_img,
											"BANNER_URL"=>$banner_url,
											"BANNER_BUTTON"=>$banner_button,
											"USE_TF"=>$use_tf,
											"URL_TYPE"=>$url_type,
											"DISP_SEQ"=>0,
											"REG_ADM"=>$_SESSION["s_adm_no"],
											"S_DATE"=>$s_date,
											"S_HOUR"=>$s_hour,
											"S_MIN"=>$s_min,
											"E_DATE"=>$e_date,
											"E_HOUR"=>$e_hour,
											"E_MIN"=>$e_min,
											"UP_DATE"=>$REG_INSERT_DATE
										);

		$result =  updateBanner($conn, $arr_data, $banner_no);
		
		$$result = deleteProgramFile($conn, $banner_no);

		$file_cnt = is_null($file_name) ? 0 : count($file_name);

		for($i=0; $i <= $file_cnt; $i++) {

			$file_rname				= $_FILES["file_name"]["name"][$i];
			$old_file_name		= $_POST["old_file_name"][$i];
			$old_file_rname		= $_POST["old_file_rname"][$i];

			if (($file_rname != "") || ($old_file_rname != "")) {
				
				if ($file_rname != "") {
					$file_name	= multiupload($_FILES["file_name"], $i, $savedir1, $allow_file_size , array('gif', 'jpeg', 'jpg','png','xls', 'xlsx', 'doc','docx','ppt','pptx','hwp','zip','rar','pdf','txt','GIF', 'JPEG', 'JPG','PNG','XLS', 'XLSX', 'DOC','DOCX','PPT','PPTX','HWP','ZIP','RAR','PDF','TXT'));
				} else {
					$file_name	= $old_file_name;
					$file_rname = $old_file_rname;
				}

				$arr_data = array("BANNER_NO"=>$banner_no,
													"FILE_NM"=>$file_name,
													"FILE_RNM"=>$file_rname,
													"FILE_PATH"=>"",
													"FILE_SIZE"=>0,
													"FILE_EXT"=>"",
													"HIT_CNT"=>0,
													"REG_ADM"=>$_SESSION['s_adm_no'],
													"REG_DATE"=>$REG_INSERT_DATE
												);

				$result = insertProgramFile($conn, $arr_data);

			}
		}


		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "지원사업 수정 (제목 : ".$title.") ", "Update");

	}

	if ($mode == "D") {
		$result = deleteProgram($conn, $_SESSION['s_adm_no'], (int)$banner_no);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "지원사업 삭제 처리 (제목 : ".$title.") ", "Delete");
	}

	if ($mode == "S") {

		$arr_rs = selectBanner($conn, (int)$banner_no);

		$rs_banner_no							= trim($arr_rs[0]["BANNER_NO"]); 
		$rs_yyyy								= trim($arr_rs[0]["YYYY"]); 
		$rs_type								= trim($arr_rs[0]["TYPE"]); 
		$rs_app_type						= trim($arr_rs[0]["APP_TYPE"]); 
		$rs_app_limt						= trim($arr_rs[0]["APP_LIMT"]); 
		$rs_s_date							= trim($arr_rs[0]["S_DATE"]); 
		$rs_s_hour							= trim($arr_rs[0]["S_HOUR"]); 
		$rs_s_min								= trim($arr_rs[0]["S_MIN"]); 
		$rs_e_date							= trim($arr_rs[0]["E_DATE"]); 
		$rs_e_hour							= trim($arr_rs[0]["E_HOUR"]); 
		$rs_e_min								= trim($arr_rs[0]["E_MIN"]); 
		$rs_title								= SetStringFromDB($arr_rs[0]["TITLE"]); 
		$rs_contents						= SetStringFromDB($arr_rs[0]["CONTENTS"]); 
		$rs_state								= trim($arr_rs[0]["STATE"]); 
		$rs_hit_cnt							= trim($arr_rs[0]["HIT_CNT"]); 
		$rs_list_type						= trim($arr_rs[0]["LIST_TYPE"]); 
		$rs_use_tf							= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf							= trim($arr_rs[0]["DEL_TF"]); 
		$rs_reg_adm							= trim($arr_rs[0]["REG_ADM"]); 
		$rs_reg_date						= trim($arr_rs[0]["REG_DATE"]); 
		$rs_confirm_memo				= SetStringFromDB($arr_rs[0]["CONFIRM_MEMO"]); 
		$rs_fileadd_start				= trim($arr_rs[0]["FILEADD_START"]); 
		$rs_fileadd_end					= trim($arr_rs[0]["FILEADD_END"]); 
		$rs_result_date					= trim($arr_rs[0]["RESULT_DATE"]); 
		$rs_result_time					= trim($arr_rs[0]["RESULT_TIME"]); 
		$rs_supportfund					= trim($arr_rs[0]["SUPPORTFUND"]); 
											

		$arr_rs_file = listProgramFile($conn, $banner_no);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "지원사업 조회 (제목 : ".$rs_title.") ", "Read");

		//$arr_rs_question = listQuestion($conn, $banner_no);

	}

	if ($result) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str;
		$strParam = $strParam."&con_yyyy=".$con_yyyy."&con_type=".$con_type."&con_app_type=".$con_app_type."&con_state=".$con_state;

?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "program_list.php<?=$strParam?>";
</script>
</head>
</html>
<?
		exit;
	}	
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="../css/common.css" media="all" />
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../js/jquery.nicefileinput.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.js"></script>
<script type="text/javascript" src="../js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="../js/jquery.form.js"></script>
<script type="text/javascript" src="../js/ui.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../../_common/SE2-2.8.2.3/js/HuskyEZCreator.js" charset="utf-8"></script>

<script type="text/javascript">
/*
$(document).ready(function() {
	<? if ($banner_no <> "") { ?>
	js_get_question();
	<? } ?>
});
*/
function js_list() {
	var frm = document.frm_list;
		
	frm.method = "get";
	frm.action = "banner_list.php";
	frm.submit();
}

function js_save() {


	var frm = document.frm;
	var banner_no = "<?=$banner_no?>";
	frm.banner_nm.value = frm.banner_nm.value.trim();
	
	//$("#use_tf").val($(":input:radio[name=rd_use_tf]:checked").val());
	
	if (isNull(frm.banner_nm.value)) {
		alert('배너명을 입력해주세요.');
		frm.banner_nm.focus();
		return ;
	}

	if (isNull(frm.banner_url.value)) {
		alert('배너를 연결할 주소를 입력해주세요.');
		frm.banner_url.focus();
		return ;
	}

	if (document.frm.rd_url_type == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_url_type[0].checked == true) {
			frm.url_type.value = "Y";
		} else {
			frm.url_type.value = "N";
		}
	}

	if (isNull(frm.banner_button.value)) {
		alert('배너 버튼 내용을 입력해 주세요.');
		frm.banner_button.focus();
		return ;
	}

	if (isNull(frm.s_date.value)) {
		alert('신청 시작일를 입력해주세요.');
		frm.s_date.focus();
		return ;
	}

	if (isNull(frm.e_date.value)) {
		alert('신청 마감일를 입력해주세요.');
		frm.e_date.focus();
		return ;
	}

	if (document.frm.rd_use_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
		}
	}

	frm.target = "";
	frm.method = "post";
	frm.action = "banner_write.php";

	if (isNull(banner_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
	}
	frm.submit();
}


</script>
</head>
<body>
	<div id="wrap">
		
		<div class="cont_aside">
<?
	#====================================================================
	# common left_area
	#====================================================================
		require "../../_common/left_area.php";
?>
		</div>
		<div id="container">
			<div class="top">
<?
	#====================================================================
	# common top_area
	#====================================================================
		require "../../_common/top_area.php";
?>
			</div>
			<div class="contents">
<?
	#====================================================================
	# common location_area
	#====================================================================
		require "../../_common/location_area.php";
?>
				<div class="tit_h3"><h3><?=$p_menu_name?></h3></div>

<form name="frm" id="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="banner_no" value="<?=$banner_no?>" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="" />
<input type="hidden" name="nPageSize" value="" />
<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">

				<div class="cont">
					<div class="tit_h4 first"><h4>상단 배너</h4></div>
					<div class="tbl_style01 left">
						<table>
							<colgroup>
								<col style="width:12%" />
								<col style="width:38%" />
								<col style="width:12%" />
								<col />
							</colgroup>
							<tbody>
<!--
								<?
									if (sizeof($arr_rs_file) > 0) {
										for ($j=0 ; $j < sizeof($arr_rs_file) ; $j++) {
											$RS_FILE_NO			= trim($arr_rs_file[$j]["FILE_NO"]);
											$RS_FILE_NM			= trim($arr_rs_file[$j]["FILE_NM"]);
											$RS_FILE_RNM		= trim($arr_rs_file[$j]["FILE_RNM"]);
								?>
								<tr>
									<th>배너명 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3"><input type="text" name="banner_nm" value="<?=$rs_banner_nm?>" style="width:50%"/></td>
								</tr>
								<tr>
									<th scope="row">
										배너명
										<img src="../images/btn_plus.gif" alt="파일추가"  style="cursor:pointer" class="btn_add_file" />
										<img src='../images/btn_minus.gif' alt='파일삭제'  style='cursor:pointer' class='btn_del_file' />
									</th>
									<td colspan="3">
										<div style="display:inline;float:left;width:20%;height:20px;">
											<span class="tbl_txt">
												<a href="../../_common/new_download_file.php?menu=program&file_no=<?= $RS_FILE_NO ?>"><?=$RS_FILE_RNM?></a>
											</span>
										</div>
										<input type="file" size="40%" name="file_name[]">
										<input type="hidden" name="old_file_name[]" value="<?=$RS_FILE_NM?>">
										<input type="hidden" name="old_file_rname[]" value="<?=$RS_FILE_RNM?>">
									</td>
								</tr>
								<?
										}
									} else { 
								?>
//-->
								<tr>
									<th>배너명 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3"><input type="text" name="banner_nm" value="<?=$rs_banner_nm?>" style="width:30%"/></td>
								</tr>
								<tr>
									<th scope="row">배너이미지</th>
									<td colspan="3">
										<input type="file" size="40%" name="banner_img">
										<input type="hidden" name="old_banner_img" value="">
										<input type="hidden" name="old_banner_img" value="">
									</td>
								</tr>
<!--
								<?
									}
								?>
//-->
								<tr>
									<th scope="row">링크주소</th>
									<td colspan="3"><input type="text" name="banner_url" value="<?=$rs_banner_url?>" style="width:90%" /></td>
								</tr>
								<tr>
									<th scope="row">링크방식</th>
									<td colspan="3">
										<input type="radio" id="blank" name="rd_url_type" value="Y" <? if (($rs_url_type =="Y") || ($rs_url_type =="")) echo "checked"; ?> /> <label for="blank">새창</label>&nbsp;&nbsp;
										<input type="radio" id="own" name="rd_url_type" value="N" <? if ($rs_url_type =="N") echo "checked"; ?> /> <label for="own">자기창</label>
										<input type="hidden" name="url_type" value="<?= $rs_url_type ?>">
									</td>
								</tr>
								<tr>
									<th>배너버튼내용 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3"><input type="text" name="banner_button" value="<?=$rs_banner_button?>" style="width:30%"/></td>
								</tr>
								<tr>
									<th>신청 기간 <img src="../images/img_essen.gif" alt="필수입력" /></th>
									<td colspan="3">
										<div class="datepickerbox" style="width:120px;" >
											<input type="text" name="s_date" id="s_date" class="datepicker onlyphone" value="<?=$rs_s_date?>" maxlength="10" autocomplete="off" readonly="1"/>
										</div>
										<div style="display:inline-block;">
											<?= makeSelectBox($conn,"TIME","s_hour","120","","",$rs_s_hour)?>
											<select name="s_min" id="s_min" style="width:80px">
											<?
												for ($k = 0 ; $k < 60 ; $k++) {
													$str_k = right(("0".$k),2);

													$str_selected = "";
													if ($rs_s_min == $str_k) $str_selected  = "selected";
											?>
												<option value="<?=$str_k?>" <?=$str_selected?>><?=$str_k?> 분</option>
											<?
												}
											?>
											</select>
										</div>
										<span class="tbl_txt">~&nbsp;&nbsp;</span> 
										<div class="datepickerbox" style="width:120px;" >
											<input type="text" name="e_date" id="e_date" class="datepicker onlyphone" value="<?=$rs_e_date?>" maxlength="10" autocomplete="off" readonly="1"/>
										</div>
										<div style="display:inline-block;">
											<?= makeSelectBox($conn,"TIME","e_hour","120","","",$rs_e_hour)?>
											<select name="e_min" id="e_min" style="width:80px">
											<?
												for ($k = 0 ; $k < 60 ; $k++) {
													$str_k = right(("0".$k),2);

													$str_selected = "";
													if ($rs_e_min == $str_k) $str_selected  = "selected";
											?>
												<option value="<?=$str_k?>" <?=$str_selected?>><?=$str_k?> 분</option>
											<?
												}
											?>
											</select>
										</div>
									</td>
								</tr>
								<tr>
									<th>공개여부</th>
									<td colspan="3">
										<input type="radio" id="all" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?> class="radio" /> <label for="all">공개</label>&nbsp;&nbsp;
										<input type="radio" id="secret" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N") echo "checked"; ?> class="radio" /> <label for="secret">비공개</label>
										<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>">
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					
					<div class="btn_wrap">
						<a href="javascript:js_list();" class="button type02">목록</a>
						<? if ($banner_no == "") {?>
						<?	if ($sPageRight_I == "Y") {?>
						<button type="button" class="button" onClick="js_save()">등록</button>
						<?	} ?>
						<? } else {  ?>
						<?	if ($sPageRight_U == "Y") {?>
						<button type="button" class="button" onClick="js_save()">수정</button>
						<?	} ?>
						<?	if ($sPageRight_D == "Y") {?>
						<button type="button" class="button" onClick="js_delete()">삭제</button>
						<?	} ?>
						<? } ?>
					</div>
				</div>

</form>

<form name="frm_list" method="post">

<input type="hidden" name="banner_no" value="<?=$banner_no?>" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="" />
<input type="hidden" name="nPageSize" value="" />
<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">
<input type="hidden" name="order_field" value="<?=$order_field?>">
<input type="hidden" name="order_str" value="<?=$order_str?>">
<input type="hidden" name="con_yyyy" value="<?=$con_yyyy?>">
<input type="hidden" name="con_type" value="<?=$con_type?>">
<input type="hidden" name="con_app_type" value="<?=$con_app_type?>">
<input type="hidden" name="con_state" value="<?=$con_state?>">

</form>


			</div>

		</div>
	</div>
<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</body>
</html>
<SCRIPT LANGUAGE="JavaScript">
<!--
	var oEditors = [];

	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "contents",
		sSkinURI: "../../_common/SE2-2.8.2.3/SmartEditor2Skin.html",
		htParams : {
			bUseToolbar : true, 
			fOnBeforeUnload : function(){ 
				// alert('야') 
			},
			fOnAppLoad : function(){ 
			// 이 부분에서 FOCUS를 실행해주면 됩니다. 
			this.oApp.exec("EVENT_EDITING_AREA_KEYDOWN", []); 
			this.oApp.setIR(""); 
			//oEditors.getById["ir1"].exec("SET_IR", [""]); 
			}
		}, 
		fCreator: "createSEditor2"
	});

//-->
</SCRIPT>

<?
#=====================================================================
# DB Close
#=====================================================================

	db_close($conn);
?>