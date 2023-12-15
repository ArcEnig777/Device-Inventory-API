<?php
$files=scandir($argv[1]);
$arr = array_diff($files, array(".", ".."));
foreach($arr as $key=>$value)
{
	 shell_exec("/usr/bin/php /var/www/html/import-args.php $key $value > /var/www/html/$value.log 2>/var/www/html/$value.log &");
}
echo "Main Proccess Done\n";
print_r($arr);

?>