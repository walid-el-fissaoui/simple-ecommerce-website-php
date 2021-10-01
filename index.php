<?php 
    session_start();
    if($_SESSION['userLogedIn']){ include "init.php"; ?>

            <div class="container my-5">
                <div class="row">
                <?php foreach(getAll('*','items',' WHERE approval = 1 ' , '' , 'itemId') as $item) { ?>
                    <div class='col-sm-6 col-md-3'>
                        <div class='card item-box'>
                            <div class='card-body'>
                                <?php if($item['approval'] == 0) { ?>
                                    <span class='approve-status'>waiting approval</span>
                                <?php } ?>
                                <span class='price-tag'><?php echo "$" . $item['price'] ?></span> 
                                <img src='layout/images/placeholder.jpg' class='img-fluid' alt='...'>
                                <h5 class='card-title'><a href='items.php?itemId=<?php echo $item['itemId'] ?>'><?php echo $item['name'] ?></a></h5>
                                <p class='card-text'>
                                    <?php if(strlen($item['description']) > 0){
                                        echo substr($item['description'],0,50) . '...';
                                    } 
                                    else{
                                        echo $item['description']; 
                                    }?>
                                </p>
                                <div class='date'><?php echo $item['created_at'] ?></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>

    <?php include $tpl . '_footer.php';  
    }else
    {
        header('Location:authentication.php');
    }
?>