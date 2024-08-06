<?php include 'partial.php'; ?>
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
        <a href="admin_dash.php" class="brand">
            <img src="images/logo-wname-clearbg.png" alt="" class="logo">
            <span class="text">Attendance Management System</span>
        </a>
        <ul class="side-menu top">
            <li class="<?php if ($page == 'admin_dash') {
                            echo 'active';
                        } ?>">
                <a href="admin_dash.php">
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
            <div id="switch_content" class="dropdown" style="<?php if ($tab != 'attendance') {
                                                                    echo "display: none;";
                                                                } ?>">
                <li class="<?php if ($page == 'attendance-summary-view') {
                                echo 'active';
                            } ?>">
                    <a href="attendance-summary-view.php">
                        <i class='bx bx-table'></i>
                        <span class="text">Summary View</span>
                    </a>
                </li>
                <li class="<?php if ($page == 'dtr_view') {
                                echo 'active';
                            } ?>">
                    <a href="dtr_view.php">
                        <i class='bx bx-windows'></i>
                        <span class="text">Activities/DTRs</span>
                    </a>
                </li>
                <li class="<?php if ($page == 'manual_inout') {
                                echo 'active';
                            } ?>">
                    <a href="manual_inout.php">
                        <i class='bx bx-timer'></i>
                        <span class="text">Manual In/Out</span>
                    </a>
                </li>
                <li class="<?php if ($page == 'attendance-filed-leaves') {
                                  echo 'active';
                              } ?>">
                      <a href="attendance-filed-leaves.php">
                      <i class='bx bx-calendar-star' ></i>
                          <span class="text">Leaves</span>
                      </a>
                  </li>
                  <li class="<?php if ($page == 'attendance-file-a-leave-admin') {
                                  echo 'active';
                              } ?>">
                      <a href="attendance-file-a-leave-admin.php">
                          <i class='bx bx-calendar-edit'></i>
                          <span class="text">File a Leave</span>
                      </a>
                  </li>
            </div>
            <li class="<?php if ($page == 'notif') {
                                echo 'active';
                            } ?>">
                    <a href="notif.php">
                        <i class='bx bx-windows'></i>
                        <span class="text">Late Notification Page</span>
                    </a>
                </li>  
            <li>
                <a id="switch_mngmt" style="cursor: default;">
                    <i class='bx bx-list-check'></i>
                    <span class="text">Management</span>
                </a>
            </li>
            <div id="switch_mngmt_content" class="dropdown_mngmt" style="<?php if ($tab != 'mngmt') {
                                                                                echo "display: none;";
                                                                            } ?>">
                <li class="<?php if ($page == 'create_anncmnt') {
                                echo 'active';
                            } ?>">
                    <a href="create_anncmnt.php">
                        <i class='bx bx-news'></i>
                        <span class="text">Create Announcement</span>
                    </a>
                </li>

                <!-- <li class="<?php if ($page == 'send_notification') {
                                echo 'active';
                            } ?>">
                    <a href="send_notification.php">
                        <i class='bx bx-bell'></i>
                        <span class="text">Send Notification</span>
                    </a>
                </li> -->

                <!-- <li class="<?php if ($page == 'send-task') {
                                echo 'active';
                            } ?>">
                    <a href="mngmt-send-task.php">
                        <i class='bx bx-task'></i>
                        <span class="text">Send Task</span>
                    </a>
                </li> -->
                <li class="<?php if ($page == 'attendance-create-acc') {
                                echo 'active';
                            } ?>">
                    <a href="attendance-create-acc.php">
                        <i class='bx bx-user-plus'></i>
                        <span class="text">Create Account</span>
                    </a>
                </li>
                <li class="<?php if ($page == 'delete-account') {
                                echo 'active';
                            } ?>">
                    <a href="attendance-delete-acc.php">
                        <i class='bx bx-user-x'></i>
                        <span class="text">Delete Account</span>
                    </a>
                </li>
                <li class="<?php if ($page == 'account-details') {
                                echo 'active';
                            } ?>">
                    <a href="attendance-acc-details.php">
                        <i class='bx bxs-user-detail'></i>
                        <span class="text">Account Details</span>
                    </a>
                </li>
            </div>

            <li class="<?php if ($page == 'view-timein-timeout') {
                            echo 'active';
                        } ?>">
                <a href="view-timein-timeout.php">
                    <i class='bx bx-time'></i>
                    <span class="text">View Time In / Out</span>
                </a>
            </li>

            <li class="<?php if ($page == 'admin_inout') {
                            echo 'active';
                        } ?>">
                <a href="admin_inout.php">
                    <i class='bx bx-timer'></i>
                    <span class="text">My Time In / Out</span>
                </a>
            </li>
            <li class="<?php if ($page == 'my-profile') {
                            echo 'active';
                        } ?>">
                <a href="my-profile.php?id=<?php echo $id; ?>">
                    <i class='bx bx-user-circle'></i>
                    <span class="text">My Profile</span>
                </a>
            </li>
            <li>
                <a id="switch_csk" style="cursor: default;">
                    <i class='bx bx-globe'></i>
                    <span class="text">CSK</span>
                </a>
            </li>

            <div id="switch_csk_content" class="dropdown_csk" style="<?php if ($tab != 'csk') {
                                                                            echo "display: none;";
                                                                        } ?>">
                <li class="<?php if ($page == 'csk-account-list') {
                                echo 'active';
                            } ?>">
                    <a href="csk-account-list.php">
                        <i class='bx bx-group'></i>
                        <span class="text">Account List</span>
                    </a>
                </li>
                <li class="<?php if ($page == 'csk-organization') {
                                echo 'active';
                            } ?>">
                    <a href="csk-organization.php">
                        <i class='bx bx-body'></i>
                        <span class="text">Organization</span>
                    </a>
                </li>
                <li class="<?php if ($page == 'ams_team') {
                                echo 'active';
                            } ?>">
                    <a href="ams_team.php">
                        <i class='bx bx-vector'></i>
                        <span class="text">AMS Dev Team</span>
                    </a>
                </li>
            </div>
        </ul>

    </section>
</body>

</html>