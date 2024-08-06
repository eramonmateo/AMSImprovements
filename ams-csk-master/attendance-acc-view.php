<?php

    /// JHERIMY B. ///

    session_start();
    
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include "connects.php";

    $page = 'account-details';
    $tab = 'mngmt';
    include_once('sidebar.php');

    if(isset($_SESSION['username'])) {
        //do nothing
        } else {
        header('Location: index.php');
        exit;
        }
    
        $name = $_SESSION['username'];
        $position = $_SESSION['position'];
    
    
        if($position == "employee") {
            $query = "SELECT name, department, position, start_date FROM emp_info WHERE name='$name'";
        } else {
            $query = "SELECT name, department, position, start_date, hr_req, hr_ren, hr_left FROM int_info WHERE name='$name'";
        }
        
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        $viewName = $_POST['viewName'];
        $viewEmail = $_POST['viewEmail'];
        $viewPassword = $_POST['viewPassword'];
        $viewRole = $_POST['viewRole'];
        $viewPosition = $_POST['viewPosition'];
        $viewDepartment = $_POST['viewDepartment'];
        $viewSupervisor = $_POST['viewSupervisor'];
        $viewAddress = $_POST['viewAddress'];
        $viewAge = $_POST['viewAge'];
        $viewGender = $_POST['viewGender'];
        $viewStartdate = $_POST['viewStartdate'];
        $viewSchool = $_POST['viewSchool'];
        $viewImage = $_POST['viewImage'];
        ?>

    
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Boxicons -->
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;900&display=swap" rel="stylesheet">
        <!-- My CSS -->
        <link rel="stylesheet" href="css/attendance-acc-view.css">
        <title>AMS | ACCOUNT VIEW | <?php echo $viewName ?></title>
    </head>
    <body>
        <section id="section">
            <nav>
                <i class='bx bx-menu' onclick="closeSideBar()"></i>
                <h2>
                    <?php
                    $userDisplay = explode(" ", $_SESSION['username']);
                    echo (count($userDisplay) > 2) ?  
                    $userDisplay[0] . " " . $userDisplay[count($userDisplay) - 1] : $userDisplay[0];
                    echo " | ";
                    echo "AMS Admin";
                    echo " | ";
                    $positionDisplay = explode(" ", $row['position']);
                    echo (count($positionDisplay) > 2) ?  
                    $positionDisplay[0] . " " . $positionDisplay[count($positionDisplay) - 1] : $positionDisplay[0];
                    echo " | ";
                    echo $row['department'];
                    ?>
                </h2>
                <a href="logout.php" class="logout">
                    <i class='bx bxs-log-out-circle' ></i>
                    <span class="text">Logout</span>
                </a>
            </nav>
            <main>
                <div class="dateTime">
                    <h2>TIME AND DATE: <span id="live-time"></span></h2>
                </div> 

                <!-- <div class="boxEdit">
                    <form action="edit-profile-admin.php" method="POST">
                        <input type="hidden" value="<?php echo $page ?>" name="page">
                        <input type="hidden" value="<?php echo $tab ?>" name="tab">
                        <input type="hidden" value="<?php echo $sql_row['name'];?>" name="nameProfile">
                        <input type="submit" class="editButton" value="EDIT PROFILE">
                    </form>
                </div>
         -->
                <div class="viewContainer">
                    <div class="box1">
                        <div class="imageContainer">
                            <img src="<?php echo (!empty($viewImage)) ? $viewImage : "images/profile-user.png" ?>">
                        </div>
                        <h2><?php echo explode(" ", $viewName)[0] ?>'s Profile</h2> 
                        <p>Name <span><?php echo $viewName ?></span></p>
                        <p style="text-transform: none;">Email <span><?php echo $viewEmail ?></span></p>
                        <p style="text-transform: none;">Password <span><?php echo $viewPassword ?></span></p>
                        <p>Address <span><?php echo (!empty($viewAddress)) ? $viewAddress : "N/A" ?></span></p>
                    </div>

                    <div class="box2">
                        <h2>CSK Status</h2>
                        <p>Role <span><?php echo $viewRole ?></span></p>
                        <p>Position <span><?php echo $viewPosition ?></span></p>
                        <p>Department <span><?php echo $viewDepartment ?></span></p>
                        <p>Supervisor <span><?php echo (!empty($viewSupervisor)) ? $viewSupervisor : "N/A" ?></span></p>
                        <p>Date Started <span><?php echo (!empty($viewStartdate)) ? $viewStartdate : "N/A" ?></span></p>        

                    </div>

                    <!-- <div class="boxButton">
                        <a href="attendance-acc-details.php">
                            <i class='bx bx-chevron-left'></i>
                        </a>
                    </div> -->
                    
                    <div class="box3" id="box3">
                        <h2>Additional info</h2>
                        <p>Age <span><?php echo (!empty($viewAge)) ? $viewAge : "N/A" ?></span></p>
                        <p>Gender <span><?php echo (!empty($viewGender)) ? $viewGender : "N/A" ?></span></p>
                        <p><?php echo ($viewPosition === 'intern') ? 
                        ((!empty($viewSchool)) ? "School <span>" . $viewSchool : "School <span> N/A") : "" ?></span></p>
                    </div>

                    <div>
                
                    <form action="edit-profile.php" method="POST">
                        <input type="hidden" value="<?php echo $page ?>" name="page">
                        <input type="hidden" value="<?php echo $tab ?>" name="tab">
                        <input type="hidden" value="<?php echo $sql_row['name'];?>" name="nameProfile">
                        <input type="submit" class="editButton" value="EDIT PROFILE">
                    </form>
                </div>
                
            </main>   
        </section>
        
        <script>
            function closeSideBar() {
                sidebar.classList.toggle('hide');
                section.classList.toggle('active');
            }

        </script>
    
        <script src="js/Dashboard.js"></script>
        <script src="js/summaryView.js"></script>
        <script src="js/navDropdown.js"></script>
        <script src="js/date-time.js"></script>
    
    </body>
    </html>