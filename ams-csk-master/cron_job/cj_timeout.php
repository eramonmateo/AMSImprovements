<!-- < ?php
$datetime = date('Y-m-d H:i:s');

// set the datetime of no time out
$query = "UPDATE time_out SET datetime='$datetime' WHERE approval='No Time-out'";;
$result = mysqli_query($conn, $query);

// set user as out
$query2 = "UPDATE user SET status='out' WHERE status='in'";
$result2 = mysqli_query($conn, $query2);
? > -->
<!-- Revision -->
<?php
$servername = "localhost";
$username = "u879258463_csk";
$password = "@ttEndAnc3";
$dbname = "u879258463_attendancesys";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentTime = date('Y-m-d') . ' 17:00:00'; // 5:00 PM

// Update time_out table
$query = "UPDATE time_out SET datetime='$currentTime' WHERE approval='No Time-out'";
$result = $conn->query($query);

if ($result !== TRUE) {
    echo "Error updating time_out table: " . $conn->error;
}

// Update user table
$query2 = "UPDATE user SET status='out' WHERE status='in'";
$result2 = $conn->query($query2);

if ($result2 !== TRUE) {
    echo "Error updating user table: " . $conn->error;
}

// Close the database connection
$conn->close();
?>