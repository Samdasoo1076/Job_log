<?
    $current_url = $_SERVER['REQUEST_URI'];

    $member_url = [
        '/member/mypage.do',
        '/member/mypage_check.do',
        '/member/mypage_modify.do'
    ];
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
} else {
    // 기본 실행 코드 또는 에러 처리
    echo "<p>올바른 페이지 유형이 지정되지 않았습니다.</p>";
}
?>
					