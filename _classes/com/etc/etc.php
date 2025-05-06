<?




function sql_password($db, $value)
{
	$query = "select password('$value') as pass";
	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);
	return $rows[pass];
}

function getZipCode($db, $dong)
{

	$offset = $nRowCount * ($nPage - 1);


	$query = "SELECT POST_NO , SIDO , SIGUNGU, DONG, RI , BUNJI, FULL_ADDR
								FROM TBL_ZIPCODE WHERE 1 = 1 ";

	if ($dong <> "") {
		$query .= " AND DONG like '%" . $dong . "%' ";
	}

	$query .= " ORDER BY POST_NO ";

	//echo $query;

	$result = mysqli_query($db, $query);
	$record = array();

	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}

	return $record;
}

function getName($db, $mem_nm)
{

	$offset = $nRowCount * ($nPage - 1);


	$query = "SELECT MEM_ID,MEM_NM FROM TBL_MEMBER  WHERE 1 = 1 and DEL_TF='N'";

	if ($mem_nm <> "") {
		$query .= " AND MEM_NM like '%" . $mem_nm . "%' ";
	}

	$query .= " ORDER BY MEM_NO  ";

	//echo $query;

	$result = mysqli_query($db, $query);
	$record = array();

	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}

	return $record;
}


function makeSelectBoxOnChange($db, $pcode, $objname, $size, $str, $val, $checkVal)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "<select name='" . $objname . "' id='" . $objname . "' class=\"box01\" style='width:" . $size . "px;' onchange=\"js_" . $objname . "();\">";

	if ($str <> "") {
		$tmp_str .= "<option value='" . $val . "'>" . $str . "</option>";
	}

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		if ($checkVal == $RS_DCODE) {
			$tmp_str .= "<option value='" . $RS_DCODE . "' selected>" . $RS_DCODE_NM . "</option>";
		} else {
			$tmp_str .= "<option value='" . $RS_DCODE . "'>" . $RS_DCODE_NM . "</option>";
		}
	}
	$tmp_str .= "</select>";
	return $tmp_str;
}

function makeSelectBoxOnChange2($db, $pcode, $objname, $size, $str, $val, $checkVal)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "<select name='" . $objname . "' id='" . $objname . "' class=\"sel\" style='width:" . $size . "px;' onchange=\"js_" . $objname . "(this);\">";

	if ($str <> "") {
		$tmp_str .= "<option value='" . $val . "'>" . $str . "</option>";
	}

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		if ($checkVal == $RS_DCODE) {
			$tmp_str .= "<option value='" . $RS_DCODE . "' selected>" . $RS_DCODE_NM . "</option>";
		} else {
			$tmp_str .= "<option value='" . $RS_DCODE . "'>" . $RS_DCODE_NM . "</option>";
		}
	}
	$tmp_str .= "</select>";
	return $tmp_str;
}

function makeSelectBoxAllOnChange($db, $pcode, $objname, $size, $str, $val, $checkVal)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "<select name='" . $objname . "' id='" . $objname . "' class=\"box01\" style='width:" . $size . "px;' onchange=\"js_" . $objname . "();\">";

	if ($str <> "") {
		$tmp_str .= "<option value='" . $val . "'>" . $str . "</option>";
	}

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		if ($checkVal == $RS_DCODE) {
			$tmp_str .= "<option value='" . $RS_DCODE . "' selected>" . $RS_DCODE_NM . "</option>";
		} else {
			$tmp_str .= "<option value='" . $RS_DCODE . "'>" . $RS_DCODE_NM . "</option>";
		}
	}
	$tmp_str .= "</select>";
	return $tmp_str;
}


function makeSelectBox($db, $pcode, $objname, $size, $str, $val, $checkVal)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	} else {
		$query .= " AND PCODE = 'XXXXXXXX' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "<select name='" . $objname . "' id='" . $objname . "' class='box01'  style='width:" . $size . "px;' >";

	if ($str <> "") {
		$tmp_str .= "<option value='" . $val . "'>" . $str . "</option>";
	}

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		if ($checkVal == $RS_DCODE) {
			$tmp_str .= "<option value='" . $RS_DCODE . "' selected>" . $RS_DCODE_NM . "</option>";
		} else {
			$tmp_str .= "<option value='" . $RS_DCODE . "'>" . $RS_DCODE_NM . "</option>";
		}
	}
	$tmp_str .= "</select>";
	return $tmp_str;
}

function makeSelectBox2($db, $pcode, $objname, $size, $str, $val, $checkVal)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	} else {
		$query .= " AND PCODE = 'XXXXXXXX' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "<select name='" . $objname . "' id='" . $objname . "' class=\"sel\" style='width:" . $size . "px;'>";
	
	if ($str <> "") {
		$tmp_str .= "<option value='" . $val . "'>" . $str . "</option>";
	}
		
	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		if ($checkVal == $RS_DCODE) {
			$tmp_str .= "<option value='" . $RS_DCODE . "' selected>" . $RS_DCODE_NM . "</option>";
		} else {
			$tmp_str .= "<option value='" . $RS_DCODE . "'>" . $RS_DCODE_NM . "</option>";
		}
	}
	
	$tmp_str .= "</select>";
	return $tmp_str;
}

function makeFrontSelectBox($db, $pcode, $objname, $size, $str, $val, $checkVal)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	} else {
		$query .= " AND PCODE = 'XXXXXXXX' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "<select name='" . $objname . "' id='" . $objname . "' >";

	if ($str <> "") {
		$tmp_str .= "<option value='" . $val . "'>" . $str . "</option>";
	}

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		if ($checkVal == $RS_DCODE) {
			$tmp_str .= "<option value='" . $RS_DCODE . "' selected>" . $RS_DCODE_NM . "</option>";
		} else {
			$tmp_str .= "<option value='" . $RS_DCODE . "'>" . $RS_DCODE_NM . "</option>";
		}
	}
	$tmp_str .= "</select>";
	return $tmp_str;
}

function makeSelectBoxAll($db, $pcode, $objname, $size, $str, $val, $checkVal)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	} else {
		$query .= " AND PCODE = 'XXXXXXXX' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "<select name='" . $objname . "' id='" . $objname . "' class='box01'  style='width:" . $size . "px;' >";

	if ($str <> "") {
		$tmp_str .= "<option value='" . $val . "'>" . $str . "</option>";
	}

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		if ($checkVal == $RS_DCODE) {
			$tmp_str .= "<option value='" . $RS_DCODE . "' selected>" . $RS_DCODE_NM . "</option>";
		} else {
			$tmp_str .= "<option value='" . $RS_DCODE . "'>" . $RS_DCODE_NM . "</option>";
		}
	}
	$tmp_str .= "</select>";
	return $tmp_str;
}

function getDcodeName($db, $pcode, $dcode)
{

	$query = "SELECT DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	}

	if ($pcode <> "") {
		$query .= " AND DCODE = '" . $dcode . "' ";
	}

	//echo $query;

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);

	if ($rows) {
		$tmp_str  = $rows[0];
	} else {
		$tmp_str  = "&nbsp;";
	}

	return $tmp_str;
}

function getDcodeCode($db, $pcode, $dcode_nm)
{

	$query = "SELECT DCODE
								FROM TBL_CODE_DETAIL WHERE 1 = 1 ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	}

	if ($pcode <> "") {
		$query .= " AND DCODE_NM = '" . $dcode_nm . "' ";
	}

	//echo $query;

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);

	if ($result <> "") {
		$tmp_str  = $rows[0];
	} else {
		$tmp_str  = "&nbsp;";
	}

	return $tmp_str;
}

function makeRadioBox($db, $pcode, $objname, $checkVal)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "";

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		if (($checkVal == "") && ($i == 0)) $checkVal = $RS_DCODE;

		if ($checkVal == $RS_DCODE) {
			$tmp_str .= "<div><input type = 'radio' class='cl_" . $objname . "' name= '" . $objname . "' id='" . $i . "_" . $objname . "' value='" . $RS_DCODE . "' checked><label for='" . $i . "_" . $objname . "'> " . $RS_DCODE_NM . "</label></div>";
		} else {
			$tmp_str .= "<div><input type = 'radio' class='cl_" . $objname . "' name= '" . $objname . "' id='" . $i . "_" . $objname . "' value='" . $RS_DCODE . "'><label for='" . $i . "_" . $objname . "'> " . $RS_DCODE_NM . "</label></div>";
		}
	}
	return $tmp_str;
}

function makeRadioBoxFront($db, $pcode, $objname, $checkVal)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' AND DCODE <> '0'";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "";

	if ($pcode == "IS_FEE") {
		$add_class = "class='rs'";
	} else {
		$add_class = "";
	}

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		if ($checkVal == $RS_DCODE) {
			$tmp_str .= "<span " . $add_class . "><input type='radio' class='cl_" . $objname . "' name= '" . $objname . "' id='" . $i . "_" . $objname . "' value='" . $RS_DCODE . "' checked><label for='" . $i . "_" . $objname . "'> " . $RS_DCODE_NM . "</label></span>";
		} else {
			$tmp_str .= "<span " . $add_class . "><input type='radio' class='cl_" . $objname . "' name= '" . $objname . "' id='" . $i . "_" . $objname . "' value='" . $RS_DCODE . "'><label for='" . $i . "_" . $objname . "'> " . $RS_DCODE_NM . "</label></span>";
		}
	}
	return $tmp_str;
}

function makeRadioBoxOnClick($db, $pcode, $objname, $checkVal)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "";

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		if ($checkVal == $RS_DCODE) {
			$tmp_str .= "<input type = 'radio' class='chk' style='width:15px' name= '" . $objname . "' value='" . $RS_DCODE . "' checked onClick=\"js_" . $objname . "();\"> " . $RS_DCODE_NM . " &nbsp;&nbsp;&nbsp;";
		} else {
			$tmp_str .= "<input type = 'radio' class='chk' style='width:15px' name= '" . $objname . "' value='" . $RS_DCODE . "' onClick=\"js_" . $objname . "();\"> " . $RS_DCODE_NM . " &nbsp;&nbsp;&nbsp;";
		}
	}
	return $tmp_str;
}

function makeCheckBox($db, $pcode, $objname, $checkVal)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "";

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		if (strrpos($checkVal, $RS_DCODE)) {
			$tmp_str .= "<div><input type='checkbox' class='cl_" . $objname . "' name='" . $objname . "' value='" . $RS_DCODE . "' id='" . $i . "_" . $objname . "' checked><label for='" . $i . "_" . $objname . "'> " . $RS_DCODE_NM . "</label></div>";
		} else {
			$tmp_str .= "<div><input type='checkbox' class='cl_" . $objname . "' name='" . $objname . "' value='" . $RS_DCODE . "' id='" . $i . "_" . $objname . "'><label for='" . $i . "_" . $objname . "'> " . $RS_DCODE_NM . "</label></div>";
		}
	}
	return $tmp_str;
}

