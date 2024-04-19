<!DOCTYPE html>
<html lang="cn">

<!-- Head -->
<?php include('head.php'); ?>

<style>
  /* Set the page height to 100vh (viewport height) */
  body,
  html {
    height: 100%;
    margin: 0;
    padding: 0;
  }

  /* Set background color to white */
  body {
    background-color: #fff;
  }

  /* Center the content on the page */
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

  /* Footer styling */
  footer {
    background-color: #f0f0f0;
    /* Light gray background color */
    padding: 10px 0;
    text-align: center;
    position: absolute;
    bottom: 0;
    width: 100%;
  }
</style>

<body>
  <!-- Logo -->
  <div class="error-container">
    <img class="error-logo" src="https://wxies.cn/logo.png" alt="Logo">
    <div class="error-message">
      <p>WXies API</p>
      <p>将在 <span id="countdown">5</span> 秒后自动跳转到API文档</p>
      <p>如果没有自动跳转，请点击<a href="https://doc.wxies.cn" class="home-link">这里</a>。</p>
    </div>
  </div>
  <!-- Footer -->
  <footer>
    <?php include 'footer.php'; ?>
  </footer>

  <script>
    // JavaScript倒计时
    var countdown = 5;
    var countdownElement = document.getElementById('countdown');

    function updateCountdown() {
      countdownElement.innerHTML = countdown;
      countdown--;

      if (countdown < 0) {
        window.location.href = 'https://doc.wxies.cn';
      } else {
        setTimeout(updateCountdown, 1000);
      }
    }

    // 页面加载后启动倒计时
    document.addEventListener('DOMContentLoaded', function () {
      updateCountdown();
    });
  </script>
</body>

</html>
