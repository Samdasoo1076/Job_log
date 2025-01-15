<div class="content-header">
	<div class="snb-wrap">
		<nav class="nav dep3-nav">
			<ul class="dep3-list">
			<li class="dep3-item">
					<a href="/communication/?b=B_1_3" 
						class="dep3-link <? if ($m_type == '') echo 'is-current'; ?>" 
						title="<? if ($m_type == 'ALL') echo 'aria-current="page"'; else echo '전체'; ?>">
						전체
					</a>
				</li>

				<li class="dep3-item">
					<a href="/communication/?b=B_1_3&m_type=EMP" 
						class="dep3-link <? if ($m_type == 'EMP') echo 'is-current'; ?>" 
						title="<? if ($m_type == 'EMP') echo 'aria-current="page"'; else echo '임직원현황'; ?>">
						임직원현황
					</a>
				</li>

				<li class="dep3-item">
					<a href="/communication/?b=B_1_3&m_type=INS" 
						class="dep3-link <? if ($m_type == 'INS') echo 'is-current'; ?>" 
						title="<? if ($m_type == 'INS') echo 'aria-current="page"'; else echo '기관운영현황'; ?>">
						기관운영현황
					</a>
				</li>
				<li class="dep3-item">
					<a href="/communication/?b=B_1_3&m_type=BU" 
						class="dep3-link <? if ($m_type == 'BU') echo 'is-current'; ?>" 
						title="<? if ($m_type == 'BU') echo 'aria-current="page"'; else echo '예산 및 결산현황'; ?>">
						예산 및 결산현황
					</a>
				</li>
				<li class="dep3-item">
					<a href="/communication/?b=B_1_3&m_type=CO" 
						class="dep3-link <? if ($m_type == 'CO') echo 'is-current'; ?>" 
						title="<? if ($m_type == 'CO') echo 'aria-current="page"'; else echo '이사회 회의록'; ?>">
						이사회 회의록
					</a>
				</li>
				<li class="dep3-item">
					<a href="/communication/?b=B_1_3&m_type=ACH" 
						class="dep3-link <? if ($m_type == 'ACH') echo 'is-current'; ?>" 
						title="<? if ($m_type == 'ACH') echo 'aria-current="page"'; else echo '경영성과'; ?>">
						경영성과
					</a>
				</li>
			</ul>
		</nav>
	</div>
</div>
<!-- 
	EMP - 임직원현황
	INS - 기관운영현황
	BU  - 예산 및 결산현황
	CO  - 이사회 회의록
	ACH - 경영성과
-->