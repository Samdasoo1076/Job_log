<?
# =============================================================================
# File Name    : reservation.php
# Modlue       : 
# Table        : TBL_RESERVATION
# Writer       : Seo Hyun Seok 
# Create Date  : 2024-12-09
# Modify Date  : 

#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================

#=====================================================================
# Table        : TBL_RESERVATION START
#=====================================================================
/* TBL_RESERVATION - DDL 수정 2024-12-14 R_NO - 생성된 예약번호 추가
CREATE TABLE `TBL_RESERVATION` ( 
    `RV_NO` 			int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '예약번호',
    `M_NO` 			int(11) NOT NULL  COMMENT '회원 번호', -- 멤버 테이블 참조 
    `ROOM_NO`			int(11)	NOT NULL  COMMENT '시설 번호', -- 시설 테이블 참조
    `R_NO`              VARCHAR(50) NOT NULL  COMMENT '생성된 예약번호',
    `RV_PURPOSE` 		VARCHAR(100) DEFAULT NULL COMMENT  '사용 목적',
    `RV_DATE` 			VARCHAR(50) DEFAULT NULL COMMENT  '예약 날짜 YYYY-MM-DD',
    `RV_USE_DATE`      VARCHAR(50) default null COMMENT  '시설 방문 날짜',
    `RV_USE_TIME`      VARCHAR(50) default null COMMENT  '시설 이용 시간',
    `RV_START_TIME`    VARCHAR(100) DEFAULT NULL COMMENT  '시작 시간 HH24:MI',
    `RV_END_TIME`    	VARCHAR(100) DEFAULT NULL COMMENT  '종료 시간 HH24:MI',
    `RV_EQUIPMENT` 	VARCHAR(100) DEFAULT NULL COMMENT '시설대여기자재',
    `RV_USE_COUNT`     int(11) default null COMMENT '사용 인원',
    `RV_COST`          VARCHAR(100) DEFAULT NULL COMMENT '이용료', 		
    `RV_REDUCTION` 	char(1) NOT NULL DEFAULT 'N' COMMENT '감면 대상 사유',
    `RV_MEMO`			VARCHAR(2000) default null COMMENT '예약관련 메모',
    `RV_REDUCTION_TF` 	char(1) NOT NULL DEFAULT 'N' COMMENT '감면 대상(Y) 비대상(Y)',
    `RV_AGREE_TF` 		char(1) NOT NULL DEFAULT 'Y' COMMENT '예약 여부 승인(Y), 미승인(N)',
    `DISP_SEQ` int(11) DEFAULT NULL COMMENT '순서',
    `USE_TF` char(1) NOT NULL DEFAULT 'Y' COMMENT '사용 여부 사용(Y),사용안함(N)',
    `DEL_TF` char(1) NOT NULL DEFAULT 'N' COMMENT '삭제 여부 삭제(Y),사용(N)',
    `REG_ADM` int(11) unsigned DEFAULT NULL COMMENT '등록 관리자 일련번호 TBL_ADMIN ADM_NO',
    `REG_DATE` datetime DEFAULT NULL COMMENT '등록일',
    `UP_ADM` int(11) unsigned DEFAULT NULL COMMENT '수정 관리자 일련번호 TBL_ADMIN ADM_NO',
    `UP_DATE` datetime DEFAULT NULL COMMENT '수정일',
    `DEL_ADM` int(11) unsigned DEFAULT NULL COMMENT '삭제 관리자 일련번호 TBL_ADMIN ADM_NO',
    `DEL_DATE` datetime DEFAULT NULL COMMENT '삭제일',
     PRIMARY KEY (`RV_NO`)
    )ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
*/
#=========================================================================================================
# End Table
#=========================================================================================================

