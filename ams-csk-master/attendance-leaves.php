<?php

    /// JHERIMY B. ///

    session_start();
    include "connects.php";

    $leaveName = $_POST['leaveName'];
    $leaveDepartment = $_POST['leaveDepartment'];
    $leavePosition = $_POST['leavePosition'];
    $leaveType = $_POST['leaveType'];
    $leaveDatetime = date('Y-m-d\TH:i');
    $leaveStart = $_POST['leaveStart'];
    $leaveEnd = $_POST['leaveEnd'];
    $leaveFrom = $_SESSION['username'];
    $sql = "INSERT INTO leaves 
    (`name`, department, position, `type`, given, `start`, `end`, `from`) 
    VALUES ('$leaveName', '$leaveDepartment', '$leavePosition', '$leaveType', '$leaveDatetime', '$leaveStart', '$leaveEnd', '$leaveFrom')";

    if ($conn->query($sql) === TRUE) {
            echo '<script>alert("Leave info successfully added."); window.location.href = "attendance-leave-tagging.php";</script>';
    return;
    } else {
        echo "Error in adding leave";
    }

    mysqli_close($conn);
?>