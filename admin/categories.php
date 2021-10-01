<?php
ob_start();
session_start();

$pageTitle = "Categories";

if (isset($_SESSION['adminLogedIn'])) {
    include "init.php";

    $action = isset($_GET['action']) ? $_GET['action'] : 'index';

    // go to index page 

    if ($action == 'index') {

        echo "<div class='container my-5'>";
        echo "<h1 class='text-center'>Manage Categories</h1>";
        $sort = 'ASC';
        $sort_array = array('ASC', 'DESC');
        if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
            $sort = $_GET['sort'];
        }
        $rows = getAll('*','categories',' WHERE parent = 0 ','','categoryId',$sort);

        if(!empty($rows))
        {

            echo "<div class='card categories'>";
                echo "<div class='card-header d-flex justify-content-between'>";
                    echo "<div><i class='fas fa-edit'></i> Manage Categories</div>";
                    echo "<div class='options'>";
                        echo "<i class='fas fa-sort options-sort-icon'></i> ordering : [";
                            echo "<a href='?sort=ASC'  class='";if ($sort == 'ASC'){echo 'active';};echo "'>Asc</a> | ";
                            echo "<a href='?sort=DESC' class='";if ($sort == 'DESC'){echo 'active';};echo "'>Desc</a> ]";

                            // classic and full view 

                        echo " • <i class='fas fa-eye options-view-icon'></i> view : [ <span class='active' data-view='full'>Full</span> | 
                                                                                    <span data-view='classic'>Classic</span> ]";
                    echo "</div>";
                echo "</div>";
                echo "<ul class='list-group list-group-flush'>";
                    foreach ($rows as $category) {
                        echo "<li class='list-group-item'>";
                            echo "<div class='hidden-buttons'>";
                                echo "<a href='categories.php?action=edit&catId=" . $category['categoryId'] . "' class='btn btn-sm btn-primary'> <i class='fas fa-edit'></i> Edit</a>";
                                echo "<a href='categories.php?action=delete&catId=" . $category['categoryId'] . "' class='confirm btn btn-sm btn-danger'>  <i class='fas fa-times'></i> Delete</a>";
                            echo "</div>";
                            echo "<h5>" . $category['name'] . "</h5>";
                            echo "<div class='full-view'>";
                                echo "<p>";
                                echo empty($category['description']) ? "<i class='text-muted'>no description for this category</i>" : $category['description'];
                                echo "</p>";
                                if ($category['visibility'] == 1) {
                                    echo "<span class='badge badge-warning'><i class='fas fa-eye'></i> hidden</span>";
                                };
                                if ($category['allow_comments'] == 1) {
                                    echo "<span class='badge badge-dark'><i class='fas fa-times'></i> comments disabled</span>";
                                };
                                if ($category['allow_ads'] == 1) {
                                    echo "<span class='badge badge-secondary'><i class='fas fa-times'></i> advertisements disabled</span>";
                                };
                                $children = getAll('*','categories'," where parent = {$category['categoryId']}","","categoryId");
                                if(!empty($children)){
                                    echo "<div class='categories-children-header'>sub categories :</div>";
                                    echo "<ul class='list-group categories-children-list'>";
                                    foreach ($children as $cat) {
                                        echo "<li class='categories-children-list-item'>";
                                            echo "<a href='categories.php?action=edit&catId=".$cat['categoryId']."'>" . $cat['name'] . "</a>";
                                            echo "<a href='categories.php?action=delete&catId=" . $cat['categoryId'] . "' class='confirm btn-delete'>delete</a>";
                                        echo "</li>";
                                    }
                                    echo "</ul>";
                                }
                            echo "</div>";
                        echo "</li>";
                    }
                echo "</ul>";
            echo "</div>";
        }
        else{
            echo "<div class='alert-message'>There is no categories to show!</div>";
        }
        echo "<a href='categories.php?action=create' class='btn btn-primary btn-create-category'> <i class='fas fa-plus'></i> add new category</a>";
        echo "</div>";
    }

    // go to create page

    elseif ($action == 'create') {

        // start create new categorie form

?>

        <div class="container my-5">
            <h1 class="text-center">Create New Categorie</h1>
            <form action="?action=store" method="POST">
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputNameCategorie">Name : </label>
                            <input type="text" class="form-control" id="inputNameCategorie" name="categoryName" placeholder="enter the name of the categorie" required="required" autocomplete="off">
                        </div>
                        <div class="form-group col-md-5 row">
                            <label for="inputOrder" class="col-md-6 col-form-label">order : </label>
                            <input type="number" class="form-control col-md-6" id="inputOrder" name="categoryOrder" min="0" max="255">
                        </div>
                    </div>
                    <div class="form-group col-md-6 ">
                        <label for="inputDescriptionCategorie">description : </label>
                        <textarea class="form-control" id="inputDescriptionCategorie" name="categoryDescription" rows="3" placeholder="describe the categorie"></textarea>
                    </div>
                </div>
                <div class="form-group col-md-6 row">
                    <div class="form-group col-md-4">
                        <label for="inputCategories">parent : </label>
                        <select type="text" name="categoryParent" id="inputCategories" required>
                            <option value="0">...</option>
                            <?php
                            foreach (getAll('*','categories','WHERE parent = 0','','categoryId') as $row) {
                                echo "<option value='" . $row['categoryId'] . "'>" . $row['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-6 row">
                    <label class="col-sm-6"> visible : </label>
                    <div class="form-check col-sm-2">
                        <input class="form-check-input" type="radio" name="categoryVisibility" id="inputVisibleYes" value="0" checked>
                        <label class="form-check-label" for="inputVisibleYes">
                            Yes
                        </label>
                    </div>
                    <div class="form-check col-sm-2">
                        <input class="form-check-input" type="radio" name="categoryVisibility" id="inputVisibleNo" value="1">
                        <label class="form-check-label" for="inputVisibleNo">
                            No
                        </label>
                    </div>
                </div>
                <div class="form-group col-md-6 row">
                    <label class="col-sm-6"> allow comments : </label>
                    <div class="form-check col-sm-2">
                        <input class="form-check-input" type="radio" name="categoryAllowComments" id="inputAllowCommentsYes" value="0" checked>
                        <label class="form-check-label" for="inputAllowCommentsYes">
                            Yes
                        </label>
                    </div>
                    <div class="form-check col-sm-2">
                        <input class="form-check-input" type="radio" name="categoryAllowComments" id="inputAllowCommentsNo" value="1">
                        <label class="form-check-label" for="inputAllowCommentsNo">
                            No
                        </label>
                    </div>
                </div>
                <div class="form-group col-md-6 row">
                    <label class="col-sm-6"> allow advertisements : </label>
                    <div class="form-check col-sm-2">
                        <input class="form-check-input" type="radio" name="categoryAllowAds" id="inputAllowAdvertisementsYes" value="0" checked>
                        <label class="form-check-label" for="inputAllowAdvertisementsYes">
                            Yes
                        </label>
                    </div>
                    <div class="form-check col-sm-2">
                        <input class="form-check-input" type="radio" name="categoryAllowAds" id="inputAllowAdvertisementsNo" value="1">
                        <label class="form-check-label" for="inputAllowAdvertisementsNo">
                            No
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>

        <?php

        // End create new categorie form

    }

    // go to store page

    elseif ($action == 'store') {

        echo "<div class='container my-5'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Store Category</h1>";
            $categoryName = $_POST['categoryName'];
            $categoryOrder = $_POST['categoryOrder'];
            $categoryParent = $_POST['categoryParent'];
            $categoryDescription = $_POST['categoryDescription'];
            $categoryVisibility = $_POST['categoryVisibility'];
            $categoryAllowComments = $_POST['categoryAllowComments'];
            $categoryAllowAds = $_POST['categoryAllowAds'];

            $exits = checkItem('name', 'categories', $categoryName);

            if ($exits == 0) {

                $stmt = $cnx->prepare("INSERT INTO categories(name,description,ordering,parent,visibility,allow_comments,allow_ads) VALUES(:categoryName,:categoryDescription,:categoryOrder,:categoryParent,:categoryVisibility,:categoryAllowComments,:categoryAllowAds)");
                $stmt->execute(array(
                    'categoryName' => $categoryName,
                    'categoryDescription' => $categoryDescription,
                    'categoryOrder' => $categoryOrder,
                    'categoryParent' => $categoryParent,
                    'categoryVisibility' => $categoryVisibility,
                    'categoryAllowComments' => $categoryAllowComments,
                    'categoryAllowAds' => $categoryAllowAds
                ));
                if ($stmt->rowCount()) {
                    $message = "<div class='alert alert-success'>the category has been created</div>";
                    redirectHome($message);
                } else {
                    $message = "<div class='alert alert-danger'>no category has been created</div>";
                    redirectHome($message);
                }
            } else {
                $message = "<div class='alert alert-danger'>this category already exist</div>";
                redirectHome($message, 'back');
            }
        } else {
            $message = "<div class='alert alert-danger'>unauthorized</div>";
            redirectHome($message);
        }
        echo "</div>";
    }

    // go to update page 

    elseif ($action == 'edit') {
        echo "<div class='container my-5'>";
        echo "<h1 class='text-center'>Edit Category</h1>";
        $categoryId = isset($_GET['catId']) && is_numeric($_GET['catId']) ? intval($_GET['catId']) : 0;

        $stmt = $cnx->prepare("SELECT * FROM categories WHERE categoryId = ?");
        $stmt->execute(array($categoryId));
        $row = $stmt->fetch();
        if ($stmt->rowCount() > 0) {
            // Start edit form
        ?>
            <div class="container my-5">
                <form action="?action=update" method="POST">
                    <div class="form-row">
                        <div class="col-md-6">
                            <input type="hidden" name="categoryId" value="<?php echo $row['categoryId'] ?>">
                            <div class="form-group">
                                <label for="inputNameCategorie">Name : </label>
                                <input type="text" class="form-control" id="inputNameCategorie" name="categoryName" placeholder="enter the name of the categorie" autocomplete="off" value="<?php echo $row['name'] ?>">
                            </div>
                            <div class="form-group col-md-5 row">
                                <label for="inputOrder" class="col-md-6 col-form-label">order : </label>
                                <input type="number" class="form-control col-md-6" id="inputOrder" name="categoryOrder" min="0" max="255" value="<?php echo $row['ordering'] ?>">
                            </div>
                        </div>
                        <div class="form-group col-md-6 ">
                            <label for="inputDescriptionCategorie">description : </label>
                            <textarea class="form-control" id="inputDescriptionCategorie" name="categoryDescription" rows="3" placeholder="describe the categorie"><?php echo $row['description'] ?></textarea>
                        </div>
                    </div>
                    <div class="form-group col-md-6 row">
                    <div class="form-group col-md-4">
                        <label for="inputCategories">parent : </label>
                        <select type="text" name="categoryParent" id="inputCategories" required>
                            <option value="0">...</option>
                            <?php
                            foreach (getAll('*','categories','WHERE parent = 0','','categoryId') as $cat) {
                                echo "<option value='" . $cat['categoryId'] . "'";
                                    if ($cat['categoryId'] == $row['parent']){ echo "selected";}
                                echo ">" . $cat['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    </div>
                    <div class="form-group col-md-6 row">
                        <label class="col-sm-6"> visible : </label>
                        <div class="form-check col-sm-2">
                            <input class="form-check-input" type="radio" name="categoryVisibility" id="inputVisibleYes" value="0" <?php if ($row['visibility'] == '0') echo 'checked' ?>>
                            <label class="form-check-label" for="inputVisibleYes">
                                Yes
                            </label>
                        </div>
                        <div class="form-check col-sm-2">
                            <input class="form-check-input" type="radio" name="categoryVisibility" id="inputVisibleNo" value="1" <?php if ($row['visibility'] == '1') echo 'checked' ?>>
                            <label class="form-check-label" for="inputVisibleNo">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-md-6 row">
                        <label class="col-sm-6"> allow comments : </label>
                        <div class="form-check col-sm-2">
                            <input class="form-check-input" type="radio" name="categoryAllowComments" id="inputAllowCommentsYes" value="0" <?php if ($row['allow_comments'] == '0') echo 'checked' ?>>
                            <label class="form-check-label" for="inputAllowCommentsYes">
                                Yes
                            </label>
                        </div>
                        <div class="form-check col-sm-2">
                            <input class="form-check-input" type="radio" name="categoryAllowComments" id="inputAllowCommentsNo" value="1" <?php if ($row['allow_comments'] == '1') echo 'checked' ?>>
                            <label class="form-check-label" for="inputAllowCommentsNo">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-md-6 row">
                        <label class="col-sm-6"> allow advertisements : </label>
                        <div class="form-check col-sm-2">
                            <input class="form-check-input" type="radio" name="categoryAllowAds" id="inputAllowAdvertisementsYes" value="0" <?php if ($row['allow_ads'] == '0') echo 'checked' ?>>
                            <label class="form-check-label" for="inputAllowAdvertisementsYes">
                                Yes
                            </label>
                        </div>
                        <div class="form-check col-sm-2">
                            <input class="form-check-input" type="radio" name="categoryAllowAds" id="inputAllowAdvertisementsNo" value="1" <?php if ($row['allow_ads'] == '1') echo 'checked' ?>>
                            <label class="form-check-label" for="inputAllowAdvertisementsNo">
                                No
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">save</button>
                </form>
            </div>

<?php

            // End edit form

        } else {
            $message = "<div class='alert alert-danger'>there is no such id !</div>";
            redirectHome($message, 'back');
        }
        echo "</div>";
    }

    // go to update page

    elseif ($action == 'update') {
        echo "<div class='container my-5'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update Category</h1>";
            $catId = $_POST['categoryId'];
            $catName = $_POST['categoryName'];
            $catDescription = $_POST['categoryDescription'];
            $catParent = $_POST['categoryParent'];
            $catOredering = $_POST['categoryOrder'];
            $catVisibility = $_POST['categoryVisibility'];
            $catCommenting = $_POST['categoryAllowComments'];
            $catAdvertising = $_POST['categoryAllowAds'];

            // $exists = checkItem('name', 'categories', $catName); // you should adapt this 
            // SELECT COUNT(*) FROM categories WHERE categoryId = ? AND name != ? 
            
            $stmt = $cnx->prepare("SELECT COUNT(*) FROM categories WHERE name = '$catName' AND categoryId != $catId");
            $stmt->execute();
            $exists = $stmt->fetchColumn();

            if ($exists == 0) {

                $stmt = $cnx->prepare("UPDATE categories SET 
                                                        name = ? ,
                                                        description = ? ,
                                                        ordering = ? ,
                                                        parent = ?,
                                                        visibility = ? ,
                                                        allow_comments = ? ,
                                                        allow_ads = ?
                                                        WHERE categoryId  = ?");
                $stmt->execute(array($catName, $catDescription, $catOredering, $catParent, $catVisibility, $catCommenting, $catAdvertising, $catId));

                if ($stmt->rowCount() > 0) {
                    $message = "<div class='alert alert-success'>this category has been updated</div>";
                    redirectHome($message);
                } else {
                    $message = "<div class='alert alert-info'>no cotegory has been updated</div>";
                    redirectHome($message);
                }

            } else {
                $message = "<div class='alert alert-danger'>this category already exist</div>";
                redirectHome($message);
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
            echo "<h1 class='text-center'>Delelte Category</h1>";
            $catId = isset($_GET['catId']) && is_numeric($_GET['catId']) ? $_GET['catId'] : 0;
            $exists = checkItem('categoryId','categories',$catId);
            if($exists > 0){

                $stmt = $cnx->prepare("DELETE FROM categories WHERE categoryId = :catid");
                $stmt->bindParam('catid',$catId);
                $stmt->execute();

                if($stmt->rowCount() > 0)
                {
                    $message = "<div class='alert alert-success'>the category has been deleted</div>";
                    redirectHome($message,'back');
                }
                else
                {
                    $message = "<div class='alert alert-info'>no category has been deleted</div>";
                    redirectHome($message,'back');
                }

            }
            else
            {
                $message = "<div class='alert alert-danger'>there is no such category</div>";
                redirectHome($message,'back');
            }
        echo "</div>";
    }

    include $tpl . "_footer.php";
} else {
    header("location: index.php");
    exit();
}

ob_end_flush();
?>