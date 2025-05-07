    <?php
    // PHP 내부 타임존 설정 (php.ini 대신 코드 상에서 잡아줄 수도 있습니다)
    date_default_timezone_set('Asia/Seoul');

    $host = '158.179.168.106';
    $db   = 'cms_blog';
    $user = 'admin';
    $pass = '2134';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);

    // MySQL 세션 타임존을 서울(UTC+9)로 설정
    $pdo->exec("SET time_zone = '+09:00'");

    
    ?>