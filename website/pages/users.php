<script src="<?php echo BASE_URL; ?>js/site/users.page.js"></script>

<!-- Show users -->
<?php if ($_GET['action'] == 'show'): ?>
<div class="page-header">
	
	<h1>Gebruikers <small>overzicht</small><a href="<?php echo BASE_URL; ?>users/add/" class="btn btn-success pull-right"><i class="fa fa-plus"></i></a></h1>
		
</div>
	
<table class="table table-striped" id="usersTable">
    <thead>
	<tr>
            <th>#</th>
            <th>Gebruikersnaam</th>
            <th>E-mailadres</th>
            <th></th>
            <th></th>
	</tr>
    </thead>
    <tbody>

    </tbody>
</table>

<script>getUsers();</script>

<!-- Add user -->
<?php endif; if ($_GET['action'] == 'add'): ?>
<div class="page-header">
	
	<h1>Gebruiker <small>toevoegen</small></h1>
	
</div>

<form>

    <div class="form-group">

        <label class="control-label">Gebruikersnaam</label>
        <input class="form-control" type="text" id="name" />
        
    </div>
    
        <br>
        
    <div class="form-group">
        
        <label class="control-label">Email</label>
        <input class="form-control" type="email" id="email" />
        
    </div>
        
        <br>
        
    <div class="form-group">
        
        <label class="control-label">Wachtwoord</label>
        <input class="form-control" type="password" id="pass" />

    </div>

    <hr>

    <button class="btn btn-labeled btn-success" type="button" onclick="javascript: addUser();"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Opslaan</button> <a href="<?php echo BASE_URL; ?>questions/show/" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>

</form>

<!-- Edit user -->
<?php endif; if ($_GET['action'] == 'edit'): ?>
<div class="page-header">
	
	<h1>Gebruiker <small>aanpassen</small></h1>
	
</div>

<form>
	
	<div class="form-group">
                
        <label>Naam</label>
        <input class="form-control" type="text" id="name"/>

        <br>

        <label>Email</label>
        <input class="form-control" type="email" id="email"/>
        
         <br>

        <label>Oude wachtwoord</label>
        <input class="form-control" type="password" id="oldpass"/>

        <br>

        <label>Wachtwoord</label>
        <input class="form-control" type="password" id="pass"/>

        <hr>
		
	</div>
	
    <button class="btn btn-labeled btn-success updateUser" type="button" onclick="javascript: updateUser(<?php echo $_GET['id'] ?>);"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Aanpassen</button> <a href="<?php echo BASE_URL; ?>users/show/" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>

    <script>fillEditUserFormWithData( <?php echo $_GET['id'] ?> );</script>

<?php endif; ?>