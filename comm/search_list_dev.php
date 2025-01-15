<? session_start(); ?>
<?
$_PAGE_NO = "105";
require $_SERVER['DOCUMENT_ROOT'] . "/_common/common_inc.php";
$page_header_type = 'search_list';
$searchKeyword = trim($_GET['searchKeyword'] ?? '');
$searchCategory = trim($_GET['category'] ?? 'all');

$nPage = isset($_POST["nPage"]) && $_POST["nPage"] !== '' ? $_POST["nPage"] : (isset($_GET["nPage"]) ? $_GET["nPage"] : '');
$nPageSize = isset($_POST["nPageSize"]) && $_POST["nPageSize"] !== '' ? $_POST["nPageSize"] : (isset($_GET["nPageSize"]) ? $_GET["nPageSize"] : '');


if ($nPage <> "" && $nPageSize <> 0) {
    $nPage = (int) ($nPage);
} else {
    $nPage = 1;
}

if ($nPage <> "" && $nPageSize <> 0) {
    $nPageSize = (int) ($nPageSize);
} else {
    $nPageSize = 20;
}

if ($nPageSize == 0) {
    $nPageSize = 20;
}

$nPageBlock = 10;

?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script language="javascript">
    $(document).ready(function () {
        let currentTab = ''; // 현재 선택된 탭의 B_CODE 저장 (기본값: 전체)
        let totalCount = 0; // 전체 카운트를 저장할 변수

        // URL에서 검색어 추출
        const urlParams = new URLSearchParams(window.location.search);
        const searchKeyword = urlParams.get('searchKeyword') || '';
        const tabId = urlParams.get('tab') || 'tab-all';
        const page = parseInt(urlParams.get('page')) || 1;

        // 탭과 페이지 설정
        if (tabId && $(`#${tabId}`).length) {
            $('.dep2-link').removeClass('is-current');
            $(`#${tabId}`).addClass('is-current');
            currentTab = (tabId === 'tab-all') ? '' : tabId.replace('tab-', '');
        }

        // 초기 검색 실행
        fetchResults(page);

        $('#input_search').val(searchKeyword);


        // 검색 필드에서 Enter 키 입력 처리
        $('#input_search').keypress(function (e) {
            if (e.which === 13) { // Enter 키
                $('#btn_search').trigger('click'); // 버튼 클릭 트리거
            }
        });

        $('#btn_search').click(function () {
            const newSearchKeyword = $('#input_search').val().trim();
            if (!newSearchKeyword) {
                alert('검색어를 입력해주세요.');
                return;
            }

            // URL에 새 검색어 반영 후 페이지 리로드
            const url = new URL(window.location.href);
            url.searchParams.set('searchKeyword', newSearchKeyword);
            url.searchParams.set('page', 1); // 첫 페이지로 초기화
            location.href = url.toString();
        });

        // 탭 클릭 이벤트 처리
        $('.dep2-link').click(function (e) {
            const tabId = $(this).attr('id'); // 클릭된 탭의 id 가져오기

            // 특정 탭 ID에만 기본 동작 방지 적용
            if (tabId === 'tab-all' || tabId === 'tab-B_1_1' || tabId === 'tab-B_1_2' || tabId === 'tab-B_1_3') {
                e.preventDefault(); // 기본 링크 동작 방지
                $('.dep2-link').removeClass('is-current'); // 모든 탭의 활성화 클래스 제거
                $(this).addClass('is-current'); // 클릭한 탭에 활성화 클래스 추가

                currentTab = (tabId === 'tab-all') ? '' : tabId.replace('tab-', ''); // 전체 탭이면 필터 조건 초기화

                const url = new URL(window.location.href);
                url.searchParams.set('tab', tabId);
                url.searchParams.set('page', 1); // 탭 변경 시 페이지는 1로 초기화
                history.pushState(null, '', url.toString());

                fetchResults(1); // 첫 페이지에서 데이터 검색
            }
        });


        // 검색 결과를 가져오는 함수
        function fetchResults(page) {
            const nPage = page || 1; // 현재 페이지 번호 (기본값: 1)
            const nRowCount = 2; // 페이지당 표시할 데이터 수

            $.ajax({
                url: '/_common/board/ajax_board_search.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    searchKeyword: $('#input_search').val(),
                    page: nPage,
                    nRowCount: nRowCount,
                    b_code: currentTab // 현재 선택된 B_CODE 전달
                },
                success: function (response) {
                    $('#search-list').empty();
                    if (response.status === 'success' && response.data.length > 0) {
                        // 데이터 렌더링
                        $.each(response.data, function (index, item) {
                            const highlightedTitle = highlightKeyword(item.TITLE, searchKeyword);
                            const highlightedContent = highlightKeyword(item.CONTENTS, searchKeyword);
                            $('#search-list').append(`
                            <li>
                                <a href="/communication/view.php?b=${item.B_CODE}&bn=${item.B_NO}">
                                    <p class="title">${highlightedTitle}</p>
                                    <p class="cont">${highlightedContent}</p>
                                    <div class="category-wrap">
                                        <div class="category">
                                            <span class="first">소통마당</span>
                                            <span class="second">${getCategoryName(item.B_CODE)}</span>
                                        </div>
                                        <span class="date">${item.REG_DATE}</span>
                                    </div>
                                </a>
                            </li>
                        `);
                        });

                        // 페이지네이션 업데이트
                        updatePagination(response.totalPages, nPage);

                        // 카운트 업데이트
                        if (currentTab === '') {
                            // 전체 탭일 경우만 totalCount를 업데이트
                            totalCount = response.totalCount;
                        }

                        // 카테고리별 카운트 업데이트
                        updateCategoryCounts(response.categoryCounts);
                    } else {
                        $('#board-list').html('<div class="no-list"><p>검색 결과가 없습니다.</p></div>');
                        $('#board-page').empty();
                    }

                    $('#search-keyword').text($('#input_search').val());
                    $('#search-count').text(response.totalCount || 0);
                },
                error: function (xhr, status, error) {
                    console.error("검색 오류:", error);
                    $('#search-list').html('<li>검색 중 오류가 발생했습니다.</li>');
                    $('#board-page').empty();
                }
            });
        }

        $(document).on('click', '#board-page button', function () {
            const page = $(this).data('page'); // 버튼의 data-page 속성에서 페이지 번호를 가져옴
            if (page) {
                // URL 업데이트
                const url = new URL(window.location.href);
                url.searchParams.set('page', page);
                history.pushState(null, '', url.toString());

                fetchResults(page); // 해당 페이지 번호로 검색 함수 호출
            }
        });

        // 키워드를 강조하는 함수
        function highlightKeyword(text, keyword) {
            if (!keyword) return text; // 키워드가 없으면 원래 텍스트 반환
            const regex = new RegExp(`(${keyword})`, 'gi'); // 대소문자 구분 없이 검색
            return text.replace(regex, '<em>$1</em>'); // 검색된 키워드를 <em> 태그로 감싸기
        }


        // 페이지네이션을 업데이트하는 함수
        function updatePagination(totalPages, currentPage) {
            let paginationHtml = '';
            const pageGroupSize = 5;

            const startPage = Math.floor((currentPage - 1) / pageGroupSize) * pageGroupSize + 1;
            const endPage = Math.min(startPage + pageGroupSize - 1, totalPages);

            // 첫 페이지와 이전 페이지 버튼 추가
            if (currentPage > 1) {
                paginationHtml += '<button type="button" class="btn-page-first" data-page="1"></button>';
                paginationHtml += '<button type="button" class="btn-page-prev" data-page="' + (currentPage - 1) + '"></button>';
            } else if (currentPage == 1) {
                paginationHtml += '<button type="button" class="btn-page-first" disabled onclick="alert(\'첫 페이지입니다.\')"></button>';
                paginationHtml += '<button type="button" class="btn-page-prev" disabled onclick="alert(\'첫 페이지입니다.\')"></button>';
            }

            // 중간 페이지 버튼 추가
            for (let i = startPage; i <= endPage; i++) {
                paginationHtml += '<button type="button" class="btn-page-number ' + (i === currentPage ? 'is-current' : '') + '" data-page="' + i + '">' + i + '</button>';
            }

            // 다음 페이지와 마지막 페이지 버튼 추가
            if (currentPage < totalPages) {
                paginationHtml += '<button type="button" class="btn-page-next" data-page="' + (currentPage + 1) + '"></button>';
                paginationHtml += '<button type="button" class="btn-page-last" data-page="' + totalPages + '"></button>';
            } else {
                paginationHtml += '<button type="button" class="btn-page-next" disabled onclick="alert(\'마지막 페이지입니다.\')"></button>';
                paginationHtml += '<button type="button" class="btn-page-last" disabled onclick="alert(\'마지막 페이지입니다.\')"></button>';
            }

            $('#board-page').html(paginationHtml);
        }


        // 카테고리 이름 변환 함수
        function getCategoryName(bCode) {
            const categoryMap = {
                'B_1_1': '공지사항',
                'B_1_2': '보도자료',
                'B_1_3': '경영공시'
            };
            return categoryMap[bCode] || '기타'; // 매핑이 없을 경우 '기타' 반환
        }

        // 카테고리별 카운트를 업데이트하는 함수
        function updateCategoryCounts(categoryCounts) {
            $('#tab-all-count').text(totalCount); // 전체 카운트는 전역 변수 유지
            $('#tab-B_1_1-count').text(categoryCounts.notice); // 공지사항 카운트
            $('#tab-B_1_2-count').text(categoryCounts.press); // 보도자료 카운트
            $('#tab-B_1_3-count').text(categoryCounts.management); // 경영공시 카운트
        }
    });


