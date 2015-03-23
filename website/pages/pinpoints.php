<?php
if ($_GET['action'] == 'show'):
?>
<div class="page-header">
	
	<h1>Pinpoints <small>overzicht</small><a href="<?php echo BASE_URL; ?>pinpoints/add" class="btn btn-success pull-right"><i class="fa fa-plus"></i></a></h1>
		
</div>
	
<table class="table table-striped">
	
	<tr>
		
		<th>#</th>
		<th>X Positie</th>
		<th>Y Positie</th>
		
	</tr>
	
</table>
<?php
endif;
if ($_GET['action'] == 'add'):
?>
<div class="page-header">
	
	<h1>Pinpoints <small>toevoegen</small></h1>
	
</div>

<?php
endif;
?>