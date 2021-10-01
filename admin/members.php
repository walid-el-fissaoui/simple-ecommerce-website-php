<?php

/*
** manage members page
** add | edit | delete members from here 
*/

$pageTitle = "Members";
session_start();

if (isset($_SESSION['adminLogedIn'])) {
    include 'init.php';

    $action = isset($_GET['action']) ? $_GET['action'] : 'index';

    // go to manage page

    if ($action == 'index') {

        echo "<div class='container my-5'>";
        echo "<h1 class='text-center mb-5'>Manage Members</h1>";

        // check if there is a list variable in the query string and if it equal pending , to add this condition inside the sql query that select users

        $query = isset($_GET['list']) && $_GET['list'] == 'pending' ? 'AND regStatus = 0' : '';

        // select members from database , except admin

        $stmt = $cnx->prepare("SELECT * FROM users WHERE roleId != 1 $query");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if($stmt->rowCount() > 0)
        {
?>
            <table class="table table-bordered text-center manage-members">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>avatar</th>
                        <th>username</th>
                        <th>email</th>
                        <th>fullname</th>
                        <th>registred date</th>
                        <th>control</th>
                    </tr>
                </thead>
                <?php
                foreach ($rows as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['userId'] . "</td>";
                    echo "<td>";  
                        if(!empty($row['avatar']))
                            echo "<img src='uploads\avatars\\".$row['avatar']."' alt='' />";
                        else
                            echo "<img src='layout\images\placeholder.jpg' alt='' />";
                    echo "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['fullName'] . "</td>";
                    echo "<td>" . $row['registred_at'] . "</td>";
                    echo "<td>
                        <a href='members.php?action=edit&userId=" . $row['userId'] . "' class='btn btn-success'><i class='fas fa-edit'></i> edit</a>
                        <a href='members.php?action=delete&userId=" . $row['userId'] . "' class='btn btn-danger confirm'><i class='fas fa-times'></i> delete</a>";
                    if ($row['regStatus'] == 0)
                        echo "<a href='members.php?action=activate&userId=" . $row['userId'] . "' class='btn btn-info btn-activate'><i class='fas fa-user-check'></i> activate</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
            <a href="members.php?action=create" class="btn btn-primary"><i class="fas fa-plus"></i> add new member</a>
        

    <?php
        }
        else{

            echo "<div class='alert-message'>There is no members to show!</div>";
            echo "<a href='members.php?action=create' class='btn btn-primary'><i class='fas fa-plus'></i> add new member</a>";
            echo "</div>";
        }
    }

    // go to create page 

    elseif ($action == 'create') {

        // add new member form

    ?>
        <div class="container my-5">
            <h1 class="text-center mb-5">Create Member</h1>
            <!-- add the action of store and method of post to send the form data to the store page -->
            <form action="?action=store" method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <!-- username input -->
                    <div class="form-group col-md-6">
                        <label for="inputUserName">username :</label>
                        <input type="text" class="form-control" id="inputUserName" name="inputUserName" autocomplete="off" placeholder="username will be used for login" required="required">
                    </div>
                    <!-- password input -->
                    <div class="form-group col-md-6">
                        <label for="inputPassword">password :</label>
                        <input type="password" class="inputPassword form-control" id="inputPassword" name="inputPassword" autocomplete="new-password" placeholder="password should be complex of digits , symbols and characters" required="required">
                        <i class="show-password fas fa-eye"></i>
                    </div>
                </div>
                <div class="form-row">
                    <!-- email input -->
                    <div class="form-group col-md-6">
                        <label for="inputEmail">email :</label>
                        <input type="email" class="form-control" id="inputEmail" name="inputEmail" autocomplete="off" placeholder="email should be a valide one" required="required">
                    </div>
                    <!-- fullname input -->
                    <div class="form-group col-md-6">
                        <label for="inputFullName">full name :</label>
                        <input type="text" class="form-control" id="inputFullName" name="inputFullName" autocomplete="off" placeholder="full name will appear on your profile" required="required">
                    </div>
                </div>
                <div class="form-row">
                    <!-- avatar input -->
                    <div class="form-group col-md-6">
                        <label for="inputAvatar">user avatar :</label>
                        <input type="file" class="form-control" id="inputAvatar" name="inputAvatar"  required>
                    </div>
                </div>
                <!-- create button -->
                <button type="submit" class="btn btn-primary">create</button>
            </form>
        </div>


        <?php
    }

    // go to store page 

    elseif ($action == 'store') {

        echo "<div class='container my-5'>";

        // check if the user came with post http method 

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo "<h1 class='text-center mb-5'>Store Member</h1>";

            // upload variables

            $avatarName = $_FILES['inputAvatar']['name'];
            $avatarType = $_FILES['inputAvatar']['type'];
            $avatarTmp  = $_FILES['inputAvatar']['tmp_name'];
            $avatarSize = $_FILES['inputAvatar']['size'];

            // allowed extenstions

            $avatarAllowedExtensions = array('png','jpeg','jpg','gif');

            // get file extension 

            if(!empty($avatarName))
            {
                $avatarExtension = explode('.',$avatarName);
                $avatarExtension = end($avatarExtension);
                $avatarExtension = strtolower($avatarExtension);
            }

            // Get values from the form of the create

            $userName = $_POST['inputUserName'];
            $password = $_POST['inputPassword'];
            $email = $_POST['inputEmail'];
            $fullName = $_POST['inputFullName'];
            $hashedPassword = sha1($_POST['inputPassword']);

            // request validation 

            $requestRulesErrors = array();
            if (empty($userName))   $requestRulesErrors[] = "username can't be <strong>empty</strong>";
            else {
                if (strlen($userName) < 4)
                    $requestRulesErrors[] = "username can't be <strong>less</strong> than <strong>4</strong> characters";
                elseif (strlen($userName) > 20)
                    $requestRulesErrors[] = "username can't be <strong>more</strong> than <strong>20</strong> characters";
            }
            if (empty($email))      $requestRulesErrors[] = "email can't be <strong>empty</strong> </div>";
            if (empty($password))   $requestRulesErrors[] = "password can't be <strong>empty</strong> </div>";
            if (empty($fullName))   $requestRulesErrors[] = "fullName can't be <strong>empty</strong> </div>";
            else {
                if (strlen($fullName) < 4)
                    $requestRulesErrors[] = "fullName can't be <strong>less</strong> than <strong>4</strong> characters";
                elseif (strlen($fullName) > 20)
                    $requestRulesErrors[] = "fullName can't be <strong>more</strong> than <strong>20</strong> characters";
            }
            if(!empty($avatarName) && !in_array($avatarExtension,$avatarAllowedExtensions))
            {
                $requestRulesErrors[] = "this extention is not <strong>allowed</strong>";
            }
            if(empty($avatarName))
            {
                $requestRulesErrors[] = "avatar can't be <strong>empty</strong>";
            }
            if(!empty($avatarSize) && $avatarSize > 4194304)
            {
                $requestRulesErrors[] = "avatar can't be larger than <strong>4MB</strong>";
            }

            foreach ($requestRulesErrors as $error)
                echo "<div class = 'alert alert-danger'>" . $error . "</div>";

            if (empty($requestRulesErrors)) {

                $avatar = rand(0,1000000000) . '_' . $avatarName;
                move_uploaded_file($avatarTmp,'uploads\avatars\\' . $avatar);


                $exists = checkItem("username", "users", $userName);

                if ($exists > 0) {
                    $message =  "<div class='alert alert-danger mt-5'>This username already exist</div>";
                    redirectHome($message, 'back');
                } else {

                    // insert new member into database 

                    $stmt = $cnx->prepare("INSERT INTO users(username,password,email,fullName,regStatus,registred_at,avatar) VALUES(:username , :password , :email , :fullname , 1 , now(),:avatar)");
                    $stmt->execute(array(
                        'username' => $userName,
                        'password' => $hashedPassword,
                        'email'    => $email,
                        'fullname' => $fullName,
                        'avatar'   => $avatar,
                    ));

                    // state message

                    if ($stmt->rowCount() > 0) {
                        $message = "<div class='alert alert-success'>member has been created</div>";
                        redirectHome($message, 'back');
                    } else {
                        $message = "<div class='alert alert-info'>no member has been created</div>";
                        redirectHome($message, 'back');
                    }
                }
            }
        }

        // If the user does not come with post http method

        else {
            $message = "<div class='alert alert-danger'>unauthorized</div>";
            redirectHome($message);
        }
        echo "</div>";
    }

    // go to edit page 

    elseif ($action == 'edit') {

        // check if there is a user id sent , and its type  

        $user = isset($_GET['userId']) && is_numeric($_GET['userId']) ? intval($_GET['userId']) : 0;

        // select the user from database that has user id such the valude sent in the request

        $stmt = $cnx->prepare("SELECT * FROM users where userId = ? Limit 1");
        $stmt->execute(array($user));

        /** fetch , retreive the row from the sql request  */

        $row = $stmt->fetch();

        // retreive the count of the rows in the result

        $count = $stmt->rowCount();

        // if the count of rows greater than zero , so there is a row with this id 

        if ($count > 0) {

            // show the form of the profile edit 

        ?>
            <div class="container my-5">
                <h1 class="text-center mb-5">Edit Member</h1>
                <!-- add the action of update and method of post to send the form data to the update page -->
                <form action="?action=update" method="POST">
                    <div class="form-row">
                        <!--add a hidden input to send userId with the form -->
                        <input type="hidden" name="inputUserId" value="<?php echo $user ?>">
                        <!-- username input -->
                        <div class="form-group col-md-6">
                            <label for="inputUserName">username :</label>
                            <input type="text" class="form-control" id="inputUserName" name="inputUserName" autocomplete="off" value="<?php echo $row['username'] ?>" required="required">
                        </div>
                        <!-- password input -->
                        <div class="form-group col-md-6">
                            <label for="inputPassword">password :</label>
                            <!-- old user's password from database  -->
                            <input type="hidden" id="inputOldPassword" name="inputOldPassword" value="<?php echo $row['password'] ?>">
                            <!-- new password for update -->
                            <input type="password" class="form-control" id="inputNewPassword" name="inputNewPassword" autocomplete="new-password" placeholder="Leave it blank to keep the same password">
                        </div>
                    </div>
                    <div class="form-row">
                        <!-- email input -->
                        <div class="form-group col-md-6">
                            <label for="inputEmail">email :</label>
                            <input type="email" class="form-control" id="inputEmail" name="inputEmail" autocomplete="off" value="<?php echo $row['email'] ?>" required="required">
                        </div>
                        <!-- fullname input -->
                        <div class="form-group col-md-6">
                            <label for="inputFullName">full name :</label>
                            <input type="text" class="form-control" id="inputFullName" name="inputFullName" autocomplete="off" value="<?php echo $row['fullName'] ?>" required="required">
                        </div>
                    </div>
                    <!-- save button -->
                    <button type="submit" class="btn btn-primary">save</button>
                </form>
            </div>

<?php
        }

        // return message if there is no such id  

        else {
            echo "<div class='container my-5'>";
            $message = "<div class='alert alert-danger'>There is no such user</div>";
            redirectHome($message);
            echo "</div>";
        }
    }

    // go to update page

    elseif ($action == 'update') {

        // check if the user came with post http method 

        echo "<div class='container my-5'>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo "<h1 class='text-center'>Update Member</h1>";

            // Get values from the form of the edit

            $id = $_POST['inputUserId'];
            $userName = $_POST['inputUserName'];
            $email = $_POST['inputEmail'];
            $fullName = $_POST['inputFullName'];

            // password update trick
            // Condition ? True : False

            $password = empty($_POST['inputNewPassword']) ? $_POST['inputOldPassword'] : sha1($_POST['inputNewPassword']);

            // request validation 

            $requestRulesErrors = array();
            if (empty($userName))
                $requestRulesErrors[] = "username can't be <strong>empty</strong>";
            else {
                if (strlen($userName) < 4)
                    $requestRulesErrors[] = "username can't be <strong>less</strong> than <strong>4</strong> characters";
                elseif (strlen($userName) > 20)
                    $requestRulesErrors[] = "username can't be <strong>more</strong> than <strong>20</strong> characters";
            }
            if (empty($email))
                $requestRulesErrors[] = "email can't be <strong>empty</strong> </div>";
            if (empty($fullName))
                $requestRulesErrors[] = "fullName can't be <strong>empty</strong> </div>";
            else {
                if (strlen($fullName) < 4)
                    $requestRulesErrors[] = "fullName can't be <strong>less</strong> than <strong>4</strong> characters";
                elseif (strlen($fullName) > 20)
                    $requestRulesErrors[] = "fullName can't be <strong>more</strong> than <strong>20</strong> characters";
            }

            foreach ($requestRulesErrors as $error)
                echo "<div class = 'alert alert-danger'>" . $error . "</div>";

            if (empty($requestRulesErrors)) {

                $stmt = $cnx->prepare("SELECT COUNT(*) FROM users WHERE username LIKE '$userName' AND userId != $id");
                $stmt->execute();
                $exists =  $stmt->fetchColumn();
                if($exists == 0)
                {
                    // update the user in database with entered infos 

                    $stmt = $cnx->prepare('UPDATE users SET username = ? , password = ? , email = ? , fullName = ? WHERE userId = ?');
                    $stmt->execute(array($userName, $password, $email, $fullName, $id));

                       // state message

                    if ($stmt->rowCount() > 0) {
                        $message = "<div class='alert alert-success mt-5'>profile has been updated</div>";
                        redirectHome($message, 'back');
                    } else {
                        $message = "<div class='alert alert-info mt-5'>nothing has been updated</div>";
                        redirectHome($message);
                    }
                }
                else
                {
                    $message = "<div class='alert alert-danger mt-5'>this username already used</div>";
                    redirectHome($message,'back');
                }
            }
        }

        // If the user does not come with post http method

        else {
            $message = "<div class='alert alert-danger'>unauthorized</div>";
            redirectHome($message);
        }
        echo "</div>";
    }

    // go to delete page

    elseif ($action == 'delete') {

        // check if there is a user id sent , and its type  

        $user = isset($_GET['userId']) && is_numeric($_GET['userId']) ? intval($_GET['userId']) : 0;

        echo "<div class='container my-5'>";
        echo "<h1 class='text-center mt-5'>Delete Member</h1>";

        // check if there is a user with the id sent with get

        $exist = checkItem('userId', 'users', $user);

        // if exit greater than 0 so there is a user with the id sent 

        if ($exist > 0) {

            // delete the user with the id sent

            $stmt = $cnx->prepare("DELETE FROM users WHERE userId = :userid");
            $stmt->bindParam(":userid", $user);
            $stmt->execute();
            $message = "<div class='alert alert-success mt-5'>the user has been deleted</div>";
            redirectHome($message, 'back');
        }

        // if exist equal to zero , so there is no such user with this id 

        else {
            $message = "<div class='alert alert-danger mt-5'>there is no such user</div>";
            redirectHome($message);
        }
        echo "</div>";
    }

    // go to activate member page

    elseif($action == 'activate')
    {
        // check if there is a user id sent , and its type  

        $user = isset($_GET['userId']) && is_numeric($_GET['userId']) ? intval($_GET['userId']) : 0;

        echo "<div class='container my-5'>";
        echo "<h1 class='text-center mt-5'>Activate Member</h1>";

        // check if there is a user with the id sent with get

        $exist = checkItem('userId', 'users', $user);

        // if exit greater than 0 so there is a user with the id sent 

        if ($exist > 0) {

            // activate the user with the id sent

            $stmt = $cnx->prepare("UPDATE users SET regStatus = 1 WHERE userId = ?");
            $stmt->execute(array($user));
            $message = "<div class='alert alert-success mt-5'>the user has been activated</div>";
            redirectHome($message, 'back');
        }

        // if exist equal to zero , so there is no such user with this id 

        else {
            $message = "<div class='alert alert-danger mt-5'>there is no such user</div>";
            redirectHome($message);
        }
        echo "</div>";
    }


    // include the footer in this page with its all javascript calls

    include $tpl . "_footer.php";
}

// redirect to index.php if the user not logged in  

else {
    header('Location: index.php');
    exit();
}
