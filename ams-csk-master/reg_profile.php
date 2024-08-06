<?php include('partial.php'); ?>
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
	
    $department = $row['department'];
   
	
?>
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
	<link rel="stylesheet" href="css/Dashboard.css">
	<link rel="stylesheet" href="css/Profile.css">
	<!-- <link rel="stylesheet" href="css/Attendances2.css"> -->
	<title>AMS | My Profile</title>

	<script>
		function formatDate(date) {
			const options = {
				weekday: 'long',
				month: 'long',
				day: 'numeric',
				year: 'numeric',
				hour: 'numeric',
				minute: 'numeric',
				second: 'numeric'
			};
			return date.toLocaleDateString(undefined, options);
		}

		function updateTime() {
			var date = new Date();
			var formattedDate = formatDate(date);
			document.getElementById("live-time").textContent = formattedDate;
		}

		setInterval(updateTime, 1000);
	</script>
</head>


<body>
<!-- Old Sidebar Code below, included the file instead -->
	<!-- SIDEBAR DONT DELETE-->
	<!-- <section id="sidebar">
		<a href="reg_dash.php" class="brand">
			<img src="images/CSK Logo.png" alt="" class="logo">
			<span class="text">Attendance Management System</span>
		</a>
		<ul class="side-menu top">
			<li>
				<a href="reg_dash.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="reg_attendance.php">
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
				<a href="reg_profile.php">
					<i class='bx bxs-calendar-check'></i>
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
	</section> -->
	

	<!-- ---------------------SIDEBAR ------------------------>


	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<!-- Updated Navbar to Match other pages -->
		<nav>
			<i class='bx bx-menu'></i>
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
					<i class='bx bxs-log-out-circle'></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</nav>

		<!-- MAIN -->
		<main>

			<!--------START---------- Updated the Current Date Time to match other pages by Pedrozo --->
			<div class="input-box ">
				<ul class="box-info">
					<div class="input-field">
						<div class="date-time">
							<h1>Current Time and Date: <span id="live-time"></span></h1>
						</div>
					</div>
				</ul>
			</div>
			<!-- --------------END---Date Time to match other pages----------------------------------- -->


			<!-- ---------------------------QUERY FOR EMPLOYEE"s INFORMATION DISPLAY -------------------------	-->

			<div class="background-img">
				<div id='editpro'>
					<div class="edit_container transistion">

						<?php
						include 'partial.php';
						$sql7 = "SELECT * FROM int_info WHERE name = '$name'";
						//Execute
						$res7 = mysqli_query($conn, $sql7);
						if (!$conn) {
							die("Unable to select database");
						}

						$count = mysqli_num_rows($res7);

						if ($count > 0) {

							// We do not have data
							while ($row = mysqli_fetch_assoc($res7)) {

								$address = $row['address'];
								$gender = $row['gender'];
								$school = $row['school'];
								$age = $row['age'];
								$hr_req = $row['hr_req'];
								$hr_ren = $row['hr_ren'];
								$hr_left = $row['hr_left'];
							}
						}

						$sql8 = "SELECT * FROM emp_info WHERE name = '$name'";
						//Execute
						$res8 = mysqli_query($conn, $sql8);
						if (!$conn) {
							die("Unable to select database");
						}

						$count = mysqli_num_rows($res8);

						if ($count > 0) {

							// We do not have data
							while ($row = mysqli_fetch_assoc($res8)) {

								$address = $row['address'];
								$gender = $row['gender'];
								$age = $row['age'];				
							}
						}
						?>

						<h1 class="subtitle center">My Profile</h1>

<!-- =================== PROFILE COLUMN ================================= -->

						<div class="profile-list">
							<?php
							$sql5 = "SELECT * FROM users WHERE name = '$name'";
							
							//Execute
							$res5 = mysqli_query($conn, $sql5);
							
							if (!$conn) {
								die("Unable to select database");
							}

							$count = mysqli_num_rows($res5) ;

							if ($count > 0) {

								// We do not have data
								while ($row = mysqli_fetch_assoc($res5) ) {

									$email = $row['email'];
									$name = $row['name'];
									$image = $row['image'];
									$position = $row['position'];
									
								// We do not have data
							
							}
							}
							
							?>
							<div class="col-1"><br>
								<h2 class="center">Profile Picture</h2><br>

								<p class="center"><strong><?php echo $name; ?></strong></p><br><br>
								<p class="center">
									<?php if ($image == '') {
									?><img src="images/users.png" class="picture center">
									<?php
									} else {
									?><img src="images/<?php echo $image; ?>" class="picture"><?php
																							}
																								?>
								</p>
								<br><br>
								<?php 
								if($position == 'intern'){
									?>
									<p><strong>Hrs. Requested:</strong> <?php echo $hr_req; ?></p> <br>

									<p><strong>Hrs. Rendered:</strong> <?php echo $hr_ren; ?></p> <br>
	
									<p><strong>Hrs. Left:</strong> <?php echo $hr_left; ?></p><br>
=
									<?php
								}
								?>
								
							</div>

