<?php
session_start();
require 'server/connection.php';

if (!isset($_GET['order_id'])) {
    echo "<script>alert('Beli Dulu Gan!'); window.location.href = 'index.php';</script>";
    exit;
}

$order_id = $_GET['order_id'];

// Retrieve order information from the database
$query = "SELECT orders.order_id, product.product_name, order_items.product_quantity, product.product_price
          FROM orders 
          INNER JOIN order_items ON orders.order_id = order_items.order_id 
          INNER JOIN product ON order_items.product_id = product.product_id 
          WHERE orders.order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Invalid order. <a href='shopping_cart.php'>Go back to shopping cart</a>";
    exit();
}

// Initialize total price variable
$total_price = 0;

// Start HTML output
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Berhasil</title>
    <link rel="stylesheet" href="css/invoice.css" />
</head>

<body>
<div class="container">
    <h1>INVOICE</h1>
    <p>Order ID: <?php echo htmlspecialchars($order_id); ?></p>
    <br>
    <br>
    <table>
        <tbody>
            <?php 
            $total_price = 0; 
            while ($row = $result->fetch_assoc()) : 
                $product_total = $row['product_quantity'] * $row['product_price'];
                $total_price += $product_total;
            ?>
                <tr>
                    <td colspan="2"><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td colspan="2">Rp.<?php echo number_format($row['product_price'], 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td> x <?php echo htmlspecialchars($row['product_quantity']); ?></td>
                </tr>

            <?php endwhile; ?>
            <tr>
                    <td colspan="2">Biaya Pengiriman : </td>
                    <td colspan="2"><?php echo isset($_SESSION['shipping_cost']) ? 'Rp.' . number_format($_SESSION['shipping_cost'], 0, ',', '.') : 'Not Available'; ?></td>
                </tr>
                <tr>
                    <td colspan="2">Total Pembayaran : </td>
                    <td>Rp.<?php echo number_format($total_price + $_SESSION['shipping_cost'], 0, ',', '.'); ?></td>
                </tr>
        </tbody>
    </table>
    <p style="font-weight: bold;"><?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
    <br>
    <p style="font-weight: bold;"><?php echo htmlspecialchars($_SESSION['user_address']); ?></p>
    <br>
    <p>Terimakasih Telah Berbelanja Di Toko Kami :)</p>
    <br>
    <a href="index.php">Back to Home</a>
</div>
</body>

</html>