<div class="board-list">
    <table class="tbl">
        <caption>
            <strong>공지사항 목록</strong>
            <p>No., 제목, 작성자, 등록일, 조회수 순서로 구성됨</p>
        </caption>
        <colgroup>
            <col style="width:120rem">
            <col style="width:auto">
            <col style="width:200rem">
            <col style="width:120rem">
            <col style="width:120rem">
        </colgroup>
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">제목</th>
                <th scope="col">작성자</th>
                <th scope="col">등록일</th>
                <th scope="col">조회수</th>
            </tr>
        </thead>
        <tbody>
            <?
            if (isset($arr_rs) && sizeof($arr_rs) > 0) {
                $count = 1; // 번호를 매기기 위한 초기값
                foreach ($arr_rs as $row) {
                    // 데이터 가져오기
                    $TITLE = htmlspecialchars(strip_tags(trim($row["TITLE"])), ENT_QUOTES, 'UTF-8');
                    $rn = trim($row["rn"]);
					$highlightedTitle = highlightKeyword($TITLE, $s); // 검색어 강조 적용
                    $WRITER_NM = htmlspecialchars(trim($row["WRITER_NM"]), ENT_QUOTES, 'UTF-8');
                    $REG_DATE = date("Y.m.d", strtotime(trim($row["REG_DATE"])));
                    $HIT_CNT = number_format(trim($row["HIT_CNT"]));
                    $F_CNT = trim($row["F_CNT"]);
					$B_NO = trim($row["B_NO"]);
                    ?>
                    <tr>
                        <td class="tbl-no">
                            <p><?= $rn; ?></p>
                        </td>
                        <td class="tbl-tit">
                            <p>
                                <a href="view.do?b=<?= $b ?>&bn=<?= $B_NO ?>&m_type=<?= $m_type ?>&nPage=<?= $nPage ?>"
                                    class="link">
                                    <?= $highlightedTitle; ?>
                                </a>
                                <? if ($F_CNT > 0) { ?>
                                    <a href="view.do?b=<?= $b ?>&bn=<?= $B_NO ?>&m_type=<?= $m_type ?>&nPage=<?= $nPage ?>&f=<?= $f ?>&s=<?= $s ?>"
                                        class="file"><span class="blind">첨부파일</span></a>
                                <? } ?>
                            </p>
                        </td>
                        <td class="tbl-writer">
                            <p><?= $WRITER_NM; ?></p>
                        </td>
                        <td class="tbl-date">
                            <p><?= $REG_DATE; ?></p>
                        </td>
                        <td class="tbl-views">
                            <p><?= $HIT_CNT; ?></p>
                        </td>
                    </tr>
                    <?
                }
            } else {
                ?>
                <tr>
                    <td colspan="5" class="no-list">
                        <p>게시글이 없습니다.</p>
                    </td>
                </tr>
            <? } ?>
        </tbody>
    </table>
</div>