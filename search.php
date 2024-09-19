<?php
include('server/connection.php');

session_start();

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

if (isset($_GET['query'])) {
  $query = $_GET['query'];
  $query = "%{$query}%";
  
  $sql = "SELECT * FROM product WHERE product_brand LIKE ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $query);
  $stmt->execute();
  $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Search Results - Computopia</title>
  <link rel="icon" href="img/ajazz2.svg" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
  <script src="https://unpkg.com/feather-icons"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
  <!-- navbar start -->
  <nav class="navbars" x-data>
    <a href="index.php" class="navbars-logo">Compu<span>topia</span> .</a>
    <div class="navbars-nav">
      <a href="index.php">Home</a>
      <a href="about.php">About</a>
      <a class="active" href="shop.php">Shop</a>
      <a href="contact.php">Contact</a>
      <button class="btnLogin-popup" x-show="!isLoggedIn"> Login </button>
    </div>
    <div class="navbars-extra">
      <!-- <a href="#" id="search-button"> <i data-feather="search"></i></a>
      <a href="#" id="shopping-cart-button">
        <i data-feather="shopping-cart"></i>
        <span class="quantity-badge" x-show="$store.cart.quantity" x-text="$store.cart.quantity"></span>
      </a> -->
      <!-- <div class="dropdown" id="user-dropdown" x-show="isLoggedIn">
        <a href="#" id="user" class="dropdown-toggle"> <i data-feather="user"></i></a>
        <ul class="dropdown-menu">
          <li><a href="userProfile.php" class="dropdown-item">User Profile</a></li>
          <li><a href="HistoryTransaksi.php" class="dropdown-item">Order History</a></li>
          <li><a href="index.php?logout=1" class="dropdown-item">Logout</a></li>
        </ul>
      </div> -->
      <a href="#" id="hamburger-menu"> <i data-feather="menu"></i></a>
    </div>
    <div class="search-form">
      <form action="search.php" method="GET">
        <input type="search" id="search-box" name="query" placeholder="Search By Brand Here..." />
        <label for="search-box"><i data-feather="search"></i></label>
      </form>
    </div>
  </nav>
  <!-- navbar end -->

  <!-- search results section start -->
  <section id="search-results" class="shop">
    <br>
    <br>
    <br>
    <h2>Search Results</h2>
    <br>
    <div class="row">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="shop-card">
            <div class="product-image">
              <img src="img/products/<?php echo $row['product_photo']; ?>" alt="<?php echo $row['product_name']; ?>" class="product-img">
            </div>
            <div class="shop-content">
              <h3><?php echo $row['product_name']; ?></h3>
              <p><?php echo $row['product_desc']; ?></p>
              <div class="product-price">IDR <?php echo number_format($row['product_price'], 0, ',', '.'); ?></div>
              <div class="product-icons">
                <form id="addToCartForm" action="shopping_cart.php" method="post" style="display: none;">
                  <input type="hidden" id="productId" name="product_id" value="">
                </form>
                <a href="#" onclick="addToCart(<?php echo $row['product_id']; ?>)">
                  <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <use href="img/feather-sprite.svg#shopping-cart" />
                  </svg>
                </a>
                
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No products found.</p>
      <?php endif; ?>
    </div>
  </section>
  <!-- search results section end -->

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
      <a class="active" href="shop.php">Shop</a>
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
        <h3 id="modal-product-name"></h3>
        <p id="modal-product-description"></p>
        <div id="modal-product-price" class="product-price"></div>
        <button id="modal-add-to-cart" class="add-to-cart">
          <i data-feather="shopping-cart"></i> 
          <span>Add to Cart</span>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- modal box item detail end -->

  <script>
    feather.replace();
  </script> 
  <script src="js/script.js"></script>
  <script>
    function addToCart(productId) {
      document.getElementById('productId').value = productId;
      document.getElementById('addToCartForm').submit();
    }
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const itemDetailButtons = document.querySelectorAll('.item-detail-button');
      const modal = document.getElementById('item-detail-modal');
      const modalProductImage = document.getElementById('modal-product-image');
      const modalProductName = document.getElementById('modal-product-name');
      const modalProductDescription = document.getElementById('modal-product-description');
      const modalProductPrice = document.getElementById('modal-product-price');
      const modalAddToCartButton = document.getElementById('modal-add-to-cart');

      itemDetailButtons.forEach(button => {
        button.addEventListener('click', function(event) {
          event.preventDefault();
          const productId = button.dataset.productId;

          fetch('get_product_detail.php?product_id=' + productId)
            .then(response => response.json())
            .then(product => {
              modalProductName.innerText = product.product_name;
              modalProductDescription.innerText = product.product_desc;
              modalProductPrice.innerText = 'IDR ' + formatCurrency(product.product_price);
              modalProductImage.src = 'img/products/' + product.product_photo;

              modal.classList.add('show');
            })
            .catch(error => {
              console.error('Error:', error);
            });
        });
      });

      modal.addEventListener('click', function(event) {
        if (event.target === modal || event.target.closest('.close-icon')) {
          modal.classList.remove('show');
        }
      });

      function formatCurrency(amount) {
        return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
      }
    });
  </script>
</body>
</html>
