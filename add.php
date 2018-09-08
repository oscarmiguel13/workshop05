<?php
session_start();
if ( ! isset($_SESSION['login']) ) {
    die('Not logged in');
}

$msg='';
$errmsg='';
// Demand a GET parameter
/*if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}*/

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    session_destroy();
    header('Location: index.php');
    return;
}

if(isset($_POST['submit'])){

if (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage']))
{
    $errmsg='Mileage and year must be numeric';
}
elseif ($_POST['make'] == '') {
    $errmsg='Make is required';
}
else{

include('dbconnect.php');

try {
$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$username,$password);

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // <== add this line
/*$sql = "INSERT INTO autos (make, year, mileage)
VALUES ('".$_POST["make"]."','".$_POST["year"]."','".$_POST["mileage"]."')";*/
$stmt = $dbh->prepare('INSERT INTO autos (make, year, mileage) VALUES ( :mk, :yr, :mi)');
    $stmt->execute(array(
        ':mk' => $_POST['make'],
        ':yr' => $_POST['year'],
        ':mi' => $_POST['mileage'])
    );

if (!$dbh->query($stmt)) {
    session_start();
    $_SESSION['success'] = "Record inserted";
    header("Location: view.php");
    return;
}
else{
    echo "<script type= 'text/javascript'>alert('Data not successfully Inserted.');</script>";
}

$dbh = null;
}
catch(PDOException $e)
{
echo $e->getMessage();
}

}
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
<br>
<div><p class="green"><?php echo $msg; ?></p><p class="red"><?php echo $errmsg; ?></p></div>
<form action="" method="post">
Make: <input type="text" name="make"><br>
Year: <input type="text" name="year"><br>
Mileage: <input type="text" name="mileage"><br>
<input type="submit" name="submit" value="Submit">
<input type="submit" name="logout" value="Logout">
</form>
<div>
    <?php
        include('dbconnect.php');
        try{
        $dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$username,$password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $dbh->query("SELECT * FROM autos");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo"<p><h3>Automobiles</h3></p>";
        foreach($rows as $row){
        //while ($row = $stmt->fetch()) {
            //echo $row['year'].' '.$row['make'].' / '.$row['mileage']"<br />\n";
            echo $row['year'].' '.$row['make'].' / '.$row['mileage']."<br />\n";
        //}
        }
    }catch(PDOException $e){

    }
    ?>
</div>
</div>
</body>
</html>