// 예약 등록.
function insertReservation($db, $arr_data)
{
    $set_field = "";
    $set_value = "";
    $first = "Y";

    foreach ($arr_data as $key => $value) {
        $value = str_replace("'", "''", $value);

        if ($first == "Y") {
            $set_field .= $key;
            $set_value .= "'" . $value . "'";
            $first = "N";
        } else {
            $set_field .= "," . $key;
            $set_value .= ",'" . $value . "'";
        }
    }

    // 예약번호 생성 함수 호출
    $new_rv_no = generateReservationNo($db, $arr_data);

    // R_NO 추가 필드 설정
    $set_field = "R_NO, " . $set_field;
    $set_value = "'$new_rv_no', " . $set_value;

    // INSERT 쿼리 작성
    $query = "INSERT INTO TBL_RESERVATION ($set_field, REG_DATE, UP_DATE, RV_AGREE_TF)
              VALUES ($set_value, NOW(), NOW(), 0);";

    if (!mysqli_query($db, $query)) {
        echo "<script>alert(\"[1] 오류가 발생하였습니다 - " . mysqli_error($db) . "\");</script>";
        exit;
    } else {
        return $new_rv_no; // 변경된 예약번호 반환
    }
}



function ReservationStatus($db, $rv_date)
{
    $query = "
        SELECT 
            R.RV_NO,
            R.ROOM_NO,
            M.ROOM_NAME,  
            R.RV_PURPOSE,
            R.RV_DATE,
            R.RV_START_TIME,
            R.RV_END_TIME,
            R.RV_EQUIPMENT,
            M.ROOM_CAPACITY,
            M.ROOM_SCALE,
            R.RV_USE_COUNT,
            R.RV_COST,
            R.RV_REDUCTION,
            R.RV_MEMO,
            R.RV_REDUCTION_TF,
            R.RV_AGREE_TF
        FROM 
            TBL_RESERVATION R
        JOIN 
            TBL_MEETING_ROOM M ON R.ROOM_NO = M.ROOM_NO
        WHERE 
            R.RV_DATE = ?
			AND R.RV_AGREE_TF IN (0, 1)
            AND R.USE_TF = 'Y'
            AND R.DEL_TF = 'N'
            AND M.USE_TF = 'Y'  
            AND M.DEL_TF = 'N'  
        ORDER BY 
            M.ROOM_NAME ASC, R.RV_DATE DESC, R.RV_START_TIME ASC;
    ";

    $stmt = mysqli_prepare($db, $query);
    if (!$stmt) {
        echo "<script>alert('DB 오류: 준비된 문장을 생성할 수 없습니다. - " . mysqli_error($db) . "');</script>";
        return false;
    }

    mysqli_stmt_bind_param($stmt, "s", $rv_date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $arr_rs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $arr_rs[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $arr_rs;
}





function listReservations($db, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $nListCnt)
{

    $offset = $nRowCount * ($nPage - 1);
    if ($offset < 0)
        $offset = 0;

    $query = "SET @rownum = " . $offset . ";";
    mysqli_query($db, $query);

    $query = "SELECT
    @rownum := @rownum + 1 AS rn, 
    r.RV_NO,
    m.M_ID,
    rm.ROOM_NAME,
    r.R_NO,
    r.RV_PURPOSE,
    r.RV_DATE,
    r.RV_START_TIME,
    r.RV_END_TIME,
    r.RV_EQUIPMENT,
    r.RV_USE_COUNT,
    r.RV_COST,
    r.RV_MEMO,
    r.RV_AGREE_TF
FROM
    TBL_RESERVATION r
JOIN
    TBL_MEMBER m ON r.M_NO = m.M_NO
JOIN
    TBL_MEETING_ROOM rm ON r.ROOM_NO = rm.ROOM_NO
WHERE
    r.DEL_TF = 'N'";

    if ($use_tf <> "") {
        $query .= " AND r.USE_TF = '" . $use_tf . "' ";
    }

    if ($del_tf <> "") {
        $query .= " AND r.DEL_TF = '" . $del_tf . "' ";
    }

    if ($search_str <> "") {
        $query .= " AND " . $search_field . " like '%" . $search_str . "%' ";
    }

    $query .= " ORDER BY r.USE_TF DESC, r.DISP_SEQ ASC, r.RV_DATE DESC limit " . $offset . ", " . $nRowCount; //limit ".$offset.", ".$nRowCount;

    $result = mysqli_query($db, $query);
    $record = array();

    if ($result != "") {
        for ($i = 0; $i < mysqli_num_rows($result); $i++) {
            $record[$i] = sql_result_array($db, $result, $i);
        }
    }

    return $record;
}

function totalCntReservationAdmin($db, $m_no, $use_tf, $del_tf, $search_field, $search_str)
{
    $query = "
        SELECT COUNT(*)
        FROM TBL_RESERVATION tr
        LEFT JOIN TBL_MEETING_ROOM tmr
            ON tr.ROOM_NO = tmr.ROOM_NO
        WHERE 1 = 1
        AND tr.USE_TF = 'Y'
        AND tr.DEL_TF = 'N'
    ";

    if ($m_no <> "") {
        $query .= " AND tr.M_NO = '" . $m_no . "' ";
    }

    if ($use_tf <> "") {
        $query .= " AND tr.USE_TF = '" . $use_tf . "' ";
    }

    if ($del_tf <> "") {
        $query .= " AND tr.DEL_TF = '" . $del_tf . "' ";
    }

    if ($search_str <> "") {
        if ($search_field == "ALL") {
            $query .= " AND (tmr.ROOM_NAME LIKE '%" . $search_str . "%' 
                        OR tr.RV_DATE LIKE '%" . $search_str . "%')";
        } elseif ($search_field == "RV_AGREE_TF") {
            switch ($search_str) {
                case "승인대기":
                    $query .= " AND tr.RV_AGREE_TF = '0' ";
                    break;
                case "승인":
                    $query .= " AND tr.RV_AGREE_TF = '1' ";
                    break;
                case "미승인":
                    $query .= " AND tr.RV_AGREE_TF = '2' ";
                    break;
                case "취소":
                    $query .= " AND tr.RV_AGREE_TF = '3' ";
                    break;
                case "회원취소":
                    $query .= " AND tr.RV_AGREE_TF = '4' ";
                    break;
                default:
                    break;
            }
        } else {
            $query .= " AND " . $search_field . " LIKE '%" . $search_str . "%' ";
        }
    }

    $result = mysqli_query($db, $query);
    if ($result) {
        $rows = mysqli_fetch_array($result);
        return $rows[0]; 
    }
    return 0; 
}



function totalCntReservation($db, $m_no, $use_tf, $del_tf, $search_field, $search_str, $search_period)
{
    $query = "SELECT COUNT(*) FROM TBL_RESERVATION WHERE 1 = 1
              AND USE_TF = 'Y'
              AND DEL_TF = 'N'";

    if ($m_no <> "") {
        $query .= " AND M_NO = '" . $m_no . "' ";
    }

    if ($use_tf <> "") {
        $query .= " AND USE_TF = '" . $use_tf . "' ";
    }

    if ($del_tf <> "") {
        $query .= " AND DEL_TF = '" . $del_tf . "' ";
    }

    if ($search_str <> "") {
        $query .= " AND " . $search_field . " LIKE '%" . $search_str . "%' ";
    }

    // 조회기간 조건 추가
    if ($search_period <> "") {
        $period_condition = "";
        if ($search_period == "6M") {
            $period_condition = "DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND REG_DATE <= CURDATE()";
        } elseif ($search_period == "12M") {
            $period_condition = "DATE_SUB(CURDATE(), INTERVAL 12 MONTH) AND REG_DATE <= CURDATE()";
        } elseif ($search_period == "24M") {
            $period_condition = "DATE_SUB(CURDATE(), INTERVAL 24 MONTH) AND REG_DATE <= CURDATE()";
        }
    }
    $result = mysqli_query($db, $query);
    if ($result) {
        $rows = mysqli_fetch_array($result);
        return $rows[0];  // Return the count
    }
    return 0; // Return 0 if the query fails
}

function listReservations2($db, $m_no, $use_tf, $del_tf, $search_field, $search_str, $search_period, $nPage, $nRowCount, $nListCnt)
{
    $offset = $nRowCount * ($nPage - 1);
    if ($offset < 0) {
        $offset = 0;
    }

    $query = "SET @rownum = " . $offset . ";";
    mysqli_query($db, $query);

    $query = "SELECT
                        @rownum := @rownum + 1 AS rn, 
                        tr.RV_NO,tr.R_NO, tr.M_NO, tr.ROOM_NO, tr.RV_PURPOSE, tr.RV_DATE, 
                        tr.RV_START_TIME, tr.RV_END_TIME, tr.RV_EQUIPMENT, 
                        tr.RV_USE_COUNT, tr.RV_COST, tr.RV_REDUCTION, tr.RV_MEMO, 
                        tr.RV_REDUCTION_TF, tr.RV_AGREE_TF, tr.DISP_SEQ, 
                        tr.USE_TF, tr.DEL_TF, tr.REG_ADM, tr.REG_DATE AS RV_REG_DATE,  
                        tr.UP_ADM, tr.UP_DATE AS RV_UP_DATE, tr.DEL_ADM, tr.DEL_DATE, 
                        tmr.*,
                        tm.*
                    FROM TBL_RESERVATION tr
                    LEFT JOIN TBL_MEETING_ROOM tmr 
                        ON tr.ROOM_NO = tmr.ROOM_NO
                    LEFT JOIN TBL_MEMBER tm
                        ON tr.M_NO = tm.M_NO
                    WHERE 1=1
                    AND tr.USE_TF = 'Y'
                    AND tr.DEL_TF = 'N'";

    if ($m_no <> "") {
        $query .= " AND tr.M_NO = '" . $m_no . "' ";
    }

    if ($use_tf <> "") {
        $query .= " AND tr.USE_TF = '" . $use_tf . "' ";
    }

    if ($del_tf <> "") {
        $query .= " AND tr.DEL_TF = '" . $del_tf . "' ";
    }


    if ($search_str <> "") {
        if ($search_field == "ALL") {
            $query .= " AND (tmr.ROOM_NAME LIKE '%" . $search_str . "%' 
                        OR tr.RV_DATE LIKE '%" . $search_str . "%')";
        } elseif ($search_field == "RV_AGREE_TF") {
            switch ($search_str) {
                case "승인대기":
                    $query .= " AND tr.RV_AGREE_TF = '0' ";
                    break;
                case "승인":
                    $query .= " AND tr.RV_AGREE_TF = '1' ";
                    break;
                case "미승인":
                    $query .= " AND tr.RV_AGREE_TF = '2' ";
                    break;
                case "취소":
                    $query .= " AND tr.RV_AGREE_TF = '3' ";
                    break;
                case "회원취소":
                    $query .= " AND tr.RV_AGREE_TF = '4' ";
                    break;
                default:
                    break;
            }
        } else {
            $query .= " AND " . $search_field . " LIKE '%" . $search_str . "%' ";
        }
    }
    

    // 조회기간 조건 추가
    if ($search_period <> "") {
        $period_condition = "";
        if ($search_period == "6M") {
            $period_condition = "DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND tr.REG_DATE < CURDATE()";
        } elseif ($search_period == "12M") {
            $period_condition = "DATE_SUB(CURDATE(), INTERVAL 12 MONTH) AND tr.REG_DATE < CURDATE()";
        } elseif ($search_period == "24M") {
            $period_condition = "DATE_SUB(CURDATE(), INTERVAL 24 MONTH) AND tr.REG_DATE < CURDATE()";
        }
    }

    $query .= " ORDER BY tr.REG_DATE DESC, tr.RV_DATE DESC 
                    LIMIT " . $offset . ", " . $nRowCount;

    $result = mysqli_query($db, $query);
    $record = array();

    if ($result != "") {
        for ($i = 0; $i < mysqli_num_rows($result); $i++) {
            $record[$i] = mysqli_fetch_assoc($result);
        }
    }

    return $record;
}


