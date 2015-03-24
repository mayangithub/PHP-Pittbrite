<?php session_start();
	
	require_once("functions.php");	
	
	redirectCreate();		
	
	$email = $_SESSION['email'];
	$user = new User;
	$user->__construct($email);
	
	if ($_POST['update']) {
		$newpwd = $_POST['pwd'];
	}
	
	include_once("header.php");
	
	
	
 ?>
	<p class="title">Welcome, <?php echo $user->firstname; ?></p><br />
	<span class="ico-box-green">1</span>
	<span class="text-heading-primary">
	    Account Details
	</span><hr>
	<?php 
		if ($_POST['update']) {
			$user->modifyPwd($user->uid, $newpwd);
		}
	 ?>
	<?php 
	echo   "<div class=\"panel panel-default\">";
	echo 	"<div class=\"panel-body\">";
	echo 	"<p><b>First name: </b>$user->firstname</p>";
	echo 	"<p><b>Last name: </b>$user->lastname</p>";
	echo 	"<p><b>Email: </b>$user->email</p>";
	echo 	"<form method=\"post\" action=\"profile.php\">";
	echo 	"<p><b>New password: </b><input id='pwd' type=\"password\" name=\"pwd\" value=\"$newpwd\" />&nbsp; &nbsp; <input id='update' type=\"submit\" name=\"update\" value=\"UPDATE\" /></p>";
	echo 	"</form>";
	echo 	"</div>";
	echo 	"</div>"; ?>
	
	<br />
	<span class="ico-box-green">2</span>
	<span class="text-heading-primary">
	    Registration History
	</span><hr>
	<?php 
	echo   "<div class=\"panel panel-default\">";
	echo 	"<div class=\"panel-body\">";
	echo   "<table class=\"table\">";
	echo 	"<tr><td>EVENT</td><td>TICKET</td><td>QUANTITY</td><td>REGISTER TIME</td></tr>";
	$user->listRegistration($user->uid);
	echo   "</table>";
	
	echo 	"</div>";
	echo 	"</div>"; ?>


<?php 
	include_once("footer.php");
 ?>