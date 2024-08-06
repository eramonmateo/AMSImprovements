<?php

    /// JHERIMY B. ///

    session_start();
    
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include "connects.php";

    $page = 'my-profile';
    $tab = 'non-admin';

    if ($_SESSION['role'] === 'regular') {
        include_once('non-admin-sidebar.php');
    } else {
        include_once('sidebar.php');
    }

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

        $sql =  "SELECT u.name, u.email, u.password, u.position, u.role, u.image, 
                ii.school, ii.hr_req, 
                ei.position as job_position,
                sc.monday, sc.tuesday, sc.wednesday, sc.thursday, sc.friday, sc.saturday, sc.sunday,
                COALESCE(ii.department, ei.department) AS department,
                COALESCE(ii.address, ei.address) AS address, 
                COALESCE(ii.age, ei.age) AS age, 
                COALESCE(ii.gender, ei.gender) AS gender, 
                COALESCE(ii.start_date, ei.start_date) AS start_date, 
                s.supervisor_name
                FROM users u
                LEFT JOIN int_info ii ON u.name = ii.name
                LEFT JOIN emp_info ei ON u.name = ei.name
                LEFT JOIN supervision s ON (ii.department = s.supervised_dept OR ei.department = s.supervised_dept)
                LEFT JOIN schedule sc ON u.name = sc.name
                WHERE (ei.name='$name' OR ii.name='$name')";
        $sql_result = mysqli_query($conn, $sql);
        $sql_row = mysqli_fetch_assoc($sql_result);
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
        <link rel="stylesheet" href="css/my-profile.css">
        <title>AMS | MY PROFILE</title>
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

                <div class="viewContainer" id="viewContainer"> 
                    <div class="box1">
                        <div class="imageContainer">
                            <img src="<?php echo (!empty($sql_row['image'])) ? $sql_row['image'] : "images/profile-user.png" ?>">
                        </div>
                        <h2><?php echo explode(" ", $sql_row['name'])[0] ?>'S PROFILE</h2>
                        <p>Name <span><?php echo $sql_row['name'];?></span></p>
                        <p style="text-transform: none;">Email <span><?php echo $sql_row['email'];?></span></p>
                        <p style="text-transform: none;">Password <span><?php echo $sql_row['password'];?></span></p>
                        <p>Address <span><?php echo $sql_row['address'];?></span></p>
                    </div>
                    <div class="box2" id="box2">
                        <h2>CSK STATUS</h2>
                        <p>Role <span><?php echo $sql_row['role'];?></span></p>
                        <p>Position <span><?php echo $sql_row['position'];?></span></p>
                        <p>Department <span><?php echo $sql_row['department'];?></span></p>
                        <p>Supervisor <span><?php echo $sql_row['supervisor_name'];?></span></p>
                        <p>Date Started <span><?php echo $sql_row['start_date'];?></span></p> 
                        <p>Schedule <a onclick="toggleSchedule()">View Schedule</a></p>         
                    </div>
                    <div class="schedule" id="schedule">
                        <h2><?php echo explode(" ", $sql_row['name'])[0] ?>'S SCHEDULE<i class='bx bx-x' onclick="toggleSchedule()"></i></h2>
                        <p>Monday <span><?php echo ($sql_row['monday'] !== " ") ? $sql_row['monday'] : 'OFF';?></span></p>
                        <p>Tuesday <span><?php echo ($sql_row['tuesday'] !== " ") ? $sql_row['tuesday'] : 'OFF';?></span></p>
                        <p>Wednesday <span><?php echo ($sql_row['wednesday'] !== " ") ? $sql_row['wednesday'] : 'OFF';?></span></p>
                        <p>Thursday <span><?php echo($sql_row['thursday'] !== " ") ? $sql_row['thursday'] : 'OFF';?></span></p>
                        <p>Friday <span><?php echo ($sql_row['friday'] !== " ") ? $sql_row['friday'] : 'OFF';?></span></p>  
                        <p>Saturday <span><?php echo ($sql_row['saturday'] !== " ") ? $sql_row['saturday'] : 'OFF';?></span></p>       
                        <p>Sunday <span><?php echo ($sql_row['sunday'] !== " ") ? $sql_row['sunday'] : 'OFF';?></span></p>       
                    </div>
                    <div class="box3" id="box3">
                        <h2>ADDITIONAL INFO</h2>
                        <p>Age <span><?php echo $sql_row['age'];?></span></p>
                        <p>Gender <span><?php echo $sql_row['gender'];?></span></p>
                        <?php echo ($sql_row['position'] === 'employee') ? 
                        ((!empty($sql_row['school'])) ? "<p>Job Description <span>" . $sql_row['job_position'] . '</span></p>' : "<p>Job Description <span> N/A </span></p>") 
                        : NULL?>
                        <?php echo ($sql_row['position'] === 'intern') ? 
                        ((!empty($sql_row['school'])) ? "<p>School <span>" . $sql_row['school'] . '</span></p>' : "<p>School <span> N/A </span></p>") 
                        : NULL?>
                    </div>
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

            schedule.classList.toggle('hidden');

            function toggleSchedule () {
                schedule.classList.toggle('hidden');
                box2.classList.toggle('hidden');
            }
        </script>
    
        <script src="js/Dashboard.js"></script>
        <script src="js/summaryView.js"></script>
        <script src="js/navDropdown.js"></script>
        <script src="js/date-time.js"></script>
    
    </body>
    </html>