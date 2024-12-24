<?php
// 读取图片
function readImage($imagePath)
{
    return imagecreatefrompng($imagePath);
}

// 设置文字颜色
function setTextColor($image)
{
    return imagecolorallocate($image, 0, 0, 0);
}

// 获取文本并拆分成行，自动处理换行
function getAndSplitText($name, $city, $time)
{
    // 为 "恭喜你在xxxxxxxxx" 行添加两个字符的缩进（即两个空格），并插入城市名
    $text = $name . " 同学\n" . wordwrap("    恭喜你在" . $city . "xxxxxxxxx", 113, "\n", true) . "\n" . $time;
    return explode("\n", $text);
}

// 在图片上插入单行文字
function insertTextOnImage($image, $text, $x, $y, $textColor, $font, $fontSize)
{
    imagettftext($image, $fontSize, 0, $x, $y, $textColor, $font, $text);
}

// 输出图片并保存为文件
function saveImageToFile($image, $filePath)
{
    imagepng($image, $filePath);
    imagedestroy($image);
}

// 图片路径
$imagePath = 'cert.png';
// 字体文件路径
$font = 'font.ttc';
// 文字大小
$fontSize = 100;

// 定义每一行文字的坐标
$coordinates = [
    ['x' => 400, 'y' => 400],  // 第一行
    ['x' => 500, 'y' => 600],  // 第二行
    ['x' => 2000, 'y' => 2000],  // 第三行
];

// 设定要显示的名称、城市和当前时间
$name = $_GET["name"];
$city = $_GET["city"];
$time = date("Y年m月d日");  // 获取当前日期，格式：2024年12月24日

// 读取图片
$image = readImage($imagePath);
// 设置文字颜色
$textColor = setTextColor($image);
// 获取并拆分文本
$lines = getAndSplitText($name, $city, $time);

// 确保坐标数量与文本行数一致
if (count($lines) > count($coordinates)) {
    die('坐标数量不足，无法为所有行文字指定坐标。');
}

// 在图片上逐行插入文字
for ($i = 0; $i < count($lines); $i++) {
    $x = $coordinates[$i]['x'];
    $y = $coordinates[$i]['y'];
    insertTextOnImage($image, $lines[$i], $x, $y, $textColor, $font, $fontSize);
}

// 根据 `name` 和 `city` 动态生成文件名
$file_name = "{$name}_{$city}.png";

// 保存图片到文件
saveImageToFile($image, $file_name);

// 进行文件下载
header("Content-Disposition: attachment; filename=\"$file_name\"");
header("Content-Type: image/png");
header("Content-Length: " . filesize($file_name));
readfile($file_name);

// 删除文件
unlink($file_name);
?>
