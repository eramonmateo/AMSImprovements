<?php 

    /// JHERIMY B. ///

    session_start();

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include 'connects.php';

    $page = 'view-timein-timeout';
    $tab = 'admin';
    include_once('sidebar.php');

    if(isset($_SESSION['username'])) {
    //do nothing
    } else {
    header('Location: index.php');
    exit;
    }
    
    if(isset($_GET['token'])) {
        $id = $_GET['token'];
        $date = date('Y-m-d');
        $sql = "DELETE FROM time_in WHERE token='$id'";
        $sql2 = "DELETE FROM time_out WHERE token='$id'";

        $del1=mysqli_query($conn, $sql);
        $del2=mysqli_query($conn, $sql2);
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
    
    $positionFilter = isset($_GET['position']) ? $_GET['position'] :'';
    $departmentFilter = isset($_GET['department']) ? $_GET['department'] : '';
    $filterDate = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';

    // Check if the $filterDate variable is empty
    if (empty($filterDate)) {
        $filterDate = date('Y-m-d'); // Use the current date as the default filter date
    }
    if ($positionFilter === 'All Positions') {
        $dtr_sql = "SELECT u.name, u.position, COALESCE(ii.department, ei.department) AS department, TIME(t.datetime) AS time_in, TIME(o.datetime) AS time_out, o.hours, o.overtime, COALESCE(ei.start_date, ii.start_date) AS start_date, ii.hr_req, ii.hr_ren, ii.hr_left, t.token
                    FROM users u
                    LEFT JOIN int_info ii ON u.name = ii.name
                    LEFT JOIN emp_info ei ON u.name = ei.name
                    LEFT JOIN time_in t ON u.name = t.name AND DATE(t.datetime) = '$filterDate'
                    LEFT JOIN time_out o ON u.name = o.name AND DATE(o.datetime) = '$filterDate'";
        
        $whereClause = "";

        if (!empty($departmentFilter)) {
            $whereClause .= "(ii.department = '$departmentFilter' OR ei.department = '$departmentFilter')";
        }
    
        if (!empty($whereClause)) {
            $dtr_sql .= " WHERE " . $whereClause;
        }
        
    } elseif ($positionFilter === 'Intern') {
        $dtr_sql = "SELECT u.name, u.position, ii.department, TIME(t.datetime) AS time_in, TIME(o.datetime) AS time_out, o.hours, o.overtime, ii.start_date, ii.hr_req, ii.hr_ren, ii.hr_left, t.token
                    FROM users u
                    JOIN int_info ii ON u.name = ii.name
                    LEFT JOIN time_in t ON u.name = t.name AND DATE(t.datetime) = '$filterDate'
                    LEFT JOIN time_out o ON u.name = o.name AND DATE(o.datetime) = '$filterDate'";
        
        $whereClause = "";

        if (!empty($departmentFilter)) {
            $whereClause .= "ii.department = '$departmentFilter'";
        }

        if (!empty($whereClause)) {
            $dtr_sql .= " WHERE " . $whereClause;
        }

    } elseif ($positionFilter === 'Employee') {
        $dtr_sql = "SELECT u.name, u.position, ei.department, TIME(t.datetime) AS time_in, TIME(o.datetime) AS time_out, o.hours, o.overtime, ei.start_date, t.token
                    FROM users u
                    JOIN emp_info ei ON u.name = ei.name
                    LEFT JOIN time_in t ON u.name = t.name AND DATE(t.datetime) = '$filterDate'
                    LEFT JOIN time_out o ON u.name = o.name AND DATE(o.datetime) = '$filterDate'";
        
        $whereClause = "";

        if (!empty($departmentFilter)) {
            $whereClause .= "ei.department = '$departmentFilter'";
            
            // Check if the selected department has employees
            $check_department_sql = "SELECT COUNT(*) FROM emp_info WHERE department = '$departmentFilter'";
            $department_result = mysqli_query($conn, $check_department_sql);
            $department_count = mysqli_fetch_row($department_result)[0];
            
            if ($department_count == 0) {
                // Show no results if the selected department has no employees
                $dtr_sql .= " AND 1 = 0";
            }
        }

        if (!empty($whereClause)) {
            $dtr_sql .= " WHERE " . $whereClause;
        }

    } else {
        // Show all employees and departments when no specific filters selected
        $dtr_sql = "SELECT u.name, u.position, COALESCE(ii.department, ei.department) AS department, TIME(t.datetime) AS time_in, TIME(o.datetime) AS time_out, o.hours, o.overtime, COALESCE(ei.start_date, ii.start_date) AS start_date, ii.hr_req, ii.hr_ren, ii.hr_left, t.token
                    FROM users u
                    LEFT JOIN int_info ii ON u.name = ii.name
                    LEFT JOIN emp_info ei ON u.name = ei.name
                    LEFT JOIN time_in t ON u.name = t.name AND DATE(t.datetime) = '$filterDate'
                    LEFT JOIN time_out o ON u.name = o.name AND DATE(o.datetime) = '$filterDate'";
    }
    
    $dtr_sql .= " ORDER BY name ASC";

$dtr_result = mysqli_query($conn, $dtr_sql);

if (!$dtr_result) {
    // Query execution failed, display the error message and terminate the script
    die('Error: ' . mysqli_error($conn));
}

$departmentOptions = array('IT', 'HR', 'Accounting', 'Marketing', 'Admin');

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
	<link rel="stylesheet" href="css/view-timein-timeout.css">
	<title>AMS | VIEW TIME IN AND TIME OUT</title>
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

            <div class="filterContainer">
                <form class="formContainer" method="get">
                    <select name="position" id="position">
                        <?php
                        $positionOptions = array("All Positions","Employee", "Intern");
                        
                        foreach ($positionOptions as $position) {
                            $selected = ($positionFilter == $position) ? 'selected' : '';
                            echo '<option value="' . $position . '" ' . $selected . '>' . $position . '</option>';
                        }
                        ?>
                    </select>

                    <select name="department" id="department">
                        <option value="">All Departments</option>
                        <?php
                        foreach ($departmentOptions as $option) {
                            $selected = ($departmentFilter == $option) ? 'selected' : '';
                            echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                        }
                            ?>
                    </select>

                    <input type="date" name="filter_date" id="filterDate" value="
                    <?php echo isset($_GET['filter_date']) ? $_GET['filter_date'] : ''; ?>">
                    
                    <input class="filterButton" type="submit"/>
                </form>
            </div>


            <div class="tableContainer">
                <table id="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Starting Date</th>
                            <th>Time in</th>
                            <th>Time out</th>
                            <th>Hours</th>
                            <th>Required</th>
                            <th>Rendered</th>
                            <th>Left</th>
                            <th>Overtime</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($dtr_result)) {
                            echo '<tr>';
                                echo '<td>' . $row['name'] . '</td>';
                                echo '<td>' . $row['department'] . '</td>';
                                echo '<td>' . $row['position'] . '</td>';
                                echo '<td>' . $row['start_date'] . '</td>';
                                echo '<td>' . substr($row['time_in'], 0, 5) . '</td>';
                                echo '<td>' . substr($row['time_out'], 0, 5) . '</td>';
                                echo '<td>' . $row['hours'] . '</td>';
                                if ($row['position'] == "intern") {
                                    echo '<td>' . $row['hr_req'] . '</td>';
                                    echo '<td>' . $row['hr_ren'] . '</td>';
                                    echo '<td>' . $row['hr_left'] . '</td>';
                                } else {
                                    echo '<td style="background-color: #c9c9c9"></td>';
                                    echo '<td style="background-color: #c9c9c9"></td>';
                                    echo '<td style="background-color: #c9c9c9"></td>';
                                }
                                echo '<td>' . $row['overtime'] . '</td>';
                                echo '
                                <td>
                                    <a href="view-timein-timeout.php?token=' . $row['token'] . '">Delete</a> 
                                </td>
                                ';  

                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div> 
            
            <button id="export" class="export" onclick="excel()">Export as Excel File</button>
            
        </main>


    </section>

    <script>
        function closeSideBar() {
            sidebar.classList.toggle('hide');
            section.classList.toggle('active');
        }

        function excel() {
            exportTable_gian()
        }

    </script>

        <script src="js/Dashboard.js"></script>
        <script src="js/summaryView.js"></script>
        <script src="js/navDropdown.js"></script>
        <script src="js/export.js"> </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
        <script src="js/date-time.js"></script>

</body>
</html>