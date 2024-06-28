<?php

// 设置变量
$windows_version_name = $_GET["win"] ?: "9.9.11";
$linux_version_name = $_GET["linux"] ?: "3.2.9";
$macos_version_name = $_GET["macos"] ?: "6.9.36";
$version_code = $_GET["vcode"];
$winodws_x86_md5 = $_GET["w86"];
$winodws_x64_md5 = $_GET["w64"];
$winodws_arm_md5 = $_GET["warm"];
$macos_md5 = $_GET["mac"];
$linux_md5 = $_GET["linuxc"];
$update_log = $_GET["log"];
$backup_link_num = $_GET["blink"];

// 替换链接
$update_content = "Windows QQ_NT {$windows_version_name}.{$version_code} &\n";
$update_content .= "MacOS QQ_NT {$macos_version_name}.{$version_code} &\n";
$update_content .= "Linux QQ_NT {$linux_version_name}.{$version_code}\n";
$update_content .= "\n官方更新内容：\n{$update_log}\n\n";
$update_content .= "下载：\n";
$update_content .= "- Windows:\n";
$update_content .= "[X86](https://dldir1.qq.com/qqfile/qq/QQNT/{$winodws_x86_md5}/QQ{$windows_version_name}.{$version_code}_x86.exe) | [X64](https://dldir1.qq.com/qqfile/qq/QQNT/{$winodws_x64_md5}/QQ{$windows_version_name}.{$version_code}_x64.exe) | [Arm](https://dldir1.qq.com/qqfile/qq/QQNT/{$winodws_arm_md5}/QQ{$windows_version_name}.{$version_code}_arm64.exe)\n";
$update_content .= "- MacOS:\n";
$update_content .= "[Dmg](https://dldir1.qq.com/qqfile/qq/QQNT/{$macos_md5}/QQ_v{$macos_version_name}.{$version_code}.dmg )\n";
$update_content .= "- Linux:\n";
$update_content .= "[DEB_x64](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_amd64.deb) | [RPM_x64](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_x86_64.rpm) | [Appimage_x64](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_x86_64.AppImage)\n";
$update_content .= "[DEB_Arm](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_arm64.deb) | [RPM_Arm](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_aarch64.rpm) | [Appimage_Arm](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_arm64.AppImage)\n";
$update_content .= "[LoongArch](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_loongarch64.deb) | [Mips](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_mips64el.deb)\n";
// 备份
if ($backup_link_num !== "") {
    $backup_link_num = intval($backup_link_num) ?: 0;
    $update_content .= "\n备份：\n";
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

// 删除
unlink($file_name);

?>