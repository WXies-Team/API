<?php

// 图片路径
$imagePath = 'cert.png';

// 检查图片文件是否存在
if (!file_exists($imagePath)) {
    die('图片文件不存在。');
}

// 读取图片
$image = imagecreatefrompng($imagePath);

// 设置文字颜色
$textColor = imagecolorallocate($image, 0, 0, 0);

// 要插入的文字
$text = $_GET["text"];

// 字体文件路径
$font = 'font.ttc';

// 文字大小
$fontSize = 100;

// 文字插入位置
$x = 1000;
$y = 1000;

imagettftext($image, $fontSize, 0, $x, $y, $textColor, $font, $text);

// 输出图片到浏览器
header('Content-Type: image/png');
imagepng($image);

// 释放内存
imagedestroy($image);
?>