<?php
    session_start();
    session_destroy();
    header('Location: default.php');
    exit;
?>