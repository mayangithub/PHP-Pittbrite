<?php 
	require_once("dbconnect.php");
	dbconnect();

/*navigator css function*/
function nav($button) {
	$url = $_SERVER["REQUEST_URI"];
	$pos = strpos($url, $button);
	if ($pos>0) {
		return "class='active'";
	}
}
/*check whether fill in email and password*/
function loginFill($email, $pwd) {
	if (empty($email) || empty($pwd)) {
		return "<p class='error'>Please enter both email and password. </p>";
	}
	return null;
}


/*
	check the credentials
	both input the right username and password
	otherwise, return false
	visit database by query
*/
function testCredentials($email, $pwd) {
		$email = mysql_real_escape_string($email);
		$pwd = mysql_real_escape_string($pwd);
		$query = "SELECT first_name FROM yam14.F_user WHERE email= BINARY '" . $email . "' AND password = BINARY '". $pwd . "';";
		//echo $query;
		$result = mysql_query($query);
		if (!$result) {
			$message='Invalid Query' .mysql_errno()." \n";
		    $message .= 'Whole Query' . $query;
		    die($message);
		}//end if
		
		if(mysql_affected_rows()==1 ){
			while($row = mysql_fetch_assoc($result)){
				$_SESSION['username']  = $row['first_name'];
				$_SESSION['email'] = $email;
			}		
			return null;
		}else{
			return  "<p class='error'>Please enter the right credentials. </p>";
		}
		mysql_free_result($result);	
}
/*if pass the chedential test, redirect page*/
function login($email, $pwd) {
	if (empty(loginFill($email, $pwd))) {
		if (empty(testCredentials($email, $pwd))) {
			header("Location:index.php");
		}
	}
}
/*redirect page to home page
get session username
ensure user logged in*/
function redirect() {
	$username = $_SESSION['username'];
	echo $username;
	if (!empty($username)) {
		//echo "Here";
		//header("Location: index.php");
		echo "Here";
	}
}

/*redirect create page
if user not logged in*/

function redirectCreate($email) {
		if (empty($email)) {
			header("Location: login.php");
		}
}

/*check register form
require input all fields*/
function registerFill($firstname, $lastname, $email, $password) {
	if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
		return "<p class='error'>Please enter all fields. </p>";
	}
	return null;
}
/*register new  user 
check whether email already existed
if not existed, insert new user
*/
function registerNewUser($firstname, $lastname, $email, $password) {
	$firstname = mysql_real_escape_string($firstname);
	$lastname = mysql_real_escape_string($lastname);
	$email = mysql_real_escape_string($email);
	$password = mysql_real_escape_string($password);
	$query = "SELECT first_name FROM yam14.F_user WHERE email=BINARY '" . $email . "';";
	$result = mysql_query($query);
	if (!$result) {
		$message='Invalid Query' .mysql_errno()." \n";
	    $message .= 'Whole Query' . $query;
	    die($message);
	}//end if
	
	if(mysql_affected_rows()==1 ){
			return "<p class='error'>Email already existed. Retry!</p>";
	}else {
		$query = "INSERT INTO yam14.F_user (email, first_name, last_name, date_created, password) VALUES ('" . $email . "', '" . $firstname . "','". $lastname ."', NOW(), '". $password . "');";
		//echo $query;
		mysql_query($query);
			
		if(mysql_affected_rows()==1 ){
			return null;
		}else{
			return "<p class='error'>Register Failed. Retry!</p>";
		}
		
	}
	
		
}
/*register user 
after pass all test
set session with username and email*/
function register($firstname, $lastname, $email, $password){
	if (empty(registerFill($firstname, $lastname, $email, $password))) {
		if(empty(registerNewUser($firstname, $lastname, $email, $password))){
			$_SESSION['username']  = $firstname;
			$_SESSION['email'] = $email;
			redirectLogin();
		}
	}
}
/*redirect login page
if user logged in */
function redirectLogin() {
	$username = $_SESSION['username'];
	if (!empty($username)) {
		header("Location: index.php");
	}
}
/*list categories if user click filter button
sticky form*/
function listAllCatPost($catarray) {
	$query = "SELECT c_id, c_name FROM yam14.F_category;";
	$result = mysql_query($query);
	if (!$result) {
		$message='Invalid Query' .mysql_errno()." \n";
	    $message .= 'Whole Query' . $query;
	    die($message);
	}//end if
	
	while($row = mysql_fetch_assoc($result)){
		  echo "<input type='checkbox' name='cat[]' value='" . $row['c_id'] . "' " . checkCheckboxes($catarray, $row['c_id']). " >&nbsp;&nbsp;" . $row['c_name'] . "<br />";
	}	
	
	mysql_free_result($result);	
}
/*list categories if user choose one category on the home page*/
function listAllCatGet($cat) {
	$cat = html_entity_decode($cat);
	$query = "SELECT c_id, c_name FROM yam14.F_category;";
	$result = mysql_query($query);
	if (!$result) {
		$message='Invalid Query' .mysql_errno()." \n";
	    $message .= 'Whole Query' . $query;
	    die($message);
	}//end if
	
	while($row = mysql_fetch_assoc($result)){
		  echo "<input type='checkbox' name='cat[]' value='" . $row['c_id'] . "' " . checkCheckbox($cat, $row['c_name']). " >&nbsp;&nbsp;" . $row['c_name'] . "<br />";
	}	
	
	mysql_free_result($result);	
}

