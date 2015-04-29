<?php
// Gebruikers tonen
// URL: wildlands/users/show/
if ($_GET['action'] == 'show'):

$query = $mysqli->query("SELECT UserID, Screenname, Email FROM user");

?>
<div class="page-header">
	
	<h1>Gebruikers <small>overzicht</small><a href="<?php echo BASE_URL; ?>users/add/" class="btn btn-success pull-right"><i class="fa fa-plus"></i></a></h1>
		
</div>
	
<table class="table table-striped" id="usersTable">
	
	<tr>
		
		<th>#</th>
		<th>Gebruikersnaam</th>
		<th>E-mailadres</th>
                <th></th>
		
	</tr>
        
</table>

<script>getUsers();</script>

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
        <input class="form-control" type="text" id="name" />
        
        <br>
        
        <label>Email</label>
        <input class="form-control" type="email" id="email" />
        
        <br>
        
        <label>Wachtwoord</label>
        <input class="form-control" type="password" id="pass" />

    </div>

    <hr>

    <button class="btn btn-labeled btn-success" type="button" onclick="javascript: addUser();"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Opslaan</button> <a href="<?php echo BASE_URL; ?>questions/show" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>

</form>
<?php 
endif; 
if ($_GET['action'] == 'aanpassen'):
    $id = $_GET['id'];
    
    $query = $mysqli->query("SELECT * FROM user WHERE UserID ='$id' limit 1;");
    $data = $query->fetch_assoc(); 
    
?>


        
        <div class="page-header">
	
	<h1>Gebruiker <small>aanpassen</small></h1>
	
</div>

<form>
	
	<div class="form-group">
                
                <label>Naam</label>
                <input class="form-control" type="text" id="name" value="<?php echo $data['Screenname'];?>"/>
                
                <br>
                
		<label>Email</label>
                <input class="form-control" type="text" id="email" value="<?php echo $data['Email'];?>"/>
                
                <br>
                
                <label>Wachtwoord</label>
                <input class="form-control" type="password" id="pass"/>
                <input type="hidden" id="userId" value="<?php echo $_GET['id'] ?>">
                
                <hr>
		
	</div>
	
        <button class="btn btn-labeled btn-success updateUser"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Aanpassen</button> <a href="<?php echo BASE_URL; ?>users/show/" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>
    
<?php 
    endif;
?>
