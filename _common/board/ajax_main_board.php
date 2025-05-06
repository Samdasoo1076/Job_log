<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";

	$tab	= $_POST['tab']!=''?$_POST['tab']:$_GET['tab'];
	$navi	= $_POST['navi']!=''?$_POST['navi']:$_GET['navi'];

	$arr_rs_notice = listSubBoard($conn, $tab, $navi, "5", "5", "5");

	if (sizeof($arr_rs_notice) > 0) {
?>
											<ul class="posts-list">
<?
		for ($j = 0 ; $j < sizeof($arr_rs_notice); $j++) {
			
			$B_NO						= trim($arr_rs_notice[$j]["B_NO"]);
			$B_CODE					= trim($arr_rs_notice[$j]["B_CODE"]);
			$CATE_01				= trim($arr_rs_notice[$j]["CATE_01"]);
			$CATE_02				= trim($arr_rs_notice[$j]["CATE_02"]);
			$CATE_03				= trim($arr_rs_notice[$j]["CATE_03"]);
			$CATE_04				= trim($arr_rs_notice[$j]["CATE_04"]);
			$WRITER_NM			= trim($arr_rs_notice[$j]["WRITER_NM"]);
			$TITLE					= SetStringFromDB($arr_rs_notice[$j]["TITLE"]);
			$REG_DATE				= trim($arr_rs_notice[$j]["REG_DATE"]);

			switch ($CATE_01) {
				case "" : $COLOR = "<span class='mark module-a style-c type-line normal-04 small-1x'><span class='mark-text'>전체</span></span>"; break;
				case "ALL" : $COLOR = "<span class='mark module-a style-c type-line normal-04 small-1x'><span class='mark-text'>공통</span></span>"; break;
				case "SUSI" : $COLOR = "<span class='mark module-a style-c type-line accent-05 small-1x'><span class='mark-text'>수시모집</span></span>"; break;
				case "JEONGSI" : $COLOR = "<span class='mark module-a style-c type-line accent-06 small-1x'><span class='mark-text'>정시모집</span></span>"; break;
				case "JEOEGUK" : $COLOR = "<span class='mark module-a style-c type-line accent-07 small-1x'><span class='mark-text'>재외국민/외국인</span></span>"; break;
				case "PYEONIP" : $COLOR = "<span class='mark module-a style-c type-line accent-08 small-1x'><span class='mark-text'>편입학</span></span>"; break;
				case "YACKHACK" : $COLOR = "<span class='mark module-a style-c type-line accent-09 small-1x'><span class='mark-text'>약학대학편입학</span></span>"; break;
				//case "ETC" : $COLOR = "<em class='badge gray'>기타</em>"; break;
			}

			if ($B_CODE == "B_1_1") {
				$link_str = "notice";
			} else { 
				$link_str = "data";
			}

			$REG_DATE = date("Y.m.d",strtotime($REG_DATE));
?>
												<li class="posts-item">
													<div class="posts-wrap">
														<div class="posts-inform">
															<div class="posts-head"><p class="posts-subject"><a class="posts-name" href="/<?=$link_str?>/view.php?bn=<?=$B_NO?>&m_type=<?=$CATE_01?>"><?=$TITLE?></a></p></div>
															<div class="posts-prop">
																<div class="data-list">
																	<span class="data-item"><?=$COLOR?></span>
																</div>
															</div>
															<div class="posts-data">
																<p class="data-list">
																	<span class="data-item date"><span class="head">작성일</span> <span class="body"><?=$REG_DATE?></span></span>
																</p>
															</div>
														</div>
													</div>
												</li>
<?
		}
?>
											</div>
<?
	} else {
?>
											<div class="info-board module-d style-b type-c no-data attr-comment" style="padding:70px">
												<div class="board-wrap">
													<div class="board-head">
														<p class="board-subject"><span class="board-name">등록된 게시글이 없습니다.</span></p>
													</div>
												</div>
											</div>
<?
	}

	db_close($conn);
?>