function makeCheckBoxFront($db, $pcode, $objname, $checkVal)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "";

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		if (strrpos($checkVal, $RS_DCODE)) {
			$tmp_str .= " <span><input type='checkbox' class='cl_" . $objname . "' name='" . $objname . "' value='" . $RS_DCODE . "' id='" . $i . "_" . $objname . "' checked><label for='" . $i . "_" . $objname . "'> " . $RS_DCODE_NM . "</label></span> ";
		} else {
			$tmp_str .= " <span><input type='checkbox' class='cl_" . $objname . "' name='" . $objname . "' value='" . $RS_DCODE . "' id='" . $i . "_" . $objname . "'><label for='" . $i . "_" . $objname . "'> " . $RS_DCODE_NM . "</label></span> ";
		}
	}
	return $tmp_str;
}


function getSiteInfo($db, $site_no)
{

	$query = "SELECT SITE_NO, SITE_NM, SITE_LANG, SITE_CONTENT
								FROM TBL_SITE_INFO WHERE SITE_NO = '$site_no' ";

	$result = mysqli_query($db, $query);
	$record = array();

	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}
	return $record;
}

function getCodeList($db, $pcode)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";

	//echo $query;

	$result = mysqli_query($db, $query);
	$record = array();

	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}
	return $record;
}


function makeAdminGroupSelectBox($db, $objname, $size, $str, $val, $checkVal)
{

	$query = "SELECT GROUP_NO, GROUP_NAME
								FROM TBL_ADMIN_GROUP WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
	$query .= " ORDER BY GROUP_NAME ";

	//echo $checkVal;

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "<select name='" . $objname . "' style='width:" . $size . "px;'>";

	if ($str <> "") {
		$tmp_str .= "<option value='" . $val . "'>" . $str . "</option>";
	}

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$RS_GROUP_NO			= Trim($row[0]);
		$RS_GROUP_NAME		= Trim($row[1]);

		if (trim($checkVal) == trim($RS_GROUP_NO)) {
			$tmp_str .= "<option value='" . $RS_GROUP_NO . "' selected>" . $RS_GROUP_NAME . "</option>";
		} else {
			$tmp_str .= "<option value='" . $RS_GROUP_NO . "'>" . $RS_GROUP_NAME . "</option>";
		}
	}
	$tmp_str .= "</select>";
	return $tmp_str;
}

function getListAdminGroupMenu($db, $group_no)
{


	$query = "SELECT CONCAT(A.MENU_SEQ01,A.MENU_SEQ02,A.MENU_SEQ03) as SEQ, A.MENU_CD, A.MENU_NAME, A.MENU_URL, 
										 B.READ_FLAG, B.REG_FLAG, B.UPD_FLAG, B.DEL_FLAG, B.FILE_FLAG, A.MENU_RIGHT 
								FROM TBL_ADMIN_MENU A, TBL_ADMIN_MENU_RIGHT B 
							 WHERE A.MENU_CD = B.MENU_CD 
								 AND B.GROUP_NO = '" . $group_no . "' 
								 AND A.MENU_FLAG = 'Y'
								 AND A.DEL_TF = 'N'
							 ORDER BY SEQ ";


	//echo $query;

	$result = mysqli_query($db, $query);
	$record = array();


	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}
	return $record;
}


function makeScriptArray($db, $pcode, $objname)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str_name		=	"";
	$tmp_str_value	=	"";

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		$tmp_str_name		.= ",'" . $RS_DCODE_NM . "'";
		$tmp_str_value	.= ",'" . $RS_DCODE . "'";
	}

	$tmp_str_name  = substr($tmp_str_name, 1, strlen($tmp_str_name) - 1);
	$tmp_str_value = substr($tmp_str_value, 1, strlen($tmp_str_value) - 1);


	$tmp_str	= $objname . "_nm = new Array(" . $tmp_str_name . "); \n";
	$tmp_str .= $objname . "_val = new Array(" . $tmp_str_value . "); \n";

	return $tmp_str;
}


function makeRadioBoxWithConditionOnClick($db, $pcode, $objname, $checkVal, $condition)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' " . $condition . " ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "";

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		if ($checkVal == $RS_DCODE) {
			$tmp_str .= "<span><input type = 'radio' name= '" . $objname . "' value='" . $RS_DCODE . "' checked onClick=\"js_" . $objname . "();\"> " . $RS_DCODE_NM . " </span>&nbsp;&nbsp;&nbsp;";
		} else {
			$tmp_str .= "<span><input type = 'radio' name= '" . $objname . "' value='" . $RS_DCODE . "' onClick=\"js_" . $objname . "();\"> " . $RS_DCODE_NM . " </span>&nbsp;&nbsp;&nbsp;";
		}
	}
	return $tmp_str;
}

function makeSelectBoxWithCondition($db, $pcode, $objname, $size, $str, $val, $checkVal, $condition)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' " . $condition . " ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "<select name='" . $objname . "' class=\"box01\"  style='width:" . $size . "px;'>";

	if ($str <> "") {
		$tmp_str .= "<option value='" . $val . "'>" . $str . "</option>";
	}

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		if ($checkVal == $RS_DCODE) {
			$tmp_str .= "<option value='" . $RS_DCODE . "' selected>" . $RS_DCODE_NM . "</option>";
		} else {
			$tmp_str .= "<option value='" . $RS_DCODE . "'>" . $RS_DCODE_NM . "</option>";
		}
	}
	$tmp_str .= "</select>";
	return $tmp_str;
}

function makeSelectBoxWithConditionOnChange($db, $pcode, $objname, $size, $str, $val, $checkVal, $condition)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' " . $condition . " ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "<select name='" . $objname . "' class=\"box01\"  style='width:" . $size . "px;' onChange=\"js_" . $objname . "();\">";

	if ($str <> "") {
		$tmp_str .= "<option value='" . $val . "'>" . $str . "</option>";
	}

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		if ($checkVal == $RS_DCODE) {
			$tmp_str .= "<option value='" . $RS_DCODE . "' selected>" . $RS_DCODE_NM . "</option>";
		} else {
			$tmp_str .= "<option value='" . $RS_DCODE . "'>" . $RS_DCODE_NM . "</option>";
		}
	}
	$tmp_str .= "</select>";
	return $tmp_str;
}

function makeCategorySelectBoxOnChange($db, $checkVal)
{

	$query = "SELECT MAX(LENGTH(CATE_CD)) as MAX
									FROM TBL_CATEGORY WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);

	$MAX = $rows[0];

	if ($checkVal == "") {

		$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
									FROM TBL_CATEGORY 
								 WHERE 1 = 1 
									 AND DEL_TF = 'N' 
									 AND USE_TF = 'Y'
									 AND LENGTH(CATE_CD) = '2' 
								 ORDER BY SEQ ASC ";

		//echo $query;

		$result = mysqli_query($db, $query);
		$total  = mysqli_affected_rows($db);

		$tmp_str = "<select name='gd_cate_01' onChange=\"js_gd_cate_01();\" style=\"width:150px\">";
		$tmp_str .= "<option value=''>1차 분류 선택</option>";

		for ($i = 0; $i < $total; $i++) {
			mysqli_data_seek($result, $i);
			$row     = mysqli_fetch_array($result);

			$RS_CATE_CD		= Trim($row[1]);
			$RS_CATE_NAME	= Trim($row[2]);

			$tmp_str .= "<option value='" . $RS_CATE_CD . "'>" . $RS_CATE_NAME . "</option>";
		}
		$tmp_str .= "</select>&nbsp;";

		if ($MAX >= 4) {
			$tmp_str .= "<select name='gd_cate_02' onChange=\"js_gd_cate_02();\" style=\"width:150px\">";
			$tmp_str .= "<option value=''>2차 분류 선택</option>";
			$tmp_str .= "</select>&nbsp;";
		}

		/*
			if ($MAX >= 6) {
				$tmp_str .= "<select name='gd_cate_03' onChange=\"js_gd_cate_03();\" style=\"width:190px\">";
				$tmp_str .= "<option value=''>3차 분류 선택</option>";
				$tmp_str .= "</select>&nbsp;";
			}

			if ($MAX >= 8) {
				$tmp_str .= "<select name='gd_cate_04' onChange=\"js_gd_cate_04();\" style=\"width:190px\">";
				$tmp_str .= "<option value=''>4차 지역분류 선택</option>";
				$tmp_str .= "</select>&nbsp;";
			}
			*/
	} else {

		$cate_01 = substr($checkVal, 0, 2);
		$cate_02 = substr($checkVal, 0, 4);
		$cate_03 = substr($checkVal, 0, 6);
		$cate_04 = substr($checkVal, 0, 8);

		$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
									FROM TBL_CATEGORY 
								 WHERE 1 = 1 
									 AND DEL_TF = 'N' 
									 AND USE_TF = 'Y'
									 AND LENGTH(CATE_CD) = '2' 
								 ORDER BY SEQ ASC ";

		$result = mysqli_query($db, $query);
		$total  = mysqli_affected_rows($db);

		$tmp_str = "<select name='gd_cate_01' onChange=\"js_gd_cate_01();\" style=\"width:150px\">";
		$tmp_str .= "<option value=''>1차 분류 선택</option>";

		for ($i = 0; $i < $total; $i++) {
			mysqli_data_seek($result, $i);
			$row     = mysqli_fetch_array($result);

			$RS_CATE_CD		= Trim($row[1]);
			$RS_CATE_NAME	= Trim($row[2]);

			if (trim($cate_01) == trim($RS_CATE_CD)) {
				$tmp_str .= "<option value='" . $RS_CATE_CD . "' selected>" . $RS_CATE_NAME . "</option>";
			} else {
				$tmp_str .= "<option value='" . $RS_CATE_CD . "'>" . $RS_CATE_NAME . "</option>";
			}
		}
		$tmp_str .= "</select>&nbsp;";

		if (strlen($checkVal) >= 2) {

			$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
										FROM TBL_CATEGORY 
									 WHERE 1 = 1 
										 AND DEL_TF = 'N' 
										 AND USE_TF = 'Y'
										 AND CATE_CD LIKE '" . $cate_01 . "%' 
										 AND LENGTH(CATE_CD) = '4' 
									 ORDER BY SEQ ASC ";
			$result = mysqli_query($db, $query);
			$total  = mysqli_affected_rows($db);

			$tmp_str .= "<select name='gd_cate_02' onChange=\"js_gd_cate_02();\" style=\"width:150px\">";
			$tmp_str .= "<option value=''>2차 분류 선택</option>";

			for ($i = 0; $i < $total; $i++) {
				mysqli_data_seek($result, $i);
				$row     = mysqli_fetch_array($result);

				$RS_CATE_CD		= Trim($row[1]);
				$RS_CATE_NAME	= Trim($row[2]);

				if (trim($cate_02) == trim($RS_CATE_CD)) {
					$tmp_str .= "<option value='" . $RS_CATE_CD . "' selected>" . $RS_CATE_NAME . "</option>";
				} else {
					$tmp_str .= "<option value='" . $RS_CATE_CD . "'>" . $RS_CATE_NAME . "</option>";
				}
			}
			$tmp_str .= "</select>&nbsp;";
		}
	}

	return $tmp_str;
}

