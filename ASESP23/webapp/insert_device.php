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
if(isset($_POST['submit']) && ($_POST['submit']=="insert_device"))
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	
	echo '<form method="post" action="">';
	echo '<h3>Insert type</h3>';
	echo '<h3>Serial Number:</h3>';
	echo '<input type="text" id="fname" name="snum">';
	echo '<h3>Type:</h3>';
	
	$sql="Select * from `type`";
	$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		
	echo '<select name="newD_type">';
	while($dataT=$result->fetch_array(MYSQLI_NUM))
	{
		echo "<option value='" . implode(',', $dataT) . "'>$dataT[1]</option>";
	}
	echo '</select>';
	echo '<h3>Manufacturer:</h3>';
	
	$sql="Select * from `manufacturer`";
	$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		
	echo '<select name="newD_manufacturer">';
	while($dataM=$result->fetch_array(MYSQLI_NUM))
	{
		echo "<option value='" . implode(',', $dataM) . "'>$dataM[1]</option>";
	}
	echo '</select>';
	echo '<h3>Status:</h3>';
	echo '<select name="newD_status">';
	echo "<option value=Active>Active</option>";
	echo "<option value=Inactive>Inactive</option>";
	echo '</select>';
	echo '<button type="submit" name="submit" value="insert_device_update">Insert</button>';
	echo '</form>';
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else if(isset($_POST['submit']) && ($_POST['submit']=="insert_device_update"))
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$serial_num = $_POST['snum'];
	$queryS= $_POST['newD_status'];
	$queryT= explode (",", $_POST['newD_type']);
	$queryM= explode (",", $_POST['newD_manufacturer']);
	
	if (preg_match("/^SN-[a-zA-Z0-9]{10,35}$/i", $serial_num)) 
	{
    	$sql = "SELECT COUNT(`s_auto_id`)
			FROM `equipment_indexed`
			WHERE `serial_num` = '$serial_num'";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		$data = $result->fetch_array(MYSQLI_NUM);
	
		if($data[0] != 0 )
		{
			echo '<p>Device already exists.</p>';
		}
	
		else
		{
			$sql = "INSERT INTO `equipment_indexed` (`type`,`manufacturer`,`serial_num`)
					VALUES ('$queryT[0]','$queryM[0]','$serial_num')";
			$result=$dblink->query($sql) or
				die("Something went wrong with: $sql<br>".$dblink->error);
			
			if($queryS == "Inactive")
			{
				$sql = "INSERT INTO `equipment_disabled` (`auto_id`,`d_serial_num`)
						SELECT `s_auto_id`, `serial_num` from `equipment_indexed`
						WHERE `serial_num` = '$serial_num'";
				$result=$dblink->query($sql) or
					die("Something went wrong with: $sql<br>".$dblink->error);
			}
			else
			{
				$sql = "UPDATE `manufacturer`
						SET `m_status` = 'Active'
						WHERE `m_auto_id` = '$queryM[0]'";
				$result=$dblink->query($sql) or
					die("Something went wrong with: $sql<br>".$dblink->error);
				
				$sql = "UPDATE `type`
						SET `t_status` = 'Active'
						WHERE `t_auto_id` = '$queryT[0]'";
				$result=$dblink->query($sql) or
					die("Something went wrong with: $sql<br>".$dblink->error);
			}
			echo '<p>Insert succesful</p>';
		}
	}
	else
	{
		echo '<p>Invalid Serial Number.</p>';
	}
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