<?php

// 设置变量
$windows_version_name = $_GET["win"] ?: "9.9.12";
$linux_version_name = $_GET["linux"] ?: "3.2.10";
$macos_version_name = $_GET["macos"] ?: "6.9.39";
$version_code = $_GET["vcode"];
$feature_code = $_GET["fcode"];
$pubilsh_num = $_GET["pnum" ?: "01"];
$update_log = $_GET["log"];
$backup_link_num = $_GET["blink"];

// 替换链接
$update_content = "**Windows QQ_NT {$windows_version_name}.{$version_code} &**\n";
$update_content .= "**MacOS QQ_NT {$macos_version_name}.{$version_code} &**\n";
$update_content .= "**Linux QQ_NT {$linux_version_name}.{$version_code}**\n";
$update_content .= "\n**官方更新内容：**\n{$update_log}\n\n";
$update_content .= "**下载：**\n";
$update_content .= "- Windows:\n";
$update_content .= "[X86](https://dldir1.qq.com/qqfile/qq/QQNT/Windows/QQ_{$windows_version_name}_{$feature_code}_x86_{$pubilsh_num}.exe) | [X64](https://dldir1.qq.com/qqfile/qq/QQNT/Windows/QQ_{$windows_version_name}_{$feature_code}_x64_{$pubilsh_num}.exe) | [Arm](https://dldir1.qq.com/qqfile/qq/QQNT/Windows/QQ_{$windows_version_name}_{$feature_code}_arm64_{$pubilsh_num}.exe)\n";
$update_content .= "- MacOS:\n";
$update_content .= "[Dmg](https://dldir1.qq.com/qqfile/qq/QQNT/Mac/QQ_{$macos_version_name}_{$feature_code}_{$pubilsh_num}.dmg)\n";
$update_content .= "- Linux:\n";
$update_content .= "[DEB_x64](https://dldir1.qq.com/qqfile/qq/QQNT/Linux/QQ_{$linux_version_name}_{$feature_code}_amd64_{$pubilsh_num}.deb) | [RPM_x64](https://dldir1.qq.com/qqfile/qq/QQNT/Linux/QQ_{$linux_version_name}_{$feature_code}_x86_64_{$pubilsh_num}.rpm) | [Appimage_x64](https://dldir1.qq.com/qqfile/qq/QQNT/Linux/QQ_{$linux_version_name}_{$feature_code}_x86_64_{$pubilsh_num}.AppImage)\n";
$update_content .= "[DEB_Arm](https://dldir1.qq.com/qqfile/qq/QQNT/Linux/QQ_{$linux_version_name}_{$feature_code}_arm64_{$pubilsh_num}.deb) | [RPM_Arm](https://dldir1.qq.com/qqfile/qq/QQNT/Linux/QQ_{$linux_version_name}_{$feature_code}_aarch64_{$pubilsh_num}.rpm) | [Appimage_Arm](https://dldir1.qq.com/qqfile/qq/QQNT/Linux/QQ_{$linux_version_name}_{$feature_code}_arm64_{$pubilsh_num}.AppImage)\n";
$update_content .= "[LoongArch](https://dldir1.qq.com/qqfile/qq/QQNT/Linux/QQ_{$linux_version_name}_{$feature_code}_loongarch64_{$pubilsh_num}.deb) | [Mips](https://dldir1.qq.com/qqfile/qq/QQNT/Linux/QQ_{$linux_version_name}_{$feature_code}_mips64el_{$pubilsh_num}.deb)\n";

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
$file_name = "QQ_Update_Log_{$feature_code}.md";
file_put_contents($file_name, $update_content);

// 文件下载
header("Content-Disposition: attachment; filename=\"$file_name\"");
header("Content-Type: text/markdown");
header("Content-Length: " . filesize($file_name));
readfile($file_name);

// 删除
unlink($file_name);

?>