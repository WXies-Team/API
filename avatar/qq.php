<?php
declare(strict_types=1);

require_once __DIR__ . '/../common.php';

// 获取参数
$qq = get_param('qq', '');
$s  = (string) get_param('s', '640');

// 验证 QQ 号（5-12 位正整数）
if (!preg_match('/^[1-9]\d{4,11}$/', (string)$qq)) {
    api_error(400, '请在 URL 中提供有效的 QQ 号 (5-12位数字)');
}

// 尺寸映射：兼容代号 1/2/3 以及像素值 40/100/640
$size_map = [
    '1'   => 40,
    '40'  => 40,
    '2'   => 100,
    '100' => 100,
    '3'   => 640,
    '640' => 640,
];
$size = $size_map[$s] ?? 640;

$link = 'https://q1.qlogo.cn/g?b=qq&nk=' . $qq . '&s=' . $size;
api_redirect($link, 86400);
?>
