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
$fp=fopen(/var/www/html/)//add file with data here
$count=0;
$time_start=microtime(true);
echo "<p>Start time is: $time_start</p>\n";
while (($row-fgetcsv($fp)) !== FALSE)
{
	$sql="Insert into `equipment2` (`type`, `manufacturer`, `serial_num`) values ('$row[0]', '$row[1]', '$row[2]')";
	$dblink->query($sql) or
		die("Something went wrong with $sql<br>\n".$dblink->error);
	$count++;
}
$time_end=microtime(true);
echo "<p>End time is: $time_end</p>\n";
$seconds=$time_end-$time_start;
$execution_time=($seconds)/60;
echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
$rowsPerSecond=$count/$seconds;
echo "<p>Insert rate: $rowsPerSecond per second</p>\n";
fclose($fp);
?>