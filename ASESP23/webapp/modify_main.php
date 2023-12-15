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
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	echo '<h3>Modify:</h3>';
	$sql="Select * from `manufacturer`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<form method="post" action="modify_manufacturer.php">';
	echo '<select name="manufacturer">';
	echo "<option value=New>New</option>";
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo "<option value='" . implode(',', $data) . "'>$data[1]</option>";
	}
	echo '</select>';
	echo '<button type="submit" name="submit" value="modify_manu">Modify</button>';
	echo '</form>';
	
	$sql="Select * from `type`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<form method="post" action="modify_type.php">';
	echo '<select name="type">';
	echo "<option value=New>New</option>";
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo "<option value='" . implode(',', $data) . "'>$data[1]</option>";
	}
	echo '</select>';
	echo '<button type="submit" name="submit" value="modify_type">Modify</button>';
	echo '</form>';
	
	
	echo '<form method="post" action="modify_device.php">';
	echo '<label for="fname">Serial Number:</label>';
  	echo '<input type="text" id="fname" name="serial_num"><br>';
	echo '<button type="submit" name="submit" value="modify_device">Modify</button>';
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