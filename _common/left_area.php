<?
		$arr_rs_menu = getListAdminGroupMenu($conn, $_SESSION['s_adm_group_no']);
?>
			<h1><a href="/manager/main.php"><img src="/manager/images/img-logo-wh.svg" alt="<?=$g_title?>" /></a></h1>
			
<?
		if ($arr_page_nm[2] == "main.php") {
			$p_parent_menu_name = "홈페이지 관리자";
		}
?>

			<div class="tit_h2"><h2><?=$p_parent_menu_name?> </h2></div>

			<div class="side_menu">
				<ul>
				<?
					if (sizeof($arr_rs_menu) > 0) {

						for ($m = 0 ; $m < sizeof($arr_rs_menu); $m++) {
			
							$M_MENU_CD		= trim($arr_rs_menu[$m]["MENU_CD"]);
							$M_MENU_NAME	= trim($arr_rs_menu[$m]["MENU_NAME"]);
							$M_MENU_URL		= trim($arr_rs_menu[$m]["MENU_URL"]);

							if (strlen($M_MENU_CD) == "2") {

								if ($m <> 0) {
				?>
						</ul>
					</li>
				<?
								}
				
								if ($M_MENU_CD == substr($sPageMenu_CD,0,2)) {
									$str_display_ = "class='sele'";
									//$str_display_ = "";
								} else {
									$str_display_ = "";
								}

				?>
					<li <?=$str_display_?> >
						<a href="<?=$M_MENU_URL?>" class="one_depth_menu"><?=$M_MENU_NAME?></a>
						<ul>
				<?
							}

							if (strlen($M_MENU_CD) == "4") {
				
								if (strpos($M_MENU_URL, "?") > 0) {
									$str_menu_url = $M_MENU_URL."&menu_cd=".$M_MENU_CD;
								} else {
									$str_menu_url = $M_MENU_URL."?menu_cd=".$M_MENU_CD;
								}

								if ($M_MENU_CD == substr($sPageMenu_CD,0,4)) {
									$str_display_ = "class='sele'";
									//$str_display_ = "";
								} else {
									$str_display_ = "";
								}

				?>
							<li <?=$str_display_?>><a href="<?=$str_menu_url?>"><?=$M_MENU_NAME?></a></li>
				<?
							}
						}
					}
				?>
						</ul>
					</li>
				</ul>
			</div>
			<iframe src="/_common/keep_session.php" name="keep_session" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border=0></iframe>
