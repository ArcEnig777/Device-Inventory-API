<?php
if(!isset($_REQUEST['serial_num']) && !isset($_REQUEST['manufacturer']) && !isset($_REQUEST['type']))
{
	$output[]='Status: ERROR';
	$output[]='MSG: Serial Number, Manufacturer and Type data NULL';
	$output[]='Action: Resend either a valid Serial Number or a valid Manufacturer and Type data but not both';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
if(isset($_REQUEST['serial_num']) && isset($_REQUEST['manufacturer']) && isset($_REQUEST['type']))
{
	$output[]='Status: ERROR';
	$output[]='MSG: Serial Number, Manufacturer and Type data all have value, which is not a valid behavior';
	$output[]='Action: Resend either a valid Serial Number or a valid Manufacturer and Type data but not both';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
if(isset($_REQUEST['serial_num']) && (isset($_REQUEST['manufacturer']) || isset($_REQUEST['type'])))
{
	$output[]='Status: ERROR';
	$output[]='MSG: Serial Number and either Manufacturer or Type both have value, which is not a valid behavior';
	$output[]='Action: Resend either a valid Serial Number or a valid Manufacturer and Type data but not a mixture of both';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
if(!isset($_REQUEST['manufacturer']) && !isset($_REQUEST['serial_num']))
{
	$output[]='Status: ERROR';
	$output[]='MSG: Manufacturer data NULL';
	$output[]='Action: Resend Manufacturer data';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
if(!isset($_REQUEST['type']) && !isset($_REQUEST['serial_num']))
{
	$output[]='Status: ERROR';
	$output[]='MSG: type data NULL';
	$output[]='Action: Resend type data';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
$time_start=microtime(true);
$dblink=db_iconnect("test");
if(!isset($_REQUEST['manufacturer']) || $_REQUEST['manufacturer']=="all")
{
	$manu="`manufacturer` like '%'";
}
else if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_REQUEST['manufacturer']))
{
   	$output[]='Status: ERROR';
	$output[]='MSG: manufacturer data contains Special characters, which is not allowed';
	$output[]='Action: Resend maufacturer data without any special characters';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
else
{	
	$tmp = trim(mysqli_real_escape_string($dblink, $_REQUEST['manufacturer']));
	$manu = "`manufacturer`='$tmp'";
}
if(!isset($_REQUEST['type']) || $_REQUEST['type']=="all")
{
	$type="`type` like '%'";
}
else if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_REQUEST['type']))
{
   	$output[]='Status: ERROR';
	$output[]='MSG: type data contains Special characters, which is not allowed';
	$output[]='Action: Resend type data without any special characters';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
else
{
	$tmp = trim(mysqli_real_escape_string($dblink, $_REQUEST['type']));
	$type = "`type`='$tmp'";
}
$info=array();
if(isset($_REQUEST['serial_num']) && preg_match("/^SN-[a-zA-Z0-9]{10,35}$/i", $_REQUEST['serial_num']))
{
	$tmp = trim(mysqli_real_escape_string($dblink, $_REQUEST['serial_num']));
	$snum = "`serial_num`='$tmp'";
	$sql="SELECT * 
	  FROM `equipment_indexed`
	  WHERE $snum LIMIT 1";
}
else if (isset($_REQUEST['manufacturer']) && isset($_REQUEST['type']))
{
	$sql="SELECT * 
	  FROM `equipment_indexed`
	  WHERE $manu AND $type LIMIT 1000";
}
else
{
	$output[]='Status: ERROR';
	$output[]='MSG: Invalid Serial Number';
	$output[]='Action: Resend a valid Serial Number data';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
$result=$dblink->query($sql) or
	die("Something went wrong with: $sql<br>".$dblink->error);
while($data=$result->fetch_array(MYSQLI_ASSOC))
{
	$sql="SELECT `t_name` 
		  FROM `type`
		  WHERE `t_auto_id` = '$data[type]'";
	$rst=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$tmp=$rst->fetch_array(MYSQLI_ASSOC);
	$type = $tmp['t_name'];
	
	$sql="SELECT `m_name` 
		  FROM `manufacturer`
		  WHERE `m_auto_id` = '$data[manufacturer]'";
	$rst=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$tmp=$rst->fetch_array(MYSQLI_ASSOC);
	$manufacturer = $tmp['m_name'];
	
	$info[]=array($type,$manufacturer,$data['serial_num']);
}
if (empty($info)) {
    $output[]='Status: ERROR';
	$output[]='MSG: No matches found';
	$output[]='Action: Resend a valid Serial Number or Manufacturer and Type that matches the record on the database';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
$infoJson=json_encode($info);
$time_end=microtime(true);
$seconds=$time_end-$time_start;
$execution_time=($seconds)/60;
$output[]='Status: Success';
$output[]='MSG:'.$infoJson;
$output[]='Action:'.$execution_time.'';
$responseData=json_encode($output);
echo $responseData;
?>