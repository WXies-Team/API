<?php
header('Content-Type: application/json');

function get_client_ipv6() {
    $ip = null;

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        foreach ($ips as $candidate) {
            $candidate = trim($candidate);
            if (filter_var($candidate, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $ip = $candidate;
                break;
            }
        }
    }

    if (!$ip && !empty($_SERVER['HTTP_CLIENT_IP'])) {
        if (filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }

    if (!$ip && !empty($_SERVER['REMOTE_ADDR'])) {
        if (filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    }

    return $ip ?: '未检测到IPv6地址';
}

echo json_encode(['ipv6' => get_client_ipv6()]);
?>
