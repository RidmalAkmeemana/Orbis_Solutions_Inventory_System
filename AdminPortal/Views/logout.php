<?php
    session_start();
    unset($_SESSION['user']);
    unset($_SESSION['status']);
    
    // Unset the token
    unset($_SESSION['token']);

    header("location: index.php");
?>