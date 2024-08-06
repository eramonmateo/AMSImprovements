<?php
    session_start();
    include "connects.php";

    // Ensure that session variables and POST data are set and not empty
    if(isset($_SESSION['username']) && isset($_POST['dept']) && isset($_POST['body'])) {
        $name = $_SESSION['username'];
        $dept = $_POST['dept'];
        $date = date('Y-m-d');
        $body = $_POST['body'];
        
        // Use prepared statement to insert data safely
        $stmt = $conn->prepare("INSERT INTO announcement (date_created, name, department, body) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $date, $name, $dept, $body);
        
        if ($stmt->execute()) {
            echo '<script>alert("Announcement info successfully added."); window.location.href = "create_anncmnt.php";</script>';
        } else {
            echo "Error in adding announcement: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Missing or empty session or POST data.";
    }

    mysqli_close($conn);
?>