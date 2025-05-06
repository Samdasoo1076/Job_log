<?
if (!function_exists("createThumb")) {
    // 원본 이미지를 넘기면 비율에 따라 썸네일 이미지를 생성함
    function createThumb($imgWidth, $imgHeight, $imgSource, $imgThumb='') {
        if (!$imgThumb)
            $imgThumb = $imgSource;

        //@unlink($imgSource); // 원본 이미지 파일명
        //echo $imgSource; exit;

        $size = getimagesize($imgSource);
        if ($size[2] == 1)
            $source = imagecreatefromgif($imgSource);
        else if ($size[2] == 2)
            $source = imagecreatefromjpeg($imgSource);
        else if ($size[2] == 3)
            $source = imagecreatefrompng($imgSource);
        else
            return 0;

        $rate = $imgWidth / $size[0];
        $height = (int)($size[1] * $rate);

        if ($size[0] < $imgWidth || $size[1] < $imgHeight) {
            $xWidth = $imgWidth;
            $xHeight = $imgHeight;
            $height = $imgHeight;
        } else {
            $xWidth = $size[0];
            $xHeight = $size[1];
        }
        $target = @imagecreatetruecolor($imgWidth, $imgHeight);
        $white = @imagecolorallocate($target, 255, 255, 255);
        @imagefilledrectangle($target, 0, 0, $imgWidth, $imgHeight, $white);
        @imagecopyresampled($source, $source, 0, 0, 0, 0, $imgWidth, $height, $xWidth, $xHeight);
        @imagecopy($target, $source, 0, 0, 0, 0, $imgWidth, $imgHeight);

        @imagejpeg($target, $imgThumb, 100);
        @chmod($imgThumb, 0606); // 추후 삭제를 위하여 파일모드 변경
    }
}

// 원본 이미지를 넘기면 비율에 따라 썸네일 이미지를 생성함
function createThumb2($imgWidth, $imgHeight, $imgSource, $imgThumb='', $mb_id) {
    global $g4;

    if (!$imgThumb)
        $imgThumb = $imgSource;

    $size = getimagesize($imgSource);
    if ($size[2] == 1)
        $source = imagecreatefromgif($imgSource);
    else if ($size[2] == 2)
        $source = imagecreatefromjpeg($imgSource);
    else if ($size[2] == 3)
        $source = imagecreatefrompng($imgSource);
    else
        return 0;

    $rate = $imgWidth / $size[0];
    $height = (int)($size[1] * $rate);

    $target = @imagecreatetruecolor($imgWidth, $imgHeight);
    //@imagecolorallocate($source, 255, 255, 255);
    @imagecopyresampled($source, $source, 0, 0, 0, 0, $imgWidth, $height, $size[0], $size[1]);
    @imagecopy($target, $source, 0, 0, 0, 0, $imgWidth, $imgHeight);
    //$white = imagecolorallocate($target, 255, 255, 255);
    $white = imagecolorallocate($target, 150, 150, 150);
    $black = imagecolorallocate($target, 100, 100, 100);
    $margin=0;
    $n=0;
    for ($y=5;$y<$imgHeight;$y+=70) {
        for ($x=5;$x<$imgWidth;$x+=120) {
            $string = ($n%2) ? "" : "";//$mb_id;
            $color = ($n%2) ? $white : $black;
            imagestring($target, 1, $x+$margin, $y, $string, $color);
            $n++;
        }
        $margin+=5;
    }
    //@imagettftext($target, 20, 0, 10, 20, $white, "$g4[path]/img/arialbd.ttf", "sir.co.kr");
    /*
    $font=ImagePsLoadFont("$g4[path]/img/cibt____.pfb");
    ImagePsText($target, "Testing... It worked!", $font, 32, $white, $white, 32, 32);
    ImagePsFreeFont($font);
    */
    @imagejpeg($target, $imgThumb, 100);
    @chmod($imgThumb, 0606); // 추후 삭제를 위하여 파일모드 변경
}

##################################################################################
##################################################################################
##
## function Thumbnail_Create;
##
##  ***usage
##
##  $return_value =  Thumbnail_Create($source_file,$save_thumbnail_file_name, $width, $height);
##
##  $return_value is array
##
##          $return_value[0] : converted or source file name
##          $return_value[1] ; created thumbnail file_name
##
##
##
##  ***parameter
##
##   -input : source file(path+file name) , save file name to output(path+file name) , width value , height value
##   -return value : array(convert source file name,thumbnail file name);
##
##  *** create rule
##
##  -jpeg,png files : create thumbnail file to given format.
##  -gif files : create thumbnail file to png file format.
##  -bmp files : delete source file(bmp) after create jpeg file from source file(bmp).
##              and create thumbnail file to jpeg files from converted files.
##
##  *** requirements program, libraries
##
##   -gif2png - to create png file from gif files
##   -cjpeg - to create jpeg files from bmp files
##
##    ** win32 server is not support
##
##################################################################################

