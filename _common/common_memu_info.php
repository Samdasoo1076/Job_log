<?
	$arr_page_rs = selectPage($conn, $p);

	// echo "<pre>";
	// print_r($arr_page_rs);
	// echo "</pre>";	

	// exit;

	if (sizeof($arr_page_rs) > 0) {

		$rs_page_no					= trim($arr_page_rs[0]["PAGE_NO"]); 
		$rs_page_cd					= trim($arr_page_rs[0]["PAGE_CD"]); 
		$rs_page_lang				= trim($arr_page_rs[0]["PAGE_LANG"]); 
		$rs_page_name				= getStringFromDB($arr_page_rs[0]["PAGE_NAME"]); 
		$rs_page_url				= getStringFromDB($arr_page_rs[0]["PAGE_URL"]); 
		$rs_title_img				= trim($arr_page_rs[0]["TITLE_IMG"]); 
		$rs_title_img_over	= trim($arr_page_rs[0]["TITLE_IMG_OVER"]); 
		$rs_page_img				= trim($arr_page_rs[0]["PAGE_IMG"]); 
		$rs_page_img_over		= trim($arr_page_rs[0]["PAGE_IMG_OVER"]); 
		$rs_page_script			= trim($arr_page_rs[0]["PAGE_SCRIPT"]); 
		$rs_page_content		= getStringFromDB($arr_page_rs[0]["PAGE_CONTENT"]); 
		$rs_page_info01			= trim($arr_page_rs[0]["PAGE_INFO01"]); 
		$rs_page_info02			= trim($arr_page_rs[0]["PAGE_INFO02"]); 
		$rs_page_info03			= trim($arr_page_rs[0]["PAGE_INFO03"]); 
		$rs_page_info04			= trim($arr_page_rs[0]["PAGE_INFO04"]); 
		$rs_page_info05			= trim($arr_page_rs[0]["PAGE_INFO05"]); 
		$rs_use_tf					= trim($arr_page_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_page_rs[0]["DEL_TF"]); 

		$rs_reg_date				= trim($arr_page_rs[0]["REG_DATE"]); 
		$rs_up_date					= trim($arr_page_rs[0]["UP_DATE"]); 
		
		//echo $rs_reg_date;
		// echo $rs_page_name;
		// exit;

		//echo $rs_up_date;

		// echo "<pre>";
		// print_r($arr_page_rs);
		// echo "</pre>";	

		// exit;



		$seo_title						= $rs_page_name;
		//$seo_title				    = trim($arr_page_rs[0]["PAGE_INFO03"]); 
		$seo_description				= $rs_page_info02;
		$seo_keywords					= $rs_page_info03;
		$rs_iso_up_date					= $rs_up_date;
		
		/*
		if ($rs_title_img == "") {
			$seo_og_image = $g_site_url."/images/meta_logo_2.png";
		} else {
			$seo_og_image = $g_site_url."/upload_data/menu/".$rs_title_img;
		}
		*/

		$rs_iso_up_date			= date("c", strtotime($rs_iso_up_date)); 
		$rs_iso_reg_date		= date("c", strtotime($rs_reg_date)); 
		
		// 최근 업데이트 ISO 날짜
		if ($rs_iso_up_date == "") $rs_iso_up_date = $rs_iso_reg_date; 

		$_SESSION['s_current_lang'] = $rs_page_lang;
		
		if ($rs_page_content == "<p>&nbsp;</p>") $rs_page_content = "";

	} else {

?>
<meta http-equiv='Refresh' content='0; URL=/'>
<?
		exit;
	}

	// 현재 depth 확인..
	$this_depth01 = substr($rs_page_cd,0,2);
	$this_depth02 = substr($rs_page_cd,2,2);
	$this_depth03 = substr($rs_page_cd,4,2);
	$this_depth04 = substr($rs_page_cd,6,2);
	$this_depth05 = substr($rs_page_cd,8,2);

	//echo $this_depth01;
	//echo $this_depth02;

	// 언어 관련 셋팅
	if ($rs_page_lang) $l = $rs_page_lang;

	$use_tf = "Y";
	$del_tf = "N";
	$menu_tf = "Y";
	
	// 1 depth
	$arr_menu_rs_01 = listSubPage($conn, $l, "2", $menu_tf, $use_tf, $del_tf);
	// 2 depth
	$arr_menu_rs_02 = listSubPage($conn, $l, "4", $menu_tf, $use_tf, $del_tf);
	// 3 depth
	$arr_menu_rs_03 = listSubPage($conn, $l, "6", $menu_tf, $use_tf, $del_tf);

	// echo "<pre>";
	// print_r ($arr_menu_rs_03);
	// echo "</pre>";
	// exit;

	if (sizeof($arr_menu_rs_01) > 0) {
		for ($j = 0 ; $j < sizeof($arr_menu_rs_01); $j++) {
			$PAGE_CD			= trim($arr_menu_rs_01[$j]["PAGE_CD"]);
			$PAGE_INFO01	= trim($arr_menu_rs_01[$j]["PAGE_INFO01"]);
			$PAGE_INFO02	= trim($arr_menu_rs_01[$j]["PAGE_INFO02"]);
			$PAGE_INFO03	= trim($arr_menu_rs_01[$j]["PAGE_INFO03"]);
			$PAGE_INFO04	= trim($arr_menu_rs_01[$j]["PAGE_INFO04"]);
			$PAGE_INFO05	= trim($arr_menu_rs_01[$j]["PAGE_INFO05"]);

			if ($this_depth01 == $PAGE_CD) $depth_01_class = $PAGE_INFO03;
		}
	}
?>