<?php
session_start();
include('server/connection.php');

if (isset($_SESSION['logged_in'])) {
}

// if (isset($_SESSION['logged_in_adm'])) {
//   header('Location: adminDashboard.php');
//   exit;
// }

if (isset($_POST['login_btn'])) {
  $login_credential = $_POST['login_credential'];
  $password = $_POST['password'];

  // Check if user is an admin
  $query = "SELECT * FROM admins WHERE admin_name =? AND admin_password =? LIMIT 1";
  $stmt_login = $conn->prepare($query);
  $stmt_login->bind_param('ss', $login_credential, $password);
  $stmt_login->execute();
  $stmt_login->store_result();

  if ($stmt_login->num_rows() == 1) {
    $stmt_login->bind_result($admin_id, $admin_name, $admin_password, $admin_phone, $admin_photo);
    $stmt_login->fetch();
    $_SESSION['admin_id'] = $admin_id;
    $_SESSION['admin_name'] = $admin_name;
    $_SESSION['admin_phone'] = $admin_phone;
    $_SESSION['admin_photo'] = $admin_photo;
    $_SESSION['logged_in_adm'] = true;
    header('Location: adminDashboard.php');
    exit;
  } else {
    // Check if user is a regular user
    $query = "SELECT * FROM users WHERE (user_email =? OR user_phone =?) AND user_password =? LIMIT 1";
    $stmt_login = $conn->prepare($query);
    $stmt_login->bind_param('sss', $login_credential, $login_credential, $password);
    $stmt_login->execute();
    $stmt_login->store_result();

    if ($stmt_login->num_rows() == 1) {
      $stmt_login->bind_result($user_id, $user_name, $user_email, $user_password, $user_address, $user_phone, $user_photo);
      $stmt_login->fetch();
      $_SESSION['user_id'] = $user_id;
      $_SESSION['user_name'] = $user_name;
      $_SESSION['user_email'] = $user_email;
      $_SESSION['user_address'] = $user_address;
      $_SESSION['user_phone'] = $user_phone;
      $_SESSION['user_photo'] = $user_photo;
      $_SESSION['logged_in'] = true;
      header('Location: index.php');
      exit;
    } else {
      echo "<script>alert('Username Atau Password Salah'); window.location.href = 'index.php';</script>";
      exit;
    }
  }
}

