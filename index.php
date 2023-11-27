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

  .countdown {
    font-size: 18px;
    margin-top: 20px;
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
    <img class="error-logo" src="https://www.wxies.cn/logo.png" alt="Logo">
    <div class="error-message">
      <p>WXies API</p>
      <p id="countdown-message">5秒后将自动跳转至API文档。</p>
      <div class="countdown" id="countdown">5</div>
    </div>
  </div>
  <!-- Footer -->
  <footer>
    <?php include 'footer.php'; ?>
  </footer>

  <!-- JavaScript for countdown and redirection after 5 seconds -->
  <script>
    var countdown = 5;

    function updateCountdown() {
      document.getElementById('countdown').innerText = countdown;
    }

    function updateCountdownMessage() {
      document.getElementById('countdown-message').innerText = countdown + '秒后将自动跳转至API文档。';
    }

    function redirectAfterCountdown() {
      window.location.href = 'https://doc.wxies.cn'; // Replace with your desired URL
    }

    setInterval(function () {
      if (countdown > 0) {
        countdown--;
        updateCountdown();
        updateCountdownMessage();
      } else {
        redirectAfterCountdown();
      }
    }, 1000); // Update every 1000 milliseconds (1 second)
  </script>
</body>
</html>
