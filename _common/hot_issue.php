		</div>
	</div>
	<?
		$arr_rs_gate = mainlistGate($conn);

		$REG_NOW					= date("Y-m-d H:i"); // 게시기간 확인용

		$class_on_alarm = "";

		if (sizeof($arr_rs_gate) > 0) {
			for ($j = 0 ; $j < sizeof($arr_rs_gate); $j++) {

				$DATE_USE_TF			= trim($arr_rs_gate[$j]["DATE_USE_TF"]);
				$S_DATE						= trim($arr_rs_gate[$j]["S_DATE"]);
				$S_HOUR						= trim($arr_rs_gate[$j]["S_HOUR"]);
				$S_MIN						= trim($arr_rs_gate[$j]["S_MIN"]);
				$E_DATE						= trim($arr_rs_gate[$j]["E_DATE"]);
				$E_HOUR						= trim($arr_rs_gate[$j]["E_HOUR"]);
				$E_MIN						= trim($arr_rs_gate[$j]["E_MIN"]);
						
				$REG_START				= $S_DATE." ".$S_HOUR.":".$S_MIN;
				$REG_END					= $E_DATE." ".$E_HOUR.":".$E_MIN;

				if ($DATE_USE_TF == "Y") {
					if (($REG_NOW < $REG_START) || ($REG_NOW > $REG_END)) {
						$REG_TERM = "게시기간종료";
					}
				}

				if ($REG_TERM == "") {
					$class_on_alarm = "on";
				}
			}
		}
	?>
	<button type="button" class="btn-notice <?=$class_on_alarm?>" title="알림 열기" <? if ($class_on_alarm == "on") { ?> onclick="front.modal.view('pop-notice', this)" <? } ?>></button> <!-- 알림있을경우 on클래스 추가 -->
	<button type="button" class="btn-allmenu" title="전체메뉴 열기" onclick="front.navi.allMenuToggle()"><hr /><hr /><hr /></button>

</header>