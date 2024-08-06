<!-- Modified by k.l. Abecia-->

<?php
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include "connects.php";

$page = 'reg_inout';
$tab = 'attendance';
include_once('non-admin-sidebar.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$name = $_SESSION['username'];
$position = $_SESSION['position'];

$query = "SELECT name, department, position, start_date FROM emp_info WHERE name='$name'";

if ($position == "intern") {
    $query = "SELECT name, department, position, start_date, hr_req, hr_ren, hr_left FROM int_info WHERE name='$name'";
}

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$date = $row['start_date'];
$formatted_date = date('D, M d, Y', strtotime($date));

$result_text = "<h1>Name: " . $row['name'] . "<br>Department: " . $row['department'] . "<br>Position: " . $row['position'] . "<br>Start Date: " . $formatted_date;

if ($position == "intern") {
    $hours_required = $row['hr_req'];
    $hours_rendered = $row['hr_ren'];
    $hours_left = $row['hr_left'];

    $result_text .= "<br>Hours Required: " . $hours_required;
    $result_text .= "<br>Hours Rendered: " . $hours_rendered;
    $result_text .= "<br>Hours Left: " . $hours_left;
}

// Check the current user status
$check_status = "SELECT status, role FROM users WHERE name='$name'";
$result_status = mysqli_query($conn, $check_status);

if ($result_status) {
    $row_status = mysqli_fetch_assoc($result_status);
    $user_status = $row_status['status'];
    $role_status = $row_status['role'];
}

// Fetch the current time_in record in a single query
$current_date = date('Y-m-d');
$check_time_in = "SELECT datetime FROM time_in WHERE DATE(datetime) = CURDATE() AND name='$name' LIMIT 1";

$result_time_in = mysqli_query($conn, $check_time_in);

if ($result_time_in) {
    $row_time_in = mysqli_fetch_assoc($result_time_in);

    if ($row_time_in) {
        $time_in_record = $row_time_in['datetime'];

        // Check if it's past 11:59 PM
        $current_time = date('H:i:s');
        $timeout_time = '23:59:00';

        if ($current_time > $timeout_time) {
            // Perform automatic time out
            $auto_time_out_datetime = date('Y-m-d H:i:s');
            $insert_auto_time_out = "INSERT INTO time_out (name, datetime) VALUES ('$name', '$auto_time_out_datetime')";
            
            if (mysqli_query($conn, $insert_auto_time_out)) {
                // Auto time-out record inserted successfully
                
                // Update the hr_left for the intern
                if ($position === "intern") {
                    // Deduct an hour for auto time-out
                    $hours_left_before = $hours_left_before - 7;
                    $update_hr_left_query = "UPDATE int_info SET hr_left = $hours_left_before WHERE name = '$name'";
                    mysqli_query($conn, $update_hr_left_query);
                }
            } else {
                // Handle database query error
                echo "Error: " . mysqli_error($conn);
            }
        }

    } else {
        $time_in_record = "No record";
    }
} else {
    // Handle database query error
    echo "Error: " . mysqli_error($conn);
}

// Fetch the current time_out record in a single query
$check_time_out = "SELECT datetime FROM time_out WHERE DATE(datetime) = CURDATE() AND name='$name'";
$result_time_out = mysqli_query($conn, $check_time_out);

if ($result_time_out && mysqli_num_rows($result_time_out) > 0) {
    $row_time_out = mysqli_fetch_assoc($result_time_out);
    $time_out_record = $row_time_out['datetime'];
} else {
    $time_out_record = "NA";
}

// Fetch hr_left value before and after timing out in a single query
$query_hr_info = "SELECT hr_left FROM int_info WHERE name='$name'";
$result_hr_info = mysqli_query($conn, $query_hr_info);

$hours_left_before = 0; 

if ($result_hr_info && $position === "intern") {
    $row_hr_info = mysqli_fetch_assoc($result_hr_info);
    $hours_left_before = $row_hr_info['hr_left'];

    // If user_status is "out" and both time_in_record and time_out_record exist, calculate total hours worked
    if ($user_status === "out" && $time_in_record !== "No record" && $time_out_record !== "NA") {
        $time_in = strtotime($time_in_record);
        $time_out = strtotime($time_out_record);
        $total_hours_worked = (($time_out - $time_in) / 3600) - 1; // Convert seconds to hours
    } else {
        $total_hours_worked = 0; // No record yet or not checked out
    }

    // Determine if the total hours worked exceed 8 hours
    $required_hours = 8;
    $overtime_hours = max(0, $total_hours_worked - $required_hours);

    // Update the "overtime" column in the "time_out" table
    $update_query = "UPDATE time_out SET overtime = $overtime_hours WHERE name = '$name' AND DATE(datetime) = CURDATE()";
    mysqli_query($conn, $update_query);

    // Fetch hr_left value after timing out
    $query_after_timeout = "SELECT hr_left FROM int_info WHERE name='$name'";
    $result_after_timeout = mysqli_query($conn, $query_after_timeout);

    if ($result_after_timeout && $position === "intern") {
        $row_after_timeout = mysqli_fetch_assoc($result_after_timeout);
        $hours_left_after = $row_after_timeout['hr_left'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--IconsScout-->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="css/Dashboard.css">
    <link rel="stylesheet" href="css/reg_inout.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity= "sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
          crossorigin="anonymous">
    <script src= "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity= "sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous">
        </script>
    <link rel="stylesheet" href= "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>AMS | Dashboard</title>
</head>

<body>

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu'></i>
            <h2><?php
                $userDisplay = explode(" ", $_SESSION['username']);
                echo (count($userDisplay) > 2) ?
                    $userDisplay[0] . " " . $userDisplay[count($userDisplay) - 1] : $userDisplay[0];
                echo " | ";
                echo "AMS Regular";
                ?> <?php
                $positionDisplay = explode(" ", $row['position']);
                echo (count($positionDisplay) > 2) ?
                    $positionDisplay[0] . " " . $positionDisplay[count($positionDisplay) - 1] : $positionDisplay[0];
                echo " | ";
                echo $row['department'];
                ?></h2>

            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </nav>

        <!-- MAIN
        <main>
            <div class="input-box">
                <ul class="box-info">
                    <div class="input-field">
                        <div class="date-time">
                            <h1>Current Time and Date: <span id="live-time"></span></h1>
                        </div>
                    </div>
                </ul>
            </div><br> -->

<!-- ... -->
<div class="makerow-container">
<div class="inout-container">
    <div class="time-in-container">
        <!-- Time IN section -->
        <div class="profile-column">
            <img class="img1" src="/images/profile-user.png" >
            
            <?php 
            // Check if the user status is 'in' or 'out' and set the values of $buti and $buto accordingly
            $buti = ($user_status === 'in') ? 'disabled' : '';
            $buto = ($user_status === 'out') ? 'disabled' : '';

            if ($position == "intern") {
                echo "<div class='hours-item'>" . $name . "</div>";
                $userDisplay = explode(" ", $_SESSION['username']);
                echo (count($userDisplay) > 2) ?
                    $userDisplay[0] . " " . $userDisplay[count($userDisplay) - 1] : $userDisplay[0];
                echo " | ";
                echo "AMS Regular";
                ?> <?php
                $positionDisplay = explode(" ", $row['position']);
                echo (count($positionDisplay) > 2) ?
                    $positionDisplay[0] . " " . $positionDisplay[count($positionDisplay) - 1] : $positionDisplay[0];
                echo " | ";
                echo $row['department'];
                
                $currentDate = date('Y-m-d'); // Get the current date in the format "YYYY-MM-DD"
                $sql_check = "SELECT approval FROM time_in WHERE name='$name' AND DATE(datetime) = '$currentDate'";
                $result_check = mysqli_query($conn, $sql_check);

                if ($result_check && mysqli_num_rows($result_check) > 0) {
                    $row_check = mysqli_fetch_assoc($result_check);
                    $approval_status = $row_check['approval'];

                    if ($approval_status === "Approved") {
                        $hours_column_query = "SELECT hours FROM time_out WHERE name='$name' AND DATE(datetime) = '$currentDate'";
                        $result_hours_column = mysqli_query($conn, $hours_column_query);

                        if ($result_hours_column && mysqli_num_rows($result_hours_column) > 0) {
                            $row_hours_column = mysqli_fetch_assoc($result_hours_column);
                            $hours = $row_hours_column['hours'];
                            $hours_left_before += $hours;
                        }

                //echo "<div class='hours-item'><strong>Hours Remaining:</strong> " . $hours_left_before . "</div>";
            } else {
                //echo "<div class='hours-item'><strong>Hours Remaining:</strong> " . $hours_left_before . "</div>";
            }
        } else {
            //echo "<div class='hours-item'><strong>Hours Remaining:</strong> " . $hours_left_before . "</div>";
        }
    }
    ?>
        </div>

        <div class="status-column">
            <div class="date-time">
                <h1><span id="live-time"></span></h1>
            </div><br>

            <!-- Time in and Time out Buttons -->
            <div class="time-column">
                <?php if ($user_status !== 'in') : ?>
                    <a href="time_in.php" <?php echo $buti; ?>>
                <?php endif; ?>
                <div class="timeinbutton">
                    <button type="button" class="btn btn-lg btn-outline-success"><p>TIME IN</p></button>
            
                </div>
                <?php if ($user_status !== 'in') : ?>
                    </a>
                <?php endif; ?>
                <form method="POST" action="time_out.php">
                    <?php if ($user_status !== 'out') : ?>
                    
                        <button type="submit" class="btn btn-lg btn-outline-danger"><p>TIME OUT</p></button>
                        </button>
                    <?php else : ?>
                        <button type="submit" class="btn btn-lg btn-outline-danger" disabled style="cursor: default;">
                            <p>TIME OUT</p>
                        </button>
                    <?php endif; ?>
            </div>
                 
            <!-- Status and time for Time IN -->
            <!-- <?php
            if ($time_in_record !== "No record") {
                echo '<div class="status">Status: Successfully</div>';
                echo '<div class="status-time">';
                echo 'Time IN: ' . date('h:i:s A', strtotime($time_in_record)) . '<br>';
                echo date('M/d/Y', strtotime($time_in_record));
                echo '</div>';
            } else {
                echo '<div class="status">Status: No Record Yet</div>';
                echo '<div class="status-time">Time IN: No Record Yet</div>';
            }
            ?> -->
        </div> 
    </div>

    <div class="time-out-container">
<!-- Time OUT section -->
<div class="hours-column">
    <h1>Status</h1>
<?php
            if ($time_in_record !== "No record") {
                
                echo '<div class="status-time">';
                echo 'TIME IN: ' . date('h:i:s A', strtotime($time_in_record)) ;
                echo date(' | M/d/Y', strtotime($time_in_record)).'<br>'.'<br>';
                echo '</div>';
            } else {
                echo '<div class="status">Status: No Record Yet</div>' . '<div class="status-time">Time IN: No Record Yet</div>';
               
            }
            ?>
             <?php
            if ($user_status === "out") {
                if ($time_out_record !== "NA") {
                    echo '<div class="status-time">';
                    echo 'TIME OUT: ' . date('h:i:s A', strtotime($time_out_record)) ;
                    echo date(' | M/d/Y', strtotime($time_out_record));
                    echo '</div>';
                } else {
                    
                    echo '<div class="status-time">TIME OUT: No Record Yet</div>';
                }
            } else {
                
                echo '<div class="status-time">TIME OUT: No Record Yet</div>';
            }
            ?>
    <!--
    <?php
    if ($position == "intern") {
        $currentDate = date('Y-m-d'); // Get the current date in the format "YYYY-MM-DD"
        $sql_check = "SELECT approval FROM time_out WHERE name='$name' AND DATE(datetime) = '$currentDate'";
        $result_check = mysqli_query($conn, $sql_check);

        if ($result_check && mysqli_num_rows($result_check) > 0) {
            $row_check = mysqli_fetch_assoc($result_check);
            $approval_status = $row_check['approval'];

            if ($approval_status === "Approved") {
                echo "<div class='hours-item'><strong>Hours Remaining (Time OUT):</strong> " . $hours_left_after . "</div>";
            } else {
                echo "<div class='hours-item'><strong>Hours Remaining (Time OUT):</strong> " . $hours_left_after . "</div>";
            }
        } else {
            echo "<div class='hours-item'><strong>Hours Remaining (Time OUT):</strong> " . $hours_left_after . "</div>";
        }
    }
    ?>
    -->
</div>
        
        <div class="hours-column">
            
            <form method="POST" action="time_out.php">
                <div class="input-field-text">
                    <textarea id="taskstext" name="tasks" class="input" minlength="0" placeholder="Task here" required></textarea>
                </div>
            </form>
        </div>
<!--
        <div class="status-column">
            Status and time for Time OUT 
            <?php
            
            // if ($user_status === "out") {
            //     if ($time_out_record !== "NA") {
            //         echo '<div class="status">Status: Successfully</div>';
            //         echo '<div class="status-time">';
            //         echo 'Time OUT: ' . date('h:i:s A', strtotime($time_out_record)) . '<br>';
            //         echo date('M/d/Y', strtotime($time_out_record));
            //         echo '</div>';
            //     } else {
            //         echo '<div class="status">Status: No Record Yet</div>';
            //         echo '<div class="status-time">Time OUT: No Record Yet</div>';
            //     }
            // } else {
            //     echo '<div class="status">Status: No Record Yet</div>';
            //     echo '<div class="status-time">Time IN: No Record Yet</div>';
            // }
            ?>
        </div>
    </div>-->
    <div class="hours-column">
            <div class='hours-item'><strong>Hours Required:</strong> 200</div><div class='hours-item'><strong>Hours Remaining:</strong> 200</div>
    </div>        
</div> 
<!--
<div class="utility-column">
<form action="reg_inout_export.php" method="get">
    <div class="row">
        <strong>Filter Date</strong>
    </div>
    <div class="row">
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" id="start_date" required>
    </div>
    <div class="row">
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" id="end_date" required>
    </div>
    <div class="row">
         Add the hidden input field for the name 
        <input type="hidden" name="name" value="<?php echo $name; ?>">
        <input type="submit" class="submit" value="Export Time In/Out">
    </div>
</form>
</div> --> 

</div>
<!-- ... -->

</main>
</section>
</main>
</section>

<!-- CONTENT -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+ECpoR6pN6f6p+R5fV/2v8cVc5zPMy5um5EB4lH1AC6zfoFk" crossorigin="anonymous"></script>
<script async src="js/Dashboard.js"></script>
<script async src="js/summaryView.js"></script>
<script async src="js/navDropdown.js"></script>
<script async>
    // Fetch and update the live time using AJAX
    $(document).ready(function () {
        function updateTime() {
            $.ajax({
                url: 'get_live_time.php', // fetch from the get_live_time.php
                success: function (data) {
                    $('#live-time').text(data);
                }
            });
        }

        // Update the time every second
        setInterval(updateTime, 1000);
        updateTime(); // Initial update
    });
</script>
<script>
    
</script>
</body>
</html>