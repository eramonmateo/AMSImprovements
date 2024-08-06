<?php
    session_start();
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include "connects.php";

    require_once 'auth_check.php';

    $page = 'admin_dash';
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

    // if ($position == "intern") {
    //     $result_text .= "<br>Hours Required: " .
    //         $row['hr_req'] . "<br>Hours Rendered: " .
    //         $row['hr_ren'] . "<br>Hours Left: " .
    //         $row['hr_left'];
    // }

    // Get the current user's department
    $department = $_SESSION['department'];
    //$department = 'Accounting';

    // pagination
    if (isset($_GET['page_no']) && $_GET['page_no'] !== ""){
        $page_no = $_GET['page_no'];
    }else{
        $page_no = 1;
    }

    $total_records_per_page = 1;
    $offset = ($page_no - 1) * $total_records_per_page;

    // Fetch all the announcements for the department
    $query2 = "SELECT * FROM announcement ORDER  BY date_created DESC LIMIT $offset, $total_records_per_page";
    //$query2 = "SELECT * FROM announcement WHERE department='$department' OR department = '' ORDER  BY date_created DESC LIMIT $offset, $total_records_per_page";

    $result2 = mysqli_query($conn, $query2) or die(mysqli_error($conn));

    if(empty($result2)){
    }else{
        $currentDate = date('Y-m-d');
        $prev_page = $page_no - 1;
        $next_page = $page_no + 1;
    
        $result_count = mysqli_query($conn, "SELECT COUNT(*) as total_records FROM announcement") OR die(mysqli_error($conn));
        $records = mysqli_fetch_array($result_count);
        $total_records = $records['total_records'];
        $total_no_of_pages = ceil($total_records / $total_records_per_page);
    }
    mysqli_close($conn);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="css/Dashboard.css">
    <link rel="stylesheet" href="css/admin_dash.css">
    <!-- Bootstrap -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
    <title>AMS | Dashboard</title>
</head>
<body>


<!-- SIDEBAR -->
<?php include_once 'sidebar.php'; ?>

<!-- CONTENT -->
<section id="content">
    <!-- NAVBAR -->
    <nav>
        <i class='bx bx-menu'></i>
        <h2 class="font-size spaces"><?php

                $userDisplay = explode(" ", $_SESSION['username']);
                echo (count($userDisplay) > 2) ?  
                $userDisplay[0] . " " . $userDisplay[count($userDisplay) - 1] : $userDisplay[0];
                echo " | ";
                echo "AMS Admin";
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

    <!-- MAIN -->
    <main>
    <div class="input-box">
            <ul class="box-info">
                <div class="input-field">
                    <div class="date-time">
                        <h1>Current Time and Date: <br>
                            <span id="live-time"></span></h1>
                    </div>
                </div>
            </ul>
        </div>
        <div class="head-title">
            <table class="table-holder">
                <thead>
                    <tr>
                        <th><h1 class="anncmnt-title">Announcement</h1> </th>
                    </tr>
                </thead>
                <tbody class="tbody-holder">
                <?php
                    //if(!empty($result2)){
                    if(mysqli_num_rows($result2)==0){
                        ?>
                        <tr>
                            <td>There are no latest announcement</td>
                        </tr>
                        <?php
                    }else{
                        while ($row2 = mysqli_fetch_array($result2)) {?>
                            <tr>
                                <div>
                                    <td>
                                    <h3 class="date_posted"><?php echo date('M d, Y', strtotime($row2['date_created'])) ; ?></h3>
                                    <h2 class="body"><?php echo $row2['body']; ?></h2>
                                    <h3 class="sender"><?php echo $row2['name']; ?></h3>
                                </div>
                                

                                    <ul class="nav_announcement">
                                        <li>
                                        <a class="page-link <?= ($page_no <= 1) ? 'disabled' : '';?>" <?= ($page_no > 1) ? 'href=?page_no=' . $prev_page : ''; ?> aria-label="Previous">
                                            <span aria-hidden="true" class="icon">&laquo;</span>
                                        </a>
                                        </li>
                                        <li>
                                        <a class="page-link <?= ($page_no >= $total_no_of_pages) ? 'disabled' : '';?>" <?= ($page_no < $total_no_of_pages) ? 'href=?page_no=' . $next_page : ''; ?> aria-label="Next">
                                            <span aria-hidden="true" class="icon">&raquo;</span>
                                        </a>
                                        </li>
                                    </ul> 
                                </td>

                            </tr>
                        <?php }
                    }
                     ?>
                </tbody>
            </table>

        </div>
        <div class="head-title">
            <div class="left">
                <h1>Dashboard</h1>
            </div>
        </div>

        <div class="grid-container">
            <div class="grid-item">
                <a href="attendance-summary-view.php">
                    <i class='bx bx-table logo-icon'></i>
                    <figcaption class="caption">Attendance Summary View</figcaption>
                </a>
            </div>
            <div class="grid-item">
                <a href="dtr_view.php">
                    <i class='bx bx-windows logo-icon'></i>
                    <figcaption class="caption">Activities/DTRs</figcaption>
                </a>
            </div>
            <div class="grid-item">
                <a href="create_anncmnt.php">
                    <i class='bx bx-news logo-icon'></i>
                    <figcaption class="caption">Create Announcement</figcaption>
                </a>
            </div>
            <div class="grid-item">
                <a href="manual_inout.php">
                    <i class='bx bx-timer logo-icon'></i>
                    <figcaption class="caption">Manual In/Out</figcaption>
                </a>
            </div>
            <div class="grid-item">
                <a href="send_notification.php">
                    <i class='bx bx-bell logo-icon'></i>
                    <figcaption class="caption">Send Notice</figcaption>
                </a>
            </div>
            <div class="grid-item">
                <a href="my-profile.php">
                    <i class='bx bx-user-circle logo-icon'></i>
                    <figcaption class="caption">My Profile</figcaption>
                </a>
            </div>
            <div class="grid-item">
                <a href="attendance-create-acc.php">
                    <i class='bx bx-user-plus logo-icon'></i>
                    <figcaption class="caption">Account Creation</figcaption>
                </a>
            </div>
            <div class="grid-item">
                <a href="view-timein-timeout.php">
                    <i class='bx bx-time logo-icon'></i>
                    <figcaption class="caption">Time In and Out</figcaption>
                </a>
            </div>
            <div class="grid-item">
                <a href="csk-organization.php">
                    <i class='bx bx-body logo-icon'></i>
                    <figcaption class="caption">CSK Org. Chart</figcaption>
                </a>
            </div>
            <!-- <div class="grid-item">
                <a href="mngmt-send-task.php">
                    <i class='bx bx-task logo-icon'></i>
                    <figcaption class="caption">Create Task</figcaption>
                </a>
            </div> -->
        </div>
    </main>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/Dashboard.js"></script>
<script src="js/navDropdown.js"></script>
<script src="js/annCarousel.js"></script>
<script src="js/summaryView.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>