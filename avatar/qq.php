<?php

$qq = $_GET['qq'];
$link = "https://q1.qlogo.cn/g?b=qq&nk={$qq}&s=640";

if ($qq != null)
{
    header("Location:$link"); // 302 跳转
}
else
{
    echo "请在url后加入?qq=QQ号";
}

?>