function makeCompanySelectBox($db, $cp_type, $checkVal)
{

	$query = "SELECT CP_NO, CP_NM, CP_TYPE
								FROM TBL_COMPANY WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	if ($cp_type <> "") {
		$query .= " AND CP_TYPE IN ('" . $cp_type . "','판매공급') ";
	}

	$query .= " ORDER BY CP_NM ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "<select name='cp_type' class=\"txt\" >";

	$tmp_str .= "<option value=''> 소속선택 </option>";

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$RS_DCODE				= Trim($row[0]);
		$RS_DCODE_NM		= Trim($row[1]);
		$RS_DCODE_TYPE	= Trim($row[2]);

		if ($checkVal == $RS_DCODE) {
			$tmp_str .= "<option value='" . $RS_DCODE . "' selected>" . $RS_DCODE_NM . " [" . $RS_DCODE . " " . $RS_DCODE_TYPE . "]</option>";
		} else {
			$tmp_str .= "<option value='" . $RS_DCODE . "'>" . $RS_DCODE_NM . " [" . $RS_DCODE . " " . $RS_DCODE_TYPE . "]</option>";
		}
	}
	$tmp_str .= "</select>";
	return $tmp_str;
}

function makeCompanySelectBoxWithName($db, $obj, $cp_type, $checkVal)
{

	$query = "SELECT CP_NO, CP_NM, CP_TYPE
								FROM TBL_COMPANY WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	if ($cp_type <> "") {
		$query .= " AND CP_TYPE IN ('" . $cp_type . "','판매공급') ";
	}

	$query .= " ORDER BY CP_NM ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "<select name='" . $obj . "' class=\"txt\" >";

	$tmp_str .= "<option value=''> 업체선택 </option>";

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$RS_DCODE				= Trim($row[0]);
		$RS_DCODE_NM		= Trim($row[1]);
		$RS_DCODE_TYPE	= Trim($row[2]);

		if ($checkVal == $RS_DCODE) {
			$tmp_str .= "<option value='" . $RS_DCODE . "' selected>" . $RS_DCODE_NM . " [" . $RS_DCODE . " " . $RS_DCODE_TYPE . "]</option>";
		} else {
			$tmp_str .= "<option value='" . $RS_DCODE . "'>" . $RS_DCODE_NM . " [" . $RS_DCODE . " " . $RS_DCODE_TYPE . "]</option>";
		}
	}
	$tmp_str .= "</select>";
	return $tmp_str;
}

function makeCompanySelectBoxAsCpNo($db, $cp_type, $checkVal)
{

	$query = "SELECT CP_NO, CP_NM
								FROM TBL_COMPANY WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	if ($cp_type <> "") {
		$query .= " AND CP_TYPE IN ('" . $cp_type . "','판매공급') ";
	}

	$query .= " ORDER BY CP_NM ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "<select name='cp_type2' class=\"txt\" >";

	$tmp_str .= "<option value=''> 업체선택 </option>";

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		if ($checkVal == $RS_DCODE) {
			$tmp_str .= "<option value='" . $RS_DCODE . "' selected>" . $RS_DCODE_NM . " [" . $RS_DCODE . "]</option>";
		} else {
			$tmp_str .= "<option value='" . $RS_DCODE . "'>" . $RS_DCODE_NM . " [" . $RS_DCODE . "]</option>";
		}
	}
	$tmp_str .= "</select>";
	return $tmp_str;
}

function makeCompanySelectBoxOnChabge($db, $cp_type, $checkVal)
{

	$query = "SELECT CP_NO, CP_NM
								FROM TBL_COMPANY WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	if ($cp_type <> "") {
		$query .= " AND CP_TYPE IN ('" . $cp_type . "','판매공급') ";
	}

	$query .= " ORDER BY CP_NM ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "<select name='cp_type' class=\"txt\" onChange=\"js_cp_type()\">";

	$tmp_str .= "<option value=''> 업체선택 </option>";

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		if ($checkVal == $RS_DCODE) {
			$tmp_str .= "<option value='" . $RS_DCODE . "' selected>" . $RS_DCODE_NM . " [" . $RS_DCODE . "]</option>";
		} else {
			$tmp_str .= "<option value='" . $RS_DCODE . "'>" . $RS_DCODE_NM . " [" . $RS_DCODE . "]</option>";
		}
	}
	$tmp_str .= "</select>";
	return $tmp_str;
}

function getCompanyName($db, $cp_code)
{

	if (is_numeric($cp_code)) {

		$query = "SELECT CP_NO, CP_NM FROM TBL_COMPANY WHERE CP_NO = '" . $cp_code . "' ";

		//echo $query;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);

		if ($result <> "") {
			$tmp_str  = $rows[1] . " [" . $rows[0] . "]";
		} else {
			$tmp_str  = "&nbsp;";
		}
	} else {
		$tmp_str  = "&nbsp;";
	}

	return $tmp_str;
}

function getCategoryName($db, $cate_code)
{
	$query = "SELECT CATE_CD, CATE_NAME
								FROM TBL_CATEGORY WHERE 1 = 1 ";
	$query .= " AND CATE_CD = '$cate_code' ";


	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);

	if ($rows[0]) {
		$tmp_str  = $rows[1] . " [" . $rows[0] . "]";
	} else {
		$tmp_str  = "&nbsp;";
	}

	return $tmp_str;
}

function getCategoryName2($db, $cate_code)
{
	$query = "SELECT CATE_CD, CATE_NAME
								FROM TBL_CATEGORY WHERE 1 = 1 ";
	$query .= " AND CATE_CD = '$cate_code' ";


	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);

	if ($rows[0]) {
		$tmp_str  = $rows[1];
	} else {
		$tmp_str  = "&nbsp;";
	}

	return $tmp_str;
}

function getCompanyCode($db, $admin_id)
{

	$query = "SELECT C.CP_NO
							FROM TBL_COMPANY C, TBL_ADMIN_INFO A
						 WHERE C.CP_NO = A.COM_CODE
							 AND A.ADM_ID	= '$admin_id'
							 AND C.DEL_TF = 'N' ";

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);

	if ($result <> "") {
		$tmp_str  = $rows[0];
	} else {
		$tmp_str  = "&nbsp;";
	}

	return $tmp_str;
}


function getDeliveryLink($db, $delivery_cp, $delivery_no)
{

	$delivery_url = "";

	$query = "SELECT DCODE_EXT, DCODE, DCODE_NM 
							 FROM TBL_CODE_DETAIL 
							WHERE PCODE = 'DELIVERY_CP'
								AND DCODE = '$delivery_cp'
								AND USE_TF = 'Y' 
								AND DEL_TF = 'N' ";

	//echo $query;

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);

	if ($result <> "") {
		$delivery_url		= $rows[0];
		$delivery_cp		= $rows[2];
	}

	if ($delivery_url == "") {
		$url = "택배사경로 없음";
	} else {
		$url = "<a href='" . $delivery_url . $delivery_no . "' target='_new'>" . $delivery_cp . " " . $delivery_no . "</a>";
	}
	return $url;
}


function getCompayChk($db, $cp_type, $s_adm_cp_type, $cp_no)
{

	if ($s_adm_cp_type = "운영") {

		$query = "SELECT COUNT(*) AS CNT FROM TBL_COMPANY 
							 WHERE CP_TYPE LIKE '%" . $cp_type . "%' 
								 AND CP_NO	= '$cp_no'
								 AND DEL_TF = 'N' ";
	} else {

		$query = "SELECT COUNT(*) AS CNT 
								FROM TBL_COMPANY C, TBL_ADMIN_INFO A
							 WHERE C.CP_NO = A.COM_CODE
								 AND C.CP_TYPE LIKE '%" . $cp_type . "%' 
								 AND A.ADM_ID	= '$cp_no'
								 AND C.DEL_TF = 'N' ";
	}

	//echo $query;
	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);

	if ($rows[0] == 0) {
		return false;
	} else {
		return true;
	}
}

function getCompanyChk($db, $cp_no)
{

	$query = "SELECT COUNT(*) AS CNT FROM TBL_COMPANY 
						 WHERE CP_NO	= '$cp_no'
							 AND DEL_TF = 'N' ";


	//echo $query;
	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);

	if ($rows[0] == 0) {
		return false;
	} else {
		return true;
	}
}

function getDeliveryUrl($db, $delivery_cp)
{

	$query = "SELECT DCODE_EXT FROM TBL_CODE_DETAIL WHERE PCODE = 'DELIVERY_CP' AND DCODE = '$delivery_cp' AND USE_TF = 'Y' AND DEL_TF = 'N' ";
	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);

	if ($rows[0]) {
		return $rows[0];
	} else {
		return false;
	}
}

function isHeadAdmin($db, $adm_no)
{

	$query = "SELECT COUNT(*) AS CNT 
							FROM TBL_ADMIN_INFO A, TBL_COMPANY C
						 WHERE A.COM_CODE = C.CP_NO
							 AND C.CP_TYPE = '운영'
							 AND A.DEL_TF = 'N'
							 AND A.USE_TF = 'Y'
							 AND A.ADM_NO = '$adm_no' ";

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);

	if ($rows[0] == 0) {
		return false;
	} else {
		return true;
	}
}

function getBanner($db, $site_no, $banner_type, $nRowCount)
{

	$query = "SELECT BANNER_NO, SITE_NO, BANNER_TYPE, BANNER_NM, BANNER_IMG, BANNER_REAL_IMG, BANNER_URL, DISP_SEQ,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BANNER WHERE 1 = 1 AND USE_TF = 'Y' AND DEL_TF = 'N' ";

	$query .= " AND BANNER_TYPE = '" . $banner_type . "' ";
	$query .= " AND SITE_NO = '" . $site_no . "' ";

	$query .= " ORDER BY DISP_SEQ asc limit 0, " . $nRowCount;

	//echo $query;

	$result = mysqli_query($db, $query);
	$record = array();


	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}
	return $record;
}

