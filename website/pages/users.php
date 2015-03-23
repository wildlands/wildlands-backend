<?php
// Gebruikers tonen
// URL: wildlands/users/show/
if ($_GET['action'] == 'show'):

$query = $mysqli->query("SELECT UserID, Screenname, Email FROM user");

?>
<div class="page-header">
	
	<h1>Gebruikers <small>overzicht</small><a href="<?php echo BASE_URL; ?>questions/add" class="btn btn-success pull-right"><i class="fa fa-plus"></i></a></h1>
		
</div>
	
<table class="table table-striped">
	
	<tr>
		
		<th>#</th>
		<th>Gebruikersnaam</th>
		<th>E-mailadres</th>
		
	</tr>
	
	<?php
	while ($row = $query->fetch_assoc())
	{
		echo '<tr>
			<td>' . $row['UserID'] . '</td>
			<td>' . $row['Screenname'] . '</td>
			<td>' . $row['Email'] . '</td>
			</tr>';
	}	
	?>
	
</table>
<?php
endif;
if ($_GET['action'] == 'add'):
?>
<div class="page-header">
	
	<h1>Vragen <small>toevoegen</small></h1>
	
</div>

<?php
endif;
?>