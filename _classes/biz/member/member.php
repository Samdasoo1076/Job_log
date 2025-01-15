<?
# =============================================================================
# File Name    : member.php
# Modlue       : 
# Table        : TBL_MEMBER
# Writer       : Seo Hyun Seok  Lee Ji Min
# Create Date  : 2024-11-13
# Modify Date  : 

#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
# =============================================================================


#=====================================================================
# Table        : TBL_MEMBER START
#=====================================================================
/*
    CREATE TABLE `TBL_MEMBER` (
    `M_NO` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '회원 번호',
    `M_ID` varchar(50) NOT NULL COMMENT '회원 아이디',
    `M_PWD` varchar(50) NOT NULL COMMENT '회원 비밀번호',
    `M_PHONE` varchar(50) DEFAULT NULL COMMENT '회원 전화번호',
    `M_EMAIL` varchar(100) DEFAULT NULL COMMENT '회원 이메일 주소',
    `M_GUBUN` char(1) NOT NULL DEFAULT 'P' COMMENT '회원 구분 (예: 개인(P), 기업(C))',
    `M_ADDR` varchar(100) DEFAULT NULL COMMENT '회원 주소',
    `M_POST_CD` varchar(50) DEFAULT NULL COMMENT '우편번호',
    `M_ADDR_DETAIL` varchar(100) DEFAULT NULL COMMENT '회원 주소 상세',
    `M_BIZ_NO` varchar(30) DEFAULT NULL COMMENT '사업자 등록번호 (기업회원)',
    `M_KSIC` varchar(100) DEFAULT NULL COMMENT '한국 산업 표준 분류 코드 (KSIC)',
    `M_KSIC_DETAIL` varchar(100) DEFAULT NULL COMMENT '한국 산업 표준 분류 상세 코드 (KSIC_DETAIL)',
    `EMAIL_TF` char(1) NOT NULL DEFAULT 'N' COMMENT '이메일 수신여부 사용(Y),사용안함(N)',
    `MESSAGE_TF` char(1) NOT NULL DEFAULT 'N' COMMENT '문자 사용 여부 사용(Y),사용안함(N)',
    `DISP_SEQ` int(11) DEFAULT NULL COMMENT '순서',
    `USE_TF` char(1) NOT NULL DEFAULT 'Y' COMMENT '사용 여부 사용(Y),사용안함(N)',
    `DEL_TF` char(1) NOT NULL DEFAULT 'N' COMMENT '삭제 여부 삭제(Y),사용(N)',
    `REG_ADM` int(11) unsigned DEFAULT NULL COMMENT '등록 관리자 일련번호 TBL_ADMIN ADM_NO',
    `REG_DATE` datetime DEFAULT NULL COMMENT '등록일',
    `UP_ADM` int(11) unsigned DEFAULT NULL COMMENT '수정 관리자 일련번호 TBL_ADMIN ADM_NO',
    `UP_DATE` datetime DEFAULT NULL COMMENT '수정일',
    `DEL_ADM` int(11) unsigned DEFAULT NULL COMMENT '삭제 관리자 일련번호 TBL_ADMIN ADM_NO',
    `DEL_DATE` datetime DEFAULT NULL COMMENT '삭제일',
    `M_NAME` varchar(50) NOT NULL COMMENT '회원 이름',
    `M_ORGAN_NAME` varchar(50) NOT NULL COMMENT '기관명',
    PRIMARY KEY (`M_NO`)
    ) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
*/

#=========================================================================================================
# End Table
#=========================================================================================================