function getReservationsByMember($db, $m_no, $rv_no)
{
    $query = "SELECT 
                    TR.RV_NO, TR.R_NO, TR.M_NO, TR.ROOM_NO, TR.RV_PURPOSE, TR.RV_DATE, TR.RV_START_TIME, TR.RV_END_TIME, 
                    TR.RV_EQUIPMENT, TR.RV_USE_COUNT, TR.RV_COST, TR.RV_REDUCTION, TR.RV_MEMO, TR.RV_REDUCTION_TF, 
                    TR.RV_AGREE_TF, TR.DISP_SEQ, TR.USE_TF, TR.DEL_TF, TR.REG_ADM, TR.REG_DATE, TR.UP_ADM, TR.UP_DATE, 
                    TR.DEL_ADM, TR.DEL_DATE, TM.M_PHONE, TM.M_ID, TMR.ROOM_NAME
                FROM TBL_RESERVATION TR
                INNER JOIN TBL_MEMBER TM 
                    ON TR.M_NO = TM.M_NO
                LEFT JOIN TBL_MEETING_ROOM TMR
                    ON TR.ROOM_NO = TMR.ROOM_NO
                WHERE 1=1
                AND TR.USE_TF = 'Y'
                AND TR.DEL_TF = 'N'";

    if ($m_no <> "") {
        $query .= " AND TR.M_NO = '" . $m_no . "' ";
    }

    if ($rv_no <> "") {
        $query .= " AND TR.RV_NO = '" . $rv_no . "' ";
    }
    $result = mysqli_query($db, $query);
    $record = array();

    if ($result != "") {
        for ($i = 0; $i < mysqli_num_rows($result); $i++) {
            $record[$i] = sql_result_array($db, $result, $i);
        }
    }

    return $record;
}

