<?
	# =============================================================================
	# File Name    : member.php
	# Modlue       : 
    # Table        : TBL_MEMBER_TEST
	# Writer       : 
	# Create Date  :
	# Modify Date  : 

	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================

    function listMember($conn, $m_no, $m_name, $m_email, $m_phone, $m_status, $m_role, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nRowCount, $nListCnt) {

        $offset = $nRowCount * ($nPage - 1);
        if ($offset < 0) $offset = 0;
    
        $query = "SET @rownum = " . $offset.";";
        mysqli_query($conn, $query);
    
        $query = "SELECT 
                    @rownum := @rownum + 1 AS rn, 
                    M_NO, M_NAME, M_EMAIL, M_PHONE, M_STATUS, M_ROLE,
                    DISP_SEQ, USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
                  FROM TBL_MEMBER_TEST
                  WHERE 1 = 1";
    
        if ($use_tf != "") {
            $query .= " AND USE_TF = '" . $use_tf . "' ";
        }
    
        if ($del_tf != "") {
            $query .= " AND DEL_TF = '" . $del_tf . "' ";
        }
    
        if ($search_str != "") {
            $query .= " AND (M_NAME LIKE '%$search_str%' OR M_ROLE LIKE '%$search_str%') ";
        }
    
        $query .= " ORDER BY USE_TF DESC, DISP_SEQ ASC, M_NO DESC limit ".$offset.", ".$nRowCount; //limit ".$offset.", ".$nRowCount;

        $result = mysqli_query($conn, $query);
        $record = array();
    
        if ($result != "") {
            for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                $record[$i] = sql_result_array($conn, $result, $i);
            }
        }

        return $record;
    }

    function totalCntMembers($conn, $m_no, $use_tf, $del_tf, $search_field, $search_str) {

        $query ="SELECT COUNT(*) FROM TBL_MEMBER_TEST WHERE 1 = 1";

        if ($use_tf <> "") {
			$query .= " AND USE_TF = '".$use_tf."' ";
		}
		
		if ($del_tf <> "") {
			$query .= " AND DEL_TF = '".$del_tf."' ";
		}

		if ($search_str <> "") {
            $query .= " AND (M_NAME LIKE '%$search_str%' OR M_ROLE LIKE '%$search_str%') ";
		}

		$result = mysqli_query($conn,$query);
		$rows   = mysqli_fetch_array($result);

		$record  = $rows[0];
		return $record;
    }

    function updateMemberUseTF($db, $use_tf, $up_adm, $m_no) {
		
		$query="UPDATE tbl_member_test SET 
							USE_TF			= '$use_tf',
							UP_ADM			= '$up_adm',
							UP_DATE			= now()
				 WHERE M_NO			= '$m_no' ";

		//echo $query;

		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


    function insertMember($db, $arr_data) {
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

        $query = "SELECT IFNULL(MAX(M_NO),0) + 1 AS MAX_NO FROM TBL_MEMBER_TST";
        $result = mysqli_query($db, $query);
        $rows = mysqli_fetch_array($result);

        $new_m_no = $rows['MAX_NO']; 
        
        /*
            INSERT INTO `TBL_MEMBER_TEST` (`M_NAME`, `M_EMAIL`, `M_PHONE`, `M_ROLE`, `REG_ADM`, `REG_DATE`)
            VALUES ('유컴돈줘', 'simhanyork@ucomp.co.kr', '010-4444-4444', 'GUEST', 1, NOW());
        */

        $query = "INSERT INTO TBL_MEMBER_TST (M_NO, " . $set_field . ", REG_DATE, UP_DATE) 
                    VALUES ($new_m_no, " . $set_value . ", now(), now());";

        if (!mysqli_query($db, $query)) {
            echo "<script>alert(\"[1] 오류가 발생하였습니다 - \");</script>";
            exit;
        } else {
            return $new_m_no;
        }
    }

    // 아이디 중복체크 추가
    function dupMemberIdChk($db, $m_id) {
        
        $query ="SELECT COUNT(*) CNT FROM TBL_MEMBER_TST WHERE DEL_TF = 'N' ";


		if ($m_id <> "") {
			$query .= " AND M_ID = '".$m_id."' ";
		}

        $result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
	
		if ($rows[0] == 0) {
			return 0;
		} else {
			return 1;
		}
    }

    function updateMember($db, $arr_data, $m_no){

        $set_query_str = "";

        foreach ($arr_data as $key => $value) {
			$value = str_replace("'","''",$value);
			$set_query_str .= $key." = '".$value."',"; 
		}

        $query = "UPDATE TBL_MEMBER_TEST set ".$set_query_str." ";
        $query .= "UP_DATE = now(), ";
		$query .= "M_NO = '$m_no' WHERE M_NO = '$m_no' ";

        if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}

    }


    function deleteMemberTF($db, $del_adm, $m_no) {

		$query = "UPDATE TBL_MEMBER_TEST SET
						DEL_TF				= 'Y',
						DEL_ADM				= '$del_adm',
						DEL_DATE			= now()
					WHERE 
                        M_NO				= '$m_no'"; 
		
		if(!mysqli_query($db,$query)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - \"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

    function selectMember($db, $m_no) {
        $query = "SELECT M_NO, M_ID, M_PWD, M_PHONE, M_EMAIL, M_GUBUN, M_ADDR, M_POST_CD, M_ADDR_DETAIL, 
                        M_BIZ_NO, M_KSIC, M_KSIC_DETAIL, EMAIL_TF, MESSAGE_TF, DISP_SEQ, USE_TF, DEL_TF, 
                        REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE 
                FROM TBL_MEMBER 
                WHERE 1=1 AND m_no = '$m_no'";

        $result = mysqli_query($db, $query);
		$record = array();

        if ($result <> "") {
			for($i=0;$i < mysqli_num_rows($result);$i++) {
				$record[$i] = sql_result_array($db,$result,$i);
			}
		}
		return $record;
    }


    #=====================================================================
    # Table        : TBL_MEMBER_TST START
    #=====================================================================

    

?>
