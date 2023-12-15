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
	$link = "https://ec2-3-134-86-72.us-east-2.compute.amazonaws.com/api/search?manufacturer=$manu&type=$type";
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
		echo "<h3>cURL Error on Search API #:$err</h3>";
		die();
	}
	else
		$results  = json_decode($response, true); 
	$tmp = explode(":",$results[0]);
	$status = trim($tmp[1]);
	if($status=="Success")
	{
		echo '<h3>Search results</h3>';
		$tmp=explode(":",$results[1]);
		$data=json_decode($tmp[1],true);
		echo '<table id="results" class="display" cellspacing="0" width="100%">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>Type</th>';
		echo '<th>Manufacturer</th>';
		echo '<th>Serial Number</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach($data as $key=>$value)
		{
			//$tmp = explode(",",$value);
			echo '<tr>';
			echo '<td>'.$value[0].'</td>';
			echo '<td>'.$value[1].'</td>';
			echo '<td>'.$value[2].'</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
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
	
	echo '<form method="post" action="">';
	echo '<button type="submit" name="submit" value="">Back</button>';
	echo '</form>';
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
	
}
else if(isset($_POST['submit']) && ($_POST['submit']=="submit_s"))
{
	$time_start=microtime(true);
	$sn = $_POST['serial_num'];
	$link = "https://ec2-3-134-86-72.us-east-2.compute.amazonaws.com/api/search?serial_num=$sn";
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
		echo "<h3>cURL Error on Search API #:$err</h3>";
		die();
	}
	else
		$results  = json_decode($response, true); 
	$tmp = explode(":",$results[0]);
	$status = trim($tmp[1]);
	if($status=="Success")
	{
		echo '<h3>Search results</h3>';
		$tmp=explode(":",$results[1]);
		$data=json_decode($tmp[1],true);
		echo '<table id="results" class="display" cellspacing="0" width="100%">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>Type</th>';
		echo '<th>Manufacturer</th>';
		echo '<th>Serial Number</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach($data as $key=>$value)
		{
			//$tmp = explode(",",$value);
			echo '<tr>';
			echo '<td>'.$value[0].'</td>';
			echo '<td>'.$value[1].'</td>';
			echo '<td>'.$value[2].'</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
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
	
	echo '<form method="post" action="">';
	echo '<button type="submit" name="submit" value="">Back</button>';
	echo '</form>';
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
else
{
	$dblink=db_iconnect("test");
	$time_start=microtime(true);
	echo '<h3>Search Functionality</h3>';
	
	$sql="Select * from `manufacturer`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	
	echo '<form method="post" action="">';
	echo '<p>Select a Manufacturer:';
	echo '<select name="manufacturer">';
	echo "<option value=all>All</option>";
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
	echo "<option value=all>All</option>";
	while($data=$result->fetch_array(MYSQLI_NUM))
	{
		echo "<option value='" . $data[0]. "'>$data[1]</option>";
	}
	echo '</select>';
	echo '</p>';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';
	
	
	echo '<form method="post" action="">';
	echo '<p>Type a Serial Number:';
  	echo '<input type="text" id="fname" name="serial_num"><br>';
	echo '</p>';
	echo '<button type="submit" name="submit" value="submit_s">Submit</button>';
	echo '</form>';

	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time: $execution_time minutes or $seconds seconds.</p>\n";
}
	//inner join type
	//on (type.auto_id = `type`)
?>