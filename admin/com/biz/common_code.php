<?php
function commonCodeOptions(PDO $pdo, string $group): array
{
    $stmt = $pdo->prepare("SELECT d.* FROM TBL_CODE_DETAIL d JOIN TBL_CODE_PARENT p ON p.PCODE=d.PCODE WHERE d.PCODE=? AND d.USE_TF='Y' AND d.DEL_TF='N' AND p.USE_TF='Y' AND p.DEL_TF='N' ORDER BY d.DCODE_SEQ_NO, d.DCODE_NO");
    $stmt->execute([$group]);
    return $stmt->fetchAll();
}