if (isset($_GET['logout'])) {
  if (isset($_SESSION['logged_in'])) {
    unset($_SESSION['logged_in']);
    unset($_SESSION['user_email']);
    header('location: index.php');
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Computopia</title>

  <!-- icon -->
  <link rel="icon" href="img/ajazz2.svg" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap" rel="stylesheet" />

  <!-- feather icons -->
  <script src="https://unpkg.com/feather-icons"></script>

  <!-- my style -->
  <link rel="stylesheet" href="css/style.css" />

  <!-- alpineJs -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- app -->
  <script src="src/app.js"></script>
</head>

<body>
  <!-- page loader start -->
  <div class="loader">
    <span class="loader_dot" style="--d: 200ms"></span>
    <span class="loader_dot" style="--d: 400ms"></span>
    <span class="loader_dot" style="--d: 600ms"></span>
    <span class="loader_dot" style="--d: 800ms"></span>
    <span class="loader_dot" style="--d: 1000ms"></span>
  </div>
  <!-- page loader end -->

  <!-- navbar start -->
  <nav class="navbars" x-data>
    <a href="index.php" class="navbars-logo">Compu<span>topia</span> .</a>

    <div class="navbars-nav">
      <a href="index.php">Home</a>
      <a href="about.php">About</a>
      <a href="shop.php">Shop</a>
      <a class="active" href="contact.php">Contact</a>
      <button class="btnLogin-popup" x-show="!isLoggedIn"> Login </button>
    </div>
    
    <div class="navbars-extra">
      <div class="dropdown" id="user-dropdown" x-show="isLoggedIn">
        <a href="#" id="user" class="dropdown-toggle"> <i data-feather="user"></i></a>
        <ul class="dropdown-menu">
          <li><a href="userProfile.php" class="dropdown-item">User Profile</a></li>
          <li><a href="HistoryTransaksi.php" class="dropdown-item">Order History</a></li>
          <li><a href="index.php?logout=1" class="dropdown-item">Logout</a></li>
        </ul>
        <a href="#" id="search-button"> <i data-feather="search"></i></a>
        <a href="shopping_cart.php" id="shopping-cart-button">
          <i data-feather="shopping-cart"></i>
          <span class="quantity-badge" x-show="$store.cart.quantity" x-text="$store.cart.quantity"></span>
        </a>

        <a href="#" id="hamburger-menu"> <i data-feather="menu"></i></a>
      </div>

      <!-- search form start -->
      <div class="search-form">
        <form action="search.php" method="GET">
          <input type="search" id="search-box" name="query" placeholder="Search By Brand Here..." />
          <label for="search-box"><i data-feather="search"></i></label>
        </form>
      </div>
      <!-- search form end -->

  </nav>
  <!-- navbar end -->

  <!-- login pop-up start -->
  <div class="wrapper">
    <span span class="icon-close">
      <i data-feather="x-circle"></i>
    </span>

    <div class="form-box login">
      <h2>Login</h2>
      <form method="post">
        <div class="input-box">
          <span class="icon">
            <i data-feather="user"></i>
          </span>
          <input type="text" name="login_credential" required>
          <label>Username, Email, or Phone Number</label>
        </div>
        <div class="input-box">
          <span class="icon">
            <i data-feather="lock"></i>
          </span>
          <input type="password" name="password" required>
          <label>Password</label>
        </div>
        <div class="remember-forgot">
          <label><input type="checkbox"> Remember me </label>
        </div>
        <button type="submit" class="btnUser" name="login_btn">Login</button>
        <div class="login-register">
          <p>Don't have an account? <a href="#" class="register-link">Register</a></p>
        </div>
      </form>
    </div>

    <div class="form-box register">
      <h2>Register</h2>
      <form method="post" action="registerLog.php">
        <div class="input-box">
          <span class="icon">
            <i data-feather="user"></i>
          </span>
          <input type="text" name="user_name" required>
          <label>Username</label>
        </div>
        <div class="input-box">
          <span class="icon">
            <i data-feather="mail"></i>
          </span>
          <input type="mail" name="user_email" required>
          <label>Email</label>
        </div>
        <div class="input-box">
          <span class="icon">
            <i data-feather="lock"></i>
          </span>
          <input type="password" name="user_password" required>
          <label>Password</label>
        </div>
        <div class="remember-forgot">
          <label><input type="checkbox"> I agree to the terms & conditions </label>
        </div>
        <button type="submit" class="btnRegister">Register</button>
        <div class="login-register">
          <p>Already have an account? <a href="#" class="login-link">Login</a></p>
        </div>
      </form>
    </div>
  </div>
  <!-- login pop-up end -->

    <!-- banner section start -->
    <!-- <section class="banner">
    <div class="banner-image">
      <img src="img/bannerLogo.svg" alt="">
    </div>
  </section> -->
  <!-- banner section end -->

    <!-- <div class="separator"></div> -->

  <!-- contact section start -->
  <section id="contact" class="contact">
    <br><br>

    <h2><span>Our</span> Contact</h2>
    <!-- <p>
      Lorem, ipsum dolor sit amet consectetur adipisicing elit. Blanditiis nam
      nisi distinctio dolores a eum facere tempora quo alias temporibus illum
      minus et, labore veritatis voluptatem voluptas maxime? Maiores, quidem.
    </p> -->

    <div class="row">
      <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126748.56211042157!2d107.64315755000001!3d-6.903449449999993!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6398252477f%3A0x146a1f93d3e815b2!2sBandung%2C%20Kota%20Bandung%2C%20Jawa%20Barat!5e0!3m2!1sid!2sid!4v1714985267671!5m2!1sid!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="map"></iframe> -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.9779949025515!2d107.63230329999999!3d-6.893235299999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e7af6e1a8777%3A0xa59e5b599e8b1ec2!2sJl.%20Sido%20Mulyo%20No.23%2C%20Sukaluyu%2C%20Kec.%20Cibeunying%20Kaler%2C%20Kota%20Bandung%2C%20Jawa%20Barat%2040123!5e0!3m2!1sid!2sid!4v1716819576058!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

      <form action="">
        <div class="input-group">
          <i data-feather="user"></i>
          <h3>Computopia</h3>
        </div>
        <div class="input-group">
          <i data-feather="mail"></i>
          <h3>ilyaskalamullah@computopia.com</h3>
        </div>
        <div class="input-group">
          <i data-feather="phone"></i>
          <h3>082130492500</h3>
        </div>
      </form>
    </div>
  </section>
  <!-- contact section end -->

  <!-- footer start -->
  <footer>
    <div class="socials">
      <a href="#"><i data-feather="instagram"></i></a>
      <a href="#"><i data-feather="twitter"></i></a>
      <a href="#"><i data-feather="facebook"></i></a>
    </div>

    <div class="links">
      <a href="index.php">Home</a>
      <a href="about.php">About Us</a>
      <a href="shop.php">Shop</a>
      <a class="active" href="contact.php">Contact</a>
    </div>

    <div class="credits">
      <p>Created by <a href="">Kelompok Computopia</a>. | &copy; 2024.</p>
    </div>
  </footer>
  <!-- footer end -->

  <!-- feather icons -->
  <script>
    feather.replace();
  </script>

  <!-- my javasript -->
  <script src="js/script.js"></script>
  <script>
    let isLoggedIn = <?php echo isset($_SESSION['logged_in']) ? 'true' : 'false'; ?>;
  </script>
</body>

</html>