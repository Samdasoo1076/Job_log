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
	
	
	// �����ͺ��̽� ���� ����
	require "../_common/common_inc.php";

	// ������ �������� ����
	$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // ���� ������ ��ȣ
	$pageSize = 10; // �� �������� �Խù� ��
	$offset = ($page - 1) * $pageSize; // ������ ������ ������

	try {
		// �Խù� ��� ����
		$sql = "SELECT B_CODE, TITLE, REG_DATE 
				FROM tbl_board 
				WHERE USE_TF = 'Y' AND DEL_TF = 'N'
				ORDER BY REG_DATE DESC 
				LIMIT :offset, :pageSize";

		$stmt = $conn->prepare($sql); // $conn�� common_inc.php���� ����
		$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
		$stmt->bindValue(':pageSize', $pageSize, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC); // ��� ��������

		// �� �Խù� �� ����
		$countSql = "SELECT COUNT(*) as cnt FROM tbl_board WHERE USE_TF = 'Y' AND DEL_TF = 'N'";
		$countStmt = $conn->query($countSql);
		$totalCount = $countStmt->fetchColumn();
		$totalPages = ceil($totalCount / $pageSize);

		// �ʿ��� �����͸� �迭�� ��ȯ
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