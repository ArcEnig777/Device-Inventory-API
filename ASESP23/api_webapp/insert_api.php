<!--- JQUERY SCRIPTS --->
<script src= https://code.jquery.com/jquery-3.5.1.js></script>
<!--- BOOTSTRAP SCRIPTS --->
<script src= https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js></script>
<script src= https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<script>
$(document).ready(function () {
    $('#results').DataTable();
});
</script>
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
	$time_start=microtime(true);
	$manu = $_POST['manufacturer'];
	$type = $_POST['type'];
	$snum =  $_POST['serial_num'];
	$status = $_POST['status'];
	$link = "https://ec2-3-134-86-72.us-east-2.compute.amazonaws.com/api/insert?manufacturer=$manu&type=$type&serial_num=$snum&status=$status&insertmode=device";
	$link = trim($link);
	$link = str_replace ( ' ', '%20', $link);
	$curl = curl_init();
	curl_setopt_array
	($curl, array
	 (
		CURLOPT_URL => $link,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_SSL_VERIFYPEER => false
	));
	$response =  curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
	if($err)
	{
		echo "<h3>cURL Error on Insert API #:$err</h3>";
		die();
	}
	else
		$results  = json_decode($response, true);
	
	$tmp = explode(":",$results[0]);
	$status = trim($tmp[1]);
	if($status=="Success")
	{
		echo '<h3>Insert results</h3>';
		
		$tmp=explode(":",$results[1]);
		$msg = trim($tmp[1]);
		$tmp=explode(":",$results[2]);
		$action = trim($tmp[1]);
		
		echo '<p>'.$msg.'</p>';
		echo '<p>'.$action.'</p>';
		echo '<form method="post" action="">';
		echo '<button type="submit" name="submit" value="">Back</button>';
		echo '</form>';
	}
	else
	{
		echo '<h3>Error</h3>';
		$tmp=explode(":",$results[1]);
		$err = trim($tmp[1]);
		$tmp=explode(":",$results[2]);
		$action = trim($tmp[1]);
		
		echo '<p>'.$err.'</p>';
		echo '<p>'.$action.'</p>';
		echo '<form method="post" action="">';
		echo '<button type="submit" name="submit" value="">Back</button>';
		echo '</form>';
	}
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
	
}
else if(isset($_POST['submit']) && ($_POST['submit']=="submit_m"))
{
	$time_start=microtime(true);
	$manu = $_POST['newmanufacturer'];
	$status = $_POST['status'];
	$link = "https://ec2-3-134-86-72.us-east-2.compute.amazonaws.com/api/insert?newmanufacturer=$manu&status=$status&insertmode=manufacturer";
	$link = trim($link);
	$link = str_replace ( ' ', '%20', $link);
	$curl = curl_init();
	curl_setopt_array
	($curl, array
	 (
		CURLOPT_URL => $link,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_SSL_VERIFYPEER => false
	));
	$response =  curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
	if($err)
	{
		echo "<h3>cURL Error on Insert API #:$err</h3>";
		die();
	}
	else
		$results  = json_decode($response, true); 
	$tmp = explode(":",$results[0]);
	$status = trim($tmp[1]);
	if($status=="Success")
	{
		echo '<h3>Insert results</h3>';
		
		$tmp=explode(":",$results[1]);
		$msg = trim($tmp[1]);
		$tmp=explode(":",$results[2]);
		$action = trim($tmp[1]);
		
		echo '<p>'.$msg.'</p>';
		echo '<p>'.$action.'</p>';
		echo '<form method="post" action="">';
		echo '<button type="submit" name="submit" value="">Back</button>';
		echo '</form>';
	}
	else
	{
		$tmp=explode(":",$results[1]);
		$err = trim($tmp[1]);
		$tmp=explode(":",$results[2]);
		$action = trim($tmp[1]);
		
		echo '<p>'.$err.'</p>';
		echo '<p>'.$action.'</p>';
		echo '<form method="post" action="">';
		echo '<button type="submit" name="submit" value="">Back</button>';
		echo '</form>';
	}
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else if(isset($_POST['submit']) && ($_POST['submit']=="submit_t"))
{
	$time_start=microtime(true);
	$type = $_POST['newtype'];
	$status = $_POST['status'];
	$link = "https://ec2-3-134-86-72.us-east-2.compute.amazonaws.com/api/insert?newtype=$type&status=$status&insertmode=type";
	$link = trim($link);
	$link = str_replace ( ' ', '%20', $link);
	$curl = curl_init();
	curl_setopt_array
	($curl, array
	 (
		CURLOPT_URL => $link,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_SSL_VERIFYPEER => false
	));
	$response =  curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
	if($err)
	{
		echo "<h3>cURL Error on Insert API #:$err</h3>";
		die();
	}
	else
		$results  = json_decode($response, true); 
	$tmp = explode(":",$results[0]);
	$status = trim($tmp[1]);
	if($status=="Success")
	{
		echo '<h3>Insert results</h3>';
		
		$tmp=explode(":",$results[1]);
		$msg = trim($tmp[1]);
		$tmp=explode(":",$results[2]);
		$action = trim($tmp[1]);
		
		echo '<p>'.$msg.'</p>';
		echo '<p>'.$action.'</p>';
		echo '<form method="post" action="">';
		echo '<button type="submit" name="submit" value="">Back</button>';
		echo '</form>';
	}
	else
	{
		$tmp=explode(":",$results[1]);
		$err = trim($tmp[1]);
		$tmp=explode(":",$results[2]);
		$action = trim($tmp[1]);
		
		echo '<p>'.$err.'</p>';
		echo '<p>'.$action.'</p>';
		echo '<form method="post" action="">';
		echo '<button type="submit" name="submit" value="">Back</button>';
		echo '</form>';
	}
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	echo '<h3>Insert Functionality</h3>';
	
	$sql="Select * from `manufacturer`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	
	echo '<form method="post" action="">';
	echo '<p><b>Insert a new Device:</b></p>';
	echo '<p>Select a Manufacturer:';
	echo '<select name="manufacturer">';
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo "<option value='" . $data[0] . "'>$data[1]</option>";
	}
	echo '</select>';
	echo '</p>';
	
	$sql="Select * from `type`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	
	echo '<p>Select a Type:';
	echo '<select name="type">';
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo "<option value='" . $data[0]. "'>$data[1]</option>";
	}
	echo '</select>';
	echo '</p>';
	echo '<p>Type a Serial Number:';
  	echo '<input type="text" id="fname" name="serial_num"><br>';
	echo '</p>';
	echo '<p>Select a Status:';
	echo '<select name="status">';
	echo "<option value='Active'>Active</option>";
	echo "<option value='Inactive'>Inactive</option>";
	echo '</select>';
	echo '</p>';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';
	
	
	echo '<form method="post" action="">';
	echo '<p><b>Insert a new Manufacturer:</b></p>';
	echo '<p>Type a Name:';
  	echo '<input type="text" id="fname" name="newmanufacturer"><br>';
	echo '</p>';
	echo '<p>Select a Status:';
	echo '<select name="status">';
	echo "<option value='Active'>Active</option>";
	echo "<option value='Inactive'>Inactive</option>";
	echo '</select>';
	echo '</p>';
	echo '<button type="submit" name="submit" value="submit_m">Submit</button>';
	echo '</form>';
	
	echo '<form method="post" action="">';
	echo '<p><b>Insert a new Type:</b></p>';
	echo '<p>Type a Name:';
  	echo '<input type="text" id="fname" name="newtype"><br>';
	echo '</p>';
	echo '<p>Select a Status:';
	echo '<select name="status">';
	echo "<option value='Active'>Active</option>";
	echo "<option value='Inactive'>Inactive</option>";
	echo '</select>';
	echo '</p>';
	echo '<button type="submit" name="submit" value="submit_t">Submit</button>';
	echo '</form>';

	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
	//inner join type
	//on (type.auto_id = `type`)
?>