function updateAgreeTF($db, $rv_agree_tf, $up_adm, $rv_no)
{

    $query = "UPDATE TBL_RESERVATION SET 
							RV_AGREE_TF			= '$rv_agree_tf',
							UP_ADM			= '$up_adm',
							UP_DATE			= now()
				 WHERE RV_NO			= '$rv_no' ";

    //echo $query;

    if (!mysqli_query($db, $query)) {
        return false;
        echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
        exit;
    } else {
        return true;
    }
}

function updateReservationCancle($conn, $rv_no, $m_no)
{
    $query = "UPDATE TBL_RESERVATION SET 
                RV_AGREE_TF = '4',
                UP_DATE = now(),
                DEL_DATE = now()
            WHERE RV_NO = '$rv_no' 
                AND M_NO = '$m_no'";
    error_log("----------------------------------------------: " . $query);
    if (!mysqli_query($conn, $query)) {
        echo "<script>alert('[1]오류가 발생하였습니다 - " . mysqli_error($conn) . "');</script>";
        return false;
    } else {
        return true;
    }
}


function getAbleDateTime($db, $date, $room_no)
{

    $query = "select B.DCODE, B.DCODE_NM,
									(select COUNT(*) from TBL_RESERVATION where RV_DATE = '" . $date . "' and ROOM_NO = '" . $room_no . "' and RV_START_TIME = B.DCODE AND DEL_TF = 'N' AND RV_AGREE_TF IN ('0','1','2') AND DEL_TF = 'N' and USE_TF = 'Y' ) +
									(select COUNT(*) from TBL_MEETING_ROOM_DISABLE where DISABLE_DATE = '" . $date . "' and ROOM_NO = '" . $room_no . "' and DISABLE_TIME = B.DCODE ) as RESERVATION_FLAG
						 from TBL_MEETING_ROOM A, TBL_CODE_DETAIL B
						where A.USE_TIME = B.PCODE 
							and A.ROOM_NO = '" . $room_no . "'
							and A.DEL_TF = 'N'
							and A.USE_TF = 'Y'
							and B.DEL_TF = 'N'
							and B.USE_TF = 'Y'
						order by B.DCODE_SEQ_NO ";

    $result = mysqli_query($db, $query);
    $record = array();

    if ($result != "") {
        for ($i = 0; $i < mysqli_num_rows($result); $i++) {
            $record[$i] = sql_result_array($db, $result, $i);
        }
    }
    return $record;
}


