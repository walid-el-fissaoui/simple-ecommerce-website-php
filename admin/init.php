<?php

    include 'connect.php';

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
    
    /** include navbar to all pages except the page that has a $withoutNavbar variable */
    if(!isset($withoutNavbar)) { include $tpl . "_navbar.php";}