function Thumbnail_Create($org_file,$save_file,$max_width,$max_height,$_size_mode="v"){
	$img_info = getImageSize($org_file);
	##############################################################
	//Gif
	if($img_info[2] == 1){
		/*##$src_img = ImageCreateFromGif($org_file);
		passthru("gif2png -O $org_file");
		//$msgs = system("gif2png -Ov $org_file",$msgs1);
		//$msgs = exec("gif2png -Ov $org_file",$msgs1);
		//die("msgs:".$msgs."<br>msgs1 :".$msgs1);
		$corg_file_pre = substr($org_file,0,-4);
		$corg_file = $corg_file_pre.".png";
		//passthru("mv ".$corg_file_pre.".png ".$corg_file_pre.".gif");
		//passthru("rm -rf ".$corg_file_pre.".p*");
		if(file_exists($corg_file)){
			$src_img = ImageCreateFromPNG($corg_file);
			//@unlink($corg_file);
			passthru("rm -rf ".$corg_file_pre.".p*");
		}else{
			return 0;
		}
		*/
		$src_img = ImageCreateFromGif($org_file);
	############################################################
	//jpg
	}elseif($img_info[2] == 2){
		$src_img = ImageCreateFromJPEG($org_file);
	//png
	}elseif($img_info[2] == 3){
		$src_img = ImageCreateFromPNG($org_file);

	###########################################################
	//bmp -> convert jpg
	}elseif($img_info[2] == 6){
		$corg_file = substr($org_file,0,-4).".jpg";
		$save_file = substr($save_file,0,-4).".jpg";
		passthru("cjpeg -optimize -outfile $corg_file $org_file");
		if(file_exists($corg_file)){
			$src_img = ImageCreateFromJPEG($corg_file);
			@unlink($org_file);
			$org_file = $corg_file;
		}else{
			return 0;
		}
	}else{
		return 0;
	}

	$img_width = $img_info[0];
	$img_height = $img_info[1];

	if($img_width > $max_width || $img_height > $max_height){
		if($img_width == $img_height){
			$dst_width = $max_width;
			$dst_height = $max_height;
		}else if($img_width > $img_height){
			$dst_width = $max_width;
			$dst_height = ceil(($max_width / $img_width) * $img_height);
		}else{
			//$dst_width = $max_width;
			//$dst_height = ceil(($max_width / $img_width) * $img_height);
			$dst_height = $max_height;
			$dst_width = ceil(($max_height / $img_height) * $img_width);
		}
	}else{
		$dst_width = $img_width;
		$dst_height = $img_height;
	}
	//echo $dst_width." x ".$dst_height."<br>";
	/*
	if($dst_width < $max_width) $srcx = ceil(($max_width - $dst_width)/2); else $srcx = 0;
	if($dst_height < $max_height) $srcy = ceil(($max_height - $dst_height)/2); else $srcy = 0;
	##if($img_info[2] == 1){
	##      $dst_img = imagecreate($max_width, $max_height);
	##}else{
		//$dst_img = imagecreatetruecolor($max_width, $max_height);

	##}
	//echo $srcx." x ".$srcy."<br>";
	*/

	if($_size_mode =="f"){
		$dst_width = $max_width;
		$dst_height = ceil(($max_width / $img_width) * $img_height);
		if($dst_width < $max_width) $srcx = ceil(($max_width - $dst_width)/2); else $srcx = 0;
		if($dst_height < $max_height) $srcy = ceil(($max_height - $dst_height)/2); else $srcy = 0;
		$dst_img = imagecreatetruecolor($max_width, $max_height);

	}else{
		$dst_img = imagecreatetruecolor($dst_width, $dst_height);
	}
	if (!$src_img) {
		$bgc = ImageColorAllocate($dst_img, 240, 240, 240);
		$src_img = ImageCreate ($max_width, $max_height);
		$tc  = ImageColorAllocate ($dst_img, 0, 0, 0);
		ImageFilledRectangle($dst_img, 0, 0, $max_width, $max_height, $bgc);
		ImageString ($dst_img, 2, 5, 15, "Thumbnail Image", $tc);
		ImageString ($dst_img, 2, 7, 30, "Creating Error..", $tc);
	}else{
		$bgc = ImageColorAllocate($dst_img, 255, 255, 255);
		ImageFilledRectangle($dst_img, 0, 0, $max_width, $max_height, $bgc);
		ImageCopyResampled($dst_img, $src_img, $srcx, $srcy, 0, 0, $dst_width, $dst_height, ImageSX($src_img),ImageSY($src_img));
		//ImageCopyResized($dst_img, $src_img, $srcx, $srcy, 0, 0, $dst_width, $dst_height,  ImageSX($src_img),ImageSY($src_img));
		//echo "<div>최종 :  $dst_img / $src_img / ".$srcx."x".$srcy." / ".$dst_width."x".$dst_height."<br>";


	}

	if($img_info[2] == 1){
		ImageInterlace($dst_img);
		//ImageGif($dst_img, $save_file);
		//ImageInterlace($dst_img);
		ImagePNG($dst_img, $save_file);

	}elseif($img_info[2] == 2){
		ImageInterlace($dst_img);
		ImageJPEG($dst_img, $save_file);
	}elseif($img_info[2] == 3){
		ImageInterlace($dst_img);
		ImagePNG($dst_img, $save_file);
	}elseif($img_info[2] == 6){
		ImageInterlace($dst_img);
		ImageJPEG($dst_img, $save_file);
	}

	ImageDestroy($dst_img);
	ImageDestroy($src_img);
	$truns = array($org_file,$save_file);
	unset($img_info,$org_file,$save_file,$max_width,$max_height,$corg_file_pre,$corg_file,$src_img,$img_width,$img_height,$dst_width,$dst_height,$dst_img,$bgc,$tc);
	return $truns;

}
##################################################################################
##  function end!!
##################################################################################
##################################################################################