function getReservationDetails($conn, $rv_no)
{
	$query = "
		SELECT R.RV_NO, R.R_NO, M.M_ID,
					 DATE_FORMAT(R.REG_DATE, '%Y-%m-%d / %H:%I:%S') AS REG_DATE,
					 DATE_FORMAT(R.UP_DATE, '%Y-%m-%d / %H:%I:%S')AS UP_DATE,
					 R.RV_AGREE_TF, M.M_PHONE, RM.ROOM_NAME, R.RV_USE_COUNT, R.RV_PURPOSE, R.RV_DATE, 
					 R.RV_START_TIME, R.RV_END_TIME, R.RV_REDUCTION, R.RV_EQUIPMENT,
					 R.RV_MEMO, R.RV_COST
		 FROM TBL_RESERVATION AS R
					JOIN TBL_MEMBER AS M ON R.M_NO = M.M_NO
					JOIN TBL_MEETING_ROOM AS RM ON R.ROOM_NO = RM.ROOM_NO
		WHERE R.RV_NO = ? ";

    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        echo "DB Error: " . mysqli_error($conn);
        return null;
    }

    mysqli_stmt_bind_param($stmt, "i", $rv_no);  // 예약 번호를 바인딩
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $reservationDetails = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);
    return $reservationDetails;
}

