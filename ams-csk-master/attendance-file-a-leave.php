<?php session_start();

    /// JHERIMY B. ///
    
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include 'connects.php';

    // submit a new leave request
    if(isset($_POST['submit_leave'])){

        $name = $_SESSION['username'];
        $type = $_POST['leaveType'];
        $startdate = $_POST['leaveStartDate'];
        $enddate = $_POST['leaveEndDate'];

        date_default_timezone_set('Asia/Manila');
        $currentDate = new DateTime();
        $formattedDate = sprintf('%04d%02d%02d', $currentDate->format('Y'), $currentDate->format('m'), $currentDate->format('d'));
        $formattedTime = sprintf('%02d%02d%02d', $currentDate->format('H'), $currentDate->format('i'), $currentDate->format('s'));

        $validFileExtension = ['pdf'];

        $leaveform_fileName = $_FILES["leaveForm"]["name"];
        $leaveform_fileSize = $_FILES["leaveForm"]["size"];
        $leaveform_tmpName = $_FILES["leaveForm"]["tmp_name"];

        $leaveform_extension = explode('.', $leaveform_fileName);
        $leaveform_extension = strtolower(end($leaveform_extension));

        if(!empty($_FILES["leaveForm"]["name"])){
            $filename = $name . '_LeaveForm_' . $formattedDate . '_' . $formattedTime . '.' . $leaveform_extension;
        }
        move_uploaded_file($leaveform_tmpName, 'uploaded_leave_forms/' . $filename);

        $checkConflict = " SELECT COUNT(*) AS count FROM filed_leaves 
            WHERE name = '$name' AND '$startdate' BETWEEN startdate AND enddate; ";
        $resultConflict = $conn->query($checkConflict);

        if ($resultConflict) {
            $rowConflict = $resultConflict->fetch_assoc();
            $countConflict = $rowConflict['count'];

            if($countConflict > 0){
                echo "<script>alert('Unable to submit leave request! You have an existing leave request for that date. Please try again.');</script>";
            echo "<script>window.location = 'attendance-file-a-leave.php';</script>";
            exit;
            }
        }


        
        if($startdate > $enddate){
            echo "<script>alert('Unable to submit leave request! The start date must not precede the end date. Please try again.');</script>";
            echo "<script>window.location = 'attendance-file-a-leave.php';</script>";
            exit;
        }
        elseif($type == 'PL' && strtotime($startdate) - strtotime($formattedDate) < 5 * 24 * 60 * 60){
            echo "<script>alert('Unable to submit leave request! The start date for planned leaves must be at least 5 days prior. Please try again.');</script>";
            echo "<script>window.location = 'attendance-file-a-leave.php';</script>";
            exit;
        }
        elseif($leaveform_extension !== 'pdf'){
            echo "<script>alert('Unable to submit leave request! The leave form must be in PDF format. Please try again.');</script>";
            echo "<script>window.location = 'attendance-file-a-leave.php';</script>";
            exit;
        }
        else{
            $insert_leave = " INSERT INTO filed_leaves(name, type, startdate, enddate, form) VALUES ('$name', '$type', '$startdate', '$enddate', '$filename')";
            $query_insert_leave = mysqli_query($conn, $insert_leave);
        
            if($query_insert_leave){
                echo "<script>alert('Leave has been filed successfully!');</script>";
                echo "<script>window.location = 'attendance-file-a-leave.php';</script>";
                exit;
            }
        }
    };


    $page = 'attendance-file-a-leave';
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

    $display_position = $_SESSION['position'];
    $display_department = $row['department'];
    
    $departmentFilter = isset($_GET['department']) ? $_GET['department'] : '';
    $nameFilter = isset($_GET['name']) ? $_GET['name'] : '';
    $positionFilter = isset($_GET['position']) ? $_GET['position'] :'';

    if ($positionFilter === 'All Positions') {
        $dtr_sql = "SELECT u.name, u.position, COALESCE(ii.department, ei.department) AS department
                    FROM users u
                    LEFT JOIN int_info ii ON u.name = ii.name
                    LEFT JOIN emp_info ei ON u.name = ei.name";
        
        $whereClause = "";

        if (!empty($departmentFilter)) {
            $whereClause .= "(ii.department = '$departmentFilter' OR ei.department = '$departmentFilter')";
        }
    
        if (!empty($nameFilter)) {
            if (!empty($whereClause)) {
                $whereClause .= " AND ";
            }
            $whereClause .= "(ii.name LIKE '%$nameFilter%' OR ei.name LIKE '%$nameFilter%')";
        }
    
        if (!empty($whereClause)) {
            $dtr_sql .= " WHERE " . $whereClause;
        }
        
    } elseif ($positionFilter === 'Intern') {
        $dtr_sql = "SELECT u.name, u.position, ii.department
                    FROM users u
                    JOIN int_info ii ON u.name = ii.name";
        
        $whereClause = "";

        if (!empty($departmentFilter)) {
            $whereClause .= "ii.department = '$departmentFilter'";
        }
    
        if (!empty($nameFilter)) {
            if (!empty($whereClause)) {
                $whereClause .= " AND ";
            }
            $whereClause .= "ii.name LIKE '%$nameFilter%'";
        }
    
        if (!empty($whereClause)) {
            $dtr_sql .= " WHERE " . $whereClause;
        }
    } elseif ($positionFilter === 'Employee') {
        $dtr_sql = "SELECT u.name, u.position, ei.department
                    FROM users u
                    JOIN emp_info ei ON u.name = ei.name";
        
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
        };

        if (!empty($nameFilter)) {
            if (!empty($whereClause)) {
                $whereClause .= " AND ";
            }
            $whereClause .= "ei.name LIKE '%$nameFilter%'";
        }
    
        if (!empty($whereClause)) {
            $dtr_sql .= " WHERE " . $whereClause;
        }
    } else {
        // Show all employees and departments when no specific filters selected
        $dtr_sql = "SELECT u.name, u.position, COALESCE(ii.department, ei.department) AS department
                    FROM users u
                    LEFT JOIN int_info ii ON u.name = ii.name
                    LEFT JOIN emp_info ei ON u.name = ei.name"; 
    }  
    $dtr_sql .= " ORDER BY name ASC";
    
