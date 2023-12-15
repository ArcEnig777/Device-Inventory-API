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
if((isset($_POST['submit']) && ($_POST['submit']=="insert_manu")) or (isset($_GET['submit']) && ($_GET['submit']=="insert_manu")))
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	
	echo '<form method="post" action="">';
	echo '<h3>Insert Manufacturer</h3>';
	echo '<h3>Name:</h3>';
	echo '<input type="text" id="fname" name="manu_name">';
	echo '<select name="newM_status">';
	echo "<option value=Active>Active</option>";
	echo "<option value=Inactive>Inactive</option>";
	echo '</select>';
	echo '<button type="submit" name="submit" value="insert_manu_update">Insert</button>';
	echo '</form>';
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else if(isset($_POST['submit']) && ($_POST['submit']=="insert_manu_update"))
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	$name = $_POST['manu_name'];
	$status = $_POST['newM_status'];
	$sql = "SELECT COUNT(`m_name`)
			FROM `manufacturer`
			WHERE `m_name` = '$name'";
	$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
	$data = $result->fetch_array(MYSQLI_NUM);
	
	if($data[0] != 0 )
	{
		echo '<p>Manufacturer already exists.</p>';
	}
	else
	{
		$sql = "INSERT INTO `manufacturer` (`m_name`, `m_status`)
				VALUES ('$name','$status')";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		echo '<p>Insert succesful</p>';
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