<?php
session_start();
include ('server/connection.php');

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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
    rel="stylesheet" />

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
      <a class="active" href="index.php">Home</a>
      <a href="about.php">About</a>
      <a href="shop.php">Shop</a>
      <a href="contact.php">Contact</a>
      <button class="btnLogin-popup" x-show="!isLoggedIn"> Login </button>
    </div>

    <div class="navbars-extra">
      <div class="dropdown" id="user-dropdown" x-show="isLoggedIn">
        <a href="#" id="user" class="dropdown-toggle"> <i data-feather="user"></i></a>
        <ul class="dropdown-menu">
          <li>
            <a href="userProfile.php" class="dropdown-item"> User Profile</a>
          </li>
          <li>
            <a href="HistoryTransaksi.php" class="dropdown-item">Order History</a>
          </li>
          <li>
            <a href="index.php?logout=1" class="dropdown-item">Logout</a>
          </li>
        </ul>
        <a href="#" id="search-button"> <i data-feather="search"></i></a>
        <a href="shopping_cart.php" id="shopping-cart-button">
          <i data-feather="shopping-cart"></i>
          <a href="#" id="hamburger-menu"> <i data-feather="menu"></i></a>
      </div>

      <!-- search form start -->
      <div class="search-form">
        <form action="search.php" method="GET">
          <input type="search" id="search-box" name="query" placeholder="Search By Brand Here..." />
          <label for="search-box"><i data-feather="search"></i></label>
        </form>
      </div>

  </nav>
  <!-- navbar end -->
  <!-- login pop-up start -->
  <div class="wrapper" id="wrapper">
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
        <button type="submit" class="btnRegister">Register</button>
        <div class="login-register">
          <p>Already have an account? <a href="#" class="login-link">Login</a></p>
        </div>
      </form>
    </div>
  </div>
  <!-- login pop-up end -->

  <!-- break space start -->
  <div class="space">
    <div class="space-break">
    </div>
  </div>
  <!-- break space end -->

  <!-- logo slide star -->
  <div class="logos">
    <div class="logos-slide">
      <img src="img/logo-slider/ajazz.png">
      <img src="img/logo-slider/hyperx.png">
      <img src="img/logo-slider/logitech.png">
    </div>
  </div>
  <!-- logo slide end -->

  <!-- slider start -->
  <section class="slider-container">
    <div class="slider-item">
      <img src="img/index/ajazz3.svg" alt="">
      <div class="slider-content">
        <a class="slider-action" href="shop.php">Buy Now
          <i data-feather="arrow-down-right"></i>
        </a>
      </div>
    </div>
    <div class="slider-item">
      <img src="img/index/hyperx.svg" alt="">
      <div class="slider-content2">
        <a class="slider-action" href="shop.php">Buy Now</a>
      </div>
    </div>
    <div class="slider-item">
      <img src="img/index/Logitech.svg" alt="">
      <div class="slider-content3">
        <a class="slider-action" href="shop.php">Buy Now</a>
      </div>
    </div>
  </section>
  <!-- slider end -->


  <!-- our category section start -->
  <section class="gallery">
    <h2>Product Category</h2>
    <div class="content-container">
      <div class="content-left">
        <img src="img/products/15.png" alt="">
        <a href="shop.php?category=MousePad" class="see">Mousepad
          <i data-feather="arrow-down-right"></i>
        </a>
      </div>

      <div class="content-center">
        <img src="img/products/10.png" alt="">
        <a href="shop.php?category=Headset" class="see">Headset
          <i data-feather="arrow-down-right"></i>
        </a>
      </div>

      <div class="content-center">
        <img src="img/products/6.png" alt="">
        <a href="shop.php?category=Keyboard" class="see">Keyboard
          <i data-feather="arrow-down-right"></i>
        </a>
      </div>

      <div class="content-right">
        <img src="img/products/1.png" alt="">
        <a href="shop.php?category=Mouse" class="see">Mouse
          <i data-feather="arrow-down-right"></i>
        </a>
      </div>
    </div>
  </section>
  <!-- our category section end -->


  <!-- products section start -->
  <section id="products" class="products" x-data="products">
    <h2>Our Recommended Products</h2>

    <div class="row">
      <?php
      // Membuat Query untuk menemukan product_name
      $orderItemsQuery = "
    SELECT product_name, COUNT(product_name) as count 
    FROM order_items 
    GROUP BY product_name
    ORDER BY count DESC
    LIMIT 3
";
      $orderItemsResult = $conn->query($orderItemsQuery);

      $productNames = [];
      while ($row = $orderItemsResult->fetch_assoc()) {
        $productNames[] = $row['product_name'];
      }

      $productNamesString = implode(',', array_fill(0, count($productNames), '?'));

      // Membuat Query untuk mendapatkan order_id dari product_name yang sesuai
      $orderIdQuery = "
    SELECT DISTINCT order_id 
    FROM order_items 
    WHERE product_name IN ($productNamesString)
