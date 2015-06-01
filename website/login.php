<?php
require_once('functions.php');
// Als de gebruiker als ingelogd deze doorsturen naar dashboard
if (isLoggedIn())
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

	<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	<script src="<?php echo BASE_URL; ?>js/jquery.bootstrap.notify.min.js"></script>
	
	<script>
		
	$(document).ready(function() {
		
		$('#login').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
			
			$(this).removeClass('fadeInUp');
			$(this).css('animation-delay', '0s');
			
		});
		
		$('#login_button').click(function(event) {
			
			event.preventDefault();
			
			$.ajax({
				
				url : '<?php echo BASE_URL; ?>ajax/login.php',
				type : 'post',
				cache: false,
				data : $('.login-form').serialize()
				
			}).done(function(data) {
				console.log(data);
				if (data.code == 0)
				{
					$('#login').addClass('shake');
					createErrorMessage(data.message);
					$('#login').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
			
						$(this).removeClass('shake');
						
					});
				}
				else
				{
					window.location.href = 'index.php';
				}
				
			}).fail(function(data) {
                        console.log(data);
                        
                        });
			
		});
                $('#change_button').click(function(event) {
			
			event.preventDefault();
                        
                        var parameter = {
                            "email": $("#email").val()
                        };
			
			$.ajax({
				
				url : '<?php echo BASE_URL; ?>ajax/passEmail.php',
				type : 'post',
				cache: false,
				data : parameter
				
			}).done(function(data) {
				console.log(data);
				if (data.code == 0)
				{
					createErrorMessage(data.message);
				}
				else
				{
					createSuccessMessage("Email verstuurd met een link");
				}
				
			}).fail(function(data) {
                        console.log(data);
                        
                        });
			
		});
		
	});
		
	</script>
	
</head>

<body id="login-body">
	
	<div class="background-image animated fadeIn"></div>
	
	<div class="window animated fadeInUp" id="login">
		
		<p class="text-center"><img src="images/logo_wildlands.png" alt="Wildlands Emmen">
		
		<hr>
		
		<form role="form" class="login-form">
			
			<div class="form-group">
				
				<div class="input-group">
					
					<span class="input-group-addon"><i class="fa fa-user"></i></span>
					<input type="text" class="form-control" placeholder="E-mailadres" name="username">
					
				</div>
				
			</div>
			
			<div class="form-group">
				
				<div class="input-group">
					
					<span class="input-group-addon"><i class="fa fa-key"></i></span>
					<input type="password" class="form-control" placeholder="Wachtwoord" name="password">
					
				</div>
				
			</div>
			
			<div class="checkbox">
				
				<label>
					<input type="checkbox"> Onthoudt mij
				</label>
                            
			</div>
                    
                        <div>
                            
                            <button type="submit" class="btn btn-labeled btn-success" id="login_button"><span class="btn-label"><i class="fa fa-check"></i></span> Inloggen</button>
                            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#myModal"> Wachtwoord vergeten</button>
                            
                        </div>    
		</form>
                
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                      <!-- Modal content-->
                      <div class="modal-content animated">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Wachtwoord vergeten?</h4>
                        </div>
                        <div class="modal-body">
                            <form class="change-form">
                                <label>Email</label>
                                <input type="text" class="form-control" id="email">
                            </form>
                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-success" data-dismiss="modal" id="change_button">Verstuur</button>
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>

                    </div>
                </div>
	</div>
	
	<script src="<?php echo BASE_URL; ?>js/site/main.js"></script>
	
</body>

</html>