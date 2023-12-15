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
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$sql="Select * from `manufacturer`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<form method="post" action="search_manufacturer.php">';
	echo '<select name="manufacturer">';
	echo "<option value=All>All</option>";
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo "<option value='" . implode(',', $data) . "'>$data[1]</option>";
	}
	echo '</select>';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';
	
	$sql="Select * from `type`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<form method="post" action="search_type.php">';
	echo '<select name="type">';
	echo "<option value=All>All</option>";
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo "<option value='" . implode(',', $data) . "'>$data[1]</option>";
	}
	echo '</select>';
	echo '<button type="submit" name="submit" value="submit_t">Submit</button>';
	echo '</form>';
	
	
	echo '<form method="post" action="search_snum.php">';
	echo '<label for="fname">Serial Number:</label>';
  	echo '<input type="text" id="fname" name="serial_num"><br>';
	echo '<button type="submit" name="submit" value="submit_s">Submit</button>';
	echo '</form>';

	echo '<form method="post" action="search_all.php">';
	echo '<button type="submit" name="submit" value="submit_a">All</button>';
	echo '</form>';
	
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";

	//inner join type
	//on (type.auto_id = `type`)
?>