<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include 'connects.php';

$page = 'ams_team';
$tab = 'csk';
include_once('sidebar.php');

if (isset($_SESSION['username'])) {
    // Do nothing
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

?>


<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="css/ams_team.css">
    <title>AMS | AMS Web Dev Team</title>
</head>

<body>
    <!-- CONTENT -->
    <section id="content">
     <!-- NAVBAR -->
<nav>
    <i class='bx bx-menu'></i>
    <h2>
        <?php
        $userDisplay = explode(" ", $_SESSION['username']);
        echo (count($userDisplay) > 2) ?
            $userDisplay[0] . " " . $userDisplay[count($userDisplay) - 1] : $userDisplay[0];
        echo " | ";
        echo "AMS Admin";
        echo " | ";
        if (!empty($row['position'])) {
            $positionDisplay = explode(" ", $row['position']);
            echo (count($positionDisplay) > 2) ?
                $positionDisplay[0] . " " . $positionDisplay[count($positionDisplay) - 1] : $positionDisplay[0];
        }
        echo " | ";
        if (!empty($row['department'])) {
            echo $row['department'];
        }
        ?>
    </h2>

    <li>
        <a href="logout.php" class="logout">
            <i class='bx bxs-log-out-circle'></i>
            <span class="text">Logout</span>
        </a>
    </li>
</nav>

<!-- Acel M. -->

<div class="container">
    <h1>AMS Development Team</h1>
</div>

<table id="data-table">
    <th></th>
    <th>Name</th>
    <th>Position</th>
    <th>Email</th>
</table>

<script>
// Read and parse the CSV data 
fetch('Web Developer.csv')
    .then(response => response.text())
    .then(data => {
        const rows = data.split('\n');
        const table = document.getElementById('data-table');

        rows.forEach(rowData => {
            const row = document.createElement('tr');
            const columns = rowData.split(',');

            columns.forEach(columnData => {
                const cell = document.createElement('td');
                cell.textContent = columnData;
                row.appendChild(cell);
            });

            table.appendChild(row);
        });
    });
</script>

<script src="js/Dashboard.js"></script>
<script src="js/navDropdown.js"></script>
</section>
</body>
</html>