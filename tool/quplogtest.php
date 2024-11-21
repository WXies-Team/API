<?php

// 获取 URL 参数
$link_windows_x86 = $_GET["link_windows_x86"];
$link_windows_x64 = $_GET["link_windows_x64"];
$link_windows_arm = $_GET["link_windows_arm"];
$link_macos = $_GET["link_macos"];
$link_linux = $_GET["link_linux"];
$update_log = $_GET["log"];
$backup_link_num = $_GET["blink"];

// Windows x86
preg_match('/QQNT\/([a-z0-9]+)\/QQ(\d+\.\d+\.\d+)\.(\d+)_x86/', $url_windows_x86, $matches_x86);
$winodws_x86_md5 = $matches_x86[1];
$windows_version_name = $matches_x86[2];
$version_code = $matches_x86[3];

// Windows x64
preg_match('/QQNT\/([a-z0-9]+)\/QQ(\d+\.\d+\.\d+)\.(\d+)_x64/', $url_windows_x64, $matches_x64);
$winodws_x64_md5 = $matches_x64[1];

// Windows arm
preg_match('/QQNT\/([a-z0-9]+)\/QQ(\d+\.\d+\.\d+)\.(\d+)_arm64/', $url_windows_arm, $matches_arm);
$winodws_arm_md5 = $matches_arm[1];

// MacOS
preg_match('/QQNT\/([a-z0-9]+)\/QQ_v(\d+\.\d+\.\d+)\.(\d+)/', $url_macos, $matches_macos);
$macos_md5 = $matches_macos[1];
$macos_version_name = $matches_macos[2];

// Linux
preg_match('/QQNT\/([a-z0-9]+)\/linuxqq_(\d+\.\d+\.\d+)-(\d+)/', $url_linux, $matches_linux);
$linux_md5 = $matches_linux[1];
$linux_version_name = $matches_linux[2];

// 生成更新内容
$update_content = "**Windows QQ_NT {$windows_version_name}.{$version_code} &**\n";
$update_content .= "**MacOS QQ_NT {$macos_version_name}.{$version_code} &**\n";
$update_content .= "**Linux QQ_NT {$linux_version_name}.{$version_code}**\n";
$update_content .= "\n**官方更新内容：**\n{$update_log}\n\n";
$update_content .= "**下载：**\n";
$update_content .= "- Windows:\n";
$update_content .= "[X86](https://dldir1.qq.com/qqfile/qq/QQNT/{$winodws_x86_md5}/QQ{$windows_version_name}.{$version_code}_x86.exe) | [X64](https://dldir1.qq.com/qqfile/qq/QQNT/{$winodws_x64_md5}/QQ{$windows_version_name}.{$version_code}_x64.exe) | [Arm](https://dldir1.qq.com/qqfile/qq/QQNT/{$winodws_arm_md5}/QQ{$windows_version_name}.{$version_code}_arm64.exe)\n";
$update_content .= "- MacOS:\n";
$update_content .= "[Dmg](https://dldir1.qq.com/qqfile/qq/QQNT/{$macos_md5}/QQ_v{$macos_version_name}.{$version_code}.dmg)\n";
$update_content .= "- Linux:\n";
$update_content .= "[DEB_x64](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_amd64.deb) | [RPM_x64](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_x86_64.rpm) | [Appimage_x64](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_x86_64.AppImage)\n";
$update_content .= "[DEB_Arm](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_arm64.deb) | [RPM_Arm](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_aarch64.rpm) | [Appimage_Arm](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_arm64.AppImage)\n";
$update_content .= "[LoongArch](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_loongarch64.deb) | [Mips](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_mips64el.deb)\n";

// 备份链接
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

// 删除文件<?php

// 获取 URL 参数
$url_windows_x86 = "https://dldir1.qq.com/qqfile/qq/QQNT/09d7ff8c/QQ9.9.16.29804_x86.exe";
$ur$link_windows_x86 = $_GET["link_windows_x86"];
$link_windows_x64 = $_GET["link_windows_x64"];
$link_windows_arm = $_GET["link_windows_arm"];
$link_macos = $_GET["link_macos"];
$link_linux = $_GET["link_linux"];
$update_log = $_GET["log"];
$backup_link_num = $_GET["blink"];

// Windows x86
preg_match('/QQNT\/([a-z0-9]+)\/QQ(\d+\.\d+\.\d+)\.(\d+)_x86/', $url_windows_x86, $matches_x86);
$winodws_x86_md5 = $matches_x86[1];
$windows_version_name = $matches_x86[2];
$version_code = $matches_x86[3];

// Windows x64
preg_match('/QQNT\/([a-z0-9]+)\/QQ(\d+\.\d+\.\d+)\.(\d+)_x64/', $url_windows_x64, $matches_x64);
$winodws_x64_md5 = $matches_x64[1];

// Windows arm
preg_match('/QQNT\/([a-z0-9]+)\/QQ(\d+\.\d+\.\d+)\.(\d+)_arm64/', $url_windows_arm, $matches_arm);
$winodws_arm_md5 = $matches_arm[1];

// MacOS
preg_match('/QQNT\/([a-z0-9]+)\/QQ_v(\d+\.\d+\.\d+)\.(\d+)/', $url_macos, $matches_macos);
$macos_md5 = $matches_macos[1];
$macos_version_name = $matches_macos[2];

// Linux
preg_match('/QQNT\/([a-z0-9]+)\/linuxqq_(\d+\.\d+\.\d+)-(\d+)/', $url_linux, $matches_linux);
$linux_md5 = $matches_linux[1];
$linux_version_name = $matches_linux[2];

// 生成更新内容
$update_content = "**Windows QQ_NT {$windows_version_name}.{$version_code} &**\n";
$update_content .= "**MacOS QQ_NT {$macos_version_name}.{$version_code} &**\n";
$update_content .= "**Linux QQ_NT {$linux_version_name}.{$version_code}**\n";
$update_content .= "\n**官方更新内容：**\n{$update_log}\n\n";
$update_content .= "**下载：**\n";
$update_content .= "- Windows:\n";
$update_content .= "[X86](https://dldir1.qq.com/qqfile/qq/QQNT/{$winodws_x86_md5}/QQ{$windows_version_name}.{$version_code}_x86.exe) | [X64](https://dldir1.qq.com/qqfile/qq/QQNT/{$winodws_x64_md5}/QQ{$windows_version_name}.{$version_code}_x64.exe) | [Arm](https://dldir1.qq.com/qqfile/qq/QQNT/{$winodws_arm_md5}/QQ{$windows_version_name}.{$version_code}_arm64.exe)\n";
$update_content .= "- MacOS:\n";
$update_content .= "[Dmg](https://dldir1.qq.com/qqfile/qq/QQNT/{$macos_md5}/QQ_v{$macos_version_name}.{$version_code}.dmg)\n";
$update_content .= "- Linux:\n";
$update_content .= "[DEB_x64](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_amd64.deb) | [RPM_x64](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_x86_64.rpm) | [Appimage_x64](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_x86_64.AppImage)\n";
$update_content .= "[DEB_Arm](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_arm64.deb) | [RPM_Arm](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_aarch64.rpm) | [Appimage_Arm](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_arm64.AppImage)\n";
$update_content .= "[LoongArch](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_loongarch64.deb) | [Mips](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_mips64el.deb)\n";

// 备份链接
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

// 删除文件
unlink($file_name);

?>

unlink($file_name);

?>
