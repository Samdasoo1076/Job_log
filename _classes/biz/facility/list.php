<? session_start(); ?>
<?

function listEquipment($db) {

	$query = "SELECT * FROM TBL_CODE_DETAIL WHERE PCODE= 'EQUIPMENT' AND DEL_TF = 'N' AND USE_TF = 'Y' ORDER BY DCODE_SEQ_NO ASC;";

	$result = mysqli_query($db, $query);
	$record = array();


	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}
	return $record;
}


function listFormMeetingRoom($db) {

	$query = "SELECT * FROM TBL_MEETING_ROOM WHERE USE_TF = 'Y' AND DEL_TF = 'N' ORDER BY REG_DATE DESC";

	$result = mysqli_query($db, $query);
	$record = array();


	if ($result <> "") {
		for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			$record[$i] = sql_result_array($db, $result, $i);
		}
	}
	return $record;
}


function listMeetingRoom($db)
{
    $query = "
        SELECT 
            mr.ROOM_NO, 
            mr.ROOM_NAME, 
            mr.ROOM_FILE, 
            mr.ABLE_PERIOD, 
            mr.ROOM_SCALE, 
            mr.ROOM_CAPACITY, 
            mr.ROOM_PRICE, 
            mf.FILE_NM, 
            mf.FILE_RNM
        FROM 
            TBL_MEETING_ROOM AS mr
        LEFT JOIN 
            TBL_MEETING_ROOM_FILE AS mf
        ON 
            mr.ROOM_NO = mf.ROOM_NO
		WHERE 
            mr.USE_TF = 'Y' 
            AND mr.DEL_TF = 'N'
        ORDER BY 
            mr.REG_DATE DESC";

    $result = mysqli_query($db, $query);
    $records = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $roomNo = $row['ROOM_NO'];
        if (!isset($records[$roomNo])) {
            $records[$roomNo] = [
                'ROOM_INFO' => $row,
                'FILES' => []
            ];
        }
        if (!empty($row['FILE_NM'])) {
            $records[$roomNo]['FILES'][] = [
                'FILE_NM' => $row['FILE_NM'],
                'FILE_RNM' => $row['FILE_RNM']
            ];
        }
    }
    return $records;
}

//컨펌 페이지

function completeMeetingRoom($db, $room_no)
{
    $query = "SELECT 
                mr.ROOM_NO, 
                mr.ROOM_NAME, 
                mr.ROOM_FILE, 
                mr.ABLE_PERIOD, 
                mr.ROOM_SCALE, 
                mr.ROOM_CAPACITY, 
                mr.ROOM_PRICE
            FROM 
                TBL_MEETING_ROOM AS mr
            WHERE 
                mr.ROOM_NO = ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $room_no);
    $stmt->execute();
    $result = $stmt->get_result();

    $record = $result->fetch_assoc();

    // 기본 사진 정보 처리
    if ($record) {
        $record['FILES'] = [];

        // ROOM_FILE 필드가 있는 경우 파일 정보 추가
        if (!empty($record['ROOM_FILE'])) {
            $record['FILES'][] = [
                'FILE_NM' => $record['ROOM_FILE'],
                'FILE_RNM' => $record['ROOM_FILE'] // 원본 파일명과 저장된 파일명이 동일하다고 가정
            ];
        }
    }

    return $record;
}



function detailMeetingRoom($db, $room_no)
{
    $query = "SELECT mr.ROOM_NO, mr.ROOM_NAME, mr.ROOM_FILE, mr.ABLE_PERIOD, mr.ROOM_SCALE, mr.ROOM_CAPACITY, mr.ROOM_PRICE, mr.ROOM_NIGHT_PRICE,
                     mf.FILE_NM, mf.FILE_RNM, mr.USE_TIME
              FROM TBL_MEETING_ROOM AS mr
              LEFT JOIN TBL_MEETING_ROOM_FILE AS mf
              ON mr.ROOM_NO = mf.ROOM_NO
              WHERE mr.ROOM_NO = ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $room_no);
    $stmt->execute();
    $result = $stmt->get_result();

    $records = [];

    while ($row = $result->fetch_assoc()) {
        if (!isset($records[$row['ROOM_NO']])) {
            $records[$row['ROOM_NO']] = [
                'ROOM_NO' => $row['ROOM_NO'],
                'ROOM_NAME' => $row['ROOM_NAME'],
                'ROOM_FILE' => $row['ROOM_FILE'],
                'ABLE_PERIOD' => $row['ABLE_PERIOD'],
                'ROOM_SCALE' => $row['ROOM_SCALE'],
                'ROOM_CAPACITY' => $row['ROOM_CAPACITY'],
                'ROOM_PRICE' => $row['ROOM_PRICE'],
                'ROOM_NIGHT_PRICE' => $row['ROOM_NIGHT_PRICE'],
                'USE_TIME' => $row['USE_TIME'],
                'FILES' => []
            ];
        }

        // FILES 배열 추가
        if ($row['FILE_NM']) {
            $records[$row['ROOM_NO']]['FILES'][] = [
                'FILE_NM' => $row['FILE_NM'],
                'FILE_RNM' => $row['FILE_RNM']
            ];
        }
    }

    return $records;
}



