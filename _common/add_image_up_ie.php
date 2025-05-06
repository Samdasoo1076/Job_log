<?session_start();?>
<?
	ini_set("memory_limit","256M");

	require "../_common/config.php";
	require "../_classes/com/util/AES2.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/util/class.upload.php";
	
	$type			= $_POST['type']!=''?$_POST['type']:$_GET['type'];
	$b_code		= $_POST['b_code']!=''?$_POST['b_code']:$_GET['b_code'];

	if ($type == "group") {

		$contents_list_w_width = 429;
		$contents_list_w_height = 270;

		$contents_list_h_width = 429;
		$contents_list_h_height = 270;

		$base_url = '/upload_data/group/';
		//$org_url = $base_url . 'org/';
		$org_url = $base_url;

		$dir = "../upload_data/group/";
		//$dir_dest = $dir . "org/";
		$dir_dest = $dir;
		
		$crop_type = "4";
	}

	if ($type == "interview_list") {

		$contents_list_w_width = 380;
		$contents_list_w_height = 240;

		$contents_list_h_width = 380;
		$contents_list_h_height = 240;

		$base_url = '/upload_data/board/'.$b_code.'/';
		//$org_url = $base_url . 'org/';
		$org_url = $base_url;

		$dir = "../upload_data/board/".$b_code."/";
		//$dir_dest = $dir . "org/";
		$dir_dest = $dir;
		
		$crop_type = "3";
	}

	if ($type == "interview_main") {

		$contents_list_w_width = 420;
		$contents_list_w_height = 588;

		$contents_list_h_width = 420;
		$contents_list_h_height = 588;

		$base_url = '/upload_data/board/'.$b_code.'/';
		//$org_url = $base_url . 'org/';
		$org_url = $base_url;

		$dir = "../upload_data/board/".$b_code."/";
		//$dir_dest = $dir . "org/";
		$dir_dest = $dir;
		
		$crop_type = "2";
	}

	if (($type == "notice") || ($type == "tour")) {

		$contents_list_w_width = 380;
		$contents_list_w_height = 240;

		$contents_list_h_width = 380;
		$contents_list_h_height = 240;

		$base_url = '/upload_data/board/'.$b_code.'/';
		//$org_url = $base_url . 'org/';
		$org_url = $base_url;

		$dir = "../upload_data/board/".$b_code."/";
		//$dir_dest = $dir . "org/";
		$dir_dest = $dir;
		
		$crop_type = "3";
	}

	if ($type == "culture") {

		$contents_list_w_width = 364;
		$contents_list_w_height = 515;

		$contents_list_h_width = 364;
		$contents_list_h_height = 515;

		$base_url = '/upload_data/board/'.$b_code.'/';
		//$org_url = $base_url . 'org/';
		$org_url = $base_url;

		$dir = "../upload_data/board/".$b_code."/";
		//$dir_dest = $dir . "org/";
		$dir_dest = $dir;
		
		$crop_type = "1";
	}

	if ($type == "space") {

		$contents_list_w_width = 365;
		$contents_list_w_height = 365;

		$contents_list_h_width = 365;
		$contents_list_h_height = 365;

		$base_url = '/upload_data/space/';
		//$org_url = $base_url . 'org/';
		$org_url = $base_url;

		$dir = "../upload_data/space/";
		//$dir_dest = $dir . "org/";
		$dir_dest = $dir;
		
		$crop_type = "0";
	}


	if( $_POST['uploaded'] == 'upload_now' && !$_FILES['image_file']['error'] ) { 

		// --- 디렉토리가 없을 경우 생성
		MAKEDIR($base_url );
		MAKEDIR($org_url);

		//--- 파일명 생성
		$file_head_num = mt_rand(100, 999) . "_" . mktime();

		$file_org_size = 0;
		$file_org_name = $_FILES['image_file']['name'] ;
		$file_org_size = $_FILES['image_file']['size'] ;
		$file_org_ext = file_extension($file_org_name) ;

		// --- 업로드 파일명
		$save_name = $file_head_num .".".strtolower($file_org_ext);

		// --- 업로드 파일 경로
		$up_path = $org_url;
		// --- 전체경로
		$save_list_image_path = trim($up_path . $save_name);
		
		//echo $save_list_image_path;
		//$UP_imageurl = 'http://' . $_SERVER['HTTP_HOST'] . $org_url . $save_name;

		// ---------- IMAGE UPLOAD ----------
		// ---- 앞페이지에서 넘어오는 파일 <input type=file name=my_field>
		//$handle = new Upload($_FILES['image_file']);

		$handle = new upload($_FILES['image_file']);

		// --- 원본파일 업로드
		if ($handle->uploaded) {

			//--- 업로드된 파일의 가로 세로 크기값 읽어오기
			list($img_width, $img_height) = getimagesize($_FILES['image_file']['tmp_name']); 
		
			if($img_height > $img_width){
				$list_max_width = $contents_list_h_width;
				$list_max_height = $contents_list_h_height;
			}else{
				$list_max_width = $contents_list_w_width;
				$list_max_height = $contents_list_w_height;
			}
		
			// --- 컨텐츠용 이미지
			$handle->file_new_name_body = $file_head_num;
			$handle->image_resize = true;
			$handle->image_ratio_crop = true;
			$handle->image_x = $list_max_width;
			$handle->image_y = $list_max_height;
			$handle->Process($dir_dest);

			// we delete the temporary files
			$handle-> Clean();

			//echo  $dir_dest . " --- " . $save_name;
			//exit;

		} else {
			// if we're here, the upload file failed for some reasons
			// i.e. the server didn't receive the file
			echo '<fieldset>';
			echo '  <legend>file not uploaded on the server</legend>';
			echo '  Error: ' . $handle->error . '';
			echo '</fieldset>';
		}


		// --- 이미지 리사이즈를 위해 실제 이미지의 가로/세로 크기 확인 
		$image_size = IMAGE_SIZE($contents_list_h_width, $contents_list_h_height, $_SERVER['DOCUMENT_ROOT'] . $save_list_image_path);

		// ---- 업로드된 임시파일 삭제
		//FILEDEL($__temp_crop_file2);

	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>이미지 첨부</title> 

<!-- 크롭처리를 위한 라이브러리 -->
<script src="/EXT/crop/js/jquery.min.js"></script>
<script src="/EXT/crop/js/jquery.Jcrop.js"></script>
<link rel="stylesheet" href="/EXT/crop/css/jquery.Jcrop.css" type="text/css" />
<link rel="stylesheet" href="/EXT/crop/css/demos.css" type="text/css" />

<script src="../editor/js/popup.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="../editor/css/popup.css" type="text/css"  charset="utf-8"/>

<script type="text/javascript">
// <![CDATA[
	
	function upload() {
		if(document.image_upload.image_file.value==""){
			alert('파일을 선택해 주세요');
			return false;
		}

		document.image_upload.submit();
	}

	function done() {

		<? if ($type == "interview_main") { ?>
			opener.document.getElementById('save_image_name_main').value = "<?=$save_name?>";
			opener.document.getElementById('list_image_preview_main').innerHTML = "<img src='<?=$save_list_image_path?>' height='100px' >";
			opener.document.getElementById('list_image_preview_main').style.visibility = 'visible';
		
		<? } else if ($type == "space") { ?>
			
			var tmp_images = opener.document.getElementById('space_image').value;
			
			//alert(tmp_images);

			if (tmp_images == "") {
				tmp_images = "<?=$save_name?>";
			} else { 
				tmp_images = tmp_images+"^<?=$save_name?>"; 
			}

			opener.document.getElementById('space_image').value = tmp_images;

			var arr_img_list = opener.document.getElementById('space_image').value.split("^");
			var tmp_html = "";

			for (var i = 0; i < arr_img_list.length ; i++) {
				if (arr_img_list[i] != "") {
					tmp_html = tmp_html + "<img src='/upload_data/space/"+arr_img_list[i]+"' width='170px'><a href='javascript:js_del_space_img(\""+arr_img_list[i]+"\")'><img src='/manager/images/btn_del.gif'></a>";
				}
			}
			opener.document.getElementById('img_area').innerHTML = tmp_html;

		<? } else { ?>
			opener.document.getElementById('save_image_name').value = "<?=$save_name?>";
			opener.document.getElementById('list_image_preview').innerHTML = "<img src='<?=$save_list_image_path?>' height='100px' >";
			opener.document.getElementById('list_image_preview').style.visibility = 'visible';
		<? } ?>
		closeWindow();
	}

// ]]>
</script>
</head>
<body>

<div class="wrapper">
	<div class="header">
		<h1>목록 이미지 첨부</h1>
	</div>

<?php
if( $_POST['uploaded'] != 'upload_now' ) :
?>

<div class="body">
	<form name="image_upload" id="image_upload" action="<?=$_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" accept-charset="utf-8">
	<dl class="alert">
		<dt>목록 이미지 첨부 하기</dt>
		<dd>
			<input type="hidden" id="type" name="type" value="<?=$type?>" />
			<input type="hidden" id="b_code" name="b_code" value="<?=$b_code?>" />
		</dd>

		<dd>
			<input type=hidden name="uploaded" value="upload_now">
			<input type=file name="image_file" id="image_file" size="30"><br>
		</dd>

	</dl>
</form>
	</div>
	<div class="footer">
		<p><a href="#" onclick="closeWindow();" title="닫기" class="close">닫기</a></p>
		<ul>
			<li class="submit"><a href="#" onclick="upload();" title="업로드" class="btnlink">업로드</a> </li>
			<li class="cancel"><a href="#" onclick="closeWindow();" title="취소" class="btnlink">취소</a></li>
		</ul>
	</div>

<?php
else:
?>
	<div class="body">
		<dl class="alert">
		    <dt>목록 이미지 첨부 확인 : <font color=black>등록을 누르시면 <b>"<?=$file_org_name;?>"</b> 가 첨부 됩니다.</font></dt>
		    <dd><img src='<?=$save_list_image_path?>' width='<?=$image_size["width"]?>' height='<?=$image_size["height"]?>' ></dd>
		</dl>
	</div>
	<div class="footer">
		<p><a href="#" onclick="closeWindow();" title="닫기" class="close">닫기</a></p>
		<ul>
			<li class="submit"><a href="#" onclick="done();" title="등록" class="btnlink">등록</a> </li>
			<li class="cancel"><a href="#" onclick="closeWindow();" title="취소" class="btnlink">취소</a></li>
		</ul>
	</div>

<?php
endif;
?>
	
</div>

</body>
</html>