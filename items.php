<?php
ob_start();
session_start();
    include "init.php";
    

    $itemId =  isset($_GET['itemId']) && is_numeric($_GET['itemId']) ? intval($_GET['itemId']) : 0;
    $stmt = $cnx->prepare("SELECT items.*,categories.name AS category , users.username FROM items 
                                INNER JOIN categories ON categories.categoryId = items.category_id
                                INNER JOIN users ON users.userId = items.member_id
                                WHERE itemId = ? AND (approval = 1 OR items.member_id = ?)");
        $stmt->execute(array($itemId,$_SESSION['userId']));
        $row = $stmt->fetch();
    $exists = $stmt->rowCount();
    echo "<div class='container my-5'>";
    if($exists > 0)
    {
        ?>
                <h1 class='text-center'><?php echo $row['name']; ?></h1>
                <div class="row">
                    <div class="col-md-3">
                        <img src="layout/images/placeholder.jpg" class="img-fluid img-thumbnail" alt="...">
                    </div>
                    <div class="col-md-9 item-info">
                        <h5><?php echo $row['name'] ?></h5>
                        <p><?php echo $row['description'] ?></p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="fas fa-calendar fa-fw"></i>
                                <span>added : </span><?php echo $row['created_at'] ?>
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-money-bill-alt fa-fw"></i>
                                <span>Price : </span>$<?php echo $row['price'] ?>
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-building fa-fw"></i>
                                <span>Made In : </span><?php echo $row['made_in'] ?>
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-tags fa-fw"></i>
                                <span>Category : </span><a href="categories.php?catId=<?php echo $row['category_id'] ?>"><?php echo $row['category'] ?></a>
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-user fa-fw"></i>
                                <span>Added by : </span><a href="#=<?php echo $row['member_id'] ?>"><?php echo $row['username'] ?></a>
                            </li>
                            <li class="list-group-item item-tags">
                                <i class="fas fa-user fa-fw"></i>
                                <span>Tags : </span>
                                <?php 
                                $allTags = explode(',',$row['tags']);
                                    foreach ($allTags as $tag) {
                                        $tag = str_replace(' ','',$tag);
                                        if(!empty($tag))
                                        echo "<a href='tags.php?tagName=".strtolower($tag)."'>".$tag."</a>";
                                }
                                ?>
                            </li>
                        </ul>
                    </div>
                </div>
                <hr class="custom-hr">
                <div class="row">
                    <?php 
                        if(isset($_SESSION['userLogedIn'])){ 
                    ?>
                    <div class="offset-md-3">
                        <div class="add-comment-form">
                            <h5>Add new comment :</h5>
                            <form action="<?php echo $_SERVER['PHP_SELF'] . "?itemId=" . $row['itemId'] ?>" method="POST">
                                <textarea name="commentText" rows="4" required></textarea>
                                <button class="btn btn-primary" type="submit">Add Comment</button>
                            </form>
                            <?php 
                            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                                $commentText = filter_var($_POST['commentText'],FILTER_SANITIZE_STRING);
                                $commentItem = $row['itemId'];
                                $commentUser = $_SESSION['userId'];

                                if(!empty($commentText)){
                                    $stmt = $cnx->prepare("INSERT INTO comments(text,item_id,user_id,created_at,status) VALUES(:commentText,:itemId,:userId,NOW(),0)");
                                    $stmt->execute(array(
                                        'commentText' => $commentText,
                                        'itemId'      => $commentItem,
                                        'userId'      => $commentUser
                                    ));

                                    if($stmt){
                                        echo "<div class='alert alert-success'>The comment has been added</div>";
                                    }
                                    else
                                    {
                                        echo "<div class='alert alert-danger'>The comment has been not added</div>";
                                    }
                                }

                            }
                            }else{
                                echo "<a href='authentication.php'>signIn</a> or  <a href='authentication.php'>signUp</a> to add comment";
                            } 
                            ?>
                        </div>
                    </div>
                </div>
                <hr class="custom-hr">
                <?php 
                    $stmt = $cnx->prepare("SELECT comments.*, users.username FROM comments
                                            INNER JOIN users ON users.userId = comments.user_id
                                            WHERE item_id = ? AND status = 1");
                    $stmt->execute(array($row['itemId']));
                    $comments = $stmt->fetchAll();
                ?>

                <?php foreach ($comments as $comment) { ?>
                    <div class='comment-box'>
                        <div class='row'>
                            <div class='col-sm-2 text-center'>
                                <img src='layout/images/placeholder.jpg' class='img-fluid img-thumbnail rounded-circle' alt='...'>
                                <?php echo $comment['username']; ?>
                            </div>
                            <div class='col-sm-10'>
                                <p class="lead"><?php echo $comment['text']; ?></p>
                            </div>
                        </div>
                    </div>
                    <hr class="custom-hr">
                <?php } ?>
        <?php
    }
    else
    {
        echo "<div class='alert alert-danger'>there is no such item</div>";
    }
    echo "</div>";
    
    include $tpl . "_footer.php";
ob_end_flush();
?>