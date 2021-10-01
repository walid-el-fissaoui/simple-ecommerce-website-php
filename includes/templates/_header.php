<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php getTitle(); ?></title>
    <link rel="stylesheet" href="<?php echo $css ?>bootstrap.min.css"/>
    <link rel="stylesheet" href="<?php echo $css ?>all.min.css"/>
    <link rel="stylesheet" href="<?php echo $css ?>jquery-ui.css"/>
    <link rel="stylesheet" href="<?php echo $css ?>jquery.selectBoxIt.css"/>
    <link rel="stylesheet" href="<?php echo $css ?>app.css"/>
</head>
    <body>
    <div class="navbar navbar-expand-lg">
        <div class="container">
            <ul class="nav <?php if(!isset($_SESSION['userLogedIn'])) echo 'ml-auto';?> ">
            <?php if(!isset($_SESSION['userLogedIn'])){ ?>
                <li class="nav-item"><a class="nav-link active" href="authentication.php">signIn/signUp</a></li>
            <?php }else{ ?>
            <img src='layout/images/placeholder.jpg' class='user-profile-image img-fluid img-thumbnail rounded-circle' alt='...'>
            <div class="btn-group dropright">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo $sessionUserName;?>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="profile.php">Profile</a>
                    <a class="dropdown-item" href="createItem.php">New Item</a>
                    <a class="dropdown-item" href="Profile.php#MyItems">Items</a>
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </div>
            <?php } ?>
            </ul>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="app-nav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php"><?php echo lang('HOME') ?></a></li>
                </ul>
                <ul class="nav navbar-nav ml-auto">
                    <?php 
                        
                        foreach (getAll('*','categories','WHERE parent = 0','','categoryId','ASC') as  $category) {
                            echo "<li class='nav-item'><a class='nav-link' href='categories.php?catId=".$category['categoryId']."'>".$category['name']."</a></li>";
                        }
                    ?>
                </ul>
            </div>
        </div>
    </nav>