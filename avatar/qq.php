<?php
// 关闭错误显示（生产环境必须）
ini_set('display_errors', 0);
error_reporting(E_ALL);

// 获取并验证 QQ 号
$qq = $_GET['qq'] ?? '';

// 获取尺寸参数（默认为640）
$s = $_GET['s'] ?? '640';

// 验证 QQ 号是否为有效数字（5-10 位）
if (!empty($qq) && is_numeric($qq) && strlen($qq) >= 5 && strlen($qq) <= 10) {
    // 验证尺寸参数
    $valid_sizes = ['1' => 40, '2' => 100, '3' => 640];
    $size = $valid_sizes[$s] ?? 640;
    
    $link = "https://q1.qlogo.cn/g?b=qq&nk={$qq}&s={$size}";
    header("Location: $link"); // 302 跳转
    exit();
} else {
    http_response_code(400);
    echo "请在 url 后加入有效的 QQ 号";
}
?>
