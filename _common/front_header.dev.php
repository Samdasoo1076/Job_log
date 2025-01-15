<!-- <header id="header"> -->
<div class="header-pc-sec">
	<div class="gnb-area">
		<div class="header-logo">
			<a href="/">
				<img class="down" src="../../assets/images/common/img-logo-wh.svg" alt="WFI (재)원주미래산업진흥원 로고">
				<img class="up" src="../../assets/images/common/img-logo-bk.svg" alt="WFI (재)원주미래산업진흥원 로고">
				<span class="blind">WFI (재)원주미래산업진흥원</span>
			</a>
		</div>
		<nav class="header__nav">
				<ul class="dep1-list">
				<? 
					if (sizeof($arr_menu_rs_01) > 0) {

						for ($j = 0 ; $j < sizeof($arr_menu_rs_01); $j++) {

							$PAGE_NO			= trim($arr_menu_rs_01[$j]["PAGE_NO"]);
							$PAGE_CD			= trim($arr_menu_rs_01[$j]["PAGE_CD"]);
							$PAGE_NAME		= trim($arr_menu_rs_01[$j]["PAGE_NAME"]);
							$PAGE_URL			= trim($arr_menu_rs_01[$j]["PAGE_URL"]);
							$PAGE_INFO01	= trim($arr_menu_rs_01[$j]["PAGE_INFO01"]);
							$PAGE_INFO02	= trim($arr_menu_rs_01[$j]["PAGE_INFO02"]);
							$PAGE_INFO03	= trim($arr_menu_rs_01[$j]["PAGE_INFO03"]);
							$PAGE_INFO04	= trim($arr_menu_rs_01[$j]["PAGE_INFO04"]);
							$URL_TYPE			= trim($arr_menu_rs_01[$j]["URL_TYPE"]);

							$PAGE_NAME = str_replace("<br />","",$PAGE_NAME);
							$PAGE_NAME = str_replace("<br/>","",$PAGE_NAME);
							$PAGE_NAME = str_replace("<br>","",$PAGE_NAME);

							if ($PAGE_URL == "") {
								$PAGE_URL = "/pages/?p=".$PAGE_NO."&title=".$PAGE_NAME;
							} else {
								if (strpos($PAGE_URL, "script:") == true) {
									$PAGE_URL = $PAGE_URL;
								} else {
									$PAGE_URL = $PAGE_URL;
								}
							}

							if ($this_depth01 == $PAGE_CD) {
								$depth_01_page_name = $PAGE_NAME;
								$depth_01_page_cd		= $PAGE_CD;
							}

							if ($this_depth01 == $PAGE_CD) $depth_01_page_name = $PAGE_NAME;
				?>
					
					
				<li class="dep1-item <?if ($this_depth01 == $PAGE_CD)  echo "is-active" ?>">
					<a href="<?=$PAGE_URL?>" class="nav__1st" <? if ($URL_TYPE == "Y") { ?>target="_blank" title="새창열기"<? } ?>><?=$PAGE_NAME?></a>
					<div class="nav__depth">
						<div class="nav__category"><?=$PAGE_NAME?></div>
						<?
									if (sizeof($arr_menu_rs_02) > 0) {
						?>
						<ul>
						<?
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

											if ($PAGE_CD == left($SUB_PAGE_CD,2)) {

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
							<li><a href="<?=$SUB_PAGE_URL?>" class="nav__2nd" <? if ($SUB_URL_TYPE == "Y") { ?>target="_blank" title="새창열기"<? } ?>><?=$SUB_PAGE_NAME?></a></li>
						<?
											}
										}
						?>
						</ul>
						<?
									}
						?>

					</div>
				</li>

				<?
						}
					}
				?>

			</ul>
			<div class="header-nav-bg" aria-hidden="false"></div>
		</nav>
	</div>
</div>



	<div class="header__right">
		<?
			$intro_notice_cnt = getMainlistQuickCnt($conn, "TOP");
			if (($intro_notice_cnt > 0) && ($_PAGE_NO <> "1")) {
		?>
		<button type="button" class="btn-ico btn-ico__notice" title="알림">
			<span class="cnt"><?=$intro_notice_cnt?></span>
		</button>
		<?
			}
		?>
		<button type="button" class="btn-ico btn-ico__allmenu" title="전체 메뉴" onclick="front.gnb.open()">
			<span></span>
			<span></span>
			<span></span>
		</button>
	</div>
</header>




<nav class="gnb__wrap">
	<h1 class="blind">GNB 메뉴</h1>
	<ul class="gnb__list--main">

	<? 
		if (sizeof($arr_menu_rs_01) > 0) {

			for ($j = 0 ; $j < sizeof($arr_menu_rs_01); $j++) {

				$PAGE_NO			= trim($arr_menu_rs_01[$j]["PAGE_NO"]);
				$PAGE_CD			= trim($arr_menu_rs_01[$j]["PAGE_CD"]);
				$PAGE_NAME		= trim($arr_menu_rs_01[$j]["PAGE_NAME"]);
				$PAGE_URL			= trim($arr_menu_rs_01[$j]["PAGE_URL"]);
				$PAGE_INFO01	= trim($arr_menu_rs_01[$j]["PAGE_INFO01"]);
				$PAGE_INFO02	= trim($arr_menu_rs_01[$j]["PAGE_INFO02"]);
				$PAGE_INFO03	= trim($arr_menu_rs_01[$j]["PAGE_INFO03"]);
				$PAGE_INFO04	= trim($arr_menu_rs_01[$j]["PAGE_INFO04"]);
				$URL_TYPE			= trim($arr_menu_rs_01[$j]["URL_TYPE"]);

				$PAGE_NAME = str_replace("<br />","",$PAGE_NAME);
				$PAGE_NAME = str_replace("<br/>","",$PAGE_NAME);
				$PAGE_NAME = str_replace("<br>","",$PAGE_NAME);

				if ($PAGE_URL == "") {
					$PAGE_URL = "/pages/?p=".$PAGE_NO."&title=".$PAGE_NAME;
				} else {
					if (strpos($PAGE_URL, "script:") == true) {
						$PAGE_URL = $PAGE_URL;
					} else {
						$PAGE_URL = $PAGE_URL;
					}
				}

				if ($this_depth01 == $PAGE_CD) {
					$depth_01_page_name = $PAGE_NAME;
					$depth_01_page_cd		= $PAGE_CD;
				}

				if ($this_depth01 == $PAGE_CD) $depth_01_page_name = $PAGE_NAME;
	?>

		<li>
			<span><?=$PAGE_NAME?></span>
	<?
				if (sizeof($arr_menu_rs_02) > 0) {
	?>
			<ul class="gnb__list--sub">
	<?
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

						if ($PAGE_CD == left($SUB_PAGE_CD,2)) {

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
				<li>
					<a href="<?=$SUB_PAGE_URL?>" <? if ($SUB_URL_TYPE == "Y") { ?>target="_blank" title="새창열기"<? } ?>><?=$SUB_PAGE_NAME?></a>
				</li>
	<?
						}
					}
	?>
			</ul>
	<?
				}
	?>
		</li>
			<?
					}
				}
			?>

	</ul>
	<button type="button" title="메뉴 닫기" class="btn_ico btn-ico__close" onclick="front.gnb.close()">
		<span class="blind">닫기</span>
	</button>
</nav>
<!-- //GNB -->
