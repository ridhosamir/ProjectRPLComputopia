<?php
include('server/connection.php');

$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$product_brand = $_POST['product_brand'];
$product_category = $_POST['product_category'];
$product_desc = $_POST['product_desc'];
$product_criteria = $_POST['product_criteria'];
$product_photo = $_FILES['product_photo']['name'];
$product_price = $_POST['product_price'];

$target_dir = "img/products/";
$target_file = $target_dir . basename($_FILES["product_photo"]["name"]);

if (move_uploaded_file($_FILES["product_photo"]["tmp_name"], $target_file)) {
    echo "The file " . htmlspecialchars(basename($_FILES["product_photo"]["name"])) . " has been uploaded.";
} else {
    echo "Sorry, there was an error uploading your file.";
}

$query = "UPDATE product SET 
            product_name = '$product_name', 
            product_brand = '$product_brand', 
            product_category = '$product_category', 
            product_desc = '$product_desc', 
            product_criteria = '$product_criteria', 
            product_photo = '$product_photo', 
            product_price = '$product_price' 
          WHERE product_id = $product_id";

mysqli_query($conn, $query);

header("Location: manageProduct.php");
