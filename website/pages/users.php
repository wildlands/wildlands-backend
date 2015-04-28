<?php
// Gebruikers tonen
// URL: wildlands/users/show/
if ($_GET['action'] == 'show'):

$query = $mysqli->query("SELECT UserID, Screenname, Email FROM user");

?>
<div class="page-header">
	
	<h1>Gebruikers <small>overzicht</small><a href="<?php echo BASE_URL; ?>users/add/" class="btn btn-success pull-right"><i class="fa fa-plus"></i></a></h1>
		
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
	
	<h1>Gebruiker <small>toevoegen</small></h1>
	
</div>

<form>

    <div class="form-group">

        <label>Gebruikersnaam</label>
        <input class="form-control" type="text" id="question" />
        
        <br>
        
        <label>Email</label>
        <input class="form-control" type="text" id="question" />
        
        <br>
        
        <label>Wachtwoord</label>
        <input class="form-control" type="password" id="question" />

    </div>

    <hr>

    <button class="btn btn-labeled btn-success" onclick="javascript: addQuestion();"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Opslaan</button> <a href="<?php echo BASE_URL; ?>questions/show" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>

</form>
<?php 
endif; ?>
