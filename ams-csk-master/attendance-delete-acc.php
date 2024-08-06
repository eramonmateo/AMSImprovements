<?php 
    
    /// JHERIMY B. ///

    session_start();

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include 'connects.php';

    $page = 'delete-account';
    $tab = 'mngmt';
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
        
        $nameFilter = isset($_GET['name']) ? $_GET['name'] : '';
        $positionFilter = isset($_GET['position']) ? $_GET['position'] :'';
    
        if ($positionFilter === 'All Positions') {
            $dtr_sql = "SELECT u.name, u.email, u.position, COALESCE(ei.department, ii.department) AS department
                        FROM users u
                        LEFT JOIN int_info ii ON u.name = ii.name
                        LEFT JOIN emp_info ei ON u.name = ei.name";
        
            $whereClause = "";

            if (!empty($nameFilter)) {
                $whereClause .= "(ii.name LIKE '%$nameFilter%' OR ei.name LIKE '%$nameFilter%')";
            }
        
            if (!empty($whereClause)) {
                $dtr_sql .= " WHERE " . $whereClause;
            }
            
        } elseif ($positionFilter === 'Intern') {
             $dtr_sql = "SELECT u.name, u.email, u.position, ii.department
                        FROM users u
                        JOIN int_info ii ON u.name = ii.name";

            $whereClause = "";

            if (!empty($nameFilter)) {
                $whereClause .= "ii.name LIKE '%$nameFilter%'";
            }

            if (!empty($whereClause)) {
                $dtr_sql .= " WHERE " . $whereClause;
            }

        } elseif ($positionFilter === 'Employee') {
            $dtr_sql = "SELECT u.name, u.email, u.position, ei.department
                        FROM users u
                        JOIN emp_info ei ON u.name = ei.name";

            $whereClause = "";

            if (!empty($nameFilter)) {
                $whereClause .= "ei.name LIKE '%$nameFilter%'";
            }

            if (!empty($whereClause)) {
                $dtr_sql .= " WHERE " . $whereClause;
            }

        } else {
            $dtr_sql = "SELECT u.name, u.email, u.position, COALESCE(ii.department, ei.department) AS department
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

    $deletion_sql = "SELECT * FROM deletions ORDER BY datetime ASC";
                    $deletion_result = mysqli_query($conn, $deletion_sql);
                    
    if (!$deletion_result) {
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
        <link rel="stylesheet" href="css/attendance-delete-acc.css">
        <title>AMS | MANAGEMENT | DELETE ACCOUNT</title>
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
    
                <div class="filterContainer" id="filterContainer">
                    <form class="formContainer" id="formContainer" method="get">
                        <select name="position" id="position">
                            <?php
                            $positionOptions = array("All Positions","Employee", "Intern");
                            
                            foreach ($positionOptions as $position) {
                                $selected = ($positionFilter == $position) ? 'selected' : '';
                                echo '<option value="' . $position . '" ' . $selected . '>' . $position . '</option>';
                            }
                            ?>
                        </select>

                        <input type="text" name="name" class="nameSearch" id="nameSearch" placeholder=" Name Search"/>
                        
                        <input class="filterButton" type="submit"/>
                    </form>
                </div>
                
                <?php
                if (mysqli_num_rows($dtr_result) == 0) {
                    echo '<p class="emptyTable" id="emptyTable"> No record available </>';
                } else { ?>
                    <div class="tableContainer" id="tableContainer">
                        <table id="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email Address</th>
                                    <th>Position</th>
                                    <th>Department</th>
                                    <th>Reason</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_assoc($dtr_result)) {
                                    echo '<tr>';
                                        echo '<td class="name">' . $row['name'] . '</td>';
                                        echo '<td style="text-transform: none;">' . $row['email'] . '</td>';
                                        echo '<td>' . $row['position'] . '</td>';
                                        echo '<td>' . $row['department'] . '</td>';
                                        ?>

                                        <form action="attendance-deletion-acc.php" method="POST" id="delete-acc-form-<?php echo $row['name']; ?>">
                                                <!-- HIDDEN INPUTS -->
                                                <input value="<?php echo $row['name']; ?>" type="hidden" name="deleteName">
                                                <input value="<?php echo $row['email']; ?>" type="hidden" name="deleteEmail">
                                                <input value="<?php echo $row['position']; ?>" type="hidden" name="deletePosition">
                                                <input value="<?php echo $row['department']; ?>" type="hidden" name="deleteDepartment">

                                                <td style="width: 35%">
                                                    <input type="text" class="delete" name="deleteReason" required>
                                                </td>
                                                
                                                <td>
                                                    <input type="submit" class="functionButton" value="Delete" 
                                                    form="delete-acc-form-<?php echo $row['name']; ?>">
                                                </td>
                                        </form> 

                                        <?php
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> 

                <?php
                }
                ?>
    
                <div class="popUpContainer" id="popUpContainer">    
                    <table id="tableDeletion">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Department</th>
                                <th>Email</th>
                                <th>Reason</th>
                                <th>Deleted By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($deleterow = mysqli_fetch_assoc($deletion_result)) {
                                echo '<tr>';
                                    echo '<td>' . date("F j, Y g:i A", strtotime($deleterow['datetime'])) . '</td>';
                                    echo '<td class="name">' . $deleterow['name'] . '</td>';
                                    echo '<td>' . $deleterow['position'] . '</td>';
                                    echo '<td>' . $deleterow['department'] . '</td>';
                                    echo '<td style="text-transform: none;">' . $deleterow['email'] . '</td>';
                                    echo '<td>' . $deleterow['reason'] . '</td>';
                                    echo '<td>' . $deleterow['deletedby'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="buttonContainer">
                    <button id="deleteLogs" class="deleteLogs" onclick="toggleHide()">View Deletion Logs</button>
                    <button id="export" class="export" onclick="excel()">Export as Excel File</button>
                </div>
            </main>   
        </section>
        
        <script>
            function closeSideBar() {
                sidebar.classList.toggle('hide');
                section.classList.toggle('active');
            }

            function toggleHide() {
                tableContainer.classList.toggle('hidden');
                popUpContainer.classList.toggle('hidden');
                formContainer.classList.toggle('hidden');
                (deleteLogs.innerHTML === "View Deletion Logs") ? 
                deleteLogs.innerHTML = "Close Deletion Logs" : deleteLogs.innerHTML = "View Deletion Logs";
                document.getElementById('export').classList.toggle('hidden');
            }

            popUpContainer.classList.toggle('hidden');
            document.getElementById('export').classList.toggle('hidden');

            
            function excel() {
                var excel = new Table2Excel();
                excel.export(document.getElementById("tableDeletion"));
            }
        </script>
    
        <script src="js/Dashboard.js"></script>
        <script src="js/summaryView.js"></script>
        <script src="js/navDropdown.js"></script>

        <script src="js/date-time.js"></script>
        <script src = js/export-excel.js></script>
    
    </body>
    </html>