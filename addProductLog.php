<?php
include('server/connection.php');

$product_name = $_POST['product_name'];
$product_brand = $_POST['product_brand'];
$product_category = $_POST['product_category'];
$product_desc = $_POST['product_desc'];
$product_criteria = $_POST['product_criteria'];
$product_price = $_POST['product_price'];

// Get the uploaded file information
$product_photo = $_FILES['product_photo']['name'];
$product_photo_temp = $_FILES['product_photo']['tmp_name'];

// Move the uploaded file to the target directory
move_uploaded_file($product_photo_temp, 'img/products/' . $product_photo);

// Insert the new product into the database
$query = "INSERT INTO product (product_name, product_brand, product_category, product_desc, product_criteria, product_photo, product_price) VALUES ('$product_name', '$product_brand', '$product_category', '$product_desc', '$product_criteria', '$product_photo', '$product_price')";
$result = mysqli_query($conn, $query);

// Close the database connection
mysqli_close($conn);

// Redirect back to the admin dashboard
header('Location: manageProduct.php');
exit;

?>
