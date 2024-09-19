<?php
    $conn = mysqli_connect("127.0.0.1", "root", "", "computopia");
        if (mysqli_connect_errno()){
            echo "Failed To Connect to MySQL: " . mysqli_connect_errno();
            exit();
        }
        ?>