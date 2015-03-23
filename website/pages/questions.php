<?php
// Vragen tonen
// URL: wildlands/questions/show/
if ($_GET['action'] == 'show'):
?>
<div class="page-header">
	
	<h1>Vragen <small>overzicht</small><a href="<?php echo BASE_URL; ?>questions/add" class="btn btn-success pull-right"><i class="fa fa-plus"></i></a></h1>
		
</div>

<table class="table table-striped" id="questionsTable">
	
	<tr>
		
		<th>#</th>
		<th>Vraag</th>
		
	</tr>
   
        
</table>

<script src="<?php echo BASE_URL; ?>js/questions.js"></script>

<?php
endif;
// Vraag toevoegen
// URL: wildlands/questions/add/
if ($_GET['action'] == 'add'):
?>
<div class="page-header">
	
	<h1>Vragen <small>toevoegen</small></h1>
	
</div>
	
<form>
	
	<div class="form-group">
		
		<label>Link aan pinpoint</label>
		<select class="form-control">
			<option>Selecteer pinpoint</option>
		</select>
		
	</div>
	
	<div class="form-group">
		
		<label>Vraag</label>
		<input class="form-control" type="text" />
		
	</div>
	
	<hr>
	
	<div class="form-group antwoorden">
		
		<div class="input-group">
			<input class="form-control antwoord" type="text" placeholder="Antwoord 1" name="answer[]" />
			<div class="input-group-addon"><a href="javascript:void(0);" class="removeAnswer"><i class="fa fa-trash-o"></i></a></div>
		</div>
		<div class="input-group">
			<input class="form-control antwoord" type="text" placeholder="Antwoord 2" name="answer[]" />
			<div class="input-group-addon"><a href="javascript:void(0);" class="removeAnswer"><i class="fa fa-trash-o"></i></a></div>
		</div>
		<div class="input-group">
			<input class="form-control antwoord" type="text" placeholder="Antwoord 3" name="answer[]" />
			<div class="input-group-addon"><a href="javascript:void(0);" class="removeAnswer"><i class="fa fa-trash-o"></i></a></div>
		</div>
		<div class="input-group">
			<input class="form-control antwoord" type="text" placeholder="Antwoord 4" name="answer[]" />
			<div class="input-group-addon"><a href="javascript:void(0);" class="removeAnswer"><i class="fa fa-trash-o"></i></a></div>
		</div>
		
	</div>
	
	<div class="form-group">
		
		<button class="btn btn-default addAnswer">Antwoord toevoegen</button>
		
	</div>
	
        <button class="btn btn-labeled btn-success"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Opslaan</button> <a href="<?php echo BASE_URL; ?>questions/show" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>
	
</form>
<?php
endif;
?>