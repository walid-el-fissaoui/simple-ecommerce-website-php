<?php 
ob_start();
session_start();
include "init.php";
        if(isset($_SESSION['userLogedIn'])){
                header('Location:index.php');
        }
        else
        {
                if($_SERVER['REQUEST_METHOD'] == 'POST')
                {
                        if(isset($_POST['signIn']))
                        {
                                $username = $_POST['signInUsername'];
                                $password = $_POST['signInPassword'];
                                $hashedPassword = sha1($password);
                
                                $stmt = $cnx->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
                                $stmt->execute(array($username,$hashedPassword));
                                $rows = $stmt->fetch();
                                if($stmt->rowCount() > 0)
                                {
                                        $_SESSION['userLogedIn'] = $username;
                                        $_SESSION['userId'] = $rows['userId'];
                                        header('Location:index.php');
                                        exit();
                                }
                        }
                        else
                        {
                                $sentUserName        = $_POST['SignUpUsername'];
                                $sentPassword        = $_POST['SignUpPassword'];
                                $sentConfirmPassword = $_POST['SignUpConfirmPassword'];
                                $sentEmail           = $_POST['SignUpEmail'];

                                $requestRulesErrors = array();

                                if(isset($sentUserName)){
                                        $filteredUserName = filter_var($sentUserName,FILTER_SANITIZE_STRING);
                                        if(strlen($filteredUserName) < 4){
                                                $requestRulesErrors[] = 'username should be more than 4 characters!';
                                        }
                                }
                                if(isset($sentPassword) && isset($sentConfirmPassword)){
                                        if(empty($sentPassword))
                                        {       
                                                $requestRulesErrors[] = 'password should not be empty!';
                                        }
                                        if($sentPassword !== $sentConfirmPassword){
                                                $requestRulesErrors[] = 'password and confirmation not match!';
                                        }
                                }
                                if(isset($sentEmail)){
                                        $filteredEmail = filter_var($sentEmail, FILTER_SANITIZE_EMAIL);
                                        if(filter_var($filteredEmail,FILTER_VALIDATE_EMAIL) != true){
                                                $requestRulesErrors[] = 'sorry , email is not valide';
                                        }
                                }

                                if(empty($requestRulesErrors)){
                                        $exists = checkItem('username','users',$filteredUserName);
                                        if($exists > 0){
                                                $requestRulesErrors[] = "Sorry this username already exists";
                                        }
                                        else
                                        {
                                                $stmt = $cnx->prepare("INSERT INTO users(username,password,email,registred_at) VALUES (:username,:password,:email,now())");
                                                $stmt->execute(array(
                                                        'username' => $filteredUserName,
                                                        'password' => sha1($sentPassword),
                                                        'email'    => $filteredEmail,
                                                ));
                                                if($stmt->rowCount()>0){
                                                        $successMessage = "Congrats you has been registred successfully!";
                                                }
                                                else{
                                                        $requestRulesErrors = "Unfortunately your account has not been registered , try again";
                                                }
                                        }
                                }
                        }
                }
        }
?>

<div class='container authentication-page'>
        <h1 class='text-center'><span class="selected" data-class="signIn">SignIn</span> | <span data-class="signUp" >SignUp</span></h1>
        <form class="signIn" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="input-container">
                        <input type="text" 
                                class="form-control"
                                id="inputSignInUserName" 
                                name="signInUsername" 
                                placeholder="username" 
                                autocomplete="off"
                                required="required">
                </div>
                <div class="input-container">
                        <input type="password" 
                                class="form-control"
                                id="inputSignInPassword" 
                                name="signInPassword" 
                                placeholder="password" 
                                autocomplete="new-password"
                                required="required">
                </div>
                <input type="submit" value="signIn" name="signIn" class="btn btn-primary btn-block">
        </form>
        <form class="signUp" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="input-container">
                        <input  type="text" 
                                pattern='.{4,}'
                                title='username should be more than 4 chars'
                                class="form-control"
                                id="inputSignUpUserName" 
                                name="SignUpUsername" 
                                placeholder="username" 
                                autocomplete="off"
                                required>
                </div>
                <div class="input-container">
                        <input  type="password"
                                minlength="4"
                                class="form-control"
                                id="inputSignUpPassword" 
                                name="SignUpPassword" 
                                placeholder="password" 
                                autocomplete="new-password"
                                required>
                </div>
                <div class="input-container">
                        <input type="password" 
                                class="form-control"
                                id="inputSignUpConfirmPassword" 
                                name="SignUpConfirmPassword" 
                                placeholder="confirm password" 
                                autocomplete="new-password"
                                required>
                </div>
                <div class="input-container">
                        <input type="email" 
                                class="form-control"
                                id="inputSignUpEmail" 
                                name="SignUpEmail" 
                                placeholder="email" 
                                autocomplete="off"
                                required>
                </div>
        <input type="submit" value="signUp" name="signUp" class="btn btn-success btn-block">
        </form>
        <div class="messages text-center">
                <?php 
                        if(!empty($requestRulesErrors))
                        {
                                foreach ($requestRulesErrors as $error) {
                                        echo "<div class='message error'>$error</div>";
                                }
                        } 
                        if(isset($successMessage))
                        {
                                echo "<div class='message success'>$successMessage</div>";
                        } 
                ?>
        </div>
</div>

<?php 
include $tpl . "_footer.php";
ob_end_flush();
?>