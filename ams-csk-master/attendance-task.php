<?php 
    
    /// JHERIMY B. ///

    session_start();

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include 'connects.php';

    $page = 'task';
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
    $filterTask = isset($_GET['task']) ? $_GET['task'] : '';

    $dtr_sql = "SELECT *
                FROM tasks t
                WHERE t.name='$name' AND t.status='Pending'";

    $andClause = "";

    if(!empty($filterDate)) {
        $andClause .= " AND '$filterDate' BETWEEN DATE(t.date) AND DATE(t.deadline)";
    }

    if(!empty($filterTask)) {
        $andClause .= " AND t.task LIKE '%$filterTask%'";
    }

    if (!empty($andClause)) {
        $dtr_sql .= $andClause;
    }

    $dtr_sql .= " ORDER BY date ASC";

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
	<link rel="stylesheet" href="css/attendance-task.css">
	<title>AMS | ATTENDANCE | MY TASKS</title>
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
                    <?php echo isset($_GET['filter_date']) ? $_GET['filter_date'] : ''; ?>" min="date()">
                    <input type="text" name="task" class="taskSearch" id="taskSearch" placeholder=" Search Task"/>
                    
                    <input class="filterButton" type="submit"/>
                </form>
            </div>

            <div class="tableContainer">
                <?php
                if (mysqli_num_rows($dtr_result) == 0) {
                    echo '<p class="emptyTable"> No task available </>';
                } else {
                ?>
                    <table>
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Date & Time</th>
                            <th>Deadline</th>
                            <th>From</th>
                            <th>Mark</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($dtr_result)) {
                            echo '<tr>';
                                echo '<td style="text-transform: none; width: 35%">' . $row['task'] . '</td>';
                                echo '<td>' . date("F j, Y g:i A", strtotime($row['date'])) . '</td>';
                                echo '<td>' . date("F j, Y g:i A", strtotime($row['deadline'])) . '</td>';
                                echo '<td>' . $row['from'] . '</td>';
                        ?>      <form action="attendance-task-done.php" method="POST" id="done-task-<?php echo $row['id']; ?>">
                                    <input value="<?php echo $row['id']; ?>" type="hidden" name="taskID">
                                    
                                    <td>   
                                        <input type="submit" class="functionButton" value="Done" name="doneButton"
                                        form="done-task-<?php echo $row['id']; ?>">
                                    </td>

                            </form>
                        <?php
                            echo '</tr>';
                        }
                }
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
    </script>

    <script src="js/Dashboard.js"></script>
	<script src="js/summaryView.js"></script>
	<script src="js/navDropdown.js"></script>
    <script src="js/date-time.js"></script>

</body>
</html>