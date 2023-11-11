<?php

$link = "https://q.qlogo.cn/headimg_dl?dst_uin={$_GET['qq']}&spec=640";

$qq = $_GET['qq'];

if ($qq != null)
{
    header("Location:$link"); // 302 跳转
}
else
{
    echo "请在url后加入?=QQ号";
}

?>