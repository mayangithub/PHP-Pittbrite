<?php 
	require_once("functions.php");		
	
	if ($_POST['register']) {
		$lastname = $_POST['lastname'];
		$firstname = $_POST['firstname'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		
		register($firstname, $lastname, $email, $password);
	}
	
	
	
	include_once("header.php");
	
	


 ?>
 
 <div class="l-padded-v-4 l-padded-h-2 pod js-authentication-layout" id="registerbox">
     <div class="text--centered">
         <h3 class="text-heading-primary">Register</h3>
         <p class="l-block-1">Already have an account?
             <a class="js-switch-to-signup" href="login.php"> Log in.</a>
         </p>
     </div>
     <?php 
         	
     	
     	if (isset($lastname) && isset($firstname) && isset($email) && isset($password)) {
			if (empty(registerFill($firstname, $lastname, $email, $password))) {
				echo registerNewUser($firstname, $lastname, $email, $password);
			}else {
				echo registerFill($firstname, $lastname, $email, $password);
			}
		}
     ?>
     
     
     <form class="l-block-3 responsive-form" method="post" action="register.php">
         <div class="js-current-component">
         	<div class="l-block-3 form__row">
				<input value="<?php echo  $firstname;?>" name="firstname" type="text" placeholder="First Name" tabindex="1" >
			</div>
			<div class="js-error-for-email form__field-error is-hidden"><br /></div>
			<div class="l-block-3 form__row">
				<input value="<?php echo $lastname; ?>" name="lastname" type="text" placeholder="Last Name" tabindex="2" >
			</div>
			<div class="js-error-for-email form__field-error is-hidden"><br /></div>
             <div class="l-block-3 form__row">
     			<input value="<?php echo $email; ?>" name="email" type="email" placeholder="Email" tabindex="3" >
 			</div>
 			<div class="js-error-for-email form__field-error is-hidden"><br /></div>
 			<div class="l-block-3 form__row">
     			<input value="<?php echo $password; ?>" name="password" type="password" placeholder="Password" tabindex="4" >
 			</div>
 			<div class="js-error-for-email form__field-error is-hidden"><br /></div>
 			<div class="l-block-3 form__row">
     			<input type="submit" name="register" class="btn btn-lg btn-success" tabindex="3" value="Register" class="form-control">
 			</div>
         </div>
     </form>
 </div>
   
 
 <?php 
 	include_once("footer.php");
  ?>