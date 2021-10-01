<?php
ob_start();
session_start();
$pageTitle = 'Comments';
if ($_SESSION['adminLogedIn']) {
    include 'init.php';
    $action = isset($_GET['action']) ? $_GET['action'] : 'index';

    // go to index page

    if ($action == 'index') {

        echo "<div class='container my-5'>";
        echo "<h1 class='text-center'>Manage Comments</h1>";

        $stmt = $cnx->prepare("SELECT comments.* , items.name AS itemName , users.username 
                                        FROM comments 
                                        INNER JOIN items ON items.itemId = comments.item_id 
                                        INNER JOIN users ON users.userId = comments.user_id");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if($stmt->rowCount() > 0)
        {
?>
            <table class='table table-bordered text-center'>
                <thead>
                    <tr>
                        <th>$Id</th>
                        <th>comment</th>
                        <th>added</th>
                        <th>item</th>
                        <th>member</th>
                        <th>controls</th>
                    </tr>
                </thead>
                <?php
                foreach ($rows as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['comment_id'] . "</td>";
                    echo "<td>"; 
                    if(strlen($row['text']) > 25)
                    echo substr($row['text'],0,25) . "...";
                    else
                    echo $row['text']; 
                    echo "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "<td>" . $row['itemName'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>";
                    echo "<a href='comments.php?action=edit&comId=" . $row['comment_id'] . "' class='btn btn-primary btn-sm mr-2'><i class='fas fa-edit'></i> edit</a>";
                    echo "<a href='comments.php?action=delete&comId=" . $row['comment_id'] . "' class='btn btn-danger btn-sm confirm'><i class='fas fa-times'></i> delete</a>";
                    if ($row['status'] == 0)
                    echo "<a href='comments.php?action=approve&comId=" . $row['comment_id'] . "' class='btn btn-info btn-sm ml-2'><i class='fas fa-check'></i> approve</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        
        <?php
        }
        else
        {
            echo "<div class='alert-message'>There is no comments to show!</div>";
            echo "</div>";
        }
    }

    // go to edit page
    elseif ($action == 'edit') {
        $commentId = isset($_GET['comId']) && is_numeric($_GET['comId']) ? intval($_GET['comId']) : 0;
        $stmt = $cnx->prepare("SELECT * FROM comments WHERE comment_id = ?");
        $stmt->execute(array($commentId));
        $row = $stmt->fetch();
        if ($stmt->rowCount() > 0) {
        ?>
            <div class='container my-5'>
                <h1 class='text-center'>Edit Comment</h1>
                <form action="?action=update" method="POST">
                    <div class="form-row">
                        <input type="hidden" name="commentId" value="<?php echo $commentId ?>">
                        <div class="form-group col-md-12">
                            <label for="inputCommentText">comment :</label>
                            <textarea row='3' class="form-control" id="inputCommentText" name="commentText"><?php echo $row['text'] ?></textarea>
                        </div>
                    </div>
                    <button type="submit" class='btn btn-primary'>save</button>
                </form>
            </div>
<?php
        } else {
            $message = "<div class='alert alert-danger'>There is no such id</div>";
            redirectHome($message);
        }
    }

    // go to update page

    elseif ($action == 'update') {
        echo "<div class='container my-5'>";
        echo "<h1 class='text-center'>Update comment</h1>";
        $commentId = $_POST['commentId'];
        $commentText = $_POST['commentText'];
        $exists = checkItem('comment_id', 'comments', $commentId);
        if ($exists > 0) {
            $stmt = $cnx->prepare("UPDATE comments SET text = ? WHERE comment_id = ?");
            $stmt->execute(array($commentText,$commentId));

            if($stmt->rowCount() > 0)
            {
                $message = "<div class='alert alert-success'>The comment has been updated</div>";
                redirectHome($message);
            }
            else
            {
                $message = "<div class='alert alert-info'>No comment has been updated</div>";
                redirectHome($message);
            }
        } 
        else {
            $message = "<div class='alert alert-danger'>There is no such id</div>";
            redirectHome($message);
        }
        echo "</div>";
    }

    // go to delete page

    elseif($action == 'delete')
    {
        echo "<div class='container my-5'>";
            echo "<h1 class='text-center'>Delete Comment</h1>";
            $commentId = isset($_GET['comId']) && is_numeric($_GET['comId']) ? intval($_GET['comId']) : 0;
            $exists = checkItem('comment_id','comments',$commentId);
            if($exists > 0)
            {
                $stmt = $cnx->prepare("DELETE FROM comments WHERE comment_id = :commentId");
                $stmt->bindParam('commentId',$commentId);
                $stmt->execute();
                if($stmt->rowCount() > 0 )
                {
                    $message = "<div class='alert alert-success'>The comment has been deleted</div>";
                    redirectHome($message);
                }
                else
                {
                    $message = "<div class='alert alert-info'>No comment has been deleted</div>";
                    redirectHome($message);
                }
            }
            else
            {
                $message = "<div class='alert alert-danger'>There is no such id</div>";
                redirectHome($message);
            }

        echo "</div>";
    }

    // go to approve page

    elseif($action == 'approve')
    {
        echo "<div class='container my-5'>";
            echo "<h1 class='text-center'>Approve Comment</h1>";
            $commentId = isset($_GET['comId']) && is_numeric($_GET['comId']) ? $_GET['comId'] : 0;
            $exists = checkItem('comment_id','comments',$commentId);
            if($exists > 0)
            {
                $stmt = $cnx->prepare("UPDATE comments SET status = 1 WHERE comment_id = ?");
                $stmt->execute(array($commentId));
                if($stmt->rowCount() > 0)
                {
                    $message = "<div class='alert alert-success'>The comment has been deleted</div>";
                    redirectHome($message);
                }
                else
                {
                    $message = "<div class='alert alert-info'>No comment has been deleted</div>";
                    redirectHome($message);
                }
            }
            else
            {
                $message = "<div class='alert alert-danger'>There is no such id</div>";
                redirectHome($message);
            }
        echo "</div>";
    }

    include $tpl . '_footer.php';
} else {
    header("location:index.php");
}
ob_end_flush();
?>