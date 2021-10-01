<?php 

    session_start(); // start the session 
    session_unset(); // unset session variables 
    session_destroy(); // destroy the session 

    header('Location: index.php'); // redirect user to the index page
    exit();