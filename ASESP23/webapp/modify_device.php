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
if(isset($_POST['submit']) && ($_POST['submit']=="modify_device"))
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$query=$_POST['serial_num'];
	$sql="Select COUNT(`s_auto_id`)
	from `equipment_indexed`
	where `serial_num`='$query'";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$data = $result->fetch_array(MYSQLI_NUM);
	
	if($data[0] != 1)
	{
		echo 'Serial Number '.$query.' does not exist on the database';
	}
	else
	{
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
		$data=$result->fetch_array(MYSQLI_ASSOC);
		
		echo '<h3>Modifying by Device: '.$query.'</h3>';
		echo '<h3>Auto_ID: '.$data['s_auto_id'].'</h3>';
		echo '<form method="post" action="">';
		echo '<h3>Current Type: '.$data['Type'].'</h3>';
		
		$sql="Select * from `type`";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		
		echo '<select name="modify_device_type">';
		while($dataT=$result->fetch_array(MYSQLI_NUM))
		{
			echo "<option value='" . implode(',', $dataT) . "'>$dataT[1]</option>";
		}
		echo '</select>';
		echo '<button type="submit" name="submit" value="new_type">New</button>';
		echo '<h3>Current Manufacturer: '.$data['Manufacturer'].'</h3>';
		
		$sql="Select * from `manufacturer`";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		
		echo '<select name="modify_device_manufacturer">';
		while($dataM=$result->fetch_array(MYSQLI_NUM))
		{
			echo "<option value='" . implode(',', $dataM) . "'>$dataM[1]</option>";
		}
		echo '</select>';
		echo '<button type="submit" name="submit" value="new_manufacturer">New</button>';
		
		$sql="Select COUNT(`s_auto_id`)
			  from `equipment_indexed`
			  where `serial_num`='$query' AND `s_auto_id` NOT IN
			  (SELECT `auto_id`
			  from `equipment_disabled`)";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		$dataS = $result->fetch_array(MYSQLI_NUM);
		
		if($dataS[0] == 0)
		{
			$status = "Inactive";
		}
		else
		{
			$status = "Active";
		}
		
		echo '<h3>Current Status: '.$status.'</h3>';
		echo '<select name="modify_device_status">';
		echo "<option value=Active>Active</option>";
		echo "<option value=Inactive>Inactive</option>";
		echo '</select>';
		
		echo "<input type=hidden name=snum value='$query'>";
		echo "<input type=hidden name=sId value='$data[s_auto_id]'>";
		echo '<button type="submit" name="submit" value="modify_device_update">Submit</button>';
		echo '</form>';
	}

	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else if(isset($_POST['submit']) && ($_POST['submit']=="new_type"))
{
	  header("Location: https://ec2-3-134-86-72.us-east-2.compute.amazonaws.com/webapp/insert_type.php?submit=insert_type");
	  exit();
}
else if(isset($_POST['submit']) && ($_POST['submit']=="new_manufacturer"))
{
	  header("Location: https://ec2-3-134-86-72.us-east-2.compute.amazonaws.com/webapp/insert_manu.php?submit=insert_manu");
	  exit();
}
else if(isset($_POST['submit']) && ($_POST['submit']=="modify_device_update"))
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$query= $_POST['snum'];
	$queryI= $_POST['sId'];
	$queryS= $_POST['modify_device_status'];
	$queryT= explode (",", $_POST['modify_device_type']);
	$queryM= explode (",", $_POST['modify_device_manufacturer']);
	
	$sql = "UPDATE `equipment_indexed`
			SET `type` = '$queryT[0]', `manufacturer` = '$queryM[0]'
			WHERE `s_auto_id` = '$queryI'";
	$result=$dblink->query($sql) or
				die("Something went wrong with: $sql<br>".$dblink->error);
	
	if($queryS == "Active")
	{
		$sql = "DELETE FROM `equipment_disabled`
				WHERE `auto_id` IN
				(SELECT `s_auto_id` from `equipment_indexed`
					WHERE `s_auto_id` = '$queryI'
				)";
	}
	else
	{
		$sql = "INSERT INTO `equipment_disabled` (`auto_id`, `d_serial_num`)
				SELECT `s_auto_id`, `serial_num` from `equipment_indexed`
				WHERE `s_auto_id` = '$queryI' AND `s_auto_id` NOT IN
				(SELECT `auto_id`
					from `equipment_disabled`
				)";
	}
	$result=$dblink->query($sql) or
				die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<p>Modification succesful</p>';
	echo '<form method="post" action="">';
	echo '<button type="submit" name="submit" value="return_main">Back to Main Page</button>';
	echo '</form>';
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else
{
	  header("Location: https://ec2-3-134-86-72.us-east-2.compute.amazonaws.com/webapp/modify_insert_main.php");
	  exit();
}


	//inner join type
	//on (type.auto_id = `type`)
?>