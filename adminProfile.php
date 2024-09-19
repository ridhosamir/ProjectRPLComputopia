<?php
include('server/connection.php');
session_start();

if (!isset($_SESSION['logged_in_adm'])) {
    header('location: index.php');
    exit;
}

$admin_id = $_SESSION['admin_id'];
$admin_name = $_SESSION['admin_name'];
$admin_phone = $_SESSION['admin_phone'];
$admin_photo = $_SESSION['admin_photo'];

if (isset($_POST['btn'])) {
    $admin_name = $_POST['admin_name'];
    $admin_phone = $_POST['admin_phone'];

    // Process photo upload if a new photo is uploaded
    if ($_FILES['admin_photo']['name']) {
        $photoName = $_FILES['admin_photo']['name'];
        $photoTmp = $_FILES['admin_photo']['tmp_name'];
        $photoPath = "img/admin/$photoName";
        move_uploaded_file($photoTmp, $photoPath);

        // Update photo path in database
        $query = "UPDATE admins SET admin_photo = '$photoName' WHERE admin_id = $admin_id";
        mysqli_query($conn, $query);

        // Update session photo
        $_SESSION['admin_photo'] = $photoName;
    }

    // Update admin details in database
    $query = "UPDATE admins SET admin_name = '$admin_name', admin_phone = '$admin_phone' WHERE admin_id = $admin_id";
    mysqli_query($conn, $query);

    // Update session details
    $_SESSION['admin_name'] = $admin_name;
    $_SESSION['admin_phone'] = $admin_phone;

    header('Location: adminProfile.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>

    <!-- my css -->
    <link rel="stylesheet" href="css/adminProfile.css">
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
  
    <div class="profile-container">
        <h1>Admin Profile</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="profile-image">
                <img src="img/admin/<?php echo $admin_photo; ?>" alt="Profile Image">
                <input type="file" name="admin_photo" id="admin_photo">
            </div>
            <div class="form-group">
                <label for="admin_name" class="form-label">Name</label>
                <input type="text" class="form-control" id="admin_name" name="admin_name" value="<?php echo $admin_name; ?>" required>
            </div>
            <div class="form-group">
                <label for="admin_phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="admin_phone" name="admin_phone" value="<?php echo $admin_phone; ?>" required>
            </div>
            <a href="adminDashboard.php">
                <button type="button">
                    Back
                </button>
            </a>
            <button type="submit" name="btn">Save Changes</button>
        </form>
    </div>

    <script src="js/profile.js"></script>
</body>

</html>