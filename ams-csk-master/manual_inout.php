<?php session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include 'connects.php';

$page = 'manual_inout';
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
    $date = $row['start_date'];
    $formatted_date = date('D, M d, Y', strtotime($date));
    $result_text = "<h1>Name: " . 
    $row['name'] . "<br>Department: " . 
    $row['department'] . "<br>Position: " . 
    $row['position'] . "<br>Start Date: " . 
    $formatted_date;

    if($position == "intern") {
        $result_text .= "<br>Hours Required: " . 
        $row['hr_req'] . "<br>Hours Rendered: " . 
        $row['hr_ren'] . "<br>Hours Left: " . 
        $row['hr_left'];
    }
    
    
$positionFilter = isset($_GET['position']) ? $_GET['position'] :'';
$departmentFilter = isset($_GET['department']) ? $_GET['department'] : '';
$filterDate = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';

// Check if the $filterDate variable is empty
if (empty($filterDate)) {
    $filterDate = date('Y-m-d'); // Use the current date as the default filter date
}

if ($positionFilter === 'All Positions') {
    $dtr_sql = "SELECT u.name, COALESCE(ii.department, ei.department) AS department, TIME(t.datetime) AS time_in, TIME(o.datetime) AS time_out
                FROM users u
                LEFT JOIN int_info ii ON u.name = ii.name
                LEFT JOIN emp_info ei ON u.name = ei.name
                LEFT JOIN time_in t ON u.name = t.name AND DATE(t.datetime) = '$filterDate'
                LEFT JOIN time_out o ON u.name = o.name AND DATE(o.datetime) = '$filterDate'";
    
    if (!empty($departmentFilter)) {
        $dtr_sql .= " WHERE ii.department = '$departmentFilter' OR ei.department = '$departmentFilter'";
    }
} elseif ($positionFilter === 'Intern') {
    $dtr_sql = "SELECT u.name, ii.department, TIME(t.datetime) AS time_in, TIME(o.datetime) AS time_out
                FROM users u
                JOIN int_info ii ON u.name = ii.name
                LEFT JOIN time_in t ON u.name = t.name AND DATE(t.datetime) = '$filterDate'
                LEFT JOIN time_out o ON u.name = o.name AND DATE(o.datetime) = '$filterDate'";
    
    if (!empty($departmentFilter)) {
        $dtr_sql .= " WHERE ii.department = '$departmentFilter'";
    }
} elseif ($positionFilter === 'Employee') {
    $dtr_sql = "SELECT u.name, ei.department, TIME(t.datetime) AS time_in, TIME(o.datetime) AS time_out
                FROM users u
                JOIN emp_info ei ON u.name = ei.name
                LEFT JOIN time_in t ON u.name = t.name AND DATE(t.datetime) = '$filterDate'
                LEFT JOIN time_out o ON u.name = o.name AND DATE(o.datetime) = '$filterDate'";
    
    if (!empty($departmentFilter)) {
        $dtr_sql .= " WHERE ei.department = '$departmentFilter'";
        
        // Check if the selected department has employees
        $check_department_sql = "SELECT COUNT(*) FROM emp_info WHERE department = '$departmentFilter'";
        $department_result = mysqli_query($conn, $check_department_sql);
        $department_count = mysqli_fetch_row($department_result)[0];
        
        if ($department_count == 0) {
            // Show no results if the selected department has no employees
            $dtr_sql .= " AND 1 = 0";
        }
    }
} else {
    // Show all employees and departments when no specific filters selected
    $dtr_sql = "SELECT u.name, COALESCE(ii.department, ei.department) AS department, TIME(t.datetime) AS time_in, TIME(o.datetime) AS time_out
                FROM users u
                LEFT JOIN int_info ii ON u.name = ii.name
                LEFT JOIN emp_info ei ON u.name = ei.name
                LEFT JOIN time_in t ON u.name = t.name AND DATE(t.datetime) = '$filterDate'
                LEFT JOIN time_out o ON u.name = o.name AND DATE(o.datetime) = '$filterDate'";
}


$dtr_result = mysqli_query($conn, $dtr_sql);

if (!$dtr_result) {
    // Query execution failed, display the error message and terminate the script
    die('Error: ' . mysqli_error($conn));
}

$departmentOptions = array('IT', 'HR', 'Accounting', 'Marketing', 'Admin');

?>


<script>
    function formatTime(date) {
      var hours = date.getHours();
      var minutes = date.getMinutes();
      var seconds = date.getSeconds();
      var ampm = hours >= 12 ? 'PM' : 'AM';

      hours = hours % 12;
      hours = hours ? hours : 12;
      hours = hours.toString().padStart(2, '0');
      minutes = minutes.toString().padStart(2, '0');
      seconds = seconds.toString().padStart(2, '0');

      return hours + ":" + minutes + ":" + seconds + ' ' + ampm;
    }

    function formatDate(date) {
      const options = { month: 'long', day: 'numeric', year: 'numeric' };
      return date.toLocaleDateString(undefined, options);
    }

    

