<?php session_start();

    /// JHERIMY B. ///
    
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include 'connects.php';

    $page = 'attendance-filed-leaves';
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
    
    // $display_position = $_SESSION['position'];
    // $display_department = $row['department'];

    $departmentFilter = isset($_GET['department']) ? $_GET['department'] : '';
    $nameFilter = isset($_GET['name']) ? $_GET['name'] : '';
    $positionFilter = isset($_GET['position']) ? $_GET['position'] :'';

    if ($positionFilter === 'All Positions') {
        $dtr_sql = "SELECT DISTINCT l.leave_id, u.name, u.position, COALESCE(ii.department, ei.department) AS department, l.*
                    FROM users u
                    LEFT JOIN int_info ii ON u.name = ii.name
                    LEFT JOIN emp_info ei ON u.name = ei.name
                    JOIN filed_leaves l ON u.name = l.name";
        
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
        $dtr_sql = "SELECT DISTINCT l.leave_id, u.name, u.position, ii.department, l.*
                    FROM users u
                    JOIN int_info ii ON u.name = ii.name
                    JOIN filed_leaves l ON u.name = l.name";
        
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
        $dtr_sql = "SELECT DISTINCT l.leave_id, u.name, u.position, ei.department, l.*
                    FROM users u
                    JOIN emp_info ei ON u.name = ei.name
                    JOIN filed_leaves l ON u.name = l.name";
        
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
        $dtr_sql = "SELECT DISTINCT l.leave_id, u.name, u.position, COALESCE(ii.department, ei.department) AS department, l.*
                    FROM users u
                    LEFT JOIN int_info ii ON u.name = ii.name
                    LEFT JOIN emp_info ei ON u.name = ei.name
                    JOIN filed_leaves l ON u.name = l.name";
    }
    
    $dtr_sql .= " ORDER BY l.date_submitted DESC";
    
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
                $sql = "SELECT * FROM filed_leaves";
                $result = $conn->query($sql);
                
                if (!$result) {
                    echo "Error in fetching leave records: " . $conn->error;
                }


    // System-generated Approval
    $getDateStatus = "SELECT leave_id, date_lastmodified, status FROM filed_leaves";
    $resDateStatus = $conn->query($getDateStatus);

    $currentDateTime = date('Y-m-d H:i:s');

    if ($resDateStatus->num_rows > 0) {
        while($rowDateStatus = $resDateStatus->fetch_assoc()) {
            $dateLastModified = $rowDateStatus["date_lastmodified"];
            $status = $rowDateStatus["status"];
            
            if($status == "Pending"){
                $diff = strtotime($currentDateTime) - strtotime($dateLastModified);
                $diffInDays = round($diff / (60 * 60 * 24));
                // $diffInHours = round($diff / (60 * 60));

                if ($diffInDays >= 2 && $status != "Approved") {
                    
                    $approvedBy = "System-Generated Approval";
                    date_default_timezone_set('Asia/Manila');
                    $dateNow = new DateTime();
                    $dateNow = date("Y-m-d H:i:s");
                    
                    $updateStatusToApprove = "UPDATE filed_leaves SET status = 'Approved', approvedby='$approvedBy', date_approved='$dateNow' WHERE date_lastmodified = '$dateLastModified'";
                    
                    if ($conn->query($updateStatusToApprove) === TRUE) {
                        echo "<script>window.location.href='attendance-filed-leaves.php';</script>";
                        exit;
                    }
                }
            }
        }
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
	<!-- My CSS -->
	<link rel="stylesheet" href="css/attendance-leave-tagging.css">
	<link rel="stylesheet" href="css/attendance-filing-leave.css?<?php echo time(); ?>">
	<title>AMS | ATTENDANCE | LEAVES</title>
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

            <div class="filterContainer">
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
            </div>

            <!-- DRAFT -->
            <div id="leaveTable" class="tableContainer"">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Type</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                            <th>Approved By</th>
                            <th>Date Approved</th>
                            <th>Form</th>
                            <th>Save</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while ($row = mysqli_fetch_assoc($dtr_result)) {
                                echo "<tr>";
                                    echo "<td class='name'>" . $row['name'] . "</td>";
                                    echo "<td>" . $row['department'] . "</td>";
                                    echo "<td>" . $row['position'] . "</td>";
                                    echo "<td>" . $row['type'] . "</td>";
                                    echo "<td>" . $row['startdate'] . "</td>";
                                    echo "<td>" . $row['enddate'] . "</td>";
                                    echo "<td>" . $row['date_submitted'] . "</td>";
                                    
                                    if($row['status'] == "Approved"){
                                        echo "<td class='green'>
                                            <select class='inside-td' name='new_status'>
                                                <option value='Approved' selected>Approved</option>
                                                <option value='Pending'>Pending</option>
                                                <option value='Rejected'>Rejected</option>
                                            </select>
                                            </td>";
                                    }
                                    else if($row['status'] == "Pending"){   
                                        echo "<td class='yellow'>
                                            <select class='inside-td' name='new_status'>
                                                <option value='Approved'>Approved</option>
                                                <option value='Pending' selected>Pending</option>
                                                <option value='Rejected'>Rejected</option>
                                            </select>
                                            </td>";
                                    }
                                    else if($row['status'] == "Rejected"){   
                                        echo "<td class='red'>
                                            <select class='inside-td' name='new_status'>
                                                <option value='Approved'>Approved</option>
                                                <option value='Pending'>Pending</option>
                                                <option value='Rejected' selected>Rejected</option>
                                            </select>
                                            </td>";
                                    }

                                    echo "<td>" . $row['approvedby'] . "</td>";
                                    echo "<td>" . $row['date_approved'] . "</td>";
                                    echo "<td><button class='btn btn-view-form' data-id='" . $row['leave_id'] . "'><i class='fa-solid fa-paperclip'></i></button></td>"; 
                                    echo "<td><button class='btn btn-save-row' data-id='" . $row['leave_id'] . "'><i class='fa-solid fa-floppy-disk'></i></button></td>"; 
                                    echo "</tr>";
                            }
                            // $getAllUserLeaves = "SELECT * FROM filed_leaves";
                            // $res_getAllUserLeaves = $conn->query($getAllUserLeaves);
                            // if ($res_getAllUserLeaves->num_rows === 0) {
                            //     echo "<tr>";
                            //         echo "<td colspan='10'>No leave requests yet</td>";
                            //         echo "</tr>";
                            // }
                            // else {
                            //     while ($row = $res_getAllUserLeaves->fetch_assoc()) {
                            //         $checkName = mysqli_real_escape_string($conn, $row['name']);

                            //         $query_checkName = "SELECT * FROM emp_info WHERE name = '$checkName'";
                            //         $result_checkName = mysqli_query($conn, $query_checkName);

                            //         if ($result_checkName && mysqli_num_rows($result_checkName) > 0) {
                            //             $query_getPosition = " SELECT position, department FROM emp_info WHERE name='$checkName' ";
                            //             $result_getPosition = mysqli_query($conn, $query_getPosition);
                            //             $row_getPosition = mysqli_fetch_assoc($result_getPosition);
                            //             $display_position = $row_getPosition['position'];
                            //             $display_department = $row_getPosition['department'];
                            //         }
                            //         else {
                            //             $query_getPosition = " SELECT position, department FROM int_info WHERE name='$checkName' ";
                            //             $result_getPosition = mysqli_query($conn, $query_getPosition);

                            //             $row_getPosition = mysqli_fetch_assoc($result_getPosition);
                            //             $display_position = $row_getPosition['position'];
                            //             $display_department = $row_getPosition['department'];
                            //         }

                            //         echo "<tr>";
                            //         echo "<td class='name'>" . $row['name'] . "</td>";
                            //         echo "<td>" . $display_department . "</td>";
                            //         echo "<td>" . $display_position . "</td>";
                            //         echo "<td>" . $row['type'] . "</td>";
                            //         echo "<td>" . $row['startdate'] . "</td>";
                            //         echo "<td>" . $row['enddate'] . "</td>";
                            //         echo "<td>" . $row['date_submitted'] . "</td>";
                                    
                            //         if($row['status'] == "Approved"){
                            //             echo "<td class='green'>
                            //                 <select class='inside-td' name='new_status'>
                            //                     <option value='Approved' selected>Approved</option>
                            //                     <option value='Pending'>Pending</option>
                            //                     <option value='Rejected'>Rejected</option>
                            //                 </select>
                            //                 </td>";
                            //         }
                            //         else if($row['status'] == "Pending"){   
                            //             echo "<td class='yellow'>
                            //                 <select class='inside-td' name='new_status'>
                            //                     <option value='Approved'>Approved</option>
                            //                     <option value='Pending' selected>Pending</option>
                            //                     <option value='Rejected'>Rejected</option>
                            //                 </select>
                            //                 </td>";
                            //         }
                            //         else if($row['status'] == "Rejected"){   
                            //             echo "<td class='red'>
                            //                 <select class='inside-td' name='new_status'>
                            //                     <option value='Approved'>Approved</option>
                            //                     <option value='Pending'>Pending</option>
                            //                     <option value='Rejected' selected>Rejected</option>
                            //                 </select>
                            //                 </td>";
                            //         }

                            //         echo "<td>" . $row['approvedby'] . "</td>";
                            //         echo "<td>" . $row['date_approved'] . "</td>";
                            //         echo "<td><button class='btn btn-view-form' data-id='" . $row['leave_id'] . "'><i class='fa-solid fa-paperclip'></i></button></td>"; 
                            //         echo "<td><button class='btn btn-save-row' data-id='" . $row['leave_id'] . "'><i class='fa-solid fa-floppy-disk'></i></button></td>"; 
                            //         echo "</tr>";
                            //     }
                            // }
                        ?>
                    </tbody>
                </table>
            </div>
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
    function toggleLeaveTable() {
        var leaveTable = document.getElementById("leave-tagging");
        var leaveTableLog = document.getElementById("leaveTable"); // Updated this line
        var toggleButton = document.getElementById("toggleButton");

        if (leaveTable.style.display === "none") {
            leaveTable.style.display = "block";
            leaveTableLog.style.display = "none"; // Updated this line
            toggleButton.textContent = "View Leave Logs";
        } else {
            leaveTable.style.display = "none";
            leaveTableLog.style.display = "block"; // Updated this line
            toggleButton.textContent = "View Original Page";
        }
    }
</script>
<script>
$(document).ready(function() {
    $('.btn-save-row').on('click', function() {
        if (confirm('Are you sure you want to save this leave record?')) {
            var id = $(this).data('id');
            var row = $(this).closest('tr');
            var newStatus = $(this).closest('tr').find('select[name=new_status]').val();

            $.ajax({
                url: 'save_leave.php',
                type: 'POST',
                data: 
                    { 
                        id: id,
                        new_status: newStatus
                    },
                success: function(response) {
                    if (response === 'success') {
                        alert('Leave record saved successfully.');
                        location.href = "attendance-filed-leaves.php";
                    } 
                    else {
                        alert('Error saving the leave record.');
                    }
                },
                error: function() {
                    alert('An error occurred during the request.');
                }
            });
        }
    });

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

    <!-- <script src="js/Dashboard.js"></script> -->
	<!-- <script src="js/summaryView.js"></script> -->
	<script src="js/navDropdown.js"></script>
    <script src="js/date-time.js"></script>

</body>
</html>