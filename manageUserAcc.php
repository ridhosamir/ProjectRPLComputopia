<?php
include('server/connection.php');
session_start();

if (!isset($_SESSION['logged_in_adm'])) {
    header('location: index.php');
    exit;
}

$admin_photo = $_SESSION['admin_photo'];

// Eksekusi query pertama
$query = "SELECT 
             (SELECT COUNT(*) FROM order_items) as total_orders,
             (SELECT COUNT(*) FROM users) as total_users,
             (SELECT COUNT(DISTINCT user_id) FROM order_items) as total_customer,
             (SELECT SUM(order_items.product_quantity * product.product_price) FROM order_items JOIN product ON order_items.product_id = product.product_id) as total_earnings";
$result = mysqli_query($conn, $query);

// Ambil hasil query pertama
$row = mysqli_fetch_assoc($result);
$total_orders = $row['total_orders'];
$total_users = $row['total_users'];
$total_customer = $row['total_customer'];
$total_earnings = $row['total_earnings'];
$total_earnings_IDR = number_format($total_earnings, 0, ',', '.');

// Query kedua
$query_users = "SELECT * FROM users";
$users_result = mysqli_query($conn, $query_users);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- my style -->
    <link rel="stylesheet" href="css/admin.css">
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

    <!-- navigation -->
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="planet-outline"></ion-icon>
                            <!-- <ion-icon name="logo-electron"></ion-icon>  -->
                            <!-- <ion-icon name="logo-ionitron"></ion-icon> -->
                        </span>
                        <span class="tittle">Computopia</span>
                    </a>
                </li>

                <li>
                    <a href="adminDashboard.php">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="tittle">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="manageUserAcc.php">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="tittle">Users</span>
                    </a>
                </li>

                <li>
                    <a href="manageProduct.php">
                        <span class="icon">
                            <ion-icon name="laptop-outline"></ion-icon>
                        </span>
                        <span class="tittle">Products</span>
                    </a>
                </li>

                <li>
                    <a href="adminTransaction.php">
                        <span class="icon">
                            <ion-icon name="cash-outline"></ion-icon>
                        </span>
                        <span class="tittle">Transactions</span>
                    </a>
                </li>

                <!-- <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="lock-closed-outline"></ion-icon>
                        </span>
                        <span class="tittle">Re-Password</span>
                    </a>
                </li> -->

                <li>
                    <a href="adminDashboard.php?logout=1">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="tittle">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- main -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

                <!-- <div class="search">
                    <label>
                        <input type="text" placeholder="Search here...">
                        <ion-icon name="search-outline"></ion-icon>
                    </label>
                </div> -->

                <div class="user">
                    <a href="adminProfile.php">
                        <img src='img/admin/<?php echo $admin_photo; ?>' alt=''>
                    </a>
                </div>
            </div>
            <!-- cards -->
            <div class="cardBox">
                <div class="card">
                    <div>
                        <div class="numbers"><?php echo $total_customer; ?></div>
                        <div class="cardName">Customers</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="people-outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="numbers"><?php echo $total_orders; ?></div>
                        <div class="cardName">Sales</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="cart-outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="numbers"><?php echo $total_users; ?></div>
                        <div class="cardName">Users</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="person-outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="numbers"><?php echo $total_earnings_IDR; ?></div>
                        <div class="cardName">Earning</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="wallet-outline"></ion-icon>
                    </div>
                </div>
            </div>

            <!-- tabel user -->
            <div class="detail">
                <div class="manage">
                <div class="cardHeader" style="justify-content: center;">
                        <ion-icon name="people"></ion-icon>
                        <h2 style="font-size: 1.7rem;">Users</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>Name</td>
                                <td>Email</td>
                                <td>Address</td>
                                <td>Phone</td>
                                <td>Photo</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($users_result)) {
                            ?>
                                <tr>
                                    <td><?= $row['user_id'] ?></td>
                                    <td><?= $row['user_name'] ?></td>
                                    <td><?= $row['user_email'] ?></td>
                                    <td><?= $row['user_address'] ?></td>
                                    <td><?= $row['user_phone'] ?></td>
                                    <td><img src="img/user/<?= $row['user_photo'] ?>" alt="User Profile"></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- my js -->
            <script src="js/admin.js"></script>

            <!-- ion icon -->
            <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
            <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>