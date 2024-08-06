<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="images/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="css/send_notification.css">
</head>

<body>
    <section id="sidebar">
        <a href="reg_dash.php" class="brand">
            <img src="images/logo-wname-clearbg.png" alt="" class="logo">
            <span class="text">Attendance Management System</span>
        </a>
        <ul class="side-menu top">
            <li class="<?php if ($page == 'reg_dash') {
                            echo 'active';
                        } ?>">
                <a href="reg_dash.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a id="switch" style="cursor: default;">
                    <i class='bx bx-clipboard'></i>
                    <span class="text">Attendance</span>
                </a>
            </li>
            <!-- <li class="<?php if ($page == 'notif') {
                                echo 'active';
                            } ?>">
                    <a href="notif.php">
                        <i class='bx bx-windows'></i>
                        <span class="text">Intern Notification Page</span>
                    </a>
            </li>   -->

            <div class="dropdown" style="<?php if ($tab != 'attendance') {
                                                echo "display: none;";
                                            } ?>">
                <li class="<?php if ($page == 'reg_inout') {
                                echo 'active';
                            } ?>">
                    <a href="reg_inout.php">
                        <i class='bx bx-table'></i>
                        <span class="text">Time In/Out</span>
                    </a>
                </li>

                <li class="<?php if ($page == 'task') {
                                echo 'active';
                            } ?>">
                    <a href="attendance-task.php">
                        <i class='bx bx-windows'></i>
                        <span class="text">My Tasks</span>
                    </a>
                </li>

                <li class="<?php if ($page == 'dtr') {
                                echo 'active';
                            } ?>">
                    <a href="attendance-dtr.php">
                        <i class='bx bx-history'></i>
                        <span class="text">Daily Time Record</span>
                    </a>
                </li>

                <li class="<?php if ($page == 'attendance-file-a-leave') {
                                echo 'active';
                            } ?>">
                    <a href="attendance-file-a-leave.php">
                        <i class='bx bx-calendar-edit'></i>
                        <span class="text">File a Leave</span>
                    </a>
                </li>

            </div>
            <!-- <li>
                <a href="under_dev.html">
                    <i class='bx bxs-calendar-exclamation'></i>
                    <span class="text">My Notices</span>
                </a>
            </li> -->

            <li class="<?php if ($page == 'my-profile') {
                            echo 'active';
                        } ?>">
                <a href="my-profile.php">
                    <i class='bx bx bx-user-circle'></i>
                    <span class="text">My Profile</span>
                </a>
            </li>

            <li class="<?php if ($page == 'csk') {
                            echo 'active';
                        } ?>">
                <a href="csk.php">
                    <i class='bx bx-globe'></i>
                    <span class="text">CSK</span>
                </a>
            </li>
        </ul>
    </section>
</body>

</html>