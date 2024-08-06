<?php
session_start();
include 'connects.php';

$name = $_SESSION['username'];
$dateNow = date("Y-m-d H:i:s");

if (isset($_POST['id']) && is_numeric($_POST['id']) && isset($_POST['new_status'])) {
    $id = $_POST['id'];
    $newStatus = $_POST['new_status'];

    $sql = "UPDATE filed_leaves SET status='$newStatus' WHERE leave_id = $id";

    if ($conn->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'error';
    }

    if($newStatus == "Approved"){
        $sql_approved = "UPDATE filed_leaves SET approvedby='$name', date_approved='$dateNow' WHERE leave_id = $id";
        $res_approved = mysqli_query($conn, $sql_approved);
    }
    else{
        $sql_not_approved = "UPDATE filed_leaves SET approvedby=null, date_approved=null WHERE leave_id = $id";
        $res_not_approved = mysqli_query($conn, $sql_not_approved);
    }
    
} else {
    echo 'invalid';
}

mysqli_close($conn);
?>
