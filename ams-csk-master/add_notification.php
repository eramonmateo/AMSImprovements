<?php

session_start();
include "connects.php";

if (isset($_POST['notification']) && isset($_GET['to_user_name'])) {
    $name = $_GET['to_user_name'];
    $department = $_POST['dept'];
    $notif_body = $_POST['notification'];
    $date = date('Y-m-d');

    $sql = "INSERT INTO `notifications` (`name`, `department`, `body`, `date`) VALUES ('$name', '$department','$notif_body','$date')";
    $sql_result = mysqli_query($conn, $sql);
    if ($sql_result) {
        echo '<script>alert("Notification info successfully added."); window.location.href = "send_notification.php";</script>';
    } else {
        echo "Error in adding notification" . $conn->error;
    }
} else {
    echo  $conn->error;
}

/*

if ($conn->query($sql) == TRUE) {
    //echo '<script>alert("Notification info successfully added."); window.location.href = "send_notification.php";</script>';
    return;
} else {
    echo "Error in adding notification" . $conn->error;
}
*/