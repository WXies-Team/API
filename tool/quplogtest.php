<?php

// 获取用户输入的 URL 和其他信息
$url = $_GET["url"];
$update_log = $_GET["log"];
$backup_link_num = $_GET["blink"];

// 定义正则表达式
$pattern = [
    'md5' => '/\/([a-f0-9]{8})\//i', // 提取8位md5值
    'version_name' => '/(\d+\.\d+\.\d+)(?=\-\d+|\.dmg|\.exe)/i', // 提取版本名
    'version_code' => '/(\d+)(?=\_x86|\_x64|\_arm|\_aarch64)/i' // 提取版本号
];

// 函数用来解析 URL
function extract_info($url, $pattern) {
    $info = [];
    
    // 提取md5
    if (preg_match($pattern['md5'], $url, $matches)) {
        $info['md5'] = $matches[1]; // 提取到的MD5值
    }

    // 提取版本名
    if (preg_match($pattern['version_name'], $url, $matches)) {
        $info['version_name'] = $matches[1]; // 提取到的版本名
    }

    // 提取版本号
    if (preg_match($pattern['version_code'], $url, $matches)) {
        $info['version_code'] = $matches[1]; // 提取到的版本号
    }

    return $info;
}

// 提取信息
$windows_x86_info = extract_info($url, $pattern);
$windows_x64_info = extract_info($url, $pattern);
$windows_arm_info = extract_info($url, $pattern);
$macos_info = extract_info($url, $pattern);
$linux_info = extract_info($url, $pattern);

// 设定 MD5 和版本号
$windows_x86_md5 = $windows_x86_info['md5'];
$windows_x64_md5 = $windows_x64_info['md5'];
$windows_arm_md5 = $windows_arm_info['md5'];
$macos_md5 = $macos_info['md5'];
$linux_md5 = $linux_info['md5'];

$windows_version_name = $windows_x86_info['version_name'];
$macos_version_name = $macos_info['version_name'];
$linux_version_name = $linux_info['version_name'];

$version_code = $windows_x86_info['version_code']; // 所有平台使用同一个 version_code

// 保留原有的 update_content
$update_content = $update_log; // 保留原始的内容

// 添加动态链接内容
$update_content .= "\n**Windows x86**: https://dldir1.qq.com/qqfile/qq/QQNT/{$windows_x86_md5}/QQ{$windows_version_name}.{$version_code}_x86.exe\n";
$update_content .= "**Windows x64**: https://dldir1.qq.com/qqfile/qq/QQNT/{$windows_x64_md5}/QQ{$windows_version_name}.{$version_code}_x64.exe\n";
$update_content .= "**Windows arm**: https://dldir1.qq.com/qqfile/qq/QQNT/{$windows_arm_md5}/QQ{$windows_version_name}.{$version_code}_arm64.exe\n";
$update_content .= "**MacOS**: https://dldir1.qq.com/qqfile/qq/QQNT/{$macos_md5}/QQ_v{$macos_version_name}.{$version_code}.dmg\n";
$update_content .= "**Linux**: https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_aarch64.rpm\n";
$update_content .= "https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_x86_64.rpm\n";
$update_content .= "https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_amd64.deb\n";
$update_content .= "https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_arm64.deb\n";
$update_content .= "https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_loongarch64.deb\n";
$update_content .= "https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_mips64el.deb\n";
$update_content .= "https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_arm64.AppImage\n";
$update_content .= "https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_x86_64.AppImage\n";

// 备份链接
if ($backup_link_num !== "") {
    $backup_link_num = intval($backup_link_num) ?: 0;
    $update_content .= "\n**备份：**\n";
    $update_content .= "- Windows x86: [Backup Link](https://t.me/linqiqi_backup/{$backup_link_num})\n";
    $update_content .= "- Windows x64: [Backup Link](https://t.me/linqiqi_backup/" . ++$backup_link_num . ")\n";
    $update_content .= "- Windows arm: [Backup Link](https://t.me/linqiqi_backup/" . ++$backup_link_num . ")\n";
    $update_content .= "- MacOS: [Backup Link](https://t.me/linqiqi_backup/" . ++$backup_link_num . ")\n";
    $update_content .= "- Linux: [Backup Link](https://t.me/linqiqi_backup/" . ++$backup_link_num . ")\n";
}

$update_content .= "\nTG@ [QQ/TIM For Update Log](https://t.me/qq_updatelog)\n";
$update_content .= "#QQ_NT_Windows\n";
$update_content .= "#QQ_NT_MacOS\n";
$update_content .= "#QQ_NT_Linux";

// 输出到文件
$file_name = "QQ_Update_Log_{$version_code}.md";
file_put_contents($file_name, $update_content);

// 文件下载
header("Content-Disposition: attachment; filename=\"$file_name\"");
header("Content-Type: text/markdown");
header("Content-Length: " . filesize($file_name));
readfile($file_name);

// 删除临时文件
unlink($file_name);

?>