function listMember($conn, $m_gubun, $m_ksic, $email_tf, $message_tf, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $nListCnt)
{

    $offset = $nRowCount * ($nPage - 1);
    if ($offset < 0)
        $offset = 0;

    $query = "SET @rownum = " . $offset . ";";
    mysqli_query($conn, $query);

    $query = "SELECT 
                    @rownum := @rownum + 1 AS rn, 
                    M_NO, M_ID, M_NAME, M_PWD, M_PHONE, M_EMAIL, M_GUBUN, M_ORGAN_NAME, M_ADDR, M_POST_CD, M_ADDR_DETAIL, M_BIZ_NO, M_KSIC,
                    M_KSIC_DETAIL, EMAIL_TF, MESSAGE_TF, DISP_SEQ,
                    USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
                  FROM TBL_MEMBER
                  WHERE 1 = 1";

    if ($m_gubun != "") {
        $query .= " AND M_GUBUN = '" . $m_gubun . "' ";
    }

    if ($m_ksic != "") {
        $query .= " AND M_KSIC = '" . $m_ksic . "' ";
    }

    if ($email_tf != "") {
        $query .= " AND EMAIL_TF = '" . $email_tf . "' ";
    }

    if ($message_tf != "") {
        $query .= " AND MESSAGE_TF = '" . $message_tf . "' ";
    }

    if ($use_tf != "") {
        $query .= " AND USE_TF = '" . $use_tf . "' ";
    }

    if ($del_tf != "") {
        $query .= " AND DEL_TF = '" . $del_tf . "' ";
    }

    if ($search_str != "") {
        if ($search_field == "ALL") {
            $query .= " AND (
                        (M_GUBUN LIKE '%" . $search_str . "%') OR 
                        (M_KSIC LIKE '%" . $search_str . "%') OR 
                        (M_PHONE LIKE '%" . $search_str . "%')
                    )";
        } else {
            if ($search_field == "M_PHONE") {
                $decryptedPhone = decrypt($key, $iv, $search_str);
                $query .= " AND M_PHONE LIKE '%" . $decryptedPhone . "%' ";
            } else {
                $query .= " AND " . $search_field . " LIKE '%" . $search_str . "%' ";
            }
        }
    }


    $query .= " ORDER BY USE_TF DESC, DISP_SEQ ASC, M_NO DESC limit " . $offset . ", " . $nRowCount; //limit ".$offset.", ".$nRowCount;

    $result = mysqli_query($conn, $query);
    $record = array();

    if ($result != "") {
        for ($i = 0; $i < mysqli_num_rows($result); $i++) {
            $record[$i] = sql_result_array($conn, $result, $i);
        }
    }

    return $record;
}

function totalCntMembers($conn, $m_no, $m_gubun, $m_ksic, $email_tf, $message_tf, $use_tf, $del_tf, $search_field, $search_str)
{

    $query = "SELECT COUNT(*) FROM TBL_MEMBER WHERE 1 = 1";

    if ($m_gubun != "") {
        $query .= " AND M_GUBUN = '" . $m_gubun . "' ";
    }

    if ($m_ksic != "") {
        $query .= " AND M_KSIC = '" . $m_ksic . "' ";
    }

    if ($email_tf != "") {
        $query .= " AND EMAIL_TF = '" . $email_tf . "' ";
    }

    if ($message_tf != "") {
        $query .= " AND MESSAGE_TF = '" . $message_tf . "' ";
    }

    if ($use_tf != "") {
        $query .= " AND USE_TF = '" . $use_tf . "' ";
    }

    if ($del_tf != "") {
        $query .= " AND DEL_TF = '" . $del_tf . "' ";
    }

    if ($search_str != "") {
        if ($search_field == "ALL") {
            $query .= " AND (
                        (M_GUBUN LIKE '%" . $search_str . "%') OR 
                        (M_KSIC LIKE '%" . $search_str . "%') OR 
                        (M_PHONE LIKE '%" . $search_str . "%')
                    )";
        } else {
            if ($search_field == "M_PHONE") { 
                $decryptedPhone = decrypt($key, $iv, $search_str);
                $query .= " AND M_PHONE LIKE '%" . $decryptedPhone . "%' ";
            } else {
                $query .= " AND " . $search_field . " LIKE '%" . $search_str . "%' ";
            }
        }
    }

    $result = mysqli_query($conn, $query);
    $rows = mysqli_fetch_array($result);

    $record = $rows[0];
    return $record;
}