function makeCategorySelectBoxOnChangeArea($db, $checkVal)
{

	$query = "SELECT MAX(LENGTH(CATE_CD)) as MAX
									FROM TBL_CATEGORY WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);

	$MAX = $rows[0];

	if ($checkVal == "") {

		$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
									FROM TBL_CATEGORY 
								 WHERE 1 = 1 
									 AND DEL_TF = 'N' 
									 AND USE_TF = 'Y'
									 AND CATE_CD <> '01' 
									 AND LENGTH(CATE_CD) = '2' 
								 ORDER BY SEQ ASC ";

		//echo $query;

		$result = mysqli_query($db, $query);
		$total  = mysqli_affected_rows($db);

		$tmp_str = "<select name='gd_cate_01' onChange=\"js_gd_cate_01();\">";
		$tmp_str .= "<option value=''>1차 지역분류 선택</option>";

		for ($i = 0; $i < $total; $i++) {
			mysqli_data_seek($result, $i);
			$row     = mysqli_fetch_array($result);

			$RS_CATE_CD		= Trim($row[1]);
			$RS_CATE_NAME	= Trim($row[2]);

			$tmp_str .= "<option value='" . $RS_CATE_CD . "'>" . $RS_CATE_NAME . "</option>";
		}
		$tmp_str .= "</select>&nbsp;";


		if ($MAX >= 4) {
			$tmp_str .= "<select name='gd_cate_02' onChange=\"js_gd_cate_02();\">";
			$tmp_str .= "<option value=''>2차 지역분류 선택</option>";
			$tmp_str .= "</select>&nbsp;";
		}

		if ($MAX >= 6) {
			$tmp_str .= "<select name='gd_cate_03' onChange=\"js_gd_cate_03();\">";
			$tmp_str .= "<option value=''>3차 지역분류 선택</option>";
			$tmp_str .= "</select>&nbsp;";
		}

		if ($MAX >= 8) {
			$tmp_str .= "<select name='gd_cate_04' onChange=\"js_gd_cate_04();\">";
			$tmp_str .= "<option value=''>4차 지역분류 선택</option>";
			$tmp_str .= "</select>&nbsp;";
		}
	} else {

		$cate_01 = substr($checkVal, 0, 2);
		$cate_02 = substr($checkVal, 0, 4);
		$cate_03 = substr($checkVal, 0, 6);
		$cate_04 = substr($checkVal, 0, 8);

		$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
									FROM TBL_CATEGORY 
								 WHERE 1 = 1 
									 AND DEL_TF = 'N' 
									 AND USE_TF = 'Y'
									 AND CATE_CD <> '01' 
									 AND LENGTH(CATE_CD) = '2' 
								 ORDER BY SEQ ASC ";

		$result = mysqli_query($db, $query);
		$total  = mysqli_affected_rows($db);

		$tmp_str = "<select name='gd_cate_01' onChange=\"js_gd_cate_01();\">";
		$tmp_str .= "<option value=''>1차 지역분류 선택</option>";

		for ($i = 0; $i < $total; $i++) {
			mysqli_data_seek($result, $i);
			$row     = mysqli_fetch_array($result);

			$RS_CATE_CD		= Trim($row[1]);
			$RS_CATE_NAME	= Trim($row[2]);

			if (trim($cate_01) == trim($RS_CATE_CD)) {
				$tmp_str .= "<option value='" . $RS_CATE_CD . "' selected>" . $RS_CATE_NAME . "</option>";
			} else {
				$tmp_str .= "<option value='" . $RS_CATE_CD . "'>" . $RS_CATE_NAME . "</option>";
			}
		}
		$tmp_str .= "</select>&nbsp;";

		if (strlen($checkVal) >= 2) {

			$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
										FROM TBL_CATEGORY 
									 WHERE 1 = 1 
										 AND DEL_TF = 'N' 
										 AND USE_TF = 'Y'
										 AND CATE_CD LIKE '" . $cate_01 . "%' 
										 AND LENGTH(CATE_CD) = '4' 
									 ORDER BY SEQ ASC ";
			$result = mysqli_query($db, $query);
			$total  = mysqli_affected_rows($db);

			$tmp_str .= "<select name='gd_cate_02' onChange=\"js_gd_cate_02();\">";
			$tmp_str .= "<option value=''>2차 지역분류 선택</option>";

			for ($i = 0; $i < $total; $i++) {
				mysqli_data_seek($result, $i);
				$row     = mysqli_fetch_array($result);

				$RS_CATE_CD		= Trim($row[1]);
				$RS_CATE_NAME	= Trim($row[2]);

				if (trim($cate_02) == trim($RS_CATE_CD)) {
					$tmp_str .= "<option value='" . $RS_CATE_CD . "' selected>" . $RS_CATE_NAME . "</option>";
				} else {
					$tmp_str .= "<option value='" . $RS_CATE_CD . "'>" . $RS_CATE_NAME . "</option>";
				}
			}
			$tmp_str .= "</select>&nbsp;";
		}

		if (strlen($checkVal) >= 4) {

			$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
										FROM TBL_CATEGORY 
									 WHERE 1 = 1 
										 AND DEL_TF = 'N' 
										 AND USE_TF = 'Y'
										 AND CATE_CD LIKE '" . $cate_02 . "%' 
										 AND LENGTH(CATE_CD) = '6' 
									 ORDER BY SEQ ASC ";
			$result = mysqli_query($db, $query);
			$total  = mysqli_affected_rows($db);

			$tmp_str .= "<select name='gd_cate_03' onChange=\"js_gd_cate_03();\">";
			$tmp_str .= "<option value=''>3차 지역분류 선택</option>";

			for ($i = 0; $i < $total; $i++) {
				mysqli_data_seek($result, $i);
				$row     = mysqli_fetch_array($result);

				$RS_CATE_CD		= Trim($row[1]);
				$RS_CATE_NAME	= Trim($row[2]);

				if (trim($cate_03) == trim($RS_CATE_CD)) {
					$tmp_str .= "<option value='" . $RS_CATE_CD . "' selected>" . $RS_CATE_NAME . "</option>";
				} else {
					$tmp_str .= "<option value='" . $RS_CATE_CD . "'>" . $RS_CATE_NAME . "</option>";
				}
			}
			$tmp_str .= "</select>&nbsp;";
		}

		if (strlen($checkVal) >= 6) {

			$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
										FROM TBL_CATEGORY 
									 WHERE 1 = 1 
										 AND DEL_TF = 'N' 
										 AND USE_TF = 'Y'
										 AND CATE_CD LIKE '" . $cate_03 . "%' 
										 AND LENGTH(CATE_CD) = '8' 
									 ORDER BY SEQ ASC ";
			$result = mysqli_query($db, $query);
			$total  = mysqli_affected_rows($db);

			$tmp_str .= "<select name='gd_cate_04' onChange=\"js_gd_cate_04();\">";
			$tmp_str .= "<option value=''>4차 지역분류 선택</option>";

			for ($i = 0; $i < $total; $i++) {
				mysqli_data_seek($result, $i);
				$row     = mysqli_fetch_array($result);

				$RS_CATE_CD		= Trim($row[1]);
				$RS_CATE_NAME	= Trim($row[2]);

				if (trim($cate_04) == trim($RS_CATE_CD)) {
					$tmp_str .= "<option value='" . $RS_CATE_CD . "' selected>" . $RS_CATE_NAME . "</option>";
				} else {
					$tmp_str .= "<option value='" . $RS_CATE_CD . "'>" . $RS_CATE_NAME . "</option>";
				}
			}
			$tmp_str .= "</select>&nbsp;";
		}

		if (strlen($checkVal) == 2) {

			if ($MAX >= 6) {
				$tmp_str .= "<select name='gd_cate_03' onChange=\"js_gd_cate_03();\">";
				$tmp_str .= "<option value=''>3차 지역분류 선택</option>";
				$tmp_str .= "</select>&nbsp;";
			}

			if ($MAX >= 8) {
				$tmp_str .= "<select name='gd_cate_04' onChange=\"js_gd_cate_04();\">";
				$tmp_str .= "<option value=''>4차 지역분류 선택</option>";
				$tmp_str .= "</select>&nbsp;";
			}
		}

		if (strlen($checkVal) == 4) {


			if ($MAX >= 8) {
				$tmp_str .= "<select name='gd_cate_04' onChange=\"js_gd_cate_04();\">";
				$tmp_str .= "<option value=''>4차 지역분류 선택</option>";
				$tmp_str .= "</select>&nbsp;";
			}
		}
	}

	return $tmp_str;
}

function getBannerList($db, $banner_type, $dip_cnt)
{

	$tmp_str = "";

	$query = "SELECT BANNER_IMG, BANNER_URL, URL_TYPE, BANNER_NM 
								FROM TBL_BANNER WHERE BANNER_TYPE = '$banner_type' AND USE_TF = 'Y' AND DEL_TF = 'N' ORDER BY DISP_SEQ ASC LIMIT $dip_cnt ";

	$result = mysqli_query($db, $query);
	$record = array();


	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}
	return $record;
}

function getBcodeName($db, $board_code)
{

	$query = "SELECT BOARD_NM
								FROM TBL_BOARD_CONFIG WHERE 1 = 1 ";

	if ($board_code <> "") {
		$query .= " AND BOARD_CODE = '" . $board_code . "' ";
	}

	//echo $query;

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);

	if ($result <> "") {
		$tmp_str  = $rows[0];
	} else {
		$tmp_str  = "&nbsp;";
	}

	return $tmp_str;
}

function getBcodeCate($db, $board_code)
{

	$query = "SELECT BOARD_CATE
								FROM TBL_BOARD_CONFIG WHERE 1 = 1 ";

	if ($board_code <> "") {
		$query .= " AND BOARD_CODE = '" . $board_code . "' ";
	}

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);

	if ($result <> "") {
		$tmp_str  = $rows[0];
	} else {
		$tmp_str  = "&nbsp;";
	}

	return $tmp_str;
}

function getCommonBannerList($db, $banner_lang, $banner_type)
{

	$query = "SELECT * FROM TBL_BANNER WHERE BANNER_LANG = '$banner_lang' AND BANNER_TYPE = '$banner_type' AND DEL_TF ='N' AND USE_TF = 'Y' ORDER BY DISP_SEQ ASC ";

	//echo $query;

	$result = mysqli_query($db, $query);
	$record = array();

	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}

	return $record;
}