function getResizeImage($file_nm, $img_url, $file_path_150, $file_rnm_150, $width, $height, $path) {

	// 이미지가 저장 되어 있을 경우

	if ($file_nm <> "") {

		if (!function_exists("imagecopyresampled")) alert("GD 2.0.1 이상 버전이 설치되어 있어야 사용할 수 있는 갤러리 게시판 입니다.");

		// 메인Thumb
		$_thumb_type ="";
		$_thumb_size = array();
		$_thumb_type = "f" ;
		$_thumb_size[0] = $width;
		$_thumb_size[1] = $height;

		$_thumb1 = array($_thumb_type,$_thumb_size[0],$_thumb_size[1]);

		$data_path = $_SERVER[DOCUMENT_ROOT]."/upload_data/".$path;
		$img_path = "/upload_data/".$path."/simg";
		$thumb_path = $data_path.'/simg';

		//echo $thumb_path;

		@mkdir($thumb_path, 0707);
		@chmod($thumb_path, 0707);

		$file = $data_path."/".$file_nm;
		$thumb_path = $thumb_path."/s_".$width."_".$height."_".$file_nm;
		$img_path		= $img_path."/s_".$width."_".$height."_".$file_nm;
							
									
		if(file_exists($file)){
			if(!file_exists($thumb_path)){
				Thumbnail_Create($file, $thumb_path, $_thumb1[1], $_thumb1[2],$_thumb1[0] );
			}
		}

		$str_img_url = $img_path;

	} else {

		if ($img_url <> "") {
			$str_img_url = $img_url; 
		} else {
			if ($file_path_150 <> "") {
									
				if (!function_exists("imagecopyresampled")) alert("GD 2.0.1 이상 버전이 설치되어 있어야 사용할 수 있는 갤러리 게시판 입니다.");

				// 메인Thumb
				$_thumb_type ="";
				$_thumb_size = array();
				$_thumb_type = "f" ;
				$_thumb_size[0] = $width;
				$_thumb_size[1] = $height;

				$_thumb1 = array($_thumb_type,$_thumb_size[0],$_thumb_size[1]);

				$data_path	= $_SERVER[DOCUMENT_ROOT].$file_path_150;
				$img_path		= $file_path_150."simg";
				$thumb_path = $data_path.'simg';

				//echo $thumb_path;

				@mkdir($thumb_path, 0707);
				@chmod($thumb_path, 0707);

				$file = $data_path.$file_rnm_150;
				$thumb_path = $thumb_path."/s_".$width."_".$height."_".$file_rnm_150;
				$img_path		= $img_path."/s_".$width."_".$height."_".$file_rnm_150;
								
										
				if(file_exists($file)){
					if(!file_exists($thumb_path)){
						Thumbnail_Create($file, $thumb_path, $_thumb1[1], $_thumb1[2],$_thumb1[0] );
					}
				}

				$str_img_url = $img_path;
			} else {
				$str_img_url	= "/manager/images/no_img.gif";
			}
		}
	}
	return $str_img_url;
}



