<?php
// /common/logger.php

require_once __DIR__ . '/../db.php';
// require_once __DIR__ . '/com/etc.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * 사용자의 페이지 조회를 로그에 남깁니다.
 *
 * @param int|null $postId 조회한 포스트 ID
 */
function log_user_activity(?int $postId = null): void {
    global $pdo;
    $now = new DateTime();
    $ip  = $_SERVER['REMOTE_ADDR'] ?? '';

    // NordVPN API 호출: GET 요청 후 JSON 파싱
    $country     = null;
    $countryCode = null;
    $region      = null;
    $zipCode     = null;
    $city        = null;
    $stateCode   = null;
    $latitude    = null;
    $longitude   = null;
    $isp         = null;
    $ispAsn      = null;
    $gdpr        = null;

    if ($ip) {
        $json = @file_get_contents("https://web-api.nordvpn.com/v1/ips/lookup/{$ip}");
        if ($json !== false) {
            $data = json_decode($json, true);
            $country     = $data['country']      ?? null;
            $countryCode = $data['country_code'] ?? null;
            $region      = $data['region']       ?? null;
            $zipCode     = $data['zip_code']     ?? null;
            $city        = $data['city']         ?? null;
            $stateCode   = $data['state_code']   ?? null;
            $latitude    = $data['latitude']     ?? null;
            $longitude   = $data['longitude']    ?? null;
            $isp         = $data['isp']          ?? null;
            $ispAsn      = $data['isp_asn']      ?? null;
            $gdpr        = !empty($data['gdpr']) ? 1 : 0;
        }
    }

    // 응답 시간(ms)
    $responseTime = isset($_SERVER['REQUEST_TIME_FLOAT'])
        ? (int)((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000)
        : null;

    $params = [
        ':log_date'         => $now->format('Y-m-d'),
        ':log_hour'         => (int)$now->format('H'),
        ':log_minute'       => (int)$now->format('i'),
        ':log_week'         => (int)$now->format('W'),
        ':post_id'          => $postId,
        ':url'              => $_SERVER['REQUEST_URI'] ?? '',
        ':device_type'      => preg_match('/Mobile|Android|iPhone|iPad|iPod/',
                                 $_SERVER['HTTP_USER_AGENT'] ?? '') ? 'MO' : 'PC',
        ':referer'          => $_SERVER['HTTP_REFERER'] ?? null,
        ':session_id'       => session_id(),
        ':ip_address'       => $ip,
        ':reg_datetime'     => $now->format('Y-m-d H:i:s'),
        ':user_agent'       => $_SERVER['HTTP_USER_AGENT'] ?? '',
        ':user_id'          => $_SESSION['user_id'] ?? null,
        ':response_time_ms' => $responseTime,
        ':http_status'      => http_response_code(),
        ':country'          => $country,
        ':country_code'     => $countryCode,
        ':region'           => $region,
        ':zip_code'         => $zipCode,
        ':city'             => $city,
        ':state_code'       => $stateCode,
        ':latitude'         => $latitude,
        ':longitude'        => $longitude,
        ':isp'              => $isp,
        ':isp_asn'          => $ispAsn,
        ':gdpr'             => $gdpr,
    ];

    $sql = <<<'SQL'
INSERT INTO user_activity_log
    (log_date, log_hour, log_minute, log_week,
     post_id, url, device_type, referer,
     session_id, ip_address, reg_datetime,
     user_agent, user_id, response_time_ms,
     http_status, country, country_code,
     region, zip_code, city, state_code,
     latitude, longitude, isp, isp_asn, gdpr)
VALUES
    (:log_date, :log_hour, :log_minute, :log_week,
     :post_id, :url, :device_type, :referer,
     :session_id, :ip_address, :reg_datetime,
     :user_agent, :user_id, :response_time_ms,
     :http_status, :country, :country_code,
     :region, :zip_code, :city, :state_code,
     :latitude, :longitude, :isp, :isp_asn, :gdpr)
SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
}