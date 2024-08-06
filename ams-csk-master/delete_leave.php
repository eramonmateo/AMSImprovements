<?php
include 'connects.php';

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = $_POST['id'];

    // Create a SQL query to delete the record with the specified ID
    $sql = "DELETE FROM leaves WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Record deleted successfully
        echo 'success';
    } else {
        // Error occurred while deleting the record
        echo 'error';
    }
} else {
    // Invalid or missing ID
    echo 'invalid';
}

mysqli_close($conn);
?>