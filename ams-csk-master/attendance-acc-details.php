<?php 
    
    /// JHERIMY B. ///

    session_start();

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include 'connects.php';

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
        
        $nameFilter = isset($_GET['name']) ? $_GET['name'] : '';
        $positionFilter = isset($_GET['position']) ? $_GET['position'] :'';
    
        if ($positionFilter === 'All Positions') {
            $dtr_sql = "SELECT u.name, u.email, u.password, u.position, u.role, u.image, 
                        ii.school,
                        COALESCE(ii.department, ei.department) AS department,
                        COALESCE(ii.address, ei.address) AS address, 
                        COALESCE(ii.age, ei.age) AS age, 
                        COALESCE(ii.gender, ei.gender) AS gender, 
                        COALESCE(ii.start_date, ei.start_date) AS start_date, 
                        s.supervisor_name
                        FROM users u
                        LEFT JOIN int_info ii ON u.name = ii.name
                        LEFT JOIN emp_info ei ON u.name = ei.name
                        LEFT JOIN supervision s ON (ii.department = s.supervised_dept OR ei.department = s.supervised_dept)";
        
            $whereClause = "";

            if (!empty($nameFilter)) {
                $whereClause .= "(ii.name LIKE '%$nameFilter%' OR ei.name LIKE '%$nameFilter%')";
            }
        
            if (!empty($whereClause)) {
                $dtr_sql .= " WHERE " . $whereClause;
            }
            
        } elseif ($positionFilter === 'Intern') {
            $dtr_sql = "SELECT u.name, u.email, u.password, u.position, u.role, u.image, 
                        ii.department,  ii.address, ii.age, ii.gender, ii.start_date, ii.school,
                        s.supervisor_name
                        FROM users u
                        JOIN int_info ii ON u.name = ii.name
                        LEFT JOIN supervision s ON ii.department = s.supervised_dept";

            $whereClause = "";

            if (!empty($nameFilter)) {
                $whereClause .= "ii.name LIKE '%$nameFilter%'";
            }

            if (!empty($whereClause)) {
                $dtr_sql .= " WHERE " . $whereClause;
            }

        } elseif ($positionFilter === 'Employee') {
            $dtr_sql = "SELECT u.name, u.email, u.password, u.position, u.role, u.image,  
                        ei.department, ei.address, ei.age, ei.gender, ei.start_date,
                        s.supervisor_name
                        FROM users u
                        JOIN emp_info ei ON u.name = ei.name
                        LEFT JOIN supervision s ON ei.department = s.supervised_dept";

            $whereClause = "";

            if (!empty($nameFilter)) {
                $whereClause .= "ei.name LIKE '%$nameFilter%'";
            }

            if (!empty($whereClause)) {
                $dtr_sql .= " WHERE " . $whereClause;
            }

        } else {
            $dtr_sql = "SELECT u.name, u.email, u.password, u.position, u.role, u.image,
                ii.school,
                COALESCE(ii.department, ei.department) AS department,
                COALESCE(ii.address, ei.address) AS address, 
                COALESCE(ii.age, ei.age) AS age, 
                COALESCE(ii.gender, ei.gender) AS gender, 
                COALESCE(ii.start_date, ei.start_date) AS start_date, 
                s.supervisor_name
                FROM users u
                LEFT JOIN int_info ii ON u.name = ii.name
                LEFT JOIN emp_info ei ON u.name = ei.name
                LEFT JOIN supervision s ON (ii.department = s.supervised_dept OR ei.department = s.supervised_dept)";
        }  
        $dtr_sql .= " ORDER BY name ASC";
        
    $dtr_result = mysqli_query($conn, $dtr_sql);
    
    if (!$dtr_result) {
        // Query execution failed, display the error message and terminate the script
        die('Error: ' . mysqli_error($conn));
    }
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
        <link rel="stylesheet" href="css/attendance-acc-details.css">
        <title>AMS | MANAGEMENT | ACCOUNT DETAILS</title>
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
    
                <div class="filterContainer" id="filterContainer">
                    <form class="formContainer" id="formContainer" method="get">
                        <select name="position" id="position">
                            <?php
                            $positionOptions = array("All Positions","Employee", "Intern");
                            
                            foreach ($positionOptions as $position) {
                                $selected = ($positionFilter == $position) ? 'selected' : '';
                                echo '<option value="' . $position . '" ' . $selected . '>' . $position . '</option>';
                            }
                            ?>
                        </select>

                        <input type="text" name="name" class="nameSearch" id="nameSearch" placeholder=" Name Search"/>
                        
                        <input class="filterButton" type="submit"/>
                    </form>
                </div>
                
                <?php
                if (mysqli_num_rows($dtr_result) == 0) {
                    echo '<p class="emptyTable" id="emptyTable"> No record available </>';
                } else { ?>
                    <div class="tableContainer" id="tableContainer">
                        <table id="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Email Address</th>
                                    <th>Password</th>
                                    <th>Supervisor</th>
                                    <th>Profile</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_assoc($dtr_result)) {
                                    echo '<tr>';
                                        echo '<td class="name">' . $row['name'] . '</td>';
                                        echo '<td>' . $row['position'] . '</td>';
                                        echo '<td style="text-transform: none;">' . $row['email'] . '</td>';
                                        echo '<td>' . $row['password'] . '</td>';
                                        echo (!empty($row['supervisor_name'])) ? 
                                        '<td>' . $row['supervisor_name'] . '</td>' :  '<td style="background-color: #c9c9c9"></td>';
                                        ?>

                                        <form action="attendance-acc-view.php" method="POST" id="view-acc-form-<?php echo $row['name']; ?>">
                                                <!-- HIDDEN INPUTS -->
                                                <input value="<?php echo $row['name']; ?>" type="hidden" name="viewName">
                                                <input value="<?php echo $row['email']; ?>" type="hidden" name="viewEmail">
                                                <input value="<?php echo $row['password']; ?>" type="hidden" name="viewPassword">
                                                <input value="<?php echo $row['role']; ?>" type="hidden" name="viewRole">
                                                <input value="<?php echo $row['position']; ?>" type="hidden" name="viewPosition">
                                                <input value="<?php echo $row['department']; ?>" type="hidden" name="viewDepartment">
                                                <input value="<?php echo $row['supervisor_name']; ?>" type="hidden" name="viewSupervisor">
                                                <input value="<?php echo $row['address']; ?>" type="hidden" name="viewAddress">
                                                <input value="<?php echo $row['age']; ?>" type="hidden" name="viewAge">
                                                <input value="<?php echo $row['gender']; ?>" type="hidden" name="viewGender">
                                                <input value="<?php echo $row['start_date']; ?>" type="hidden" name="viewStartdate">
                                                <input value="<?php echo $row['school']; ?>" type="hidden" name="viewSchool">
                                                <input value="<?php echo $row['image']; ?>" type="hidden" name="viewImage">
                                                
                                                <td>
                                                    <input type="submit" class="functionButton" value="View"
                                                    form="view-acc-form-<?php echo $row['name']; ?>">
                                                </td>
                                        </form> 

                                        <?php
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> 

                <?php
                }
                ?>
    
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