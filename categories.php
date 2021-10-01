<?php 
session_start();
include "init.php";
?>

<div class='container my-5'>
    <h1 class='text-center'>show category</h1>
    <div class='row'>
    <?php
    if(isset($_GET['catId']) && is_numeric($_GET['catId']))
    {
        foreach (getItems('category_id',intval($_GET['catId'])) as $item) {
            echo "<div class='col-sm-6 col-md-3'>";
                    echo "<div class='card item-box'>";
                        echo "<div class='card-body'>";
                                echo "<span class='price-tag'>$".$item['price']."</span>"; 
                                echo "<img src='layout/images/placeholder.jpg' class='img-fluid' alt='...'>";
                                echo "<h5 class='card-title'><a href='items.php?itemId=".$item['itemId']."'>".$item['name']."</a></h5>";
                                echo "<p class='card-text'>";
                                if(strlen($item['description']) > 0){
                                    echo substr($item['description'],0,50) . '...';
                                } 
                                else{
                                    echo $item['description']; 
                                }
                                echo"</p>";
                                echo "<div class='date'>".$item['created_at']."</div>";
                        echo "</div>";
                    echo "</div>";
            echo "</div>";
        }
    }
    else
    {
        echo "<div class='alert alert-danger'>There is no id</div>";
    }
    ?>
    </div>
</div>

<?php include $tpl ."_footer.php" ?>