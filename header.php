    <nav id="myNavmenu" class="navmenu navmenu-default navmenu-fixed-left offcanvas" role="navigation">
    <a class="navmenu-brand" href="http://www.valpo.edu">
        <img src="assets/Signature_Horiz_Full_web.png" id="brand-img"/>
    </a>
    <ul class="nav navmenu-nav" id="main-nav"></ul>
    </nav>
    <div class="navbar navbar-default navbar-fixed-top">
        <?php if (is_user_logged_in()): ?>
            <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target="#myNavmenu" data-canvas="body">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <p class="navbar-text" style="float: right">
                Signed in as <?php echo $_SESSION[USER_FIRSTNAME] . " " . $_SESSION[USER_LASTNAME] ?>.
                <a href="logout_redirect.php">Log Out <span class="glyphicon glyphicon-log-out"></span></a>
            </p>
        <?php else: ?>
            <a class="navbar-brand" href="#">LIMTS</a>
        <?php endif ?>
    </div>

    <script type="text/javascript" src="assets/js/header.js"></script>

    <script type="text/javascript">
        $(setupHeader);
    </script>
