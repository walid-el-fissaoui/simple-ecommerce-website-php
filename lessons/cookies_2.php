<?php

$color = "#fff";

// setcookie('Background', "", time() - 3600, '/'); delete cookie



if (isset($_COOKIE['Background'])) {
    $body = $_COOKIE['Background'];
} else {
    $body = $color;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $color = $_POST['color'];
    setcookie('Background', $color, time() + 3600, '/');
    $body = $color;
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>Background</title>
</head>

<body style="background-color: <?php echo $body ?>">
    <form action="<?php echo  $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="color" name="color">
        <input type="submit" value="Choose">

    </form>

</body>

</html>