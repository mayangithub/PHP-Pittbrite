<?php  session_start();
	echo $_SESSION['username'];
	if (!empty($_SESSION['username'])) {
		header("Location:profile.php");
	}
	require_once("functions.php");

	
	
	
			
	include_once("header.php");
	
 ?>

<div class="l-padded-v-4 l-padded-h-2 pod js-authentication-layout" id="loginbox">
    <div class="text--centered">
        <h3 class="text-heading-primary">Log in</h3>
        <p class="l-block-1">
            <a class="js-switch-to-signup" href="register.php">Or, register.</a>
        </p>
    </div>
    <?php 
        if ($_POST['login']) {
        	$email = $_POST['email'];
        	$pwd = $_POST['password'];		
    		if (empty(loginFill($email, $pwd))) {
    			 echo testCredentials($email, $pwd);
    		}else {
    			echo loginFill($email, $pwd);
    		}        	
        			
        }
	    	
    	
    	    	
    	
   	?>
    <form class="l-block-3 responsive-form" method="post" action="login.php">
        <div class="js-current-component">
            <div class="l-block-3 form__row">
    			<input value="<?php echo $email; ?>" name="email" type="email" placeholder="Email" tabindex="1" >
			</div>
			<div class="js-error-for-email form__field-error is-hidden"><br /></div>
			<div class="l-block-3 form__row">
    			<input value="<?php echo $pwd; ?>" name="password" type="password" placeholder="Password" tabindex="2" >
			</div>
			<div class="js-error-for-email form__field-error is-hidden"><br /></div>
			<div class="l-block-3 form__row">
    			<input type="submit" class="btn btn-lg btn-success" tabindex="3" value="Log in" class="form-control" name="login">
			</div>
        </div>
    </form>
</div>


<?php 
	include_once("footer.php");
 ?>