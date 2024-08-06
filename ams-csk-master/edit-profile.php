<?php 
    
    /// JHERIMY B. ///

    session_start();

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include 'connects.php';

    $editPage =  $_POST['page'];
    $editTab =  $_POST['tab'];
    $editName =  $_POST['nameProfile'];

    $page = $editPage;
    $tab = $editTab;
    
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

        $sql =  "SELECT u.name, u.email, u.password, u.position, u.role, u.image, 
                ii.school, ii.hr_req, ei.position as job_position,
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
                WHERE (ei.name='$name' OR ii.name='$editName')";
        $sql_result = mysqli_query($conn, $sql);
        $sql_row = mysqli_fetch_assoc($sql_result);
        
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result); 

        $departmentOption = array(
            'Accounting' => 'Accounting', 
            'Admin' => 'Admin',
            'HR' => 'Human Resource',
            'IT' => 'Information Technology',
            'Management' => 'Management',
            'Marketing' => 'Marketing',);

        $genderOption = array('Male', 'Female');
        $roleOption = array('Admin', 'Regular');
        $positionOption = array('Intern', 'Employee');
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
        <link rel="stylesheet" href="css/edit-profile.css">
        <title>AMS | EDIT PROFILE</title>
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
                    <h1>Current Time and Date: <span id="live-time"></span></h1>
                </div>

                <form action="edit-profile-save.php" method="POST" id="edit-acc-form" enctype="multipart/form-data">
                    <input type="hidden" value="<?php echo $sql_row['name']?>" name="nameProfile">
                    <input type="hidden" value="<?php echo $sql_row['position']?>" name="positionProfile">
                    <input type="hidden" value="<?php echo $sql_row['image']?>" name="imageProfile">
                    <div class="viewContainer">

                        <div class="box1">
                            <h2>EDIT PROFILE</h2>
                            <div class="container">
                                <label for="editImage">Image</label>
                                <input type="file" name="editImage" accept=".jpg, .jpeg, .png">
                            </div>
                            <div class="container">
                                <label for="editName">Name</label>
                                <input type="text" value="<?php echo $sql_row['name'] ?>" name="editName" placeholder=" Enter Name" required>
                            </div>
                            <div class="container">
                                <label for="editEmail">Email Address</label>
                                <input type="text" value="<?php echo $sql_row['email'] ?>" name="editEmail" placeholder=" Enter Email" required>
                            </div>
                            <div class="container">
                                <label for="editPassword">Password</label>
                                <input type="text" value="<?php echo $sql_row['password'] ?>" name="editPassword" placeholder=" Enter Password" required>
                            </div>  
                            <div class="container">
                                <label for="editAddress">Address</label>
                                <input type="text" value="<?php echo $sql_row['address'] ?>" name="editAddress" placeholder=" Enter Address" required>
                            </div>
                            <div class="container">
                                <label for="editAge">Age</label>
                                <input type="number" value="<?php echo $sql_row['age'] ?>" name="editAge" placeholder=" Enter Age">
                            </div>                
                            <div class="container">
                                <label for="editGender">Gender</label>
                                <select name="editGender" required>
                                    <option value="" disabled selected>Select Gender</option>
                                    <?php foreach ($genderOption as $gender) {
                                        $selected = ($gender === $sql_row['gender']) ? 'selected' : '';
                                        echo '<option value="' . $gender . '" ' . $selected . '>' . $gender .'</option>';
                                    } ?>
                                </select>
                            </div>      
                            <div class="container">
                                <label for="editRole">Role</label>
                                <select name="editRole" required>
                                    <option value="" disabled selected>Select Account Type</option>
                                    <?php foreach ($roleOption as $role) {
                                        $selected = (strtolower($role) === $sql_row['role']) ? 'selected' : '';
                                        echo '<option value="' . strtolower($role) . '" ' . $selected . '>' . $role .'</option>';
                                    } ?>
                                </select> 
                            </div>
                            <div class="container">
                                <label for="editPosition">Position</label>
                                <select name="editPosition" id="editPosition" onchange="toggleAdditionalInput()" required>
                                    <option value="" disabled>Select Position</option>
                                    <?php foreach ($positionOption as $positionChoice) {
                                        $selected = (strtolower($positionChoice) === $sql_row['position']) ? 'selected' : '';
                                        echo '<option value="' . strtolower($positionChoice) . '" ' . $selected . '>' . $positionChoice .'</option>';
                                    } ?>
                                </select>
                            </div> 
                            <div class="container">
                                <label for="editDepartment">Department</label>
                                <select name="editDepartment" required>
                                    <option value="" disabled selected>Select Department</option>
                                    <?php foreach ($departmentOption as $department) {
                                        $selected = (array_search($department, $departmentOption) === $sql_row['department']) ? 'selected' : '';
                                        echo '<option value="' . array_search($department, $departmentOption) . '" ' . $selected . '>' . $department . '</option>';
                                    } ?>
                                </select>
                            </div>  
                            <div class="container">
                                <label for="editStartDate">Start Date</label>
                                <input type="date" value="<?php echo $sql_row['start_date'] ?>" name="editStartDate" required>
                            </div>          
                        </div>

                        <div class="box2" id="box2">   
                            <div class="container" id="hours" <?php echo ($position !== 'intern') ? 'style="display: none;"' : '' ?>>
                                <label for="editHrsReq">Hours Required</label>
                                <input type="number" value="<?php echo $sql_row['hr_req'] ?>" id="hoursInput" name="editHrsReq" placeholder=" Enter Hours Required">
                            </div> 
                            <div class="container" id="school" <?php echo ($position !== 'intern') ? 'style="display: none;"' : '' ?>>
                                <label for="editSchool">School</label>
                                <input type="text" value="<?php echo $sql_row['school'] ?>" id="schoolInput" name="editSchool" placeholder=" Enter School">
                            </div> 
                            <div class="container" id="jobPosition" <?php echo ($position === 'intern') ? 'style="display: none;"' : '' ?>>
                                <label for="editJobPosition">Job Position</label>
                                <input type="text" value="<?php echo $sql_row['job_position'] ?>" id="positionInput" name="editJobPosition" placeholder=" Enter Job Position">
                            </div>    
                            <h2>CHANGE SCHEDULE</h2>
                            <div class="container">
                                <label for="day">Monday</label>
                                <div class="day">
                                    <label for="mondayIn">IN</label>
                                    <input type="time" value="<?php echo explode(" ", $sql_row['monday'])[0] ?>" name="mondayIn">
                                    <label for="mondayOut">OUT</label>
                                    <input type="time" value="<?php echo explode(" ", $sql_row['monday'])[1] ?>" name="mondayOut">
                                </div>
                            </div> 
                            <div class="container">
                                <label for="day">Tuesday</label>
                                <div class="day">
                                    <label for="tuesdayIn">IN</label>
                                    <input type="time" value="<?php echo explode(" ", $sql_row['tuesday'])[0] ?>" name="tuesdayIn">
                                    <label for="tuesdayOut">OUT</label>
                                    <input type="time"  value="<?php echo explode(" ", $sql_row['tuesday'])[1] ?>"name="tuesdayOut">
                                </div>
                            </div>
                            <div class="container">
                                <label for="day">Wednesday</label>
                                <div class="day">
                                    <label for="wednesdayIn">IN</label>
                                    <input type="time" value="<?php echo explode(" ", $sql_row['wednesday'])[0] ?>" name="wednesdayIn">
                                    <label for="wednesdayOut">OUT</label>
                                    <input type="time" value="<?php echo explode(" ", $sql_row['wednesday'])[1] ?>" name="wednesdayOut">
                                </div>
                            </div>
                            <div class="container">
                                <label for="day">Thursday</label>
                                <div class="day">
                                    <label for="thursdayIn">IN</label>
                                    <input type="time" value="<?php echo explode(" ", $sql_row['thursday'])[0] ?>" name="thursdayIn">
                                    <label for="thursdayOut">OUT</label>
                                    <input type="time" value="<?php echo explode(" ", $sql_row['thursday'])[1] ?>" name="thursdayOut">
                                </div>
                            </div>
                            <div class="container">
                                <label for="day">Friday</label>
                                <div class="day">
                                    <label for="fridayIn">IN</label>
                                    <input type="time" value="<?php echo explode(" ", $sql_row['friday'])[0] ?>" name="fridayIn">
                                    <label for="fridayOut">OUT</label>
                                    <input type="time" value="<?php echo explode(" ", $sql_row['friday'])[1] ?>" name="fridayOut">
                                </div>
                            </div>
                            <div class="container">
                                <label for="day">Saturday</label>
                                <div class="day">
                                    <label for="saturdayIn">IN</label>
                                    <input type="time" value="<?php echo explode(" ", $sql_row['saturday'])[0] ?>" name="saturdayIn">
                                    <label for="saturdayOut">OUT</label>
                                    <input type="time" value="<?php echo explode(" ", $sql_row['saturday'])[1] ?>" name="saturdayOut">
                                </div>
                            </div>
                            <div class="container">
                                <label for="day">Sunday</label>
                                <div class="day">
                                    <label for="sundayIn">IN</label>
                                    <input type="time" value="<?php echo explode(" ", $sql_row['sunday'])[0] ?>" name="sundayIn">
                                    <label for="sundayOut">OUT</label>
                                    <input type="time" value="<?php echo explode(" ", $sql_row['sunday'])[1] ?>" name="sundayOut">
                                </div>
                            </div>
                            
                            <input type="submit" class="functionButton" value="SAVE" name="edit">
                        </div>
                    </div>
                </form> 
            </main>   
        </section>
        
        <script>
            function closeSideBar() {
                sidebar.classList.toggle('hide');
                section.classList.toggle('active');
            }

            function toggleAdditionalInput() {
                if (editPosition.value === "intern") {
                    hoursInput.setAttribute('required', 'required');
                    hours.style.display = "block";
                    schoolInput.setAttribute('required', 'required');
                    school.style.display = "block";
                    positionInput.removeAttribute('required');
                    jobPosition.style.display = "none"; 
                } else {
                    hoursInput.removeAttribute('required');
                    hours.style.display = "none";
                    schoolInput.removeAttribute('required');
                    school.style.display = "none"; 
                    positionInput.setAttribute('required', 'required');
                    jobPosition.style.display = "block";
                }
            }
        </script>
    
        <script src="js/Dashboard.js"></script>
        <script src="js/summaryView.js"></script>
        <script src="js/navDropdown.js"></script>

        <script src="js/date-time.js"></script>
    
    </body>
    </html>