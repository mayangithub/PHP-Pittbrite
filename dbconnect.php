<?php 
	
	$DBConnect;
	//connect database
	function dbconnect(){
		//save the connection information
		$localhost = "";
		$username = "";
		$password = "";
		//connect the database
		global $DBConnect;
		$DBConnect = mysql_connect($localhost, $username, $password);
		//if the connection failed, return an error
		// if not, select the database
		if (!$DBConnect) {
			die("<p class='error'>Database connection failed.".mysql_errno() . "</p>");
		}
		//if the database can not be selected
		//print an error message
		mysql_select_db("", $DBConnect);
		if(!mysql_select_db("")){
			echo "Unable to select : " . mysql_error();
			exit;
		}

		return $DBConnect;
	}
	
	//close database method
	function closeDB() {
		global $DBConnect;
		mysql_close($DBConnect);
		echo "<p class='info'>Database Closed.</p>";
	}

 ?>