/*list all topics 
after user click filter button
give the sticky form*/
function listAllTopic($tagarray) {
	$query = "SELECT tag_id, tag_name FROM yam14.F_topic;";
	$result = mysql_query($query);
	if (!$result) {
		$message='Invalid Query' .mysql_errno()." \n";
	    $message .= 'Whole Query' . $query;
	    die($message);
	}//end if
	
	while($row = mysql_fetch_assoc($result)){
		  echo "<input type='checkbox' name='topic[]' value='" . $row['tag_id']  . "' " . checkCheckboxes($tagarray, $row['tag_id']) . " >&nbsp;&nbsp;" . $row['tag_name'] . "<br />";
	}	
	
	mysql_free_result($result);	
}
/*check checkbox sticky form function after filtering*/
function checkCheckboxes($choices, $value) {
	foreach ($choices as $key => $choice) {
		if ($choice == $value) {
			return "checked";
		}
	}
}
/*check checkbox sticky form function after homepage*/
function checkCheckbox($choice, $value) {
	if ($choice == $value) {
		return "checked";
	}
}
/*update event status before list events
check by datetime*/
function updateEventStatus() {
	$query = "UPDATE `yam14`.`F_event` SET `status` = 'ended' WHERE end_datetime < now()  AND e_id>0;";
	mysql_query($query);
	$query = "UPDATE `yam14`.`F_event` SET `status` = 'live' WHERE start_datetime > now() AND e_id>0;";
	mysql_query($query);
	$query = "UPDATE `yam14`.`F_event` SET `status` = 'started' WHERE start_datetime <= now() AND end_datetime > now() AND e_id>0;";
	mysql_query($query);
}

