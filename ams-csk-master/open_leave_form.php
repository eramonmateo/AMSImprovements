<?php
session_start();
include 'connects.php';

if(isset($_POST['id'])) {
    $id = $_POST['id'];

    $query = "SELECT form FROM filed_leaves WHERE leave_id = $id;";
    $result = mysqli_query($conn, $query);

    if($result) {
        $row = mysqli_fetch_assoc($result);
        $filename = $row['form'];
    } else {
        echo "Error executing query: " . mysqli_error($conn);
    }
} else {
    echo "ID not provided";
}

header('Content-Type: application/json');
echo json_encode($filename);

mysqli_close($conn);
?>