function updateReservationAgree($conn, $rv_no, $rv_agree_tf)
{
    $query = "UPDATE TBL_RESERVATION SET RV_AGREE_TF = ?, UP_DATE = NOW() WHERE RV_NO = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        // 쿼리 준비 실패 시 오류 메시지 출력
        error_log("Prepare failed: " . $conn->error);
        return false;
    }

    // RV_NO의 타입에 따라 'si' 또는 'ss'로 설정
    $stmt->bind_param("ss", $rv_agree_tf, $rv_no);

    if (!$stmt->execute()) {
        // 쿼리 실행 실패 시 오류 메시지 출력
        error_log("Execute failed: " . $stmt->error);
        $stmt->close();
        return false;
    }

    $stmt->close();
    return true;
}

function deleteReservationTF($db, $del_adm, $r_no)
{
    $query = "UPDATE TBL_RESERVATION SET
						DEL_TF				= 'Y',
						DEL_ADM				= '$del_adm',
						DEL_DATE			= now()
					WHERE 
                        R_NO				= '$r_no'";

    if (!mysqli_query($db, $query)) {
        return false;
        echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
        exit;
    } else {
        return true;
    }
}

function updateReservationMemo($db, $r_no, $rv_memo) {
	
	$query = "UPDATE TBL_RESERVATION SET RV_MEMO = '$rv_memo' WHERE R_NO = '$r_no'";

	if (!mysqli_query($db, $query)) {
		return false;
	} else {
		return true;
	}

}

function chkDupReservation ($db, $room_no, $rv_date, $usetime) {

	$query ="SELECT COUNT(*) CNT FROM TBL_RESERVATION WHERE DEL_TF = 'N' AND RV_AGREE_TF IN ('0','1','2') ";

	if ($room_no <> "") {
		$query .= " AND ROOM_NO = '".$room_no."' ";
	}

	if ($rv_date <> "") {
		$query .= " AND RV_DATE = '".$rv_date."' ";
	}
	
	if ($usetime <> "") {
		$query .= " AND RV_START_TIME = '".$usetime."' ";
	}

	$result = mysqli_query($db, $query);
	$rows   = mysqli_fetch_array($result);
	$record  = $rows[0];
	return $record;
}

?>