function chkBlockIP($db, $ip)
{

	$query = "SELECT COUNT(*) CNT FROM TBL_BLOCK_IP WHERE BLOCK_IP = '$ip' AND USE_TF = 'Y' ";

	//echo $query;

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);
	$record  = $rows[0];

	if ($record == 0) {
		return false;
	} else {
		return true;
	}
}

function isHoliday($db, $h_date)
{

	$we = date("w", strtotime($h_date));

	$is_holiday = "false";

	if (($we == "0") || ($we == "6")) {

		$is_holiday = "true";
	} else {

		$query = "SELECT COUNT(H_DATE) AS CNT FROM TBL_HOLIDAY WHERE H_DATE = '$h_date' AND IS_HOLIDAY = 'Y' ";

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);

		if ($rows[0] > 0) {
			$is_holiday = "true";
		}
	}
	return $is_holiday;
}

function insertUserLog($db, $user_type, $log_id, $log_ip, $task, $task_type)
{

	$query = "INSERT INTO TBL_USER_LOG (USER_TYPE, LOG_ID, LOG_IP, LOGIN_DATE, TASK, TASK_TYPE) 
															values ('$user_type', '$log_id', '$log_ip', now(), '$task', '$task_type'); ";

	//echo $query;
	//exit;

	if (!mysqli_query($db, $query)) {
		return false;
		echo "<script>alert(\"[1]오류가 발생하였습니다 - " . mysql_errno() . ":" . mysql_error() . "\"); //history.go(-1);</script>";
		exit;
	} else {
		return true;
	}
}

function getFbInfo($db, $b_code, $b_no)
{

	$query = "SELECT * FROM TBL_BOARD WHERE  B_CODE = '$b_code' AND  B_NO = '$b_no' ";
	//echo $query;
	$result = mysqli_query($db, $query);
	$record = array();

	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}
	return $record;
}

function getAdminName($db, $adm_no)
{

	$query = "SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = '$adm_no' ";

	//echo $query;

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);
	$record  = $rows[0];
	return $record;
}

function getAdminNo($db, $adm_name)
{

	$query = "SELECT ADM_NO FROM TBL_ADMIN_INFO WHERE ADM_NAME = '$adm_name' ";

	//echo $query;

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);
	$record  = $rows[0];
	return $record;
}

function getMemberMemNo($db, $mem_num)
{

	$query = "SELECT MEM_NO FROM TBL_MEMBER WHERE MEM_NUM = '$mem_num' ";

	//echo $query;

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);
	$record  = $rows[0];
	return $record;
}

// 고객번호 생성 함수 입니다.
// 유일키를 생성
function getMemberNum($db, $type, $len = 11)
{

	$thisdate = date("Ymd", strtotime("0 month"));;

	$query = " INSERT INTO TBL_MEMBER_NO (THIS_DATE) VALUES ('$thisdate'); ";

	//echo $query;

	if (mysqli_query($db, $query)) {
		$new_member_no	= mysqli_insert_id();
		$new_member_num	= $type . right("00000000000" . $new_member_no, 10);

		$arr_member_no = array(
			'no' => $new_member_no,
			'num' => $new_member_num
		);
	}

	return $arr_member_no;
}

// 고객번호 생성 함수 입니다.
// 유일키를 생성
function getAdminInfoNum($db, $type, $len = 10)
{

	$thisdate = date("Ymd", strtotime("0 month"));;

	$query = " INSERT INTO TBL_ADMIN_NO (THIS_DATE) VALUES ('$thisdate'); ";

	if (mysqli_query($db, $query)) {
		$new_admin_no	= mysqli_insert_id();
		$new_admin_num	= $type . right("00000000000" . $new_admin_no, 11);

		$arr_admin_no = array(
			'no' => $new_admin_no,
			'num' => $new_admin_num
		);
	}

	return $arr_admin_no;
}


function makeAdminSelectBox($db, $objname, $str, $val, $checkVal)
{

	$query = "SELECT ADM_ID, ADM_NO, ADM_NAME
								FROM TBL_ADMIN_INFO WHERE 1 = 1 AND USE_TF = 'Y' AND DEL_TF = 'N' ";

	$query .= " ORDER BY ADM_NAME ASC ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "<select name='" . $objname . "' id='" . $objname . "' >";

	if ($str <> "") {
		$tmp_str .= "<option value='" . $val . "'>" . $str . "</option>";
	}

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row	= mysqli_fetch_array($result);

		$RS_ADM_ID		= Trim($row[0]);
		$RS_ADM_NO		= Trim($row[1]);
		$RS_ADM_NAME	= Trim($row[2]);

		if ($checkVal == $RS_ADM_NO) {
			$tmp_str .= "<option value='" . $RS_ADM_NO . "' selected>" . $RS_ADM_NAME . "[" . $RS_ADM_ID . "]</option>";
		} else {
			$tmp_str .= "<option value='" . $RS_ADM_NO . "'>" . $RS_ADM_NAME . "[" . $RS_ADM_ID . "]</option>";
		}
	}
	$tmp_str .= "</select>";
	return $tmp_str;
}

function makeAreaSelect($db, $pcode, $checkVal)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	} else {
		$query .= " AND PCODE = 'XXXXXXXX' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	if ($checkVal == "") {
		$tmp_str = "<li class='on cl_area' alt=''><a href=\"javascript:js_area_sel('')\">전체</a></li>";
	} else {
		$tmp_str = "<li class='cl_area' alt=''><a href=\"javascript:js_area_sel('')\">전체</a></li>";
	}

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$RS_DCODE			= Trim($row[0]);
		$RS_DCODE_NM	= Trim($row[1]);

		if ($checkVal == $RS_DCODE) {
			$tmp_str .= "<li class='on cl_area' alt='" . $RS_DCODE . "'><a href=\"javascript:js_area_sel('" . $RS_DCODE . "')\">" . $RS_DCODE_NM . "</a></li>";
		} else {
			$tmp_str .= "<li class='cl_area' alt='" . $RS_DCODE . "'><a href=\"javascript:js_area_sel('" . $RS_DCODE . "')\">" . $RS_DCODE_NM . "</a></li>";
		}
	}

	return $tmp_str;
}

function getLatLongAsAreaName($db, $sido, $sigungu)
{

	$query = "SELECT AREA_CD, AREA_LAT, AREA_LONG, ZOOM FROM TBL_AREA WHERE AREA_NAME = '$sido' ";
	$result			= mysqli_query($db, $query);
	$rows				= mysqli_fetch_array($result);
	$area_cd		= $rows[0];
	$area_lat		= $rows[1];
	$area_long	= $rows[2];
	$zoom				= $rows[3];

	if ($sigungu <> "") {
		$query = "SELECT AREA_CD, AREA_LAT, AREA_LONG, ZOOM FROM TBL_AREA WHERE AREA_CD like '" . $area_cd . "%' AND AREA_NAME = '$sigungu'";
		$result			= mysqli_query($db, $query);
		$rows				= mysqli_fetch_array($result);
		$area_cd		= $rows[0];
		$area_lat		= $rows[1];
		$area_long	= $rows[2];
		$zoom				= $rows[3];

		//echo $query;
	}

	return $area_lat . "^" . $area_long . "^" . $zoom;
}


function insertLogData($db, $arr_data)
{

	// 게시물 등록
	$set_field = "";
	$set_value = "";

	foreach ($arr_data as $key => $value) {
		if ($key == "REF_IP") $value = $_SERVER['REMOTE_ADDR'];
		$set_field .= $key . ",";
		$set_value .= "'" . $value . "',";
	}

	$query = "INSERT INTO TBL_LOG (" . $set_field . " REG_DATE) 
					values (" . $set_value . " now()); ";

	//echo $query."<br>";
	//exit;

	if (!mysqli_query($db, $query)) {
		return false;
		echo "<script>alert(\"[1]오류가 발생하였습니다.\"); //history.go(-1);</script>";
		exit;
	} else {
		return true;
	}
}

function getApplyNum($db, $pseq_no, $type)
{

	$thisdate_app_no = date("Ymd", strtotime("0 month"));

	$query = "SELECT COUNT(CNT_NO) AS CNT FROM TBL_APPLY_NUM WHERE PSEQ_NO = '$pseq_no'";
	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);

	if (!$rows[0]) {
		$sql = " INSERT INTO TBL_APPLY_NUM (CNT_NO, PSEQ_NO) VALUES ('1','$pseq_no'); ";
	} else {
		$sql = " UPDATE TBL_APPLY_NUM SET CNT_NO = CNT_NO + 1 WHERE PSEQ_NO = '$pseq_no' ";
	}

	mysqli_query($db, $sql);

	$query = "SELECT IFNULL(MAX(CNT_NO),0) AS NEXT_NO FROM TBL_APPLY_NUM WHERE PSEQ_NO = '$pseq_no' ";
	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);
	$new_apply_no  = $thisdate_app_no . $type . right("00000" . $rows[0], 5);

	return $new_apply_no;
}


function getCurrentProgram($db)
{

	$query = "SELECT SEQ_NO FROM TBL_PROGRAM 
							WHERE TYPE = 'TYPE01' 
								AND DEL_TF = 'N'
								AND USE_TF = 'Y'
							ORDER BY SEQ_NO DESC
							LIMIT 1 ";

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);
	$record  = $rows[0];
	return $record;
}

function getAmountType03($db, $seq_no)
{

	$query = "SELECT SUPPORTFUND FROM TBL_PROGRAM 
							WHERE SEQ_NO = '$seq_no' ";

	//echo $query;

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);
	$record  = $rows[0];
	return $record;
}




