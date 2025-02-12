<?session_start(); ?>
<?
function selectBoardList($db, $page = 1, $pageSize = 4) {
    // 시작 행 계산
    $offset = ($page - 1) * $pageSize;

    // 기본 쿼리
    $query = "SELECT 
                    B_CODE, B_NO, B_PO, CATE_01, TITLE, HIT_CNT, DATE_FORMAT(REG_DATE, '%Y-%m-%d') AS REG_DATE
              FROM TBL_BOARD 
              WHERE 1=1 
              AND B_CODE IN ('B_1_1', 'B_1_3')
              ORDER BY REG_DATE DESC 
              LIMIT $offset, $pageSize";

    $result = mysqli_query($db, $query);
    $records = [];

    // 결과를 배열로 저장
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $records[] = $row;
        }
    }

    return $records;
}

function selectPopupList($db, $page = 1, $pageSize = 4) {
    // 시작 행 계산
    $offset = ($page - 1) * $pageSize;

    $query ="SELECT 
                INTRO_NO, INTRO_TITLE, INTRO_MEMO, INTRO_DISP_SEQ, FILE_NM,
                FILE_RNM, USE_TF, DEL_TF, REG_ADM, DATE_FORMAT(REG_DATE, '%Y-%m-%d') AS REG_DATE, UP_DATE, DEL_DATE
                START_DATE, END_DATE, DATE_USE_TF
            FROM TBL_INTRO
            WHERE 1=1
            AND USE_TF = 'Y'
            AND DEL_TF = 'N'
            ORDER BY INTRO_DISP_SEQ asc, INTRO_NO DESC";

    $result = mysqli_query($db, $query);
    $records = [];

    // 결과를 배열로 저장
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $records[] = $row;
        }
    }

    return $records;
}


function getMainlistBanner($db, $banner_type) {

		$query = "SELECT BANNER_NO, BANNER_NM, BANNER_TYPE, CATE_01, TITLE_NM, SUB_TITLE_NM, 
										 BANNER_IMG, BANNER_REAL_IMG, BANNER_IMG_M, BANNER_REAL_IMG_M, BANNER_URL, BANNER_BUTTON, URL_TYPE, S_DATE, S_HOUR, S_MIN, E_DATE, E_HOUR, E_MIN, DISP_SEQ,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BANNER WHERE USE_TF = 'Y' AND DEL_TF = 'N' ";

		if ($banner_type <> "") {
			$query .= " AND BANNER_TYPE = '".$banner_type."' ";
		}
		
		$query .= " ORDER BY DISP_SEQ asc, BANNER_NO DESC";  //limit ".$offset.", ".$nRowCount;

//		echo $query;

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