function getGoodsImage($file_nm, $img_url, $file_path_150, $file_rnm_150, $width, $height) {

	// 이미지가 저장 되어 있을 경우

	if ($file_nm <> "") {

		if (!function_exists("imagecopyresampled")) alert("GD 2.0.1 이상 버전이 설치되어 있어야 사용할 수 있는 갤러리 게시판 입니다.");

		// 메인Thumb
		$_thumb_type ="";
		$_thumb_size = array();
		$_thumb_type = "f" ;
		$_thumb_size[0] = $width;
		$_thumb_size[1] = $height;

		$_thumb1 = array($_thumb_type,$_thumb_size[0],$_thumb_size[1]);

		$data_path = $_SERVER[DOCUMENT_ROOT]."/upload_data/goods";
		$img_path = "/upload_data/goods/simg";
		$thumb_path = $data_path.'/simg';

		//echo $thumb_path;

		@mkdir($thumb_path, 0707);
		@chmod($thumb_path, 0707);

		$file = $data_path."/".$file_nm;
		$thumb_path = $thumb_path."/s_".$width."_".$height."_".$file_nm;
		$img_path		= $img_path."/s_".$width."_".$height."_".$file_nm;
							
									
		if(file_exists($file)){
			if(!file_exists($thumb_path)){
				Thumbnail_Create($file, $thumb_path, $_thumb1[1], $_thumb1[2],$_thumb1[0] );
			}
		}

		$str_img_url = $img_path;

	} else {

		if ($img_url <> "") {
			$str_img_url = $img_url; 
		} else {
			if ($file_path_150 <> "") {
									
				if (!function_exists("imagecopyresampled")) alert("GD 2.0.1 이상 버전이 설치되어 있어야 사용할 수 있는 갤러리 게시판 입니다.");

				// 메인Thumb
				$_thumb_type ="";
				$_thumb_size = array();
				$_thumb_type = "f" ;
				$_thumb_size[0] = $width;
				$_thumb_size[1] = $height;

				$_thumb1 = array($_thumb_type,$_thumb_size[0],$_thumb_size[1]);

				$data_path	= $_SERVER[DOCUMENT_ROOT].$file_path_150;
				$img_path		= $file_path_150."simg";
				$thumb_path = $data_path.'simg';

				//echo $thumb_path;

				@mkdir($thumb_path, 0707);
				@chmod($thumb_path, 0707);

				$file = $data_path.$file_rnm_150;
				$thumb_path = $thumb_path."/s_".$width."_".$height."_".$file_rnm_150;
				$img_path		= $img_path."/s_".$width."_".$height."_".$file_rnm_150;
								
										
				if(file_exists($file)){
					if(!file_exists($thumb_path)){
						Thumbnail_Create($file, $thumb_path, $_thumb1[1], $_thumb1[2],$_thumb1[0] );
					}
				}

				$str_img_url = $img_path;
			} else {
				$str_img_url	= "/manager/images/no_img.gif";
			}
		}
	}
	return $str_img_url;
}

function getStringBetween($str, $strStart, $strEnd) {

	$str = str_replace($strStart, strtoupper($strStart), $str);
	
	$PosStart	= strripos($str, $strStart);

	if ($PosStart) {

		$str_temp	= substr($str, $PosStart, strlen($str));

		$PosEnd		= strripos($str_temp, $strEnd) + $PosStart;

		if (($PosStart > 0) && ($PosEnd > 0)) {
			
			$getStringBetween = substr($str, $PosStart + strlen($strStart), ($PosEnd - $PosStart - strlen($strStart)));
			
			//echo $getStringBetween;
			return $getStringBetween;

		} else {
			return "";
		}
	} else {
		return "";
	}
}

function getImageName($str) {

	$str_temp = getStringBetween($str, "<img", ">");
	
	if ($str_temp <> "") {
		$PosStart	= strripos($str_temp, "src=");
		$str_temp	= substr($str_temp, ($PosStart + 5), strlen($str_temp));
		$PosStart	= strripos($str_temp, "\"");
		$str_temp	= substr($str_temp, 0, $PosStart);
	} else {
		$str_temp = "";
	}

	return $str_temp;
}
?>