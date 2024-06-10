<?php

$qq = $_GET['qq'];
$spec = $_GET["spec"] ?: "640";
$link = "https://q.qlogo.cn/headimg_dl?dst_uin={$_GET['qq']}&spec={$_GET['spec']}";

if ($qq != null)
{
    header("Location:$link"); // 302 跳转
}
else
{
    echo "请在url后加入?=QQ号";
}

?>
