<?php
ob_start();
    session_start();
    $pageTitle = "Create New Item";
    if(isset($_SESSION['userLogedIn'])){
        include "init.php";

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $itemName        = filter_var($_POST['itemName'],FILTER_SANITIZE_STRING); 
            $itemDescription = filter_var($_POST['itemDescription'],FILTER_SANITIZE_STRING); 
            $itemPrice       = filter_var($_POST['itemPrice'],FILTER_SANITIZE_NUMBER_INT); 
            $itemCountry     = filter_var($_POST['itemCountry'],FILTER_SANITIZE_STRING);
            $itemStatus      = filter_var($_POST['itemStatus'],FILTER_SANITIZE_NUMBER_INT);
            $itemCategory    = filter_var($_POST['itemCategory'],FILTER_SANITIZE_NUMBER_INT);
            $itemTags        = filter_var($_POST['itemTags'],FILTER_SANITIZE_STRING);

            $requestRulesErrors = array();

            if(strlen($itemName) < 4) $requestRulesErrors[] = 'The item name should be more than 4 chars';
            if(strlen($itemDescription) < 10) $requestRulesErrors[] = 'The item description should be more than 10 chars';
            if(strlen($itemCountry) < 2) $requestRulesErrors[] = 'The item country should be more than 2 chars';
            if(empty($itemPrice)) $requestRulesErrors[] = 'The item price couldn\'t be empty';
            if(empty($itemStatus)) $requestRulesErrors[] = 'The item status couldn\'t be empty';
            if(empty($itemCategory)) $requestRulesErrors[] = 'The item category couldn\'t be empty';

            if(empty($requestRulesErrors)){

                $stmt = $cnx->prepare("INSERT INTO items(name,description,price,made_in,status,category_id,member_id,created_at,tags) VALUES(:name,:description,:price,:country,:status,:category,:member,now(),:tags)");
                $stmt->execute(array(
                    'name'        => $itemName,
                    'description' => $itemDescription,
                    'price'       => $itemPrice,
                    'country'     => $itemCountry,
                    'status'      => $itemStatus,
                    'category'    => $itemCategory,
                    'member'      => $_SESSION['userId'],
                    'tags'        => $itemTags,
                ));

                if($stmt){
                    $message = 'The item added succesfully';
                }else{
                    $requestRulesErrors[] = 'The item has not been added';
                }
            }
        }

        ?>
            <h1 class='text-center'><?php echo $pageTitle ?></h1>
            <div class='create-ad block'>
                <div class='container'>
                    <div class='card m-2'>
                        <div class='card-header'>
                        <?php echo $pageTitle ?>
                        </div>
                        <div class='card-body'>
                            <div class='row'>
                                <div class='col-md-8'>
                                    <div class="container my-5">
                                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class='main-form'>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputItemName">name : </label>
                                                    <input 
                                                        type="text" 
                                                        name="itemName" 
                                                        id="inputItemName"
                                                        data-class='.live-title' 
                                                        class="form-control live" 
                                                        placeholder="write the name of the product" 
                                                        required
                                                        autocomplete="off">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputItemDescription">description : </label>
                                                    <textarea 
                                                        type="text" 
                                                        name="itemDescription" 
                                                        id="inputItemDescription"
                                                        data-class='.live-description' 
                                                        class="form-control live" 
                                                        placeholder="write the description of the product here" 
                                                        required
                                                        rows="3" 
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
                                                        data-class=".live-price" 
                                                        class="form-control live" 
                                                        placeholder="write the item's price" 
                                                        required
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
                                                        required
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-4">
                                                    <label for="inputItemPrice">Status : </label>
                                                    <select type="text" name="itemStatus" id="inputItemStatus" required>
                                                        <option value="">...</option>
                                                        <option value="1">New</option>
                                                        <option value="2">like new</option>
                                                        <option value="3">used</option>
                                                        <option value="4">Very old</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="inputItemCategory">Categorie : </label>
                                                    <select type="text" name="itemCategory" id="inputItemCategory" required>
                                                        <option value="">...</option>
                                                        <?php
                                                        foreach (getAll('*','categories','','','categoryId') as $row) {
                                                            echo "<option value='" . $row['categoryId'] . "'>" . $row['name'] . "</option>";
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
                                </div>
                                <div class='col-md-4'>
                                        <div class='card item-box live-preview'>
                                            <div class='card-body'>
                                                <div class='price-tag'>$<span class='live-price'>0</span></div> 
                                                <img src='layout/images/placeholder.jpg' class='img-fluid' alt='...'>
                                                <h5 class='card-title live-title'>name</h5>
                                                <p class='card-text live-description'>description</p>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="m-2">
                            <?php
                                if(!empty($requestRulesErrors)){
                                    foreach ($requestRulesErrors as $error) {
                                        echo "<div class='alert alert-danger'>".$error."</div>";
                                    }
                                }
                                if(isset($message)){
                                    echo "<div class='alert alert-success'>".$message."</div>";
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        include $tpl . "_footer.php";
    }
    else{
        header('Location:index.php');
        exit();
    }
ob_end_flush();
?>