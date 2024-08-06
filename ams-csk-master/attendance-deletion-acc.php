<?php

    /// JHERIMY B. ///

    session_start();
    include "connects.php";

    $deleteDate = date('Y-m-d\TH:i');
    $deleteName = $_POST['deleteName'];
    $deletePosition = $_POST['deletePosition'];
    $deleteDepartment = $_POST['deleteDepartment'];
    $deleteEmail = $_POST['deleteEmail'];
    $deleteReason = $_POST['deleteReason']; 
    $deleteBy = $_SESSION['username'];

    if ($deletePosition === 'intern') {
        $deleteUser = "DELETE FROM users WHERE name='$deleteName' AND position='$deletePosition';
        DELETE FROM int_info WHERE name='$deleteName';
        DELETE FROM time_in WHERE name='$deleteName';
        DELETE FROM time_out WHERE name='$deleteName';
        DELETE FROM tasks WHERE name='$deleteName';
        DELETE FROM notifications WHERE name='$deleteName';
        DELETE FROM leaves WHERE name='$deleteName';
        DELETE FROM deletions WHERE name='$deleteName';
        DELETE FROM announcement WHERE name='$deleteName';
        INSERT INTO deletions (`datetime`, name, position, department, email, reason, deletedby) 
        VALUES ('$deleteDate', '$deleteName', '$deletePosition', '$deleteDepartment', '$deleteEmail', '$deleteReason', '$deleteBy')";
    } else {
        $deleteUser = "DELETE FROM users WHERE name='$deleteName' AND position='$deletePosition';
        DELETE FROM emp_info WHERE name='$deleteName';
        DELETE FROM time_in WHERE name='$deleteName';
        DELETE FROM time_out WHERE name='$deleteName';
        DELETE FROM tasks WHERE name='$deleteName';
        DELETE FROM notifications WHERE name='$deleteName';
        DELETE FROM leaves WHERE name='$deleteName';
        DELETE FROM deletions WHERE name='$deleteName';
        DELETE FROM announcement WHERE name='$deleteName';
        DELETE FROM schedule WHERE name='$deleteName';
        INSERT INTO deletions (`datetime`, name, position, department, email, reason, deletedby) 
        VALUES ('$deleteDate', '$deleteName', '$deletePosition', '$deleteDepartment', '$deleteEmail', '$deleteReason', '$deleteBy')";
    }

    if ($conn->multi_query($deleteUser) === TRUE) {
            echo '<script>alert("Account successfully deleted."); window.location.href = "attendance-delete-acc.php";</script>';
    return;
    } else {
        echo "Error in deleting account.";
    }

    mysqli_close($conn);
?>