function makeReportSelectBox($db, $objname, $str, $val, $checkVal, $next_objname, $next_str, $next_val, $next_checkVal)
{

	$query = "SELECT CONCAT(AC_SEQ01,AC_SEQ02,AC_SEQ03) as SEQ,
										 AC_NO, AC_CD, AC_NAME, AC_SEQ01, AC_SEQ02, AC_SEQ03, 
										 USE_TF, DEL_TF
								FROM TBL_ACCOUNT WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND length(AC_CD) = '2' ORDER BY SEQ ASC ";

	$result = mysqli_query($db, $query);
	$arr_cate01 = array();

	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}

	$tmp_str = "<select name='" . $objname . "' id='" . $objname . "' style='width:150px'>";

	if ($str <> "") {
		$tmp_str .= "<option value='" . $val . "'>" . $str . "</option>";
	}

	for ($i = 0; $i < sizeof($arr_cate01); $i++) {

		$RS_AC_CD		= Trim($arr_cate01[$i]['AC_CD']);
		$RS_AC_NAME	= Trim($arr_cate01[$i]['AC_NAME']);

		if ($checkVal == $RS_AC_CD) {
			$tmp_str .= "<option value='" . $RS_AC_CD . "' selected>" . $RS_AC_NAME . "</option>";
		} else {
			$tmp_str .= "<option value='" . $RS_AC_CD . "'>" . $RS_AC_NAME . "</option>";
		}
	}

	$tmp_str .= "</select> ";

	$tmp_str .= "<select name='" . $next_objname . "' id='" . $next_objname . "' style='width:150px'>";

	if ($checkVal <> "") {

		$query = "SELECT CONCAT(AC_SEQ01,AC_SEQ02,AC_SEQ03) as SEQ, AC_CD, AC_NAME 
								 FROM TBL_ACCOUNT 
								WHERE left(AC_CD,2) LIKE (SELECT AC_CD FROM TBL_ACCOUNT WHERE AC_NAME = '$checkVal' AND length(AC_CD) = 2)
									AND length(AC_CD) = 4
									AND USE_TF ='Y'
									AND DEL_TF ='N'
								ORDER BY SEQ ASC ";

		//echo $query;

		$result = mysqli_query($db, $query);
		$arr_cate02 = array();

		if ($result <> "") {
			for ($i = 0; $i < mysqli_num_rows($result); $i++) {
				$record[$i] = sql_result_array($db, $result, $i);
			}
		}

		if ($next_str <> "") {
			$tmp_str .= "<option value='" . $next_val . "'>" . $next_str . "</option>";
		}

		for ($i = 0; $i < sizeof($arr_cate02); $i++) {

			$RS_AC_CD		= Trim($arr_cate02[$i]['AC_CD']);
			$RS_AC_NAME	= Trim($arr_cate02[$i]['AC_NAME']);

			if ($next_checkVal == $RS_AC_CD) {
				$tmp_str .= "<option value='" . $RS_AC_CD . "' selected>" . $RS_AC_NAME . "</option>";
			} else {
				$tmp_str .= "<option value='" . $RS_AC_CD . "'>" . $RS_AC_NAME . "</option>";
			}
		}
	} else {

		if ($next_str <> "") {
			$tmp_str .= "<option value='" . $next_val . "'>" . $next_str . "</option>";
		}
	}

	$tmp_str .= "</select>";
	return $tmp_str;
}


function makeFrontReportSelectBox($db, $objname, $str, $val, $checkVal, $next_objname, $next_str, $next_val, $next_checkVal)
{

	$query = "SELECT CONCAT(AC_SEQ01,AC_SEQ02,AC_SEQ03) as SEQ,
										 AC_NO, AC_CD, AC_NAME, AC_SEQ01, AC_SEQ02, AC_SEQ03, 
										 USE_TF, DEL_TF
								FROM TBL_ACCOUNT WHERE USE_TF = 'Y' AND DEL_TF = 'N' AND length(AC_CD) = '2' ORDER BY SEQ ASC ";

	$result = mysqli_query($db, $query);
	$arr_cate01 = array();

	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}

	$tmp_str = "<li class='half'>";
	$tmp_str .= "<label>구분</label>";
	$tmp_str .= "<div>";
	$tmp_str .= "<p class='optionbox'>";

	$tmp_str .= "<select name='" . $objname . "' id='" . $objname . "'>";

	if ($str <> "") {
		$tmp_str .= "<option value='" . $val . "'>" . $str . "</option>";
	}

	for ($i = 0; $i < sizeof($arr_cate01); $i++) {

		$RS_AC_CD		= Trim($arr_cate01[$i]['AC_CD']);
		$RS_AC_NAME	= Trim($arr_cate01[$i]['AC_NAME']);

		if ($checkVal == $RS_AC_CD) {
			$tmp_str .= "<option value='" . $RS_AC_CD . "' selected>" . $RS_AC_NAME . "</option>";
		} else {
			$tmp_str .= "<option value='" . $RS_AC_CD . "'>" . $RS_AC_NAME . "</option>";
		}
	}

	$tmp_str .= "</select> ";

	$tmp_str .= "</p>";
	$tmp_str .= "</div>";
	$tmp_str .= "</li>";
	$tmp_str .= "<li class='half'>";
	$tmp_str .= "<label>내용</label>";
	$tmp_str .= "<div>";
	$tmp_str .= "<p class='optionbox'>";

	$tmp_str .= "<select name='" . $next_objname . "' id='" . $next_objname . "'>";

	if ($checkVal <> "") {

		$query = "SELECT CONCAT(AC_SEQ01,AC_SEQ02,AC_SEQ03) as SEQ, AC_CD, AC_NAME 
								 FROM TBL_ACCOUNT 
								WHERE left(AC_CD,2) LIKE (SELECT AC_CD FROM TBL_ACCOUNT WHERE AC_CD = '$checkVal' AND length(AC_CD) = 2)
									AND length(AC_CD) = 4
									AND USE_TF ='Y'
									AND DEL_TF ='N'
								ORDER BY SEQ ASC ";

		//echo $query;

		$result = mysqli_query($db, $query);
		$arr_cate02 = array();

		if ($result <> "") {
			for ($i = 0; $i < mysqli_num_rows($result); $i++) {
				$record[$i] = sql_result_array($db, $result, $i);
			}
		}

		if ($next_str <> "") {
			$tmp_str .= "<option value='" . $next_val . "'>" . $next_str . "</option>";
		}

		for ($i = 0; $i < sizeof($arr_cate02); $i++) {

			$RS_AC_CD		= Trim($arr_cate02[$i]['AC_CD']);
			$RS_AC_NAME	= Trim($arr_cate02[$i]['AC_NAME']);

			if ($next_checkVal == $RS_AC_CD) {
				$tmp_str .= "<option value='" . $RS_AC_CD . "' selected>" . $RS_AC_NAME . "</option>";
			} else {
				$tmp_str .= "<option value='" . $RS_AC_CD . "'>" . $RS_AC_NAME . "</option>";
			}
		}
	} else {

		if ($next_str <> "") {
			$tmp_str .= "<option value='" . $next_val . "'>" . $next_str . "</option>";
		}
	}

	$tmp_str .= "</select>";
	$tmp_str .= "</p>";
	$tmp_str .= "</div>";
	$tmp_str .= "</li>";

	return $tmp_str;
}


function getAccountName($db, $ac_cd)
{

	$query = "SELECT AC_NAME
							 FROM TBL_ACCOUNT 
							WHERE AC_CD = '$ac_cd'
								AND USE_TF ='Y'
								AND DEL_TF ='N'
							ORDER BY AC_NO DESC LIMIT 1 ";

	//echo $query;

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);
	$record  = $rows[0];
	return $record;
}

function getApplySelectedGroupInfo($db, $seq_no, $manager_no, $objname, $str, $val, $checkVal)
{

	$query = "SELECT B.IDX, B.GROUP_NAME
								FROM TBL_APPLY A, TBL_GROUPS_INFO B
							 WHERE A.IDX_NO = B.IDX
								 AND A.DEL_TF = 'N'
								 AND B.DEL_TF = 'N'
								 AND A.SEQ_NO = '$seq_no'
								 AND A.APP_STATE = '1' ";

	if ($manager_no <> "") {
		$query .= "AND A.MANAGER_NO = '$manager_no' ";
	}

	$query .= "ORDER BY GROUP_NAME ASC ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "<select name='" . $objname . "' id='" . $objname . "' >";

	if ($str <> "") {
		$tmp_str .= "<option value='" . $val . "'>" . $str . "</option>";
	}

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$IDX					= Trim($row[0]);
		$GROUP_NAME		= Trim($row[1]);

		if ($checkVal == $IDX) {
			$tmp_str .= "<option value='" . $IDX . "' selected>" . $GROUP_NAME . "</option>";
		} else {
			$tmp_str .= "<option value='" . $IDX . "'>" . $GROUP_NAME . "</option>";
		}
	}
	$tmp_str .= "</select>";

	return $tmp_str;
}


function getCurrentAuthorProgram($db)
{

	$query = "SELECT SEQ_NO FROM TBL_PROGRAM 
							WHERE TYPE = 'TYPE03' 
								AND DEL_TF = 'N'
								AND USE_TF = 'Y'
							ORDER BY SEQ_NO DESC
							LIMIT 1  ";

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);
	$record  = $rows[0];
	return $record;
}

function getNewApplySelectedGroupInfo($db, $list_type, $manager_no, $objname, $str, $val, $checkVal)
{

	if ($list_type == "TYPE01") {
		$seq_no = getCurrentProgram($db);
		$con_seq_no = $seq_no;
	} else if ($list_type == "TYPE03") {
		$seq_no = getCurrentProgram($db);
		$con_seq_no = getCurrentAuthorProgram($db);
	}

	$query = "SELECT B.IDX, B.GROUP_NAME
								FROM TBL_APPLY A, TBL_GROUPS_INFO B, TBL_APPLY C
							 WHERE A.IDX_NO = B.IDX
								 AND C.IDX_NO = A.IDX_NO
								 AND C.IDX_NO = B.IDX
								 AND A.DEL_TF = 'N'
								 AND B.DEL_TF = 'N'
								 AND C.DEL_TF = 'N'
								 AND A.SEQ_NO = '$seq_no'
								 AND C.SEQ_NO = '$con_seq_no'
								 AND A.APP_STATE = '1'
								 AND C.APP_STATE = '1' ";

	if ($manager_no <> "") {
		$query .= "AND A.MANAGER_NO = '$manager_no' ";
	}

	$query .= "ORDER BY GROUP_NAME ASC ";

	$result = mysqli_query($db, $query);
	$total  = mysqli_affected_rows($db);

	$tmp_str = "<select name='" . $objname . "' id='" . $objname . "' >";

	if ($str <> "") {
		$tmp_str .= "<option value='" . $val . "'>" . $str . "</option>";
	}

	for ($i = 0; $i < $total; $i++) {
		mysqli_data_seek($result, $i);
		$row     = mysqli_fetch_array($result);

		$IDX					= Trim($row[0]);
		$GROUP_NAME		= Trim($row[1]);

		if ($checkVal == $IDX) {
			$tmp_str .= "<option value='" . $IDX . "' selected>" . $GROUP_NAME . "</option>";
		} else {
			$tmp_str .= "<option value='" . $IDX . "'>" . $GROUP_NAME . "</option>";
		}
	}
	$tmp_str .= "</select>";

	return $tmp_str;
}


