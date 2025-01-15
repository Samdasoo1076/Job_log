		<colgroup>
			<col style="width:3%" />
			<col style="width:5%" />
			<col style="width:10%" />
			<col style="width:35%" />
			<col style="width:10%" />
			<col style="width:12%" />
			<col style="width:10%" />
			<col style="width:5%" />
			<col style="width:10%" />
		</colgroup>
		<tbody>
		<tr>
			<th scope="col"><input type="checkbox" class="checkbox" id="all_chk_no" name="all_chk_no" alt="chk_no" onClick="js_check()" /></th>
			<th scope="col">No.</th>
			<th scope="col">구분</th>
			<th scope="col">제목</th>
			<th scope="col">작성자</th>
			<th scope="col">등록일</th>
			<th scope="col">노출여부</th>
			<th scope="col">조회수</th>
			<th scope="col">작성자IP</th>
		</tr>
	<?
		$nCnt = 0;
		
		if (sizeof($arr_rs) > 0) {
			
			for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
				
				$rn							= trim($arr_rs[$j]["rn"]);
				$B_NO						= trim($arr_rs[$j]["B_NO"]);
				$B_RE						= trim($arr_rs[$j]["B_RE"]);
				$B_PO						= trim($arr_rs[$j]["B_PO"]);
				$B_CODE					= trim($arr_rs[$j]["B_CODE"]);
				$CATE_01				= trim($arr_rs[$j]["CATE_01"]);
				$CATE_02				= trim($arr_rs[$j]["CATE_02"]);
				$CATE_03				= trim($arr_rs[$j]["CATE_03"]);
				$CATE_04				= trim($arr_rs[$j]["CATE_04"]);
				$WRITER_NM			= trim($arr_rs[$j]["WRITER_NM"]);
				$TITLE					= SetStringFromDB($arr_rs[$j]["TITLE"]);
				$REG_ADM				= trim($arr_rs[$j]["REG_ADM"]);
				$HIT_CNT				= trim($arr_rs[$j]["HIT_CNT"]);
				$REF_IP					= trim($arr_rs[$j]["REF_IP"]);
				$MAIN_TF				= trim($arr_rs[$j]["MAIN_TF"]);
				$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
				$COMMENT_TF			= trim($arr_rs[$j]["COMMENT_TF"]);
				$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
				$SECRET_TF			= trim($arr_rs[$j]["SECRET_TF"]);
				$F_CNT					= trim($arr_rs[$j]["F_CNT"]);
				$REPLY_DATE			= trim($arr_rs[$j]["REPLY_DATE"]);
				$REPLY_STATE		= trim($arr_rs[$j]["REPLY_STATE"]);
				$TOP_TF					= trim($arr_rs[$j]["TOP_TF"]);
				$VIEW_TF				= trim($arr_rs[$j]["VIEW_TF"]);

				$rs_admin			= selectAdmin($conn, $REG_ADM);
				$rs_adm_name	= SetStringFromDB($rs_admin[0]["ADM_NAME"]);

				$CATE_01 = str_replace("^"," & ",$CATE_01);

				$is_new = "";
				if ($REG_DATE >= date("Y-m-d H:i:s", (strtotime("0 day") - ($b_new_hour * 3600)))) {
					if ($MAIN_TF <> "N") {
						$is_new = "<img src='../images/bu/ic_new.png' alt='새글' width='35'/> ";
					}
				}

				$REG_DATE = date("Y-m-d H:i",strtotime($REG_DATE));

				if ($b_board_type == "EVENT") {
					$TITLE = $TITLE." (기간 : ".$CATE_03." ~ ".$CATE_04.")";
				}

				if ($USE_TF == "Y") {
					$STR_USE_TF = "<font color='navy'>보이기</font>";
				} else {
					$STR_USE_TF = "<font color='red'>보이지않기</font>";
				}
				
				if ($COMMENT_TF == "Y") {
					$STR_COMMENT_TF = "<font color='navy'>사용</font>";
				} else {
					$STR_COMMENT_TF = "<font color='red'>사용안함</font>";
				}

				$STR_REPLY_STATE = "";
				if ($REPLY_STATE == "Y") {
					$STR_REPLY_STATE = "<font color='navy'>답변 완료</font>";
				} else {
					$STR_REPLY_STATE = "<font color='red'>답변 대기</font>";
				}

				$R_CNT = getReplyCount($conn, $B_CODE, $B_NO);

				if ($R_CNT > 0) {
					$reply_cnt = "<span class=\"orange f11\">(".$R_CNT.")</span>";
				} else {
					$reply_cnt = "";
				}

				$space = "";
				
				$DEPTH = strlen($B_PO);

				for ($l = 0; $l < $DEPTH; $l++) {
					if ($l != 1)
						$space .= "&nbsp;";
					else
						$space .= "&nbsp;";
	
					if ($l == ($DEPTH - 1))
						$space .= "&nbsp;┗";
	
					$space .= "&nbsp;";
				}

				if ($SECRET_TF == "Y") 
					$str_lock = "<img src='../images/bu/ic_lock.jpg' alt='' />";
				else 
					$str_lock = "";

				if ($F_CNT > 0) 
					$str_file = "<img src='../images/bu/ic_file2.gif' alt='' />";
				else 
					$str_file = "";

				if ($TOP_TF == "Y") 
					$top_style = "background: #f5f5f5;";
				else 
					$top_style = "";

				if (($VIEW_TF == "1") && ($USE_TF =="Y")) 
					$view_style = "font-weight:bold;";
				else 
					$view_style = "";


	?>
		<tr style="<?=$top_style?> <?=$view_style?>"> 

			<td><input type="checkbox" name="chk[]" value="<?=$B_NO?>"></td>
			<td>
				<? if ($TOP_TF == "Y") { ?>
				<a href="javascript:js_view('<?=$B_CODE?>','<?=$B_NO?>');"><b>공지</b></a>
				<? } else { ?>
				<a href="javascript:js_view('<?=$B_CODE?>','<?=$B_NO?>');"><?= $rn ?></a>
				<? } ?>
			</td>
			<td>
			<? if ($CATE_01) {?>
			[<?=getDcodeName($conn,"MOJIB_TYPE", $CATE_01)?>]&nbsp;
			<? } ?>
			</td>
			<td style="text-align:left"><?=$space?>
			<?=$is_new?>
			<a href="javascript:js_view('<?=$B_CODE?>','<?=$B_NO?>');"><?=$TITLE?></a> <?=$reply_cnt?> <?=$str_lock?> <?=$str_file?></td>
			<td><?= $WRITER_NM ?></td>
			<td><?= $REG_DATE ?></td>
			<td class="filedown">
				<a href="javascript:js_toggle('<?=$B_NO?>','<?=$USE_TF?>');"><span><?=$STR_USE_TF?></span></a>
			</td>
			<td class="filedown">
				<?=$HIT_CNT?>
			</td>
			<td class="filedown">
				<?=$REF_IP?>
			</td>
		</tr>
	<?			
			}
		} else { 
	?> 
		<tr>
			<td height="50" align="center" colspan="10">데이터가 없습니다. </td>
		</tr>
	<? 
		}
	?>
		</tbody>