</script>

<!-- Container -->
<main role="main" class="container">
    <!-- content -->
    <div id="content" class="content annou-list-page">
        <!-- content-header -->
        <div class="content-header">
            <?
            require $_SERVER['DOCUMENT_ROOT'] . "/_common/hard_content_header.php";
            ?>
            <!-- // Sub Visual -->

        </div>
        <!-- // content-header -->
        <!-- content-body -->
        <div class="content-body search-wrap">
            <!-- 페이지 유형 예시 (페이지 유형에 따라 타이틀 영역의 예외처리 대응) -->
            <div class="board-list-page">
                <!-- 타이틀 영역 -->
                <div class="title-wrap">
                    <h2 class="title">통합검색</h2>
                    <div class="search_wrap">
                        <div class="input">
                            <input id="input_search" type="search" name="input_search" placeholder="검색어를 입력하세요">
                            <!-- <span class="delete"><span class="blind">delete</span></span>  -->
                            <button id="btn_search" name="btn_search" type="button" class="btn_search"><span
                                    class="blind">search</span></button>

                        </div>
                    </div>
                    <p class="explain" id="search-info"><em class="search" id="search-keyword"></em>에 대한 검색 결과 <em
                            class="num" id="search-count">0</em>건의
                        검색결과를 찾았습니다.</p>
                </div>
                <!-- // 타이틀 영역 -->


                <!-- 게시판목록 영역 -->

                <div class="board-list-wrap">
                    <!-- Sub Nav -->
                    <div class="snb-wrap">
                        <nav class="nav dep2-nav">
                            <ul class="dep2-list" id="search-tabs">
                                <li class="dep2-item">
                                    <a href="#" class="dep2-link is-current" aria-current="page" id="tab-all">전체
                                        <span class="total" id="tab-all-count">0</span></a>
                                </li>
                                <li class="dep2-item">
                                    <a href="#" class="dep2-link" id="tab-B_1_1">공지사항
                                        <span class="total" id="tab-B_1_1-count">0</span></a>
                                </li>
                                <li class="dep2-item">
                                    <a href="#" class="dep2-link" id="tab-B_1_2">보도자료
                                        <span class="total" id="tab-B_1_2-count">0</span></a>
                                </li>
                                <li class="dep2-item">
                                    <a href="#" class="dep2-link" id="tab-B_1_3">
                                        경영공시 <span class="total" id="tab-B_1_3-count">0</span></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <!-- // Sub Nav -->
                    <div class="board-list" id="board-list">
                        <ul class="search-list" id="search-list">
                        </ul>
                    </div>
                    <div class="board-page" id="board-page">
                        <? if (sizeof($arr_rv_list) > 0) { ?>
                            <?
                            # ==========================================================================
                            #  페이징 처리
                            # ==========================================================================
                            $strParam = $strParam . "f=" . $f . "&s=" . $s;

                            ?>
                            <?= Front_Image_PageList($_SERVER["PHP_SELF"], $nPage, $nTotalPage, $nPageBlock, $strParam) ?>

                        <? } ?>
                    </div>
                </div>
                <!-- // 게시판목록 영역 -->
            </div>
        </div>
        <!-- // content-body -->
    </div>
    <!-- // content -->
</main>
<!-- // Container -->

<footer class="footer">
    <?
    require_once "../_common/front_footer.php";
    ?>
</footer>