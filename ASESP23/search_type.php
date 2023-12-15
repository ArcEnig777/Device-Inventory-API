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
if(isset($_POST['submit']) && ($_POST['submit']=="submit_t"))
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$query= explode (",", $_POST['type']);
	
		if($query[0] == "All")
	{
		$sql="SELECT `m_name`
		  	  FROM `manufacturer`";
	}
	else
	{
		$sql="SELECT `m_name`
		  FROM `manufacturer`
		  WHERE `m_auto_id` = ANY
  		  (SELECT `manufacturer`
		  from `equipment_indexed`
		  where `type` = '$query[0]')";
	}
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$newQuery = implode(',', $query);
	echo '<p>Searching by Type: '.$query[1].'</p>';
	echo '<br>';
	echo '<form method="post" action="search_type_results.php">';
	echo '<select name="type_manufacturer">';
	echo "<option value=All>All</option>";
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	echo '</select>';
	echo "<input type=hidden name=type value='$newQuery'>";
	echo '<button type="submit" name="submit" value="submit_t_m">Submit</button>';
	echo '</form>';
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}

	//inner join type
	//on (type.auto_id = `type`)
?>