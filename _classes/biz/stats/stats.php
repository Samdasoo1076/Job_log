<?
	# =============================================================================
	# File Name    : stats.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2019-06-11
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	function memberStatsAsDate($db, $yyyy, $mm, $chk_type, $pay_type, $area_cd, $party, $agreement, $m_online_flag, $group_cd) {
		
		$YYYYMM = $yyyy."-".$mm;
		
		if ($chk_type == "D") {
			$query = "SELECT IFNULL(IN_COUNT,0) AS IN_COUNT, M_OPEN_DATE, CAL_DATE, CAL_YYYYMM, DW ";

			$query .= "	FROM
									(SELECT COUNT(M_NO) AS IN_COUNT, M_OPEN_DATE 
										 FROM TBL_MEMBER
										WHERE 1 = 1 AND IFNULL(M_OPEN_DATE, '') <> ''
										";

		} else {
			$query = "SELECT IFNULL(IN_COUNT,0) AS IN_COUNT, M_OPEN_DATE, CAL_DATE, CAL_YYYYMM, DW ";
			$query .= "	 FROM
									(SELECT COUNT(M_NO) AS IN_COUNT, left(M_OPEN_DATE,7) AS M_OPEN_DATE
										 FROM TBL_MEMBER
										WHERE 1 = 1 AND IFNULL(M_OPEN_DATE, '') <> ''
										";
		}

		if (($YYYYMM <> "") && ($chk_type == "D")) {
			$query .= " AND left(M_OPEN_DATE, 7) = '".$YYYYMM."' ";
		}

		if ($group_cd <> "") {
			$query .= " AND M_ORGANIZATION like '".$group_cd."%' ";
		}

		if ($pay_type <> "") {
			$query .= " AND M_6 like '%".$pay_type."%' ";
		}

		if ($area_cd <> "") {
			if ($area_cd == "경북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%제주%') ";
			} else {
				$query .= " AND SIDO like '%".$area_cd."%' ";
			}
		}

		if ($party <> "") {
			$query .= " AND M_3 like '%".$party."%' ";
		}
		
		if ($agreement <> "") {
			if ($agreement == "Y") {
				$query .= " AND M_SIGNATURE <> '' ";
			} else {
				$query .= " AND M_SIGNATURE = '' ";
			}
		}

		if ($m_online_flag <> "") {
			$query .= " AND M_ONLINE_FLAG = '$m_online_flag' ";
		}

		if ($chk_type == "D") {
			$query .= "			GROUP BY M_OPEN_DATE) A right outer join ";
		} else {
			$query .= "			GROUP BY left(M_OPEN_DATE,7)) A right outer join ";
		}


		if ($chk_type == "D") {
			$query .= "		(SELECT CONCAT(left(ym,4),'-',right(ym,2),'-',d) AS CAL_DATE, DW,
														CONCAT(left(ym,4),'-',right(ym,2)) AS CAL_YYYYMM ";
		} else {

			$query .= "		(SELECT CONCAT(left(ym,4),'-',right(ym,2)) AS CAL_DATE, DW,
														CONCAT(left(ym,4),'-',right(ym,2)) AS CAL_YYYYMM ";
		}

		$query .= "			 FROM (SELECT date_format(dt,'%Y%m') YM, Week(dt) W, right(CONCAT('0',Day(dt)),2) D, DayofWeek(dt) DW
														 FROM (SELECT CONCAT(y, '0101') + INTERVAL a*100 + b*10 + c DAY dt
																		 FROM (SELECT 0 a
																									UNION ALL SELECT 1
																									UNION ALL SELECT 2
																									UNION ALL SELECT 3
																					 ) a, 
																					(SELECT 0 b
																									UNION ALL SELECT 1
																									UNION ALL SELECT 2
																									UNION ALL SELECT 3
																									UNION ALL SELECT 4
																									UNION ALL SELECT 5
																									UNION ALL SELECT 6
																									UNION ALL SELECT 7
																									UNION ALL SELECT 8
																									UNION ALL SELECT 9
																					) b, 
																					(SELECT 0 c
																									UNION ALL SELECT 1
																									UNION ALL SELECT 2
																									UNION ALL SELECT 3
																									UNION ALL SELECT 4
																									UNION ALL SELECT 5
																									UNION ALL SELECT 6
																									UNION ALL SELECT 7
																									UNION ALL SELECT 8
																									UNION ALL SELECT 9
																					) c, 
																					(SELECT '$yyyy' y) d
																			WHERE a*100 + b*10 + c < DayOfYear(CONCAT(y, '1231'))
																	) a
															) a ";
		
		if ($chk_type == "D") {
			$query .= "					GROUP BY ym, d, dw) B on A.M_OPEN_DATE = B.cal_date
										WHERE CAL_YYYYMM = '$YYYYMM' ";
		} else {
			$query .= "					GROUP BY ym) B on A.M_OPEN_DATE = B.cal_date ";
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


	function memberStatsAsArea($db, $start_date, $end_date, $pay_type, $area_cd, $party, $agreement, $m_online_flag, $group_cd) {


		$query = "SELECT SUM(IN_CNT) AS IN_COUNT,
										 SUM(OUT_CNT) AS OUT_COUNT,
										 S_SIDO, SIDO 
								FROM
										(SELECT M_NO, SIDO, M_LEAVE_DATE,
														CASE WHEN IFNULL(M_OPEN_DATE, '') <> '' THEN 1 ELSE 0 END AS IN_CNT, 
														0 AS OUT_CNT,
														CASE WHEN SIDO like '중앙%' THEN '중앙당' ELSE 
														CASE WHEN SIDO like '서울%' THEN '서울' ELSE 
														CASE WHEN SIDO like '인천%' THEN '인천' ELSE 
														CASE WHEN SIDO like '부산%' THEN '부산' ELSE 
														CASE WHEN SIDO like '대전%' THEN '대전' ELSE 
														CASE WHEN SIDO like '대구%' THEN '대구' ELSE 
														CASE WHEN SIDO like '광주%' THEN '광주' ELSE 
														CASE WHEN SIDO like '울산%' THEN '울산' ELSE 
														CASE WHEN SIDO like '세종%' THEN '세종특별자치시' ELSE 
														CASE WHEN SIDO like '강원%' THEN '강원' ELSE
														CASE WHEN SIDO like '경기%' THEN '경기' ELSE
														CASE WHEN (SIDO like '경남%' OR SIDO like '경상남도%') THEN '경남' ELSE 
														CASE WHEN (SIDO like '경북%' OR SIDO like '경상북도%') THEN '경북' ELSE 
														CASE WHEN (SIDO like '전남%' OR SIDO like '전라남도%') THEN '전남' ELSE 
														CASE WHEN (SIDO like '전북%' OR SIDO like '전라북도%') THEN '전북' ELSE 
														CASE WHEN (SIDO like '충남%' OR SIDO like '충청남도%') THEN '충남' ELSE 
														CASE WHEN (SIDO like '충북%' OR SIDO like '충청북도%') THEN '충북' ELSE 
														CASE WHEN SIDO like '제주%' THEN '제주특별자치도' ELSE '(기타)'
														END END END END END END END END END END END END END END END END END END
														AS S_SIDO
											 FROM TBL_MEMBER
											WHERE 1 = 1 ";

		if ($start_date <> "") {
			$query .= " AND M_OPEN_DATE >= '".$start_date."' ";
		}

		if ($group_cd <> "") {
			$query .= " AND M_ORGANIZATION like '".$group_cd."%' ";
		}


		if ($end_date <> "") {
			$query .= " AND M_OPEN_DATE <= '".$end_date." 23:59:59' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		if ($pay_type <> "") {
			$query .= " AND M_6 like '%".$pay_type."%' ";
		}

		if ($area_cd <> "") {
			if ($area_cd == "경북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%제주%') ";
			} else {
				$query .= " AND SIDO like '%".$area_cd."%' ";
			}
		}

		if ($party <> "") {
			$query .= " AND M_3 like '%".$party."%' ";
		}
		
		if ($agreement <> "") {
			if ($agreement == "Y") {
				$query .= " AND M_SIGNATURE <> '' ";
			} else {
				$query .= " AND M_SIGNATURE = '' ";
			}
		}

		if ($m_online_flag <> "") {
			$query .= " AND M_ONLINE_FLAG = '$m_online_flag' ";
		}
			
		$query .= "				UNION ";

		$query .= "			 SELECT M_NO, SIDO, M_LEAVE_DATE,
														0 AS IN_CNT, 
														CASE WHEN IFNULL(M_LEAVE_DATE, '') != '' THEN 1 ELSE 0 END AS OUT_CNT,
														CASE WHEN SIDO like '중앙%' THEN '중앙당' ELSE 
														CASE WHEN SIDO like '서울%' THEN '서울' ELSE 
														CASE WHEN SIDO like '인천%' THEN '인천' ELSE 
														CASE WHEN SIDO like '부산%' THEN '부산' ELSE 
														CASE WHEN SIDO like '대전%' THEN '대전' ELSE 
														CASE WHEN SIDO like '대구%' THEN '대구' ELSE 
														CASE WHEN SIDO like '광주%' THEN '광주' ELSE 
														CASE WHEN SIDO like '울산%' THEN '울산' ELSE 
														CASE WHEN SIDO like '세종%' THEN '세종특별자치시' ELSE 
														CASE WHEN SIDO like '강원%' THEN '강원' ELSE
														CASE WHEN SIDO like '경기%' THEN '경기' ELSE
														CASE WHEN (SIDO like '경남%' OR SIDO like '경상남도%') THEN '경남' ELSE 
														CASE WHEN (SIDO like '경북%' OR SIDO like '경상북도%') THEN '경북' ELSE 
														CASE WHEN (SIDO like '전남%' OR SIDO like '전라남도%') THEN '전남' ELSE 
														CASE WHEN (SIDO like '전북%' OR SIDO like '전라북도%') THEN '전북' ELSE 
														CASE WHEN (SIDO like '충남%' OR SIDO like '충청남도%') THEN '충남' ELSE 
														CASE WHEN (SIDO like '충북%' OR SIDO like '충청북도%') THEN '충북' ELSE 
														CASE WHEN SIDO like '제주%' THEN '제주특별자치도' ELSE '(기타)'
														END END END END END END END END END END END END END END END END END END
														AS S_SIDO
											 FROM TBL_MEMBER
											WHERE 1 = 1 ";

		if ($start_date <> "") {
			$query .= " AND M_LEAVE_DATE >= '".str_replace("-","",$start_date)."' ";
		}

		if ($group_cd <> "") {
			$query .= " AND M_ORGANIZATION like '".$group_cd."%' ";
		}

		if ($end_date <> "") {
			$query .= " AND M_LEAVE_DATE <= '".str_replace("-","",$end_date)."' ";
		}

		if ($search_str <> "") {
			$query .= " AND ".$search_field." like '%".$search_str."%' ";
		}

		if ($pay_type <> "") {
			$query .= " AND M_6 like '%".$pay_type."%' ";
		}

		if ($area_cd <> "") {
			if ($area_cd == "경북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%제주%') ";
			} else {
				$query .= " AND SIDO like '%".$area_cd."%' ";
			}
		}

		if ($party <> "") {
			$query .= " AND M_3 like '%".$party."%' ";
		}
		
		if ($agreement <> "") {
			if ($agreement == "Y") {
				$query .= " AND M_SIGNATURE <> '' ";
			} else {
				$query .= " AND M_SIGNATURE = '' ";
			}
		}

		if ($m_online_flag <> "") {
			$query .= " AND M_ONLINE_FLAG = '$m_online_flag' ";
		}

		$query .= "			) A
									GROUP BY S_SIDO";
		
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

	function paymentStatsAsDate($db, $yyyy, $mm, $chk_type, $pay_type, $area_cd, $party, $pay_state, $pay_reason, $group_cd) {
		
		$YYYYMM = $yyyy.$mm;
		
		if ($chk_type == "D") {
			$query = "SELECT IFNULL(IN_COUNT,0) AS IN_COUNT, RES_PAY_DATE, CAL_DATE, CAL_YYYYMM, DW  ";

			$query .= "	FROM
									(SELECT CASE WHEN PAY_RESULT = 'Y' THEN SUM(B.RES_CMS_AMOUNT) ELSE SUM(B.CMS_AMOUNT) END AS IN_COUNT, 
													B.RES_PAY_DATE
										 FROM TBL_MEMBER A, TBL_MEMBER_PAYMENT B
										 WHERE A.M_NO = B.M_NO 
											 AND B.DEL_TF = 'N'
											 AND left(B.RES_PAY_DATE, 6) = '".$YYYYMM."' 
										";

		} else {

			$query = "SELECT IFNULL(IN_COUNT,0) AS IN_COUNT, RES_PAY_DATE, CAL_DATE, CAL_YYYYMM, DW ";
			$query .= "	 FROM
									(SELECT CASE WHEN PAY_RESULT = 'Y' THEN SUM(B.RES_CMS_AMOUNT) ELSE SUM(B.CMS_AMOUNT) END AS IN_COUNT, 
													left(RES_PAY_DATE,6) AS RES_PAY_DATE
										 FROM TBL_MEMBER A, TBL_MEMBER_PAYMENT B
										WHERE A.M_NO = B.M_NO
											AND B.DEL_TF ='N'
										";
		}

		if ($group_cd <> "") {
			$query .= " AND A.M_ORGANIZATION like '".$group_cd."%' ";
		}

		if ($pay_type <> "") {
			$query .= " AND B.PAY_TYPE like '%".$pay_type."%' ";
		}

		if ($area_cd <> "") {
			if ($area_cd == "경북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%제주%') ";
			} else {
				$query .= " AND SIDO like '%".$area_cd."%' ";
			}
		}

		if ($pay_state <> "") {

			if ($pay_state == "Y") { $query .= " AND B.PAY_RESULT = 'Y' "; }
			if ($pay_state == "F") { $query .= " AND B.PAY_RESULT <> 'Y' AND B.PAY_RESULT <> '' "; }
			if ($pay_state == "S") { $query .= " AND B.PAY_RESULT = '' "; }
			if ($pay_state == "R") { $query .= " AND B.PAY_RESULT = 'R' "; }

		}

		if ($party <> "") {

			$query .= " AND A.M_3 like '%".$party."%' ";

		}
		
		if ($m_online_flag <> "") {

			$query .= " AND M_ONLINE_FLAG = '$m_online_flag' ";

		}

		if ($chk_type == "D") {

			$query .= "			GROUP BY B.RES_PAY_DATE) A right outer join ";

		} else {

			$query .= "			GROUP BY left(B.RES_PAY_DATE,6)) A right outer join ";

		}


		if ($chk_type == "D") {

			$query .= "		(SELECT CONCAT(ym,d) AS CAL_DATE, DW,
														ym AS CAL_YYYYMM ";

		} else {

			$query .= "		(SELECT ym AS CAL_DATE, DW,
														ym AS CAL_YYYYMM ";

		}

		$query .= "			 FROM (SELECT date_format(dt,'%Y%m') YM, Week(dt) W, right(CONCAT('0',Day(dt)),2) D, DayofWeek(dt) DW
														 FROM (SELECT CONCAT(y, '0101') + INTERVAL a*100 + b*10 + c DAY dt
																		 FROM (SELECT 0 a
																									UNION ALL SELECT 1
																									UNION ALL SELECT 2
																									UNION ALL SELECT 3
																					 ) a, 
																					(SELECT 0 b
																									UNION ALL SELECT 1
																									UNION ALL SELECT 2
																									UNION ALL SELECT 3
																									UNION ALL SELECT 4
																									UNION ALL SELECT 5
																									UNION ALL SELECT 6
																									UNION ALL SELECT 7
																									UNION ALL SELECT 8
																									UNION ALL SELECT 9
																					) b, 
																					(SELECT 0 c
																									UNION ALL SELECT 1
																									UNION ALL SELECT 2
																									UNION ALL SELECT 3
																									UNION ALL SELECT 4
																									UNION ALL SELECT 5
																									UNION ALL SELECT 6
																									UNION ALL SELECT 7
																									UNION ALL SELECT 8
																									UNION ALL SELECT 9
																					) c, 
																					(SELECT '$yyyy' y) d
																			WHERE a*100 + b*10 + c < DayOfYear(CONCAT(y, '1231'))
																	) a
															) a ";
		
		if ($chk_type == "D") {

			$query .= "					GROUP BY ym, d, dw) B on A.RES_PAY_DATE = B.CAL_DATE
										WHERE CAL_YYYYMM = '$YYYYMM' ";

		} else {

			$query .= "					GROUP BY ym) B on A.RES_PAY_DATE = B.CAL_DATE ";

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


	function paymentStatsAsArea($db, $start_date, $end_date, $pay_type, $area_cd, $party, $pay_state, $pay_reason, $group_cd) {
		
		$start_date		= str_replace("-","", $start_date);
		$end_date			= str_replace("-","", $end_date);

		$query = "SELECT IFNULL(SUM(RES_CMS_AMOUNT - REFUND_AMOUNT),0) AS IN_COUNT,
										 IFNULL(SUM(REFUND_AMOUNT),0) AS OUT_COUNT, S_SIDO, SIDO 
								FROM
										(SELECT B.RES_CMS_AMOUNT, B.REFUND_AMOUNT, B.CMS_AMOUNT, PAY_RESULT, SIDO, 
														CASE WHEN SIDO like '중앙%' THEN '중앙당' ELSE 
														CASE WHEN SIDO like '서울%' THEN '서울' ELSE 
														CASE WHEN SIDO like '인천%' THEN '인천' ELSE 
														CASE WHEN SIDO like '부산%' THEN '부산' ELSE 
														CASE WHEN SIDO like '대전%' THEN '대전' ELSE 
														CASE WHEN SIDO like '대구%' THEN '대구' ELSE 
														CASE WHEN SIDO like '광주%' THEN '광주' ELSE 
														CASE WHEN SIDO like '울산%' THEN '울산' ELSE 
														CASE WHEN SIDO like '세종%' THEN '세종특별자치시' ELSE 
														CASE WHEN SIDO like '강원%' THEN '강원' ELSE
														CASE WHEN SIDO like '경기%' THEN '경기' ELSE
														CASE WHEN (SIDO like '경남%' OR SIDO like '경상남도%') THEN '경남' ELSE 
														CASE WHEN (SIDO like '경북%' OR SIDO like '경상북도%') THEN '경북' ELSE 
														CASE WHEN (SIDO like '전남%' OR SIDO like '전라남도%') THEN '전남' ELSE 
														CASE WHEN (SIDO like '전북%' OR SIDO like '전라북도%') THEN '전북' ELSE 
														CASE WHEN (SIDO like '충남%' OR SIDO like '충청남도%') THEN '충남' ELSE 
														CASE WHEN (SIDO like '충북%' OR SIDO like '충청북도%') THEN '충북' ELSE 
														CASE WHEN SIDO like '제주%' THEN '제주특별자치도' ELSE SIDO
														END END END END END END END END END END END END END END END END END END
														AS S_SIDO
											 FROM TBL_MEMBER A, TBL_MEMBER_PAYMENT B
											WHERE A.M_NO = B.M_NO 
												AND B.DEL_TF ='N' ";

		if ($start_date <> "") {
			$query .= " AND B.RES_PAY_DATE >= '".$start_date."' ";
		}

		if ($end_date <> "") {
			$query .= " AND B.RES_PAY_DATE <= '".$end_date." 23:59:59' ";
		}

		if ($group_cd <> "") {
			$query .= " AND A.M_ORGANIZATION like '".$group_cd."%' ";
		}

		if ($pay_type <> "") {
			$query .= " AND B.PAY_TYPE like '%".$pay_type."%' ";
		}

		if ($area_cd <> "") {
			if ($area_cd == "경북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$query .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%제주%') ";
			} else {
				$query .= " AND SIDO like '%".$area_cd."%' ";
			}
		}

		if ($pay_state <> "") {
			if ($pay_state == "Y") { $query .= " AND B.PAY_RESULT = 'Y' "; }
			if ($pay_state == "F") { $query .= " AND B.PAY_RESULT <> 'Y' AND B.PAY_RESULT <> '' "; }
			if ($pay_state == "S") { $query .= " AND B.PAY_RESULT = '' "; }
			if ($pay_state == "R") { $query .= " AND B.PAY_RESULT = 'R' "; }
		}

		if ($party <> "") {
			$query .= " AND A.M_3 like '%".$party."%' ";
		}
		
		if ($m_online_flag <> "") {
			$query .= " AND M_ONLINE_FLAG = '$m_online_flag' ";
		}

		$query .= "			) A
									GROUP BY S_SIDO";

		$result = mysqli_query($db, $query);
		$record = array();
		
		//echo $query;

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}



	function getStateYear($db) {
		
		$query = "SELECT DISTINCT left(M_OPEN_DATE,4) AS S_YEAR FROM TBL_MEMBER ORDER BY M_OPEN_DATE ASC ";

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;

	}



	function NewpaymentStatsAsDate($db, $yyyy, $mm, $chk_type, $pay_type, $area_cd, $party, $pay_state, $pay_reason, $group_cd) {
		
		$YYYYMM = $yyyy.$mm;

		$str_where = "";

		if ($group_cd <> "") {
			$str_where .= " AND A.M_ORGANIZATION like '".$group_cd."%' ";
		}

		if ($pay_type <> "") {
			$str_where .= " AND B.PAY_TYPE like '%".$pay_type."%' ";
		}

		if ($area_cd <> "") {
			if ($area_cd == "경북") {
				$str_where .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$str_where .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$str_where .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$str_where .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$str_where .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$str_where .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$str_where .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$str_where .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%제주%') ";
			} else {
				$str_where .= " AND SIDO like '%".$area_cd."%' ";
			}
		}

		if ($pay_state <> "") {

			if ($pay_state == "Y") { $str_where .= " AND B.PAY_RESULT = 'Y' "; }
			if ($pay_state == "F") { $str_where .= " AND B.PAY_RESULT <> 'Y' AND B.PAY_RESULT <> '' "; }
			if ($pay_state == "S") { $str_where .= " AND B.PAY_RESULT = '' "; }
			if ($pay_state == "R") { $str_where .= " AND B.PAY_RESULT = 'R' "; }

		}

		if ($party <> "") {

			$str_where .= " AND A.M_3 like '%".$party."%' ";

		}
		
		if ($m_online_flag <> "") {

			$str_where .= " AND M_ONLINE_FLAG = '$m_online_flag' ";

		}


		if ($chk_type == "D") {

			$query = "SELECT CONCAT(ym,d) AS CAL_DATE, DW, ym AS CAL_YYYYMM,

											(SELECT IFNULL(SUM(B.RES_CMS_AMOUNT - B.REFUND_AMOUNT),0) AS IN_COUNT
												 FROM TBL_MEMBER A, TBL_MEMBER_PAYMENT B 
												 WHERE A.M_NO = B.M_NO
													AND B.RES_PAY_DATE = CONCAT(ym,d) 
													AND B.DEL_TF = 'N' ".$str_where. "
											) AS IN_COUNT,

											(SELECT IFNULL(SUM(B.REFUND_AMOUNT),0) AS OUT_COUNT
												 FROM TBL_MEMBER A, TBL_MEMBER_PAYMENT B 
												WHERE A.M_NO = B.M_NO
													AND B.REFUND_DATE = CONCAT(ym,d) 
													AND B.DEL_TF = 'N' ".$str_where. "
											) AS OUT_COUNT

									FROM (SELECT date_format(dt,'%Y%m') YM, Week(dt) W, right(CONCAT('0',Day(dt)),2) D, 
															 DayofWeek(dt) DW FROM (SELECT CONCAT(y, '0101') + INTERVAL a*100 + b*10 + c DAY dt 
												 FROM (SELECT 0 a 
																			UNION ALL SELECT 1 
																			UNION ALL SELECT 2 
																			UNION ALL SELECT 3 ) a, 
															(SELECT 0 b 
																			UNION ALL SELECT 1 
																			UNION ALL SELECT 2 
																			UNION ALL SELECT 3 
																			UNION ALL SELECT 4 
																			UNION ALL SELECT 5 
																			UNION ALL SELECT 6 
																			UNION ALL SELECT 7 
																			UNION ALL SELECT 8 
																			UNION ALL SELECT 9 ) b, 
															(SELECT 0 c 
																			UNION ALL SELECT 1 
																			UNION ALL SELECT 2 
																			UNION ALL SELECT 3 
																			UNION ALL SELECT 4 
																			UNION ALL SELECT 5 
																			UNION ALL SELECT 6 
																			UNION ALL SELECT 7 
																			UNION ALL SELECT 8 
																			UNION ALL SELECT 9 ) c, 
															(SELECT '$yyyy' y) d 
													WHERE a*100 + b*10 + c < DayOfYear(CONCAT(y, '1231')) ) a ) a 
										WHERE ym = '$YYYYMM'
							GROUP BY ym, d, dw ";
		} else {

			$query = "SELECT ym AS CAL_DATE, DW, ym AS CAL_YYYYMM,

											(SELECT IFNULL(SUM(B.RES_CMS_AMOUNT - B.REFUND_AMOUNT),0) AS IN_COUNT
												 FROM TBL_MEMBER A, TBL_MEMBER_PAYMENT B 
												 WHERE A.M_NO = B.M_NO
													AND left(B.RES_PAY_DATE,6) = ym
													AND B.DEL_TF = 'N' ".$str_where. "
											) AS IN_COUNT,

											(SELECT IFNULL(SUM(B.REFUND_AMOUNT),0) AS OUT_COUNT
												 FROM TBL_MEMBER A, TBL_MEMBER_PAYMENT B 
												WHERE A.M_NO = B.M_NO
													AND left(B.REFUND_DATE,6) = ym
													AND B.DEL_TF = 'N' ".$str_where. "
											) AS OUT_COUNT

									FROM (SELECT date_format(dt,'%Y%m') YM, Week(dt) W, right(CONCAT('0',Day(dt)),2) D, 
															 DayofWeek(dt) DW FROM (SELECT CONCAT(y, '0101') + INTERVAL a*100 + b*10 + c DAY dt 
												 FROM (SELECT 0 a 
																			UNION ALL SELECT 1 
																			UNION ALL SELECT 2 
																			UNION ALL SELECT 3 ) a, 
															(SELECT 0 b 
																			UNION ALL SELECT 1 
																			UNION ALL SELECT 2 
																			UNION ALL SELECT 3 
																			UNION ALL SELECT 4 
																			UNION ALL SELECT 5 
																			UNION ALL SELECT 6 
																			UNION ALL SELECT 7 
																			UNION ALL SELECT 8 
																			UNION ALL SELECT 9 ) b, 
															(SELECT 0 c 
																			UNION ALL SELECT 1 
																			UNION ALL SELECT 2 
																			UNION ALL SELECT 3 
																			UNION ALL SELECT 4 
																			UNION ALL SELECT 5 
																			UNION ALL SELECT 6 
																			UNION ALL SELECT 7 
																			UNION ALL SELECT 8 
																			UNION ALL SELECT 9 ) c, 
															(SELECT '$yyyy' y) d 
													WHERE a*100 + b*10 + c < DayOfYear(CONCAT(y, '1231')) ) a ) a 
							GROUP BY ym ORDER BY ym";

		}

		$result = mysqli_query($db, $query);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
	}


	function NewmemberStatsAsDate($db, $yyyy, $mm, $chk_type, $pay_type, $area_cd, $party, $agreement, $m_online_flag, $group_cd) {
		
		$YYYYMM = $yyyy."-".$mm;

		$str_where = "";

		if ($group_cd <> "") {
			$str_where .= " AND M_ORGANIZATION like '".$group_cd."%' ";
		}

		if ($pay_type <> "") {
			$str_where .= " AND M_6 like '%".$pay_type."%' ";
		}

		if ($area_cd <> "") {
			if ($area_cd == "경북") {
				$str_where .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상북도%') ";
			} else if ($area_cd == "경남") {
				$str_where .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%경상남도%') ";
			} else if ($area_cd == "전북") {
				$str_where .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라북도%') ";
			} else if ($area_cd == "전남") {
				$str_where .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%전라남도%') ";
			} else if ($area_cd == "충북") {
				$str_where .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청북도%') ";
			} else if ($area_cd == "충남") {
				$str_where .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%충청남도%') ";
			} else if ($area_cd == "세종특별자치시") {
				$str_where .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%세종%') ";
			} else if ($area_cd == "제주특별자치도") {
				$str_where .= " AND (SIDO like '%".$area_cd."%' OR SIDO like '%제주%') ";
			} else {
				$str_where .= " AND SIDO like '%".$area_cd."%' ";
			}
		}

		if ($party <> "") {
			$str_where .= " AND M_3 like '%".$party."%' ";
		}
		
		if ($agreement <> "") {
			if ($agreement == "Y") {
				$str_where .= " AND M_SIGNATURE <> '' ";
			} else {
				$str_where .= " AND M_SIGNATURE = '' ";
			}
		}

		if ($m_online_flag <> "") {
			$str_where .= " AND M_ONLINE_FLAG = '$m_online_flag' ";
		}


		if ($chk_type == "D") {

			$query = "SELECT CONCAT(ym,'-',d) AS CAL_DATE, DW, ym AS CAL_YYYYMM,

											(SELECT COUNT(M_NO) AS IN_COUNT
												 FROM TBL_MEMBER
												 WHERE M_OPEN_DATE = CONCAT(ym,'-',d) 
													AND IFNULL(M_OPEN_DATE, '') <> '' ".$str_where. "
											) AS IN_COUNT,

											(SELECT COUNT(M_NO) AS OUT_COUNT
												 FROM TBL_MEMBER
												 WHERE M_LEAVE_DATE = REPLACE(CONCAT(ym,d),'-','')
													AND IFNULL(M_LEAVE_DATE, '') != '' ".$str_where. "
											) AS OUT_COUNT

									FROM (SELECT date_format(dt,'%Y-%m') YM, Week(dt) W, right(CONCAT('0',Day(dt)),2) D, 
															 DayofWeek(dt) DW FROM (SELECT CONCAT(y, '0101') + INTERVAL a*100 + b*10 + c DAY dt 
												 FROM (SELECT 0 a 
																			UNION ALL SELECT 1 
																			UNION ALL SELECT 2 
																			UNION ALL SELECT 3 ) a, 
															(SELECT 0 b 
																			UNION ALL SELECT 1 
																			UNION ALL SELECT 2 
																			UNION ALL SELECT 3 
																			UNION ALL SELECT 4 
																			UNION ALL SELECT 5 
																			UNION ALL SELECT 6 
																			UNION ALL SELECT 7 
																			UNION ALL SELECT 8 
																			UNION ALL SELECT 9 ) b, 
															(SELECT 0 c 
																			UNION ALL SELECT 1 
																			UNION ALL SELECT 2 
																			UNION ALL SELECT 3 
																			UNION ALL SELECT 4 
																			UNION ALL SELECT 5 
																			UNION ALL SELECT 6 
																			UNION ALL SELECT 7 
																			UNION ALL SELECT 8 
																			UNION ALL SELECT 9 ) c, 
															(SELECT '$yyyy' y) d 
													WHERE a*100 + b*10 + c < DayOfYear(CONCAT(y, '1231')) ) a ) a 
										WHERE ym = '$YYYYMM'
							GROUP BY ym, d, dw ";
		} else {

			$query = "SELECT ym AS CAL_DATE, DW, ym AS CAL_YYYYMM,

											(SELECT COUNT(M_NO) AS IN_COUNT
												 FROM TBL_MEMBER
												 WHERE left(M_OPEN_DATE,7) = ym
													AND IFNULL(M_OPEN_DATE, '') <> '' ".$str_where. "
											) AS IN_COUNT,

											(SELECT COUNT(M_NO) AS OUT_COUNT
												 FROM TBL_MEMBER
												 WHERE left(M_LEAVE_DATE,6) = REPLACE(ym,'-','')
													AND IFNULL(M_LEAVE_DATE, '') != '' ".$str_where. "
											) AS OUT_COUNT

									FROM (SELECT date_format(dt,'%Y-%m') YM, Week(dt) W, right(CONCAT('0',Day(dt)),2) D, 
															 DayofWeek(dt) DW FROM (SELECT CONCAT(y, '0101') + INTERVAL a*100 + b*10 + c DAY dt 
												 FROM (SELECT 0 a 
																			UNION ALL SELECT 1 
																			UNION ALL SELECT 2 
																			UNION ALL SELECT 3 ) a, 
															(SELECT 0 b 
																			UNION ALL SELECT 1 
																			UNION ALL SELECT 2 
																			UNION ALL SELECT 3 
																			UNION ALL SELECT 4 
																			UNION ALL SELECT 5 
																			UNION ALL SELECT 6 
																			UNION ALL SELECT 7 
																			UNION ALL SELECT 8 
																			UNION ALL SELECT 9 ) b, 
															(SELECT 0 c 
																			UNION ALL SELECT 1 
																			UNION ALL SELECT 2 
																			UNION ALL SELECT 3 
																			UNION ALL SELECT 4 
																			UNION ALL SELECT 5 
																			UNION ALL SELECT 6 
																			UNION ALL SELECT 7 
																			UNION ALL SELECT 8 
																			UNION ALL SELECT 9 ) c, 
															(SELECT '$yyyy' y) d 
													WHERE a*100 + b*10 + c < DayOfYear(CONCAT(y, '1231')) ) a ) a 
							GROUP BY ym ORDER BY ym";

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
?>