function updateMemberUseTF($db, $use_tf, $up_adm, $m_no)
{

    $query = "UPDATE TBL_MEMBER SET 
							USE_TF			= '$use_tf',
							UP_ADM			= '$up_adm',
							UP_DATE			= now()
				 WHERE M_NO			= '$m_no' ";

    //echo $query;

    if (!mysqli_query($db, $query)) {
        return false;
        echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
        exit;
    } else {
        return true;
    }
}


function insertMember($db, $arr_data)
{
    // 멤버 등록
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

    $query = "SELECT IFNULL(MAX(M_NO),0) + 1 AS MAX_NO FROM TBL_MEMBER";
    $result = mysqli_query($db, $query);
    $rows = mysqli_fetch_array($result);

    $new_m_no = $rows['MAX_NO'];

    $query = "INSERT INTO TBL_MEMBER (M_NO, " . $set_field . ", REG_DATE, UP_DATE) 
                    VALUES ($new_m_no, " . $set_value . ", now(), now());";

    if (!mysqli_query($db, $query)) {
        echo "<script>alert(\"[1] 오류가 발생하였습니다 - \");</script>";
        exit;
    } else {
        return $new_m_no;
    }
}

// 아이디 중복체크 추가
function dupMemberIdChk($db, $m_id)
{

    $query = "SELECT COUNT(*) CNT FROM TBL_MEMBER WHERE DEL_TF = 'N' ";

    if ($m_id <> "") {
        $query .= " AND M_ID = '" . $m_id . "' ";
    }

    $result = mysqli_query($db, $query);
    $rows = mysqli_fetch_array($result);

    if ($rows[0] == 0) {
        return 0;
    } else {
        return 1;
    }
}

function updateMember($db, $arr_data, $m_no)
{

    $set_query_str = "";

    foreach ($arr_data as $key => $value) {
        $value = str_replace("'", "''", $value);
        $set_query_str .= $key . " = '" . $value . "',";
    }

    $query = "UPDATE TBL_MEMBER set " . $set_query_str . " ";
    $query .= "UP_DATE = now(), ";
    $query .= "M_NO = '$m_no' WHERE M_NO = '$m_no' ";

    if (!mysqli_query($db, $query)) {
        return false;
        echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
        exit;
    } else {
        return true;
    }

}


function deleteMemberTF($db, $del_adm, $m_no)
{

    $query = "UPDATE TBL_MEMBER SET
						DEL_TF				= 'Y',
						DEL_ADM				= '$del_adm',
						DEL_DATE			= now()
					WHERE 
                        M_NO				= '$m_no'";

    if (!mysqli_query($db, $query)) {
        return false;
        echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
        exit;
    } else {
        return true;
    }
}


function selectMember($db, $m_no)
{
    $query = "SELECT M_NO, M_ID, M_PWD, M_PHONE, M_EMAIL, M_GUBUN, M_ADDR, M_POST_CD, M_ADDR_DETAIL, 
                    M_BIZ_NO, M_KSIC, M_KSIC_DETAIL, EMAIL_TF, MESSAGE_TF, DISP_SEQ, USE_TF, DEL_TF, 
                    REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE, M_NAME, M_ORGAN_NAME
            FROM TBL_MEMBER 
            WHERE 1=1 AND USE_TF = 'Y' AND DEL_TF = 'N' AND m_no = '$m_no'";

    $result = mysqli_query($db, $query);
    $record = array();

    if ($result <> "") {
        for ($i = 0; $i < mysqli_num_rows($result); $i++) {
            $record[$i] = sql_result_array($db, $result, $i);
        }
    }
    return $record;
}


function selectKsicCodeDetail($db, $dcode_ext)
{
    $query = "SELECT
                    DCODE_NO, PCODE, DCODE, DCODE_NM, DCODE_EXT
		        FROM TBL_CODE_DETAIL
		        WHERE 1=1
		        AND PCODE = 'INDUSTRY_CATEGORY_DETAIL'
		        AND DCODE_EXT = '$dcode_ext'";

    $result = mysqli_query($db, $query);
    $record = array();

    if ($result <> "") {
        for ($i = 0; $i < mysqli_num_rows($result); $i++) {
            $record[$i] = sql_result_array($db, $result, $i);
        }
    }

    return $record;

}


