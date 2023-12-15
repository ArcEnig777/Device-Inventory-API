<?php
header('Content-Type: application/json');
header('HTTP/1.1 200 OK');
/*$output[] = 'Status: API Main';
$output[] = 'MSG: Primary Endpoint reached';
$output[] = 'Action: None';
$responseData =json_encode($output);
echo $responseData;*/

$url = $_SERVER['REQUEST_URI'];
$path = parse_url($url,PHP_URL_PATH);
$pathComponents = explode("/",trim($path,"/"));
$endPoint = $pathComponents[1];

function db_iconnect($dbName)
{
	$un="arcwebuser";//username for db
	$pw="OL(6faRkaZCbZlya";//password for db
	$db=$dbName;
	$hostname="localhost";
	$dblink=new mysqli($hostname,$un,$pw,$db);
	return $dblink;
}

switch($endPoint)
{
	case "search":
		include("search.php");
		break;
	case "insert":
		include("insert.php");
		break;
	default:
		$output[] = 'STATUS: Error';
		$output[] = 'MSG: '.$endPoint.' Endpoint not found';
		$output[] = 'ACTION: None';
		$responseData =json_encode($output);
		echo $responseData;
		echo "<h3>Invalid Endpoint: $endPoint </h3>";
		break;
}

?>
