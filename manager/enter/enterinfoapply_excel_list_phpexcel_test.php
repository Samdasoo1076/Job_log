<?

ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);
session_start();

//header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
//header("x-xss-Protection:0");

# =============================================================================
# File Name    : enterinfoapply_excel_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2020-11-26
# Modify Date  : 
#	Copyright : Copyright @UCOM Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "EN002"; // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/admin/admin.php";
	require "../../_classes/biz/enter/enter.php";

	require "../../_PHPExcel/Classes/PHPExcel.php";

	// 파일의 저장형식이 utf-8일 경우 한글파일 이름은 깨지므로 euc-kr로 변환해준다.
	$str_title = iconv("UTF-8","UTF-8","입학자료 신청 리스트");
	$file_name=$str_title."-".date("Ymd");

	//header( "Content-type: application/vnd.ms-excel; charset=UTF-8" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
	//header( "Content-Disposition: attachment; filename=$file_name" );

#====================================================================
# Request Parameter
#====================================================================

	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$ean_no						= $_POST['ean_no']!=''?$_POST['ean_no']:$_GET['ean_no'];
	$apply_tf					= $_POST['apply_tf']!=''?$_POST['apply_tf']:$_GET['apply_tf'];

/*
	$e_type						= $_POST['e_type']!=''?$_POST['e_type']:$_GET['e_type'];
	$e_year						= $_POST['e_year']!=''?$_POST['e_year']:$_GET['e_year'];
	$e_title					= $_POST['e_title']!=''?$_POST['e_title']:$_GET['e_title'];
	$e_pdf						= $_POST['e_pdf']!=''?$_POST['e_pdf']:$_GET['e_pdf'];
	$e_img						= $_POST['e_img']!=''?$_POST['e_img']:$_GET['e_img'];

	$disp_seq					= $_POST['disp_seq']!=''?$_POST['disp_seq']:$_GET['disp_seq'];

	$use_tf						= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$reg_date					= $_POST['reg_date']!=''?$_POST['reg_date']:$_GET['reg_date'];

	$f							= $_POST['f']!=''?$_POST['f']:$_GET['f'];
	$s							= $_POST['s']!=''?$_POST['s']:$_GET['s'];
*/
	$chk						= $_POST['chk']!=''?$_POST['chk']:$_GET['chk'];

	$use_tf					= "";  
	$apply_tf				= "";
	$del_tf					= "N";

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntEnterInfoApplyExcelChk($conn, $chk, $apply_tf, $use_tf, $del_tf, $f, $s);

	$arr_rs = listEnterInfoApplyExcelChk($conn, $chk, $ea_name, $ea_who, $ea_who_year, $ea_post, $ea_addr, $ea_addr_detail, $ea_phone, $ea_pwd, $agree_tf, $apply_tf, $use_tf, $del_tf, $f, $s);

	$result_log = insertUserLog($conn, "admin", $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "입학 자료 신청 리스트 조회", "List");

	$objPHPExcel = new PHPExcel();

	// 셀병합 처리
	$objPHPExcel -> setActiveSheetIndex(0)		
				 -> mergeCells('A1:J1') -> setCellValue('A1', $file_name);

	$objPHPExcel -> setActiveSheetIndex(0)
				-> setCellValue("A3", "번호")
				-> setCellValue("B3", "성명")
				-> setCellValue("C3", "대상")
				-> setCellValue("D3", "전화번호")
				-> setCellValue("E3", "학교명")
				-> setCellValue("F3", "우편번호")
				-> setCellValue("G3", "주소")
				-> setCellValue("H3", "신청자료")
				-> setCellValue("I3", "처리여부")
				-> setCellValue("J3", "등록일");

	if (sizeof($arr_rs) > 0) {

		for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

			$num = 4 + $j;
			$rn = 1 + $j;
			$EA_NO				= trim($arr_rs[$j]["EA_NO"]);
			$EAN_NO				= trim($arr_rs[$j]["EAN_NO"]);
			$EA_NAME			= trim($arr_rs[$j]["EA_NAME"]);
			$EA_WHO				= trim($arr_rs[$j]["EA_WHO"]);
			$EA_WHO_YEAR		= trim($arr_rs[$j]["EA_WHO_YEAR"]);	
			$EA_POST			= trim($arr_rs[$j]["EA_POST"]);
			$EA_SC_NM			= trim($arr_rs[$j]["EA_SC_NM"]);
			$EA_SC_AREA			= trim($arr_rs[$j]["EA_SC_AREA"]);
			$EA_ADDR			= trim($arr_rs[$j]["EA_ADDR"]);
			$EA_ADDR_DETAIL		= trim($arr_rs[$j]["EA_ADDR_DETAIL"]);
			$EA_PHONE			= trim($arr_rs[$j]["EA_PHONE"]);
			$E_TITLE			= trim($arr_rs[$j]["E_TITLE"]);
			$EA_NUM				= trim($arr_rs[$j]["EA_NUM"]);
			$EA_MEMO			= trim($arr_rs[$j]["EA_MEMO"]);
			$APPLY_TF			= trim($arr_rs[$j]["APPLY_TF"]);

			$DISP_SEQ			= trim($arr_rs[$j]["DISP_SEQ"]);
			$USE_TF				= trim($arr_rs[$j]["USE_TF"]);
			$DEL_TF				= trim($arr_rs[$j]["DEL_TF"]);
			$REG_DATE			= trim($arr_rs[$j]["REG_DATE"]);
			$UP_DATE			= trim($arr_rs[$j]["UP_DATE"]);

			$STR_ES_SC_NM		= $EA_SC_NM;
			if ($EA_SC_AREA <>"") $STR_ES_SC_NM = $STR_ES_SC_NM." (".$EA_SC_AREA.")";

			$STR_EA_ADDR		= $EA_ADDR." ".$EA_ADDR_DETAIL;

			if ($APPLY_TF == "N") {
				$STR_APPLY_TF = "접수중";
			} else{
				$STR_APPLY_TF = "처리완료";
			}

			$objPHPExcel -> setActiveSheetIndex(0)
				-> setCellValue(sprintf("A%s", $num), $rn)	
				-> setCellValue(sprintf("B%s", $num), $EA_NAME)	
				-> setCellValue(sprintf("C%s", $num), $EA_WHO)	
				-> setCellValue(sprintf("D%s", $num), $EA_PHONE)	
				-> setCellValue(sprintf("E%s", $num), $STR_ES_SC_NM)	
				-> setCellValue(sprintf("F%s", $num), $EA_POST)	
				-> setCellValue(sprintf("G%s", $num), $STR_EA_ADDR)	
				-> setCellValue(sprintf("H%s", $num), $EA_MEMO)	
				-> setCellValue(sprintf("I%s", $num), $STR_APPLY_TF)	
				-> setCellValue(sprintf("J%s", $num), $REG_DATE);

		}
	}

	// 가로 넓이 조정
	$objPHPExcel -> getActiveSheet() -> getColumnDimension("A") -> setWidth(5);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension("B") -> setWidth(8);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension("C") -> setWidth(8);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension("D") -> setWidth(15);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension("E") -> setWidth(30);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension("F") -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension("G") -> setWidth(60);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension("H") -> setWidth(30);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension("I") -> setWidth(10);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension("J") -> setWidth(18);

	// 숫자 앞에 0 붙이는 서식 지정
	$objPHPExcel -> getActiveSheet() -> getStyle("F4:F".$num) -> getNumberFormat() -> setFormatCode("00000");

	// 타이틀 부분
	$objPHPExcel -> getActiveSheet() -> getStyle("A1:J1") -> getFont() -> setBold(true) -> setSize(14);
	$objPHPExcel -> getActiveSheet() -> getStyle("A1:J1") 
				 -> getFill() -> setFillType(PHPExcel_Style_Fill::FILL_SOLID)
				 -> getStartColor() -> setRGB("CECBCA");

	$objPHPExcel -> getActiveSheet() -> getStyle("A3:J3") -> getFont() -> setBold(true);

	// 가운데 정렬
	$objPHPExcel -> getActiveSheet() -> getStyle("A1") -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel -> getActiveSheet() -> getStyle("A3:J3") -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	// 시트 네임
	$objPHPExcel -> getActiveSheet() -> setTitle($file_name);

	header("Content-Type:application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename=".$file_name.".xls");
	header("Cache-Control:max-age=0");
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");
	ob_end_clean(); 
	$objWriter -> save("php://output");

#====================================================================
# DB Close
#====================================================================

	db_close($conn);
?>
