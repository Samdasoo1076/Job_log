<?session_start();?>
<?
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
	require "../../_classes/biz/board/board.php";
	require "../../_classes/biz/board/board_comment.php";
	require "../../_classes/biz/member/member.php";
	require "../../_classes/com/util/AES2.php";

	$arr_page_nm = explode("/", $_SERVER['PHP_SELF']);

	$mode					= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$b						= $_POST['b']!=''?$_POST['b']:$_GET['b'];
	$bn						= $_POST['bn']!=''?$_POST['bn']:$_GET['bn'];
	$cno					= $_POST['cno']!=''?$_POST['cno']:$_GET['cno'];
	$writer_nm		= $_POST['writer_nm'];
	$writer_pw		= $_POST['writer_pw'];
	$secret_tf		= $_POST['secret_tf'];
	$contents			= $_POST['contents'];
	$cPage				= $_POST['cPage']!=''?$_POST['cPage']:$_GET['cPage'];
	$encrypt_str	= $_POST['encrypt_str'];
	
	/*
	$writer_nm = iconv("UTF-8", "CP949", rawurldecode($writer_nm));
	$writer_pw = iconv("UTF-8", "CP949", rawurldecode($writer_pw));
	$contents	 = iconv("UTF-8", "CP949", rawurldecode($contents));
	*/

#====================================================================
# Board Config Start
#====================================================================
	require "../../_common/board/config_info.php";
