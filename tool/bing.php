<?php
// 关闭错误显示（生产环境必须）
ini_set('display_errors', 0);
error_reporting(E_ALL);

// 判断是否随机调用
$rand = $_GET['rand'] ?? 'false';
if ($rand === 'true') {
    $gettime = rand(-1, 7);
} else {
    // 若不为随机调用则判断是否指定日期
    $gettimebase = $_GET['day'] ?? '';
    if (empty($gettimebase)) {
        $gettime = 0;
    } else {
        $gettime = $gettimebase;
    }
}

// 获取 Bing Json 信息
$json_string = file_get_contents('https://www.bing.com/HPImageArchive.aspx?format=js&idx=' . $gettime . '&n=1');
if ($json_string === false) {
    http_response_code(502);
    echo "无法获取必应数据";
    exit();
}

// 转换为 PHP 数组
$data = json_decode($json_string);
if ($data === null || empty($data->images[0])) {
    http_response_code(502);
    echo "必应数据格式错误";
    exit();
}

// 提取基础 url
$imgurlbase = "https://www.bing.com" . $data->images[0]->urlbase;

// 判断是否指定图片大小
$imgsizebase = $_GET['size'] ?? '';
if (empty($imgsizebase)) {
    $imgsize = "1920x1080";
} else {
    $imgsize = $imgsizebase;
}

// 建立完整 url
$imgurl = $imgurlbase . "_" . $imgsize . ".jpg";

// 获取其他信息
$imgtime = $data->images[0]->startdate;
$imgtitle = $data->images[0]->copyright;
$imglink = $data->images[0]->copyrightlink;

// 判断是否只获取图片信息
$info = $_GET['info'] ?? 'false';
if ($info === 'true') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        "title" => $imgtitle,
        "url" => $imgurl,
        "link" => $imglink,
        "time" => $imgtime
    ], JSON_UNESCAPED_UNICODE);
} else {
    // 若不是则跳转 url
    header("Location: $imgurl");
}
