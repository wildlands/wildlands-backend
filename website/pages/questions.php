<?php
if ($_GET['action'] == 'show'):
?>
<!--Overzicht vragen en de tab voor het aanmaken van een nieuwe vraag-->
<div class="page-header">
	
	<h1>Vragen <small>overzicht</small><a href="<?php echo BASE_URL; ?>questions/add/" class="btn btn-success pull-right"><i class="fa fa-plus"></i></a></h1>
		
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
	
	<h1>Vragen <small>toevoegen</small></h1>
	
</div>
	
<form>
	
	<div class="form-group">
		
		<label>Link aan pinpoint</label>
		<select class="form-control" id="pinpointID">
			<option>Selecteer pinpointID</option>
		</select>
		
	</div>
        
        <script>loadPinpoints();</script>
	
	<div class="form-group">
		
		<label>Vraag</label>
		<input class="form-control" type="text" id="question"/>
		
	</div>
        
        <div class="form-group">
            <label> Voeg een plaatje toe</label>
            <input type="file"  id="image" name="picture"/>
        </div>
	
	<hr>
	
	<div class="form-group antwoorden">
		
		<div class="input-group">
			<input class="form-control antwoord" type="text" placeholder="Antwoord 1" name="answer[]" id="answer1"/>
			<div class="input-group-addon"><a href="javascript:void(0);" class="removeAnswer"><i class="fa fa-trash-o"></i></a></div>
		</div>
		<div class="input-group">
			<input class="form-control antwoord" type="text" placeholder="Antwoord 2" name="answer[]" id="answer2"/>
			<div class="input-group-addon"><a href="javascript:void(0);" class="removeAnswer"><i class="fa fa-trash-o"></i></a></div>
		</div>
		<div class="input-group">
			<input class="form-control antwoord" type="text" placeholder="Antwoord 3" name="answer[]" id="answer3"/>
			<div class="input-group-addon"><a href="javascript:void(0);" class="removeAnswer"><i class="fa fa-trash-o"></i></a></div>
		</div>
		<div class="input-group">
			<input class="form-control antwoord" type="text" placeholder="Antwoord 4" name="answer[]" id="answer4"/>
			<div class="input-group-addon"><a href="javascript:void(0);" class="removeAnswer"><i class="fa fa-trash-o"></i></a></div>
		</div>
		
	</div>
	
	<div class="form-group">
		
		<button class="btn btn-default addAnswer">Antwoord toevoegen</button>
		
	</div>
	
        <button class="btn btn-labeled btn-success" onclick="javascript: addQuestion();"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Opslaan</button> <a href="<?php echo BASE_URL; ?>questions/show" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>
	
</form>
<?php
endif;
if ($_GET['action'] == 'aanpassen'):
?>
<div class="page-header">
	
	<h1>Vragen <small>aanpassen</small></h1>
	
</div>
	
<form>
	
	<div class="form-group">
		
		<label>Link aan pinpoint</label>
		<select class="form-control" id="pinpointID">
			<option>Selecteer pinpointID</option>
		</select>
		
	</div>
        
        <script>loadPinpoints();</script>
	
	<div class="form-group">
		
		<label>Vraag</label>
		<input class="form-control" type="text" id="question"/>
		
	</div>
        
        <div class="form-group">
            <label> Voeg een plaatje toe</label>
            <input type="file"  id="image" name="picture"/>
        </div>
	
	<hr>
	
	<div class="form-group antwoorden">
		
		<div class="input-group">
			<input class="form-control antwoord" type="text" placeholder="Antwoord 1" name="answer[]" id="answer1"/>
			<div class="input-group-addon"><a href="javascript:void(0);" class="removeAnswer"><i class="fa fa-trash-o"></i></a></div>
		</div>
		<div class="input-group">
			<input class="form-control antwoord" type="text" placeholder="Antwoord 2" name="answer[]" id="answer2"/>
			<div class="input-group-addon"><a href="javascript:void(0);" class="removeAnswer"><i class="fa fa-trash-o"></i></a></div>
		</div>
		<div class="input-group">
			<input class="form-control antwoord" type="text" placeholder="Antwoord 3" name="answer[]" id="answer3"/>
			<div class="input-group-addon"><a href="javascript:void(0);" class="removeAnswer"><i class="fa fa-trash-o"></i></a></div>
		</div>
		<div class="input-group">
			<input class="form-control antwoord" type="text" placeholder="Antwoord 4" name="answer[]" id="answer4"/>
			<div class="input-group-addon"><a href="javascript:void(0);" class="removeAnswer"><i class="fa fa-trash-o"></i></a></div>
		</div>
		
	</div>
	
	<div class="form-group">
		
		<button class="btn btn-default addAnswer">Antwoord toevoegen</button>
		
	</div>
	
        <button class="btn btn-labeled btn-success" onclick="javascript: editQuestion();"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Opslaan</button> <a href="<?php echo BASE_URL; ?>questions/show" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>
	
</form>
<?php
endif;
?>
