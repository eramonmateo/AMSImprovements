<?php
    session_start();

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include "connects.php";
    $page = 'admin_inout';
    $tab = 'admin';
    include_once('sidebar.php');

    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
        exit();
    }

    $name = $_SESSION['username'];
    $position = $_SESSION['position'];

    if ($position == "employee") {
        $query = "SELECT name, department, position, start_date FROM emp_info WHERE name='$name'";
    } else {
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
    $check_status = "SELECT role, status FROM users WHERE name='$name'";
    $result_status = mysqli_query($conn, $check_status);
    if ($result_status) {
        $row_status = mysqli_fetch_assoc($result_status);
        $user_status = $row_status['status'];
        $role_status = $row_status['role'];
    }

    // Check if there is a record in the time_in table matching the live date and user's name
    $current_date = date('Y-m-d');
    $check_time_in = "SELECT datetime FROM time_in WHERE DATE(datetime) = CURDATE() AND name='$name'";

    $result_time_in = mysqli_query($conn, $check_time_in);
    if ($result_time_in && mysqli_num_rows($result_time_in) > 0) {
        // There is a record with the live date and user's name
        $row_time_in = mysqli_fetch_assoc($result_time_in);
        $time_in_record = $row_time_in['datetime'];
    } else {
        // No record found
        $time_in_record = "No record";
    }


    // Check if there is a record in the time_out table matching the live date
    $check_time_out = "SELECT datetime FROM time_out WHERE DATE(datetime) = CURDATE() AND name='$name'";

    $result_time_out = mysqli_query($conn, $check_time_out);
    if ($result_time_out && mysqli_num_rows($result_time_out) > 0) {
        // There is a record with the live date
        $row_time_out = mysqli_fetch_assoc($result_time_out);
        $time_out_record = $row_time_out['datetime'];
    } else {
        // No record found
        $time_out_record = "NA";
    }


    // Fetch hr_left value before timing out
    $query_before_timeout = "SELECT hr_left FROM int_info WHERE name='$name'";
    $result_before_timeout = mysqli_query($conn, $query_before_timeout);
    $row_before_timeout = mysqli_fetch_assoc($result_before_timeout);
    if ($position === "intern") {
    $hours_left_before = $row_before_timeout['hr_left'];
    }
    // Fetch hr_left value after timing out
    $query_after_timeout = "SELECT hr_left FROM int_info WHERE name='$name'"; 
    $result_after_timeout = mysqli_query($conn, $query_after_timeout);
    $row_after_timeout = mysqli_fetch_assoc($result_after_timeout);
    if ($position === "intern") {
    $hours_left_after = $row_after_timeout['hr_left'];
        }


// Calculate the total hours worked
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
    ?>



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
        <title>AMS | Dashboard</title>
    </head>
    <body>
        <!-- SIDEBAR -->
<?php include_once 'sidebar.php'; ?>

        <!-- CONTENT -->
        <section id="content">
            <!-- NAVBAR -->
            <nav>
            <i class='bx bx-menu' ></i>
			<h2><?php

                $userDisplay = explode(" ", $_SESSION['username']);
                echo (count($userDisplay) > 2) ?  
                $userDisplay[0] . " " . $userDisplay[count($userDisplay) - 1] : $userDisplay[0];
                echo " | ";
                echo "AMS Admin";
                ?> <br><?php
                $positionDisplay = explode(" ", $row['position']);
                echo (count($positionDisplay) > 2) ?  
                $positionDisplay[0] . " " . $positionDisplay[count($positionDisplay) - 1] : $positionDisplay[0];
                echo " | ";
                echo $row['department'];
                ?></h2>

			<li>
				<a href="logout.php" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
            </nav>

            <!-- MAIN -->
            <main>
                <div class="input-box">
                    <ul class="box-info">
                        <div class="input-field">
                            <div class="date-time">
                                <h1>Current Time and Date: <span id="live-time"></span></h1>
                            </div>
                        </div>
                    </ul>
                </div><br>
                
<!-- ... -->
<div class="makerow-container">
<div class="inout-container">
    <div class="time-in-container">
        <!-- Time IN section -->
        <div class="hours-column">
            <?php
            // Check if the user status is 'in' or 'out' and set the values of $buti and $buto accordingly
            $buti = ($user_status === 'in') ? 'disabled' : '';
            $buto = ($user_status === 'out') ? 'disabled' : '';

            if ($position == "intern") {
                echo "<div class='hours-item'><strong>Hours Required:</strong> " . $hours_required . "</div>";
                
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

                echo "<div class='hours-item'><strong>Hours Remaining:</strong> " . $hours_left_before . "</div>";
            } else {
                echo "<div class='hours-item'><strong>Hours Remaining:</strong> " . $hours_left_before . "</div>";
            }
        } else {
            echo "<div class='hours-item'><strong>Hours Remaining:</strong> " . $hours_left_before . "</div>";
        }
    }
    ?>
        </div>

        <div class="time-column">
            <?php if ($user_status !== 'in') : ?>
                <a href="time_in.php" <?php echo $buti; ?>>
            <?php endif; ?>
            <div class="time-logo">
                <i class="uil uil-clock-eight"></i>
            </div>
            <?php if ($user_status !== 'in') : ?>
                </a>
            <?php endif; ?>
            <div>TIME IN</div>
        </div>

        <div class="status-column">
            <!-- Status and time for Time IN -->
            <?php
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
            ?>
        </div>
   
    </div>

    <div class="time-out-container">
<!-- Time OUT section -->
<div class="hours-column">
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
</div>
        <div class="time-column">
            <form method="POST" action="time_out.php">
                <?php if ($user_status !== 'out') : ?>
                    <button type="submit" class="time-button" <?php echo $buto; ?>>
                        <i class="uil uil-clock-five"></i>
                    </button>
                <?php else : ?>
                    <button type="submit" class="time-button" disabled style="cursor: default;">
                        <i class="uil uil-clock-five"></i>
                    </button>
                <?php endif; ?>
            <div>TIME OUT</div>
        </div>

        <div class="status-column">
            <!-- Status and time for Time OUT -->
            <?php
            if ($user_status === "out") {
                if ($time_out_record !== "NA") {
                    echo '<div class="status">Status: Successfully</div>';
                    echo '<div class="status-time">';
                    echo 'Time OUT: ' . date('h:i:s A', strtotime($time_out_record)) . '<br>';
                    echo date('M/d/Y', strtotime($time_out_record));
                    echo '</div>';
                } else {
                    echo '<div class="status">Status: No Record Yet</div>';
                    echo '<div class="status-time">Time OUT: No Record Yet</div>';
                }
            } else {
                echo '<div class="status">Status: No Record Yet</div>';
                echo '<div class="status-time">Time IN: No Record Yet</div>';
            }
            ?>
        </div>


        
    </div>
    

    <form method="POST" action="time_out.php">
        <div class="input-field-text">
            <textarea id="taskstext" name="tasks" class="input" minlength="30" placeholder="Task here" required></textarea>
        </div>
    </form>
    </div>

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
        <!-- Add the hidden input field for the name -->
        <input type="hidden" name="name" value="<?php echo $name; ?>">
        <input type="submit" class="submit" value="Export Time In/Out">
    </div>
</form>

</div>

        


</div>
<!-- ... -->




    </main>
    </section>

    <!-- CONTENT -->
    <script src="js/Dashboard.js"></script>
    <script src="js/summaryView.js"></script>
    <script src="js/navDropdown.js"></script>

    </body>
    </html>