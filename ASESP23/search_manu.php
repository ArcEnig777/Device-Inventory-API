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
	$query= explode (",", $_POST['manufacturer']);
	
	if($query[0] == "All")
	{
		$sql="SELECT `t_name`
		  	  FROM `type`";
	}
	else
	{
		$sql="SELECT `t_name`
		  FROM `type`
		  WHERE `t_auto_id` = ANY
  		  (SELECT `type`
		  from `equipment_indexed`
		  where `manufacturer` = '$query[0]')";
	}

	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	
	/*while($data=$result->fetch_array(MYSQLI_NUM))
	{
		$tmatch[] = $data[0];
	}
	$ids = array_filter(array_unique(array_map('intval', $tmatch)));
	$sql = "SELECT `t_name` FROM `type` WHERE `t_auto_id` IN(".implode(',',$ids).")";
	$result=$dblink->query($sql) or
	die("Something went wrong with: $sql<br>".$dblink->error);*/
	$newQuery = implode(',', $query);
	echo '<p>Searching by Manufacturter: '.$query[1].'</p>';
	echo '<br>';
	echo '<form method="post" action="">';
	echo '<select name="manufacturer_type">';
	echo "<option value=All>All</option>";
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	echo '</select>';
	echo "<input type=hidden name=manufacturer value='$newQuery'>";
	echo '<button type="submit" name="submit" value="submit_m_t">Submit</button>';
	echo '</form>';
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else if(isset($_POST['submit']) && ($_POST['submit']=="submit_t"))
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
	echo '<form method="post" action="">';
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
else if(isset($_POST['submit']) && ($_POST['submit']=="submit_s"))
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
else if(isset($_POST['submit']) && ($_POST['submit']=="submit_t_m"))
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$query2=$_POST['type_manufacturer'];
	$query=explode (",", $_POST['type']);
	
	if($query2 == "All" && $query[0] == "All")
	{
		$sql="Select `s_auto_id`, manufacturer.m_name AS manufacturer,`serial_num` 
		from `equipment_indexed`
		inner join type
		on (type.t_auto_id = `type`)
		inner join manufacturer
		on (manufacturer.m_auto_id = `manufacturer`) limit 1000";
	}
	else if ($query2 == "All")
	{
		$sql="Select `s_auto_id`, manufacturer.m_name AS manufacturer,`serial_num` 
		from `equipment_indexed`
		inner join type
		on (type.t_auto_id = `type`)
		inner join manufacturer
		on (manufacturer.m_auto_id = `manufacturer`)
		where type.t_auto_id ='$query[0]' limit 1000";
	}
	else if ($query[0] == "All")
	{
		$sql="Select `s_auto_id`, manufacturer.m_name AS manufacturer,`serial_num` 
		from `equipment_indexed`
		inner join type
		on (type.t_auto_id = `type`)
		inner join manufacturer
		on (manufacturer.m_auto_id = `manufacturer`)
		where manufacturer.m_name='$query2' limit 1000";
	}
	else
	{
		$sql="Select `s_auto_id`, manufacturer.m_name AS manufacturer,`serial_num` 
		from `equipment_indexed`
		inner join type
		on (type.t_auto_id = `type`)
		inner join manufacturer
		on (manufacturer.m_auto_id = `manufacturer`)
		where type.t_auto_id ='$query[0]' and manufacturer.m_name='$query2'";
	}

	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<table>';
	echo '<h3>Search by type: '.$query[1].'</h3>';
	echo '<tr><td>Auto_ID</td><td>Manufacturer</td><td>Serial_Number</td></tr>';
	while($data=$result->fetch_array(MYSQLI_ASSOC))
	{
		echo '<tr>';
		echo "<td>$data[s_auto_id]</td>";
		echo "<td>$data[manufacturer]</td>";
		echo "<td>$data[serial_num]</td>";
		echo '</tr>';
	}
	echo '</table>';
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else if(isset($_POST['submit']) && ($_POST['submit']=="submit_m_t"))
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$query2=$_POST['manufacturer_type'];
	$query=explode (",", $_POST['manufacturer']);
	
	if($query2 == "All" && $query[0] == "All")
	{
			$sql="Select `s_auto_id`, type.t_name AS type,`serial_num`, manufacturer.m_name AS manufacturer 
			from `equipment_indexed`
			inner join type
			on (type.t_auto_id = `type`)
			inner join manufacturer
			on (manufacturer.m_auto_id = `manufacturer`) limit 1000";
	}
	else if ($query2 == "All")
	{
			$sql="Select `s_auto_id`, type.t_name AS type,`serial_num`, manufacturer.m_name AS manufacturer 
			from `equipment_indexed`
			inner join type
			on (type.t_auto_id = `type`)
			inner join manufacturer
			on (manufacturer.m_auto_id = `manufacturer`)
			where manufacturer.m_auto_id='$query[0]' limit 1000";
	}
	else if ($query[0] == "All")
	{
			$sql="Select `s_auto_id`, type.t_name AS type,`serial_num`, manufacturer.m_name AS manufacturer 
			from `equipment_indexed`
			inner join type
			on (type.t_auto_id = `type`)
			inner join manufacturer
			on (manufacturer.m_auto_id = `manufacturer`)
			where type.t_name='$query2' limit 1000";
	}
	else
	{
			$sql="Select `s_auto_id`, type.t_name AS type,`serial_num`, manufacturer.m_name AS manufacturer 
			from `equipment_indexed`
			inner join type
			on (type.t_auto_id = `type`)
			inner join manufacturer
			on (manufacturer.m_auto_id = `manufacturer`)
			where manufacturer.m_auto_id='$query[0]' and type.t_name='$query2'";
	}
	

	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<table>';
	echo '<h3>Search by manufacturer: '.$query[1].'</h3>';
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
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else if(isset($_POST['submit']) && ($_POST['submit']=="submit_a"))
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$sql="Select `s_auto_id`,`type`.t_name AS type,`manufacturer`.m_name AS manufacturer, `serial_num` 
	from `equipment_indexed`
	join type
	on (type.t_auto_id = `type`)
	join manufacturer
	on (manufacturer.m_auto_id = `manufacturer`) limit 1000";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<table>';
	echo '<h3>Search by All:</h3>';
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
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$sql="Select * from `manufacturer`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<form method="post" action="">';
	echo '<select name="manufacturer">';
	echo "<option value=All>All</option>";
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo "<option value='" . implode(',', $data) . "'>$data[1]</option>";
	}
	echo '</select>';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';
	
	$sql="Select * from `type`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<form method="post" action="">';
	echo '<select name="type">';
	echo "<option value=All>All</option>";
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo "<option value='" . implode(',', $data) . "'>$data[1]</option>";
	}
	echo '</select>';
	echo '<button type="submit" name="submit" value="submit_t">Submit</button>';
	echo '</form>';
	
	
	echo '<form method="post" action="">';
	echo '<label for="fname">Serial Number:</label>';
  	echo '<input type="text" id="fname" name="serial_num"><br>';
	echo '<button type="submit" name="submit" value="submit_s">Submit</button>';
	echo '</form>';

	echo '<form method="post" action="">';
	echo '<button type="submit" name="submit" value="submit_a">All</button>';
	echo '</form>';
	
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
	//inner join type
	//on (type.auto_id = `type`)
?>