function loginMember($db, $m_id)
{
    $query = "SELECT 
                    M_NO, 
                    M_ID, 
                    M_PWD, 
                    M_EMAIL, 
                    M_PHONE, 
                    M_GUBUN, 
                    M_ADDR, 
                    M_POST_CD, 
                    M_ADDR_DETAIL, 
                    USE_TF, 
                    DEL_TF, 
                    REG_ADM, 
                    REG_DATE, 
                    UP_ADM, 
                    UP_DATE, 
                    DEL_ADM, 
                    DEL_DATE
                  FROM TBL_MEMBER
                  WHERE 1=1 
                    AND M_ID = '$m_id'
                    AND USE_TF = 'Y'
                    AND DEL_TF = 'N'";

    $result = mysqli_query($db, $query);
    $record = array();

    if ($result && mysqli_num_rows($result) > 0) {
        for ($i = 0; $i < mysqli_num_rows($result); $i++) {
            $record[$i] = mysqli_fetch_assoc($result);
        }
    }

    return $record;
}


function mypageUserMember($db, $m_no)
{
    $query = "SELECT 
                    M_NO, 
                    M_ID,
                    M_NAME,
                    M_PWD,
                    M_EMAIL, 
                    M_PHONE,
                    M_GUBUN,
                    M_ORGAN_NAME,
                    M_ADDR, 
                    M_POST_CD, 
                    M_ADDR_DETAIL,
                    M_BIZ_NO, 
                    M_KSIC, 
                    M_KSIC_DETAIL, 
                    EMAIL_TF, 
                    MESSAGE_TF, 
                    DATE_FORMAT(REG_DATE, '%Y-%m-%d') AS REG_DATE,
                    USE_TF, 
                    DEL_TF 
                  FROM TBL_MEMBER 
                  WHERE 1=1 
                    AND M_NO = '" . mysqli_real_escape_string($db, $m_no) . "' 
                    AND USE_TF = 'Y' 
                    AND DEL_TF = 'N'";

    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result); // 단일 행 반환
    }

    return null; // 데이터가 없으면 null 반환
}

function findUserid($db, $phone)
{
    // 입력 값 정리
    $phone = trim($phone); // 공백 제거
    $phone = str_replace('-', '', $phone); // 사용자 입력 값에서 '-' 제거

    

    $query = "SELECT 
                    M_ID 
                  FROM TBL_MEMBER 
                  WHERE 1=1 
                    AND REPLACE(M_PHONE, '-', '') = '" . mysqli_real_escape_string($db, $phone) . "' 
                    AND DEL_TF = 'N'";

    // 디버깅: 실행된 쿼리 확인
    echo "DEBUG: Query = " . $query . "<br>";

    $result = mysqli_query($db, $query);
    

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result); // 단일 행 반환
    }

    return null; // 데이터가 없으면 null 반환
}

function findUserpw($db, $id, $phone)
{
    // SQL 쿼리 작성 (Prepared Statement)
    $query = "SELECT M_ID FROM TBL_MEMBER WHERE M_ID = ? AND REPLACE(M_PHONE, '-', '') = ? AND DEL_TF = 'N'";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $id, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    // 결과 반환
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}


function changeUserPassword($db, $m_id, $encrypted_formattedPhone, $encrypted_password)
{

    $encrypted_formattedPhone = str_replace('-', '', $encrypted_formattedPhone);
    // SQL 쿼리 작성
    $query = "UPDATE TBL_MEMBER 
                  SET M_PWD = '" . mysqli_real_escape_string($db, $encrypted_password) . "' 
                  WHERE M_ID = '" . mysqli_real_escape_string($db, $m_id) . "' 
                  AND REPLACE(M_PHONE, '-', '') = '" . mysqli_real_escape_string($db, $encrypted_formattedPhone) . "' 
                  AND DEL_TF = 'N'";

    error_log("SQL Query: " . $query);

    $result = mysqli_query($db, $query);

    // 결과 반환
    if ($result) {
        return true;
    } else {
        error_log("SQL Error: " . mysqli_error($db));
        return false;
    }
}

