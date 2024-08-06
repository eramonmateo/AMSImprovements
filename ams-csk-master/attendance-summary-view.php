<?php 
    
    /// JHERIMY B. ///

    session_start();

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include 'connects.php';

    $page = 'attendance-summary-view';
    $tab = 'attendance';
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
        
        $yearFilter = isset($_GET['year']) ? $_GET['year'] : '';
        $nameFilter = isset($_GET['name']) ? $_GET['name'] : '';
        $departmentFilter = isset($_GET['department']) ? $_GET['department'] : '';
        $monthFilter = isset($_GET['month']) ? $_GET['month'] : '';

        $yearChoice = (empty($yearFilter)) ? date('Y') : $yearFilter;
        $monthChoice = (empty($monthFilter)) ? date('m') : $monthFilter;

        $filterDate = "$yearChoice-$monthChoice";

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('n', strtotime($filterDate)), date('Y', strtotime($filterDate)));

        $sql = "SELECT f.name, f.position, f.department, f.time_in, f.time_out, f.date_in, f.date_out,  
                GROUP_CONCAT(CONCAT(l.startdate, ':', l.enddate)) AS start_end, 
                sc.monday, sc.tuesday, sc.wednesday, sc.thursday, sc.friday, sc.saturday, sc.sunday,
                GROUP_CONCAT(l.type) AS type_leave 
                FROM (SELECT t.name, u.position, COALESCE(ii.department, ei.department) AS department, GROUP_CONCAT(TIME(t.datetime)) AS time_in, GROUP_CONCAT(TIME(o.datetime)) AS time_out, GROUP_CONCAT(DATE(o.datetime)) AS date_out,  GROUP_CONCAT(DATE(t.datetime)) AS date_in
                    FROM time_in t 
                    LEFT JOIN time_out o ON t.token = o.token
                    JOIN users u ON t.name = u.name
                    LEFT JOIN emp_info ei ON t.name = ei.name
                    LEFT JOIN int_info ii ON t.name = ii.name GROUP BY name ORDER BY name ASC) AS f
                    LEFT JOIN filed_leaves l ON f.name = l.name AND l.status = 'Approved'
                    LEFT JOIN schedule sc ON f.name = sc.name";
        
        $whereClause = "";

        if (!empty($departmentFilter)) {
            $whereClause .= "(f.department = '$departmentFilter')";
        }

        if (!empty($nameFilter)) {
            if (!empty($whereClause)) {
                $whereClause .= " AND ";
            }
            $whereClause .= "(f.name LIKE '%$nameFilter%')";
        }
        
    
        if (!empty($whereClause)) {
            $sql .= " WHERE " . $whereClause;
        }

        $sql .= " GROUP BY name ORDER BY name ASC ";
        
        $result = mysqli_query($conn, $sql);
    
        if (!$result) {
            // Query execution failed, display the error message and terminate the script
            die('Error: ' . mysqli_error($conn));
        }

        $yearOptions = array();
        for ($year = 2019; $year <= date("Y"); $year++) {
            $yearOptions[] = "$year";
        }

        $departmentOptions = array('IT', 'HR', 'Accounting', 'Marketing', 'Admin');
        
        $monthOptions = 
        array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');    

        /// Check if Time In/ Time out is not a Time
        $leaveOptions = array('PL', 'SIL', 'EL', 'SL', 'BL', 'VL');
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
        <link rel="stylesheet" href="css/attendance-summary-view.css">
        <title>AMS | ATTENDANCE | SUMMARY VIEW</title>
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

                        <select name="year" id="year">
                            <option value="">Select Year</option>
                            <?php
                            foreach ($yearOptions as $option) {
                                $selected = ($yearFilter == $option) ? 'selected' : '';
                                echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                            }
                            ?> 
                        </select>

                        <select name="month" id="month">
                            <option value="">Select Month</option>
                            <?php   
                            foreach ($monthOptions as $option) {
                                $selected = ($monthFilter == $option) ? 'selected' : '';
                                echo '<option value="' . date('m', strtotime($option)) . '" ' . $selected . '>' . $option . '</option>';
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

                        <input type="text" name="name" class="nameSearch" id="nameSearch" placeholder=" Name Search"/>
                        
                        <input class="filterButton" type="submit"/>
                    </form>
                </div>
                
                <?php
                if (mysqli_num_rows($result) == 0) {
                    echo '<p class="emptyTable" id="emptyTable"> No record available </>';
                } else { ?>
                    <div class="tableContainer" id="tableContainer">
                        <table id="table">
                            <thead>
                                <tr>
                                    <th colspan="3" style="background-color: transparent;"></th>
                                    <?php for ($i = 0; $i < $daysInMonth; $i++) {
                                        echo '<th colspan=2:>'. $filterDate . "-" . sprintf("%02d", $i+1) . '</th>';
                                    } ?>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <?php for ($i = 0; $i < $daysInMonth; $i++) {
                                        echo '<th style="width: 5vh":>IN</th>';
                                        echo '<th style="width: 5vh":>OUT</th>';
                                    } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_assoc($result)) {

                                    $date_in = explode(',', $row['date_in']);
                                    $time_in = explode(',', $row['time_in']);

                                    $time_out = explode(',', $row['time_out']);
                                    $date_out = explode(',', $row['date_out']);

                                    if (isset($row['start_end'])) {  
                                        $start_end = explode(',', $row['start_end']);
                                        $type_leave = explode(',', $row['type_leave']);

                                        foreach($start_end as $leave) {
                                            $leave_date = explode(':', $leave); // Split the string into two date strings4
                                            $leave_start = new DateTime($leave_date[0]);
                                            $leave_end = new DateTime($leave_date[1]);
                                            $leave_end->modify("+1 day");

                                            $interval = new DateInterval('P1D'); // 1 day interval
                                            $range = new DatePeriod($leave_start, $interval, $leave_end);

                                            $array = [];
                                            array_reverse($array);
                                            foreach ($range as $date) {
                                                $array[] = $date->format("Y-m-d");
                                            }

                                            foreach ($array as $date) {
                                                $date_in[] = $date;
                                                $date_out[] = $date;
                                                $time_in[] = $type_leave[array_search($leave, $start_end)];
                                                $time_out[] = $type_leave[array_search($leave, $start_end)];
                                            }
                                        }
                                    }   

                                    /// Removes the repeated time in and left the first one.
                                    $repeatedIndicesIn = [];
                                    $repeatedElementsIn = [];
                                    for ($i = 0; $i < count($date_in); $i++) {
                                        if (!in_array($date_in[$i], $repeatedElementsIn)) {
                                            $repeatedElementsIn[] = $date_in[$i];
                                        } else {
                                            $repeatedIndicesIn[] = array_search($date_in[$i], $date_in);
                                        }
                                    }
                                    foreach ($repeatedIndicesIn as $index) {
                                        unset($date_in[$index]);
                                        unset($time_in[$index]);
                                    }

                                    /// Removes the repeated time out and left the first one.
                                    $repeatedIndicesOut = [];
                                    $repeatedElementsOut = [];
                                    for ($i = 0; $i < count($date_out); $i++) {
                                        if (!in_array($date_out[$i], $repeatedElementsOut)) {
                                            $repeatedElementsOut[] = $date_out[$i];
                                        } else {
                                            $repeatedIndicesOut[] = array_search($date_out[$i], $date_out);
                                        }
                                    }
                                    foreach ($repeatedIndicesOut as $index) {
                                        unset($date_out[$index]);
                                        unset($time_out[$index]);
                                    }
                                              
                                    
                                    echo '<tr>';
                                        echo '<td class="name">' . $row['name'] . '</td>';
                                        echo '<td>' . $row['department'] . '</td>';
                                        echo '<td>' . $row['position'] . '</td>';
                                        
                                        for ($count = 0; $count < $daysInMonth; $count++) {
                                            $days = sprintf("%02d", $count + 1);
                                            $date = "$filterDate-$days";
                                            $day = strtolower(date('l', strtotime($date)));
                                            $day_array = explode(" ", $row[$day]);
                                        
                                            if (in_array($date, $date_in)) {
                                                $index = array_search($date, $date_in);
                                                $scheduled_time_in = strtotime($day_array[0]); // Scheduled time
                                                $actual_time_in = strtotime($time_in[$index]); // Actual time
                                        
                                                if (in_array($time_in[$index], $leaveOptions)) {
                                                    echo '<td style="width: 5vh;">' . $time_in[$index] . '</td>'; // HERE
                                                } else {
                                                    if ($actual_time_in > $scheduled_time_in) {
                                                        echo '<td style="width: 5vh; background-color: #f75757;"></td>';
                                                    } else {
                                                        echo '<td style="width: 5vh; background-color: #6aff66;"></td>';
                                                    }
                                                }
                                            } else {
                                                echo '<td></td>';
                                            }
                                        
                                            if (in_array($date, $date_out)) {
                                                $index = array_search($date, $date_out);
                                                $scheduled_time_out = strtotime($day_array[1]) + 1800; // Scheduled time // HERE
                                                $actual_time_out = strtotime($time_out[$index]); // Actual time
                                        
                                                if (in_array($time_out[$index], $leaveOptions)) {
                                                    echo '<td style="width: 5vh;">' . $time_out[$index] . '</td>';
                                                } else {
                                                    if ($actual_time_out > $scheduled_time_out) {
                                                        echo '<td style="width: 5vh; background-color: #f75757;"></td>';
                                                    } else {
                                                        echo '<td style="width: 5vh; background-color: #6aff66;"></td>';
                                                    }
                                                }
                                            } else {
                                                echo '<td></td>';
                                            }
                                        }
                                        echo '</tr>';
                                        
                                } ?>
                            </tbody>
                        </table>
                    </div> 
                    <button id="export" class="export" onclick="excel()" >Export as Excel File</button>
                <?php 
                } ?>  
    
            </main>   
        </section>
        
        <script>
            function closeSideBar() {
                sidebar.classList.toggle('hide');
                section.classList.toggle('active');
            }

            function excel() {
                exportTable()
            }
            
            </script>

        </script>
    
        <script src="js/Dashboard.js"></script>
        <script src="js/summaryView.js"></script>
        <script src="js/navDropdown.js"></script>
        <script src="js/export.js"> </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
        <script src="js/date-time.js"></script>
       
    
    </body>
    </html>
