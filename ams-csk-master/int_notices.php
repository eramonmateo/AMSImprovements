<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include 'connects.php';

$page = 'int_notices';
$tab = 'switch_int_notices';
include_once('intern_sidebar.php');

if (isset($_SESSION['username'])) {
    //do nothing
} else {
    header('Location: index.php');
    exit;
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
$date =
    $row['start_date'];
$formatted_date = date('D, M d, Y', strtotime($date));
$result_text = "<h1>Name: " .
    $row['name'] . "<br>Department: " .
    $row['department'] . "<br>Position: " .
    $row['position'] . "<br>Start Date: ";


if ($position == "intern") {
    $result_text .= "<br>Hours Required: " .
        $row['hr_req'] . "<br>Hours Rendered: " .
        $row['hr_ren'] . "<br>Hours Left: " .
        $row['hr_left'];
}
?>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">



    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="css/int_notices.css">
    <title>AMS | Employee and Intern Management</title>
</head>

<body>
    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu'></i>
            <h2><?php echo $_SESSION['username'];
                echo " | ";
                echo "AMS Regular";
                echo "<br>";
                echo $row['position'];
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
            </div>

            <div class="head-title">
                <div class="left">
                    <h1>My Notices</h1>
                </div>
            </div>

            <div class="tg-wrap">
                <table style="width: 100%" class="tg">
                    <tbody>
                        <tr>
                            <th class="tg-0pky">My Notices</th>
                            <th class="tg-0pky">Date</th>
                            <th class="tg-0pky">From</th>
                        </tr>
                        
                    </tbody>
                </table>
            </div>

            <script src="js/Dashboard.js"></script>
            <script src="js/summaryView.js"></script>
            <script src="js/navDropdown.js"></script>
        </main>
    </body>
</html>