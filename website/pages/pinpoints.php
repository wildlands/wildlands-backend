<?php
if ($_GET['action'] == 'show'):
?>

<!--Overzicht vragen en de tab voor het aanmaken van een nieuwe vraag-->
<div class="page-header">
	
	<h1>Pinpoints <small>overzicht</small><a href="<?php echo BASE_URL; ?>pinpoints/add" class="btn btn-success pull-right"><i class="fa fa-plus"></i></a></h1>
		
</div>
	
<!--tabel met de bestaande pinpoints-->
<table class="table table-striped" id="pinpointsTable">
	
	<tr>
            
                <th>Naam</th>
		<th>#</th>
		<th>X Positie</th>
		<th>Y Positie</th>
                <th>Aanpassen</th>
		
	</tr>
	
</table>

<script>getPinpoints();</script>

<!--Pagina voor het aanmaken van een nieuwe pinpoint-->

<!--Overzicht vragen en de tab voor het aanmaken van een nieuwe vraag-->
<div class="page-header">
	
	<h1>Vragen <small>overzicht</small><a href="<?php echo BASE_URL; ?>questions/add" class="btn btn-success pull-right"><i class="fa fa-plus"></i></a></h1>
		
</div>

<!--tabel met de bestaande vragen-->
<table class="table table-striped" id="questionsTable">
	
	<tr>
		
		<th>#</th>
		<th>Vraag</th>
		
	</tr>
</table>
<script>getQuestions();</script>

<!--Pagina voor het aanmaken van een nieuwe vraag-->
<?php
endif;
if ($_GET['action'] == 'add'):
?>
<div class="page-header">
	
	<h1>Pinpoints <small>toevoegen</small></h1>
	
</div>

<form>
	
	<div class="form-group">
		
                <label>Naam</label>
                <input class="form-control" type="text" id="name"/>
                
                <br>
                
		<label>X-Positie pinpoint</label>
                <input class="form-control" type="text" id="xPos"/>
                
                <br>
                
                <label>Y-Positie pinpoint</label>
                <input class="form-control" type="text" id="yPos"/>
                
                <br>
                
                <label>Omschrijving</label>
                <input class="form-control" type="text" id="description"/>
                
                <br>
                
                <label>Type</label>
		<select class="form-control" id="pinpointType">
			<option>Selecteer pinpoint type</option>
		</select>
                <script>loadPinpointType();</script>
                
                <hr>
		
	</div>
	
        <button class="btn btn-labeled btn-success" id="pinpoint"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Opslaan</button> <a href="<?php echo BASE_URL; ?>pinpoints/show" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>
	
<?php
endif;
?>