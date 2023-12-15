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
if(isset($_POST['submit']) && ($_POST['submit']=="submit_m_t"))
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$query2=$_POST['manufacturer_type'];
	$query=explode (",", $_POST['manufacturer']);
	
	$results_per_page = 1000;
	
	if($query2 == "All" && $query[0] == "All")
	{
			$sql="Select COUNT(`s_auto_id`) 
			from `equipment_indexed`
			inner join type
			on (type.t_auto_id = `type`)
			inner join manufacturer
			on (manufacturer.m_auto_id = `manufacturer`)";
	}
	else if ($query2 == "All")
	{
			$sql="Select COUNT(`s_auto_id`) 
			from `equipment_indexed`
			inner join type
			on (type.t_auto_id = `type`)
			inner join manufacturer
			on (manufacturer.m_auto_id = `manufacturer`)
			where manufacturer.m_auto_id='$query[0]'";
	}
	else if ($query[0] == "All")
	{
			$sql="Select COUNT(`s_auto_id`) 
			from `equipment_indexed`
			inner join type
			on (type.t_auto_id = `type`)
			inner join manufacturer
			on (manufacturer.m_auto_id = `manufacturer`)
			where type.t_name='$query2'";
	}
	else
	{
			$sql="Select COUNT(`s_auto_id`) 
			from `equipment_indexed`
			inner join type
			on (type.t_auto_id = `type`)
			inner join manufacturer
			on (manufacturer.m_auto_id = `manufacturer`)
			where manufacturer.m_auto_id='$query[0]' and type.t_name='$query2'";
	}
	
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
	
	if($query2 == "All" && $query[0] == "All")
	{
			$sql="Select `s_auto_id`, type.t_name AS type,`serial_num`, manufacturer.m_name AS manufacturer 
			from `equipment_indexed`
			inner join type
			on (type.t_auto_id = `type`)
			inner join manufacturer
			on (manufacturer.m_auto_id = `manufacturer`) LIMIT $page_first_result,$results_per_page";
	}
	else if ($query2 == "All")
	{
			$sql="Select `s_auto_id`, type.t_name AS type,`serial_num`, manufacturer.m_name AS manufacturer 
			from `equipment_indexed`
			inner join type
			on (type.t_auto_id = `type`)
			inner join manufacturer
			on (manufacturer.m_auto_id = `manufacturer`)
			where manufacturer.m_auto_id='$query[0]' LIMIT $page_first_result,$results_per_page";
	}
	else if ($query[0] == "All")
	{
			$sql="Select `s_auto_id`, type.t_name AS type,`serial_num`, manufacturer.m_name AS manufacturer 
			from `equipment_indexed`
			inner join type
			on (type.t_auto_id = `type`)
			inner join manufacturer
			on (manufacturer.m_auto_id = `manufacturer`)
			where type.t_name='$query2' LIMIT $page_first_result,$results_per_page";
	}
	else
	{
			$sql="Select `s_auto_id`, type.t_name AS type,`serial_num`, manufacturer.m_name AS manufacturer 
			from `equipment_indexed`
			inner join type
			on (type.t_auto_id = `type`)
			inner join manufacturer
			on (manufacturer.m_auto_id = `manufacturer`)
			where manufacturer.m_auto_id='$query[0]' and type.t_name='$query2' LIMIT $page_first_result,$results_per_page";
	}
	

	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<table>';
	echo '<h3>Search by manufacturer: '.$query[1].'</h3>';
	echo '<h2>Page '.$page.' out of '.$number_of_page.'</h2>';
	echo '<tr><td>Auto_ID</td><td>Type</td><td>Serial_Number</td></tr>';
	while($data=$result->fetch_array(MYSQLI_ASSOC))
	{
		echo '<tr>';
		echo "<td>$data[s_auto_id]</td>";
		echo "<td>$data[type]</td>";
		echo "<td>$data[serial_num]</td>";
		echo '</tr>';
	}
	echo '</table>';
	
	$newQuery = implode(',', $query);
	echo '<form method="post" action="">';
	echo "<input type=hidden name=manufacturer value='$newQuery'>";
	echo "<input type=hidden name=manufacturer_type value='$query2'>";
	echo '<input type=hidden name="submit" value="submit_m_t">';
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