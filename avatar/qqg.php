<?php

$qqg = $_GET['qqg'];
$link = "https://p.qlogo.cn/gh/{$qqg}/{$qqg}/640";

if ($qqg != null)
{
    header("Location:$link"); // 302 跳转
}
else
{
    echo "请在url后加入?qqg=QQ群号";
}

?>
