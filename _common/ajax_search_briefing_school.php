<?session_start();?>
<?
	header("Content-type:text/html;charset=UTF-8");
	error_reporting(E_ALL & ~E_NOTICE);

	$search_text		= $_POST['search_text']!=''?$_POST['search_text']:$_GET['search_text'];
	$apply_area			= $_POST['apply_area']!=''?$_POST['apply_area']:$_GET['apply_area'];
	$sch_code				= $_POST['sch_code']!=''?$_POST['sch_code']:$_GET['sch_code'];
	$apply_no				= $_POST['apply_no']!=''?$_POST['apply_no']:$_GET['apply_no'];
	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	require "../_classes/com/db/DBUtil.php";
	$conn = db_connection("w");

	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/util/ImgUtil.php";
	require "../_classes/com/etc/etc.php";

	function searchBriefingSchool($db, $search_text, $apply_area) {

		$query = "SELECT *
							FROM TBL_BRIEFING_HIGH_SCHOOL WHERE S_DISTRICT = '".$apply_area."' AND USE_TF ='Y' AND NEISCODE <> 'Z000000000'  ";

		if ($search_text <> "") {
			$query .= " AND S_NAME LIKE '%".$search_text."%' ";
		}
		
		$query .= " ORDER BY S_NAME ASC";

		$result = mysqli_query($db,$query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function dupCheckBriefingSchool($db, $sch_code, $apply_no) {

		$query = "SELECT COUNT(*) AS CNT
							FROM TBL_BRIEFING WHERE SCH_CODE = '".$sch_code."' AND DEL_TF ='N' ";

		if ($apply_no <> "") {
			$query .= " AND APPLY_NO <> '".$apply_no."' ";
		}
		
		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}



	if ($mode == "search") {

		$arr_rs = searchBriefingSchool($conn, $search_text, $apply_area);
		$list_str = "";
	
		if (sizeof($arr_rs) > 0) {
			for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
				$NEISCODE			= trim($arr_rs[$j]["NEISCODE"]);
				$S_NAME				= trim($arr_rs[$j]["S_NAME"]);
				$S_CITIES			= trim($arr_rs[$j]["S_CITIES"]);
				$S_SIGUNGU		= trim($arr_rs[$j]["S_SIGUNGU"]);
				$S_DISTRICT		= trim($arr_rs[$j]["S_DISTRICT"]);
				$HIREGION			= $S_CITIES." ".$S_SIGUNGU;

				$list_str = $list_str . "<li onClick=\"search_briefing_school_return('".$NEISCODE."','".$S_NAME."','".$HIREGION."');\" style=\"cursor:pointer;\">";
				$list_str = $list_str . "<span>".$NEISCODE."</span>";
				$list_str = $list_str . "<span style='width:70%'>".$S_NAME."</span>";
				$list_str = $list_str . "<span>".$S_DISTRICT."</span>";
				$list_str = $list_str . "</li>";
			}
		}

		$result = "T";
		$arr_result = array("result"=>$result, "total"=>sizeof($arr_rs), "list"=>$list_str);
		echo json_encode($arr_result);

	}

	if ($mode == "dup_chk") {

		$apply_school_cnt = dupCheckBriefingSchool($conn, $sch_code, $apply_no);

		$result = "00";
		$arr_result = array("result"=>$result, "apply_school_cnt"=>$apply_school_cnt);
		echo json_encode($arr_result);

	}


	db_close($conn);
?>