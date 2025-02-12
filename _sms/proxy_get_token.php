<?php
session_start(); // 세션 시작

// CORS 설정 (모든 도메인 허용)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Content-Type: application/json; charset=utf-8");

// OPTIONS 요청 처리 (CORS Preflight 요청 대응)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 비즈뿌리오 API 설정
$url = "https://api.bizppurio.com/v1/token";
$username = "wfiwfi";  
$password = "wfiqwer1!";

// Basic Auth 생성
$auth_header = "Basic " . base64_encode("$username:$password");

// cURL 설정
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: $auth_header",
    "Content-Type: application/json; charset=utf-8"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

$result = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

// **디버깅 로그** (로그 파일에 남김)
error_log("📌 [proxy_get_token] 요청 URL: " . $url);
error_log("📌 [proxy_get_token] Authorization 헤더: " . $auth_header);
error_log("📌 [proxy_get_token] HTTP 상태 코드: " . $http_code);
error_log("📌 [proxy_get_token] API 응답: " . $result);

if ($http_code !== 200 || !$result) {
    error_log("❌ [proxy_get_token] 토큰 요청 실패 - cURL 오류: " . $curl_error);
    echo json_encode(["error" => "토큰 요청 실패", "http_code" => $http_code, "curl_error" => $curl_error]);
    exit();
}

// 응답을 JSON 형식으로 반환
$response = json_decode($result, true);
if (isset($response['accessToken'])) {
    echo json_encode([
        "status" => "success",
        "accessToken" => $response['accessToken']
    ]);
} else {
    echo json_encode(["error" => "토큰 발급 실패", "response" => $response]);
}
