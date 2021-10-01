<?php 
    session_start();
    $withoutNavbar  = ''; /** add this variable to avoid the including of the navbar in this page */
    $pageTitle = 'Login';
    // print_r($_SESSION); /** write list of sessions  */

    /** if i was loged in redirect me to dashboard directly when i request index page */
    if(isset($_SESSION['adminLogedIn']))
    {
        header('Location: dashboard.php'); // redirecte to dashboard 
    }
    include "init.php"; 

    // check if user comming from http request post 

    if( $_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hashedPassword = sha1($password);

        // check is the user exist in database 
        // groupeId = 1 mean the user is an admin 
        // groupeId instead of boolean is_admin because maybe we make another roles 
        $stmt = $cnx->prepare("SELECT userId , username , password FROM users where username = ? and password = ? and roleId = 1 Limit 1");
        $stmt->execute(array($username, $hashedPassword));

        /** fetch , retreive the row from the sql request  */
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        // if count > 0 , this is mean that the database contain a record with this user and pass
        if ($count > 0)
        {
            $_SESSION['adminLogedIn'] = $username; // register session name
            $_SESSION['adminUserId'] = $row['userId']; // register new session with user id 
            header('Location: dashboard.php'); // redirecte to dashboard 
            exit();
        }
    }
?>
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <h4 class="text-center">ADMIN SINGIN</h4>
        <input type="text" class="form-control" name="username" placeholder="username" autocomplete="off">
        <input type="password" class="form-control" name="password" placeholder="password" autocomplete="new-password">
        <input type="submit" value="sign in" class="btn btn-primary btn-block">
    </form>

<?php include $tpl . '_footer.php';  ?>