<?php
// Start the session
session_start();

// Check if the user has submitted the form to delete a product
if (isset($_GET['id'])) {
    // Get the value of the id variable
    $product_id = $_GET['id'];

    // Connect to the database
    include('server/connection.php');

    // Delete the product from the database
    $stmt = $conn->prepare("DELETE FROM product WHERE product_id =?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    // Close the database connection
    mysqli_close($conn);

    // Redirect the user back to the manageProduct.php page
    header('Location: manageProduct.php');
    exit;
}
?>