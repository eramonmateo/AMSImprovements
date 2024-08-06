<?php

    /// JHERIMY B. ///

    session_start();
    include "connects.php";

    $name = $_POST['taskName'];
    $department = $_POST['taskDepartment'];
    $task = $_POST['taskGiven'];
    $date = date('Y-m-d\TH:i');
    $deadline = $_POST['taskDeadline'];
    $from = $_SESSION['username'];
    $sql = "INSERT INTO tasks 
    (name, department, task, date, deadline, `from`) VALUES ('$name', '$department','$task', '$date', '$deadline', '$from')";

    if ($conn->query($sql) === TRUE) {
            echo '<script>alert("Task info successfully added."); window.location.href = "mngmt-send-task.php";</script>';
    return;
    } else {
        echo "Error in adding task";
    }

    mysqli_close($conn);
?>