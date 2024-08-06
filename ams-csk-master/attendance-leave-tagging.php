<?php session_start();

    /// JHERIMY B. ///
    
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include 'connects.php';

    $page = 'attendance-leave-tagging';
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;900&display=swap" rel="stylesheet">
	<!-- My CSS -->
	<link rel="stylesheet" href="css/attendance-leave-tagging.css">
	<title>AMS | ATTENDANCE | LEAVE TAGGING</title>
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

            <div id="leaveTable" class="tableContainer" style="display: none;">
                <table>
                        <tr>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Type</th>
                        <th>Given</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>From</th>
                        <th>Delete</th>
                    </tr>
                    <?php
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['department'] . "</td>";
                            echo "<td>" . $row['position'] . "</td>";
                            echo "<td>" . $row['type'] . "</td>";
                            echo "<td>" . $row['given'] . "</td>";
                            echo "<td>" . $row['start'] . "</td>";
                            echo "<td>" . $row['end'] . "</td>";
                            echo "<td>" . $row['from'] . "</td>";
                            echo "<td><button class='delete-row' data-id='" . $row['id'] . "'>Delete</button></td>"; 
                            echo "</tr>";
                        }
                        ?>
                </table>
            </div>

            <div id="leave-tagging" class="tableContainer">
                <table>
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Leave Type</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Save</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($dtr_result)) {
                            echo '<tr>';
                                echo '<td class="name">' . $row['name'] . '</td>';
                                echo '<td>' . $row['position'] . '</td>';
                                echo '<td>' . $row['department'] . '</td>';
                                ?>

                                <form action="attendance-leaves.php" method="POST" id="send-leave-form-<?php echo $row['name']; ?>">
                                        <!-- HIDDEN INPUTS -->
                                        <input value="<?php echo $row['name']; ?>" type="hidden" name="leaveName">
                                        <input value="<?php echo $row['department']; ?>" type="hidden" name="leaveDepartment">
                                        <input value="<?php echo $row['position']; ?>" type="hidden" name="leavePosition">
                                        <td style="width: 25%;">
                                            <select name="leaveType" id="leaveType" required>
                                                <option value="" disabled selected>Select Leave Type</option>
                                                <?php
                                                foreach ($leaveOptions as $option) {
                                                    echo '<option value="' . array_search($option, $leaveOptions) . '">' . $option . '</option>';
                                                }
                                                    ?>      
                                            </select>   
                                        </td>
                                        <td>
                                            <input type="date" class="start" type="hidden" name="leaveStart" 
                                            id="leaveStart-<?php echo $row['name']; ?>" 
                                            min="<?php echo date('Y-m-d'); ?>"
                                            onchange="setMinEndDate('<?php echo $row['name']; ?>')"  
                                            required>
                                        </td>
                                        <td>
                                            <input type="date" class="start" type="hidden" name="leaveEnd"
                                            id="leaveEnd-<?php echo $row['name']; ?>"
                                            required>
                                        </td>
                                        
                                        <td>   
                                            <input type="submit" class="functionButton" value="Save" 
                                            form="send-leave-form-<?php echo $row['name']; ?>">
                                        </td>
                                </form> 

                                <?php
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div> 
         <div class="leavebuttonContainer">
            <button id="toggleButton" class="leavelogs" onclick="toggleLeaveTable()">View Leave Logs</button>
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

    <script src="js/Dashboard.js"></script>
	<script src="js/summaryView.js"></script>
	<script src="js/navDropdown.js"></script>
    <script src="js/date-time.js"></script>

</body>
</html>