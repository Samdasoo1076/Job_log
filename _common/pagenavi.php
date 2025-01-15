				<!-- S: SubVisual -->
				<!-- susi, jeongsi, foreigner, pyeonip, information, high, university -->
				<div class="sub-visual visual__img <?=$rs_page_info05?>">
					<h2 class="sub-visual__title"><?=$depth_01_page_name?></h2>
				</div>
				<!-- // E: SubVisual -->

				<!-- S: PageNav-->
				<nav class="respo respo--pc">
					<ul>
						<li>
							<a href="/" class="ico-btn ico-btn--home" title="홈 바로가기">
								<span class="blind">HOME</span>
							</a>
						</li>
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
						<li <? if ($this_depth01.$this_depth02 == $SUB_PAGE_CD) { ?> class='on' <? } ?>>
							<a href="<?=$SUB_PAGE_URL?>" <? if ($SUB_URL_TYPE == "Y") { ?>target="_blank" title="새창열기"<? } ?>><?=$SUB_PAGE_NAME?></a>
						</li>
						<? 
									}
								}
							} 
						?>
				  </ul>
				</nav>
				<nav class="respo respo--mo">
					<ul class="inner">
						<li class="select__wrap--1st">
							<button type="button" class="title" onclick="front.breadcrumbs.main()"><?=$depth_01_page_name?></button>
							<ul class="option__list">

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
								?>
								<li class="option__item">
									<a href="<?=$PAGE_URL?>" <? if ($this_depth01 == $PAGE_CD) { ?>class='on'<? } ?> <? if ($this_depth01 == $PAGE_CD) { ?>title="선택 됨"<? } ?>><?=$PAGE_NAME?></a>
								</li>
								<?
										}
									}
								?>
							</ul>
						</li>
						<li class="select__wrap--2nd">
							<button type="button" class="title" onclick="front.breadcrumbs.sub()"><?=$depth_02_page_name?></button>
							<ul class="option__list">
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
								<li class="option__item">
									<a href="<?=$SUB_PAGE_URL?>" <? if ($this_depth01.$this_depth02 == $SUB_PAGE_CD) { ?> class='on' <? } ?>><?=$SUB_PAGE_NAME?></a>
								</li>
							<? 
										}
									}
								} 
							?>
							</ul>
						</li>
					</ul>
				</nav>

				<!-- // E: PageNav -->
				<!-- S: PageHeader -->
				<div class="screen-modulate">
					<h2 class="sub-page__title"><?=$depth_02_page_name?></h2>
					<ul class="screen__btn-list">
						<li>
							<button type="button" class="btn-ico btn-ico--plus" title="폰트 크기 확대" onclick="browserZoomIn();">확대</button>
							<i class="btn-ico btn-ico--font">폰트</i>
							<button type="button" class="btn-ico btn-ico--minus" title="폰트 크기 축소" onclick="browserZoomOut();">축소</button>
						</li>
						<li>
							<button type="button" class="btn-ico btn-ico--print" title="인쇄하기" onclick="printPage('#print_content');">프린트</button>
						</li>
					</ul>
				</div>
				<!-- // E: PageHeader -->