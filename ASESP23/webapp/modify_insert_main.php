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
	$time_start=microtime(true);
	echo '<h3>Modify or Insert:</h3>';

	echo '<form method="post" action="modify_main.php">';
	echo '<button type="submit" name="submit" value="submit_modify">Modify</button>';
	echo '</form>';
	
	echo '<form method="post" action="insert_main.php">';
	echo '<button type="submit" name="submit" value="submit_insert">Insert</button>';
	echo '</form>';
	

	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";

	//inner join type
	//on (type.auto_id = `type`)
?>