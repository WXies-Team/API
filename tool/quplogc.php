<?php

// 获取用户输入的 URL
$url = $_GET["url"];
$update_log = $_GET["log"];
$backup_link_num = $_GET["blink"];

// 定义正则表达式
$pattern = [
    'windows_x86' => '/\/([a-f0-9]{8})\/QQ(\d+\.\d+\.\d+)\_(\d+)\_x86\.exe/i', // Windows x86 提取md5、版本名和版本号
    'windows_x64' => '/\/([a-f0-9]{8})\/QQ(\d+\.\d+\.\d+)\_(\d+)\_x64\.exe/i', // Windows x64 提取md5、版本名和版本号
    'windows_arm' => '/\/([a-f0-9]{8})\/QQ(\d+\.\d+\.\d+)\_(\d+)\_arm64\.exe/i', // Windows arm 提取md5、版本名和版本号
    'macos' => '/\/([a-f0-9]{8})\/QQ\_v(\d+\.\d+\.\d+)\.(\d+)\.dmg/i', // macOS 提取md5、版本名和版本号
    'linux' => '/\/([a-f0-9]{8})\/linuxqq\_(\d+\.\d+\.\d+)\-(\d+)\_(\w+)\.(deb|rpm|AppImage)/i' // Linux 提取md5、版本名和版本号
];

// 函数用来解析 URL
function extract_info($url, $pattern) {
    $info = [];
    
    // 根据平台的不同提取相应的信息
    foreach ($pattern as $platform => $regex) {
        if (preg_match($regex, $url, $matches)) {
            // Windows x86, x64, arm
            if (strpos($platform, 'windows') !== false) {
                $info['md5'] = $matches[1];
                $info['version_name'] = $matches[2];
                $info['version_code'] = $matches[3];
            }
            // macOS
            if ($platform == 'macos') {
                $info['md5'] = $matches[1];
                $info['version_name'] = $matches[2];
                $info['version_code'] = $matches[3];
            }
            // Linux
            if ($platform == 'linux') {
                $info['md5'] = $matches[1];
                $info['version_name'] = $matches[2];
                $info['version_code'] = $matches[3];
            }
        }
    }

    return $info;
}

// 提取各个平台的信息
$windows_x86_info = extract_info($url, [$pattern['windows_x86']]);
$windows_x64_info = extract_info($url, [$pattern['windows_x64']]);
$windows_arm_info = extract_info($url, [$pattern['windows_arm']]);
$macos_info = extract_info($url, [$pattern['macos']]);
$linux_info = extract_info($url, [$pattern['linux']]);

// 替换链接内容
$update_content = "**Windows QQ_NT {$windows_x86_info['version_name']}.{$windows_x86_info['version_code']} &**\n";
$update_content .= "**MacOS QQ_NT {$macos_info['version_name']}.{$macos_info['version_code']} &**\n";
$update_content .= "**Linux QQ_NT {$linux_info['version_name']}.{$linux_info['version_code']}**\n";
$update_content .= "\n**官方更新内容：**\n{$update_log}\n\n";
$update_content .= "**下载：**\n";
$update_content .= "- Windows:\n";
$update_content .= "[X86](https://dldir1.qq.com/qqfile/qq/QQNT/{$windows_x86_info['md5']}/QQ{$windows_x86_info['version_name']}.{$windows_x86_info['version_code']}_x86.exe) | ";
$update_content .= "[X64](https://dldir1.qq.com/qqfile/qq/QQNT/{$windows_x64_info['md5']}/QQ{$windows_x64_info['version_name']}.{$windows_x64_info['version_code']}_x64.exe) | ";
$update_content .= "[Arm](https://dldir1.qq.com/qqfile/qq/QQNT/{$windows_arm_info['md5']}/QQ{$windows_arm_info['version_name']}.{$windows_arm_info['version_code']}_arm64.exe)\n";
$update_content .= "- MacOS:\n";
$update_content .= "[Dmg](https://dldir1.qq.com/qqfile/qq/QQNT/{$macos_info['md5']}/QQ_v{$macos_info['version_name']}.{$macos_info['version_code']}.dmg)\n";
$update_content .= "- Linux:\n";
$update_content .= "[DEB_x64](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_info['md5']}/linuxqq_{$linux_info['version_name']}-{$linux_info['version_code']}_amd64.deb) | ";
$update_content .= "[RPM_x64](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_info['md5']}/linuxqq_{$linux_info['version_name']}-{$linux_info['version_code']}_x86_64.rpm) | ";
$update_content .= "[Appimage_x64](https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_info['md5']}/linuxqq_{$linux_info['version_name']}-{$linux_info['version_code']}_x86_64.AppImage)\n";

// 备份链接处理
if ($backup_link_num !== "") {
    $backup_link_num = intval($backup_link_num) ?: 0;
    $update_content .= "\n**备份：**\n";
    $update_content .= "- Windows:\n";
    $update_content .= "[X86](https://t.me/linqiqi_backup/{$backup_link_num}) | [X64](https://t.me/linqiqi_backup/" . ++$backup_link_num . ") | ";
    $update_content .= "[Arm](https://t.me/linqiqi_backup/" . ++$backup_link_num . ")\n";
    $update_content .= "- MacOS:\n";
    $update_content .= "[Dmg](https://t.me/linqiqi_backup/" . ++$backup_link_num . ")\n";
    $update_content .= "- Linux:\n";
    $update_content .= "[DEB](https://t.me/linqiqi_backup/" . ++$backup_link_num . ") | [RPM](https://t.me/linqiqi_backup/" . ++$backup_link_num . ") | ";
    $update_content .= "[Appimage](https://t.me/linqiqi_backup/" . ++$backup_link_num . ") | ";
    $update_content .= "[LoongArch](https://t.me/linqiqi_backup/" . ++$backup_link_num . ") | ";
    $update_content .= "[Mips](https://t.me/linqiqi_backup/" . ++$backup_link_num . ")\n";
}

$update_content .= "\nTG@ [QQ/TIM For Update Log](https://t.me/qq_updatelog)\n";
$update_content .= "#QQ_NT_Windows\n";
$update_content .= "#QQ_NT_MacOS\n";
$update_content .= "#QQ_NT_Linux";

// 输出到文件
$file_name = "QQ_Update_Log_{$windows_x86_info['version_code']}.md";
file_put_contents($file_name, $update_content);

// 文件下载
header("Content-Disposition: attachment; filename=\"$file_name\"");
header("Content-Type: text/markdown");
header("Content-Length: " . filesize($file_name));
readfile($file_name);

// 删除临时文件
unlink($file_name);

?>
