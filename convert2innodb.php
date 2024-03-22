<?php
/**
* Script By: AR Aditya Rathore World®
* Script For: A2z Cloud Hosting
* Version: 1.0
* Date: 01-11-2022
* Upload php script in web directory (default: public_html) and run https://{your_website_url}/convert2innodb.php
*/
require_once( 'wp-config.php' );
echo "Script For Converting Database Engine To InnoDB - By Aditya Rathore";
echo "<hr/>";
//connection variables
$db = array();
$db['host']     = DB_HOST;
$db['user']     = DB_USER;
$db['password'] = DB_PASSWORD;
$db['database'] = DB_NAME;

$mysqli = @new mysqli($db['host'], $db['user'], $db['password'], $db['database']);
//checking connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error ."\n";
    die(1);
}
//fetching all tables from database
$results = $mysqli->query("show tables;");

if ($results===false or $mysqli->connect_errno) {
    echo "MySQL error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error ."\n";
    die(2);
}
//Checking and changing tables to innodb
while ($row= $results->fetch_assoc()) {
	$sql = "SHOW TABLE STATUS WHERE Name = '{$row['Tables_in_' . $db['database']]}'";
	$thisTable = $mysqli->query($sql)->fetch_assoc();

	if ($thisTable['Engine']==='MyISAM') {
		$sql = "alter table " . $row['Tables_in_' . $db['database']]. " ENGINE = InnoDB;";
		echo "Changing {$row['Tables_in_' . $db['database']]} from {$thisTable['Engine']} to InnoDB.\n";
		$mysqli->query($sql);	
	} else {
		echo $row['Tables_in_' . $db['database']] . ' is of the Engine Type ' . $thisTable['Engine'] . ".\n";
		echo "Not changing to InnoDB.\n\n"; 
	}
    echo "<br/>";
}

die(0);