$dtr_result = mysqli_query($conn, $dtr_sql);

if (!$dtr_result) {
    // Query execution failed, display the error message and terminate the script
    die('Error: ' . mysqli_error($conn));
}

$departmentOptions = array('IT', 'HR', 'Accounting', 'Marketing', 'Admin');
$positionOptions = array("All Positions","Employee", "Intern");

$leaveOptions = array(
                    'PL' => 'Planned Leave', 
                    'SIL' => 'School Initiated Leave', 
                    'EL' => 'Emergency Leave', 
                    'SL' => 'Sick Leave', 
                    'BL' => 'Birthday Leave', 
                    'VL' => 'Vacation Leave'
                );  
                $sql = "SELECT * FROM leaves";
                $result = $conn->query($sql);
                
                if (!$result) {
                    echo "Error in fetching leave records: " . $conn->error;
                }
?>


<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;900&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
	<!-- My CSS -->
	<link rel="stylesheet" href="css/attendance-leave-tagging.css">
	<link rel="stylesheet" href="css/attendance-filing-leave.css?<?php echo time(); ?>">
	<title>AMS | ATTENDANCE | FILE A LEAVE</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

            <!-- <div class="filterContainer">
                <form class="formContainer" method="get">
                    <select name="position" id="position">
                        <?php
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
                    <input type="text" name="name" class="nameSearch" id="nameSearch" placeholder=" Name Search"/>
                    
                    <input class="filterButton" type="submit"/>
                </form>
            </div> -->


            <div class="container-filing-leave" id="containerFilingLeave">
                <h1 class="n-h1">File a Leave</h1>
                <div class="filing-form">
                    <div class="title"><p>Leave Request</p></div>
                    <form method="post" enctype="multipart/form-data">
                    <div class="form-note"><p>All fields with <span class="required-field">*</span> are required.</p></div>
                        <div class="form-group">
                            <label for="leaveType">Type of Leave<span class="required-field">*</span></label>
                            <select class="form-control" name="leaveType" id="leaveType" required>
                                <option>Select type of leave</option>
                                <option value="PL">Planned Leave</option>
                                <option value="SIL">School Initiated Leave</option>
                                <option value="EL">Emergency Leave</option>
                                <option value="SL">Sick Leave</option>
                                <option value="BL">Birthday Leave</option>
                                <option value="VL">Vacation Leave</option>
                            </select>
                        </div>
                        <div class="leave-date">
                            <div class="form-group start">
                                <label for="leaveStartDate">Start Date<span class="required-field">*</span></label>
                                <input type="date" class="form-control" id="leaveStartDate" name="leaveStartDate">
                            </div>
                            <div class="form-group end">
                                <label for="leaveEndDate">End Date<span class="required-field">*</span></label>
                                <input type="date" class="form-control" id="leaveEndDate" name="leaveEndDate">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="leaveForm">Upload your Leave Form<span class="required-field">*</span></label>
                            <input type="file" class="form-control" accept="application/pdf" id="leaveForm" name="leaveForm">
                        </div>
                        

                        <button type="submit" class="btn btn-primary btn-submit" name="submit_leave">Submit</button>
                    </form> 
                </div>
            </div>

            <div class="container-toggle-btn">
                <button id="toggleButton" class="btn btn-primary toggleButton" onclick="toggleLeaveRequests()">View Previous Leave Requests</button>
            </div>

            <!-- LIST OF FILED LEAVES -->
            <h1 class="n-h1" id="headerPrev" style="display:none;">Previous Leave Requests</h1>
            <div id="containerLeaves" class="tableContainer" style="display:none;">
                <table>
                        <tr>
                        <!-- <th>Name</th>
                        <th>Department</th>
                        <th>Position</th> -->
                        <th>Type</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Date Submitted</th>
                        <th>Status</th>
                        <th>Approved By</th>
                        <th>Date Approved</th>
                        <th>Form</th>
                    </tr>
                    <?php

                        $getUserLeaves = "SELECT * FROM filed_leaves WHERE name='$name'";
                        $res_getUserLeaves = $conn->query($getUserLeaves);
                        if ($res_getUserLeaves->num_rows === 0) {
                            echo "<tr>";
                                echo "<td colspan='10'>No leave requests yet</td>";
                                echo "</tr>";
                        } else {
                            while ($row = $res_getUserLeaves->fetch_assoc()) {
                                echo "<tr>";
                                // echo "<td>" . $row['name'] . "</td>";
                                // echo "<td>" . $display_department . "</td>";
                                // echo "<td>" . $display_position . "</td>";
                                echo "<td>" . $row['type'] . "</td>";
                                echo "<td>" . $row['startdate'] . "</td>";
                                echo "<td>" . $row['enddate'] . "</td>";
                                echo "<td>" . $row['date_submitted'] . "</td>";
                                
                                if($row['status'] == "Approved"){
                                    echo "<td class='green'>" . $row['status'] . "</td>";
                                }
                                else if($row['status'] == "Pending"){   
                                    echo "<td class='yellow'>" . $row['status'] . "</td>";
                                }
                                else if($row['status'] == "Rejected"){   
                                    echo "<td class='red'>" . $row['status'] . "</td>";
                                }

                                echo "<td>" . $row['approvedby'] . "</td>";
                                echo "<td>" . $row['date_approved'] . "</td>";
                                echo "<td><button class='btn btn-view-form' data-id='" . $row['leave_id'] . "'><i class='fa-solid fa-paperclip'></i></button></td>"; 
                                echo "</tr>";
                            }
                        }
                    ?>
                </table>
            </div>


            



            <script>
        function toggleLeaveRequests() {
            var containerLeaves = document.getElementById("containerLeaves");
            var headerPrev = document.getElementById("headerPrev");
            var containerFilingLeave = document.getElementById("containerFilingLeave");
            var toggleButton = document.getElementById("toggleButton");

            if (containerLeaves.style.display == "none") {
                containerLeaves.style.display = "block";
                headerPrev.style.display = "block";
                containerFilingLeave.style.display = "none";
                toggleButton.textContent = "File a Leave";
            } else {
                containerLeaves.style.display = "none";
                headerPrev.style.display = "none";
                containerFilingLeave.style.display = "block";
                toggleButton.textContent = "View Previous Leave Requests";
            }
        }



            </script>
        </main>


    </section>

    <script>
        function closeSideBar() {
            sidebar.classList.toggle('hide');
            section.classList.toggle('active');
        }

        function setMinEndDate(rowName) {
            var startDateInput = document.getElementById('leaveStart-' + rowName);
            var endDateInput = document.getElementById('leaveEnd-' + rowName);
            
            endDateInput.min = startDateInput.value;
        }
    </script>
   
<script>
       $(document).ready(function() {
            // Add click event listener to the "Delete" buttons
            $('.delete-row').on('click', function() {
              if (confirm('Are you sure you want to delete this leave record?')) {
            var id = $(this).data('id');
            var row = $(this).closest('tr');

            // Send an AJAX request to delete the record
            $.ajax({
                url: 'delete_leave.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    if (response === 'success') {
                        // Remove the row from the table
                        row.remove();
                    } else {
                        alert('Error deleting the leave record.');
                    }
                },
                error: function() {
                    alert('An error occurred during the request.');
                }
            });
        }
    });
});
    </script>


    <script>
        $(document).ready(function() {
            $('.btn-view-form').on('click', function() {
                var id = $(this).data('id');
                console.log(id);

                $.ajax({
                    url: 'open_leave_form.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        console.log(response);
                        var dir = 'uploaded_leave_forms/' + response;
                        window.open(dir, '_blank');
                    },
                    error: function(xhr, status, error) {
                        // Handle errors if any
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>

    <script src="js/Dashboard.js"></script>
	<script src="js/summaryView.js"></script>
	<script src="js/navDropdown.js"></script>
    <script src="js/date-time.js"></script>

</body>
</html>