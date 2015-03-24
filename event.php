<?php 
	session_start();
	require_once("functions.php");
	
	if ($_POST['register']) {
		if (!isset($_SESSION['email'])) {
			redirectCreate();
		}
		
	}
	
	$e_id = $_GET['eid'];
	$event = new Event;
	$event->construct($e_id);
	$event->eid = $e_id;
	
	
	$email = $_SESSION['email'];
	$user = new User;
	$user->__construct($email);
	
	
	include_once("header.php");
 ?>
 	<?php 
 		
 		
 		
 		$event->listEventTitle($event->eid);
 		
 		if ($_POST['register']) {
			if (!empty($_POST['quantity'])) {
				$quantity = $_POST['quantity'];
				$unitprice = $event->ticketprice;
				echo $user->registerEvent($e_id, $event->ticketid, $quantity, $unitprice);
				$event->listTicket();
			}else {
				echo "<p class='error'>Please enter a quantity.</p>";
				$event->listTicket();
			}
		}
	
 		if (!$_POST['register']) {
 			$event->listTicket();
 		}
 		
 		$event->listEventInfo();
 	 ?>
 
 
 <?php 
 	include_once("footer.php");
  ?>