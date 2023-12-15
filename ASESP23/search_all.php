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
if(isset($_POST['submit']) && ($_POST['submit']=="submit_a"))
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	
	$results_per_page = 1000;
	
	$sql="Select COUNT(*) 
	from `equipment_indexed`";
	
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$number_of_result = $result->fetch_array(MYSQLI_NUM);
	$number_of_page = ceil ($number_of_result[0] / $results_per_page);
	
	if (!isset ($_POST['page']) ) {  
        $page = 1;  
    } 
	else {  
        $page = $_POST['page'];
    }  
	
	$page_first_result = ($page-1) * $results_per_page; 
	
	$sql="Select `s_auto_id`,`type`.t_name AS type,`manufacturer`.m_name AS manufacturer, `serial_num` 
	from `equipment_indexed`
	join type
	on (type.t_auto_id = `type`)
	join manufacturer
	on (manufacturer.m_auto_id = `manufacturer`) LIMIT $page_first_result,$results_per_page";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	
	echo '<table>';
	echo '<h3>Search by All:</h3>';
	echo '<h2>Page '.$page.' out of '.$number_of_page.'</h2>';
	echo '<tr><td>Auto_id</td><td>Type</td><td>Manufacturer</td><td>Serial Number</td></tr>';
	while($data=$result->fetch_array(MYSQLI_ASSOC))
	{
		echo '<tr>';
		echo "<td>$data[s_auto_id]</td>";
		echo "<td>$data[type]</td>";
		echo "<td>$data[manufacturer]</td>";
		echo "<td>$data[serial_num]</td>";
		echo '</tr>';
	}
	echo '</table>';
	
	echo '<form method="post" action="">';
	echo '<input type=hidden name="submit" value="submit_a">';
	if($page > 1)
	{
	
		echo '<button type="submit" name="page" value='.($page - ($page - 1)).'><<</button>';
		
		echo '<button type="submit" name="page" value='.($page - 1).'>Previous</button>';
		
	}
	if($page<$number_of_page)
	{
		echo '<button type="submit" name="page" value='.($page + 1).'>Next</button>';

		echo '<button type="submit" name="page" value='.($page + ($number_of_page -$page)).'>>></button>';
	}
	echo '</form>';
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}

	//inner join type
	//on (type.auto_id = `type`)
?>