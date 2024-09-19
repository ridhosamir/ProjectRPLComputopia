<?php
include('server/connection.php');

if (isset($_GET['product_id'])) {
  $productId = filter_var($_GET['product_id'], FILTER_VALIDATE_INT);
  if ($productId === false || $productId <= 0) {
    echo json_encode(['error' => 'Product ID Salah']);
    exit;
  }

  $query = "SELECT product_id, product_name, product_desc, product_price, product_photo FROM product WHERE product_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('i', $productId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    echo json_encode($product);
  } else {
    echo json_encode(['error' => 'Product Tidak Ditemukan']);
  }
} else {
  echo json_encode(['error' => 'Product ID Tidak Ada!']);
}
?>
