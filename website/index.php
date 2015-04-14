<?php
require_once('functions.php');
// Checken of gebruiker is ingelogd
if (!isLoggedIn())
{
	header('Location: ' . BASE_URL . 'login.php');
	exit;
}
// Als gebruiker wil uitloggen
if (isset($_GET['logout']) && $_GET['logout'])
{
	logout();
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

	<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	<script src="<?php echo BASE_URL; ?>js/jquery.bootstrap.notify.min.js"></script>
	<script src="<?php echo BASE_URL; ?>js/jquery.easypiechart.min.js"></script>
	<script src="<?php echo BASE_URL; ?>js/script.js"></script>
	
	<style>
	
	body {
		
		background-image: url('<?php echo BASE_URL; ?>images/background.jpg');
		background-size: cover;
		background-position: center center;
		
	}	
		
	</style>

</head>

<body>
	
	<div class="container" id="main">
		
		<div class="row" id="header">
			
			<div class="col-md-3 logo">
				
				<p><img src="<?php echo BASE_URL; ?>images/logo_wildlands.png" alt="Wildlands"></p>
				
			</div>
			
			<div class="col-md-9 title">
				
				<div class="row">
					
					<div class="col-md-4">
			
						<h3 class="header-title">Dashboard</h3>
						
					</div>
					
					<div class="col-md-8">
				
						<div class="user-menu pull-right">
						
							<ul class="list-inline">
								
								<li role="presentation" class="dropdown">
										
									<a class="dropdown-toggle menu-item" data-toggle="dropdown" aria-expanded="false" role="button">Ingelogd als <?php echo getScreenName(); ?> <b class="caret"></b></a>
									
									<ul class="dropdown-menu" role="menu">
										<li><a href="#"><i class="fa fa-user"></i> Profiel</a></li>
										<li><a href="<?php echo BASE_URL; ?>?logout=true"><i class="fa fa-sign-out"></i> Uitloggen</a></li>
									</ul>
							
								</li>
								
							</ul>
						
						</div>
						
					</div>
					
				</div>
				
			</div>
			
		</div>
		
		<div class="row">
			
			<div class="col-md-3 menu">
				
				<ul class="nav nav-pills nav-stacked">
					
					<li><a href="<?php echo BASE_URL; ?>"><i class="fa fa-tachometer"></i> Dashboard</a></li>
					<li><a href="<?php echo BASE_URL; ?>pinpoints/show"><i class="fa fa-map-marker"></i> Pinpoints en vragen</a></li>
					<li><a href="<?php echo BASE_URL; ?>users/show"><i class="fa fa-users"></i> Gebruikers</a></li>
					
				</ul>
				
			</div>
			
			<div class="col-md-9 content">
	
			<?php
			
				// Kijken of p= in de URL staat
				if (isset($_GET['p']))
				{
					// Checken of de pagina bestaat die wordt opgevraagd. 
					// Bijvoorbeeld: index.php?p=hoi vraag hoi.php op uit de map pages
					if (file_exists('pages/' . $_GET['p'] . '.php'))
					{
						// Content van de pagina inladen
						include('pages/' . $_GET['p'] . '.php');
					}
					// Pagina gestaat niet, dus 404
					else
					{
						include('404.php');
					}
				}
				// Geen p= in de URL dus open de startpagina
				else
				{
					include('pages/start.php');
				}
				
			?>
			
			</div>
			
		</div>
	
	</div>
	
	<div class="container" id="footer">
		
		<div class="row">
			
			<div class="col-md-12">
				
				<p class="text-center">&copy; INF2A Stenden Hogeschool</p>
				
			</div>
			
		</div>
		
	</div>
	
</body>

</html>