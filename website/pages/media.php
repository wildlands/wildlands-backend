<?php
if ($_GET['action'] == 'show'):
?>
<!--Overzicht vragen en de tab voor het aanmaken van een nieuwe vraag-->
<div class="page-header">
	
	<h1>Media <small>overzicht</small><a href="<?php echo BASE_URL; ?>media/add/" class="btn btn-success pull-right"><i class="fa fa-plus"></i></a></h1>
		
</div>

<?php
endif;
if ($_GET['action'] == 'add'):
?>
<div class="page-header">
	
	<h1>Media <small>toevoegen</small></h1>
	
</div>
	
<form>
	
	<div class="form-group">
		
		<input type="file"  class="add-media" name="picture"/>
		
	</div>
	
        <button class="btn btn-labeled btn-success" onclick="javascript: addQuestion();"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Opslaan</button> <a href="<?php echo BASE_URL; ?>questions/show" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>
	
</form>
<?php
endif;
?>
