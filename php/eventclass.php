<?php 
	session_start();
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