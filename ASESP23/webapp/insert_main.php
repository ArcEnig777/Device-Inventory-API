<?php
function db_iconnect($dbName)
{
	$un="arcwebuser";//username for db
	$pw="OL(6faRkaZCbZlya";//password for db
	$db=$dbName;
	$hostname="localhost";
	$dblink=new mysqli($hostname,$un,$pw,$db);
	return $dblink;
}
if(isset($_POST['submit']) && ($_POST['submit']=="return_main"))
{
	  header("Location: https://ec2-3-134-86-72.us-east-2.compute.amazonaws.com/webapp/modify_insert_main.php");
	  exit();
}
else
{
	$time_start=microtime(true);
	
	echo '<h3>Insert:</h3>';

	echo '<form method="post" action="insert_manu.php">';
	echo '<button type="submit" name="submit" value="insert_manu">Manufacturer</button>';
	echo '</form>';

	echo '<form method="post" action="insert_type.php">';
	echo '<button type="submit" name="submit" value="insert_type">Type</button>';
	echo '</form>';

	echo '<form method="post" action="insert_device.php">';
	echo '<button type="submit" name="submit" value="insert_device">Device</button>';
	echo '</form>';
	
	echo '<form method="post" action="">';
	echo '<button type="submit" name="submit" value="return_main">Back to Main Page</button>';
	echo '</form>';
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}


	//inner join type
	//on (type.auto_id = `type`)
?>