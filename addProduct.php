<?php

// if (!isset($_SESSION['logged_in_adm'])) {
//     header('location: addProduct.php');
//     exit;
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<link rel="stylesheet" href="css/addProd.css">

<body>
    <form action="addProductLog.php" method="post" enctype="multipart/form-data">
        <h3>Add Product</h3>
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required>

        <label for="product_brand">Product Brand:</label>
        <input type="text" id="product_brand" name="product_brand" required>

        <label for="product_category">Product Category:</label>
        <input type="text" id="product_category" name="product_category" required>

        <label for="product_desc">Product Description:</label>
        <textarea id="product_desc" name="product_desc" required></textarea>

        <label for="product_criteria">Product Criteria:</label>
        <input type="text" id="product_criteria" name="product_criteria">

        <label for="product_photo">Product Photo:</label>
        <input type="file" id="product_photo" name="product_photo" required>

        <label for="product_price">Product Price:</label>
        <input type="number" id="product_price" name="product_price" step="0.01" required>

        <button type="submit" name="add_product">Add Product</button>
        <a href="manageProduct.php"><button type="button">Kembali</button></a>
    </form>
</body>

</html>