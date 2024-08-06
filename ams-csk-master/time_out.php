<?php date_default_timezone_set('Asia/Manila');
session_start();
include "connects.php";
$name = $_SESSION['username'];
$datetime = date('Y-m-d H:i:s');
$tasks = $conn->real_escape_string($_POST["tasks"]);

$check_status = "SELECT status, role, position FROM users WHERE name='$name'";
$result_status = mysqli_query($conn, $check_status);

if (mysqli_num_rows(($result_status)) > 0) {
    $row_status = mysqli_fetch_assoc($result_status);
    $user_status = ($row_status['status']);
    $position_status = ($row_status['position']);
    $role_status = ($row_status['role']);

    if ($user_status==="in") {
        $query = "SELECT datetime, token FROM time_in WHERE name='$name' ORDER BY datetime DESC LIMIT 1";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) > 0) {

            $row = mysqli_fetch_assoc($result);
            $time_in = strtotime($row['datetime']);
            $time_out = strtotime($datetime);
            $token = ($row['token']);

            $hours_diff = (($time_out - $time_in) / 3600) - 1;

            $dayOfWeek = date('w', $time_in); //for sunday OT

            if ($dayOfWeek === '0') {
                $hours_diff *= 1.5;
            }

            $query_int = "SELECT * FROM int_info WHERE name='$name'";
            $result_int = mysqli_query($conn, $query_int);
            $row_int = mysqli_fetch_assoc($result_int);
            
            if ($position_status === "intern") {
            $hr_ren = $row_int['hr_ren'] + $hours_diff;
            $hr_left = $row_int['hr_req'] - $hr_ren;
            }
            
            
            $sqli = "UPDATE time_out SET approval='Reviewing', name='$name', datetime='$datetime', overtime='0', hours='$hours_diff', tasks='$tasks' WHERE token = '$token'";
            $sqlo = "UPDATE time_in SET approval='Reviewing' WHERE token = '$token'";
            $sqlstat = "UPDATE users SET status='out' WHERE name='$name'";

    //      if (mysqli_query($conn, $sql)) {
    if ($position_status === "intern") {
                $sql_int = "UPDATE int_info SET hr_ren='$hr_ren', hr_left='$hr_left' WHERE name='$name'";
    }
            if (mysqli_query($conn, $sqli)) {
                mysqli_query($conn, $sqlstat);
                mysqli_query($conn, $sqlo);
                if ($position_status === "intern") {
                    if($hours_diff > 4) {
                        mysqli_query($conn, $sql_int);
                        header("Refresh:0; url=reg_inout.php");
                        echo "<script>alert('Timed-out successfully');</script>";
                    } elseif ($hours_diff < 4) {
                        header("Refresh:0; url=reg_inout.php");
                        echo "<script>alert('It has not been 4 hours yet; the hours you just rendered will not be credited. For future reference, 4 hours is the minimum but contact your immediate supervisor about this first.');</script>";
                    } else {
                        header("Refresh:0; url=reg_inout.php");
                        echo "<script>alert('Error');</script>";
                    }
                } elseif ($position_status === "employee") {
                    if ($role_status === "regular") {
                    header("Refresh:0; url=reg_inout.php");
                    echo "<script>alert('Timed-out successfully');</script>";
                    } elseif ($role_status === "admin") {
                    header("Refresh:0; url=admin_inout.php");
                    echo "<script>alert('Timed-out successfully');</script>";
                    }
                } 
            } else {
                echo "Error: time-out unsuccessful";
            }

        } else {
            echo "Time out not recorded";
        }
        
    }
    elseif ($user_status==="out"){
        if ($role_status === "regular") {
            header("Refresh:0; url=reg_inout.php");
            echo "<script>alert('You are already timed-out! Please time-in first.');</script>";
        } elseif ($role_status === "admin") {
            header("Refresh:0; url=admin_inout.php");
            echo "<script>alert('You are already timed-out! Please time-in first.');</script>";
        }
    } else {
        echo "Error occured.";
    }

} else {
    echo "Error occured.";
}
// } else {
//     echo '<script>alert("No time in record found"); </script>';
// }

mysqli_close($conn);

if($role_status === "admin"){
    header('Location: admin_inout.php');
}else{
    header('Location: reg_inout.php');
}

exit;

?>