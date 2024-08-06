<?php
    session_start();

	header("Cache-Control: no-cache, no-store, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");

    include 'connects.php';

    if(isset($_SESSION['username'])) {
    //do nothing
    } else {
       header('Location: index.php');
       exit;
    }

	$name = $_SESSION['username'];
    $position = $_SESSION['position'];

	$query_name = "SELECT name FROM int_info";
    $result_name = mysqli_query($conn, $query_name);

    $query_in = "SELECT name, department, start_date, hr_req, hr_ren, hr_left FROM int_info";
    $result_in = mysqli_query($conn, $query_in);

	$query_int = "SELECT name FROM int_info";
	$result_int = mysqli_query($conn, $query_int);
	
	$query_emp = "SELECT name FROM emp_info";
	$result_emp = mysqli_query($conn, $query_emp);
	$combinedNames = array();
	
	while ($row1 = mysqli_fetch_assoc($result_int)) {
	  $combinedNames[] = $row1['name'];
	}
	
	while ($row2 = mysqli_fetch_assoc($result_emp)) {
	  $combinedNames[] = $row2['name'];
	}

	
	$query_notice = "SELECT name, type, date, remarks FROM notices";
    $result_notice = mysqli_query($conn, $query_notice);
?>

<script>
	function fetchUserData(selectedName) {
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
		if (xhr.readyState === 4 && xhr.status === 200) {
			var userData = JSON.parse(xhr.responseText);
			document.getElementById('hr_req').value = userData.hr_req;
			document.getElementById('hr_ren').value = userData.hr_ren;
			document.getElementById('hr_left').value = userData.hr_left;
		}
		};
		xhr.open('GET', 'fetch_int_hours.php?name=' + selectedName, true);
		xhr.send();
  	}

	function formatDate(date) {
      const options = { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' };
      return date.toLocaleDateString(undefined, options);
    }

    function updateTime() {
      var date = new Date();
      var hours = date.getHours();
      var minutes = date.getMinutes();
      var seconds = date.getSeconds();
      var ampm = hours >= 12 ? 'PM' : 'AM';

      hours = hours % 12;
      hours = hours ? hours : 12;
      hours = hours.toString().padStart(2, '0');
      minutes = minutes.toString().padStart(2, '0');
      seconds = seconds.toString().padStart(2, '0');

      var currentDate = formatDate(date);
      document.getElementById("live-time").textContent = currentDate + " " + hours + ":" + minutes + ":" + seconds + ' ' + ampm;
    }
    
    setInterval(updateTime, 1000);	
</script>

<style>
  .correct-time {
    color: black;
  }

  .late-time {
    color: red;
  }
</style>	

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="css/Profile.css">
	<!-- <link rel="stylesheet" href="css/Attendances2.css"> -->
	<title>AMS | Attendance</title>
</head>


<body>
	<!-- SIDEBAR DONT DELETE-->
	<section id="sidebar">
		<a href="admin_dash.php" class="brand">
			<img src="images/CSK Logo.png" alt="" class="logo">
			<span class="text">Attendance Management System</span>
		</a>
		<ul class="side-menu top">
			<li>
				<a href="admin_dash.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="departments.php">
					<i class='bx bxs-business'></i>
					<span class="text">Departments</span>
				</a>
			</li>
			<li>
				<a href="attendances.php">
					<i class='bx bx-clipboard'></i>
					<span class="text">Attendance</span>
				</a>
			</li>
			<li>
				<a href="users.php">
					<i class='bx bxs-calendar-check'></i>
					<span class="text">Add Employee/Intern</span>
				</a>
			</li>
			<li>
				<a href="accounts.php">
					<i class='bx bxs-user-account' ></i>
					<span class="text">Account Management</span>
				</a>
			</li>
			<li>
				<a href="leaves.php">
					<i class='bx bxs-calendar-exclamation'></i>
					<span class="text">Leaves</span>
				</a>
			</li>
			<li class="active">
				<a href="profile.php">
					<i class='bx bx-user-circle'></i>
					<span class="text">My Profile</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">	
			<li>
				<a href="logout.php" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>


	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="profile">
        	<i class='bx bxs-user-circle'></i>
			</a>
			<h2><?php echo $_SESSION['username']; ?></h2>
		</nav>

		<!-- MAIN -->
		<main>
  			<!-- FOR TIME -->
			<div class="head-title">
				<div class="left">
					<h1>Attendances</h1>
				</div>
				<div class="card-listAcc">
					<ul class="box-info">
						<text>CURRENT DATE AND TIME</text>
					</ul>
				</div>
				<div class="input-box">
					<ul class="box-info">
						<div class="input-field">
							<div class="date-time">
								<h1><span id="live-time"></span></h1>
							</div>
						</div>
					</ul>
				</div><br>
			</div>
  			<!-- FOR PROFILE -->

			<?php
			$fullName = "Sample Name";
			$address = "Location";
			$age = 99;
			$gender = "Male";
			$position = "Software Developer";
			$department = "IT";
			$startDate = "2022-01-01";
			?>

		<div class="profile-container">
			<div class="left-column">
				<img src="images/profile-user.png" alt="" class="picture">
			</div>
			<div class="right-column">
				<p><strong>Full Name:</strong> <?php echo $fullName; ?></p>
				<p><strong>Present Address:</strong> <?php echo $address; ?></p>
				<p><strong>Age:</strong> <?php echo $age; ?></p>
				<p><strong>Gender:</strong> <?php echo $gender; ?></p>
				<p><strong>Position:</strong> <?php echo $position; ?></p>
				<p><strong>Department:</strong> <?php echo $department; ?></p>
				<p><strong>Start Date:</strong> <?php echo $startDate; ?></p>

			</div>
		</div>

		<div class="button-container">
   			 <button><a href="edit_profile.php">Edit Profile</a></button>
		</div>	
		<div class="button-changepass">
   			 <button><a href="edit_profile.php">Change Password</a></button>
		</div>	

		<div class="button-changepass">
   			 <button><a href="edit_profile.php">Old Password</a></button>
		</div>	

		<div class="button-changepass">
   			 <button><a href="edit_profile.php">New Password</a></button>
		</div>	

		<div class="button-changepass">
   			 <button><a href="edit_profile.php">Retype Password</a></button>
		</div>	

		</main>
	</section>
	<!-- CONTENT -->
	<script src="js/Dashboard.js"></script>
	</body>
</html>