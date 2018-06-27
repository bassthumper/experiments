<?
$myvalue	=	"9";

$mysqlhost	= 	"aa3ebtp3v7bs1k.cklebl4y5vs8.us-west-2.rds.amazonaws.com";
$mysqlid	=	"admin";
$mysqlpw	=	"adminadmin";
$mysqldb	=	"ebdb";
$mysqlport	=	"3306";

$mySvrregionaz	= 	`curl -s http://169.254.169.254/latest/meta-data/placement/availability-zone`;

// Connect to master DB read/write
$mysqli = new mysqli($mysqlhost,$mysqlid,$mysqlpw,$mysqldb,$mysqlport);
if ($mysqli->connect_errno) {
    $message = "MySQL 1 message was>Failed to connect to MySQL (" . $mysqli->connect_errno . ")(" . $mysqli->connect_error . ")( " . $mysqli->host_info . ")";
}


// See if we are doing an update or just a page display.
// If update...
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{

  // Take value provided via POST and make it look nice to MySQL...
  $myvalue = $mysqli->real_escape_string($_POST['myvalue']);

  // Take the region/az that this code is running on and make it look nice for MySQL...
  $myregionaz = $mysqli->real_escape_string($mySvrregionaz);

  // Update the master DB with the value from Post, and the region/az where this code is running...
  $query1 = sprintf("update myrecords set regionaz='%s', value='%s' where id=1", $mySvrregionaz , $myvalue);
  if (!$mysqli->query($query1)) {
    echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
}

// Ok so now we do our page display...
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?=$mySvrregionaz?></title>
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lobster+Two" type="text/css">
	<link rel="icon" href="https://awsmedia.s3.amazonaws.com/favicon.ico" type="image/ico" >
	<link rel="shortcut icon" href="https://awsmedia.s3.amazonaws.com/favicon.ico" type="image/ico" >
	<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<link rel="stylesheet" href="/styles.css" type="text/css">
</head>
<body>
	<section class="readout">
		<center>
		<p>Time on Server:<?= date("l, F j, Y h:m:s a", strtotime("+0 hours")); ?></p>
		<p>This Page Served from:<?= $mySvrregionaz ?></p>

<?

$query2 = sprintf("SELECT ts, regionaz, value FROM myrecords where id=1");
$res = $mysqli->query($query2);

for ($row_no = $res->num_rows - 1; $row_no >= 0; $row_no--) {
    $res->data_seek($row_no);
    $row = $res->fetch_assoc();
    $myts = $row['ts'];
    $myregionaz = $row['regionaz']; 
    $myvalue = $row['value']; 
}
?>
		<p>	Record Last Updated at time: <?= $myts; ?></p>
		<p>	Record Last Updated by zone: <?= $myregionaz; ?></p>
<!--
		<p>	MySQL query 1 was><?= $query1; ?><</p>
		<p>	MySQL query 2 was><?= $query2; ?><</p>
		<p>	MySQL query returned RegionAZ of><?= $myregionaz; ?><</p>
		<p>	MySQL query returned Value of><?= $myvalue; ?><</p>
		<p>	MySQL message was><?= $message; ?><</p>

-->
		<p>
			<form action="index.php" method="post">
			Value: <input type="text" name="myvalue" value="<?= $myvalue; ?>"><br>
			<input type="submit">
			</form>
		</p>
		</center>
	</section>

</body>
</html>
