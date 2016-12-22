<?php
    echo "hello";
	#Include the connect.php file
	include('connect.php');
	#Connect to the database
	//connection String
	$connect = mysql_connect($hostname, $username, $password)
	or die('Could not connect: ' . mysql_error());
	//Select The database
	$bool = mysql_select_db($database, $connect);
	if ($bool === False){
	   print "can't find $database";
	}

	$query = "SELECT * FROM  `tennisMatch`";
	$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
	// get data and store in a json array
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$data[] = array(
			'Winner' => $row['Winner'],
			'Surface' => $row['Surface'],
			'Tournament' => $row['Tournament']
		  );
	}

	echo json_encode($data);
?>