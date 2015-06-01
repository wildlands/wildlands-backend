<?php
require_once('functions.php');
if (!isHashRight())
{
	header('Location: ' . BASE_URL);
}
?>

<!DOCTYPE html>

<html lang="nl">

<head>
	
	<title>Wildlands Backend</title>
	
	<meta charset="utf-8">
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>sass/animate.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>sass/style.css">
        <link rel="shortcut icon" href="<?php echo BASE_URL; ?>images/favicon.ico">
        
        <script>var ajax_url = "<?php echo AJAX_URL; ?>"</script>
	<script>var base_url = "<?php echo BASE_URL; ?>"</script>

	<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	<script src="<?php echo BASE_URL; ?>js/jquery.bootstrap.notify.min.js"></script>
	
	<script>
		
        $.urlParam = function(name){
              var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
              if (results==null){
                 return null;
              }
              else{
                 return results[1] || 0;
              }
          };
                
	$(document).ready(function() {
		
		$('#login').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
			
			$(this).removeClass('fadeInUp');
			$(this).css('animation-delay', '0s');
			
		});
		
		$('#change_pass_button').click(function(event) {
			
			event.preventDefault();
			
                        var parameter = {
                            "email": $.urlParam('e'),
                            "password": $('#pass').val()
                        };
                        
                        api("SetPassword", parameter, function(data) {
                            // Redirect to login screen
                            redirectTo(base_url);
				
			});
			
		});
		
	});
		
	</script>
	
</head>

<body>
	
	<div class="background-image animated fadeIn"></div>
	
	<div class="window animated fadeInUp" id="login">
		
		<p class="text-center"><img src="images/logo_wildlands.png" alt="Wildlands Emmen">
		
		<hr>
		
		<form role="form" class="login-form">
			
                    <h1><small>Wachtwoord aanpassen voor: <?php echo $_GET['e']?></small></h1>
                    
                    <br>
                    
			<div class="form-group">
				
				<div class="input-group">
					
					<span class="input-group-addon"><i class="fa fa-key"></i></span>
					<input type="password" class="form-control" placeholder="Wachtwoord" name="password" id="pass">
					
				</div>
				
			</div>
			
			<div class="form-group">
				
				<div class="input-group">
					
					<span class="input-group-addon"><i class="fa fa-key"></i></span>
					<input type="password" class="form-control" placeholder="Herhaal wachtwoord" name="repeat_password">
					
				</div>
				
			</div>
                    
                        <div>
                            
                            <button type="submit" class="btn btn-labeled btn-success" id="change_pass_button"><span class="btn-label"><i class="fa fa-check"></i></span> Verander wachtwoord</button>
                            
		</form>
		
	</div>
	
	<script src="<?php echo BASE_URL; ?>js/site/main.js"></script>
	
</body>

</html>