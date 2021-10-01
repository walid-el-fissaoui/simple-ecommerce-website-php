<?php 
ob_start();
session_start();
include "init.php";
?>

<div class='container my-5'>
    <?php
    if(isset($_GET['tagName'])){ ?>
        <h1 class='text-center'><?php echo $_GET['tagName'] ?></h1>
            <div class='row'>
            <?php foreach (getAll("*","items"," WHERE tags like '%{$_GET['tagName']}%' "," AND approval = 1 ","itemId") as $item) { ?>
                <div class='col-sm-6 col-md-3'>
                        <div class='card item-box'>
                            <div class='card-body'>
                                    <span class='price-tag'>$<?php echo $item['price'] ?></span> 
                                    <img src='layout/images/placeholder.jpg' class='img-fluid' alt='...'>
                                    <h5 class='card-title'><a href='items.php?itemId=<?php echo $item['itemId'] ?>'><?php echo $item['name'] ?></a></h5>
                                    <p class='card-text'>
                                    <?php 
                                    if(strlen($item['description']) > 0)
                                    { 
                                    echo substr($item['description'],0,50) . '...';
                                    } else
                                    { echo $item['description']; } ?>
                                    </p>
                                    <div class='date'><?php echo $item['created_at']?></div>
                            </div>
                        </div>
                </div>
            <?php } ?>
            </div>
    <?php
    }else
    {
        echo "<div class='alert alert-danger'>There is no id</div>";
    }
    ?>
    
</div>

<?php 
include $tpl ."_footer.php";
ob_end_flush();
?>