<?php
    
    /* 
    ** getTitle() : function v1.0
    ** function : echo the page title if it contains the $pageTitle variable 
    ** if it doesn't contain the variable , echo a default one 
    */

    function getTitle()
    {

        // reference to the global $pageTitle 

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

    /*
    ** redirectHome($message,$url,$seconds) : function v2.0
    ** function that redirect the user to the home page after showing the error message   
    ** $message : echo the message [error, success , info ...]
    ** $url     : the url where the user will be redirected
    ** $seconds : seconds before redirecting
    */

    function redirectHome($message , $url = null ,$seconds = 3)
    {
        if($url == null)
        {
            $url = 'index.php';
            $page = 'home page';
        }
        else
        {
            $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REFERER'] : 'index.php';
            $page = 'previous page';
        }
        echo $message;
        echo "<div class='alert alert-info'>you will be redireted to the $page after $seconds seconds</div>";
        header("refresh:$seconds;url=$url");
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


    
    /*
    ** checkItem($column,$from,$value) : function v1.0
    ** function that return the row's count of a select statement 
    ** $column : column to check its value
    ** $from   : table to select from
    ** $value : value that will be compared with the $column
    */

    // function checkItem($column,$from,$value)
    // {
    //     global $cnx;

    //     $stmt = $cnx->prepare("SELECT count(*) FROM $from WHERE $column = ?");

    //     $stmt->execute(array($value));

    //     $rows =  $stmt->fetch();

    //     return $rows[0];
    // }


    /*
    ** countItems($item,$table,$value) : function v2.0
    ** $item : item to count
    ** $table : table to select from
    */

    function countItems($item,$table,$whereEqual = null,$whereNotEqual = null,$checkValue=null)
    {
        global $cnx;
        if($whereEqual != null)
        {
            $stmt = $cnx->prepare("SELECT COUNT(*) FROM $table WHERE $item = ?");
            $stmt->execute(array($whereEqual));
        }
        else
        {
            if($whereNotEqual != null)
            {
                $stmt = $cnx->prepare("SELECT COUNT(*) FROM $table WHERE $whereNotEqual != ?");
                $stmt->execute(array($checkValue));
            }
            else
            {
                $stmt = $cnx->prepare("SELECT COUNT(*) FROM $table");
                $stmt->execute();
            }
        }        
        return $stmt->fetchColumn();
    }


    /*
    ** getLatest($select,$table,$orderBy,$limit) : function v1.0
    ** $select : the column that will be selected
    ** $table  : the table that will selet from
    ** $orderBy: which column will order by 
    ** $limit  : how many rows will be fetched from database
    */

    function getLatest($select,$table,$orderBy,$limit = 3,$where = null)
    {
        global $cnx;
        if($where == null)
        {
            $stmt = $cnx->prepare("SELECT $select FROM $table ORDER BY $orderBy DESC LIMIT $limit");
        }
        else
        {
            $stmt = $cnx->prepare("SELECT $select FROM $table WHERE $where ORDER BY $orderBy DESC LIMIT $limit");
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

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