<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<p class="tit" id="carendarModalLabel">시설 예약현황</p>
			<button type="button" class="calendar-open" data-bs-dismiss="modal" aria-label="Close">
				<span class="blind">닫기</span>
			</button>
		</div>
		<div class="modal-body">
			<div class="reservation-calendar" id="calendar"></div>
			<div class="reservation-choice-wrap">
				<div class="reservation-choice">
				</div>
				<div class="btn-wrap">
					<button type="button" class="btn white-bor"
						onclick="location.href='/facility/reservation_status.php'"><span class="txt">시설
							예약현황</span></button>
					<button type="button" class="btn red"
						onclick="location.href='/facility/reservation_form.php'"><span class="txt">시설
							예약하기</span></button>
				</div>
			</div>
		</div>
	</div>
</div>