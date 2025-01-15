<?

	function listRecommend($db, $recommend_type, $category, $info_01, $del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);
		
		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, BOOK_NO, RECOMMEND_TYPE, CATEGORY, BOOK_NM, BOOK_IMG, 
										 AUTHORS, TRANSLATORS, PUBLISHER, PUBLISH_DATE, ISBN, PAGE_CNT, BOOK_CONTENTS, BOOK_COMMENT, 
										 INFO_01, INFO_02, INFO_03, HIT_CNT, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE 
								FROM TBL_RECOMMEND_BOOK
							 WHERE 1 = 1 ";

		if ($recommend_type <> "") {
			$query .= " AND RECOMMEND_TYPE = '$recommend_type' ";
		}

		if ($category <> "") {
			$query .= " AND CATEGORY = '$category' ";
		}

		if ($info_01 <> "") {
			$query .= " AND INFO_01 = '$info_01' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (BOOK_NM like '%".$search_str."%' OR AUTHORS like '%".$search_str."%' OR TRANSLATORS like '%".$search_str."%' OR PUBLISHER like '%".$search_str."%' OR ISBN like '%".$search_str."%' OR BOOK_CONTENTS like '%".$search_str."%')";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		if ($order_field == "") {
			$order_field = " BOOK_NO ";
		}

		if ($order_str == "") {
			$order_str = " DESC ";
		}

		$query .= " ORDER BY ".$order_field." ".$order_str." limit ".$offset.", ".$nRowCount;
		
		//echo $query;

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function totalCntRecommend($db, $recommend_type, $category, $info_01, $del_tf, $search_field, $search_str){

		$query ="SELECT COUNT(BOOK_NO) CNT 
							 FROM TBL_RECOMMEND_BOOK
							WHERE 1 = 1 ";

		if ($recommend_type <> "") {
			$query .= " AND RECOMMEND_TYPE = '$recommend_type' ";
		}

		if ($category <> "") {
			$query .= " AND CATEGORY = '$category' ";
		}

		if ($info_01 <> "") {
			$query .= " AND INFO_01 = '$info_01' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (BOOK_NM like '%".$search_str."%' OR AUTHORS like '%".$search_str."%' OR TRANSLATORS like '%".$search_str."%' OR PUBLISHER like '%".$search_str."%' OR ISBN like '%".$search_str."%' OR BOOK_CONTENTS like '%".$search_str."%')";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		//echo $query;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function insertRecommend($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		$first = "Y";
		foreach ($arr_data as $key => $value) {
			if ($first == "Y") {
				$set_field .= $key; 
				$set_value .= "'".$value."'"; 
				$first = "N";
			} else {
				$set_field .= ",".$key; 
				$set_value .= ",'".$value."'"; 
			}
		}

		$query = "INSERT INTO TBL_RECOMMEND_BOOK (".$set_field.") 
					values (".$set_value."); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectRecommend($db, $book_no) {

		$query = "SELECT * FROM TBL_RECOMMEND_BOOK WHERE BOOK_NO = '$book_no' AND DEL_TF = 'N' ";
		
		$result = mysqli_query($db, $query);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateRecommend($db, $arr_data, $book_no) {

		foreach ($arr_data as $key => $value) {
			$set_query_str .= $key." = '".$value."',"; 
		}

		$query = "UPDATE TBL_RECOMMEND_BOOK SET ".$set_query_str." ";
		$query .= "BOOK_NO = '$book_no' WHERE BOOK_NO = '$book_no' ";

		//echo $query."<br>";

		//exit;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteRecommend($db, $adm_no, $book_no) {

		$query = "UPDATE TBL_RECOMMEND_BOOK SET DEL_TF = 'Y', DEL_ADM = '$adm_no', DEL_DATE = now() WHERE BOOK_NO = '$book_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function selectPostRecommend($db, $book_no, $recommend_type, $category, $info_01, $del_tf, $search_field, $search_str) {

		$query = "SELECT BOOK_NO, RECOMMEND_TYPE, CATEGORY, BOOK_NM, BOOK_IMG, 
										 AUTHORS, TRANSLATORS, PUBLISHER, PUBLISH_DATE, ISBN, PAGE_CNT, BOOK_CONTENTS, BOOK_COMMENT, 
										 INFO_01, INFO_02, INFO_03, HIT_CNT, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE 
								FROM TBL_RECOMMEND_BOOK
							 WHERE BOOK_NO < '".$book_no."' ";

		if ($recommend_type <> "") {
			$query .= " AND RECOMMEND_TYPE = '$recommend_type' ";
		}

		if ($category <> "") {
			$query .= " AND CATEGORY = '$category' ";
		}

		if ($info_01 <> "") {
			$query .= " AND INFO_01 = '$info_01' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (BOOK_NM like '%".$search_str."%' OR AUTHORS like '%".$search_str."%' OR TRANSLATORS like '%".$search_str."%' OR PUBLISHER like '%".$search_str."%' OR ISBN like '%".$search_str."%' OR BOOK_CONTENTS like '%".$search_str."%')";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		if ($order_field == "") {
			$order_field = " BOOK_NO ";
		}

		if ($order_str == "") {
			$order_str = " DESC ";
		}

		$query .= " ORDER BY BOOK_NO DESC limit 1";
		
		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function selectPreRecommend($db, $book_no, $recommend_type, $category, $info_01, $del_tf, $search_field, $search_str) {

		$query = "SELECT BOOK_NO, RECOMMEND_TYPE, CATEGORY, BOOK_NM, BOOK_IMG, 
										 AUTHORS, TRANSLATORS, PUBLISHER, PUBLISH_DATE, ISBN, PAGE_CNT, BOOK_CONTENTS, BOOK_COMMENT, 
										 INFO_01, INFO_02, INFO_03, HIT_CNT, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE 
								FROM TBL_RECOMMEND_BOOK
							 WHERE BOOK_NO > '".$book_no."' ";

		if ($recommend_type <> "") {
			$query .= " AND RECOMMEND_TYPE = '$recommend_type' ";
		}

		if ($category <> "") {
			$query .= " AND CATEGORY = '$category' ";
		}

		if ($info_01 <> "") {
			$query .= " AND INFO_01 = '$info_01' ";
		}

		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '$del_tf' ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (BOOK_NM like '%".$search_str."%' OR AUTHORS like '%".$search_str."%' OR TRANSLATORS like '%".$search_str."%' OR PUBLISHER like '%".$search_str."%' OR ISBN like '%".$search_str."%' OR BOOK_CONTENTS like '%".$search_str."%')";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		if ($order_field == "") {
			$order_field = " BOOK_NO ";
		}

		if ($order_str == "") {
			$order_str = " DESC ";
		}

		$query .= " ORDER BY BOOK_NO ASC limit 1";
		
		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function listTmpRecommend($db, $tmp_file) {

		$query = "SELECT *
								FROM TBL_TMP_RECOMMEND_BOOK 
							 WHERE TEMP_NO = '".$tmp_file."' 
							 ORDER BY BOOK_NO ASC ";

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function insertTmpRecommend($db, $arr_data) {

		// 게시물 등록
		$set_field = "";
		$set_value = "";
		
		$first = "Y";
		foreach ($arr_data as $key => $value) {
			if ($first == "Y") {
				$set_field .= $key; 
				$set_value .= "'".$value."'"; 
				$first = "N";
			} else {
				$set_field .= ",".$key; 
				$set_value .= ",'".$value."'"; 
			}
		}

		$query = "INSERT INTO TBL_TMP_RECOMMEND_BOOK (".$set_field.") 
					values (".$set_value."); ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteTempBook($db, $temp_no, $tmp_book_no) {

		$query = "DELETE FROM TBL_TMP_RECOMMEND_BOOK WHERE TEMP_NO = '$temp_no' AND BOOK_NO = '$tmp_book_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function insertTempToRealBook($db, $temp_no, $str_book_no, $adm_no) {
		
		$query = "INSERT INTO TBL_RECOMMEND_BOOK (RECOMMEND_TYPE, CATEGORY, BOOK_NM, BOOK_IMG, AUTHORS, 
										 TRANSLATORS, PUBLISHER, PUBLISH_DATE, ISBN, PAGE_CNT, BOOK_CONTENTS, BOOK_COMMENT, INFO_01, INFO_02, INFO_03, 
										 HIT_CNT, DEL_TF, REG_ADM, REG_DATE, UP_DATE)
							SELECT RECOMMEND_TYPE, CATEGORY, BOOK_NM, BOOK_IMG, AUTHORS, 
										 TRANSLATORS, PUBLISHER, PUBLISH_DATE, ISBN, PAGE_CNT, BOOK_CONTENTS, BOOK_COMMENT, INFO_01, INFO_02, INFO_03, 
										 '0', 'N', '$adm_no', now(), now()
								FROM TBL_TMP_RECOMMEND_BOOK 
							 WHERE BOOK_NO in (".$str_book_no.") AND TEMP_NO = '$temp_no' ORDER BY TBL_TMP_RECOMMEND_BOOK.BOOK_NO DESC ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function deleteTempToRealBook($db, $temp_no, $str_book_no) {
		
		$query = "DELETE FROM TBL_TMP_RECOMMEND_BOOK 
							 WHERE BOOK_NO in (".$str_book_no.") AND TEMP_NO = '$temp_no' ";

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function mainListRecommend($db, $cnt) {

		$query = "SELECT BOOK_NO, RECOMMEND_TYPE, CATEGORY, BOOK_NM, BOOK_IMG, 
										 AUTHORS, TRANSLATORS, PUBLISHER, PUBLISH_DATE, ISBN, PAGE_CNT, BOOK_CONTENTS, BOOK_COMMENT, 
										 INFO_01, INFO_02, INFO_03, HIT_CNT, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE 
								FROM TBL_RECOMMEND_BOOK
							 WHERE DEL_TF = 'N' ";
		
		$query .= " ORDER BY BOOK_NO DESC limit ".$cnt;
		
		//echo $query;

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function updateRecommendHitCnt($db, $book_no) {

		$query = "UPDATE TBL_RECOMMEND_BOOK SET HIT_CNT = HIT_CNT + 1 WHERE BOOK_NO = '$book_no' ";

		//echo $query."<br>";

		//exit;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}
?>