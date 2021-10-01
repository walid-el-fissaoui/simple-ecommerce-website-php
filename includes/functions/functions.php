<?php

    /* getAll() function version 2.0
    ** return all rows from a table sent with parameters
    */

    function getAll($selectFields,$table,$where = NULL,$and = NULL,$order,$ordering = "DESC"){
        global $cnx;
        $sql = $where == NULL ? '' : $where;
        $stmt = $cnx->prepare("SELECT $selectFields FROM $table $where $and ORDER BY $order $ordering");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /* getCategories() function version 1.0
    ** return categories
    */

    function getCategories(){
        global $cnx;
        $stmt = $cnx->prepare("SELECT * FROM categories ORDER BY categoryId ASC,ordering ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /* getTitle() function version 1.0
    ** echo the value assinged to pageTitle variable in each page or echo the default title
    */

    function getTitle(){
        global $pageTitle;
        if( isset($pageTitle))
        {
            echo $pageTitle;
        }
        else
        {
            echo lang('DEFAULT_TITLE');
        }
    }

    /* getItems($categoryId) function v1.0
    ** $categoryId : return items belongs to this category
    */

    function getItems($where , $value , $approve = NULL)
    {
        global $cnx;
        $sql = $approve == NULL ? ' AND approval = 1 ' : '';
        $stmt = $cnx->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY itemId DESC");
        $stmt->execute(array($value));
        return $stmt->fetchAll();
    }

    /* checkUserStatus($username) function v1.0
    ** $username : check the user has this username
    ** the function return the user that has the username sent with parameters and his account not activate yet
    */

    function checkUserStatus($username){
        global $cnx;
        $stmt = $cnx->prepare("SELECT username , regStatus FROM users WHERE username = ? AND regStatus = 0");
        $stmt->execute(array($username));
        return $stmt->rowCount();
    }

    /*
    ** checkItem($select,$from,$value) : function v1.0
    ** function that return the row's count of a select statement 
    ** $select : column to select
    ** $from   : table to select from
    ** $value : value to check if it exist 
    */

    function checkItem($select,$from,$value)
    {
        global $cnx;

        $stmt = $cnx->prepare("SELECT $select FROM $from WHERE $select = ?");

        $stmt->execute(array($value));

        return $stmt->rowCount();

    }
    
?>