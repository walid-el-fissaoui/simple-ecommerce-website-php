<?php
session_start();
$pageTitle = "Profile";
    if(isset($_SESSION['userLogedIn']))
    {
        include "init.php";

        $stmt = $cnx->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute(array($sessionUserName));
        $info = $stmt->fetch();

        ?>
            <h1 class='text-center'>My Profile</h1>
            <div class='information mb-3'>
                <div class='container'>
                    <div class='card'>
                        <div class='card-header'>
                            My Information
                        </div>
                        <div class='card-body'>
                            <ul class="list-group list-group-flush">
                                <li>
                                    <i class='fas fa-unlock-alt fa-fw'></i>
                                    <span>username</span> : <?php echo $info['username'] ?>
                                </li>
                                <li>
                                    <i class='fas fa-envelope fa-fw'></i>
                                    <span>email</span> : <?php echo $info['email'] ?>
                                </li>
                                <li>
                                    <i class='fas fa-user fa-fw'></i>
                                    <span>full name</span> : <?php echo $info['fullName'] ?>
                                </li>
                                <li> 
                                    <i class='fas fa-calendar fa-fw'></i>
                                    <span>registre date</span> : <?php echo $info['registred_at'] ?>
                                </li>
                                <li> 
                                    <i class='fas fa-tags fa-fw'></i>
                                    <span>favorite categories</span> : 
                                </li>
                            </ul>
                            <a href="#" class="btn btn-outline-dark edit-btn">edit info</a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="MyItems" class='my-ads mb-3'>
                <div class='container'>
                    <div class='card'>
                        <div class='card-header'>
                            My Ads
                        </div>
                        <div class='card-body'>
                        <?php
                            $items = getAll("*","items"," WHERE member_id = {$info['userId']}","","itemId","ASC");
                            if(!empty($items))
                            {
                                echo "<div class='row'>";
                                foreach ($items as $item) {
                                    echo "<div class='col-sm-6 col-md-3'>";
                                        echo "<div class='card item-box'>";
                                            echo "<div class='card-body'>";
                                                if($item['approval'] == 0) { 
                                                    echo "<span class='approve-status'>waiting approval</span>";
                                                }
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
                                    echo "</div>";
                            }else{
                                echo "There is no items yet! <a href='createItem.php'>create new item</a>";
                            } 
                        ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class='my-comments mb-3'>
                <div class='container'>
                    <div class='card'>
                        <div class='card-header'>
                            Latest Comments
                        </div>
                        <div class='card-body'>
                        <?php
                            $comments = getAll("text","comments","WHERE user_id = {$info['userId']}","","comment_id");
                            if(! empty($comments))
                            {
                                foreach ($comments as $comment) {
                                    echo $comment['text'] . "</br>";
                                }
                            }
                            else
                            {
                                echo "There is no comments yet!";
                            }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        include $tpl . "_footer.php";
    }
    else
    {
        header('Location: index.php');
    }
?>