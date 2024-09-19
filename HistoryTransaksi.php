<?php
session_start();
include('server/connection.php');

if (!isset($_SESSION['logged_in'])) {
  header('Location: index.php');
  exit;
}

$user_id = $_SESSION['user_id'];

$query = "
    SELECT 
        payments.transaction_id, 
        product.product_name, 
        order_items.product_quantity, 
        product.product_price,
        payments.payment_date
    FROM 
        payments 
    JOIN 
        order_items ON payments.order_id = order_items.order_id 
    JOIN 
        product ON order_items.product_id = product.product_id 
    WHERE 
        payments.user_id = ?
    ORDER BY 
        order_items.order_date DESC";

$stmt = $conn->prepare($query);

if ($stmt) {
  $stmt->bind_param('i', $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
} else {
  echo "Error preparing statement: " . $conn->error;
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recent Orders</title>

  <link rel="stylesheet" href="css/transaksi.css">

</head>

<body>
  <!-- riwayat transaksi start -->
  <div class="transaction-history">
    <h1>Riwayat Transaksi</h1>
    <table>
      <thead>
        <tr>
          <th>ID Transaksi</th>
          <th>Nama Barang</th>
          <th>Quantity</th>
          <th>Total Harga</th>
          <th>Tanggal</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()) : ?>
          <tr>
            <td><?php echo htmlspecialchars($row['transaction_id']); ?></td>
            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
            <td><?php echo htmlspecialchars($row['product_quantity']); ?></td>
            <td><?= $row['product_quantity'] * $row['product_price'] ?></td>
            <td><?php echo date('d M Y', strtotime($row['payment_date'])); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <a href="index.php">
      <button type="button" class="btn-back">
        Kembali
      </button>
    </a>
  </div>
  <!-- riwayat transaksi end -->

  <script src="js/transaksi.js"></script>
</body>

</html>