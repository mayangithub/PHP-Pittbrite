<?php 
	session_start();
	if (empty($_SESSION['username'])) {
		header("Location: login.php");
	}
	require_once("functions.php");
	
	$email = $_SESSION['email'];
	$user = new User;
	$user->__construct($email);
	echo $user->uid;
	include_once("header.php");

 ?>
 	<p class="title">Create An Event</p><br />
 	
 	<?php 
 		if ($_POST['save']) {
 			$title = $_POST['name'];
 			$location = $_POST['location'];
 			$ticketname = $_POST['tname'];
 			$quantity = $_POST['quantity'];
 			$price = $_POST['price'];
 			$desp = $_POST['description'];
 			$cid = $_POST['category'];
 			$tagid = $_POST['topic'];
 			$startdt = $_POST['startdate'] . " " .  $_POST['starttime'];
 			$enddt = $_POST['enddate'] ." " .  $_POST['endtime'];
 			$org = $_POST['org'];
 			$file = $_FILES['file'];
 			$filesize = $_FILES['file']['size'];
 			$filetype = $_FILES['file']['type'];
 			$filetemp = $_FILES['file']['tmp_name'];
 			$logo = $_FILES['file	']['name'];
 				
 			$user->createNewEvent($title, $desp, $cid, $tagid, $startdt, $enddt, $location, $org, $ticketname, $quantity, $price, $filetemp, $logo, $filesize, $filetype);
 			 			
  		}  
 		
 		
 		 		
 	?>
 		 	     	
 	<form method="post" action="create.php" enctype="multipart/form-data">
        <span class="ico-box">1</span>
        <span class="text-heading-primary">
            Event Details
        </span><hr>
        
 		<label>Event title <span class="star">* <?php if ($_POST['save']) {testField($_POST['name']); }?></span></label>
 		<input type="text" name="name" value="<?php if ($_POST['save']) {echo $_POST['name'];} ?>" placeholder="Give it a short distinct name" maxlength="255"  /><br /><br />
 		<label>Location <span class="star">* <?php if ($_POST['save']) {testField($_POST['location']); }?></span></label>
 		<select name="location" id="location">
 			<option value="">Specify where it's held.....</option>
 		 	<?php listVenue($_POST['location']); ?>
 		 </select><br /><br />
 		<div id="start">
 		<label>Starts</label><br />
 		<input type="date" name="startdate" value="<?php if ($_POST['save']) {echo $_POST['startdate'];} else{echo "2014-12-03";}?>"/>
 		<input type="time" name="starttime" value="<?php if ($_POST['save']) {echo $_POST['starttime'];} else{echo "12:00:00";}?>" /><br /><br />
 		</div>&nbsp;&nbsp; 
 		<div id="end">
 		<label>Ends</label><br />
 		<input type="date" name="enddate" value="<?php if ($_POST['save']) {echo $_POST['enddate'];} else{echo "2014-12-03";}?>" />
 		<input type="time" name="endtime" value="<?php if ($_POST['save']) {echo $_POST['endtime'];} else{echo "15:00:00";}?>" /><br /><br />
 		</div>
 		<label>Event descriptions</label><br />
 		<textarea cols="125" name="description" placeholder="Tell people what's special about this event" rows="10" ><?php if ($_POST['save']) {echo $_POST['description'];} ?></textarea><br /><br />
 		<label>Organizer name</label><br />
 		<select name="org" id="organizer">
 			<?php $user->listOrganizer($user->uid, $_POST['org']); ?>
 		</select><br /><br />
 		<label>Event logo</label><br />
 		<?php 
 				if ($_POST['save']) {
 					if (!empty($filetemp)) {
 						echo testFileSize($filesize) . "<br />";
 						echo testFileType($filetype) . "<br />";
 					}
 				}
 		 		 
 		
 		 ?>
 		
 		
 		 		 
 		<img src="images/default_logo.gif" alt="logo" title="logo"/>&nbsp;&nbsp;
 		<input type="file" name="file" value=""/>
 		<label style="color: red;">(size <100Kb) </label>
 		
 		<br /><br /><br />
 		
 		<span class="ico-box">2</span>
 		<span class="text-heading-primary">
 		    Create Tickets
 		</span><hr>
 		
 		<table class="table">
 			<tr>
 				<th>Ticket name <span class="star">* <?php if ($_POST['save']) {testField($_POST['tname']); }?></span></th>
 				<th>Quantity available <span class="star">* <?php if ($_POST['save']) {testField($_POST['quantity']); }?></span></th>
 				<th>Price <span class="star">* <?php if ($_POST['save']) {testField($_POST['price']); }?></span></th>
 			</tr>
 			<tr>
 				<td><input type="text" name="tname" value="<?php if ($_POST['save']) {echo $_POST['tname'];} ?>" placeholder="Early Bird...." /></td>
 				<td><input type="number" name="quantity" value="<?php if ($_POST['save']) {echo $_POST['quantity'];} ?>" min="1"  step="1"/></td>
 				<td><input type="number" name="price" value="<?php if ($_POST['save']) {echo $_POST['price'];} ?>" placeholder="0" min="0" step="any" /></td>
 			</tr>
 		
 		
 		
 		</table>
 		<br />
 		
 	
	 	<span class="ico-box">3</span>
	 	<span class="text-heading-primary">
	 	    Additional Settings
	 	</span><hr>
 		
		<label>Event Category</label><br />
		<select name="category" id="cat">
			<?php listCategory($_POST['category']); ?>
		</select><br /><br />
		
		<label>Event Topic</label><br />
		<select name="topic" id="topic">
			<?php listTopic($_POST['topic']); ?>
		</select><br /><br /><br /><br />
		<hr>
		<div id="last"><br /><br />
		<h3>Congratulations! You're done.</h3>
		<input type="submit" name="save" value="Save Your Event"  class="btn btn-lg btn-success" style="width: 50%;"/>
		</div>
 	
 	
 	</form>
 	<br /><br /><br /><br />
 
 <?php 
 	include_once("footer.php");
  ?>