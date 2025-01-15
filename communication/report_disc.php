<div class="content-header">
	<div class="snb-wrap">
		<nav class="nav dep3-nav">
			<ul class="dep3-list">
			<li class="dep3-item">
					<a href="/communication/?b=B_1_12" 
						class="dep3-link <? if ($m_type == '') echo 'is-current'; ?>" 
						title="<? if ($m_type == 'ALL') echo 'aria-current="page"'; else echo '전체'; ?>">
						전체
					</a>
				</li>

				<li class="dep3-item">
					<a href="/communication/?b=B_1_12&m_type=WFI_REPORT" 
						class="dep3-link <? if ($m_type == 'WFI_REPORT') echo 'is-current'; ?>" 
						title="<? if ($m_type == 'WFI_REPORT') echo 'aria-current="page"'; else echo '원주미래산업리포트'; ?>">
						원주미래산업리포트
					</a>
				</li>

				<li class="dep3-item">
					<a href="/communication/?b=B_1_12&m_type=FI_BRIEF" 
						class="dep3-link <? if ($m_type == 'FI_BRIEF') echo 'is-current'; ?>" 
						title="<? if ($m_type == 'FI_BRIEF') echo 'aria-current="page"'; else echo '미래산업 이슈브리프'; ?>">
						미래산업 이슈브리프
					</a>
				</li>
				
			</ul>
		</nav>
	</div>
</div>