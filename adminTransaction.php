<?php
include ('server/connection.php');
session_start();

if (!isset($_SESSION['logged_in_adm'])) {
    header('location: index.php');
    exit;
}

$admin_photo = $_SESSION['admin_photo'];

$query_transaksi = "SELECT 
                    payments.transaction_id, 
                    order_items.product_name, 
                    users.user_name, 
                    order_items.product_quantity, 
                    order_items.product_price, 
                    order_items.order_date
                    FROM payments
                    JOIN order_items ON payments.order_id = order_items.order_id
                    JOIN users ON order_items.user_id = users.user_id";

$result_transaksi = mysqli_query($conn, $query_transaksi);

$query = "SELECT 
        (SELECT COUNT(*) FROM order_items) as total_orders,
        (SELECT COUNT(*) FROM users) as total_users,
        (SELECT COUNT(DISTINCT user_id) FROM order_items) as total_customer,
        (SELECT SUM(order_items.product_quantity * product.product_price) FROM order_items JOIN product ON order_items.product_id = product.product_id) as total_earnings";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_orders = $row['total_orders'];
    $total_users = $row['total_users'];
    $total_customer = $row['total_customer'];
    $total_earnings = $row['total_earnings'];
    $total_earnings_IDR = number_format($total_earnings, 0, ',', '.');
} else {
    echo "Error: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- my style -->
    <link rel="stylesheet" href="css/admin.css">
    </style>
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
                    <a href="adminDashboard.php">
                        <span class="icon">
                            <ion-icon name="planet-outline"></ion-icon>
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
                    <a href="rePassword.php">
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

            <!--table-->
            <div class="detail">
                <div class="manage">
                    <div class="cardHeader" style="justify-content: center;">
                        <ion-icon name="cash"></ion-icon>
                        <h2 style="font-size: 1.7rem;">Transactions</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <td>Transaction ID</td>
                                <td>Nama Produk</td>
                                <td>Nama Pembeli</td>
                                <td>Quantity</td>
                                <td>Total Harga</td>
                                <td>Date</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($result_transaksi)) {
                                ?>
                                <tr>
                                    <td><?= $row['transaction_id'] ?></td>
                                    <td><?= $row['product_name'] ?></td>
                                    <td><?= $row['user_name'] ?></td>
                                    <td><?= $row['product_quantity'] ?></td>
                                    <!-- <td><img src="img/products/<?= $row['product_photo'] ?>" alt="" width="50"></td> -->
                                    <td><?= $row['product_quantity'] * $row['product_price'] ?></td>
                                    <td><?php echo date('d M Y', strtotime($row['order_date'])); ?></td>
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