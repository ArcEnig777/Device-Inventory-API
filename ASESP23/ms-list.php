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
$sql="Select * from `manufacturer` where `name`='Microsoft'";
$time_start=microtime(true);
$result=$dblink->query($sql) or 
	die("Something went wrong with: $sql<br>".$dblink->error);
$tmp=$result->fetch_array(MYSQLI_ASSOC);
$sql="Select `auto_id` from `equipment2` where `manufacturer`=$tmp[auto_id]";
//$time_start=microtime(true);
$result=$dblink->query($sql) or 
	die("Something went wrong with: $sql<br>".$dblink->error);
$count=$result->num_rows;
$time_end=microtime(true);
$seconds=$time_end-$time_start;
$execution_time=($seconds)/60;
echo "<p>Number of Rows for manufacturer type: $tmp[name] : $count</p>";
echo "PHP ID:$argv[1]-Execution time: $execution_time minutes or $seconds seconds.</p>\n";
?>