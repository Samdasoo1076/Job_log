<?php

	//error_reporting( E_ALL );
	error_reporting(E_ERROR | E_WARNING | E_PARSE);

	class mysql2i {

		public static function mysql_field_name($result,$field_offset){ 
			$fieldInfo = mysqli_fetch_field_direct($result,$field_offset); 
			return $fieldInfo->name;
		}
	}

	function db_connection($usr_type) {
		
		if ($usr_type == "w") {
			$servername = "192.168.0.24:3306";
			$username = "wfi";
			$password = "wfiasdf1!";
			$dbname = "wfi";
		} else {
			$servername = "192.168.0.24:3306";
			$username = "wfi";
			$password = "wfiasdf1!";
			$dbname = "wfi";
		}
		
		$link = new mysqli($servername, $username, $password, $dbname);

		if ($link->connect_error) {
			die("Connection failed: " . $link->connect_error);
		}

		$db_selected = mysqli_select_db($link, $dbname) or die('DB 선택 실패');

		mysqli_set_charset($link, "utf8mb4");

		return $link;
	}

	function db_close($db) {
		mysqli_close($db);
	}

	function mysql_field_name($result,$field_offset){ 
		return mysql2i::mysql_field_name($result,$field_offset); 
	}

	function mysqli_result($res,$row=0,$col=0) {
		$nums=mysqli_num_rows($res);
		if($nums && $row<=($nums-1) && $row>=0) {
			mysqli_data_seek($res,$row);
			$resrow=(is_numeric($col))?mysqli_fetch_row($res):mysqli_fetch_assoc($res);
			if(isset($resrow[$col])) {
				return $resrow[$col];
			}
		}
		return false;
	}

	function sql_result_array($db, $handle, $row) {

		$count = mysqli_field_count($db);

		for($i=0;$i<$count;$i++){

			$fieldName = mysql_field_name($handle,$i);
			$ret[$fieldName] = mysqli_result($handle,$row,$i);
		}
		return $ret;
	}


	//$conn = db_connection("w");
	//db_close($conn)
?>