/*list events after filter 
according to categories and tags
give different query then do selection*/
function listPostEvents($catarray, $tagarray) {
	updateEventStatus();
	if (empty($catarray) && empty($tagarray)) {
		$query = "SELECT e_id, e_title, e_description, c_name, tag_name, start_datetime, end_datetime, capacity, F_venue.name AS vname, address,F_organizer.name AS oname, F_event.status  
		FROM yam14.F_event, yam14.F_venue, yam14.F_category, yam14.F_topic, yam14.F_organizer
		WHERE F_event.venue_id = F_venue.v_id 
		AND F_category.c_id = F_event.c_id 
		AND F_topic.tag_id = F_event.tag_id 
		AND F_organizer.o_id = F_event.organizer_id 
		AND status<>'ended';";
	}elseif (!empty($catarray) && empty($tagarray)) {
		$query = "SELECT e_id, e_title, e_description, c_name, tag_name, start_datetime, end_datetime, capacity, F_venue.name AS vname, address,F_organizer.name AS oname, F_event.status 
		FROM yam14.F_event, yam14.F_venue, yam14.F_category, yam14.F_topic, yam14.F_organizer
		WHERE F_event.venue_id = F_venue.v_id 
		AND F_category.c_id = F_event.c_id 
		AND F_topic.tag_id = F_event.tag_id 
		AND F_organizer.o_id = F_event.organizer_id 
		AND status<>'ended' 
		AND (";
		$ccount = count($catarray);
		for ($i = 0; $i < $ccount; $i++) {
			if ($i!==$ccount-1) {
				$query .= "F_event.c_id = $catarray[$i] OR ";
			}else {
				$query .= "F_event.c_id = $catarray[$i]";
			}
		}//end for
		$query .= ");";
	}//end elseif catarray not empty, empty tagarray
	elseif (empty($catarray) && !empty($tagarray)) {
		$query = "SELECT e_id, e_title, e_description, c_name, tag_name, start_datetime, end_datetime, capacity, F_venue.name AS vname, address,F_organizer.name AS oname, F_event.status  
		FROM yam14.F_event, yam14.F_venue, yam14.F_category, yam14.F_topic, yam14.F_organizer
		WHERE F_event.venue_id = F_venue.v_id 
		AND F_category.c_id = F_event.c_id 
		AND F_topic.tag_id = F_event.tag_id 
		AND F_organizer.o_id = F_event.organizer_id 
		AND status<>'ended' 
		AND (";
		$tcount = count($tagarray);
		for ($j = 0; $j < $tcount; $j++) {
			if ($j!==$tcount-1) {
				$query .= "F_event.tag_id = $tagarray[$j] OR ";
			}else {
				$query .= "F_event.tag_id = $tagarray[$j]";
			}
		}//end for
		$query .= ");";
	}else {
		$query = "SELECT e_id, e_title, e_description, c_name, tag_name, start_datetime, end_datetime, capacity, F_venue.name AS vname, address,F_organizer.name AS oname, F_event.status 
		FROM yam14.F_event, yam14.F_venue, yam14.F_category, yam14.F_topic, yam14.F_organizer
		WHERE F_event.venue_id = F_venue.v_id 
		AND F_category.c_id = F_event.c_id 
		AND F_topic.tag_id = F_event.tag_id 
		AND F_organizer.o_id = F_event.organizer_id 
		AND status<>'ended' 
		AND (";
		$ccount = count($catarray);
		for ($i = 0; $i < $ccount; $i++) {
			if ($i!==$ccount-1) {
				$query .= "F_event.c_id = $catarray[$i] OR ";
			}else {
				$query .= "F_event.c_id = $catarray[$i]";
			}
		}//end for
		$query .= ") AND (";
		$tcount = count($tagarray);
		for ($j = 0; $j < $tcount; $j++) {
			if ($j!==$tcount-1) {
				$query .= "F_event.tag_id = $tagarray[$j] OR ";
			}else {
				$query .= "F_event.tag_id = $tagarray[$j]";
			}
		}//end for
		$query .= ");";
		
	}//end else both not empty
//	echo $query;
	$result = mysql_query($query);
	if (!$result) {
		$message='Invalid Query' .mysql_errno()." \n";
	    $message .= 'Whole Query' . $query;
	    die($message);
	}//end if
	
	while($row = mysql_fetch_assoc($result)){
		echo "<div class='card'>";
		if (file_exists("images/logo/" . $row['e_id'] . ".jpg")) {
			echo "<img src=\"images/logo/" . $row['e_id'] . ".jpg\" alt=\"\" width=\"100px\" height=\"100px\" style=\"float: left; margin: 10px;\"/>";
		}else {
			echo "<img src=\"images/default.jpg\" alt=\"\" width=\"100px\" height=\"100px\" style=\"float: left; margin: 10px;\"/>";
		}
		
		echo "<h4><a href=\"event.php?eid={$row['e_id']}\">{$row['e_title']}</a></h4>";
		echo "<p>{$row['c_name']} {$row['tag_name']} By {$row['oname']}</p>";
		echo "<p>" . date('l, M d, Y',strtotime($row['start_datetime'])) . "  " . date('H:i A',strtotime($row['start_datetime'])) . "</p>";
		echo "<p>Location: " . $row['vname'] . "</p>";
		echo "</div>";
		
	}	
	
	mysql_free_result($result);	
	
}//end function listPostEvents


/*list events after home page
corespondent to category selected*/
function listGetEvents($cat) {
	updateEventStatus();
	$query = "SELECT e_id, e_title, e_description, c_name, tag_name, start_datetime, end_datetime, capacity, F_venue.name AS vname, address,F_organizer.name AS oname, F_event.status  
	FROM yam14.F_event, yam14.F_venue, yam14.F_category, yam14.F_topic, yam14.F_organizer
	WHERE F_event.venue_id = F_venue.v_id 
	AND F_category.c_id = F_event.c_id 
	AND F_topic.tag_id = F_event.tag_id 
	AND F_organizer.o_id = F_event.organizer_id 
	AND F_category.c_name = '$cat' 
	AND status<>'ended';";
	$result = mysql_query($query);
	if (!$result) {
		$message='Invalid Query' .mysql_errno()." \n";
	    $message .= 'Whole Query' . $query;
	    die($message);
	}//end if
	
	while($row = mysql_fetch_assoc($result)){
		echo "<div class='card'>";
		if (file_exists("images/logo/" . $row['e_id'] . ".jpg")) {
			echo "<img src=\"images/logo/" . $row['e_id'] . ".jpg\" alt=\"\" width=\"100px\" height=\"100px\" style=\"float: left; margin: 10px;\"/>";
		}else {
			echo "<img src=\"images/default.jpg\" alt=\"\" width=\"100px\" height=\"100px\" style=\"float: left; margin: 10px;\"/>";
		}
		
		echo "<h4><a href=\"event.php?eid={$row['e_id']}\">{$row['e_title']}</a></h4>";
		echo "<p>{$row['c_name']} {$row['tag_name']} By {$row['oname']}</p>";
		echo "<p>" . date('l, M d, Y',strtotime($row['start_datetime'])) . "  " . date('H:i A',strtotime($row['start_datetime'])) . "</p>";
		echo "<p>Location: " . $row['vname'] . "</p>";
		echo "</div>";
		
	}	
	
	mysql_free_result($result);	
	
}//end function listGetEvents





/*check time function when create new event*/
function checkTime($startdt, $enddt) {
	if ($startdt<$enddt) {
		return true;
	}else {
		return false;
	}
}


/*list category in dropdown list when creating new event*/
function listCategory($category) {
	$category = mysql_real_escape_string($category);
	$query = "SELECT c_id, c_name FROM yam14.F_category;";
	$result = mysql_query($query);
	if (!$result) {
		$message='Invalid Query' .mysql_errno()." \n";
	    $message .= 'Whole Query' . $query;
	    die($message);
	}//end if
	
	while($row = mysql_fetch_assoc($result)){
		  echo "<option value=\"" . $row['c_id'] . "\" ";
		  echo checkDropdown($category, $row['c_id']);
		  echo  ">" . $row['c_name'] . "</option>";
	}	
	
	mysql_free_result($result);	
}

/*list topics in dropdown list when create new event*/
function listTopic($topic) {
	$query = "SELECT tag_id, tag_name FROM yam14.F_topic;";
	$result = mysql_query($query);
	if (!$result) {
		$message='Invalid Query' .mysql_errno()." \n";
	    $message .= 'Whole Query' . $query;
	    die($message);
	}//end if
	
	while($row = mysql_fetch_assoc($result)){
		  echo "<option value=\"" . $row['tag_id'] . "\" ";
		  echo checkDropdown($topic, $row['tag_id']) ;
		  echo ">" . $row['tag_name'] . "</option>";
	}	
	
	mysql_free_result($result);	
}
/*list venue in dropdown list when creating new event*/
function listVenue($venue) {
	$query = "SELECT v_id, name FROM yam14.F_venue;";
	$result = mysql_query($query);
	if (!$result) {
		$message='Invalid Query' .mysql_errno()." \n";
	    $message .= 'Whole Query' . $query;
	    die($message);
	}//end if
	
	while($row = mysql_fetch_assoc($result)){
		  echo "<option value=\"" . $row['v_id'] . "\" ";
		  echo checkDropdown($venue, $row['v_id']) ;
		  echo ">" . $row['name'] . "</option>";
	}	
	
	mysql_free_result($result);	
}

/*dropdown list sticky form*/
function checkDropdown($option, $value) {
		if ($option == $value) {
			return "selected";
		}
}

/*if the field is empty but not 0
output error */
function testField($input) {
	if (empty($input)&&$input!=="0") {
		echo "Required.";
	}
}

/*test whether all required fields are entered*/
function testRequired($title, $location, $ticketname, $available, $price) {
	if (!empty($title)&&!empty($location)&&!empty($ticketname)&&!empty($available)) {
		if (empty($price)&&$price!=="0") {
			return false;
		}else {
			return true;
		}
	}//no empty
	return false;
}
/*test file size larger than 100k*/
function testFileSize($filesize) {
	if ($filesize>102400) {
		return "<label style=\"color: red;\">Please select a file less than 100K. </label>";
	}
}
/*test file type is not an image type*/
function testFileType($filetype) {
	if ($filetype!="image/jpg" && $filetype != "image/png" && $filetype != "image/jpeg"&& $filetype != "image/gif") {
		return "<label style=\"color: red;\">Please select an image file. </label>";
	}
}



/*User class */
class User {
	
	public $uid;
	public $email;
	public $firstname;
	public $lastname;
	
	/*construct function 
	using user email to identify user and set user attributes*/
	function __construct($emal) {
		$emal = mysql_real_escape_string($emal);
		$this->email = $emal;
		$query = "SELECT u_id, email, first_name, last_name  
		FROM yam14.F_user 
		WHERE email = BINARY '" . $this->email . "';";
		
		$result = mysql_query($query);
		if (!$result) {
			$message='Invalid Query' .mysql_errno()." \n";
		    $message .= 'Whole Query' . $query;
		    die($message);
		}//end if
		
		while($row = mysql_fetch_assoc($result)){
			$this->uid = $row['u_id'];
			$this->firstname = $row['first_name'];
			$this->lastname = $row['last_name'];
		}
		mysql_free_result($result);
		
	}//end construction
	
	
	/*using event id , ticket id, quantity, price to register an event for a user*/
	function registerEvent($eid, $tid, $quantity, $unitprice) {
		$eid = mysql_real_escape_string($eid);
		$tid = mysql_real_escape_string($tid);
		$quantity = mysql_real_escape_string($quantity);
		$unitprice = mysql_real_escape_string($unitprice);
		
		if (empty($this->uid)) {
			return "<p class='error'>Please login in first.</p>";
		}
		
		$query = "UPDATE `yam14`.`F_ticket`
		SET
		`quantity_available` = quantity_available-$quantity,
		`quantity_sold` = quantity_sold+$quantity 
		WHERE `t_id` = $tid AND `e_id` = $eid
		AND quantity_available>=$quantity;";
		
		mysql_query($query);
		
		if(mysql_affected_rows()<1 ){
			return "<p class='error'>Registration Failed. Retry!</p>";
		}
			
		$total = $quantity * $unitprice;
		$query = "INSERT INTO `yam14`.`F_registration`(`u_id`,`e_id`,`t_id`,`quantity`,`unit_price`,`total`,`reg_time`)
		VALUES($this->uid,$eid,$tid,$quantity,$unitprice,$total,CURRENT_TIMESTAMP);";
		
		mysql_query($query);
			
		if(mysql_affected_rows()!==1 ){
			return "<p class='error'>Registration Failed. Retry!</p>";
		}
		
		$query = "UPDATE `yam14`.`F_event`
		SET
		`num_attendee_rows` = num_attendee_rows+$quantity 
		WHERE `e_id` = $eid;";
		mysql_query($query);
		
		if(mysql_affected_rows()!==1 ){
			return "<p class='error'>Registration Failed. Retry!</p>";
		}else {
			return "<p class='success'><b>Registration Success!</b></p>";
		}
		
		
		
	}//end registerEvent
	
	/*list organizer for a user */
	function listOrganizer($uid, $org) {
		$uid = mysql_real_escape_string($uid);
		$org = mysql_real_escape_string($org);
		$query = "SELECT o_id, name FROM yam14.F_organizer where u_id = $uid;";
		$result = mysql_query($query);
		if (!$result) {
			$message='Invalid Query' .mysql_errno()." \n";
		    $message .= 'Whole Query' . $query;
		    die($message);
		}//end if
		
		if (!empty($result)) {
			while($row = mysql_fetch_assoc($result)){
				  echo "<option value=\"" . $row['o_id'] . "\" ";
				  echo checkDropdown($org, $row['o_id']);
				  echo ">" . $row['name'] . "</option>";
			}
		}
//else{
//			echo "<option value='1'>Unknown</option>";
//		
//		}

		if (mysql_affected_rows()==0) {
			echo "<option value='1'>Unknown</option>";
		}
		
		
		mysql_free_result($result);	
	}//end list organizer
	
	/*user can create new event
	input all fields info
	using date time to decide event status*/
	function insertEvent($title, $desp,  $cid, $tagid, $startdt, $enddt, $location, $org, $available) {
		$title = mysql_real_escape_string($title);
		$desp = mysql_real_escape_string($desp);
		$cid = mysql_real_escape_string($cid);
		$tagid = mysql_real_escape_string($tagid);
		$startdt = mysql_real_escape_string($startdt);
		$enddt = mysql_real_escape_string($enddt);
		$location = mysql_real_escape_string($location);
		$org = mysql_real_escape_string($org);
		$available = mysql_real_escape_string($available);
	
	
		if (date('Y-m-d h:i',strtotime($startdt))>date("Y-m-d h:i")) {
			$status = "live";
		}elseif (date('Y-m-d h:i',strtotime($startdt))<date("Y-m-d h:i")) {
			$status = "started";
		} elseif (date('Y-m-d h:i',strtotime($enddt))<date("Y-m-d h:i")) {
			$status = "ended";
		} 
		$query = "INSERT INTO `yam14`.`F_event`(`e_title`,`e_description`,`c_id`,`tag_id`,
		`start_datetime`,`end_datetime`,`created`,`modified`,`capacity`,
		`num_attendee_rows`,`status`,`venue_id`,`organizer_id`)
		VALUES ('$title', '$desp',$cid,$tagid,'$startdt','$enddt',now(),
		CURRENT_TIMESTAMP,$available,0,'$status',$location,$org);";
		
		mysql_query($query);
		
		if (mysql_affected_rows()==1) {
			return null;
		}else{
			return "<p class='error'>Register Failed. Retry!</p>";
		}
	}
	
	/*insert ticket for an event*/
	function insertTicket($eid, $name, $price, $quantity) {
		$eid = mysql_real_escape_string($eid);
		$name = mysql_real_escape_string($name);
		$price = mysql_real_escape_string($price);
		$quantity = mysql_real_escape_string($quantity);
		
		$query = "INSERT INTO `yam14`.`F_ticket`
		(`e_id`,`name`,`price`,`quantity_available`,`quantity_sold`) VALUES ($eid,'$name',$price,$quantity,0);";
		
		mysql_query($query);
		
		if (mysql_affected_rows()==1) {
			return "<p class='success'><b>New Event Created Successfully!</b></p>";
		}else {
			return "<p class='error'>Register Failed. Retry!</p>";
		}
		
	}
	
	
	
	/*after insert a new event
	find the event id 
	and return for other uses*/
	function findEventID($title, $desp, $cid, $tagid, $startdt, $enddt, $location, $org, $available) {
		$title = mysql_real_escape_string($title);
		$desp = mysql_real_escape_string($desp);
		$cid = mysql_real_escape_string($cid);
		$tagid = mysql_real_escape_string($tagid);
		$startdt = mysql_real_escape_string($startdt);
		$enddt = mysql_real_escape_string($enddt);
		$location = mysql_real_escape_string($location);
		$org = mysql_real_escape_string($org);
		$available = mysql_real_escape_string($available);
		
		$query = "SELECT e_id FROM yam14.F_event
		WHERE e_title = '$title' AND e_description = '$desp'
		AND c_id = $cid  AND tag_id = $tagid
		AND capacity = $available
		AND num_attendee_rows = 0
		AND venue_id = $location
		AND organizer_id = $org;";
		$result = mysql_query($query);
		if (!$result) {
			$message='Invalid Query' .mysql_errno()." \n";
		    $message .= 'Whole Query' . $query;
		    die($message);
		}//end if
		
		while($row = mysql_fetch_assoc($result)){
			  return$row['e_id'];
		}	
		
		mysql_free_result($result);	
	}
	
	/*create new event function 
	check start end time 
	test all required fields are entered 
	if user upload the logo test it 
	*/
	function createNewEvent($title, $desp, $cid, $tagid, $startdt, $enddt, $location, $org, $ticketname, $available, $price, $tempname, $logoname, $size, $type) {
			if (checkTime($startdt, $enddt)) {
				if (testRequired($title, $location, $ticketname, $available, $price)) {
						if (!empty($tempname) && empty(testFileSize($size))&&empty(testFileType($type))) {
								if (empty($this->insertEvent($title, $desp,  $cid, $tagid, $startdt, $enddt, $location, $org, $available))) {
												$eid = $this->findEventID($title, $desp, $cid, $tagid, $startdt, $enddt, $location, $org, $available);
												echo $this->insertTicket($eid, $ticketname, $price, $available);
												echo $this->uploadLogo($tempname, $logoname, $eid);
								}else {
									echo "<p class='error'><b>Create event failed.</b></p><br />";
								}//end has file
						}elseif (empty($tempname)) {
								if (empty($this->insertEvent($title, $desp,  $cid, $tagid, $startdt, $enddt, $location, $org, $available))) {
											$eid = $this->findEventID($title, $desp, $cid, $tagid, $startdt, $enddt, $location, $org, $available);
											echo $this->insertTicket($eid, $ticketname, $price, $available);
								}else {
									echo "<p class='error'><b>Create event failed.</b></p><br />";
								}
						}  //end no file
				}else {
					echo "<p class='error'><b>Please enter all required fields.</b></p><br />";
				}//end test required fields
		}else {
			echo "<p class='error'><b>Please select a start time before end time.</b></p><br />";
		}//end check time
	}//end createNewEvent
	
	
	/*upload logo function 
	and move it to logo folder*/
	function uploadLogo($tempname, $logo, $eid) {
			
			move_uploaded_file($tempname, "images/logo/" . $eid . ".jpg");
			return "<p class='success'><b>Logo uploaded.</b></p>";
	}
	/*user can review his registration history
	using user id */
	function listRegistration($uid) {
		$uid = mysql_real_escape_string($uid);
		$query = "SELECT r.e_id, e.e_title, t.name,r.quantity, r.unit_price, r.reg_time  
		FROM yam14.F_registration r, yam14.F_event e, yam14.F_ticket t
		WHERE r.e_id = e.e_id
		AND t.t_id = r.t_id
		AND r.u_id = $uid;";
		$result = mysql_query($query);
		
		if (!$result) {
			$message='Invalid Query' .mysql_errno()." \n";
		    $message .= 'Whole Query' . $query;
		    die($message);
		}//end if
		
		while($row = mysql_fetch_assoc($result)){
			  echo "<tr>";
			  echo "<td><a href=\"event.php?eid=".$row['e_id']."\">".$row['e_title']."</a></td>";
			  echo "<td>".$row['name']."</td>";
			  echo "<td>".$row['quantity']."</td>";
			  echo "<td>".date('M d, Y h:i A',strtotime($row['reg_time']))."</td>";
			  echo "</tr>";
		}	
		
		mysql_free_result($result);	
		
		
	}//end list registration history 
	
	/*in profile page,
	user can modify his password
	if not enter new password or enter the same password with old password
	return error message*/
	function modifyPwd($uid, $newpwd) {
		$uid = mysql_real_escape_string($uid);
		$newpwd = mysql_real_escape_string($newpwd);
		if (!empty($newpwd)) {
			$query = "UPDATE `yam14`.`F_user`
			SET `password` = '$newpwd' WHERE `u_id` = $uid;";
			mysql_query($query);
			if (mysql_affected_rows()==1) {
				echo "<p class='success'><b>Password updated.</b></p>";
			}elseif (mysql_affected_rows()==0) {
				echo "<p class='error'><b>Please enter a different password.</b></p>";
			}else {
				echo "<p class='error'><b>Password update failed.</b></p>";
			}
		}else {
			echo "<p class='error'><b>Please enter your new password.</b></p>";
		}
		
	}
	
	
	
}//end User Class



 /*Event Class Object*/
 
 class Event {
 	public $eid;
 	public $title;
 	public $category;
 	public $tag;
 	public $description;
 	public $startdate;
 	public $starttime;
 	public $enddate;
 	public $endtime;
 	public $capacity;
 	public $organizer;
 	public $venue;
 	public $address;
 	public $ticketid;
 	public $ticketname;
 	public $ticketprice;
 	public $ticketavailable;
 	
 	/*event class construct function
 	get event id and set up all attributes*/
 	function construct($eidd) {
 		$eidd = mysql_real_escape_string($eidd);
 		$query = "SELECT t.t_id, t.name, t.price, t.quantity_available  
 		FROM yam14.F_ticket t, yam14.F_event e
 		WHERE e.e_id = t.e_id
 		AND t.e_id = " . $eidd . ";";
 		
 		$result = mysql_query($query);
 		if (!$result) {
 			$message='Invalid Query' .mysql_errno()." \n";
 		    $message .= 'Whole Query' . $query;
 		    die($message);
 		}//end if
 		
 		while($row = mysql_fetch_assoc($result)){
 			$this->ticketid = $row['t_id'];
 			$this->ticketname = $row['name'];
 			$this->ticketprice = $row['price'];
 			$this->ticketavailable = $row['quantity_available'];
 		}	
 		
 		mysql_free_result($result);
 	}
 	/*use event id get event title*/
 	function  listEventTitle($e_id) {
 		$e_id = mysql_real_escape_string($e_id);
 		$this->eid = $e_id;
 		$query = "SELECT e_title, v.name, start_datetime, end_datetime  
 		FROM yam14.F_event e, yam14.F_venue v 
 		WHERE e.venue_id = v.v_id
 		AND e_id = $this->eid;";
 		
 		$result = mysql_query($query);
 		if (!$result) {
 			$message='Invalid Query' .mysql_errno()." \n";
 		    $message .= 'Whole Query' . $query;
 		    die($message);
 		}//end if
 		
 		while($row = mysql_fetch_assoc($result)){
 			
 			$this->title = $row['e_title'];
 			$this->venue = $row['name'];
 			$this->startdate = date('l, M d, Y',strtotime($row['start_datetime']));
 			$this->starttime = date('H:i A',strtotime($row['start_datetime']));
 			$this->enddate = date('l, M d, Y',strtotime($row['end_datetime']));
 			$this->endtime =date('H:i A',strtotime($row['end_datetime']));
 			
 			echo "<div>";
 			echo "<h3>$this->title</h3>";
 			echo "<h4>$this->venue</h4>";
 			echo "<h4>From $this->startdate $this->starttime </h4><h4>To $this->enddate $this->endtime </h4>";
 			echo "<hr />";
 			echo "</div>";
 		}	
 		
 		mysql_free_result($result);
 		
 	}
 	
 	/*list ticket of the event
 	using event id*/
 	function listTicket() {
 		$query = "SELECT t.t_id, t.name, t.price, t.quantity_available  
 		FROM yam14.F_ticket t, yam14.F_event e
 		WHERE e.e_id = t.e_id
 		AND t.e_id = " . $this->eid . ";";
 		
 		$result = mysql_query($query);
 		if (!$result) {
 			$message='Invalid Query' .mysql_errno()." \n";
 		    $message .= 'Whole Query' . $query;
 		    die($message);
 		}//end if
 		
 		while($row = mysql_fetch_assoc($result)){
 			$this->ticketid = $row['t_id'];
 			$this->ticketname = $row['name'];
 			$this->ticketprice = $row['price'];
 			$this->ticketavailable = $row['quantity_available'];
 			
 			echo   "<table class=\"table\">";
 			echo 	"<form method=\"post\" action=\"event.php?eid=$this->eid\">";
 			echo  	"<tr><th colspan=\"4\">Ticket Information</th></tr>";
 			echo 	"<tr><td>TYPE</td><td>REMAINING</td><td>PRICE</td><td>QUANTITY</td></tr>";
 			echo  	"<tr>";
 			echo   "<td>$this->ticketname</td>";
 			echo   "<td>";
 			if ($this->ticketavailable==0) {
 				echo "<p class='error'>Sold Out</p>";
 			}else {
 				echo $this->ticketavailable;
 			}
 			echo 	"</td>";
 			echo 	"<td>";
 			if ($this->ticketprice==0) {
 				echo "FREE";
 			}else {
 				echo  "$" . $this->ticketprice;
 			}
 			echo    "</td>";
 			echo 	"<td><input type=\"number\" name=\"quantity\" value=\"\"  step='1' placeholder=\"1\" min=\"1\" max=\"$this->ticketavailable\" ";
 			if ($this->ticketavailable==0) {
 				echo "disabled";
 			}
 			echo   "/></td>";
 			echo 	"</tr>";
 			echo 	"<tr><td>";
 			echo 	"</td><td></td><td></td><td><input type=\"submit\" name=\"register\" value=\"Register\"  class=\"btn btn-lg btn-success\" ";
 			if ($this->ticketavailable==0) {
 				echo "disabled";
 			}
 			echo "  /></td></tr>";
 			echo  	"</form>";
 			echo 	"</table>";
 		}	
 		
 		mysql_free_result($result);
 		
 			
 			
 	}
 	
 	/*list event information
 	using event id
 	change the date time to a friendly way*/
 	function listEventInfo() {
 		$query = "SELECT  e_description, c_name, tag_name, start_datetime, end_datetime, capacity, v.name AS vname, address,o.name AS oname, v.address, v.city, v.state, v.postal_code  
 		FROM yam14.F_event e, yam14.F_venue v, yam14.F_category c, yam14.F_topic t, yam14.F_organizer o
 		WHERE e.venue_id = v.v_id 
 		AND c.c_id = e.c_id 
 		AND t.tag_id = e.tag_id 
 		AND o.o_id = e.organizer_id
 		AND e.e_id = $this->eid ;";
 		
 		$result = mysql_query($query);
 		if (!$result) {
 			$message='Invalid Query' .mysql_errno()." \n";
 		    $message .= 'Whole Query' . $query;
 		    die($message);
 		}//end if
 		
 		while($row = mysql_fetch_assoc($result)){
 			$this->category = $row['c_name'];
 			$this->tag = $row['tag_name'];
 			$this->description = $row['e_description'];
 			$this->startdate = date('l, M d, Y',strtotime($row['start_datetime']));
 			$this->starttime = date('H:i A',strtotime($row['start_datetime']));
 			$this->enddate = date('l, M d, Y',strtotime($row['end_datetime']));
 			$this->endtime =date('H:i A',strtotime($row['end_datetime']));
 			$this->capacity = $row['capacity'];
 			$this->venue = $row['vname'];
 			$this->address = $row['address'] . ",  " . $row['city'] . ", " . $row['state'] . "  " . $row['postal_code'];
 			$this->organizer = $row['oname'];
 			echo "<hr />";
 			echo   "<div class=\"panel panel-default\">";
 			echo 	"<div class=\"panel-heading\">";
 			echo 	"<h3 class=\"panel-title\">Event Information</h3>";
 			echo 	"</div>";
 			echo 	"<div class=\"panel-body\">";
 			echo   "<p><b>Description: </b>$this->description</p>";
 			echo 	"<p><b>Category: </b>$this->category</p>";
 			echo 	"<p><b>Topic: </b>$this->tag</p>";
 			echo 	"<p><b>Start Date: </b>$this->startdate</p>";
 			echo 	"<p><b>Start Time: </b>$this->starttime</p>";
 			echo 	"<p><b>End Date: </b>$this->enddate</p>";
 			echo 	"<p><b>End Time: </b>$this->endtime</p>";
 			echo 	"<p><b>Capacity: </b>$this->capacity</p>";
 			echo 	"<p><b>Organizer: </b>$this->organizer</p>";
 			echo 	"<p><b>Location: </b>$this->venue</p>";
 			echo 	"<p><b>Address: </b>$this->address</p>";
 			
 			echo 	"</div>";
 			echo 	"</div>";
 				
 			
 						
 		}	
 		
 		mysql_free_result($result);
 	}
 	
 	
 	
 	
 	
 }//end Event Class
 
 




 ?>
 
