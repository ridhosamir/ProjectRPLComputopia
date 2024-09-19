<?php
session_start();
include('server/connection.php');

if (!isset($_SESSION['logged_in'])) {
  header('Location: index.php');
  exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

$user_name = $user_data['user_name'];
$user_email = $user_data['user_email'];
$user_address = $user_data['user_address'];
$user_phone = $user_data['user_phone'];
$user_photo = $user_data['user_photo'];

if (isset($_POST['btn'])) {
  $user_name = $_POST['user_name'];
  $user_email = $_POST['user_email'];
  $user_address = $_POST['user_address'];
  $user_phone = $_POST['user_phone'];

  // Check if a new photo was uploaded
  if (isset($_FILES['user_photo']) && $_FILES['user_photo']['error'] == UPLOAD_ERR_OK) {
    $user_photo = $_FILES['user_photo']['name'];
    $target_dir = "img/user/";
    $target_file = $target_dir . basename($user_photo);
    move_uploaded_file($_FILES['user_photo']['tmp_name'], $target_file);
  }

  $query = "UPDATE users SET user_name = ?, user_email = ?, user_address = ?, user_phone = ?, user_photo = ? WHERE user_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('sssssi', $user_name, $user_email, $user_address, $user_phone, $user_photo, $user_id);

  if ($stmt->execute()) {
    $_SESSION['user_name'] = $user_name;
    $_SESSION['user_email'] = $user_email;
    $_SESSION['user_address'] = $user_address;
    $_SESSION['user_phone'] = $user_phone;
    $_SESSION['user_photo'] = $user_photo;
    header('Location: userProfile.php');
    exit;
  } else {
    // Error
    echo "Error: " . $conn->error;
    exit;
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User-Profile</title>

  <link rel="stylesheet" href="css/profile.css">

</head>

<body>
  <!-- page loader start -->
  <!-- <div class="preloader">
  <div class="loader">
    <div class="ball"></div>
    <div class="ball"></div>
    <div class="ball"></div>
    <div class="ball"></div>
    <div class="ball"></div>
  </div>
  </div> -->

  <div class="loader">
    <span class="loader_dot" style="--d: 200ms"></span>
    <span class="loader_dot" style="--d: 400ms"></span>
    <span class="loader_dot" style="--d: 600ms"></span>
    <span class="loader_dot" style="--d: 800ms"></span>
    <span class="loader_dot" style="--d: 1000ms"></span>
  </div>
  <!-- page loader end -->

  <!-- user profile start -->
  <div class="profile-container">
    <h1>User Profile</h1>
    <form method="post" enctype="multipart/form-data">
      <div class="profile-image">
        <img src="<?php echo 'img/user/' . $user_photo; ?>" alt="Profile Image">
        <input type="file" name="user_photo" id="user_photo">
      </div>
      <div class="form-group">
        <label for="user_name">Name : </label>
        <input type="text" name="user_name" id="user_name" value="<?php echo $user_name; ?>">
      </div>
      <div class="form-group">
        <label for="user_email">Email : </label>
        <input type="email" name="user_email" id="user_email" value="<?php echo $user_email; ?>">
      </div>
      <div class="form-group">
        <label for="user_address">Address : </label>
        <input type="text" name="user_address" id="user_address" value="<?php echo $user_address; ?>">
      </div>
      <div class="form-group">
        <label for="user_phone">Phone : </label>
        <input type="tel" name="user_phone" id="user_phone" value="<?php echo $user_phone; ?>">
      </div>
      <a href="index.php">
        <button type="button">
          Back
        </button>
      </a>
      <button type="submit" name="btn">Edit Profile</button>
    </form>
  </div>
  <!-- user profile end -->

  <script src="js/profile.js"></script>
</body>

</html>