function mainlistGate($db)
{

	$REG_NOW = date("YmdHi");

	$query = "SELECT G_NO, G_TYPE, G_TITLE, G_URL, G_TARGET, S_DATE, S_HOUR, S_MIN, E_DATE, E_HOUR, E_MIN, DATE_USE_TF, DISP_SEQ, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
							FROM TBL_GATE WHERE USE_TF ='Y' AND DEL_TF = 'N'";

	$query .= " AND CONCAT(REPLACE(S_DATE,'-',''),S_HOUR,S_MIN) <= '$REG_NOW' ";
	$query .= " AND CONCAT(REPLACE(E_DATE,'-',''),E_HOUR,E_MIN) > '$REG_NOW' ";

	$query .= " ORDER BY USE_TF DESC, DISP_SEQ asc, G_NO DESC";


	$result = mysqli_query($db, $query);
	$record = array();

	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}

	return $record;
}

function mainlistService($db)
{

	$query = "SELECT S_NO, S_TYPE, S_TITLE, S_COLOR, S_DATE, S_HOUR, S_MIN, E_DATE, E_HOUR, E_MIN, DATE_USE_TF, DISP_SEQ, S_URL, S_TARGET,
										 USE_TF, DEL_TF, REG_DATE
							FROM TBL_SERVICES WHERE USE_TF ='Y' AND DEL_TF = 'N'";

	$query .= " ORDER BY USE_TF DESC, DISP_SEQ asc, S_NO DESC";

	$result = mysqli_query($db, $query);
	$record = array();

	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}

	return $record;
}



function makeBoardCodeSelectBox($db, $objName, $str, $val, $strCate, $style, $checkVal)
{

	$arr_strCate = explode(";", $strCate);

	$tmp_str = "<select name='" . $objName . "' " . $style . " >";

	if ($str <> "") {
		$tmp_str .= "<option value='" . $val . "'>" . $str . "</option>";
	}

	for ($i = 0; $i < sizeof($arr_strCate); $i++) {

		$tmp_cate = str_replace("^", " & ", $arr_strCate[$i]);

		if ($checkVal == $arr_strCate[$i]) {
			$tmp_str .= "<option value='" . $arr_strCate[$i] . "' selected>" . getDcodeName($db, "MOJIB_TYPE", $tmp_cate) . "</option>";
		} else {
			$tmp_str .= "<option value='" . $arr_strCate[$i] . "'>" . getDcodeName($db, "MOJIB_TYPE", $tmp_cate) . "</option>";
		}
	}

	$tmp_str .= "</select>";
	return $tmp_str;
}

function makeProgramBoardCodeSelectBox($db, $objName, $str, $val, $strCate, $style, $checkVal)
{

	$arr_strCate = explode(";", $strCate);

	$tmp_str = "<select name='" . $objName . "' " . $style . " >";

	if ($str <> "") {
		$tmp_str .= "<option value='" . $val . "'>" . $str . "</option>";
	}

	for ($i = 0; $i < sizeof($arr_strCate); $i++) {

		$tmp_cate = str_replace("^", " & ", $arr_strCate[$i]);

		if ($checkVal == $arr_strCate[$i]) {
			$tmp_str .= "<option value='" . $arr_strCate[$i] . "' selected>" . getDcodeName($db, "HIGH_PROGRAM", $tmp_cate) . "</option>";
		} else {
			$tmp_str .= "<option value='" . $arr_strCate[$i] . "'>" . getDcodeName($db, "HIGH_PROGRAM", $tmp_cate) . "</option>";
		}
	}

	$tmp_str .= "</select>";
	return $tmp_str;
}


function makeBoardCodeSelectBoxOnChange($db, $objName, $str, $val, $strCate, $style, $checkVal)
{

	$arr_strCate = explode(";", $strCate);

	$tmp_str = "<select name='" . $objName . "' " . $style . " onChange='js_" . $objName . "();'>";

	if ($str <> "") {
		$tmp_str .= "<option value='" . $val . "'>" . $str . "</option>";
	}

	for ($i = 0; $i < sizeof($arr_strCate); $i++) {

		$tmp_cate = str_replace("^", " & ", $arr_strCate[$i]);

		if ($checkVal == $arr_strCate[$i]) {
			$tmp_str .= "<option value='" . $arr_strCate[$i] . "' selected>" . getDcodeName($db, "MOJIB_TYPE", $tmp_cate) . "</option>";
		} else {
			$tmp_str .= "<option value='" . $arr_strCate[$i] . "'>" . getDcodeName($db, "MOJIB_TYPE", $tmp_cate) . "</option>";
		}
	}

	$tmp_str .= "</select>";
	return $tmp_str;
}

function getListSchoolSearch($db, $school_name)
{

	$query = "SELECT *
							FROM TBL_HIGHSCHOOL WHERE H_SC_NAME LIKE '%" . $school_name . "%'";

	$query .= " ORDER BY H_AREA_NM ASC, H_SC_NAME ASC";

	$result = mysqli_query($db, $query);
	$record = array();

	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}

	return $record;
}

function listSubBoard($db, $b_code, $cate_01, $top_cnt, $nomal_cnt, $total_cnt)
{

	$query = "SELECT AA.B_CODE, AA.B_NO, AA.CATE_01, AA.WRITER_NM, AA.TITLE, AA.REPLY_STATE, AA.SECRET_TF, AA.TOP_TF, AA.REG_DATE FROM (
								(SELECT B_CODE, B_NO, CATE_01, WRITER_NM, TITLE, REPLY_STATE, SECRET_TF, TOP_TF, REG_DATE 
									 FROM TBL_BOARD 
									WHERE B_CODE = '$b_code'";

	if ($cate_01 <> "") {
		$query .= " AND CATE_01 = '" . $cate_01 . "' ";
	}

	$query .= "		AND TOP_TF = 'Y' AND USE_TF = 'Y' AND DEL_TF = 'N' ORDER BY REG_DATE desc LIMIT $top_cnt)
							UNION
								(SELECT B_CODE, B_NO, CATE_01, WRITER_NM, TITLE, REPLY_STATE, SECRET_TF, TOP_TF, REG_DATE 
									 FROM TBL_BOARD 
									WHERE B_CODE = '$b_code'";

	if ($cate_01 <> "") {
		$query .= " AND CATE_01 = '" . $cate_01 . "' ";
	}

	$query .= "		AND TOP_TF = 'N' AND USE_TF = 'Y' AND DEL_TF = 'N' ORDER BY REG_DATE desc LIMIT $nomal_cnt)
							) AA
							ORDER BY AA.TOP_TF DESC, AA.REG_DATE DESC LIMIT " . $total_cnt;

	//echo $query."<br>";

	$result = mysqli_query($db, $query);
	$record = array();

	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}
	return $record;
}


function getListDcode($db, $pcode, $dcode_ext)
{

	$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

	if ($pcode <> "") {
		$query .= " AND PCODE = '" . $pcode . "' ";
	}

	if ($pcode <> "") {
		$query .= " AND DCODE_EXT = '" . $dcode_ext . "' ";
	}

	$query .= " ORDER BY DCODE_SEQ_NO ";


	#echo $query."<br>";

	$result = mysqli_query($db, $query);
	$record = array();


	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}
	return $record;
}



function getListAjouNotice($db, $m_type)
{

	$now_date_ymdhis = date("Y-m-d H:i:s", strtotime("0 day"));

	$query = "SELECT * FROM (
							SELECT B_CODE, B_NO, B_PO, B_RE, CATE_01, CATE_02, CATE_03, CATE_04, LINK01, LINK02,
										 WRITER_ID, WRITER_NM, WRITER_NICK, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT, REF_IP, RECOMM, RECOMMNO, FILE_CNT, COMMENT_CNT, CONTENTS,
										 THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, MAIN_TF, TOP_TF, REF_TF, SECRET_TF, THUMB_IMG_CHK, bo_table,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF, START_DATE, END_DATE, DATE_USE_TF,
										 DISP_SEQ, SUB_DISP_SEQ, 
										 (SELECT COUNT(FILE_NO) 
												FROM TBL_BOARD_FILE
											 WHERE TBL_BOARD.B_CODE = TBL_BOARD_FILE.B_CODE 
												 AND TBL_BOARD.B_NO = TBL_BOARD_FILE.B_NO
												 AND TBL_BOARD_FILE.DEL_TF = 'N' ) AS F_CNT
								FROM TBL_BOARD 
							 WHERE DEL_TF = 'N' AND USE_TF = 'Y' 
								 AND ('" . $now_date_ymdhis . "' BETWEEN START_DATE and END_DATE OR DATE_USE_TF = 'N') ";

	if ($m_type == "ALL") {
		$query .= " AND B_CODE IN ('B_1_1','B_1_7') ";
	} else if ($m_type == "SCHOOL") {
		$query .= " AND B_CODE = 'B_1_7' ";
	} else {
		$query .= " AND B_CODE = 'B_1_1' ";
	}


	if ($m_type <> "") {
		if (($m_type <> "ALL") && ($m_type <> "SCHOOL")) {
			$query .= " AND CATE_01 = '" . $m_type . "' ";
		}
	}

	$query .= " ORDER BY MAIN_TF DESC, REG_DATE DESC limit 12 ) AA";

	if ($m_type == "ALL") {
		$query .= " ORDER BY AA.DISP_SEQ ASC";
	} else {
		$query .= " ORDER BY AA.SUB_DISP_SEQ ASC";
	}

	//echo $query;

	$result = mysqli_query($db, $query);
	$record = array();


	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}
	return $record;
}


