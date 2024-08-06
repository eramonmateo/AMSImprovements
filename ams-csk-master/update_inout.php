<?php


require_once 'connects.php';

$format = "Y-m-d H:i:s";

// Function to calculate hours difference
    function calculateHoursDifference($time_in, $time_out) {

// Convert time strings to timestamps
    $time_in_timestamp = strtotime($time_in);
    $time_out_timestamp = strtotime($time_out);

// Calculate the difference in seconds
    $seconds_diff = $time_out_timestamp - $time_in_timestamp;

// Convert the difference to hours
    $hours_diff = ($seconds_diff / 3600) - 1;

    return $hours_diff;
}

// Check if the form is submitted and the necessary fields are present
    if (
        isset($_POST['name']) &&
        isset($_POST['time_in']) &&
        isset($_POST['time_out']) &&
        isset($_POST['filter_date'])
) {
// Retrieve the submitted form data
    $name = $_POST['name'];

// Convert the time_in and time_out values to the desired format (e.g., 'H:i:s')
    $time_in = date('H:i:s', strtotime($_POST['time_in']));
    $time_out = date('H:i:s', strtotime($_POST['time_out']));

// Extract the date from the filter_date value
    $date = $_POST['filter_date']; // Assuming the date format is 'Y-m-d'

// Generate a unique token
    $token = uniqid() . mt_rand(100000, 999999);

// Calculate the hours difference
    $hours_diff = calculateHoursDifference($time_in, $time_out);

// Determine if the total hours worked exceed 8 hours
    $required_hours = 8;
    $overtime_hours = max(0, $hours_diff - $required_hours);

// Begin a transaction
    mysqli_begin_transaction($conn);

// Insert time in record
    $insert_in_sql = "INSERT INTO time_in (name, datetime, photo_loc, token) VALUES ('$name', '$date $time_in', 'photo_loc', '$token')";
    $insert_in_result = mysqli_query($conn, $insert_in_sql);

// Insert time out record with overtime
    $insert_out_sql = "INSERT INTO time_out (name, datetime, approval, token, tasks, overtime, hours) VALUES ('$name', '$date $time_out', 'Reviewing', '$token', 'Manual time in and out', $overtime_hours, $hours_diff)";
    $insert_out_result = mysqli_query($conn, $insert_out_sql);

// Check results and commit or rollback transaction
    if ($insert_in_result && $insert_out_result) {
        mysqli_commit($conn);
        echo 'Time values inserted successfully.';
    } else {
        mysqli_rollback($conn);
        echo 'Error in inserting time values: ' . mysqli_error($conn);
    }

// Close the transaction
    mysqli_autocommit($conn, true);

// Refresh the page to display the updated/inserted values
    header('Location: manual_inout.php');
    exit();
    } else {
    // Invalid form submission
    echo 'Invalid form submission.';
    }


function getHoursBetweenTwoTimes($datetime1, $datetime2) {
    // Calculate the time difference
    $interval = $datetime1->diff($datetime2);

    // Get the number of hours from the time difference
    $hours = $interval->h + ($interval->days * 24);

    return $hours;
}

// Check if the form is submitted and the necessary fields are present
if (isset($_POST['name']) && (isset($_POST['time_in']) || isset($_POST['time_out'])) && isset($_POST['filter_date'])) {
    // Retrieve the submitted form data
    $name = $_POST['name'];

    // Convert the time_in and time_out values to the desired format (e.g., 'H:i:s')
    $time_in = date('H:i:s', strtotime($_POST['time_in']));
    $time_out = date('H:i:s', strtotime($_POST['time_out']));

    $datetime_in = new DateTime(date('Y-m-d H:i:s', strtotime($_POST['time_in'])));
    $datetime_out = new DateTime(date('Y-m-d H:i:s', strtotime($_POST['time_out'])));

    // Extract the date from the filter_date value
    $date = $_POST['filter_date']; // Assuming the date format is 'Y-m-d'

    // Generate a unique token
    $token = uniqid() . mt_rand(100000, 999999);

    // Check if the time entry already exists for the specified name and date
    $check_sql = "SELECT * FROM time_in WHERE name = '$name' AND DATE(datetime) = '$date'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (isset($_POST['name']) && isset($_POST['time_in']) && isset($_POST['time_out']) && isset($_POST['filter_date'])) {
        $name = $_POST['name'];
        $time_in = date('H:i:s', strtotime($_POST['time_in']));
        $time_out = date('H:i:s', strtotime($_POST['time_out']));
        $date = $_POST['filter_date'];
    
        // Generate a unique token
        $token = uniqid() . mt_rand(100000, 999999);
    
        // Begin a transaction
        mysqli_begin_transaction($conn);
    
        // Insert time in record
        $insert_in_sql = "INSERT INTO time_in (name, datetime, photo_loc, token) VALUES ('$name', '$date $time_in', 'photo_loc', '$token')";
        $insert_in_result = mysqli_query($conn, $insert_in_sql);
    
        // Insert time out record
        $insert_out_sql = "INSERT INTO time_out (name, datetime, approval, token, tasks, overtime, hours) VALUES ('$name', '$date $time_out', 'Reviewing', '$token', 'Manual time in and out', 0, 0)";
        $insert_out_result = mysqli_query($conn, $insert_out_sql);
    
        // Check results and commit or rollback transaction
        if ($insert_in_result && $insert_out_result) {
            mysqli_commit($conn);
            echo 'Time values inserted successfully.';
        } else {
            mysqli_rollback($conn);
            echo 'Error in inserting time values: ' . mysqli_error($conn);
        }
    
        // Close the transaction
        mysqli_autocommit($conn, true);
    
        // Refresh the page to display the updated/inserted values
        header('Location: manual_inout.php');
        exit();
    } else {
        // Invalid form submission
        echo 'Invalid form submission.';
    }
}

