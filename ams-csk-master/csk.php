<?php
	session_start();
	header("Cache-Control: no-cache, no-store, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");


	include "connects.php";
	include "access_control.php";

	

	if (!isset($_SESSION['username'])) {
		header('Location: login.php');
		exit();
	}

	$name = $_SESSION['username'];
    $position = $_SESSION['position'];

	$page = 'csk';
    $tab = 'switch_csk';
	if($position == "admin") {
		include_once('sidebar.php');
	}else{
		include_once('non-admin-sidebar.php');
	} 	

    if($position == "employee") {
        $query = "SELECT name, department, position, start_date FROM emp_info WHERE name='$name'";
    } else {
        $query = "SELECT name, department, position, start_date, hr_req, hr_ren, hr_left FROM int_info WHERE name='$name'";
    }
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $date = 
    $row['start_date'];
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
	
    mysqli_close($conn);
	
?>
<!-- <php
  include 'connects.php';
	$page = 'csk';
    $tab = 'switch_csk';
	if($position == "admin") {
		include_once('sidebar.php');
	}else{
		include_once('intern_sidebar.php');
	}
    


    if(isset($_SESSION['username'])) {
    //do nothing
    } else {
       header('Location: default.php');
       exit;
    }

	$name = $_SESSION['username'];
    $position = $_SESSION['position'];


	if (!isset($_SESSION['username'])) {
		header('Location: login.php');
		exit();
	}

	$name = $_SESSION['username'];
    $position = $_SESSION['position'];

    if($position == "employee") {
        $query = "SELECT name, department, position, start_date, work_days, work_hrs FROM emp_info WHERE name='$name'";
    } else {
        $query = "SELECT name, department, position, start_date, hr_req, hr_ren, hr_left FROM int_info WHERE name='$name'";
    }
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $date = 
    $row['start_date'];
    $formatted_date = date('D, M d, Y', strtotime($date));
    $result_text = "<h1>Name: " . 
    $row['name'] . "<br>Department: " . 
    $row['department'] . "<br>Position: " . 
    $row['position'] . "<br>Start Date: " . 
    $formatted_date . "<br>Work Days: " . 
    $row['work_days'] . "<br>Work Hours: " . 
    $row['work_hrs'];

    if($position == "intern") {
        $result_text .= "<br>Hours Required: " . 
        $row['hr_req'] . "<br>Hours Rendered: " . 
        $row['hr_ren'] . "<br>Hours Left: " . 
        $row['hr_left'];
    }
	
    mysqli_close($conn);
?> -->




<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Boxicons -->
		<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
		<!-- My CSS -->

		<link rel="stylesheet"href="css/csk.css">
		<!-- <link rel="stylesheet"href="css/csk-edit.css"> -->
		<link rel="stylesheet"href="css/Dashboard.css">

		<title>AMS | CSK</title>

	</head>
	<body>
		<!-- SIDEBAR
		<section id="sidebar">
			<a href="admin_dash.php" class="brand">
				<img src="images/CSK Logo.png" alt="" class="logo">
				<span class="text">CSK ORGANIZATION</span>
			</a>
			<ul class="side-menu top">
			<li>
				<a href="reg_dash.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="attendances.php">
					<i class='bx bxs-calendar-check'></i>
					<span class="text">Attendance</span>
				</a>
			</li>

			<li>
				<a href="redirect_emp_int.php">
					<i class='bx bxs-calendar-x'></i>
					<span class="text">Request Leave/Overtime</span>
				</a>
			</li>
			<li class="active">
				<a href="csk.php">
				<i class='bx bx-group'></i>
					<span class="text">CSK</span>
				</a>
			</li>
		</ul>
		</section> -->
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
				echo "AMS Regular";
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
							<h1>Current Time and Date: <br> <span id="live-time"></span></h1>
						</div>
					</div>
				</ul>
			</div><br>

			<div class="creation">
				<ul class="creation-info">
					<div class="creation-field">
                        <text>Chrisimm Sentimo Kumon Corp. Management</text>
					</div>
				</ul>
			</div ><br><br>

			
		
            <div class="org-chart">

      
					<center><img src="images/csk-org-update.png" alt="CSK Org Chart"></center>

			</div>
		</div>

			<div class="box">

				<?php
				/*
				$dbServername = "localhost";
				$dbUsername = "u879258463_csk";
				$dbPassword = "@ttEndAnc3";
				$dbName = "u879258463_attendancesys";
				$conn = mysqli_connect($dbServername,$dbUsername, $dbPassword, $dbName);
				*/
				
				include 'connects.php';
				?>

				<h1>Administration <br> Department</h1>
				<p>
					<?php
						 
						$sql = "SELECT * FROM int_info WHERE department = 'Admin';";
						$result = mysqli_query($conn, $sql);
						$resultCheck = mysqli_num_rows($result);

						if ($resultCheck > 0) {
							while ($row = mysqli_fetch_assoc ($result)) {
								echo $row['name'] . "<br>";
							}
						}

					?>
				</p>
			</div>


			<div class="box">
				<h1>Human Resources <br> Department</h1>
				<p>
				<?php
						 
						 $sql = "SELECT * FROM int_info WHERE department = 'HR';";
						 $result = mysqli_query($conn, $sql);
						 $resultCheck = mysqli_num_rows($result);
 
						 if ($resultCheck > 0) {
							 while ($row = mysqli_fetch_assoc ($result)) {
								 echo $row['name'] . "<br>";
							 }
						 }
 
					 ?>
				</p>
				</div>
			
			<div class="box">
				<h1>Marketing Department</h1>
				<p>
				<?php
						 
						 $sql = "SELECT * FROM int_info WHERE department = 'Marketing';";
						 $result = mysqli_query($conn, $sql);
						 $resultCheck = mysqli_num_rows($result);
 
						 if ($resultCheck > 0) {
							 while ($row = mysqli_fetch_assoc ($result)) {
								 echo $row['name'] . "<br>";
							 }
						 }
 
					 ?>
				</p>
			</div>
			<br>


		<div class="interns">

		<div class="box">
				<h1>IT Department</h1>
				<p>
					<?php
				
						$sql = "SELECT * FROM int_info WHERE department = 'IT';";
						$result = mysqli_query($conn, $sql);
						$resultCheck = mysqli_num_rows($result);

						if ($resultCheck > 0) {
							while ($row = mysqli_fetch_assoc($result)) {
								echo $row['name'] . "<br>";
							}
						}
					?>
				</p>
			</div>

			<div class="box">
				<h1>Accounting Department</h1>
				<p>
					<?php
						$sql = "SELECT * FROM int_info WHERE department = 'Accounting';";
						$result = mysqli_query($conn, $sql);
						$resultCheck = mysqli_num_rows($result);

						if ($resultCheck > 0) {
							while ($row = mysqli_fetch_assoc($result)) {
								echo $row['name'] . "<br>";
							}
						}
					?>
				</p>
			</div>


			
		</div>

		
		</main>
		
	<script src="js/Dashboard.js"></script>
	<script src="js/navDropdown.js"></script>
	<script src="js/summaryView.js"></script>
	</body>
</html>