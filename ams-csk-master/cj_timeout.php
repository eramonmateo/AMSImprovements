<?php
$datetime = date('Y-m-d H:i:s');
$query = "SELECT * FROM time_out WHERE approval='No Time-out''";
$query = "UPDATE time_out SET datetime='$datetime' WHERE approval='No Time-out'";;
$result = mysqli_query($conn, $query_int);
$row_int = mysqli_fetch_assoc($result_int);
?>