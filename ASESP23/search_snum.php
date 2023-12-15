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
if(isset($_POST['submit']) && ($_POST['submit']=="submit_s"))
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$query=$_POST['serial_num'];
	$sql="Select `s_auto_id`,
	(SELECT `t_name`
	FROM `type`
	WHERE `equipment_indexed`.`type` = `type`.`t_auto_id`) AS Type,
	(SELECT `m_name`
	FROM `manufacturer`
	WHERE `equipment_indexed`.`manufacturer` = `manufacturer`.`m_auto_id`) AS Manufacturer
	from `equipment_indexed`
	where `serial_num`='$query'";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<table>';
	echo '<h3>Search by Serial Number: '.$query.'</h3>';
	echo '<tr><td>Auto_ID</td><td>Type</td><td>Manufacturer</td></tr>';
	while($data=$result->fetch_array(MYSQLI_ASSOC))
	{
		echo '<tr>';
		echo "<td>$data[s_auto_id]</td>";
		echo "<td>$data[Type]</td>";
		echo "<td>$data[Manufacturer]</td>";
		echo '</tr>';
	}
	echo '</table>';
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}

	//inner join type
	//on (type.auto_id = `type`)
?>