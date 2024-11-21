<?php

// 获取用户输入的 URL
$windows_url = $_GET["win_url"];
$linux_url = $_GET["linux_url"];
$macos_url = $_GET["macos_url"];
$version_code = $_GET["vcode"];
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
        $info['md5'] = $matches[1];
    }

    // 提取版本名
    if (preg_match($pattern['version_name'], $url, $matches)) {
        $info['version_name'] = $matches[1];
    }

    // 提取版本号
    if (preg_match($pattern['version_code'], $url, $matches)) {
        $info['version_code'] = $matches[1];
    }

    return $info;
}

// 提取 Windows, MacOS, 和 Linux 的信息
$windows_info = extract_info($windows_url, $pattern);
$macos_info = extract_info($macos_url, $pattern);
$linux_info = extract_info($linux_url, $pattern);

// 替换链接
$update_content = "**Windows QQ_NT {$windows_info['version_name']}.{$version_code} &**\n";
$update_content .= "**MacOS QQ_NT {$macos_info['version_name']}.{$version_code} &**\n";
$update_content .= "**Linux QQ_NT {$linux_info['version_name']}.{$version_code}**\n";
$update_content .= "\n**官方更新内容：**\n{$update_log}\n\n";
$update_content .= "**下载：**\n";
$update_content .= "- Windows:\n";
$update_content .= "[X86](https://dldir1.qq.com/qqfile/qq/QQNT/{$windows_info['md5']}/QQ{$windows_info['version_name']}.{$version_code}_x86.exe) | [X64](https://dldir1.qq.com/qqfile/qq/QQNT/{$windows_info['md5']}/QQ{$windows_info['version_name']}.{$version_code}_x64.exe) | [Arm](https://dldir1.qq.com/qqfile/qq/QQNT/{$windows_info['md5']}/QQ{$windows_info['version_name']}.{$version_code}_arm64.exe)\n";
$update_content .= "- MacOS:\n";
$update_content .= "[Dmg](https://dldir1.qq.com/qqfile/qq/QQNT/{$macos_info['md5']}/QQ_v{$macos_info['version_name']}.{$version_code}.dmg)\n";
$update_content .= "- Linux:\n";
$update_content .= "[DEB_x64](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_info['md5']}/linuxqq_{$linux_info['version_name']}-{$version_code}_amd64.deb) | [RPM_x64](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_info['md5']}/linuxqq_{$linux_info['version_name']}-{$version_code}_x86_64.rpm) | [Appimage_x64](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_info['md5']}/linuxqq_{$linux_info['version_name']}-{$version_code}_x86_64.AppImage)\n";
$update_content .= "[DEB_Arm](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_info['md5']}/linuxqq_{$linux_info['version_name']}-{$version_code}_arm64.deb) | [RPM_Arm](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_info['md5']}/linuxqq_{$linux_info['version_name']}-{$version_code}_aarch64.rpm) | [Appimage_Arm](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_info['md5']}/linuxqq_{$linux_info['version_name']}-{$version_code}_arm64.AppImage)\n";
$update_content .= "[LoongArch](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_info['md5']}/linuxqq_{$linux_info['version_name']}-{$version_code}_loongarch64.deb) | [Mips](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_info['md5']}/linuxqq_{$linux_info['version_name']}-{$version_code}_mips64el.deb)\n";

// 备份
if ($backup_link_num !== "") {
    $backup_link_num = intval($backup_link_num) ?: 0;
    $update_content .= "\n**备份：**\n";
    $update_content .= "- Windows:\n";
    $update_content .= "[X86](https://t.me/linqiqi_backup/{$backup_link_num}) | [X64](https://t.me/linqiqi_backup/" . ++$backup_link_num . ") | [Arm](https://t.me/linqiqi_backup/" . ++$backup_link_num . ")\n";
    $update_content .= "- MacOS:\n";
    $update_content .= "[Dmg](https://t.me/linqiqi_backup/" . ++$backup_link_num . ")\n";
    $update_content .= "- Linux:\n";
    $update_content .= "[DEB](https://t.me/linqiqi_backup/" . ++$backup_link_num . ") | [RPM](https://t.me/linqiqi_backup/" . ++$backup_link_num . ") | [Appimage](https://t.me/linqiqi_backup/" . ++$backup_link_num . ") | [LoongArch](https://t.me/linqiqi_backup/" . ++$backup_link_num . ") | [Mips](https://t.me/linqiqi_backup/" . ++$backup_link_num . ")\n";
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
