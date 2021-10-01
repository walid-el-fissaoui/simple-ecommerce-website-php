<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="app-nav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php"><?php echo lang('HOME') ?></a></li>
                <li class="nav-item"><a class="nav-link" href="categories.php"><?php echo lang('CATEGORIES') ?></a></li>
                <li class="nav-item"><a class="nav-link" href="items.php"><?php echo lang('ITEMS') ?></a></li>
                <li class="nav-item"><a class="nav-link" href="members.php"><?php echo lang('MEMBERS') ?></a></li>
                <li class="nav-item"><a class="nav-link" href="comments.php"><?php echo lang('COMMENTS') ?></a></li>
                <li class="nav-item"><a class="nav-link" href="#"><?php echo lang('STATISTICS') ?></a></li>
                <li class="nav-item"><a class="nav-link" href="#"><?php echo lang('LOGS') ?></a></li>
            </ul>
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item dropdown ml-auto">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPortfolio" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo lang('WELCOME') . ' : ' . $_SESSION['adminLogedIn'] ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownPortfolio">
                            <a class="dropdown-item" href="../index.php" style="text-align:center"><?php echo lang('VIEW_STORE') ?></a>
                            <a class="dropdown-item" href="members.php?action=edit&userId=<?php echo $_SESSION['adminUserId']?>" style="text-align:center"><?php echo lang('EDIT_PROFILE') ?></a>
                            <a class="dropdown-item" href="#" style="text-align:center"><?php echo lang('SETTINGS') ?></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php" style="text-align:center"><?php echo lang('LOGOUT') ?></a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>