";
      $stmt = $conn->prepare($orderIdQuery);
      $types = str_repeat('s', count($productNames));
      $params = array_merge([$types], $productNames);
      $stmt->bind_param(...$params);
      $stmt->execute();
      $orderIdResult = $stmt->get_result();

      $orderIds = [];
      while ($row = $orderIdResult->fetch_assoc()) {
        $orderIds[] = $row['order_id'];
      }

      if (!empty($orderIds)) {
        $orderIdsString = implode(',', array_fill(0, count($orderIds), '?'));

        // Membuat Query untuk mendapatkan produk berdasarkan order_id yang ada pada tabel payments
        $productQuery = "
        SELECT DISTINCT p.* 
        FROM product p
        JOIN order_items oi ON p.product_name = oi.product_name
        JOIN payments pay ON oi.order_id = pay.order_id
        WHERE oi.order_id IN ($orderIdsString)
    ";
        if (isset($_GET['category'])) {
          $category = $_GET['category'];
          $productQuery .= " AND p.product_category = ?";
        }
        $productQuery .= " LIMIT 4"; // Batasi hanya 4 data yang ditampilkan
      
        $stmt = $conn->prepare($productQuery);
        $types = str_repeat('i', count($orderIds));
        $params = array_merge([$types], $orderIds);
        if (isset($category)) {
          $params[0] .= 's';
          $params[] = $category;
        }
        $stmt->bind_param(...$params);
        $stmt->execute();
        $productResult = $stmt->get_result();

        while ($row = $productResult->fetch_assoc()) {
          ?>
          <div class="product-card">
            <div class="product-image">
              <img src="img/products/<?php echo $row['product_photo']; ?>" alt="<?php echo $row['product_name']; ?>"
                class="product-img">
            </div>
            <div class="shop-content">
              <h3><?php echo $row['product_name']; ?></h3>
              <!-- <p><?php echo $row['product_desc']; ?></p> -->
              <div class="product-price">IDR <?php echo number_format($row['product_price'], 0, ',', '.'); ?></div>
              <div class="product-icons">
                <form id="addToCartForm" action="shopping_cart.php" method="post" style="display: none;">
                  <input type="hidden" id="productId" name="product_id" value="">
                </form>

                <a href="#" onclick="addToCart(<?php echo $row['product_id']; ?>)">
                  <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <use href="img/feather-sprite.svg#shopping-cart" />
                  </svg>
                </a>
                <a href="#" class="item-detail-button" data-product-id="<?php echo $row['product_id']; ?>">
                  <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <use href="img/feather-sprite.svg#file-text" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
          <?php
        }
      }
      ?>

    </div>

  </section>
  <!-- products section end -->

  <!-- ads start -->
  <div class="ads">
    <img src="img/index/random.svg" alt="">
  </div>
  <!-- ads end -->

  <!-- footer start -->
  <footer>
    <div class="socials">
      <a href="#"><i data-feather="instagram"></i></a>
      <a href="#"><i data-feather="twitter"></i></a>
      <a href="#"><i data-feather="facebook"></i></a>
    </div>

    <div class="links">
      <a class="active" href="index.php">Home</a>
      <a href="about.php">About Us</a>
      <a href="shop.php">Shop</a>
      <a href="contact.php">Contact</a>
    </div>

    <div class="credits">
      <p>Created by <a href="">Kelompok Computopia</a>. | &copy; 2024.</p>
    </div>
  </footer>
  <!-- footer end -->

  <!-- modal box item detail start -->
  <div class="modal" id="item-detail-modal">
    <div class="modal-container">
      <a href="#" class="close-icon"><i data-feather="x-circle"></i></a>
      <div class="modal-content">
        <img id="modal-product-image" src="" alt="" />
        <div class="product-content">
          <br>
          <h3 id="modal-product-name"></h3>
          <br>
          <p id="modal-product-description"></p>
          <br>
          <div id="modal-product-price" class="product-price"></div>
          <br>
          <button id="modal-add-to-cart" class="add-to-cart">
            <span>Add to Cart</span>
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- modal box item detail end -->


  <!-- feather icons -->
  <script>
    feather.replace();
  </script>

  <!-- my javasript -->
  <script src="js/script.js"></script>

  <script>
    let isLoggedIn = <?php echo isset($_SESSION['logged_in']) ? 'true' : 'false'; ?>;
  </script>

  <script>
    function addToCart(productId) {
      document.getElementById('productId').value = productId;
      document.getElementById('addToCartForm').submit();
    }
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const itemDetailButtons = document.querySelectorAll('.item-detail-button');
      const modal = document.getElementById('item-detail-modal');
      const modalProductImage = document.getElementById('modal-product-image');
      const modalProductName = document.getElementById('modal-product-name');
      const modalProductDescription = document.getElementById('modal-product-description');
      const modalProductPrice = document.getElementById('modal-product-price');
      const modalAddToCartButton = document.getElementById('modal-add-to-cart');

      itemDetailButtons.forEach(button => {
        button.addEventListener('click', function (event) {
          event.preventDefault();
          const productId = button.dataset.productId;

          fetch('get_product_detail.php?product_id=' + productId)
            .then(response => response.json())
            .then(product => {

              modalProductName.innerText = product.product_name;
              modalProductDescription.innerText = product.product_desc;
              modalProductPrice.innerText = 'IDR ' + formatCurrency(product.product_price);
              modalProductImage.src = 'img/products/' + product.product_photo;
              modalAddToCartButton.addEventListener('click', function () {

                addToCart(product.product_id);
              });

              modal.classList.add('show');
            })
            .catch(error => {
              console.error('Error:', error);
            });
        });
      });

      modal.addEventListener('click', function (event) {
        if (event.target === modal || event.target.closest('.close-icon')) {
          modal.classList.remove('show');
        }
      });

      function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
          style: 'currency',
          currency: 'IDR'
        }).format(amount);
      }
    });
  </script>

</body>

</html>