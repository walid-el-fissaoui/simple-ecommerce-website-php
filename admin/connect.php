<?php

    $dsn = 'mysql:host=localhost;dbname=ecommerceshop';
    $user = 'root';
    $pass = '';
    $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    );

    try
    {
        $cnx = new PDO($dsn,$user,$pass,$option);
        $cnx->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        echo 'failed to connect' . $e->getMessage();
    }