</script>


<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    

   
	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->

	<link rel="stylesheet" href="css/dtr_view.css">

	<title>AMS | Employee and Intern Management</title>
</head>
<body>
	<!-- SIDEBAR -->
    <?php include_once 'sidebar.php'; ?>


	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>

			<h2><?php

            $userDisplay = explode(" ", $_SESSION['username']);
            echo (count($userDisplay) > 2) ?  
            $userDisplay[0] . " " . $userDisplay[count($userDisplay) - 1] : $userDisplay[0];
            echo " | ";
            echo "AMS Admin";
            ?> <br><?php
            $positionDisplay = explode(" ", $row['position']);
            echo (count($positionDisplay) > 2) ?  
            $positionDisplay[0] . " " . $positionDisplay[count($positionDisplay) - 1] : $positionDisplay[0];
            echo " | ";
            echo $row['department'];
            ?></h2>

			<li>
				<a href="logout.php" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</nav>



		<!-- MAIN -->
		<main>
			<div class="input-box">
				<ul class="box-info">
					<div class="input-field">
						<div class="date-time">

							<h1>Current Time and Date: <span id="live-time"></span></h1>
						</div>
					</div>
				</ul>
			</div>
            <div class="head-title">
            <div class="left">
					<h1> </h1>
                    <style>
                    h1 {
                        margin: 0 0;
                        padding: 20px;
                    }
                </style>
				</div>
			</div>

<form class="filter" method="get">
    <div class="filter-department">
    <label for="position">Position:</label>
        <select name="position" id="position">
            <?php
            $positionOptions = array("All Positions","Employee", "Intern");
            
            foreach ($positionOptions as $position) {
                $selected = ($positionFilter == $position) ? 'selected' : '';
                echo '<option value="' . $position . '" ' . $selected . '>' . $position . '</option>';
            }
            ?>
        </select>


        <label for="department">Department:</label>
        <select name="department" id="department">
            <option value="">All Departments</option>
            <?php
            foreach ($departmentOptions as $option) {
                $selected = ($departmentFilter == $option) ? 'selected' : '';
                echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
            }
            ?>
        </select>

        <label for="filter_date">Filter Date:</label>
        <input type="date" name="filter_date" id="filter_date" value="<?php echo isset($_GET['filter_date']) ? $_GET['filter_date'] : ''; ?>">
        <button class="filter-btn3" type="submit">Apply  Filter</button>
    </div>


</form>


<div class="tg-wrap">
    <table style="width: 100%" class="tg">
        <tbody>
            <tr>
                <th class="tg-0pky">Name</th>
                <th class="tg-0pky">Department</th>
                <th class="tg-0pky">Date</th>
                <th class="tg-0pky">Time in</th>
                <th class="tg-0pky">Time out</th>
                <th class="tg-0pky">Save</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($dtr_result)) {
                echo '<tr>';
                echo '<td class="tg-0pky">' . $row['name'] . '</td>';
                echo '<td class="tg-0pky">' . $row['department'] . '</td>';
                echo '<td class="tg-0pky">' . $filterDate . '</td>';
                echo '<td class="tg-0pky"><input type="time" id="time_in_' . $row['name'] . '" value="' . substr($row['time_in'], 0, 5) . '"></td>';
                echo '<td class="tg-0pky"><input type="time" id="time_out_' . $row['name'] . '" value="' . substr($row['time_out'], 0, 5) . '"></td>';

                // button to call the JavaScript function with row-specific parameters
                echo '<td class="tg-0pky"><button class="filter-btn4" onclick="saveTime(\'' . $row['name'] . '\', \'' . $filterDate . '\', \'' . $row['department'] . '\')">Save</button></td>';

                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>




<!-- end of table div -->





	<!-- CONTENT -->
	<script src="js/Dashboard.js"></script>
    <script src="js/navDropdown.js"></script>
    <script src="js/summaryView.js"></script>

    <script>
    
    function formatDate(dateNow) {
    const options = { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' };
    return dateNow.toLocaleDateString(undefined, options);
  }

        function updateTime() {
    var dateNow = new Date();
    var hours = dateNow.getHours();
    var minutes = dateNow.getMinutes();
    var seconds = dateNow.getSeconds();
    var ampm = hours >= 12 ? 'PM' : 'AM';

    hours = hours % 12;
    hours = hours ? hours : 12;

    hours = hours.toString().padStart(2, '0');
    minutes = minutes.toString().padStart(2, '0');
    seconds = seconds.toString().padStart(2, '0');

    var currentDate = formatDate(dateNow);
    document.getElementById("live-time").textContent = hours + ":" + minutes + ":" + seconds + ' ' + ampm + " | " + currentDate;
  }

//   updateTime(); // Call the function initially to display the time immediately
//   setInterval(updateTime, 1000); // Call the function every second to update the time
  </script>
    

</body>
</html>