function MeetingRoomPrice($db, $room_no, $start_time, $equipment_code = null, $reduction_code = null)
{
    $query = " SELECT
				mr.ROOM_NO,
				mr.ROOM_NAME,
				IFNULL(mr.ROOM_PRICE, 0) AS ROOM_PRICE,
				IFNULL(mr.ROOM_NIGHT_PRICE, 0) AS ROOM_NIGHT_PRICE,
				CASE
					WHEN '" . mysqli_real_escape_string($db, $reduction_code) . "'  = 'G002' THEN
						IFNULL((CASE WHEN HOUR('" . mysqli_real_escape_string($db, $start_time) . "') >= 18 THEN mr.ROOM_NIGHT_PRICE ELSE mr.ROOM_PRICE END), 0) / 2
					WHEN '" . mysqli_real_escape_string($db, $reduction_code) . "' = 'G001' THEN
						0
					ELSE
						IFNULL((CASE WHEN HOUR('" . mysqli_real_escape_string($db, $start_time) . "') >= 18 THEN mr.ROOM_NIGHT_PRICE ELSE mr.ROOM_PRICE END), 0)
				END AS BASE_PRICE,
				IFNULL(
					(SELECT CAST(DCODE_EXT AS UNSIGNED)
					 FROM TBL_CODE_DETAIL
					 WHERE PCODE = 'EQUIPMENT'
					   AND DCODE = '" . mysqli_real_escape_string($db, $equipment_code) . "'
					   AND DEL_TF = 'N'
					   AND USE_TF = 'Y'), 
					0
				) AS EQUIPMENT_PRICE
			FROM TBL_MEETING_ROOM AS mr
			WHERE mr.ROOM_NO = '" . mysqli_real_escape_string($db, $room_no) . "'
			  AND mr.USE_TF = 'Y'
			  AND mr.DEL_TF = 'N'; ";

    $stmt = $db->prepare($query);
    $stmt->execute();

    error_log("SQL Error (SELECT): " . $query);

    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    return $data;


}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//

// 사용되고 있지 않은 쿼리들

//

function completeMeetingRoom111($db, $room_no)
{
    $query = "SELECT 
                mr.ROOM_NO, 
                mr.ROOM_NAME, 
                mr.ROOM_FILE, 
                mr.ABLE_PERIOD, 
                mr.ROOM_SCALE, 
                mr.ROOM_CAPACITY, 
                mr.ROOM_PRICE + IFNULL(
                    (
                        SELECT SUM(CAST(cd.DCODE_EXT AS UNSIGNED))
                        FROM TBL_CODE_DETAIL AS cd
                        WHERE cd.PCODE = 'EQUIPMENT'
                    ), 0
                ) AS ROOM_PRICE,
                mr.USE_TIME, 
                -- 야간 이용료가 16시 이후일 경우 더해짐
                CASE 
                    WHEN HOUR(STR_TO_DATE(mr.USE_TIME, '%H:%i')) >= 16 THEN 
                        mr.ROOM_NIGHT_PRICE
                    ELSE 
                        0
                END AS NIGHT_PRICE,
                GROUP_CONCAT(DISTINCT mf.FILE_NM) AS FILE_NM, 
                GROUP_CONCAT(DISTINCT mf.FILE_RNM) AS FILE_RNM
            FROM 
                TBL_MEETING_ROOM AS mr
            LEFT JOIN 
                TBL_MEETING_ROOM_FILE AS mf 
                ON mr.ROOM_NO = mf.ROOM_NO
            WHERE 
                mr.ROOM_NO = ?
            GROUP BY 
                mr.ROOM_NO";

    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $room_no);
    $stmt->execute();
    $result = $stmt->get_result();

    $record = $result->fetch_assoc();

    // FILES 배열 분리
    if ($record) {
        $record['FILES'] = [];
        if ($record['FILE_NM']) {
            $fileNames = explode(',', $record['FILE_NM']);
            $fileRenames = explode(',', $record['FILE_RNM']);
            foreach ($fileNames as $index => $fileName) {
                $record['FILES'][] = [
                    'FILE_NM' => $fileName,
                    'FILE_RNM' => $fileRenames[$index] ?? null
                ];
            }
        }
        unset($record['FILE_NM'], $record['FILE_RNM']);
    }

    // 야간 이용료 추가
    if (isset($record['NIGHT_PRICE']) && $record['NIGHT_PRICE'] > 0) {
        $record['ROOM_PRICE'] += $record['NIGHT_PRICE']; // 야간 이용료 더함
        unset($record['NIGHT_PRICE']);
    }

    return $record;
}


