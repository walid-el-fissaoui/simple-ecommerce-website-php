<?php
ob_start();
session_start();

$pageTitle = 'Items';

if ($_SESSION['adminLogedIn']) {
    include "init.php";

    $action = isset($_GET['action']) ? $_GET['action'] : 'index';

    // go to index page 

    if ($action == 'index') {
        echo "<div class='container my-5'>";
        echo "<h1 class='text-center'>Manage Items</h1>";

        $stmt = $cnx->prepare("SELECT items.* , categories.name AS categoryName , users.username AS memberName 
                                        FROM items
                                        INNER JOIN categories ON categories.categoryId = items.category_id
                                        INNER JOIN users ON users.userId = items.member_id");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        
        if($stmt->rowCount() > 0)
        {
?>
        <table class="table table-bordered text-center items-table">
            <thead>
                <tr>
                    <th>#Id</th>
                    <th>name</th>
                    <th>description</th>
                    <th>price</th>
                    <th>adding</th>
                    <th>category</th>
                    <th>member</th>
                    <th>control</th>
                </tr>
            </thead>
            <?php
            foreach ($rows as $row) {
                echo "<tr>";
                echo "<td>" . $row['itemId'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>"; 
                if (strlen($row['description']) > 25) 
                echo substr($row['description'],0,25) . "..."; 
                else
                echo $row['description'];
                echo"</td>";
                echo "<td>" . $row['price'] . "</td>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "<td>" . $row['categoryName'] . "</td>";
                echo "<td>" . $row['memberName'] . "</td>";
                echo "<td>";
                echo "<a href='items.php?action=edit&itemId=" . $row['itemId'] . "' class='btn btn-primary btn-sm'><i class='fas fa-edit mr-2'></i>edit</a>";
                echo "<a href='items.php?action=delete&itemId=" . $row['itemId'] . "' class='btn btn-danger btn-sm confirm ml-2'><i class='fas fa-times mr-2'></i>delete</a>";
                if($row['approval'] == 0)
                echo "<a href='items.php?action=approve&itemId=" . $row['itemId'] . "' class='btn btn-info btn-sm ml-2'><i class='fas fa-check mr-2'></i>approve</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <a href="items.php?action=create" class="btn btn-sm btn-primary"><i class='fas fa-plus mr-2'></i>add new item</a>
        
    <?php
    }
        else
        {
        echo "<div class='alert-message'>There is no items to show!</div>";
        echo "<a href='items.php?action=create' class='btn btn-sm btn-primary'><i class='fas fa-plus mr-2'></i>add new item</a>";
        echo "</div>";
        }
    }

    // go to create page

    elseif ($action == 'create') {
        // Start create new item form
    ?>
        <div class="container my-5">
            <h1 class="text-center">Create New Item</h1>
            <form action="?action=store" method="POST">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputItemName">name : </label>
                        <input 
                            type="text" 
                            name="itemName" 
                            id="inputItemName" 
                            class="form-control" 
                            placeholder="write the name of the product" 
                            required="required" 
                            autocomplete="off">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputItemDescription">description : </label>
                        <textarea 
                            type="text" 
                            name="itemDescription" 
                            id="inputItemDescription" 
                            class="form-control" 
                            placeholder="write the description of the product here" 
                            rows="3" 
                            required="required" 
                            autocomplete="off"></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputItemPrice">price : </label>
                        <input 
                            type="text" 
                            name="itemPrice" 
                            id="inputItemPrice" 
                            class="form-control" 
                            placeholder="write the item's price" 
                            required="required" 
                            autocomplete="off">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputItemCountry">country : </label>
                        <input 
                            type="text" 
                            name="itemCountry" 
                            id="inputItemCountry" 
                            class="form-control" 
                            placeholder="write the item's country here" 
                            required="required" 
                            autocomplete="off">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="inputItemPrice">Status : </label>
                        <select type="text" name="itemStatus" id="inputItemStatus" required="required">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">like new</option>
                            <option value="3">used</option>
                            <option value="4">Very old</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputItemMemeber">Member : </label>
                        <select type="text" name="itemMember" id="inputItemMemeber" required="required">
                            <option value="0">...</option>
                            <?php
                            $members = getAll("*","users"," WHERE roleId != 1 ","","userId");
                            foreach ($members as $row) {
                                echo "<option value='" . $row['userId'] . "'>" . $row['username'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputItemCategory">Categorie : </label>
                        <select type="text" name="itemCategory" id="inputItemCategory" required="required">
                            <option value="0">...</option>
                            <?php
                            $categories = getAll("*","categories"," WHERE parent = 0 ","","categoryId");
                            foreach ($categories as $row) {
                                echo "<option value='" . $row['categoryId'] . "'>" . $row['name'] . "</option>";
                                $categoryChildren = getAll("*","categories"," WHERE parent = {$row['categoryId']} ", "","categoryId");
                                foreach ($categoryChildren as $cat) {
                                    echo "<option value='" . $cat['categoryId'] . "'>••••> " . $cat['name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="inputItemTags">tags : </label>
                        <input 
                            type="text" 
                            name="itemTags" 
                            id="inputItemTags" 
                            class="form-control" 
                            placeholder="separate tags with comma (,)" 
                            required="required" 
                            autocomplete="off">
                    </div>
                </div>
                <button type="submit" class="btn btn-sm btn-primary"><i class='fas fa-plus mr-2'></i>add new item</button>
            </form>
        </div>

        <?php
        // End create new item form
    }

    //go to store page

    elseif ($action == 'store') {

        echo "<div class='container my-5'>";
        echo "<h1 class='text-center'>Store Item</h1>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['itemName'];
            $description = $_POST['itemDescription'];
            $price = $_POST['itemPrice'];
            $country = $_POST['itemCountry'];
            $status = $_POST['itemStatus'];
            $member = $_POST['itemMember'];
            $category = $_POST['itemCategory'];
            $tags = $_POST['itemTags'];

            $requestRulesErrors = array();
            if (empty($name)) $requestRulesErrors[] = "the name couldn't be <strong>empty</strong>";
            if (empty($description)) $requestRulesErrors[] = "the description couldn't be <strong>empty</strong>";
            if (empty($price)) $requestRulesErrors[] = "the price couldn't be <strong>empty</strong>";
            if (empty($country)) $requestRulesErrors[] = "the country couldn't be <strong>empty</strong>";
            if (empty($status)) $requestRulesErrors[] = "the status couldn't be <strong>empty</strong>";
            if (empty($member)) $requestRulesErrors[] = "the member couldn't be <strong>empty</strong>";
            if (empty($category)) $requestRulesErrors[] = "the category couldn't be <strong>empty</strong>";

            if (empty($requestRulesErrors)) {
                $stmt = $cnx->prepare("INSERT INTO items(name,description,price,created_at,made_in,status,member_id,category_id,tags) VALUES (:name,:description,:price,now(),:country,:status,:member,:category,:tags)");
                $stmt->execute(array(
                    'name'          => $name,
                    'description'   => $description,
                    'price'         => $price,
                    'country'       => $country,
                    'status'        => $status,
                    'member'        => $member,
                    'category'      => $category,
                    'tags'          => $tags,
                ));

                if ($stmt->rowCount() > 0) {
                    $message = "<div class='alert alert-success'>The item has been created</div>";
                    redirectHome($message);
                } else {
                    $message = "<div class='alert alert-danger'>no item has been created</div>";
                    redirectHome($message);
                }
            } else {
                foreach ($requestRulesErrors as $error) {
                    echo "<div class='alert alert-danger'>";
                    echo $error;
                    echo "</div>";
                }
            }
        } else {
            $message = "<div class='alert alert-danger'>unauthorized</div>";
            redirectHome($message);
        }

        echo "</div>";
    }

    // go to edit page

    elseif ($action == 'edit') {
        echo "<div class='container my-5'>";
        echo "<h1 class='text-center'>Edit Member</h1>";
        $itemId = isset($_GET['itemId']) && is_numeric($_GET['itemId']) ? intval($_GET['itemId']) : 0;

        $stmt = $cnx->prepare("SELECT * FROM items WHERE itemId = ?");
        $stmt->execute(array($itemId));
        $item = $stmt->fetch();
        if ($stmt->rowCount() > 0) {
        ?>
            <form action="?action=update" method="POST">
                <div class="form-row">
                    <input type="hidden" name="itemId" value="<?php echo $item['itemId'] ?>">
                    <div class="form-group col-md-6">
                        <label for="inputItemName">name : </label>
                        <input 
                            type="text" 
                            name="itemName" 
                            id="inputItemName" 
                            class="form-control" 
                            placeholder="write the name of the product" 
                            autocomplete="off" 
                            value="<?php echo $item['name'] ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputItemDescription">description : </label>
                        <textarea 
                            type="text" 
                            name="itemDescription" 
                            id="inputItemDescription" 
                            class="form-control" 
                            placeholder="write the description of the product here" 
                            rows="3" 
                            autocomplete="off"><?php echo $item['description'] ?></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputItemPrice">price : </label>
                        <input 
                            type="text" 
                            name="itemPrice" 
                            id="inputItemPrice"
                            class="form-control" 
                            placeholder="write the item's price" 
                            autocomplete="off" 
                            value="<?php echo $item['price'] ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputItemCountry">country : </label>
                        <input 
                            type="text" 
                            name="itemCountry" 
                            id="inputItemCountry" 
                            class="form-control" 
                            placeholder="write the item's country here" 
                            autocomplete="off" 
                            value="<?php echo $item['made_in'] ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="inputItemPrice">Status : </label>
                        <select type="text" name="itemStatus" id="inputItemStatus" required="required">
                            <option value="1" <?php if ($item['status'] == 1) echo 'selected' ?>>New</option>
                            <option value="2" <?php if ($item['status'] == 2) echo 'selected' ?>>like new</option>
                            <option value="3" <?php if ($item['status'] == 3) echo 'selected' ?>>used</option>
                            <option value="4" <?php if ($item['status'] == 4) echo 'selected' ?>>Very old</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputItemMemeber">Member : </label>
                        <select type="text" name="itemMember" id="inputItemMemeber" required="required">
                            <?php
                            $stmt = $cnx->prepare("SELECT * FROM users");
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            foreach ($rows as $row) {
                            ?>
                                <option value="<?php echo $row['userId'] ?>" <?php if ($item['member_id'] == $row['userId']) echo 'selected' ?>><?php echo $row['username'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputItemCategory">Categorie : </label>
                        <select type="text" name="itemCategory" id="inputItemCategory" required="required">
                            <?php
                            $categories = getAll("*","categories"," WHERE parent = 0 ", "","categoryId");
                            foreach ($categories as $row) {
                            ?>
                                <option value="<?php echo $row['categoryId'] ?>" <?php if ($item['category_id'] == $row['categoryId']) echo 'selected' ?>><?php echo $row['name'] ?></option>
                            <?php
                                $categoryChildren = getAll("*","categories"," WHERE parent = {$row['categoryId']} ", "","categoryId");
                                foreach ($categoryChildren as $cat) {
                                ?>
                                    <option value="<?php echo $cat['categoryId'] ?>" <?php if ($item['category_id'] == $cat['categoryId']) echo 'selected' ?>>•••><?php echo $cat['name'] ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="inputItemTags">tags : </label>
                        <input 
                            type="text" 
                            name="itemTags" 
                            id="inputItemTags" 
                            class="form-control" 
                            placeholder="separate tags with comma (,)" 
                            required="required" 
                            autocomplete="off"
                            value="<?php echo $item['tags'] ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-sm btn-primary">save</button>
                <div class="card mt-5 comments">
                    <div class="card-header">
                    Manage [<?php echo $item['name'] ?>] comments
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php 
                            $stmt = $cnx->prepare("SELECT comments.* , users.username FROM comments INNER JOIN users ON users.userId = comments.user_id WHERE item_id = ?");
                            $stmt->execute(array($itemId));
                            $comments = $stmt->fetchAll();
                            if($stmt->rowCount() > 0)
                            {
                                foreach ($comments as $comment) {
                                    echo "<li class='list-group-item'>"; 
                                        echo "<div class='hidden-buttons'>";
                                        echo "<a href='comments.php?action=edit&comId=" . $comment['comment_id'] . "' class='btn btn-sm btn-primary'> <i class='fas fa-edit'></i> Edit</a>";
                                        echo "<a href='comments.php?action=delete&comId=" . $comment['comment_id'] . "' class='confirm btn btn-sm btn-danger'>  <i class='fas fa-times'></i> Delete</a>";
                                        echo "</div>";
                                        echo "<span class='card-text'>".$comment['created_at']."</span> • by : ";
                                        echo "<span>".$comment['username']."</span>";
                                        echo "<p class='card-text'>".$comment['text']."</p>";
                                    echo"</li>";
                                }
                            }
                            else
                            {
                                echo "<li class='list-group-item'>no comments yet!</li>";
                            }
                        ?>
                        
                    </ul>
                </div>
            </form>
<?php
        } else {
            $message = "<div class='alert alert-danger'>There is no such id</div>";
            redirectHome($message);
        }
        echo "</div>";
    }

    // go to update page

    elseif ($action == 'update') {
        echo "<div class='container my-5'>";
        echo "<h1 class='text-center'>Update Item</h1>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $itemId           = $_POST['itemId'];
            $itemName         = $_POST['itemName'];
            $itemDescription  = $_POST['itemDescription'];
            $itemPrice        = $_POST['itemPrice'];
            $itemCountry      = $_POST['itemCountry'];
            $itemStatus       = $_POST['itemStatus'];
            $itemCategory     = $_POST['itemCategory'];
            $itemMember       = $_POST['itemMember'];
            $itemTags         = $_POST['itemTags'];

            $requestRulesErrors = array();

            if (empty($itemName)) $requestRulesErrors[]          = "the item name couldn't be <strong>empty</strong>";
            if (empty($itemDescription)) $requestRulesErrors[]   = "the item description couldn't be <strong>empty</strong>";
            if (empty($itemPrice)) $requestRulesErrors[]         = "the item price couldn't be <strong>empty</strong>";
            if (empty($itemCountry)) $requestRulesErrors[]       = "the item country couldn't be <strong>empty</strong>";
            if (empty($itemStatus)) $requestRulesErrors[]        = "the item status couldn't be <strong>empty</strong>";
            if (empty($itemCategory)) $requestRulesErrors[]      = "the item category couldn't be <strong>empty</strong>";
            if (empty($itemMember)) $requestRulesErrors[]        = "the item member couldn't be <strong>empty</strong>";

            if (!empty($requestRulesErrors)) {
                $message = '';
                foreach ($requestRulesErrors as $error) {
                    $message .= "<div class='alert alert-danger'>$error</div>";
                }
                redirectHome($message, 'back' ,20);
            } else {
                $stmt = $cnx->prepare("UPDATE items SET name = ? , description = ? , price = ? , made_in = ? ,status = ? ,category_id = ? , member_id = ? , tags = ? WHERE itemId = ?");
                $stmt->execute(array(
                    $itemName,
                    $itemDescription,
                    $itemPrice,
                    $itemCountry,
                    $itemStatus,
                    $itemCategory,
                    $itemMember,
                    $itemTags,
                    $itemId
                ));

                if ($stmt->rowCount() > 0) {
                    $message = "<div class='alert alert-success'>The item has been updated</div>";
                    redirectHome($message);
                } else {
                    $message = "<div class='alert alert-info'>No item has been updated</div>";
                    redirectHome($message);
                }
            }
        } else {
            $message = "<div class='alert alert-danger'>unauthorized</div>";
            redirectHome($message);
        }
        echo "</div>";
    }

    // go to delete page

    elseif($action == 'delete')
    {
        echo "<div class='container my-5'>";
            echo "<h1 class='text-center'>Delete Member</h1>";
            $itemId = isset($_GET['itemId']) && is_numeric($_GET['itemId']) ? intval($_GET['itemId']) : 0;
            $exists = checkItem('itemId','items',$itemId);
            if($exists > 0)
            {
                $stmt = $cnx->prepare("DELETE FROM items WHERE itemId = :itemId");
                $stmt->bindParam('itemId',$itemId);
                $stmt->execute();

                if($stmt->rowCount() > 0)
                {
                    $message = "<div class='alert alert-success'>The item has been deleted</div>";
                    redirectHome($message);
                }
                else
                {
                    $message = "<div class='alert alert-info'>No item has been deleted</div>";
                    redirectHome($message);
                }
            }
            else{
                $message = "<div class='alert alert-danger'>There is no such id</div>";
                redirectHome($message);
            }
        echo "</div>";
    }

    // go to approve page

    elseif($action == 'approve')
    {
        echo "<div class='container my-5'>";
            echo "<h1 class='text-center'>Approve Item</h1>";
            $itemId = isset($_GET['itemId']) && is_numeric($_GET['itemId']) ? intval($_GET['itemId']) : 0;
            $exists = checkItem("itemId",'items',$itemId);
            if($exists > 0)
            {
                $stmt = $cnx->prepare("UPDATE items SET approval = 1 WHERE itemId = :itemId");
                $stmt->bindParam('itemId',$itemId);
                $stmt->execute();

                if($stmt->rowCount() > 0)
                {
                    $message = "<div class='alert alert-success'>The item has been approved</div>";
                    redirectHome($message);
                }
                else
                {
                    $message = "<div class='alert alert-inf'>No item has been approved</div>";
                    redirectHome($message);
                }
            }
            else
            {
                $message = "<div class='alert alert-danger'>unauthorized</div>";
                redirectHome($message);
            }
        echo "</div>";
    }

    include $tpl . "_footer.php";
} else {
    header('location:index.php');
    exit();
}

ob_end_flush();
?>