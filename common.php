<?php
declare(strict_types=1);

/**
 * WXies API - 公共基础引导文件
 */

// 错误处理与环境配置
ini_set('display_errors', '0');
error_reporting(E_ALL);

// 全局 CORS 跨域支持与预检请求响应
if (!headers_sent()) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

/**
 * 安全获取 GET 请求参数
 *
 * @param string $key 参数键名
 * @param mixed $default 默认值
 * @return mixed
 */
function get_param(string $key, $default = null) {
    if (!isset($_GET[$key])) {
        return $default;
    }
    $val = is_string($_GET[$key]) ? trim($_GET[$key]) : $_GET[$key];
    return $val === '' ? $default : $val;
}

/**
 * 获取布尔类型 GET 参数
 *
 * @param string $key 参数键名
 * @param bool $default 默认值
 * @return bool
 */
function get_bool_param(string $key, bool $default = false): bool {
    if (!isset($_GET[$key])) {
        return $default;
    }
    return filter_var($_GET[$key], FILTER_VALIDATE_BOOLEAN);
}

/**
 * 统一标准 JSON 输出
 *
 * @param int $code 业务状态码
 * @param string $message 提示信息
 * @param mixed $data 携带数据
 * @param int $httpStatus HTTP 状态码
 */
function api_json_response(int $code, string $message, $data = null, int $httpStatus = 200): void {
    if (!headers_sent()) {
        http_response_code($httpStatus);
        header('Content-Type: application/json; charset=utf-8');
    }
    echo json_encode([
        'code'    => $code,
        'message' => $message,
        'data'    => $data,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * 统一错误输出
 *
 * @param int $code 错误状态码
 * @param string $message 错误信息
 * @param int $httpStatus HTTP 状态码
 */
function api_error(int $code, string $message, int $httpStatus = 400): void {
    api_json_response($code, $message, null, $httpStatus);
}

/**
 * 统一 302 重定向并附带浏览器及 CDN 缓存头
 *
 * @param string $url 目标跳转 URL
 * @param int $cacheSeconds 缓存时间（秒），默认 24 小时
 * @param int $httpStatus HTTP 状态码
 */
function api_redirect(string $url, int $cacheSeconds = 86400, int $httpStatus = 302): void {
    if (!headers_sent()) {
        if ($cacheSeconds > 0) {
            header('Cache-Control: public, max-age=' . $cacheSeconds);
        } else {
            header('Cache-Control: no-store, no-cache, must-revalidate');
        }
        header('Location: ' . $url, true, $httpStatus);
    }
    exit;
}

/**
 * 安全的带超时网络 GET 请求（返回解码后的关联数组）
 *
 * @param string $url 请求地址
 * @param int $timeout 超时时间（秒）
 * @return array|null
 */
function http_get_json(string $url, int $timeout = 3): ?array {
    $opts = [
        'http' => [
            'method'        => 'GET',
            'timeout'       => $timeout,
            'header'        => "User-Agent: Mozilla/5.0 (compatible; WXies-API/1.0)\r\nAccept: application/json\r\n",
            'ignore_errors' => true,
        ],
        'ssl' => [
            'verify_peer'      => true,
            'verify_peer_name' => true,
        ],
    ];

    $context = stream_context_create($opts);
    $response = @file_get_contents($url, false, $context);
    if ($response === false || $response === '') {
        return null;
    }

    $decoded = json_decode($response, true);
    return is_array($decoded) ? $decoded : null;
}

/**
 * 获取本地缓存目录路径（自动确保目录及安全防护文件存在）
 */
function cache_dir(): string {
    $dir = __DIR__ . '/.cache';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
        @file_put_contents($dir . '/.htaccess', "Deny from all\n");
        @file_put_contents($dir . '/index.html', "");
    }
    return $dir;
}

/**
 * 获取缓存内容
 *
 * @param string $key 缓存键名
 * @return mixed|null 缓存未命中或过期返回 null
 */
function cache_get(string $key) {
    $file = cache_dir() . '/' . md5($key) . '.json';
    if (!file_exists($file)) {
        return null;
    }

    $content = @file_get_contents($file);
    if ($content === false || $content === '') {
        return null;
    }

    $data = json_decode($content, true);
    if (!is_array($data) || !isset($data['expire']) || !array_key_exists('value', $data)) {
        @unlink($file);
        return null;
    }

    if ($data['expire'] !== 0 && time() > $data['expire']) {
        @unlink($file);
        return null;
    }

    return $data['value'];
}

/**
 * 设置缓存内容
 *
 * @param string $key 缓存键名
 * @param mixed $value 缓存值
 * @param int $ttl 有效期（秒），默认 2 小时
 * @return bool
 */
function cache_set(string $key, $value, int $ttl = 7200): bool {
    $file = cache_dir() . '/' . md5($key) . '.json';
    $expire = $ttl > 0 ? time() + $ttl : 0;
    $data = [
        'expire' => $expire,
        'value'  => $value,
    ];

    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        return false;
    }

    return (bool) @file_put_contents($file, $json, LOCK_EX);
}
