<?php
session_start();

// print_r($_SESSION);  write list of sessions  
//if i was loged in redirect me to dashboard directly when i request index page 

if (isset($_SESSION['adminLogedIn'])) {

    /*
        ** define a $pageTitle variable before including init.php because it is include functions that will check if the variable exit
        ** so it should be before 
        */

    $pageTitle = "Dashboard";

    // include init page 

    include "init.php";

    $numMembers = '5';
    $numItems = '5';
    $numComments= '5';
    $whereMember = 'roleId != 1';
    $latestMembers = getLatest('*', 'users', 'userId', $numMembers,$whereMember);
    $latestItems   = getLatest('*', 'items', 'itemId', $numItems);

    $stmt = $cnx->prepare("SELECT comments.* , users.username FROM comments INNER JOIN users ON users.userId = comments.user_id LIMIT $numComments");
    $stmt->execute();
    $latestComments = $stmt->fetchAll();

    // Start dashboard 

?>

    <div class="home-stats">
        <div class="container text-center">
            <h1 class="my-5">Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">Total Members <a href="members.php"><span><?php echo countItems('userId', 'users',null,'roleId','1') ?></span></a></div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pendings">Pending Members <a href="members.php?action=index&list=pending"><span><?php echo countItems('regStatus', 'users', '0') ?></span></a></div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">Total Items<a href="items.php?action=index"><span><?php echo countItems('itemId', 'items') ?></span></a></div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments">Total Comments<a href="comments.php?action=index"><span><?php echo countItems('comment_id', 'comments') ?></span></a></div>
                </div>
            </div>
        </div>
    </div>

    <div class="latest">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <span>
                            <i class="fas fa-users"></i>
                            lastest <?php echo $numMembers ?> regestred members
                            </span>
                            <span class='toggle-info'>
                                <i class='fas fa-minus'></i>
                            </span>
                        </div>
                        <ul class="list-group list-group-flush latest-elements">
                        <?php
                            if(! empty($latestMembers))
                            {
                                foreach ($latestMembers as $member) {
                                    echo "<li class='d-flex justify-content-between'>";
                                    echo $member['username'];
                                    if ($member['regStatus'] == 0) {
                                        echo "<a href='members.php?action=activate&userId=" . $member['userId'] . "' class='ml-auto btn-activate'>";
                                        echo "<span class='btn btn-primary'> <i class='fas fa-user-check'></i> activate</span>";
                                        echo "</a>";
                                    }
                                    echo "<a href='members.php?action=edit&userId=" . $member['userId'] . "'>";
                                    echo "<span class='btn btn-success'> <i class='fas fa-edit'></i> edit</span>";
                                    echo "</a>";
                                    echo "</li>";
                                }
                            }
                            else
                            {
                                echo "<li>There is no members yet!</li>";
                            }
                        ?>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <span>
                            <i class="fas fa-tag"></i>
                            Latest <?php echo $numItems ?> items added
                            </span>
                            <span class='toggle-info'>
                                <i class='fas fa-minus'></i>
                            </span>
                        </div>
                        <ul class="list-group list-group-flush latest-elements">
                        <?php
                            if(! empty($latestItems))
                            {
                                foreach ($latestItems as $item) {
                                    echo "<li class='d-flex justify-content-between'>";
                                    echo $item['name'];
                                    if ($item['approval'] == 0) {
                                        echo "<a href='items.php?action=approve&itemId=" . $item['itemId'] . "' class='ml-auto btn-activate'>";
                                        echo "<span class='btn btn-primary'> <i class='fas fa-user-check'></i> approve</span>";
                                        echo "</a>";
                                    }
                                    echo "<a href='items.php?action=edit&itemId=" . $item['itemId'] . "'>";
                                    echo "<span class='btn btn-success'> <i class='fas fa-edit'></i> edit</span>";
                                    echo "</a>";
                                    echo "</li>";
                                }
                            }
                            else
                            {
                                echo "<li>There is no items yet!</li>";
                            }
                        ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class='row my-5'>
                <div class='col-md-6'>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <span>
                            <i class="fas fa-comments"></i>
                            Latest <?php echo $numComments ?> comments added
                            </span>
                            <span class='toggle-info'>
                                <i class='fas fa-minus'></i>
                            </span>
                        </div>
                        <ul class="list-group list-group-flush latest-comments">
                        <?php 
                            if(! empty($latestComments))
                            {
                                foreach ($latestComments as $comment) {
                                    echo "<li class='list-group-item'>";
                                        echo "<div class='comments-box'>";
                                            echo "<span class='comment-writer'>".$comment['username']."</span>";
                                            echo "<p class='comment-text'>".$comment['text']."</p>";
                                        echo "</div>";
                                        echo "<a href='comments.php?action=edit&comId=".$comment['comment_id']."' class='btn btn-primary btn-sm'><i class='fas fa-edit'></i> edit</a>";
                                        echo "<a href='comments.php?action=delete&comId=".$comment['comment_id']."' class='btn btn-danger btn-sm confirm ml-2'><i class='fas fa-times'></i> delete</a>";
                                        if($comment['status'] == 0)
                                        echo "<a href='comments.php?action=approve&comId=".$comment['comment_id']."' class='btn btn-info btn-sm ml-2'><i class='fas fa-check'></i> approve</a>";
                                    echo"</li>";
                                }
                            }
                            else
                            {
                                echo "<li>There is no comments yet!</li>" ;
                            }
                        ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php

    // End dashboard 

    // include footer page 

    include $tpl . '_footer.php';
} else {

    // redirecte to index 

    header('Location: index.php');
}

?>