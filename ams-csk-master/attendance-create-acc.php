<?php 
    
    /// JHERIMY B. ///

    session_start();

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include 'connects.php';

    $page = 'attendance-create-acc';
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

        $departmentOption = array(
            'Accounting' => 'Accounting', 
            'Admin' => 'Admin',
            'HR' => 'Human Resource',
            'IT' => 'Information Technology',
            'Management' => 'Management',
            'Marketing' => 'Marketing',)
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
        <link rel="stylesheet" href="css/attendance-create-acc.css">
        <title>AMS | MANAGEMENT | CREATE ACCOUNT</title>
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

                <form action="attendance-add-acc.php" method="POST" id="create-acc-form" enctype="multipart/form-data">
                    <div class="viewContainer">

                        <div class="box1">
                            <h2>CREATE ACCOUNT</h2>
                            <div class="container">
                                <label for="createImage">Image</label>
                                <input type="file" name="createImage" accept=".jpg, .jpeg, .png">
                            </div>
                            <div class="container">
                                <label for="createName">Name</label>
                                <input type="text" name="createName" placeholder=" Enter Name" required>
                            </div>
                            <div class="container">
                                <label for="createEmail">Email Address</label>
                                <input type="text" name="createEmail" placeholder=" Enter Email" required>
                            </div>
                            <div class="container">
                                <label for="createPassword">Password</label>
                                <input type="text" name="createPassword" placeholder=" Enter Password" required>
                            </div>  
                            <div class="container">
                                <label for="createAddress">Address</label>
                                <input type="text" name="createAddress" placeholder=" Enter Address" required>
                            </div>
                            <div class="container">
                                <label for="createAge">Age</label>
                                <input type="number"name="createAge" placeholder=" Enter Age">
                            </div>                
                            <div class="container">
                                <label for="createGender">Gender</label>
                                <select name="createGender" required>
                                    <option value="" disabled selected>Select Gender</option>
                                    <option value="Male">Male</option>;
                                    <option value="Female">Female</option>;
                                </select>
                            </div>      
                            <div class="container">
                                <label for="createRole">Role</label>
                                <select name="createRole" required>
                                    <option value="" disabled selected>Select Account Type</option>
                                    <option value="regular">Regular</option>;
                                    <option value="admin">Admin</option>;
                                </select> 
                            </div>
                            <div class="container">
                                <label for="createPosition">Position</label>
                                <select name="createPosition" id="createPosition" onchange="toggleAdditionalInput()" required>
                                    <option value="" disabled selected>Select Position</option>
                                    <option value="intern">Intern</option>;
                                    <option value="employee">Employee</option>;
                                </select>
                            </div> 
                            <div class="container">
                                <label for="createDepartment">Department</label>
                                <select name="createDepartment" required>
                                    <option value="" disabled selected>Select Department</option>
                                    <?php foreach ($departmentOption as $option) {
                                        echo '<option value="' . array_search($option, $departmentOption) . '">' . $option . '</option>';
                                    } ?>
                                </select>
                            </div>  
                            <div class="container">
                                <label for="createStartDate">Start Date</label>
                                <input type="date" name="createStartDate" required>
                            </div>          
                        </div>

                        <div class="box2" id="box2">   
                            <div class="container" id="hours" style="display: none;">
                                <label for="createHrsReq">Hours Required</label>
                                <input type="number" id="hoursInput" name="createHrsReq" placeholder=" Enter Hours Required">
                            </div> 
                            <div class="container" id="school" style="display: none;">
                                <label for="createSchool">School</label>
                                <input type="text" id="schoolInput" name="createSchool" placeholder=" Enter School">
                            </div> 
                            <div class="container" id="jobPosition" style="display: none;">
                                <label for="createJobPosition">Job Position</label>
                                <input type="text" id="positionInput" name="createJobPosition" placeholder=" Enter Job Position">
                            </div>    
                            <h2>SET SCHEDULE</h2>
                            <div class="container">
                                <label for="day">Monday</label>
                                <div class="day">
                                    <label for="mondayIn">IN</label>
                                    <input type="time" name="mondayIn">
                                    <label for="mondayOut">OUT</label>
                                    <input type="time" name="mondayOut">
                                </div>
                            </div> 
                            <div class="container">
                                <label for="day">Tuesday</label>
                                <div class="day">
                                    <label for="tuesdayIn">IN</label>
                                    <input type="time" name="tuesdayIn">
                                    <label for="tuesdayOut">OUT</label>
                                    <input type="time" name="tuesdayOut">
                                </div>
                            </div>
                            <div class="container">
                                <label for="day">Wednesday</label>
                                <div class="day">
                                    <label for="wednesdayIn">IN</label>
                                    <input type="time" name="wednesdayIn">
                                    <label for="wednesdayOut">OUT</label>
                                    <input type="time" name="wednesdayOut">
                                </div>
                            </div>
                            <div class="container">
                                <label for="day">Thursday</label>
                                <div class="day">
                                    <label for="thursdayIn">IN</label>
                                    <input type="time" name="thursdayIn">
                                    <label for="thursdayOut">OUT</label>
                                    <input type="time" name="thursdayOut">
                                </div>
                            </div>
                            <div class="container">
                                <label for="day">Friday</label>
                                <div class="day">
                                    <label for="fridayIn">IN</label>
                                    <input type="time" name="fridayIn">
                                    <label for="fridayOut">OUT</label>
                                    <input type="time" name="fridayOut">
                                </div>
                            </div>
                            <div class="container">
                                <label for="day">Saturday</label>
                                <div class="day">
                                    <label for="saturdayIn">IN</label>
                                    <input type="time" name="saturdayIn">
                                    <label for="saturdayOut">OUT</label>
                                    <input type="time" name="saturdayOut">
                                </div>
                            </div>
                            <div class="container">
                                <label for="day">Sunday</label>
                                <div class="day">
                                    <label for="sundayIn">IN</label>
                                    <input type="time" name="sundayIn">
                                    <label for="sundayOut">OUT</label>
                                    <input type="time" name="sundayOut">
                                </div>
                            </div>
                            
                            <input type="submit" class="functionButton" value="CREATE ACCOUNT" name="create">
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
                if (createPosition.value === "intern") {
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