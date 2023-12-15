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
echo "Hello from php proccess $argv[1] about to proccess file:$argv[2]\n";
$fp=fopen("/home/ubuntu/equipment_data/$argv[2]", "r");//add file with data here
$count=0;
$time_start=microtime(true);
echo "<p>Start time is: $time_start</p>\n";
$sql="Set autocommit=0";
$dblink->query($sql) or
		die("Something went wrong with $sql<br>\n".$dblink->error);
while (($row=fgetcsv($fp)) !== FALSE)
{
	$row[0] = mysqli_real_escape_string($dblink, $row[0]);
	$row[1] = mysqli_real_escape_string($dblink, $row[1]);
	$row[2] = mysqli_real_escape_string($dblink, $row[2]);
	$sql="Insert into `equipment1` (`type`, `manufacturer`, `serial_num`) values ('$row[0]', '$row[1]', '$row[2]')";
	$dblink->query($sql) or
		die("Something went wrong with $sql<br>\n".$dblink->error);
	$count++;
}
$sql="Commit";
$dblink->query($sql) or
		die("Something went wrong with $sql<br>\n".$dblink->error);
$time_end=microtime(true);
echo "PHP ID:$argv[1]-End time is: $time_end</p>\n";
$seconds=$time_end-$time_start;
$execution_time=($seconds)/60;
echo "PHP ID:$argv[1]-Execution time: $execution_time minutes or $seconds seconds.</p>\n";
$rowsPerSecond=$count/$seconds;
echo "PHP ID:$argv[1]-Insert rate: $rowsPerSecond per second</p>\n";
fclose($fp);
?>