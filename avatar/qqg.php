<?php
// 关闭错误显示（生产环境必须）
ini_set('display_errors', 0);
error_reporting(E_ALL);

// 获取并验证 QQ 群号
$qqg = $_GET['qqg'] ?? '';

// 获取尺寸参数（默认为640）
$s = $_GET['s'] ?? '640';

// 验证 QQ 群号是否为有效数字（5-10 位）
if (!empty($qqg) && is_numeric($qqg) && strlen($qqg) >= 5 && strlen($qqg) <= 10) {
    // 验证尺寸参数
    $valid_sizes = ['1' => 40, '2' => 100, '3' => 640];
    $size = $valid_sizes[$s] ?? 640;
    
    $link = "https://p.qlogo.cn/gh/{$qqg}/{$qqg}/{$size}";
    header("Location: $link"); // 302 跳转
    exit();
} else {
    http_response_code(400);
    echo "请在 url 后加入有效的 QQ 群号";
}
?>
