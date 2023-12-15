<?php
if(!isset($_REQUEST['insertmode']))
{
	$output[]='Status: ERROR';
	$output[]='MSG: No Insert mode selected';
	$output[]='Action: Please go back and access the API through the webapp';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}

$time_start=microtime(true);
$dblink=db_iconnect("test");

$imode = trim(mysqli_real_escape_string($dblink, $_REQUEST['insertmode']));
	
if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $imode))
{
	$output[]='Status: ERROR';
	$output[]='MSG: Special characters found in Insert mode';
	$output[]='Action: Please go back and access the API through the webapp';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}

if($imode != "device" && $imode != "manufacturer" && $imode != "type")
{
	$output[]='Status: ERROR';
	$output[]='MSG: Invalid insert mode';
	$output[]='Action: Please go back and access the API through the webapp';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}

if($imode=="device")
{
	if(!isset($_REQUEST['manufacturer']))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: Manufacturer data for new device NULL';
		$output[]='Action: Resend Manufacturer data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	if(!isset($_REQUEST['type']))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: type data for new device NULL';
		$output[]='Action: Resend type data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	if(!isset($_REQUEST['status']))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: status data for new device NULL';
		$output[]='Action: Resend status data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	if(!isset($_REQUEST['serial_num']))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: Serial Number data for new device NULL';
		$output[]='Action: Resend serial number data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	$tmp = trim(mysqli_real_escape_string($dblink, $_REQUEST['serial_num']));
	
	if(!(preg_match("/^SN-[a-zA-Z0-9]{10,61}$/i", $tmp)))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: Invalid Serial Number data for new device';
		$output[]='Action: Resend a valid serial number';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	if(preg_match("/^[\\s]+/i", $tmp) || preg_match("/[\\s]{2,}/i", $tmp))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: More than one space detected in between characters in Serial Number data for new device';
		$output[]='Action: Resend a valid serial number without more than one space between words';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	$snum = trim(mysqli_real_escape_string($dblink, $_REQUEST['serial_num']));
	
	
	$sql="SELECT `s_auto_id` 
	  	  FROM `equipment_indexed`
	      WHERE `serial_num`='$snum' LIMIT 1";
	$rst=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
	$tmp=$rst->fetch_array(MYSQLI_ASSOC);
	
	if ($tmp == 1)
	{
		$output[]='Status: ERROR';
		$output[]='MSG: Serial Number for new device already exists';
		$output[]='Action: Resend a new and unique serial number';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	$status = trim(mysqli_real_escape_string($dblink, $_REQUEST['status']));
	
	if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $status))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: Special characters found in status data for new device';
		$output[]='Action: Resend valid status data without special characters';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	if($status != "Active" && $status != "Inactive")
	{
		$output[]='Status: ERROR';
		$output[]='MSG: Invalid status data for new device';
		$output[]='Action: Resend valid status data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	$tmp = trim(mysqli_real_escape_string($dblink, $_REQUEST['manufacturer']));
	
	if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $tmp))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: Special characters found in manufacturer data for new device';
		$output[]='Action: Resend valid manufacturer data without special characters';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	$sql="SELECT `m_name` 
		  FROM `manufacturer`
		  WHERE `m_auto_id` = '$tmp' LIMIT 1";
	$rst=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$tmp=$rst->fetch_array(MYSQLI_ASSOC);
	
	if (empty($tmp))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: Invalid manufacturer data for new device';
		$output[]='Action: Resend valid manufacturer data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	$tmp = trim(mysqli_real_escape_string($dblink, $_REQUEST['type']));
	
	if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $tmp))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: Special characters found in type data for new device';
		$output[]='Action: Resend valid type data without special characters';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	$sql="SELECT `t_name` 
		  FROM `type`
		  WHERE `t_auto_id` = '$tmp' LIMIT 1";
	$rst=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$tmp=$rst->fetch_array(MYSQLI_ASSOC);
	
	if (empty($tmp))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: Invalid type data for new device';
		$output[]='Action: Resend valid type data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	$manu = trim(mysqli_real_escape_string($dblink, $_REQUEST['manufacturer']));
	
	$type = trim(mysqli_real_escape_string($dblink, $_REQUEST['type']));
	
}
if($imode=="manufacturer")
{
	if(!isset($_REQUEST['newmanufacturer']))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: name data NULL for new manufacturer';
		$output[]='Action: Resend name data for manufacturer';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	$tmp = trim(mysqli_real_escape_string($dblink, $_REQUEST['newmanufacturer']));
	
	if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $tmp))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: Special characters found in name data for new manufacturer';
		$output[]='Action: Resend valid name data without special characters';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	if(preg_match("/^[\\s]+/i", $tmp) || preg_match("/[\\s]{2,}/i", $tmp))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: More than one space detected in between characters in name data for new manufacturer';
		$output[]='Action: Resend a valid name without more than one space between words';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	if($tmp == "")
	{
		$output[]='Status: ERROR';
		$output[]='MSG: name data for new manufacturer empty';
		$output[]='Action: Resend valid name data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	$sql="SELECT `m_auto_id` 
		  FROM `manufacturer`
		  WHERE `m_name` = '$tmp' LIMIT 1";
	$rst=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$tmp=$rst->fetch_array(MYSQLI_ASSOC);
	
	if (!empty($tmp))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: Name for new manufacturer already exists';
		$output[]='Action: Resend valid and unique name for the new manufacturer';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	if(!isset($_REQUEST['status']))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: status data for new manufacturer NULL';
		$output[]='Action: Resend status data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	$status = trim(mysqli_real_escape_string($dblink, $_REQUEST['status']));
	
	if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $status))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: Special characters found in status data for new manufacturer';
		$output[]='Action: Resend valid status data without special characters';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	if($status != "Active" && $status != "Inactive")
	{
		$output[]='Status: ERROR';
		$output[]='MSG: Invalid status data for new manufacturer';
		$output[]='Action: Resend valid status data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	$newmanu = trim(mysqli_real_escape_string($dblink, $_REQUEST['newmanufacturer']));
	
}
if($imode=="type")
{
	if(!isset($_REQUEST['newtype']))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: name data NULL for new type';
		$output[]='Action: Resend name data for type';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	$tmp = trim(mysqli_real_escape_string($dblink, $_REQUEST['newtype']));
	
	if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $tmp))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: Special characters found in name data for new type';
		$output[]='Action: Resend valid name data without special characters';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	if(preg_match("/^[\\s]+/i", $tmp) || preg_match("/[\\s]{2,}/i", $tmp))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: More than one space detected in between characters in name data for new type';
		$output[]='Action: Resend a valid name without more than one space between words';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	if($tmp == "")
	{
		$output[]='Status: ERROR';
		$output[]='MSG: name data for new type empty';
		$output[]='Action: Resend valid name data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	$sql="SELECT `t_auto_id` 
		  FROM `type`
		  WHERE `t_name` = '$tmp' LIMIT 1";
	$rst=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$tmp=$rst->fetch_array(MYSQLI_ASSOC);
	
	if (!empty($tmp))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: Name for new type already exists';
		$output[]='Action: Resend valid and unique name for the new type';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	if(!isset($_REQUEST['status']))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: status data for new type NULL';
		$output[]='Action: Resend status data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	$status = trim(mysqli_real_escape_string($dblink, $_REQUEST['status']));
	
	if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $status))
	{
		$output[]='Status: ERROR';
		$output[]='MSG: Special characters found in status data for new type';
		$output[]='Action: Resend valid status data without special characters';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	if($status != "Active" && $status != "Inactive")
	{
		$output[]='Status: ERROR';
		$output[]='MSG: Invalid status data for new type';
		$output[]='Action: Resend valid status data';
		$responseData=json_encode($output);
		echo $responseData;
		die();
	}
	
	$newtype = trim(mysqli_real_escape_string($dblink, $_REQUEST['newtype']));
}
if($imode=="device")
{
	$sql = "INSERT INTO `equipment_indexed` (`type`,`manufacturer`,`serial_num`)
			VALUES ('$type','$manu','$snum')";
	$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
			
	if($status == "Inactive")
	{
		$sql = "INSERT INTO `equipment_disabled` (`auto_id`,`d_serial_num`)
				SELECT `s_auto_id`, `serial_num` from `equipment_indexed`
				WHERE `serial_num` = '$snum' LIMIT 1";
		$result=$dblink->query($sql) or
				die("Something went wrong with: $sql<br>".$dblink->error);
	}
	
	$output[]='Status: Success';
	$output[]='MSG: new device has been successfully inserted';
	$output[]='Action: None';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
else if($imode=="manufacturer")
{
	$sql = "INSERT INTO `manufacturer` (`m_name`, `m_status`)
			VALUES ('$newmanu','$status')";
	$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
	
	$output[]='Status: Success';
	$output[]='MSG: new manufacturer has been successfully inserted';
	$output[]='Action: None';
	$responseData=json_encode($output);
	echo $responseData;
	die();
	
}
else if($imode == "type")
{
	$sql = "INSERT INTO `type` (`t_name`, `t_status`)
			VALUES ('$newtype','$status')";
	$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
	
	$output[]='Status: Success';
	$output[]='MSG: new type has been successfully inserted';
	$output[]='Action: None';
	$responseData=json_encode($output);
	echo $responseData;
	die();
	
}
?>