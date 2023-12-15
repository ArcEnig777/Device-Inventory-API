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
if(isset($_POST['submit']) && ($_POST['submit']=="modify_type"))
{
	$time_start=microtime(true);
	$query= explode (",", $_POST['type']);
	
	if($query[0] == "New")
	{
	  header("Location: https://ec2-3-134-86-72.us-east-2.compute.amazonaws.com/webapp/insert_type.php?submit=insert_type");
	  exit();
	}
	$newQuery = implode(',', $query);
	echo '<form method="post" action="">';
	echo '<p>Modifying by Type '.$query[1].' and manufacturers:</p>';
	echo '<select name="modify_choice">';
	echo "<option value=All>All</option>";
	echo "<option value=All>Active</option>";
	echo '</select>';
	echo "<input type=hidden name=type value='$newQuery'>";
	echo '<button type="submit" name="submit" value="modify_type_c">Submit</button>';
	echo '</form>';
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
	
}
else if(isset($_POST['submit']) && ($_POST['submit']=="modify_type_c"))
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$query= $_POST['modify_choice'];
	$query2= explode (",", $_POST['type']);
	
	if($query == "Active")
	{
		$sql="SELECT `m_name`
			  FROM `manufacturer`
			  WHERE `m_auto_id` = ANY
			  (SELECT `manufacturer`
			  from `equipment_indexed`
			  where `type` = '$query2[0]') AND `m_status` = 'Active'";
		$choice = "Active";
	}
	else
	{
		$sql="SELECT `m_name`
			  FROM `manufacturer`
			  WHERE `m_auto_id` = ANY
			  (SELECT `manufacturer`
			  from `equipment_indexed`
			  where `type` = '$query2[0]')";
		$choice = "All";
	}


	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	
	$newQuery = implode(',', $query2);
	echo '<p>Modifying by Type: '.$query2[1].' and manufacturers '.$query.'</p>';
	echo '<br>';
	echo '<form method="post" action="">';
	echo '<select name="modify_type_manu">';
	echo "<option value=All>All</option>";
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	echo '</select>';
	echo "<input type=hidden name=type value='$newQuery'>";
	echo "<input type=hidden name=choice value='$choice'>";
	echo '<button type="submit" name="submit" value="modify_type_input">Submit</button>';
	echo '</form>';
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else if(isset($_POST['submit']) && ($_POST['submit']=="modify_type_input"))
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$query= $_POST['modify_type_manu'];
	$query2= explode (",", $_POST['type']);
	$modChoice = $_POST['choice'];
	
	$sql = "SELECT `t_status`
			FROM `type`
			WHERE `t_auto_id` = '$query2[0]'";
	
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$data = $result->fetch_array(MYSQLI_ASSOC);
	
	if($data['t_status'] == "Inactive" or $query == "All")
	{
		$status = $data['t_status'];
	}
	else
	{
		$sql="SELECT COUNT(`s_auto_id`)
			  FROM `equipment_indexed`
			  inner join manufacturer on (manufacturer.m_auto_id = `manufacturer`)
			  WHERE `manufacturer`.`m_name` = '$query' AND `type` = '$query2[0]' AND `s_auto_id` NOT IN
			  (SELECT `auto_id`
			  from `equipment_disabled`)";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		$data = $result->fetch_array(MYSQLI_NUM);
		
		if($data[0] == 0 )
		{
			$status = "Inactive";
		}
		else
		{
			$status = "Active";
		}
	}
	
	
	$newQuery = implode(',', $query2);
	echo '<form method="post" action="">';
	echo '<p>Current Type:'.$query2[1].'</p><br>';
	echo '<p>Sub Manufacturer:'.$query.'</p><br>';
	echo '<input type="text" id="fname" name="type_name" value='.$query2[1].'>';
	echo '<p>Current Status:'.$status.'</p><br>';
	echo '<select name="modify_type_status">';
	echo "<option value=Active>Active</option>";
	echo "<option value=Inactive>Inactive</option>";
	echo '</select>';
	echo "<input type=hidden name=type value='$newQuery'>";
	echo "<input type=hidden name=modify_type_manu value='$query'>";
	echo '<button type="submit" name="submit" value="modify_type_update">Submit</button>';
	echo '</form>';
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";

}
else if (isset($_POST['submit']) && ($_POST['submit']=="modify_type_update"))
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$query= $_POST['modify_type_manu'];
	$query2= explode (",", $_POST['type']);
	$status	= $_POST['modify_type_status'];
	$name = $_POST['type_name'];
	
	$sql = "SELECT COUNT(`t_name`)
			FROM `type`
			WHERE `t_name` = '$name'";
	$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
	$data = $result->fetch_array(MYSQLI_NUM);
	
	if($data[0] != 0 && $name != $query2[1])
	{
		echo '<p>Duplicate name detected. Please try again without a duplicate name</p>';
	}
	else
	{
		$sql = "UPDATE `type`
			SET `t_name` = '$name'
			WHERE `t_auto_id` = '$query2[0]'";
		$result=$dblink->query($sql) or
				die("Something went wrong with: $sql<br>".$dblink->error);
		echo $status;
	
		if($query == "All" && $status == "Inactive")
		{
			$sql = "UPDATE `type`
					SET `t_status` = '$status'
					WHERE `t_auto_id` = '$query2[0]'";
			$result=$dblink->query($sql) or
				die("Something went wrong with: $sql<br>".$dblink->error);
			
			$sql = "INSERT INTO `equipment_disabled` (`auto_id`, `d_serial_num`)
					SELECT `s_auto_id`, `serial_num` from `equipment_indexed`
					WHERE `type` = '$query2[0]' AND `s_auto_id` NOT IN
					(SELECT `auto_id`
					 from `equipment_disabled`
					)";
		}
		else if($query == "All" && $status == "Active")
		{
			$sql = "UPDATE `type`
					SET `t_status` = '$status'
					WHERE `t_auto_id` = '$query2[0]'";
			$result=$dblink->query($sql) or
				die("Something went wrong with: $sql<br>".$dblink->error);
			
			$sql = "DELETE FROM `equipment_disabled`
					WHERE `auto_id` IN
					(SELECT `s_auto_id` from `equipment_indexed`
					 WHERE `type` = '$query2[0]'
					)";
		}
		else if($status == "Inactive")
		{
			$sql = "INSERT INTO `equipment_disabled` (`auto_id`, `d_serial_num`)
					SELECT `s_auto_id`, `serial_num` from `equipment_indexed`
					inner join manufacturer on (manufacturer.m_auto_id = `manufacturer`)
					WHERE `type` = '$query2[0]' AND `manufacturer`.`m_name` = '$query' AND `s_auto_id` NOT IN
					(SELECT `auto_id`
					 from `equipment_disabled`
					)";
		}
		else if( $status == "Active")
		{
			$sql = "DELETE FROM `equipment_disabled`
					WHERE `auto_id` IN
					(SELECT `s_auto_id` from `equipment_indexed`
					 inner join manufacturer on (manufacturer.m_auto_id = `manufacturer`)
					 WHERE `type` = '$query2[0]' AND `manufacturer`.`m_name` = '$query'
					)";
		}
		
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		$rows = $dblink->affected_rows;
		echo "<p>Affected rows: $rows</p>";
		echo '<p>Modification succesful</p>';
		echo '<form method="post" action="">';
		echo '<button type="submit" name="submit" value="return_main">Back to Main Page</button>';
		echo '</form>';
		$time_end=microtime(true);
		$seconds=$time_end-$time_start;
		$execution_time=($seconds)/60;
		echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
	}


}
else
{
	  header("Location: https://ec2-3-134-86-72.us-east-2.compute.amazonaws.com/webapp/modify_insert_main.php");
	  exit();
}

	//inner join type
	//on (type.auto_id = `type`)
?>