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
if(isset($_POST['submit']) && ($_POST['submit']=="submit"))
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$query=$_POST['manufacturer'];
	$sql="Select type.t_name AS type, `serial_num` 
	from `equipment_indexed`
	inner join type
	on (type.auto_id = `type`)
	inner join manufacturer
	on (manufacturer.auto_id = `manufacturer`)
	where manufacturer.m_name ='$query'";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<table>';
	echo '<h3>Search by manufacturer: '.$query.'</h3>';
	echo '<tr><td>Type</td><td>Serial Number</td></tr>';
	while($data=$result->fetch_array(MYSQLI_ASSOC))
	{
		echo '<tr>';
		echo "<td>$data[type]</td>";
		echo "<td>$data[serial_num]</td>";
		echo '</tr>';
	}
	echo '</table>';
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else
{
	echo '<h3>No post data received</h3>';
}

?>