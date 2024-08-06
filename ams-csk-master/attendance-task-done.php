<?php

    /// JHERIMY B. ///

    session_start();
    include 'connects.php';

    $done = $_POST['taskID'];

    $sql = "UPDATE tasks SET status = 'Done' WHERE id = $done";

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Task is successfully done."); window.location.href = "attendance-task.php";</script>';
        return;
    } else {
        echo "Error updating status.";
    }

    $conn->close();

?>