function changeUserEmail($db, $m_no, $m_id, $m_email)
{
    // SQL 쿼리 작성
    $query = "UPDATE TBL_MEMBER 
                  SET M_EMAIL = '" . mysqli_real_escape_string($db, $m_email) . "' 
                  WHERE 1=1 
                    AND  M_NO = '" . mysqli_real_escape_string($db, $m_no) . "' 
                    AND  M_ID = '" . mysqli_real_escape_string($db, $m_id) . "' 
                    AND DEL_TF = 'N'";

    error_log("SQL Query: " . $query);

    $result = mysqli_query($db, $query);

    // 업데이트 실패 시 false 반환
    if (!$result) {
        error_log("SQL Error (UPDATE): " . mysqli_error($db));
        return false;
    }

    $select_query = "SELECT M_EMAIL FROM TBL_MEMBER 
                    WHERE M_NO = '" . mysqli_real_escape_string($db, $m_no) . "' 
                    AND M_ID = '" . mysqli_real_escape_string($db, $m_id) . "' 
                    AND DEL_TF = 'N'";

    error_log("SQL Query (SELECT): " . $select_query);

    $result = mysqli_query($db, $select_query);

    // 결과 처리
    if ($result) {
        $arr_rs = mysqli_fetch_assoc($result);
        return $arr_rs['M_EMAIL']; // 변경된 M_EMAIL 값 반환
    } else {
        error_log("SQL Error (SELECT): " . mysqli_error($db));
        return false;
    }
}

function changeUserPhone($db, $m_no, $m_id, $m_phone)
{
    // SQL 쿼리 작성
    $query = "UPDATE TBL_MEMBER 
                  SET M_PHONE = '" . mysqli_real_escape_string($db, $m_phone) . "' 
                  WHERE 1=1 
                    AND  M_NO = '" . mysqli_real_escape_string($db, $m_no) . "' 
                    AND  M_ID = '" . mysqli_real_escape_string($db, $m_id) . "' 
                    AND DEL_TF = 'N'";

    error_log("SQL Query: " . $query);

    $result = mysqli_query($db, $query);

    // 업데이트 실패 시 false 반환
    if (!$result) {
        error_log("SQL Error (UPDATE): " . mysqli_error($db));
        return false;
    }

    $select_query = "SELECT M_PHONE FROM TBL_MEMBER 
                    WHERE M_NO = '" . mysqli_real_escape_string($db, $m_no) . "' 
                    AND M_ID = '" . mysqli_real_escape_string($db, $m_id) . "' 
                    AND DEL_TF = 'N'";

    error_log("SQL Query (SELECT): " . $select_query);

    $result = mysqli_query($db, $select_query);

    // 결과 처리
    if ($result) {
        $arr_rs = mysqli_fetch_assoc($result);
        return $arr_rs['M_PHONE']; // 변경된 M_EMAIL 값 반환
    } else {
        error_log("SQL Error (SELECT): " . mysqli_error($db));
        return false;
    }
}

