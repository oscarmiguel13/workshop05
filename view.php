<?php
session_start();
if ( ! isset($_SESSION['login']) ) {
    die('Not logged in');
}
if ( isset($_SESSION['success']) ) {
    echo('<p style="color: green; padding-left: 100px; padding-top: 30px;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Carlo's Autos Database</title>
<?php require_once "bootstrap.php"; ?>
</head>
<style type="text/css">
    .green{
        color: green;
    }
    .red{
        color: red;
    }
</style>
<body>
<div class="container">
<h1>Tracking Autos for <?php echo $_GET['name'];?> </h1>
<?php
if ( isset($_REQUEST['name']) ) {
    echo "<p>Welcome: ";
    echo htmlentities($_REQUEST['name']);
    echo "</p>\n";
}
?>
<h2>Automobiles</h2>
<p><a href="add.php">Add New</a> | <a href="index.php">Logout</a></p>
<br>
<div><p class="green"><?php echo $msg; ?></p><p class="red"><?php echo $errmsg; ?></p></div>
</div>
</body>
</html>
