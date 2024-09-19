<?php
include 'server/connection.php'; // Include your database connection

// Get the JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$item_id = $data['item_id'];
$order_status = $data['status_class'];

// Update the order status in the database
$sql = "UPDATE orders SET status_class = ? WHERE item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $order_status, $item_id);

$response = [];
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>