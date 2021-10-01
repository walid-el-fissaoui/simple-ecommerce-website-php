<?php

    /*
        Categories : Manage | Edit | Update | Add | Insert | Delete | Stats  
        Condition : ? True : False 
    */

    
    $action = isset($_GET['action']) ? $_GET['action'] : 'manage';

    // $action = '';
    // if (isset($_GET['action']))
    // {
    //     $action = $_GET['action'];
    // }
    // else
    // {
    //     $action = 'manage';
    // }

    if($action == 'manage')
    {
        echo "hello inside manage page";
        echo " <a href='page.php?action=add'>go to add</a>";
    }
    elseif($action == 'add')
    {
        echo "hello inside add page";
    }
    else{
        echo "page not found";
    }