//사용x 작동하는 상세보기 코드
function detailMeetingRoom2222($db, $room_no)
{
    $query = "SELECT mr.ROOM_NO, mr.ROOM_NAME, mr.ROOM_FILE, mr.ABLE_PERIOD, mr.ROOM_SCALE, mr.ROOM_CAPACITY, mr.ROOM_PRICE, 
                     mf.FILE_NM, mf.FILE_RNM
              FROM TBL_MEETING_ROOM AS mr
              LEFT JOIN TBL_MEETING_ROOM_FILE AS mf
              ON mr.ROOM_NO = mf.ROOM_NO
              WHERE mr.ROOM_NO = ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $room_no);
    $stmt->execute();
    $result = $stmt->get_result();

    $records = [];

    while ($row = $result->fetch_assoc()) {
        if (!isset($records[$row['ROOM_NO']])) {
            $records[$row['ROOM_NO']] = [
                'ROOM_NO' => $row['ROOM_NO'],
                'ROOM_NAME' => $row['ROOM_NAME'],
                'ROOM_FILE' => $row['ROOM_FILE'],
                'ABLE_PERIOD' => $row['ABLE_PERIOD'],
                'ROOM_SCALE' => $row['ROOM_SCALE'],
                'ROOM_CAPACITY' => $row['ROOM_CAPACITY'],
                'ROOM_PRICE' => $row['ROOM_PRICE'],
                'FILES' => []
            ];
        }

        // FILES 배열 추가
        if ($row['FILE_NM']) {
            $records[$row['ROOM_NO']]['FILES'][] = [
                'FILE_NM' => $row['FILE_NM'],
                'FILE_RNM' => $row['FILE_RNM']
            ];
        }
    }

    return $records;
}

function completeMeetingRoom121($db, $room_no)
{
    $query = "SELECT 
                mr.ROOM_NO, 
                mr.ROOM_NAME, 
                mr.ROOM_FILE, 
                mr.ABLE_PERIOD, 
                mr.ROOM_SCALE, 
                mr.ROOM_CAPACITY, 
                mr.ROOM_PRICE + IFNULL(
                    (
                        SELECT SUM(CAST(cd.DCODE_EXT AS UNSIGNED))
                        FROM TBL_CODE_DETAIL AS cd
                        WHERE cd.PCODE = 'EQUIPMENT'
                    ), 0
                ) AS ROOM_PRICE,
                GROUP_CONCAT(DISTINCT mf.FILE_NM) AS FILE_NM, 
                GROUP_CONCAT(DISTINCT mf.FILE_RNM) AS FILE_RNM
            FROM 
                TBL_MEETING_ROOM AS mr
            LEFT JOIN 
                TBL_MEETING_ROOM_FILE AS mf 
                ON mr.ROOM_NO = mf.ROOM_NO
            WHERE 
                mr.ROOM_NO = ?
            GROUP BY 
                mr.ROOM_NO";

    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $room_no);
    $stmt->execute();
    $result = $stmt->get_result();

    $record = $result->fetch_assoc();

    // FILES 배열 분리
    if ($record) {
        $record['FILES'] = [];
        if ($record['FILE_NM']) {
            $fileNames = explode(',', $record['FILE_NM']);
            $fileRenames = explode(',', $record['FILE_RNM']);
            foreach ($fileNames as $index => $fileName) {
                $record['FILES'][] = [
                    'FILE_NM' => $fileName,
                    'FILE_RNM' => $fileRenames[$index] ?? null
                ];
            }
        }
        unset($record['FILE_NM'], $record['FILE_RNM']);
    }

    return $record;
}

?>