function changeUserPwd($db, $m_no, $m_id, $m_pwd)
{
    // SQL 쿼리 작성
    $query = "UPDATE TBL_MEMBER 
                  SET M_PWD = '" . mysqli_real_escape_string($db, $m_pwd) . "' 
                  WHERE 1=1 
                    AND  M_NO = '" . mysqli_real_escape_string($db, $m_no) . "' 
                    AND  M_ID = '" . mysqli_real_escape_string($db, $m_id) . "' 
                    AND DEL_TF = 'N'";

    error_log("SQL Query: " . $query);

    $result = mysqli_query($db, $query);

    // 업데이트 실패 시 false 반환
    if (!$result) {
        error_log("SQL Error (UPDATE): " . mysqli_error($db));
        return false;
    }

    $select_query = "SELECT M_PWD FROM TBL_MEMBER 
                    WHERE M_NO = '" . mysqli_real_escape_string($db, $m_no) . "' 
                    AND M_ID = '" . mysqli_real_escape_string($db, $m_id) . "' 
                    AND DEL_TF = 'N'";

    error_log("SQL Query (SELECT): " . $select_query);

    $result = mysqli_query($db, $select_query);

    // 결과 처리
    if ($result) {
        $arr_rs = mysqli_fetch_assoc($result);
        return $arr_rs['M_PWD']; // 변경된 M_EMAIL 값 반환
    } else {
        error_log("SQL Error (SELECT): " . mysqli_error($db));
        return false;
    }
}

    function changeUserBizNo($db, $m_no, $m_id, $m_biz_no)
    {
        // SQL 쿼리 작성
        $query = "UPDATE TBL_MEMBER 
                    SET M_BIZ_NO = '" . mysqli_real_escape_string($db, $m_biz_no) . "' 
                    WHERE 1=1 
                        AND  M_NO = '" . mysqli_real_escape_string($db, $m_no) . "' 
                        AND  M_ID = '" . mysqli_real_escape_string($db, $m_id) . "' 
                        AND DEL_TF = 'N'";

        error_log("SQL Query: " . $query);

        $result = mysqli_query($db, $query);

        // 업데이트 실패 시 false 반환
        if (!$result) {
            error_log("SQL Error (UPDATE): " . mysqli_error($db));
            return false;
        }

        $select_query = "SELECT M_BIZ_NO FROM TBL_MEMBER 
                        WHERE M_NO = '" . mysqli_real_escape_string($db, $m_no) . "' 
                        AND M_ID = '" . mysqli_real_escape_string($db, $m_id) . "' 
                        AND DEL_TF = 'N'";

        error_log("SQL Query (SELECT): " . $select_query);

        $result = mysqli_query($db, $select_query);

        // 결과 처리
        if ($result) {
            $arr_rs = mysqli_fetch_assoc($result);
            return $arr_rs['M_BIZ_NO']; // 변경된 M_EMAIL 값 반환
        } else {
            error_log("SQL Error (SELECT): " . mysqli_error($db));
            return false;
        }
    }

    function changeUserName($db, $m_no, $m_id, $m_name){
            // SQL 쿼리 작성
        $query = "UPDATE TBL_MEMBER 
                  SET M_NAME = '" . mysqli_real_escape_string($db, $m_name) . "' 
                  WHERE 1=1 
                  AND  M_NO = '" . mysqli_real_escape_string($db, $m_no) . "' 
                  AND  M_ID = '" . mysqli_real_escape_string($db, $m_id) . "' 
                  AND DEL_TF = 'N'";

        error_log("SQL Query: " . $query);

        $result = mysqli_query($db, $query);

        // 업데이트 실패 시 false 반환
        if (!$result) {
            error_log("SQL Error (UPDATE): " . mysqli_error($db));
            return false;
        }

        $select_query = "SELECT M_NAME FROM TBL_MEMBER 
                WHERE M_NO = '" . mysqli_real_escape_string($db, $m_no) . "' 
                AND M_ID = '" . mysqli_real_escape_string($db, $m_id) . "' 
                AND DEL_TF = 'N'";

        error_log("SQL Query (SELECT): " . $select_query);

        $result = mysqli_query($db, $select_query);

        // 결과 처리
        if ($result) {
            $arr_rs = mysqli_fetch_assoc($result);
            return $arr_rs['M_NAME']; // 변경된 M_NAME 값 반환
        } else {
            error_log("SQL Error (SELECT): " . mysqli_error($db));
            return false;
        }
    
    }
    
    function changeUserOrganName($db, $m_no, $m_id, $m_organ_name){
            // SQL 쿼리 작성
        $query = "UPDATE TBL_MEMBER 
                SET M_ORGAN_NAME = '" . mysqli_real_escape_string($db, $m_organ_name) . "' 
                WHERE 1=1 
                AND  M_NO = '" . mysqli_real_escape_string($db, $m_no) . "' 
                AND  M_ID = '" . mysqli_real_escape_string($db, $m_id) . "' 
                AND DEL_TF = 'N'";

        error_log("SQL Query: " . $query);

        $result = mysqli_query($db, $query);

        // 업데이트 실패 시 false 반환
        if (!$result) {
            error_log("SQL Error (UPDATE): " . mysqli_error($db));
            return false;
        }

        $select_query = "SELECT M_ORGAN_NAME FROM TBL_MEMBER 
                WHERE M_NO = '" . mysqli_real_escape_string($db, $m_no) . "' 
                AND M_ID = '" . mysqli_real_escape_string($db, $m_id) . "' 
                AND DEL_TF = 'N'";

        error_log("SQL Query (SELECT): " . $select_query);

        $result = mysqli_query($db, $select_query);

        // 결과 처리
        if ($result) {
            $arr_rs = mysqli_fetch_assoc($result);
            return $arr_rs['M_ORGAN_NAME']; // 변경된 M_NAME 값 반환
        } else {
            error_log("SQL Error (SELECT): " . mysqli_error($db));
            return false;
        }

    }



