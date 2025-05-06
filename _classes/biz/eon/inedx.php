<?
	# =============================================================================
	# File Name    : index.php
	# Modlue       : 
    # Table        : TBL_BOARD
	# Writer       : KIM KYEONG EON
	# Create Date  : 2024-11-29
	# Modify Date  : 

	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================

	#=========================================================================================================
	# End Table
	#=========================================================================================================
	
	
	// 데이터베이스 연결 포함
	require "../_common/common_inc.php";

	// 데이터 가져오기 로직
	$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // 현재 페이지 번호
	$pageSize = 10; // 한 페이지당 게시물 수
	$offset = ($page - 1) * $pageSize; // 가져올 데이터 시작점

	try {
		// 게시물 목록 쿼리
		$sql = "SELECT B_CODE, TITLE, REG_DATE 
				FROM tbl_board 
				WHERE USE_TF = 'Y' AND DEL_TF = 'N'
				ORDER BY REG_DATE DESC 
				LIMIT :offset, :pageSize";

		$stmt = $conn->prepare($sql); // $conn은 common_inc.php에서 정의
		$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
		$stmt->bindValue(':pageSize', $pageSize, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC); // 결과 가져오기

		// 총 게시물 수 쿼리
		$countSql = "SELECT COUNT(*) as cnt FROM tbl_board WHERE USE_TF = 'Y' AND DEL_TF = 'N'";
		$countStmt = $conn->query($countSql);
		$totalCount = $countStmt->fetchColumn();
		$totalPages = ceil($totalCount / $pageSize);

		// 필요한 데이터를 배열로 반환
		echo json_encode([
			'result' => $result,
			'totalPages' => $totalPages,
			'currentPage' => $page
		]);
	} catch (PDOException $e) {
		echo json_encode(['error' => $e->getMessage()]);
		exit;
	}
	
?>