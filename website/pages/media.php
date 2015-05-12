<?php
if ($_GET['action'] == 'show'):
?>
<!--Overzicht vragen en de tab voor het aanmaken van een nieuwe vraag-->
<div class="page-header">
	
	<h1>Media <small>overzicht</small></h1>
        
    <div class="modal-body">
        <iframe width="100%" height="500" src="<?php echo BASE_URL; ?>filemanager/dialog.php?type=1&field_id=picture1&fldr=" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: hidden;"/>
    </div>
</div>

<?php
endif;
?>
