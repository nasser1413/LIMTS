<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Define all of the metadata -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Define the Page Title -->
    <title>Valpo LIMTS</title>
    <!-- Include Pluralize -->
    <script type="text/javascript" src='assets/js/pluralize.js'></script>
    <!-- Include MomentJS -->
    <script type="text/javascript" src='//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js'></script>
    <!-- Include all of our jQuery (& Plugins) JS & CSS Files -->
    <script type="text/javascript" src="assets/js/jquery-2.1.3.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/fullcalendar.min.js"></script>
    <link type="text/css" rel="stylesheet" href="assets/css/jquery-ui.min.css">
    <link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/fullcalendar.min.css"></script>
    <link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/fullcalendar.print.css" media="print"></script>
    <!-- Include all of our Bootstrap (& Plugins) JS & CSS Files -->
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/jasny-bootstrap.min.js"></script>
    <link type="text/css" href="assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link type="text/css" href="assets/css/bootstrap-theme.min.css" rel="stylesheet"/>
    <link type="text/css" href="assets/css/jasny-bootstrap.min.css" rel="stylesheet"/>
    <!-- Include all of our In-House files -->
    <link type="text/css" href="assets/css/common.css" rel="stylesheet"/>
</head>

<header>
<?php
include "header.php";
?>
</header>

<body>
    <div class="container" id="main-container">
<?php
$page = $_GET["page"];
$add = $_GET["add"];
// check to make sure pages actually exist first (this is laziness)
if ($page) {
    include $page . ".php";
} else if ($add) {
    include "add_" . $add . ".php";
} else {
    include "home.php";
}
?>
    </div>
</body>

</html>
