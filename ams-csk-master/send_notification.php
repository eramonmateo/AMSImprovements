<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include 'connects.php';
require_once 'auth_check.php';

$page = 'send_notification';
$tab = 'mngmt';
include_once('sidebar.php');

if (isset($_SESSION['username'])) {
    //do nothing
} else {
    header('Location: index.php');
    exit;
}

// update date of notification
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $date = date('Y-m-d');
    $sql = "UPDATE notifications SET date='$date' WHERE id='$id'";
    $repost = mysqli_query($conn, $sql);
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
    <link rel="stylesheet" href="css/send_notification.css">
    <title>AMS | Employee and Intern Management</title>
</head>

<body>

    <!-- SIDEBAR -->
    <?php include_once 'sidebar.php'; ?>

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
            <div class="header">
                <div class="head-title">
                    <div class="date-time left title">
                        <h1>Send Notification</h1>
                    </div>
                </div>

                <div>
                    <!-- FILTERS -->
                    <div class="input-form">
                        <form class="filter" action="send_notification.php" method="POST">
                            <div class="filter-department">
                                <select name="department" class="select-department">
                                    <option>Select</option>
                                    <option value="HR">HR</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Accounting">Accounting</option>
                                    <option value="IT">IT</option>
                                    <option value="Admin">Admin</option>
                                    <option value="All">All</option>
                                </select>
                                <input type="text" name="name" placeholder="Name Search" id="name-search" />
                            </div>
                            <button class="search-filter" type="submit" name="search">Apply Filter</button>
                        </form>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="tg-wrap">
                    <table style="width:100%" class="tg">
                        <thead>
                            <tr class="column-name">
                                <th>Name</th>
                                <th>Department</th>
                                <th>Body</th>
                                <th>Date</th>
                                <th>Send</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // INITIAL LOADING OF PAGE
                            if (!isset($_POST['search'])) {
                                $query = "SELECT * FROM int_info ORDER BY name ASC";
                                $data = mysqli_query($conn, $query) or die('error');

                                if (mysqli_num_rows($data) > 0) {
                                    while ($row3 = mysqli_fetch_assoc($data)) {
                                        $id = $row3['id'];
                                        $name = $row3['name'];
                                        $department = $row3['department'];
                                        $date = date('Y-m-d');
                            ?>
                                        <tr>
                                            <td><?php echo $name; ?></td>
                                            <td><?php echo $department; ?></td>
                                            <td>
                                                <form action="add_notification.php?to_user_name=<?php echo $name; ?>" method="POST" id="add-notif-form-<?php echo $name; ?>">
                                                    <!-- HIDDEN INPUTS -->
                                                    <input value="<?php echo $department; ?>" type="hidden" name="dept"></input>
                                                    <!-- HIDDEN INPUTS -->

                                                    <textarea type="text" class="notif-input" wrap="soft" oninput="resizeTextarea(this)" name="notification"></textarea>
                                                </form>
                                            </td>
                                            <td><?php echo $date; ?></td>
                                            <td>
                                                <input type="submit" class="btn" value="Send" form="add-notif-form-<?php echo $name; ?>">
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                    </tr>
                                    <?php
                                }
                            }
                            // APPLIED FILTER
                            else {
                                $name = $_POST['name'];
                                $department = $_POST['department'];

                                // Filter conditions
                                if ($name != "" || $department != "") {
                                    
                                     if ($name != "" && $department != "") {
                                        $query = "SELECT * FROM int_info WHERE department = '$department' 
                                        AND name LIKE '%$name%' ORDER BY name ASC";
                                    }
                                    else if (!empty($name)) {
                                        $query = "SELECT * FROM int_info WHERE name LIKE '%$name%' ORDER BY name ASC";
                                    } elseif (!empty($department)) {
                                        if ($department == "All") {
                                            $query = "SELECT * FROM int_info ORDER BY name ASC";
                                        } else {
                                            $query = "SELECT * FROM int_info WHERE department = '$department'ORDER BY name ASC";
                                        }
                                    } 
                                   
                                    $data = mysqli_query($conn, $query) or die('error');

                                    if (mysqli_num_rows($data) > 0) {
                                        while ($row3 = mysqli_fetch_assoc($data)) {
                                            $id = $row3['id'];
                                            $name = $row3['name'];
                                            $department = $row3['department'];
                                            $date = date('Y-m-d');
                                    ?>
                                            <tr>
                                                <td><?php echo $name; ?></td>
                                                <td><?php echo $department; ?></td>
                                                <td>
                                                    <form action="add_notification.php?to_user_name=<?php echo $name; ?>" method="POST" id="add-notif-form-<?php echo $name; ?>">
                                                        <!-- HIDDEN INPUTS -->
                                                        <input value="<?php echo $department; ?>" type="hidden" name="dept"></input>
                                                        <!-- HIDDEN INPUTS -->
                                                        <textarea type="text" class="notif-input" wrap="soft" oninput="resizeTextarea(this)" name="notification" required></textarea>
                                                    </form>
                                                </td>
                                                <td><?php echo $date; ?></td>
                                                <td>
                                                    <input type="submit" class="btn" value="Send" form="add-notif-form-<?php echo $name; ?>">
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td>N/A</td>
                                            <td>N/A</td>
                                            <td>N/A</td>
                                            <td>N/A</td>
                                            <td>N/A</td>
                                        </tr>
                            <?php
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

        </main>

    </section>

    <script src="js/Dashboard.js"></script>
    <script src="js/summaryView.js"></script>
    <script src="js/navDropdown.js"></script>

    <script>
        function resizeTextarea(element) {
            element.style.height = "auto"; // Reset the height to auto
            element.style.height = element.scrollHeight + "px"; // Set the height based on the scroll height
        }

        function formatDate(dateNow) {
            const options = {
                weekday: 'long',
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            };
            return dateNow.toLocaleDateString(undefined, options);
        }

        function updateTime() {
            var dateNow = new Date();
            var hours = dateNow.getHours();
            var minutes = dateNow.getMinutes();
            var seconds = dateNow.getSeconds();
            var ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12;
            hours = hours ? hours : 12;

            hours = hours.toString().padStart(2, '0');
            minutes = minutes.toString().padStart(2, '0');
            seconds = seconds.toString().padStart(2, '0');

            var currentDate = formatDate(dateNow);
            document.getElementById("live-time").textContent = hours + ":" + minutes + ":" + seconds + ' ' + ampm + " | " + currentDate;
        }

        updateTime(); // Call the function initially to display the time immediately
        setInterval(updateTime, 1000); // Call the function every second to update the time
    </script>
</body>

</html>