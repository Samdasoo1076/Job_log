<?
	# =============================================================================
	# File Name    : navi.php
	# Modlue       : 
	# Writer       : Park Chan Ho 
	# Create Date  : 2019-12-19
	# Modify Date  : 
	#	Copyright : Copyright @UCOMP Corp. All Rights Reserved.
	# =============================================================================


	function listNavi($db, $type, $search_field, $search_str, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);
		
		if ($offset < 0) $offset = 0;

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num."; ";
		mysqli_query($db, $query);

		$query = "SELECT @rownum:= @rownum - 1  as rn, c.name as L1,
										 c.simple as SIMPLE,
										 d.name as L2,
										 d.menu_doc as MENU_DESC,
										 e.name as L3,
										 a.idx as CIDX,
										 a.level1 as LEVEL1,
										 a.level2 as LEVEL2,
										 a.level3 as LEVEL3,
										 a.link_url as LINK_URL,
										 a.path as PATH,
										 a.list_image  as IMAGE,
										 a.title as TITLE,
										 a.sub_title as SUBTITLE,
										 a.summary as SUMMARY,
										 a.main_doc as MAIN_DOC,
										 a.view_cnt as VIEW_CNT,
										 a.view_opt as VIEW_OPT,
										 a.state as STATE,
										 DATE_FORMAT(a.wdate,'%Y-%m-%d') as WDATE,
										 DATE_FORMAT(a.open_date,'%Y-%m-%d') as WDATE2,
										 DATE_FORMAT(a.wdate,'%Y-%m-%d') as CWDATE,
										 DATE_FORMAT(a.open_date,'%Y-%m-%d %h:%i') as OPEN_DATE,
										 a.use_l3 as USE_L3,
										 b.name as AUTHOR,
										 d.newbook as NEWBOOK,
										 f.name as BOOK_NAME  
								FROM tbl_contents a
										 LEFT JOIN tbl_author b ON ( a.writer = b.idx )
										 LEFT JOIN tbl_cat_l1 c ON ( a.level1= c.idx )
										 LEFT JOIN tbl_cat_l2 d ON ( a.level2= d.idx )
										 LEFT JOIN tbl_cat_l3 e ON ( a.level3= e.idx )
										 LEFT JOIN tbl_bookinfo f ON ( a.contents_book_idx = f.idx )
							 WHERE 1 = 1 ";


		if ($type == "") {
			$query .= " AND BINARY a.level1 IN (52, 53)  ";
		} else if ($type == "52") {
			$query .= " AND BINARY a.level1 = 52 ";
		} else if ($type == "53") {
			$query .= " AND BINARY a.level1 = 53  ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (b.name like '%".$search_str."%' OR f.name like '%".$search_str."%' OR a.summary like '%".$search_str."%' OR a.title like '%".$search_str."%' OR a.sub_title like '%".$search_str."%')";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		$query .= " AND a.open_date <= date_format(now(), '%Y-%m-%d %H:%i') 
								AND a.view_opt='1' 
								AND a.state='1' 
								AND d.end_flag=0 
								AND c.special!='1'
								AND c.simple!='1' 
							ORDER BY a.open_date DESC limit ".$offset.", ".$nRowCount;

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

	function totalCntNavi($db, $type, $search_field, $search_str) {

		$query ="SELECT COUNT(a.idx) CNT 
								FROM tbl_contents a
										 LEFT JOIN tbl_author b ON ( a.writer = b.idx )
										 LEFT JOIN tbl_cat_l1 c ON ( a.level1= c.idx )
										 LEFT JOIN tbl_cat_l2 d ON ( a.level2= d.idx )
										 LEFT JOIN tbl_cat_l3 e ON ( a.level3= e.idx )
										 LEFT JOIN tbl_bookinfo f ON ( a.contents_book_idx = f.idx )
							 WHERE 1 = 1 ";


		if ($type == "") {
			$query .= " AND BINARY a.level1 IN (52, 53)  ";
		} else if ($type == "52") {
			$query .= " AND BINARY a.level1 = 52 ";
		} else if ($type == "53") {
			$query .= " AND BINARY a.level1 = 53  ";
		}

		if ($search_str <> "") {
			if ($search_field == "ALL") {
				$query .= " AND (b.name like '%".$search_str."%' OR f.name like '%".$search_str."%' OR a.summary like '%".$search_str."%' OR a.title like '%".$search_str."%' OR a.sub_title like '%".$search_str."%')";
			} else {
				$query .= " AND ".$search_field." like '%".$search_str."%' ";
			}
		}
		
		$query .= " AND a.open_date <= date_format(now(), '%Y-%m-%d %H:%i') 
								AND a.view_opt='1' 
								AND a.state='1' 
								AND d.end_flag=0 
								AND c.special!='1'
								AND c.simple!='1' ";
		
		//echo $query;

		$result = mysqli_query($db, $query);
		$rows   = mysqli_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function mainlistNavi($db, $cnt) {

		$query = "SELECT c.name as L1,
										 c.simple as SIMPLE,
										 d.name as L2,
										 d.menu_doc as MENU_DESC,
										 e.name as L3,
										 a.idx as CIDX,
										 a.level1 as LEVEL1,
										 a.level2 as LEVEL2,
										 a.level3 as LEVEL3,
										 a.link_url as LINK_URL,
										 a.path as PATH,
										 a.list_image  as IMAGE,
										 a.title as TITLE,
										 a.sub_title as SUBTITLE,
										 a.summary as SUMMARY,
										 a.main_doc as MAIN_DOC,
										 a.view_cnt as VIEW_CNT,
										 a.view_opt as VIEW_OPT,
										 a.state as STATE,
										 DATE_FORMAT(a.wdate,'%Y-%m-%d') as WDATE,
										 DATE_FORMAT(a.open_date,'%Y-%m-%d') as WDATE2,
										 DATE_FORMAT(a.wdate,'%Y-%m-%d') as CWDATE,
										 DATE_FORMAT(a.open_date,'%Y-%m-%d %h:%i') as OPEN_DATE,
										 a.use_l3 as USE_L3,
										 b.name as AUTHOR,
										 d.newbook as NEWBOOK,
										 f.name as BOOK_NAME  
								FROM tbl_contents a
										 LEFT JOIN tbl_author b ON ( a.writer = b.idx )
										 LEFT JOIN tbl_cat_l1 c ON ( a.level1= c.idx )
										 LEFT JOIN tbl_cat_l2 d ON ( a.level2= d.idx )
										 LEFT JOIN tbl_cat_l3 e ON ( a.level3= e.idx )
										 LEFT JOIN tbl_bookinfo f ON ( a.contents_book_idx = f.idx )
							 WHERE BINARY a.level1 IN (52, 53) 
								 AND a.open_date <= date_format(now(), '%Y-%m-%d %H:%i') 
								 AND a.view_opt='1' 
								 AND a.state='1' 
								 AND d.end_flag=0 
								 AND c.special!='1'
								 AND c.simple!='1' 
							 ORDER BY a.open_date DESC limit ".$cnt;

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