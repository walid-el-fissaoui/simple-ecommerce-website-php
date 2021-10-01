<?php

    // Error Reporting :

    ini_set('display_errors','On');
    error_reporting(E_ALL);

    include 'admin/connect.php';

    $sessionUserName = '';

    if(isset($_SESSION['userLogedIn']))
    {
        $sessionUserName = $_SESSION['userLogedIn'];
    }

    //Routes

    $tpl = "includes/templates/"; // Templates directory
    $lang = "includes/languages/"; // languares directory 
    $func = "includes/functions/"; // functions directory 
    $css = "layout/css/"; //Css directory
    $js = "layout/js/"; //Js directory

    // include files
    include $lang . 'english.php'; 
    include $func . 'functions.php';
    include $tpl . '_header.php'; 
    