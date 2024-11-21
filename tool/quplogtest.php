$pattern = '/https:\/\/dldir1\.qq\.com\/qqfile\/qq\/QQNT\/([a-f0-9]{8})\/QQ(?:([v0-9\.]+))?_?([a-zA-Z0-9\-\.]+)(?:_([a-zA-Z0-9]+))?\.(exe|dmg|rpm|deb|AppImage)/';

$test_urls = [
    "https://dldir1.qq.com/qqfile/qq/QQNT/09d7ff8c/QQ9.9.16.29804_x86.exe", // Windows x86
    "https://dldir1.qq.com/qqfile/qq/QQNT/f50ea76d/QQ9.9.16.29804_x64.exe", // Windows x64
    "https://dldir1.qq.com/qqfile/qq/QQNT/1e656014/QQ9.9.16.29804_arm64.exe", // Windows ARM
    "https://dldir1.qq.com/qqfile/qq/QQNT/e4feec69/QQ_v6.9.61.29804.dmg",     // Mac
    "https://dldir1.qq.com/qqfile/qq/QQNT/6aaeb71d/linuxqq_3.2.13-29804_aarch64.rpm" // Linux
];

foreach ($test_urls as $url) {
    if (preg_match($pattern, $url, $matches)) {
        $md5 = $matches[1];
        $version_name = isset($matches[2]) ? $matches[2] : $matches[3];
        $version_code = substr($matches[3], -5, 5); // 提取version_code
        $platform = isset($matches[4]) ? $matches[4] : ''; // 如果存在平台信息
        echo "MD5: $md5, Version Name: $version_name, Version Code: $version_code, Platform: $platform\n";
    }
}
