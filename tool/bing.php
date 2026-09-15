<?php
declare(strict_types=1);

require_once __DIR__ . '/../common.php';

// 1. 解析日期参数（支持随机调用与指定天数）
$is_rand = get_bool_param('rand', false);
if ($is_rand) {
    $day = rand(-1, 7);
} else {
    $day_param = get_param('day', '0');
    if (is_numeric($day_param) && (int)$day_param >= -1 && (int)$day_param <= 7) {
        $day = (int)$day_param;
    } else {
        $day = 0;
    }
}

// 2. 解析图片尺寸（支持 4K/UHD 超清及常见分辨率白名单）
$size_input = strtoupper((string) get_param('size', '1920x1080'));
$supported_sizes = [
    'UHD'       => 'UHD',
    '4K'        => 'UHD',
    '1920X1080' => '1920x1080',
    '1366X768'  => '1366x768',
    '1080X1920' => '1080x1920',
    '1920X1200' => '1920x1200',
    '1280X768'  => '1280x768',
    '1024X768'  => '1024x768',
    '800X600'   => '800x600',
    '720X1280'  => '720x1280',
    '480X800'   => '480x800',
];

if (isset($supported_sizes[$size_input])) {
    $imgsize = $supported_sizes[$size_input];
} elseif (preg_match('/^\d{3,4}x\d{3,4}$/i', $size_input)) {
    $imgsize = strtolower($size_input);
} else {
    $imgsize = '1920x1080';
}

// 3. 读取本地轻量缓存（避免高频请求微软接口导致限流与响应延迟）
$cache_key = 'bing_wallpaper_idx_' . $day;
$cache_data = cache_get($cache_key);

if (!is_array($cache_data) || empty($cache_data['urlbase'])) {
    // 携带 uhd=1 参数请求 Bing 官方接口，附带 3 秒超时保护
    $bing_api = 'https://www.bing.com/HPImageArchive.aspx?format=js&idx=' . $day . '&n=1&uhd=1';
    $json = http_get_json($bing_api, 3);

    if ($json === null || empty($json['images'][0]['urlbase'])) {
        api_error(502, '无法获取必应壁纸数据，请稍后重试', 502);
    }

    $image = $json['images'][0];
    $cache_data = [
        'urlbase'       => (string) $image['urlbase'],
        'startdate'     => (string) ($image['startdate'] ?? ''),
        'copyright'     => (string) ($image['copyright'] ?? ''),
        'copyrightlink' => (string) ($image['copyrightlink'] ?? ''),
    ];

    // 缓存 2 小时 (7200秒)
    cache_set($cache_key, $cache_data, 7200);
}

// 4. 构建完整图片链接
$imgurlbase = 'https://www.bing.com' . $cache_data['urlbase'];
$imgurl = $imgurlbase . '_' . $imgsize . '.jpg';

// 5. 响应处理（JSON 元数据 vs 302 图片直链跳转）
$is_info = get_bool_param('info', false);
if ($is_info) {
    // 兼顾原有顶层字段与现代化标准规范
    $payload = [
        'code'    => 200,
        'message' => 'success',
        'title'   => $cache_data['copyright'],
        'url'     => $imgurl,
        'link'    => $cache_data['copyrightlink'],
        'time'    => $cache_data['startdate'],
        'data'    => [
            'title' => $cache_data['copyright'],
            'url'   => $imgurl,
            'link'  => $cache_data['copyrightlink'],
            'time'  => $cache_data['startdate'],
        ],
    ];

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
} else {
    // 携带 2 小时浏览器与 CDN 缓存头跳转
    api_redirect($imgurl, 7200);
}