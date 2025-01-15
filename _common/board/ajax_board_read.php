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
	require "../../_classes/biz/board/board.php";
	require "../../_classes/biz/board/board_comment.php";
	require "../../_classes/biz/member/member.php";
	require "../../_classes/com/util/AES2.php";

	$b_code							= $_POST['b_code']!=''?$_POST['b_code']:$_GET['b_code'];
	$b_no								= $_POST['b_no']!=''?$_POST['b_no']:$_GET['b_no'];

	$b									= $_POST['b']!=''?$_POST['b']:$_GET['b'];
	$bn									= $_POST['bn']!=''?$_POST['bn']:$_GET['bn'];

	require "../../_common/board/read.php";

?>
<table>
	<colgroup>
		<col width="100%" />
	</colgroup>
	<tbody>
		<tr>
			<td>
				<?
					if (sizeof($arr_rs_files) > 0) {
				?>
					<em class="file">첨부파일 : 
				<?
						for ($j = 0 ; $j < sizeof($arr_rs_files); $j++) {
				
							//FILE_NO, BB_CODE, BB_NO, FILE_NM, FILE_RNM, FILE_PATH, FILE_SIZE, FILE_EXT, HIT_CNT 
							$RS_FILE_NO			= trim($arr_rs_files[$j]["FILE_NO"]);
							$RS_BB_CODE			= trim($arr_rs_files[$j]["BB_CODE"]);
							$RS_BB_NO				= trim($arr_rs_files[$j]["BB_NO"]);
							$RS_FILE_NM			= trim($arr_rs_files[$j]["FILE_NM"]);
							$RS_FILE_RNM		= trim($arr_rs_files[$j]["FILE_RNM"]);
							$RS_FILE_PATH		= trim($arr_rs_files[$j]["FILE_PATH"]);
							$RS_FILE_SIZE		= trim($arr_rs_files[$j]["FILE_SIZE"]);
							$RS_FILE_EXT		= trim($arr_rs_files[$j]["FILE_EXT"]);
							$RS_HIT_CNT			= trim($arr_rs_files[$j]["HIT_CNT"]);

							if ($RS_FILE_NM <> "") {
				?>
					<a href="../../_common/new_download_file.php?menu=boardfile&file_no=<?= $RS_FILE_NO ?>"><?=$RS_FILE_RNM?></a>
				<?
							}
							$f_Cnt = $f_Cnt + 1;
						}
				?>
					</em>
				<?
					} 
				?>
				
				<?=$content?>
				
			</td>
		</tr>
	</tbody>
</table>
<!--
<div style="height:20px;"></div>

<div class="replylist">
	<form name="frm_comment" method="get">
	<input type="hidden" name="mode" id="mode" value=""/>
	<input type="hidden" name="b_code" id="b_code" value="<?=$rs_b_code?>"/>
	<input type="hidden" name="b_no" id="b_no" value="<?=$rs_b_no?>"/>
	<input type="hidden" name="secret_tf" id="secret_tf" value="N"/>
	<input type="hidden" name="encrypt_str" id="encrypt_str" value="<?=$temp_str?>">
	<fieldset>
		<legend>댓글 등록</legend>
		<div class="pic"><strong><?=$_SESSION['s_adm_nm']?></strong></div>
		<span></span>
		<p><textarea cols="" rows="" placeholder="댓글을 입력해주세요." name="contents" id="contents"></textarea><button type="button" onClick="js_comment_save();">등록</button></p>
	</fieldset>
	</form>
	
	<ul id="div_recomm_list">
		<li>
			<span class="pic"><img src="/upload_data/profile/20181212135754_3.jpg" style="width:44px" alt=""></span>
			<p>저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다.</p>
			<span>2018.10.10 &nbsp;&nbsp;&nbsp;&nbsp; 홍길동</span>
			<span class="btn-moreaction"><button type="button">수정/삭제</button><em><a href="#">수정</a><a href="#">삭제</a></em></span>
		</li>
		<li>
			<span class="pic"><img src="/upload_data/profile/20181212135754_3.jpg" style="width:44px" alt=""></span>
			<p>저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다.</p>
			<span>2018.10.10 &nbsp;&nbsp;&nbsp;&nbsp; 홍길동</span>
		</li>
		<li>
			<span class="pic"><img src="/upload_data/profile/20181212135754_3.jpg" style="width:44px" alt=""></span>
			<p>저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다.</p>
			<span>2018.10.10 &nbsp;&nbsp;&nbsp;&nbsp; 홍길동</span>
			<span class="btn-moreaction"><button type="button">수정/삭제</button><em><a href="#">수정</a><a href="#">삭제</a></em></span>
		</li>
		<li>
			<span class="pic"><img src="/upload_data/profile/20181212135754_3.jpg" style="width:44px" alt=""></span>
			<p>저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다저는 조금 늦을 것 같습니다. 8시까지는 가겠습니다.</p>
			<span>2018.10.10 &nbsp;&nbsp;&nbsp;&nbsp; 홍길동</span>
		</li>
	</ul>
</div>
-->
<?
	db_close($conn);
?>