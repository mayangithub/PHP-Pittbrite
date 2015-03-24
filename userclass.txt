<?php 
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



 ?>