<?php 
    
    /// JHERIMY B. ///

    session_start();

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include 'connects.php';

    $page = 'dtr';
    $tab = 'attendance';
    include_once('non-admin-sidebar.php');

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

    $filterDate = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';

    // Check if the $filterDate variable is empty
   
    $dtr_sql = "SELECT TIME(t.datetime) AS time_in, TIME(o.datetime) AS time_out, o.hours, o.overtime, COALESCE(DATE(t.datetime), DATE(o.datetime)) AS work_days
                FROM time_in t
                LEFT JOIN time_out o ON t.token = o.token
                WHERE t.name='$name' AND o.name='$name'";

    if (!empty($filterDate)) {
        $dtr_sql .= " AND (DATE(t.datetime)='$filterDate' OR DATE(o.datetime)='$filterDate')";
    }

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
	<link rel="stylesheet" href="css/attendance-dtr.css">
	<title>AMS | ATTENDANCE | DAILY TIME RECORD</title>

    <script>
        // Create a JavaScript variable to store the user information
        var userInfo = {
            name: <?php echo json_encode($row['name']); ?>,
            position: <?php echo json_encode($row['position']); ?>,
            department: <?php echo json_encode($row['department']); ?>
        };

        function excel() {
        // Acess the user information from the "userInfo" variable
        const name = userInfo.name;
        const position = userInfo.position;
        const department = userInfo.department;

        console.log(`Name: ${name}`);
        console.log(`Position: ${position}`);
        console.log(`Department: ${department}`);

        // Call the exportDTR() function 
        exportDTR(name, position, department);
        }
    </script>
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
                    <input type="date" name="filter_date" id="filterDate" value="
                    <?php echo isset($_GET['filter_date']) ? $_GET['filter_date'] : ''; ?>">
                    
                    <input class="filterButton" type="submit"/>
                </form>
            </div>

            <?php
                if (mysqli_num_rows($dtr_result) == 0) {
                    echo '<p class="emptyTable"> No record available </>';
                } else { ?>
                    <div class="tableContainer">
                            <table id="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Hours</th>
                                    <th>Overtime</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_assoc($dtr_result)) {
                                    echo '<tr>';
                                        echo '<td>' . date("F j, Y", strtotime($row['work_days'])) . '</td>';
                                        echo '<td>' . date("g:i A", strtotime($row['time_in'])) . '</td>';
                                        if (!empty($row['time_out'])) {
                                            echo '<td>' . date("g:i A", strtotime($row['time_out']))  . '</td>';
                                        } else {
                                            echo '<td style="background-color: #c9c9c9"></td>';
                                        }
                                        echo '<td>' . $row['hours'] . '</td>';
                                        echo '<td>' . $row['overtime'] . '</td>';
                                    echo '</tr>';
                                }
                        ?>
                            </tbody>
                        </table>
                    </div> 
          
                    <button id="export" class="export" onclick="excel()">Export as Excel File</button>
          
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
    <script src="js/date-time.js"></script>
    <script src = "js/export.js"></script>

</body>
</html>