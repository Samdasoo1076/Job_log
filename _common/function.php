<?php
$userAgent = $_SERVER['HTTP_USER_AGENT'];

function getBrowserInfo($userAgent) {
    $browserInfo = array(
        'name'      => 'Unknown',
        'version'   => 'Unknown',
        'platform'  => 'Unknown'
    );

    // Detect browser using regular expressions
    if (preg_match('/MSIE|Trident/i', $userAgent)) {
        $browserInfo['name'] = 'Internet Explorer';
        preg_match('/(MSIE|rv:)([\d.]+)/', $userAgent, $matches);
    } elseif (preg_match('/Edge/i', $userAgent)) {
        $browserInfo['name'] = 'Microsoft Edge';
        preg_match('/Edge\/([\d.]+)/', $userAgent, $matches);
    } elseif (preg_match('/Firefox/i', $userAgent)) {
        $browserInfo['name'] = 'Mozilla Firefox';
        preg_match('/Firefox\/([\d.]+)/', $userAgent, $matches);
    } elseif (preg_match('/Chrome/i', $userAgent)) {
        $browserInfo['name'] = 'Google Chrome';
        preg_match('/Chrome\/([\d.]+)/', $userAgent, $matches);
    } elseif (preg_match('/Safari/i', $userAgent)) {
        $browserInfo['name'] = 'Safari';
        preg_match('/Version\/([\d.]+)/', $userAgent, $matches);
    }

    if (isset($matches[1])) {
        $browserInfo['version'] = $matches[1];
    }

    // Detect platform
    if (preg_match('/Windows/i', $userAgent)) {
        $browserInfo['platform'] = 'Windows';
    } elseif (preg_match('/Macintosh|Mac OS X/i', $userAgent)) {
        $browserInfo['platform'] = 'Mac OS X';
    } elseif (preg_match('/Linux/i', $userAgent)) {
        $browserInfo['platform'] = 'Linux';
    } elseif (preg_match('/Android/i', $userAgent)) {
        $browserInfo['platform'] = 'Android';
    } elseif (preg_match('/iOS/i', $userAgent)) {
        $browserInfo['platform'] = 'iOS';
    }

    return $browserInfo;
}

?>