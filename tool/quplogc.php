<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

error_reporting(0);

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$link_windows_x86 = $_GET["link_windows_x86"];
$link_windows_x64 = $_GET["link_windows_x64"];
$link_windows_arm = $_GET["link_windows_arm"];
$link_macos = $_GET["link_macos"];
$link_linux = $_GET["link_linux"];
$update_log = $_GET["log"];
$backup_link_num = $_GET["blink"];
$token = $_GET["token"];

$bot_token = $_ENV['BOT_TOKEN'];
$chat_id = $_ENV['CHAT_ID'];
$Access_Token = $_ENV['ACCESS_TOKEN'];

preg_match('/QQNT\/([a-z0-9]+)\/QQ(\d+\.\d+\.\d+)\.(\d+)_x86/', $link_windows_x86, $matches_x86);
$winodws_x86_md5 = $matches_x86[1];
$windows_version_name = $matches_x86[2];
$windows_version_code = $matches_x86[3];

preg_match('/QQNT\/([a-z0-9]+)\/QQ(\d+\.\d+\.\d+)\.(\d+)_x64/', $link_windows_x64, $matches_x64);
$winodws_x64_md5 = $matches_x64[1];

preg_match('/QQNT\/([a-z0-9]+)\/QQ(\d+\.\d+\.\d+)\.(\d+)_arm64/', $link_windows_arm, $matches_arm);
$winodws_arm_md5 = $matches_arm[1];

preg_match('/QQNT\/([a-z0-9]+)\/QQ_v(\d+\.\d+\.\d+)\.(\d+)/', $link_macos, $matches_macos);
$macos_md5 = $matches_macos[1];
$macos_version_name = $matches_macos[2];
$macos_version_code = $matches_macos[3]; 

preg_match('/QQNT\/([a-z0-9]+)\/linuxqq_(\d+\.\d+\.\d+)-(\d+)/', $link_linux, $matches_linux);
$linux_md5 = $matches_linux[1];
$linux_version_name = $matches_linux[2];
$linux_version_code = $matches_linux[3];

if (!empty($windows_version_name)) {
$update_content = "<b>Windows QQ_NT {$windows_version_name}.{$windows_version_code} &</b>\n";
}
if (!empty($macos_version_name)) {
$update_content .= "<b>MacOS QQ_NT {$macos_version_name}.{$macos_version_code} &</b>\n";
}
if (!empty($linux_version_name)) {
$update_content .= "<b>Linux QQ_NT {$linux_version_name}.{$linux_version_code}</b>\n";
}
$update_content .= "\n<b>官方更新内容：</b>\n<blockquote>{$update_log}</blockquote>\n\n";
$update_content .= "<b>下载：</b>\n";

if (!empty($windows_version_name)) {
$update_content .= "- Windows:\n";
$update_content .= "<blockquote><a href='https://dldir1.qq.com/qqfile/qq/QQNT/{$winodws_x86_md5}/QQ{$windows_version_name}.{$version_code}_x86.exe'>X86</a> | ";
$update_content .= "<a href='https://dldir1.qq.com/qqfile/qq/QQNT/{$winodws_x64_md5}/QQ{$windows_version_name}.{$version_code}_x64.exe'>X64</a> | ";
$update_content .= "<a href='https://dldir1.qq.com/qqfile/qq/QQNT/{$winodws_arm_md5}/QQ{$windows_version_name}.{$version_code}_arm64.exe'>Arm</a></blockquote>\n";
}

if (!empty($macos_version_name)) {
$update_content .= "- MacOS:\n";
$update_content .= "<blockquote><a href='https://dldir1.qq.com/qqfile/qq/QQNT/{$macos_md5}/QQ_v{$macos_version_name}.{$version_code}.dmg'>Dmg</a></blockquote>\n";
}

if (!empty($linux_version_name)) {
$update_content .= "- Linux:\n";
$update_content .= "<blockquote><a href='https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_amd64.deb'>DEB_x64</a> | ";
$update_content .= "<a href='https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_x86_64.rpm'>RPM_x64</a> | ";
$update_content .= "<a href='https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_x86_64.AppImage'>Appimage_x64</a></blockquote>\n";
$update_content .= "<blockquote><a href='https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_arm64.deb'>DEB_Arm</a> | ";
$update_content .= "<a href='https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_aarch64.rpm'>RPM_Arm</a> | ";
$update_content .= "<a href='https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_arm64.AppImage'>Appimage_Arm</a></blockquote>\n";
$update_content .= "<blockquote><a href='https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_loongarch64.deb'>LoongArch</a> | ";
$update_content .= "<a href='https://dldir1.qq.com/qqfile/qq/QQNT/{$linux_md5}/linuxqq_{$linux_version_name}-{$version_code}_mips64el.deb'>Mips</a></blockquote>\n";
}

if ($backup_link_num !== "") {
    $backup_link_num = intval($backup_link_num) ?: 0;
    $update_content .= "\n<b>备份：</b>\n";
    $update_content .= "- Windows:\n";
    $update_content .= "<blockquote><a href='https://t.me/linqiqi_backup/{$backup_link_num}'>X86</a> | ";
    $update_content .= "<a href='https://t.me/linqiqi_backup/" . ++$backup_link_num . "'>X64</a> | ";
    $update_content .= "<a href='https://t.me/linqiqi_backup/" . ++$backup_link_num . "'>Arm</a></blockquote>\n";
    $update_content .= "- MacOS:\n";
    $update_content .= "<blockquote><a href='https://t.me/linqiqi_backup/" . ++$backup_link_num . "'>Dmg</a></blockquote>\n";
    $update_content .= "- Linux:\n";
    $update_content .= "<blockquote><a href='https://t.me/linqiqi_backup/" . ++$backup_link_num . "'>DEB</a> | ";
    $update_content .= "<a href='https://t.me/linqiqi_backup/" . ++$backup_link_num . "'>RPM</a> | ";
    $update_content .= "<a href='https://t.me/linqiqi_backup/" . ++$backup_link_num . "'>Appimage</a> | ";
    $update_content .= "<a href='https://t.me/linqiqi_backup/" . ++$backup_link_num . "'>LoongArch</a> | ";
    $update_content .= "<a href='https://t.me/linqiqi_backup/" . ++$backup_link_num . "'>Mips</a></blockquote>\n";
}

$update_content .= "\nTG@ <a href='https://t.me/qq_updatelog'>QQ/TIM For Update Log</a>\n";
if (!empty($windows_version_name)) {
$update_content .= "#QQ_NT_Windows\n";
}
if (!empty($macos_version_name)) {
$update_content .= "#QQ_NT_MacOS\n";
}
if (!empty($linux_version_name)) {
$update_content .= "#QQ_NT_Linux\n";
}

$file_name = "QQ_Update_Log_{$version_code}.html";
file_put_contents($file_name, $update_content);

header("Content-Disposition: attachment; filename=\"$file_name\"");
header("Content-Type: text/html");
header("Content-Length: " . filesize($file_name));
readfile($file_name);

unlink($file_name);

$send_message_url = "https://api.telegram.org/bot$bot_token/sendMessage";

$post_fields = [
    'chat_id' => $chat_id,
    'text' => $update_content,
    'parse_mode' => 'HTML'
];

if ($Access_Token == "$token") {
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $send_message_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
$response = curl_exec($ch);
curl_close($ch);
}

?>
