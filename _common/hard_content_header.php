<?
    $current_url = $_SERVER['REQUEST_URI'];
    $project_title = '';

    $member_url = [
        '/member/mypage.do',
        '/member/mypage_check.do',
        '/member/mypage_modify.do'
    ];

    $industry_url = [
        '/task/global_industry.do',
        '/task/mirae_industry.do',
        '/task/jungso_industry.do',
        '/task/drone_industry.do',
    ];

    if ($current_url === '/task/global_industry.do') {
        $project_title = '글로벌 디지털 인재양성 포럼 및 경진대회 운영';
    } elseif ($current_url === '/task/mirae_industry.do') {
        $project_title = '미래 신산업 인재양성 허브구축 사업';
    } elseif ($current_url === '/task/jungso_industry.do') {
        $project_title = '중소기업 화재보험 지원';
    } elseif ($current_url === '/task/drone_industry.do') {
        $project_title = '드론 실증 도시 구축 사업(K-드론배송)';
    } else {
        $project_title = ''; // 기본 텍스트
    }

?>

<?
if ($page_header_type === 'mypage') {
    ?>
    <!-- Sub Visual -->
    <div class="visual-wrap visual-05">
        <div class="inner">
            <h1>마이페이지</h1>
            <div class="location">
                <span class="lc-home"><span class="blind">홈</span></span>
                <span class="lc-split"><span class="blind">&gt;</span></span>
                <span class="lc-cate"><span>마이페이지</span></span>
                <span class="lc-split"><span class="blind">&gt;</span></span> 
                <span class="lc-current" aria-current="page">나의 회원정보</span>
            </div>
        </div>
    </div>
    <!-- // Sub Visual -->
    <!-- Sub Nav -->
    <div class="snb-wrap">
        <nav class="nav dep2-nav">
            <ul class="dep2-list">
                <li class="dep2-item">
                    <a href="/member/mypage.do" 
                    class="dep2-link <?= in_array($current_url, $member_url) ? 'is-current' : ''; ?>">나의 회원정보</a>
                </li>
                <li class="dep2-item">
                    <a href="/member/mypage_reservation.do" 
                    class="dep2-link <?= ($current_url === '/member/mypage_reservation.do') ? 'is-current' : ''; ?>">나의 예약현황</a>
                </li>
            </ul>
        </nav>
    </div>
    <!-- // Sub Nav -->
    <?
} elseif ($page_header_type === 'search_list') {
    ?>
    <!-- Sub Visual -->
    <div class="visual-wrap visual-07">
        <div class="inner">
            <h1>통합검색</h1>
            <div class="location">
                <span class="lc-home"><span class="blind">홈</span></span>
                <span class="lc-split"><span class="blind">&gt;</span></span>
                <span class="lc-cate"><span>통합검색</span></span>
            </div>
        </div>
    </div>
    <!-- // Sub Visual -->
    <?
} elseif ($page_header_type === 'search_list') {
    ?>
    <!-- Sub Visual -->
    <div class="visual-wrap visual-07">
        <div class="inner">
            <h1>통합검색</h1>
            <div class="location">
                <span class="lc-home"><span class="blind">홈</span></span>
                <span class="lc-split"><span class="blind">&gt;</span></span>
                <span class="lc-cate"><span>통합검색</span></span>
            </div>
        </div>
    </div>
    <!-- // Sub Visual -->
    <?
} elseif ($page_header_type === 'industry') {
    ?><!-- Sub Visual -->
    <!-- <div class="visual-wrap visual-03">
        <div class="inner">
            <h1>주요업무</h1>
            <div class="location">
                <span class="lc-home"><span class="blind">홈</span></span>
                <span class="lc-split"><span class="blind">&gt;</span></span>
                <span class="lc-cate"><span>주요업무</span></span>
                <span class="lc-split"><span class="blind">&gt;</span></span> 
                <span class="lc-current" aria-current="page">주요사업</span>
                <span class="lc-split"><span class="blind">&gt;</span></span> 

                <span class="lc-current" aria-current="page"><?= $project_title ?></span>
            </div>
        </div>
    </div> -->
    <!-- // Sub Visual -->
    <!-- Sub Nav -->
    <div class="snb-wrap">
        <nav class="nav dep3-nav">
            <ul class="dep3-list">
                <li class="dep3-item">
                    <a href="/task/global_industry.do" 
                    class="dep3-link <?= ($current_url === '/task/global_industry.do') ? 'is-current' : ''; ?>">글로벌 디지털 인재양성 <br> 포럼∙경진대회 운영</a>
                </li>
                <li class="dep3-item">
                    <a href="/task/mirae_industry.do" 
                    class="dep3-link <?= ($current_url === '/task/mirae_industry.do') ? 'is-current' : ''; ?>">미래 신산업 인재양성<br>허브구축 사업</a>
                </li>
                <li class="dep3-item">
                    <a href="/task/jungso_industry.do" 
                    class="dep3-link <?= ($current_url === '/task/jungso_industry.do') ? 'is-current' : ''; ?>">중소기업 화재보험 지원</a>
                </li>
                <li class="dep3-item">
                    <a href="/task/drone_industry.do" 
                    class="dep3-link <?= ($current_url === '/task/drone_industry.do') ? 'is-current' : ''; ?>">드론 실증 도시구축 사업<br>(K-드론배송)</a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- // Sub Nav -->
<? } else {
    // 기본 실행 코드 또는 에러 처리
    echo "<p>올바른 페이지 유형이 지정되지 않았습니다.</p>";
}
?>
					