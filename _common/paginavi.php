				<nav class="respo-pc">
					<ul>
						<li>
							<a href="/" class="btn-home" title="홈 바로가기"><span class="sr-only">HOME</span></a>
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
						<li <? if ($this_depth01.$this_depth02 == $SUB_PAGE_CD) { ?> class="on" <? } ?> <? if ($this_depth01.$this_depth02 == $SUB_PAGE_CD) { ?> title="현재 선택됨" <? } ?>>
							<a href="<?=$SUB_PAGE_URL?>" <? if ($SUB_URL_TYPE == "Y") { ?>target="_blank" title="새창열기"<? } ?>><?=$SUB_PAGE_NAME?></a>
						</li>
						<? 
									}
								}
							} 
						?>
					</ul>
				</nav>



				<nav class="respo-mo">
					<ul>
						<li>
							<div class="selectBox">
								<select id="sub_lnb_01">
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
									<option value="<?=$URL_TYPE?><?=$PAGE_URL?>" <? if (trim($this_depth01) == trim($PAGE_CD)) { ?>selected<? } ?>><?=$PAGE_NAME?></option>
									<?
											}
										}
									?>
								</select>
							</div>
						</li>
						<li>
							<div class="selectBox">
								<select id="sub_lnb_02">
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
									<option value="<?=$SUB_URL_TYPE?><?=$SUB_PAGE_URL?>" <? if (trim($this_depth01.$this_depth02) == trim($SUB_PAGE_CD)) { ?> selected <? } ?>><?=$SUB_PAGE_NAME?></option>
								<?
											}
										}
									}
								?>
								</select>
							</div>
						</li>
					</ul>
				</nav>