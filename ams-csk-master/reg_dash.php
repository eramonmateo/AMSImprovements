<?php
	session_start();
	header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

	include "connects.php";
	include "access_control.php";

  $page = 'reg_dash';
  $tab = 'reg';
  include_once('non-admin-sidebar.php');

	if (!isset($_SESSION['username'])) {
		header('Location: login.php');
		exit();
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
	
    $department = $row['department'];
    // pagination
    if (isset($_GET['page_no']) && $_GET['page_no'] !== ""){
      $page_no = $_GET['page_no'];
    }else{
        $page_no = 1;
    }
    $total_records_per_page = 1;
    $offset = ($page_no - 1) * $total_records_per_page;

    $query2 = "SELECT * FROM announcement WHERE department='$department' OR department = '' ORDER  BY date_created DESC LIMIT $offset, $total_records_per_page";

    $result2 = mysqli_query($conn, $query2) or die(mysqli_error($conn));

    if(empty($result2)){
    }else{
        $currentDate = date('Y-m-d');
        $prev_page = $page_no - 1;
        $next_page = $page_no + 1;
    
        $result_count = mysqli_query($conn, "SELECT COUNT(*) as total_records FROM announcement") OR die(mysqli_error($conn));
        $records = mysqli_fetch_array($result_count);
        $total_records = $records['total_records'];
        $total_no_of_pages = ceil($total_records / $total_records_per_page);
    }

    mysqli_close($conn);
	
?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--IconsScout-->
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
        <!-- Boxicons -->
        <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="css/Dashboard.css">
	<link rel="stylesheet" href="css/admin_dash.css">
	
	<title>AMS | Dashboard</title>
</head>
<body>

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
            ?> <?php
            $positionDisplay = explode(" ", $row['position']);
            echo (count($positionDisplay) > 2) ?  
            $positionDisplay[0] . " " . $positionDisplay[count($positionDisplay) - 1] : $positionDisplay[0];
            echo " | ";
            echo $row['department'];
            ?> </h2>

			<li>
				<a href="logout.php" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</nav>

		<!-- MAIN -->
		<main>
			<div class="head-title">
				
			</div>
			<div class="input-box">
				<ul class="box-info">
					<div class="input-field">
						<div class="date-time">
							<h1>Current Time and Date: <br> <span id="live-time"></span></h1>
						</div>
					</div>
				</ul>
			</div>
      <table class="table-holder">
				<table class="table-holder">
                <thead>
                    <tr>
                        <th><h1 class="anncmnt-title">Announcement</h1> </th>
                    </tr>
                </thead>
                <tbody class="tbody-holder">
                <?php
                    //if(!empty($result2)){
                    if(mysqli_num_rows($result2)==0){
                        ?>
                        <tr>
                            <td>There are no latest announcement</td>
                        </tr>
                        <?php
                    }else{
                        while ($row2 = mysqli_fetch_array($result2)) {?>
                            <tr>
                                <div>
                                    <td>
                                    <h3 class="date_posted"><?php echo date('M d, Y', strtotime($row2['date_created'])) ; ?></h3>
                                    <h2 class="body"><?php echo $row2['body']; ?></h2>
                                    <h3 class="sender"><?php echo $row2['name']; ?></h3>
                                </div>
                                

                                    <ul class="nav_announcement">
                                        <li>
                                        <a class="page-link <?= ($page_no <= 1) ? 'disabled' : '';?>" <?= ($page_no > 1) ? 'href=?page_no=' . $prev_page : ''; ?> aria-label="Previous">
                                            <span aria-hidden="true" class="icon">&laquo;</span>
                                        </a>
                                        </li>
                                        <li>
                                        <a class="page-link <?= ($page_no >= $total_no_of_pages) ? 'disabled' : '';?>" <?= ($page_no < $total_no_of_pages) ? 'href=?page_no=' . $next_page : ''; ?> aria-label="Next">
                                            <span aria-hidden="true" class="icon">&raquo;</span>
                                        </a>
                                        </li>
                                    </ul> 
                                </td>

                            </tr>
                        <?php }
                    }
                     ?>
                </tbody>
            </table>
			</table>
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
				</div>
			</div>

<div class="grid-container">
<div class="grid-item">
  <a href="#">
    <i class='uil uil-clock-eight logo-icon'></i>
    <figcaption id="ucaption" class="caption">Time In</figcaption>
  </a>
</div>
<div class="grid-item">
  <a href="#">
  <i class='uil uil-clock-five logo-icon'></i>
    <figcaption id="ucaption" class="caption">Time Out</figcaption>
  </a>
</div>
<div class="grid-item">
  <a href="int_emp_dtr_view.php">
    <i class='bx bx-windows logo-icon'></i>
    <figcaption class="caption">Activities/DTRs</figcaption>
  </a>
</div>
<!-- <div class="grid-item">
  <a href="under_dev.html">
    <i class='bx bx-bell logo-icon'></i>
    <figcaption class="caption">My Notices</figcaption>
  </a>
</div> -->
<div class="grid-item">
  <a href="reg_profile.php">
    <i class='bx bx-user-circle logo-icon'></i>
    <figcaption class="caption">My Profile</figcaption>
  </a>
</div>
<div class="grid-item">
  <a href="csk.php">
    <i class='bx bx-body logo-icon'></i>
    <figcaption class="caption">CSK Org. Chart</figcaption>
  </a>
</div>
<!-- <div class="grid-item">
  <a href="https://www.chrisimmsk-c2j.com">
    <img src="/images/CSK Logo.png" class='logo-icon'>
    <figcaption class="caption">CSK Web Page</figcaption>
  </a>
</div> -->

    		</div>

			
		</main>
    </div>
	</section>
	<!-- CONTENT -->
	<script src="js/Dashboard.js"></script>
	<script src="js/navDropdown.js"></script>

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

  updateTime(); // Call the function initially to display the time immediately
  setInterval(updateTime, 1000); // Call the function every second to update the time

  
</script>

	
</body>
</html>