function changeUserAddr($db, $m_no, $m_id, $m_addr, $m_addr_detail, $m_post_cd)
{
    // SQL 쿼리 작성
    $query = "UPDATE TBL_MEMBER 
                SET 
                    M_ADDR = '" . mysqli_real_escape_string($db, $m_addr) . "',
                    M_ADDR_DETAIL = '" . mysqli_real_escape_string($db, $m_addr_detail) . "',
                    M_POST_CD = '" . mysqli_real_escape_string($db, $m_post_cd) . "'
                WHERE 1=1 
                    AND M_NO = '" . mysqli_real_escape_string($db, $m_no) . "' 
                    AND M_ID = '" . mysqli_real_escape_string($db, $m_id) . "' 
                    AND DEL_TF = 'N'";

    error_log("SQL Query: " . $query);

    $result = mysqli_query($db, $query);

    // 업데이트 실패 시 false 반환
    if (!$result) {
        error_log("SQL Error (UPDATE): " . mysqli_error($db));
        return false;
    }

    $select_query = "SELECT M_ADDR, M_ADDR_DETAIL, M_POST_CD FROM TBL_MEMBER 
                    WHERE M_NO = '" . mysqli_real_escape_string($db, $m_no) . "' 
                    AND M_ID = '" . mysqli_real_escape_string($db, $m_id) . "' 
                    AND DEL_TF = 'N'";

    error_log("SQL Query (SELECT): " . $select_query);

    $result = mysqli_query($db, $select_query);

    // 결과 처리
    if ($result) {
        $arr_rs = mysqli_fetch_assoc($result);
        return $arr_rs; // 데이터 배열 반환
    } else {
        error_log("SQL Error (SELECT): " . mysqli_error($db));
        return false;
    }
}


function ReservationsByMember($conn, $m_no)
{
    $query = "
        SELECT 
            R.RV_NO, 
            R.M_NO, 
            R.ROOM_NO, 
            M.ROOM_NAME,        -- 시설 이름
            R.RV_PURPOSE, 
            R.RV_DATE, 
            R.RV_START_TIME, 
            R.RV_END_TIME, 
            R.RV_EQUIPMENT, 
            R.RV_USE_COUNT, 
            R.RV_COST, 
            R.RV_REDUCTION, 
            R.RV_MEMO, 
            R.RV_REDUCTION_TF, 
            R.RV_AGREE_TF, 
            DATE_FORMAT(R.REG_DATE, '%Y-%m-%d') AS REG_DATE
        FROM 
            TBL_RESERVATION R
        JOIN 
            TBL_MEETING_ROOM M ON R.ROOM_NO = M.ROOM_NO
        WHERE 
            R.M_NO = ?
            AND R.USE_TF = 'Y'
            AND R.DEL_TF = 'N'
            AND M.USE_TF = 'Y'
            AND M.DEL_TF = 'N'
        ORDER BY 
            R.RV_DATE DESC, 
            R.RV_START_TIME DESC;
    ";

    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        echo "DB Error: " . mysqli_error($conn);
        return [];
    }

    mysqli_stmt_bind_param($stmt, "i", $m_no);  // 회원 번호를 바인딩
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $reservations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $reservations[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $reservations;
}







?>