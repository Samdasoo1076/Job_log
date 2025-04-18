<?
	function listDayPeriod($db, $start_date, $end_date, $divicetype) {


		if ($divicetype <> "") {

			$query = "SELECT A.YMD, COUNT(DISTINCT A.SID) AS UV, COUNT(*) AS PV,
											 ROUND( COUNT(*)  / COUNT(DISTINCT A.SID)) AS UVPV
									FROM TBL_LOG A WHERE 1 = 1 
									 AND A.YMD >= '".$start_date."'
									 AND A.YMD <= '".$end_date."'
									 AND A.DIVICETYPE = '".$divicetype."' 
								 GROUP BY A.YMD ORDER BY A.YMD DESC ";

		} else { 

			$query = "SELECT YMD, SUM(UV) AS UV, SUM(PV) AS PV, SUM(UVPV) AS UVPV
									FROM (
								SELECT YMD, UV, PV, UVPV FROM (
								SELECT A.YMD, 
											 COUNT(DISTINCT A.SID) AS UV, 
											 COUNT(*) AS PV, ROUND( COUNT(*) / COUNT(DISTINCT A.SID)) AS UVPV
									FROM TBL_LOG A WHERE 1 = 1 
									 AND A.YMD >= '".$start_date."' 
									 AND A.YMD <= '".$end_date."'
									 AND A.DIVICETYPE IN ('P') 
								 GROUP BY A.YMD ORDER BY A.YMD DESC ) A

								UNION

								SELECT YMD, UV, PV, UVPV FROM (
								SELECT A.YMD, 
											 COUNT(DISTINCT A.SID) AS UV, 
											 COUNT(*) AS PV, ROUND( COUNT(*) / COUNT(DISTINCT A.SID)) AS UVPV
									FROM TBL_LOG A WHERE 1 = 1 
									 AND YMD >= '".$start_date."' 
									 AND YMD <= '".$end_date."'
									 AND DIVICETYPE IN ('M') 
								 GROUP BY A.YMD ORDER BY A.YMD DESC ) B
								 ) C  
								 GROUP BY C.YMD ORDER BY C.YMD DESC ";
		
		}
		
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


	function totalCntDayPeriod($db, $start_date, $end_date, $divicetype){

		$query ="SELECT COUNT(DISTINCT YMD) AS CNT FROM TBL_LOG WHERE 1 = 1 ";
		
		if ($start_date <> "") {
			$query .= " AND YMD >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND YMD <= '".$end_date."' ";
		}

		if ($divicetype <> "") {
			$query .= " AND DIVICETYPE = '".$divicetype."' ";
		}

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function getTodayTimeStatistic($db) {

		$query = "SELECT HH, COUNT(DISTINCT SID) AS UV from TBL_LOG WHERE YMD = '".date("Y-m-d",strtotime("0 day"))."' GROUP BY HH ORDER BY HH ASC ";
		
		//echo $query ;

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function getTodayStatistic($db) {

		$query = "SELECT COUNT(DISTINCT SID) AS UV from TBL_LOG WHERE YMD = '".date("Y-m-d",strtotime("0 day"))."' ";
		
		//echo $query ;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function getYesterdayStatistic($db) {

		$query = "SELECT COUNT(DISTINCT SID) AS UV from TBL_LOG WHERE YMD = '".date("Y-m-d",strtotime("-1 day"))."' ";
		
		//echo $query ;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function getYearStatistic($db) {

		$query = "SELECT COUNT(DISTINCT SID) AS UV from TBL_LOG ";
		
		//echo $query ;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function listDayCounsel($db, $start_date, $end_date, $click_type, $search_field, $search_str) {

		if ($click_type == "C") {

			$query = "SELECT YMD,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELONLINECLICK' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS ONLINE_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELNAVER' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS NAVER_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELPHONECLICK' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS PHONE_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELKAKAOCLICK' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS KAKAO_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELONLINECLICK' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_ONLINE_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELNAVER' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_NAVER_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELPHONECLICK' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_PHONE_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELKAKAOCLICK' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_KAKAO_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELCALL' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_CALL_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'P' AND ISRESERVE = 'N') THEN 1 ELSE 0 END) AS ONLINE_CANCEL_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'M' AND ISRESERVE = 'N') THEN 1 ELSE 0 END) AS M_ONLINE_CANCEL_CNT
									FROM TBL_COUNSEL WHERE 1 = 1 ";
		
		} else {

			$query = "SELECT YMD,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS ONLINE_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELNAVER' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS NAVER_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELPHONE' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS PHONE_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELKAKAO' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS KAKAO_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_ONLINE_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELNAVER' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_NAVER_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELPHONE' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_PHONE_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELKAKAO' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_KAKAO_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELCALL' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_CALL_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'P' AND ISRESERVE = 'N') THEN 1 ELSE 0 END) AS ONLINE_CANCEL_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'M' AND ISRESERVE = 'N') THEN 1 ELSE 0 END) AS M_ONLINE_CANCEL_CNT
									FROM TBL_COUNSEL WHERE 1 = 1 ";
		
		}

		if ($start_date <> "") {
			$query .= " AND YMD >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND YMD <= '".$end_date."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$query .= " GROUP BY YMD ORDER BY YMD DESC ";

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function totalCntDayCounsel($db, $start_date, $end_date, $click_type, $search_field, $search_str){

		$query ="SELECT COUNT(DISTINCT YMD) AS CNT FROM TBL_COUNSEL WHERE 1 = 1 ";
		
		if ($start_date <> "") {
			$query .= " AND YMD >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND YMD <= '".$end_date."' ";
		}

		if ($click_type <> "") {
			if ($click_type == "C") {
				$query .= " AND CATEGORY IN ('COUNSELONLINECLICK', 'COUNSELNAVER', 'COUNSELPHONECLICK', 'COUNSELKAKAOCLICK', 'COUNSELCALL') ";
			} else if ($click_type == "E") {
				$query .= " AND CATEGORY IN ('COUNSELONLINE', 'COUNSELNAVER', 'COUNSELPHONE', 'COUNSELKAKAO', 'COUNSELCALL') ";
			}
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		//echo $query;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function listPageCounsel($db, $start_date, $end_date, $click_type, $search_field, $search_str) {

		if ($click_type == "C") {

			$query = "SELECT (SELECT PAGE_NAME FROM TBL_PAGES WHERE PAGE_CD = LEFT(B.PAGE_CD,2)) AS DEPTH01_NAME,
											 CONCAT(B.PAGE_SEQ01,B.PAGE_SEQ02,B.PAGE_SEQ03,B.PAGE_SEQ04,B.PAGE_SEQ05) as SEQ, B.PAGE_CD, B.PAGE_NAME,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELONLINECLICK' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS ONLINE_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELNAVER' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS NAVER_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELPHONECLICK' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS PHONE_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELKAKAOCLICK' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS KAKAO_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELONLINECLICK' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_ONLINE_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELNAVER' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_NAVER_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELPHONECLICK' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_PHONE_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELKAKAOCLICK' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_KAKAO_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELCALL' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_CALL_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'P' AND ISRESERVE = 'N') THEN 1 ELSE 0 END) AS ONLINE_CANCEL_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'M' AND ISRESERVE = 'N') THEN 1 ELSE 0 END) AS M_ONLINE_CANCEL_CNT
									FROM TBL_COUNSEL A, TBL_PAGES B 
								 WHERE A.PAGE_NO = B.PAGE_NO AND A.DEL_TF ='N' AND B.DEL_TF ='N' ";
		
		} else {

			$query = "SELECT (SELECT PAGE_NAME FROM TBL_PAGES WHERE PAGE_CD = LEFT(B.PAGE_CD,2)) AS DEPTH01_NAME,
											 CONCAT(B.PAGE_SEQ01,B.PAGE_SEQ02,B.PAGE_SEQ03,B.PAGE_SEQ04,B.PAGE_SEQ05) as SEQ, B.PAGE_CD, B.PAGE_NAME,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS ONLINE_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELNAVER' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS NAVER_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELPHONE' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS PHONE_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELKAKAO' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS KAKAO_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_ONLINE_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELNAVER' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_NAVER_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELPHONE' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_PHONE_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELKAKAO' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_KAKAO_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELCALL' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_CALL_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'P' AND ISRESERVE = 'N') THEN 1 ELSE 0 END) AS ONLINE_CANCEL_CNT,
											 SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'M' AND ISRESERVE = 'N') THEN 1 ELSE 0 END) AS M_ONLINE_CANCEL_CNT
									FROM TBL_COUNSEL A, TBL_PAGES B 
								 WHERE A.PAGE_NO = B.PAGE_NO AND A.DEL_TF ='N' AND B.DEL_TF ='N' ";
		
		}

		if ($start_date <> "") {
			$query .= " AND A.YMD >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND A.YMD <= '".$end_date."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		$query .= " GROUP BY SEQ, B.PAGE_CD, B.PAGE_NAME ORDER BY SEQ ASC ";

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function totalCntPageCounsel($db, $start_date, $end_date, $click_type, $search_field, $search_str){

		$query ="SELECT COUNT(DISTINCT B.PAGE_CD) AS CNT FROM TBL_COUNSEL A, TBL_PAGES B  WHERE A.PAGE_NO = B.PAGE_NO AND A.DEL_TF ='N' AND B.DEL_TF ='N' ";
		
		if ($start_date <> "") {
			$query .= " AND A.YMD >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND A.YMD <= '".$end_date."' ";
		}

		if ($click_type <> "") {
			if ($click_type == "C") {
				$query .= " AND CATEGORY IN ('COUNSELONLINECLICK', 'COUNSELNAVER', 'COUNSELPHONECLICK', 'COUNSELKAKAOCLICK', 'COUNSELCALL') ";
			} else if ($click_type == "E") {
				$query .= " AND CATEGORY IN ('COUNSELONLINE', 'COUNSELNAVER', 'COUNSELPHONE', 'COUNSELKAKAO', 'COUNSELCALL') ";
			}
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		//echo $query;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function listWeekCounsel($db, $start_date, $end_date, $click_type, $search_field, $search_str) {

		$str_sub_query = "";

		if ($start_date <> "") {
			$str_sub_query .= " AND A.YMD >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$str_sub_query .= " AND A.YMD <= '".$end_date."' ";
		}

		if ($search_str <> "") {
			$str_sub_query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		if ($click_type == "C") {

			$query = "SELECT  B.STATE_VAL AS WK,
												SUM(CASE WHEN (CATEGORY = 'COUNSELONLINECLICK' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS ONLINE_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELNAVER' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS NAVER_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELPHONECLICK' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS PHONE_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELKAKAOCLICK' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS KAKAO_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELONLINECLICK' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_ONLINE_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELNAVER' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_NAVER_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELPHONECLICK' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_PHONE_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELKAKAOCLICK' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_KAKAO_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELCALL' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_CALL_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'P' AND ISRESERVE = 'N') THEN 1 ELSE 0 END) AS ONLINE_CANCEL_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'M' AND ISRESERVE = 'N') THEN 1 ELSE 0 END) AS M_ONLINE_CANCEL_CNT
									 FROM TBL_COUNSEL A RIGHT OUTER JOIN TBL_STATE_BASE B ON A.WK = B.STATE_VAL ".$str_sub_query." AND A.WK <> ''
									WHERE B.STATE_TYPE = 'WEEK' "; 
		
		} else {

			$query = "SELECT  B.STATE_VAL AS WK,
												SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS ONLINE_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELNAVER' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS NAVER_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELPHONE' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS PHONE_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELKAKAO' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS KAKAO_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_ONLINE_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELNAVER' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_NAVER_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELPHONE' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_PHONE_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELKAKAO' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_KAKAO_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELCALL' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_CALL_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'P' AND ISRESERVE = 'N') THEN 1 ELSE 0 END) AS ONLINE_CANCEL_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'M' AND ISRESERVE = 'N') THEN 1 ELSE 0 END) AS M_ONLINE_CANCEL_CNT
									 FROM TBL_COUNSEL A RIGHT OUTER JOIN TBL_STATE_BASE B ON A.WK = B.STATE_VAL ".$str_sub_query." AND A.WK <> ''
									WHERE B.STATE_TYPE = 'WEEK' "; 
		
		}

		$query .= " GROUP BY B.STATE_VAL ORDER BY WK ASC ";

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function listHourCounsel($db, $start_date, $end_date, $click_type, $search_field, $search_str) {

		$str_sub_query = "";

		if ($start_date <> "") {
			$str_sub_query .= " AND A.YMD >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$str_sub_query .= " AND A.YMD <= '".$end_date."' ";
		}

		if ($search_str <> "") {
			$str_sub_query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		if ($click_type == "C") {

			$query = "SELECT  B.STATE_VAL AS HH,
												SUM(CASE WHEN (CATEGORY = 'COUNSELONLINECLICK' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS ONLINE_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELNAVER' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS NAVER_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELPHONECLICK' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS PHONE_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELKAKAOCLICK' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS KAKAO_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELONLINECLICK' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_ONLINE_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELNAVER' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_NAVER_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELPHONECLICK' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_PHONE_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELKAKAOCLICK' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_KAKAO_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELCALL' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_CALL_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'P' AND ISRESERVE = 'N') THEN 1 ELSE 0 END) AS ONLINE_CANCEL_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'M' AND ISRESERVE = 'N') THEN 1 ELSE 0 END) AS M_ONLINE_CANCEL_CNT
									 FROM TBL_COUNSEL A RIGHT OUTER JOIN TBL_STATE_BASE B ON A.HH = B.STATE_VAL ".$str_sub_query." AND A.HH <> ''
									WHERE B.STATE_TYPE = 'TIME' "; 
		
		} else {

			$query = "SELECT  B.STATE_VAL AS HH,
												SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS ONLINE_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELNAVER' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS NAVER_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELPHONE' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS PHONE_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELKAKAO' AND DIVICE_TYPE = 'P') THEN 1 ELSE 0 END) AS KAKAO_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_ONLINE_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELNAVER' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_NAVER_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELPHONE' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_PHONE_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELKAKAO' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_KAKAO_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELCALL' AND DIVICE_TYPE = 'M') THEN 1 ELSE 0 END) AS M_CALL_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'P' AND ISRESERVE = 'N') THEN 1 ELSE 0 END) AS ONLINE_CANCEL_CNT,
												SUM(CASE WHEN (CATEGORY = 'COUNSELONLINE' AND DIVICE_TYPE = 'M' AND ISRESERVE = 'N') THEN 1 ELSE 0 END) AS M_ONLINE_CANCEL_CNT
									 FROM TBL_COUNSEL A RIGHT OUTER JOIN TBL_STATE_BASE B ON A.HH = B.STATE_VAL ".$str_sub_query." AND A.HH <> ''
									WHERE B.STATE_TYPE = 'TIME' "; 
		
		}

		$query .= " GROUP BY B.STATE_VAL ORDER BY HH ASC ";

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function totalCntHourCounsel($db, $start_date, $end_date, $click_type, $search_field, $search_str){

		$query ="SELECT COUNT(DISTINCT HH) AS CNT FROM TBL_COUNSEL WHERE 1 = 1 AND HH <> '' ";
		
		if ($start_date <> "") {
			$query .= " AND YMD >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND YMD <= '".$end_date."' ";
		}

		if ($click_type <> "") {
			if ($click_type == "C") {
				$query .= " AND CATEGORY IN ('COUNSELONLINECLICK', 'COUNSELNAVER', 'COUNSELPHONECLICK', 'COUNSELKAKAOCLICK', 'COUNSELCALL') ";
			} else if ($click_type == "E") {
				$query .= " AND CATEGORY IN ('COUNSELONLINE', 'COUNSELNAVER', 'COUNSELPHONE', 'COUNSELKAKAO', 'COUNSELCALL') ";
			}
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		//echo $query;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function getWaitCounsel($db) {

		$query ="SELECT CATEGORY, 
										IFNULL(SUM(CASE WHEN STATE = '0' THEN 1 END),0) AS STATE_00,
										IFNULL(SUM(CASE WHEN STATE = '1' THEN 1 END),0) AS STATE_01,
										IFNULL(SUM(CASE WHEN STATE = '2' THEN 1 END),0) AS STATE_02,
										IFNULL(SUM(CASE WHEN STATE = '3' THEN 1 END),0) AS STATE_03,
										IFNULL(SUM(CASE WHEN STATE IN ('4','5','6') THEN 1 END),0) AS STATE_04,
										IFNULL(SUM(CASE WHEN STATE = '5' THEN 1 END),0) AS STATE_05,
										IFNULL(SUM(CASE WHEN STATE = '6' THEN 1 END),0) AS STATE_06,
										CASE WHEN CATEGORY = 'MINWON' THEN 0 ELSE 
											CASE WHEN CATEGORY = 'PRAISE' THEN 1 ELSE 
												CASE WHEN CATEGORY = 'PROPOSAL' THEN 2  
												END
											END
										END AS ORDER_CATEGORY
							 FROM TBL_COUNSEL 
							WHERE CATEGORY IN ('MINWON','PRAISE','PROPOSAL') 
								AND DEL_TF = 'N'
							GROUP BY CATEGORY ";

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function getReservationCount($db) {
		
		$query = "SELECT SUM(CASE WHEN A.RV_AGREE_TF = '0' THEN 1 ELSE 0 END) AS A1,
										 SUM(CASE WHEN A.RV_AGREE_TF <> '0' THEN 1 ELSE 0 END) AS A2
								FROM TBL_RESERVATION A
							 WHERE A.DEL_TF = 'N' AND A.USE_TF = 'Y' ";

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}

	function listPagePeriod($db, $start_date, $end_date, $divicetype) {

		$query = "
			SELECT 
				log.PAGE_NO,
				CASE 
					WHEN pg.PAGE_NAME IN ('시설 예약', '시설예약', '입주기업 소개​', '예약 현황', '시설안내', '시설 및 입주 안내') THEN '시설안내'
					WHEN pg.PAGE_NAME IN ('로그인', '회원가입') THEN '회원 가입 및 로그인'
					WHEN pg.PAGE_NAME IN ('나의 회원정보', '나의 예약현황') THEN '마이페이지'
					WHEN pg.PAGE_NAME IN ('통합검색') THEN '통합검색'
					WHEN dp1.PAGE_NAME IS NOT NULL THEN dp1.PAGE_NAME
					WHEN dp2.PAGE_NAME IS NOT NULL THEN dp2.PAGE_NAME
					ELSE '기타'
				END AS CATE,
				pg.PAGE_NAME AS PAGE_NAME,
				COUNT(*) AS CNT
			FROM TBL_LOG log
			LEFT JOIN TBL_PAGES dp1 ON log.DEPTH01 = dp1.PAGE_NO
			LEFT JOIN TBL_PAGES dp2 ON log.DEPTH02 = dp2.PAGE_NO
			LEFT JOIN TBL_PAGES pg ON log.PAGE_NO = pg.PAGE_NO
			WHERE log.YMD >= '".$start_date."'
				AND log.YMD <= '".$end_date."'";
	
		if ($divicetype !== "") {
			$query .= " AND log.DIVICETYPE = '".$divicetype."'";
		}
	
		$query .= "
			GROUP BY log.PAGE_NO, dp1.PAGE_NAME, dp2.PAGE_NAME, pg.PAGE_NAME
			ORDER BY CNT DESC;
		";
	
		// echo $query;
	
		$result = mysqli_query($db, $query);
		$record = array();
	
		if ($result !== false) {
			for ($i = 0; $i < mysqli_num_rows($result); $i++) {
				$record[$i] = sql_result_array($db, $result, $i);
			}
		}
		return $record;
	}


	// function listPagePeriod($db, $start_date, $end_date, $divicetype) {

	// 	$query = "SELECT PAGE_NO,
	// 									(SELECT PAGE_NAME FROM TBL_PAGES WHERE PAGE_NO = TBL_LOG.DEPTH01) AS CATE,
	// 									(SELECT PAGE_NAME FROM TBL_PAGES WHERE PAGE_NO = TBL_LOG.PAGE_NO) AS PAGE_NAME,
	// 									 COUNT(*) AS CNT 
	// 						 FROM TBL_LOG 
	// 						WHERE YMD >= '".$start_date."'
	// 							AND YMD <= '".$end_date."' ";
		
	// 	if ($divicetype <> "") {
	// 		$query .= " AND DIVICETYPE = '".$divicetype."' ";
	// 	}

	// 	$query .= "GROUP BY PAGE_NO
	// 						 ORDER BY CNT DESC, YMD DESC, HH DESC, MI DESC ";
		
	// 	//echo $query;

	// 	$result = mysqli_query($db, $query);
	// 	$record = array();
		

	// 	if ($result <> "") {
	// 		for($i=0;$i < mysqli_num_rows($result);$i++) {
	// 			$record[$i] = sql_result_array($db,$result,$i);
	// 		}
	// 	}
	// 	return $record;
	// }

	function listNoticePeriod($db, $start_date, $end_date, $divicetype) {

		$query = "SELECT CAST(PARAM02 AS UNSIGNED) AS PARAM02,
										(SELECT CATE_01 FROM TBL_BOARD WHERE B_NO = TBL_LOG.PARAM02) AS CATE,
										(SELECT TITLE FROM TBL_BOARD WHERE B_NO = TBL_LOG.PARAM02) AS TITLE,
										(SELECT REG_DATE FROM TBL_BOARD WHERE B_NO = TBL_LOG.PARAM02) AS REG_DATE,
										 COUNT(*) AS CNT 
							 FROM TBL_LOG 
							WHERE PARAM01 = 'B_1_1'
								AND PARAM02 <> ''
								AND YMD >= '".$start_date."'
								AND YMD <= '".$end_date."' ";
		
		if ($divicetype <> "") {
			$query .= " AND DIVICETYPE = '".$divicetype."' ";
		}

		$query .= "GROUP BY CAST(PARAM02 AS UNSIGNED)
							 ORDER BY CNT DESC, YMD DESC, HH DESC, MI DESC ";
		
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


?>