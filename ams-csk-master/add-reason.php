<?php 

    session_start();
    include "connects.php";


if(isset($_POST['reason']) && isset($_POST['name'])){
    $name = $_POST['name'];
    $position = $_POST['position'];
    $email = $_POST['email'];
    $reason = $_POST['reason'];

    // Connect to the database 
    // Perform the insertion into the deletion logs table
    $insert_query = "INSERT INTO deletion_logs (name, position, email, reason) 
                     VALUES ('$name', '$position', '$email', '$reason')";
    mysqli_query($conn, $insert_query);

    // Perform the deletion from the users table

    $deletionSuccessful = false;

    $sql_users = "DELETE FROM users WHERE name = '$name'";
    $conn->query($sql_users);
    if ($conn->affected_rows > 0) {
        echo "Records deleted successfully from the 'users' table.<br>";
        $deletionSuccessful = true;
    } else {
        echo "No records found in the 'users' table for the specified name.<br>";
    }

    $sql_int_info = "DELETE FROM int_info WHERE name = '$name'";
    $conn->query($sql_int_info);
    if ($conn->affected_rows > 0) {
        echo "Records deleted successfully from the 'int_info' table.<br>";
        $deletionSuccessful = true;
    } else {
        echo "No records found in the 'int_info' table for the specified name.<br>";
    }

    $sql_emp_info = "DELETE FROM emp_info WHERE name = '$name'";
    $conn->query($sql_emp_info);
    if ($conn->affected_rows > 0) {
        echo "Records deleted successfully from the 'emp_info' table.<br>";
        $deletionSuccessful = true;
    } else {
        echo "No records found in the 'emp_info' table for the specified name.<br>";
    }

    mysqli_close($conn);

    if ($deletionSuccessful) {
        echo '<script>alert("User info deleted."); window.location.href = "account_details.php";</script>';
    } else {
        echo '<script>alert("No records found for the specified name."); window.location.href = "account_details.php";</script>';
    }
}


?>