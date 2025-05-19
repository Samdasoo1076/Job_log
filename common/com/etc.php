<?php

function getBrowserName(string $ua = null): string
{
    if ($ua === null) {
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    if (stripos($ua, 'Edg/') !== false) {
        return 'Edge';
    }
    if (stripos($ua, 'OPR/') !== false || stripos($ua, 'Opera') !== false) {
        return 'Opera';
    }
    if (stripos($ua, 'Chrome/') !== false) {
        return 'Chrome';
    }
    if (stripos($ua, 'Safari/') !== false && stripos($ua, 'Chrome/') === false) {
        return 'Safari';
    }
    if (stripos($ua, 'Firefox/') !== false) {
        return 'Firefox';
    }
    if (preg_match('#MSIE|Trident#i', $ua)) {
        return 'Internet Explorer';
    }
    return 'Unknown';
}
?>