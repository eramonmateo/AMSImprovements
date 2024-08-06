<?php
    if (isset($_SESSION['username'])) {
        if($_SESSION['role'] != 'admin'){
            header("Location: reg_dash.php");
        }
    }
?>