<!-- ======================== EMPLOYEE INFORMATION COLUMN ============================= -->

							<div class="col-1">
								<h2 class="center">Employee Information</h2><br><br>
								<?php
								if($position == 'intern'){
									?><p><strong>Age:</strong> <?php echo $age; ?></p> <br>

								<p><strong>Gender:</strong> <?php echo $gender; ?></p> <br>

								<p><strong>Email:</strong> <br>
								<p class="small"><?php echo $email; ?></p>
								</p><br>

								<p><strong>Address:</strong><br>
									<?php echo $address; ?></p><br>

								<p><strong>School:</strong> <br>
									<?php echo $school; ?></p> <br>
								<?php
								}else{
									?><p><strong>Age:</strong> <?php echo $age; ?></p> <br>

								<p><strong>Gender:</strong> <?php echo $gender; ?></p> <br>

								<p><strong>Email:</strong> <br>
								<p class="small"><?php echo $email; ?></p>
								</p><br>

								<p><strong>Address:</strong><br>
									<?php echo $address; ?></p><br>

						
								<?php
								}							
								?>

								<p class="button_align">
									<button class="btn3 radius padding margin_right" onclick="document.location='edit_profile.php'">
										Update
								</p>

							</div>

							<!-- ===================================== CHANGE PASSWORD COLUMN ================================================== -->

							<div class="col-1">
								<form action="" method="POST" enctype="multipart/form-data">
									<h2 class="center">Change Password</h2><br>

									<br>
									<label for="">Old password</label><br>
									<input type="password" name="password" class="medium input_box">
									<br><br>

									<label>New password </label><br>
									<input type="password" name="new_password" placeholder="Enter new password" id="password" class="input_box" required>
									<img src="images/eye-close.png" id="eyeicon" class="image_width">
									<br><br>

									<label>Retype Password:</label><br>
									<input type="password" name="confirm_password" placeholder="Confirm new password" class="input_box" id="password2" required>
									<img src="images/eye-close.png" id="eyeicon2" class="image_width">

									<p class="button_align">
										<input type="hidden" name="name" value="<?php echo $name; ?>">
										<input name="Update" type="submit" value="Submit" class="btn3 radius padding">
									</p>

								</form>
							</div>

							<!-- =============================================== SCRIPT FOR PASSWORD HIDE VIEW ========================================	 -->

							<script>
								// Onclick Function for Retype password hide or view
								let eyeicon = document.getElementById("eyeicon");
								let password = document.getElementById("password");

								// Click Function
								eyeicon.onclick = function() {
									if (password.type == "password") {
										password.type = "text";
										eyeicon.src = 'images/eye-open.png';
									} else {
										password.type = "password";
										eyeicon.src = 'images/eye-close.png';
									}
								}
								// Onclick Function for Retype password hide or view
								let eyeicon2 = document.getElementById("eyeicon2");
								let password2 = document.getElementById("password2");

								// Click function
								eyeicon2.onclick = function() {
									if (password2.type == "password") {
										password2.type = "text";
										eyeicon2.src = 'images/eye-open.png';
									} else {
										password2.type = "password";
										eyeicon2.src = 'images/eye-close.png';
									}
								}
							</script>

						</div>
					</div><br><br>
				</div>
			</div>

			<!-- --------------------------------- ACTION QUERY for CHANGE PASSWORD-----------------------------------     -->
			<?php
			if (isset($_POST['Update'])) {

				// Cannot do md5() due to the main AMS which the current password 
				// is not ENCRYPTED this will cause error need approval to encrypt other users

				$name = $_POST['name'];
				$password = $_POST['password'];
				$new_password = $_POST['new_password'];
				$confirm_password = $_POST['confirm_password'];

				$sql_select = "SELECT * FROM users WHERE name='$name'  AND password='$password'";
				$result_select = mysqli_query($conn, $sql_select);

				$count = mysqli_num_rows($result_select);

				if ($count == 1) {

					echo "user found";
					if ($new_password == $confirm_password) {

						// Update Password
						// echo "password match";
						$sql_update = "UPDATE users SET password = '$new_password'
								WHERE name = '$name'";

						// Execute query
						$result_update = mysqli_query($conn, $sql_update);

						if ($result_update == TRUE) {

							// Message for Successfully Updated password

							echo
							"
									<script>
									alert('Password Successfully Updated!');
									document.location.href = 'reg_profile.php';
									</script>
									";
						} else {

							// Message for Passw
							echo
							"
									<script>
									alert('Update Unsuccessfull!');
									document.location.href = 'reg_profile.php';
									</script>
									";
						}
					} else {

						//password does not match Redirect to Profile php 

						echo
						"
						<script>
						alert('Password Does not Match');
						document.location.href = 'reg_profile.php';
						</script>
						";
					}
				} else {

					// USER NOT FOUND Redirect to profile php

					echo "not";
					echo
					"
					<script>
					alert('Please type the correct old password.');
					document.location.href = 'reg_profile.php';
					</script>
					";
				}
			}
			// ==================================== END of QUERY FOR CHANGE PASSWORD ========================================

			// Check the connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			$positionFilter = isset($_GET['position']) ? $_GET['position'] : 'employee';

			// Retrieve the most recent entry from the database
			$sql = "SELECT * FROM emp_info
	   WHERE name = '$name' LIMIT 1";
			$result = $conn->query($sql);

			?>
		</main>
	</section>
	<!-- CONTENT -->
	<script src="js/Dashboard.js"></script>
	<script src="js/summaryView.js"></script>
	<script src="js/navDropdown.js"></script>
</body>

</html>