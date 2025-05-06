				<div class="bread_crumb">
					<ul>
						<li><a href="/manager/main.php"><img src="/manager/images/img_home.gif" alt="메인" /></a></li>
						<li><a href="javascript:void(0)"><?=$p_parent_menu_name?></a></li>
						<? if ($p_menu_name ?? '') { ?>
						<li><span><?=$p_menu_name?></span></li>
						<? } ?>
					</ul>
				</div>