function getListNewAjouNotice($db)
{

	$now_date_ymdhis = date("Y-m-d H:i:s", strtotime("0 day"));

	$query = "SELECT * FROM (
							SELECT B_CODE, B_NO, B_PO, B_RE, CATE_01, CATE_02, CATE_03, CATE_04, LINK01, LINK02,
										 WRITER_ID, WRITER_NM, WRITER_NICK, WRITER_PW, EMAIL, HOMEPAGE, TITLE, HIT_CNT, REF_HIT_CNT, REF_IP, RECOMM, RECOMMNO, FILE_CNT, COMMENT_CNT, CONTENTS,
										 THUMB_IMG, FILE_NM, FILE_RNM, KEYWORD, REPLY, REPLY_ADM, REPLY_DATE, REPLY_STATE, COMMENT_TF, MAIN_TF, TOP_TF, REF_TF, SECRET_TF, THUMB_IMG_CHK, bo_table,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, datediff(NOW(), REG_DATE) AS BB_DATEDIFF, START_DATE, END_DATE, DATE_USE_TF,
										 DISP_SEQ, SUB_DISP_SEQ, 
										 (SELECT COUNT(FILE_NO) 
												FROM TBL_BOARD_FILE
											 WHERE TBL_BOARD.B_CODE = TBL_BOARD_FILE.B_CODE 
												 AND TBL_BOARD.B_NO = TBL_BOARD_FILE.B_NO
												 AND TBL_BOARD_FILE.DEL_TF = 'N' ) AS F_CNT
								FROM TBL_BOARD 
							 WHERE DEL_TF = 'N' AND USE_TF = 'Y' AND MAIN_TF = 'Y' 
								 AND ('" . $now_date_ymdhis . "' BETWEEN START_DATE and END_DATE OR DATE_USE_TF = 'N') ";

	$query .= " AND B_CODE IN ('B_1_1','B_1_7') ";

	$query .= " ORDER BY MAIN_TF DESC, REG_DATE DESC) AA";

	$query .= " ORDER BY AA.DISP_SEQ ASC";

	$result = mysqli_query($db, $query);
	$record = array();


	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}
	return $record;
}


function getListIntro($db)
{

	$now_date_ymdhis = date("Y-m-d H:i:s", strtotime("0 day"));

	$query = "SELECT INTRO_NO, INTRO_TITLE, INTRO_DISP_SEQ,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, START_DATE, END_DATE, DATE_USE_TF
							FROM TBL_INTRO 
						 WHERE USE_TF = 'Y' 
							 AND DEL_TF = 'N'
							 AND ('" . $now_date_ymdhis . "' BETWEEN START_DATE and END_DATE OR DATE_USE_TF = 'N') ";

	$query .= " ORDER BY INTRO_DISP_SEQ ASC, INTRO_NO DESC ";

	//echo $query;
	//exit;

	$result = mysqli_query($db, $query);
	$record = array();

	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}
	return $record;
}


function getMainlistQuickCnt($db, $mtype)
{

	$query = "SELECT COUNT(*)
							FROM TBL_QUICK WHERE DEL_TF = 'N' AND USE_TF = 'Y' ";

	if ($mtype <> "") {
		$query .= " AND Q_MTYPE = '" . $mtype . "' ";
	}

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);
	$record  = $rows[0];
	return $record;
}

 function generateReservationCharNo($db, $arr_data) {
    $today = date("ymd"); 
    $room_no = isset($arr_data['ROOM_NO']) ? convertRoomNo($arr_data['ROOM_NO']) : ''; // ROOM_NO 변환 -> 

    $query = "SELECT IFNULL(MAX(CAST(SUBSTRING(RV_NO, LENGTH('$today$room_no') + 1) AS UNSIGNED)), 0) + 1 AS MAX_NO
              FROM TBL_RESERVATION
              WHERE RV_NO LIKE '$today$room_no%'";

    $result = mysqli_query($db, $query);
    $rows = mysqli_fetch_array($result);

    $increment_no = str_pad($rows['MAX_NO'], 5, "0", STR_PAD_LEFT); // 5자리로 패딩
    return $today . $room_no . $increment_no; // 새 예약번호 생성
} 

function generateReservationNo($db, $arr_data) {
	$today = date("ymd"); // 현재 날짜를 YYMMDD 형식으로 가져옴
	$room_no = isset($arr_data['ROOM_NO']) ? convertRoomNo($arr_data['ROOM_NO']) : ''; // ROOM_NO 변환

	// RV_NO의 현재 최대값 조회
	$query = "SELECT IFNULL(MAX(RV_NO), 0) + 1 AS MAX_NO FROM TBL_RESERVATION";
	$result = mysqli_query($db, $query);
	$rows = mysqli_fetch_array($result);

	// RV_NO를 4자리로 패딩
	$increment_no = str_pad($rows['MAX_NO'], 4, "0", STR_PAD_LEFT);

	// 최종 예약번호 생성: 날짜(6자리) + ROOM_NO(3자리) + RV_NO(4자리)
	return $today . $room_no . $increment_no;
}


function convertRoomNo($room_no) {
  if (is_numeric($room_no)) {
    return "C" . str_pad($room_no, 3, "0", STR_PAD_LEFT);
  }
  // 이미 변환된 값이라면 그대로 반환
  return $room_no;
}


function highlightKeyword($text, $keyword) { // 검색 결과에서 검색 키워드와 동일한 문자 하이라이팅
    if (!empty($keyword)) {
        $highlighted = preg_replace("/" . preg_quote($keyword, '/') . "/i", "<em>$0</em>", $text);
        return $highlighted;
    }
    return $text;
}

function Auth_Token() {
    $url = "https://api.bizppurio.com/v1/token";
    $username = "wfiwfi";
    $password = "wfiqwer1!";

    // Basic Auth 값 생성
    $auth_header = "Basic " . base64_encode("$username:$password");

    // cURL 요청
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: $auth_header",
        "Content-Type: application/json; charset=utf-8"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    
    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200 || !$result) {
        return false; // 토큰 발급 실패
    }

    $response = json_decode($result, true);
    return $response['accesstoken'] ?? false;
}

function biz_send_sms($db, $phone, $subject, $msg, $task) {
	$token = Auth_Token();
	if ($token == NULL) {
			return "토큰 발급 실패";
	}
	
	$url = "https://api.bizppurio.com/v3/message";
	
	$sms_data = [
			"account"   => "wfiwfi",
			"refkey"    => "test",
			"type"      => "sms",
			"from"      => "0337649020",
			"to"        => $phone,
			"content"   => [
            "sms" => [
                "message" => $msg
            ]
        ]
	];
	
	if (strlen($msg) > 90) {
			$sms_data["title"] = $subject;
	}

	// cURL 요청 설정
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
			"Content-Type: application/json",
			"Authorization: Bearer $token"
	]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sms_data));
	
	$result = curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	if ($http_code !== 200 || !$result) {
			return "❌ 메시지 전송 실패, HTTP 상태 코드: " . $result;
	}

	$response = json_decode($result, true);
	if (isset($response['code']) && $response['code'] == "1000") {
			$result_status = "T";
			$message = "문자를 전송하였습니다.";
	} else {
			$result_status = "F";
			$message = "문자 전송 실패: " . ($response['message'] ?? '알 수 없는 오류');
	}

	// DB 저장
	$query = "INSERT INTO TBL_SMS_LOG (RPHONE, MSG, TASK, SEND_RESULT, ERR, SEND_DATE) 
						VALUES ('$phone', '$msg', '$task', '$result_status', '$message', NOW())";

	if(!mysqli_query($db,$query)) {
		return false;
		echo "<script>alert(\"[1]오류가 발생하였습니다.\"); //history.go(-1);</script>";
		exit;
	}

	return $msg;
}

// Cafe24
function send_sms($db, $rphone, $subject, $msg, $task) {

	$str_msg = $msg;

	$sms_url = "https://sslsms.cafe24.com/sms_sender.php";	// 전송요청 URL
	$sms['user_id'] = base64_encode("wfiwfisms");						//SMS 아이디.
	$sms['secure'] = base64_encode("b0737bbf00b15fe5e968db4e7db92694") ;//인증키
	$sms['msg'] = base64_encode(stripslashes($msg));

	if( $_POST['smsType'] == "L"){
		$sms['subject'] =  base64_encode($subject);
	}

	$sms['rphone'] = base64_encode($rphone);
	$sms['sphone1'] = base64_encode("033");
	$sms['sphone2'] = base64_encode("764");
	$sms['sphone3'] = base64_encode("9020");

	/* 값 없어도 되요.*/
	$sms['rdate'] = base64_encode("");
	$sms['rtime'] = base64_encode("");
	$sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
	$sms['returnurl'] = base64_encode("");
	$sms['testflag'] = base64_encode("");
	$sms['destination'] = strtr(base64_encode(""), '+/=', '-,');
	$returnurl = "";
	$sms['repeatFlag'] = base64_encode("");
	$sms['repeatNum'] = base64_encode("");
	$sms['repeatTime'] = base64_encode("");
	$sms['smsType'] = base64_encode(""); // LMS일경우 L
	$nointeractive = ""; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략

	$host_info = explode("/", $sms_url);
	$host = $host_info[2];
	$path = $host_info[3]."/".$host_info[4];

	srand((double)microtime()*1000000);
	$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
	//print_r($sms);

	// 헤더 생성
	$header = "POST /".$path ." HTTP/1.0\r\n";
	$header .= "Host: ".$host."\r\n";
	$header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

	// 본문 생성
	foreach($sms AS $index => $value){
		$data .="--$boundary\r\n";
		$data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
		$data .= "\r\n".$value."\r\n";
		$data .="--$boundary\r\n";
	}
	$header .= "Content-length: " . strlen($data) . "\r\n\r\n";

	$fp = fsockopen($host, 80);

	if ($fp) {
		fputs($fp, $header.$data);
		$rsp = '';
		while(!feof($fp)) {
			$rsp .= fgets($fp,8192);
		}
		fclose($fp);
		$msg = explode("\r\n\r\n",trim($rsp));
		$rMsg = explode(",", $msg[1]);
		$Result= $rMsg[0]; //발송결과
		$Count= $rMsg[1]; //잔여건수

		//발송결과 알림
		if($Result=="success") {

			$result		= "T";
			$message	= "문자를 전송하였습니다.";

			//$alert = "성공";
			//$alert .= " 잔여건수는 ".$Count."건 입니다.";

		} else if ($Result=="reserved") {

			$result		= "T";
			$message	= "문자를 전송하였습니다.";

			//$alert = "성공적으로 예약되었습니다.";
			//$alert .= " 잔여건수는 ".$Count."건 입니다.";
		} else if($Result=="3205") {

			$result		= "F";
			$message	= "잘못된 번호형식입니다.";

		} else if($Result=="0044") {

			$result		= "F";
			$message	= "스팸문자는발송되지 않습니다.";

			$alert = "";
		} else {
			$result		= "F";
			$message	= "[Error]".$Result;
			//$alert = "[Error]".$Result;
		}
	} else {
		$result		= "F";
		$message	= "Connection Failed";
		//$alert = "Connection Failed";
	}

	$query="INSERT INTO TBL_SMS_LOG (RPHONE, MSG, TASK, SEND_RESULT, ERR, SEND_DATE) 
								 VALUES ('$rphone', '$str_msg', '$task', '$result','$message', now()) ";

	if(!mysqli_query($db,$query)) {
		return false;
		echo "<script>alert(\"[1]오류가 발생하였습니다.\"); //history.go(-1);</script>";
		exit;
	}

	return $message;

}