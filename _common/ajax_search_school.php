<?session_start();?>
<?
	header("Content-type:text/html;charset=UTF-8");
	error_reporting(E_ALL & ~E_NOTICE);

	$search_text		= $_POST['search_text']!=''?$_POST['search_text']:$_GET['search_text'];
	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	require "../_classes/com/db/DBUtil.php";
	$conn = db_connection("w");

	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/util/ImgUtil.php";
	require "../_classes/com/etc/etc.php";

	function searchSchool($db, $search_text) {

		$query = "SELECT *
							FROM TBL_HIGH_SCHOOL WHERE 1 = 1 AND NEISCODE <> 'Z000000000'  ";

		if ($search_text <> "") {
			$query .= " AND NEISNAME LIKE '%".$search_text."%' ";
		}
		
		$query .= " ORDER BY NEISNAME ASC";

		$result = mysqli_query($db,$query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	$arr_rs = searchSchool($conn, $search_text);
	$list_str = "";
	
	if (sizeof($arr_rs) > 0) {
		for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
			$NEISCODE		= trim($arr_rs[$j]["NEISCODE"]);
			$NEISNAME		= trim($arr_rs[$j]["NEISNAME"]);
			$HIREGION		= trim($arr_rs[$j]["HIREGION"]);

			$list_str = $list_str . "<li onClick=\"search_school_return('".$NEISCODE."','".$NEISNAME."','".$HIREGION."');\" style=\"display:flex;gap:1rem;height:7rem;cursor:pointer;width:97%;align-items: center;border-bottom: 0.1rem solid #e8e8e8;\">";
			$list_str = $list_str . "<span style='text-align:left;font-size:1.6rem;'>".$NEISCODE."</span>";
			$list_str = $list_str . "<span style='text-align:left;width:70%;font-size:1.6rem;'>".$NEISNAME."</span>";
			$list_str = $list_str . "<span style='text-align:right;font-size:1.6rem;'>".$HIREGION."</span>";
			$list_str = $list_str . "</li>";

			/*
			$list_str = $list_str . "<tr>";
			$list_str = $list_str . "<td><a href=\"javascript:search_school_return('".$NEISCODE."','".$NEISNAME."','".$HIREGION."');\" style=\"cursor:pointer;\">".$HIREGION."</a></td>";
			$list_str = $list_str . "<td><a href=\"javascript:search_school_return('".$NEISCODE."','".$NEISNAME."','".$HIREGION."');\" style=\"cursor:pointer;\">".$NEISNAME."</a></td>";
			$list_str = $list_str . "</tr>";
			*/
		}
	}

	$result = "T";
	$arr_result = array("result"=>$result, "total"=>sizeof($arr_rs), "list"=>$list_str);
	echo json_encode($arr_result);

	db_close($conn);
?>