<!DOCTYPE html>
<html lang="zh-CN">

<!-- Head -->
<head>
    <meta charset="utf-8" />
    <title>唯蟹 Team - We Quest On and On</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="We Quest On and On">
    <meta name="keywords" content="唯蟹Team, Xies' Group, Blog233, HoratioWeb, lujing, jack">
    <!-- favicon -->
    <link rel="shortcut icon" href="//wxies.cn/favicon.ico">
    <style>
      body,
      html {
        height: 100%;
        margin: 0;
        padding: 0;
      }

      body {
        background-color: #fff;
      }

      .error-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        height: 100vh;
        text-align: center;
      }

      .error-logo {
        max-width: 200px;
        margin-bottom: 20px;
      }

      .error-message {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
      }

      .home-link {
        color: #007bff;
        text-decoration: none;
      }
    </style>
</head>

<body>
  <!-- Logo -->
  <div class="error-container">
    <img class="error-logo" src="//wxies.cn/favicon.png" alt="Logo">
    <div class="error-message">
      <p>WXies API</p>
      <p>将在 <span id="countdown">5</span> 秒后自动跳转到API文档</p>
      <p>如果没有自动跳转，请点击<a href="https://doc.horatio.cn" class="home-link">这里</a>。</p>
    </div>
  </div>

  <script>
    const countdownElement = document.getElementById('countdown');
    let countdown = 5;

    function updateCountdown() {
      countdownElement.textContent = countdown;
      countdown--;

      if (countdown < 0) {
        window.location.href = 'https://doc.horatio.cn';
      } else {
        setTimeout(updateCountdown, 1000);
      }
    }

    document.addEventListener('DOMContentLoaded', updateCountdown);
  </script>
</body>

</html>