#====================================================================
# Board Config End
#====================================================================

	$writer_nm			= SetStringToDB($writer_nm);
	$writer_pw			= SetStringToDB($writer_pw);
	$contents				= SetStringToDB($contents);

	$use_tf				= "Y";
	$top_tf				= "N";

	if ($mode == "U") {

		$err_mag = "";

		$arr_rs = selectBoardComment($conn, $cno);

		$rs_c_no			= trim($arr_rs[0]["C_NO"]);
		$rs_writer_id	= trim($arr_rs[0]["WRITER_ID"]);

		if ($rs_c_no == "") $err_mag = "답글이 존재하지 않습니다.\\n\\n글이 삭제되었거나 이동하였을 수 있습니다."; 

		if ($_SESSION['s_adm_no'] == "") {
			if ($rs_writer_id != $_SESSION['s_m_id']) $err_mag = "작성자만 수정 할 수 있습니다.";

			if (substr_count($contents, "&#") > 50)
				$err_mag = "내용에 올바르지 않은 코드가 다수 포함되어 있습니다.";

			// 자동 등록방지 
			if ($encrypt_str != decrypt($key, $iv, $_SESSION['s_encrypt_str']))
				$err_mag = "정상적인 접근 방식이 아닙니다.";

			// 권한을 체크 합니다.
			if (!$c_right)
				$err_mag = "댓글 쓰기 권한이 없습니다.";

			// 금지어 체크
			if ($b_board_badword) {
				$arr_board_badword = explode(",",$b_board_badword);

				//$err_mag = sizeof($arr_board_badword);

				for ($i =0; $i < sizeof($arr_board_badword); $i++) {
					if(@eregi($arr_board_badword[$i],$contents)) {
						$err_mag = "'".$arr_board_badword[$i]."' 금지어가 포함되어 있습니다.";
					}
				}
			}


		}

		if ($err_mag) {
			$err_mag = iconv("EUC-kr", "UTF-8", $err_mag);
			echo $err_mag;
			mysql_close($conn);	
			exit;
		}

			
		//$msg_title		= iconv("UTF-8","EUC-KR", $msg_title);
		//$msg_contents	= iconv("UTF-8","EUC-KR", $msg_contents);

		$result = updateBoardComment($conn, $_SESSION['s_m_id'], $cno, $secret_tf, $contents);
		
		mysql_close($conn);	
		exit;
	}

	if ($mode == "D") {

		$err_mag = "";

		$arr_rs = selectBoardComment($conn, $cno);

		$rs_c_no			= trim($arr_rs[0]["C_NO"]);
		$rs_writer_id	= trim($arr_rs[0]["WRITER_ID"]);

		if ($rs_c_no == "") $err_mag = "답글이 존재하지 않습니다.\\n\\n글이 삭제되었거나 이동하였을 수 있습니다."; 
		// 외부에서 글을 등록할 수 있는 버그가 존재하므로 비밀글은 사용일 경우에만 가능해야 함
		
		// 자동 등록방지 
		//if ($encrypt_str != decrypt($key, $iv, $_SESSION['s_encrypt_str']))
		//	$err_mag = "정상적인 접근 방식이 아닙니다.";
		
		if ($_SESSION['s_adm_no'] == "") {
			if ($rs_writer_id != $_SESSION['s_m_id']) $err_mag = "작성자만 삭제 할 수 있습니다.";
		}

		if ($err_mag) {
			$err_mag = iconv("EUC-kr", "UTF-8", $err_mag);
			echo $err_mag;
			mysql_close($conn);	
			exit;
		}

		$msg_title		= "작성자 또는 관리자에 의해 삭제 되었습니다.";
		$msg_contents	= "답변글이 남아 있어 내용만 삭제 되었습니다.";
			
		//$msg_title		= iconv("UTF-8","EUC-KR", $msg_title);
		//$msg_contents	= iconv("UTF-8","EUC-KR", $msg_contents);

		$result = deleteBoardComment($conn, $_SESSION['s_m_id'], $cno, $msg_title, $msg_contents);
		
		mysql_close($conn);	
		exit;
	}


	if ($mode == "I") {
		
		$err_mag = "";

		// 입력자 관련 정보 
		if ($_SESSION['s_adm_no']) {
			
			$writer_pw		= $_SESSION['s_adm_pw'];
			$writer_id		= $_SESSION['s_adm_id'];
			$writer_nick	= $_SESSION['s_adm_nm'];
			$writer_nm		= $_SESSION['s_adm_nm'];

		} else {
			
			// 나중에 사용자 정보로 바꾼다.
			if ($_SESSION['s_m_id']) {
				$writer_id		= $_SESSION['s_m_id'];
				$writer_pw		= $m_m_password;
				$writer_nick	= $m_m_nick;
				$writer_nm		= $m_m_name;
			} else {
				$writer_id		= "";
				$writer_pw		= sql_password($conn, trim($writer_pw));
				$writer_nick	= trim($writer_nm);
				$writer_nm		= trim($writer_nm);
			}
		}

		if ($_SESSION['s_is_adm'] == "") { // 관리자 가 아닌경우 입력 값 체크

			if (substr_count($contents, "&#") > 50)
				$err_mag = "내용에 올바르지 않은 코드가 다수 포함되어 있습니다.";

			// 자동 등록방지 
			if ($encrypt_str != decrypt($key, $iv, $_SESSION['s_encrypt_str']))
				$err_mag = "정상적인 접근 방식이 아닙니다.";

			// 권한을 체크 합니다.
			if (!$c_right)
				$err_mag = "댓글 쓰기 권한이 없습니다.";

			// 금지어 체크
			if ($b_board_badword) {
				$arr_board_badword = explode(",",$b_board_badword);

				//$err_mag = sizeof($arr_board_badword);

				for ($i =0; $i < sizeof($arr_board_badword); $i++) {
					if(@eregi($arr_board_badword[$i],$contents)) {
						$err_mag = "'".$arr_board_badword[$i]."' 금지어가 포함되어 있습니다.";
					}
				}
			}


			// 글쓴이에 금지어 체크
			if (preg_match("/[\,]?{$writer_nm}/i", $g_prohibit_id))
				$err_mag = "'$writer_nm' 은(는) 예약어로 사용하실 수 없는 이름입니다.";

			// 쓰기 시간 체크
			$times = mktime();
			
			if ($_SESSION["s_write_time"] >= ($times - $g_site_re_write)) {
				$err_mag = "너무 빠른 시간내에 게시물을 연속해서 올릴 수 없습니다.";
			} else {
				if ($err_mag == "") $_SESSION["s_write_time"] = $times;
			}

			if ($err_mag) {
				$err_mag = rawurlencode(iconv("CP949", "UTF-8", $err_mag));
				echo $err_mag;
				mysql_close($conn);	
				exit;
			}
		}
		
		// 모든 예외 사항 처리 후 등록 합니다.
		$b_re = getBoardCommentNextRe($conn, $bn);
		$b_po = "";
		
		$arr_data = array("B_NO"=>$bn,
											"B_PO"=>$b_po,
											"B_RE"=>$b_re,
											"WRITER_ID"=>$writer_id,
											"WRITER_NM"=>$writer_nm,
											"WRITER_NICK"=>$writer_nick,
											"WRITER_PW"=>$writer_pw,
											"TITLE"=>$title,
											"REF_IP"=>$ref_ip,
											"CONTENTS"=>$contents,
											"SECRET_TF"=>$secret_tf,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$s_adm_no);

		$new_c_no =  insertBoardComment($conn, $arr_data);

	}

	if ($mode == "IR") {

		$err_mag = "";

		// 입력자 관련 정보 
		if ($_SESSION['s_adm_no']) {
			
			$writer_pw		= $_SESSION['s_adm_pw'];
			$writer_id		= $_SESSION['s_adm_id'];
			$writer_nick	= $_SESSION['s_adm_nm'];
			$writer_nm		= $_SESSION['s_adm_nm'];

		} else {
			
			// 나중에 사용자 정보로 바꾼다.
			if ($_SESSION['s_m_id']) {
				$writer_id		= $_SESSION['s_m_id'];
				$writer_pw		= $m_m_password;
				$writer_nick	= $m_m_nick;
				$writer_nm		= $m_m_name;
			} else {
				$writer_id		= "";
				$writer_pw		= sql_password($conn, trim($writer_pw));
				$writer_nick	= trim($writer_nm);
				$writer_nm		= trim($writer_nm);
			}
		}

		$arr_rs = selectBoardComment($conn, $cno);

		$rs_c_no = trim($arr_rs[0]["C_NO"]);
		$rs_b_po = trim($arr_rs[0]["B_PO"]);
		$rs_b_re = trim($arr_rs[0]["B_RE"]);

		if ($rs_c_no == "") $err_mag = "답글이 존재하지 않습니다.\\n\\n글이 삭제되었거나 이동하였을 수 있습니다."; 
		// 외부에서 글을 등록할 수 있는 버그가 존재하므로 비밀글은 사용일 경우에만 가능해야 함

		// 기존 소스 참고
		if ($_SESSION['s_is_adm'] == "") {

			if (substr_count($contents, "&#") > 50)
				$err_mag = "내용에 올바르지 않은 코드가 다수 포함되어 있습니다.";

			// 자동 등록방지 
			if ($encrypt_str != decrypt($key, $iv, $_SESSION['s_encrypt_str']))
				$err_mag = "정상적인 접근 방식이 아닙니다.";

			// 권한을 체크 합니다.
			if (!$c_right)
				$err_mag = "댓글 쓰기 권한이 없습니다.";

			// 금지어 체크
			if ($b_board_badword) { 
				$arr_board_badword = explode(",",$b_board_badword);

				for ($i =0; $i < sizeof($arr_board_badword); $i++) {
					if(@eregi($arr_board_badword[$i],$contents)) {
						$err_mag = "'".$arr_board_badword[$i]."' 금지어가 포함되어 있습니다.";
					}
				}
			}
			
			// 글쓴이에 금지어 체크
			if (preg_match("/[\,]?{$writer_nm}/i", $g_prohibit_id))
				$err_mag = "'$writer_nm' 은(는) 예약어로 사용하실 수 없는 이름입니다.";

			// 쓰기 시간 체크
			$times = mktime();
			
			if ($_SESSION["s_write_time"] >= ($times - $g_site_re_write)) {
				$err_mag = "너무 빠른 시간내에 게시물을 연속해서 올릴 수 없습니다.";
			} else {
				if ($err_mag == "") $_SESSION["s_write_time"] = $times;
			}

			if ($err_mag) {
				$err_mag = rawurlencode(iconv("CP949", "UTF-8", $err_mag));
				echo $err_mag;
				mysql_close($conn);	
				exit;
			}
		}


		// 게시글 배열 참조
		if (strlen($rs_b_po) == 10) {
			$err_mag = "더 이상 답변하실 수 없습니다.\n\n답변은 10단계 까지만 가능합니다.";
			$err_mag = rawurlencode(iconv("CP949", "UTF-8", $err_mag));
			echo $err_mag;
			mysql_close($conn);	
			exit;
		}

		$reply_len = strlen($rs_b_po) + 1;

		if ($b_reply_order == "1") {
			$begin_reply_char = "A";
			$end_reply_char = "Z";
			$reply_number = +1;

			$sql = " SELECT MAX(SUBSTRING(B_PO, $reply_len, 1)) as reply FROM TBL_BOARD_COMMENT WHERE B_NO = '$bn' AND B_RE = '$rs_b_re' AND SUBSTRING(B_PO, $reply_len, 1) <> '' ";

		} else {
			$begin_reply_char = "Z";
			$end_reply_char = "A";
			$reply_number = -1;

			$sql = " SELECT MIN(SUBSTRING(B_PO, $reply_len, 1)) as reply FROM TBL_BOARD_COMMENT WHERE B_NO = '$bn' B_RE = '$rs_b_re' and SUBSTRING(B_PO, $reply_len, 1) <> '' ";

		}
		
		if ($rs_b_po) $sql .= " AND B_PO like '$rs_b_po%' ";
		
		//echo $sql;

		$result = mysql_query($sql,$conn);
		$row   = mysql_fetch_array($result);

		if (!$row[reply])
			$reply_char = $begin_reply_char;
		else if ($row[reply] == $end_reply_char) {// A~Z은 26 입니다.
			$err_mag = "더 이상 답변하실 수 없습니다.\n\n답변은 26개 까지만 가능합니다.";
			$err_mag = rawurlencode(iconv("CP949", "UTF-8", $err_mag));
			echo $err_mag;
			mysql_close($conn);	
			exit;
		} else
			$reply_char = chr(ord($row[reply]) + $reply_number);

		$reply = $rs_b_po . $reply_char;

		if ($_SESSION['s_adm_no']) {
			
			$writer_pw		= $_SESSION['s_adm_pw'];
			$writer_id		= $_SESSION['s_adm_id'];
			$writer_nick	= $_SESSION['s_adm_nm'];
			$writer_nm		= $_SESSION['s_adm_nm'];

		} else {

			if ($_SESSION['s_m_id']) {
				$writer_id		= $_SESSION['s_m_id'];
				$writer_pw		= $m_m_password;
				$writer_nick	= $m_m_nick;
				$writer_nm		= $m_m_name;
			} else {
				$writer_id		= "";
				$writer_pw		= sql_password($conn, trim($writer_pw));
				$writer_nick	= trim($writer_nm);
				$writer_nm		= trim($writer_nm);
			}
		}

		// 답변의 원글이 비밀글이라면 패스워드는 원글과 동일하게 넣는다.
		if ($secret_tf == "Y")
			$wr_password = $rs_writer_pw;

		$b_re = $rs_b_re;
		$b_po = $reply;

		$arr_data = array("B_NO"=>$bn,
											"B_PO"=>$b_po,
											"B_RE"=>$b_re,
											"WRITER_ID"=>$writer_id,
											"WRITER_NM"=>$writer_nm,
											"WRITER_NICK"=>$writer_nick,
											"WRITER_PW"=>$writer_pw,
											"TITLE"=>$title,
											"REF_IP"=>$ref_ip,
											"CONTENTS"=>$contents,
											"SECRET_TF"=>$secret_tf,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$s_adm_no);

		$new_c_no =  insertBoardComment($conn, $arr_data);

	}


	if ($mode == "L") {
		if ($b_comment_tf == "Y") {
			
			// 커맨트 리스트 출력 입니다.
			if ($cPage == 0) $cPage = "1";

			if ($cPage <> "") {
				$cPage = (int)($cPage);
			} else {
				$cPage = 1;
			}

			if ($cPageSize <> "") {
				$cPageSize = (int)($cPageSize);
			} else {
				$cPageSize = 10000;
			}
			$cPageBlock = 10;
			
			$con_use_tf = "Y";
			$con_del_tf = "N";

			$cListCnt =totalCntBoardComment($conn, $bn, $con_writer_id, $con_use_tf, $con_del_tf, $f, $s);

			$cTotalPage = (int)(($cListCnt - 1) / $cPageSize + 1) ;

			if ((int)($cTotalPage) < (int)($cPage)) {
				$cPage = $cTotalPage;
			}
			$arr_rs = listBoardComment($conn, $bn, $con_writer_id, $con_use_tf, $con_del_tf, $f, $s, $cPage, $cPageSize,$cListCnt);
			
			if (sizeof($arr_rs) > 0) {
				for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

					$rn							= trim($arr_rs[$j]["rn"]);
					$C_NO						= trim($arr_rs[$j]["C_NO"]);
					$B_RE						= trim($arr_rs[$j]["B_RE"]);
					$B_PO						= trim($arr_rs[$j]["B_PO"]);
					$SECRET_TF			= trim($arr_rs[$j]["SECRET_TF"]);
					$WRITER_ID			= trim($arr_rs[$j]["WRITER_ID"]);
					$WRITER_NM			= SetStringFromDB($arr_rs[$j]["WRITER_NM"]);
					$WRITER_NICK		= SetStringFromDB($arr_rs[$j]["WRITER_NICK"]);
					$TITLE					= SetStringFromDB($arr_rs[$j]["TITLE"]);
					$TITLE					= check_html($TITLE);
					$CONTENTS				= SetStringFromDB($arr_rs[$j]["CONTENTS"]);
					$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
					$REF_IP					= trim($arr_rs[$j]["REF_IP"]);
					

					if ($_SESSION['s_adm_no'] == "" && $SECRET_TF == "Y") {
						$CONTENTS = "<font color='orange'> * 비밀글 입니다.</font>";
					}

					$cc_i_arr = array("<form",	"</form",	"<input",	"<textarea",	"</textarea",	"girin_comment" ,	"javascript:gbc_" );
					$cc_o_arr = array("<orm",	"</orm",	"<nput",	"<extarea",		"</extarea",	"glgln_comment" ,	"javascript:gbcc_");
					$str_contents		= nl2br(replace_tag_parts($CONTENTS, $cc_i_arr, $cc_o_arr));
					
					// 글쓴이.
					if ($b_realname_tf == "Y") {
						$str_writer_name = $WRITER_NM;
					} else {
						$str_writer_name = $WRITER_NICK;
					}
					
					/*
					if ($WRITER_ID == "own") {
						$str_writer_name = $WRITER_NM." (".$WRITER_NICK.")";
					} else {
						$str_writer_name = $WRITER_NM." (임직원 가족 [".$WRITER_NICK."])";
					}
					*/

					//$str_writer_name = rawurlencode(iconv("CP949", "UTF-8", $str_writer_name));
					//$str_contents = rawurlencode(iconv("CP949", "UTF-8", $str_contents));

					$str_reg_date = date("Y-m-d H:i:s",strtotime($REG_DATE));
					
					$DEPTH = strlen($B_PO);
					$str_depth_class = "";
					if ($DEPTH) {
						if ($DEPTH > 9) {
							$str_depth_class = "class='depth".$DEPTH."'";
						} else {
							$str_depth_class = "class='depth0".$DEPTH."'";
						}
					}

?>

		<li>
			<p><?=nl2br($str_contents)?></p>
			<span><?=$str_reg_date?> &nbsp;&nbsp;&nbsp;&nbsp; <?=$str_writer_name?><? if ($_SESSION['s_adm_no']) { ?>&nbsp;&nbsp;&nbsp;&nbsp;[<?=$REF_IP?>]<? } ?></span>
			<span class="btn-moreaction">

				<?
					$del_btn_flag = false;

					if ($_SESSION['s_m_id']) {
						if ($_SESSION['s_m_id'] == $WRITER_ID) $del_btn_flag = true;
					} else {
						if ($WRITER_ID == "")
							$del_btn_flag = true;
					}

					if ($_SESSION['s_adm_no']) {
						$del_btn_flag = true;
						$is_guest = false;
					}

				?>
				<!--<a href="#">수정</a>-->
				<? if ($del_btn_flag) {?> 
				<a href="javascript:js_comment_delete('<?=$C_NO?>');">삭제</a>
				<? } ?>
				<!--
				<button type="button">수정/삭제</button>
				<em>
					<? if ($del_btn_flag) {?> 
					<a href="javascript:js_comment_delete('<?=$C_NO?>');"><?=iconv("EUC-KR","UTF-8","삭제")?></a>
						<? } ?>
				</em>
				-->
			</span>
		</li>

<?
				}
			}
?>
<?

		$str = rawurlencode(iconv("CP949", "UTF-8", $str));
		//$str	= iconv("EUC-KR","UTF-8", $str);
		echo $str;

		}
	}


	if ($mode == "S") {
			
			$cno = trim($cno);
			$arr_rs = selectBoardComment($conn, $cno);
			
			$C_NO						= trim($arr_rs[0]["C_NO"]);
			$SECRET_TF			= trim($arr_rs[0]["SECRET_TF"]);
			$WRITER_ID			= trim($arr_rs[0]["WRITER_ID"]);
			$CONTENTS				= SetStringFromDB($arr_rs[0]["CONTENTS"]);
			//$cc_i_arr = array("<form",	"</form",	"<input",	"<textarea",	"</textarea",	"girin_comment" ,	"javascript:gbc_" );
			//$cc_o_arr = array("<orm",	"</orm",	"<nput",	"<extarea",		"</extarea",	"glgln_comment" ,	"javascript:gbcc_");
			//$str_contents		= nl2br(replace_tag_parts($CONTENTS, $cc_i_arr, $cc_o_arr));
			
			if (!$_SESSION['s_adm_no']) {

				if (($_SESSION['s_m_id'] != $WRITER_ID) || ($_SESSION['s_m_id'] =="")) {
					
					$err_mag = "작성자만 수정 가능합니다.";
					$err_mag = rawurlencode(iconv("CP949", "UTF-8", $err_mag));
					echo $err_mag;
					mysql_close($conn);	
					exit;
				}
			}
			
?>
<form name="frm_comment_<?=$C_NO?>" method="get">
	<input type="hidden" name="mode" value="update">
	<span>
		<input type="checkbox"  name="secret_tf" id="secret_tf_<?=$C_NO?>" class="chk" <? if ($SECRET_TF == "Y") echo "checked"?> value="Y"/> <?=iconv("EUC-KR","UTF-8","비밀글")?> <img src="../images/bbs/ic_lock.jpg" alt="" />
	</span>
	<textarea name="contents" id="contents_<?=$C_NO?>"><?=iconv("EUC-KR","UTF-8",$CONTENTS)?></textarea> 
	<a href="javascript:void();" onClick="js_comment_reply_save('<?=$C_NO?>');"><img src="../images/bbs/co_btn_write.gif" alt="<?=iconv("EUC-KR","UTF-8","의견등록")?>" /></a>
</form>
<?
		$str = rawurlencode(iconv("CP949", "UTF-8", $str));
		//$str	= iconv("EUC-KR","UTF-8", $str);
		echo $str;
	}

	mysql_close($conn);	
?>