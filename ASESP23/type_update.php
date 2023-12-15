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
echo "<p>Start time is: $time_start</p>\n";
$sql="Set autocommit=0";
$dblink->query($sql) or           
		die("Something went wrong with $sql<br>\n".$dblink->error);
$sql="Select * from `type` where `name`= 'laptop'";
$result=$dblink->query($sql) or
	die("Something went wrong with: $sql<br>".$dblink->error);
$count = 0;
while($item=$result->fetch_array(MYSQLI_ASSOC))
{
	$sql="Select `auto_id` from `equipment2` where `type`='$item[name]'";
	$rst=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	while($data=$rst->fetch_array(MYSQLI_ASSOC))
	{
		//echo "<p>About to update $data[auto_id] with new type:$item[name] from $data[type]</p>";
		$sql="Update `equipment2` set `type`='$item[auto_id]' where `auto_id`='$data[auto_id]'";
		$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		$count++;
	}
		
	
}
$sql="Commit";
$dblink->query($sql) or
		die("Something went wrong with $sql<br>\n".$dblink->error);
$time_end=microtime(true);
echo "<p>End time is: $time_end</p>\n";
$seconds=$time_end-$time_start;
$execution_time=($seconds)/60;
echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
$rowsPerSecond=$count/$seconds;
echo "<p>Insert rate: $rowsPerSecond per second</p>\n";
echo "<p>Done</p>"
?>