<div class="content-header">
	<!-- Sub Visual -->
	<div class="visual-wrap <?=$rs_page_info05?>">
		<div class="inner">
			<h1><?=$depth_01_page_name?></h1>
			<div class="location">
				<span class="lc-home"><span class="blind">홈</span></span>
				<span class="lc-split"><span class="blind">&gt;</span></span>
				<span class="lc-cate"><span><?=$depth_01_page_name?></span></span>
				<span class="lc-split"><span class="blind">&gt;</span></span>
				<span class="lc-current" aria-current="page"><?=$depth_02_page_name?></span>
			</div>
		</div>
	</div>
	<!-- // Sub Visual -->
	<!-- Sub Nav -->
	<div class="snb-wrap">
		<nav class="nav dep2-nav swiper">
			<ul class="dep2-list swiper-wrapper">
			<?
				if (sizeof($arr_menu_rs_02) > 0) {

					for ($jj = 0 ; $jj < sizeof($arr_menu_rs_02); $jj++) {

						$SUB_PAGE_NO			= trim($arr_menu_rs_02[$jj]["PAGE_NO"]);
						$SUB_PAGE_CD			= trim($arr_menu_rs_02[$jj]["PAGE_CD"]);
						$SUB_PAGE_NAME		= trim($arr_menu_rs_02[$jj]["PAGE_NAME"]);
						$SUB_PAGE_URL			= trim($arr_menu_rs_02[$jj]["PAGE_URL"]);
						$SUB_PAGE_INFO01	= trim($arr_menu_rs_02[$jj]["PAGE_INFO01"]);
						$SUB_PAGE_INFO02	= trim($arr_menu_rs_02[$jj]["PAGE_INFO02"]);
						$SUB_PAGE_INFO03	= trim($arr_menu_rs_02[$jj]["PAGE_INFO03"]);
						$SUB_PAGE_INFO04	= trim($arr_menu_rs_02[$jj]["PAGE_INFO04"]);
						$SUB_URL_TYPE			= trim($arr_menu_rs_02[$jj]["URL_TYPE"]);

						$SUB_PAGE_NAME = str_replace("<br />","",$SUB_PAGE_NAME);
						$SUB_PAGE_NAME = str_replace("<br/>","",$SUB_PAGE_NAME);
						$SUB_PAGE_NAME = str_replace("<br>","",$SUB_PAGE_NAME);

						if ($this_depth01.$this_depth02 == $SUB_PAGE_CD) {
							$depth_02_page_name = $SUB_PAGE_NAME;
							$depth_02_class = $SUB_PAGE_INFO03;
						}

						if ($this_depth01 == left($SUB_PAGE_CD,2)) {

							if ($SUB_PAGE_URL == "") {
								$SUB_PAGE_URL = "/pages/?p=".$SUB_PAGE_NO."&title=".$SUB_PAGE_NAME;
							} else {
								if (strpos($SUB_PAGE_URL, "script:") == true) {
									$SUB_PAGE_URL = $SUB_PAGE_URL;
								} else {
									$SUB_PAGE_URL = $SUB_PAGE_URL;
								}
							}
			?>
				<li class="dep2-item swiper-slide">
					<a href="<?=$SUB_PAGE_URL?>" <? if ($SUB_URL_TYPE == "Y") { ?>target="_blank" title="새창열기"<? } ?> class="dep2-link <? if ($this_depth01.$this_depth02 == $SUB_PAGE_CD) { ?>is-current<? } ?>"><?=$SUB_PAGE_NAME?></a>
				</li>
			<? 
						}
					}
				}	
			?>

			</ul>
		</nav>
	</div>
	<!-- // Sub Nav -->
</div>