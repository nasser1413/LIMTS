    <nav id="myNavmenu" class="navmenu navmenu-default navmenu-fixed-left offcanvas" role="navigation">
    <a class="navmenu-brand" href="http://www.valpo.edu">
        <img src="assets/Signature_Horiz_Full_web.png" id="brand-img"/>
    </a>
    <ul class="nav navmenu-nav">
        <li id="homeLink"><a href="?page=home">Calendar View</a></li>
        <li id="tableLink"><a href="?page=home&table=1">Table View</a></li>
        <li id="constraintsLink"><a href="?page=constraints">Constraints Check</a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Add <b class="caret"></b></a>
            <ul class="dropdown-menu navmenu-nav" role="menu">
                <li id="sectionLink"><a href="?add=section">Section</a></li>
                <li><a href="#">Class</a></li>
                <li><a href="#">Professor</a></li>
            </ul>
        </li>
        <li id="aboutLink"><a href="?page=about">About</a></li>
    </ul>
    </nav>
    <div class="navbar navbar-default navbar-fixed-top">
        <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target="#myNavmenu" data-canvas="body">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>

    <script type="text/javascript">
        $(function () {
            var page = "<?php echo ($_GET["table"] ? "table" : ($_GET["page"] ? $_GET["page"] : ($_GET["add"] ? $_GET["add"] : "home"))) ?>";
            var link = $("#" + page + "Link");
            link.addClass("active");
            link.parents(".dropdown").addClass("open");
        });
    </script>
