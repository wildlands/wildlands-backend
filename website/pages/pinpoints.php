<?php
if ($_GET['action'] == 'show'):
?>

<!--Overzicht vragen en de tab voor het aanmaken van een nieuwe vraag-->
<div class="page-header">
	
	<h1>Pinpoints <small>overzicht</small><a href="<?php echo BASE_URL; ?>pinpoints/add/" class="btn btn-success pull-right"><i class="fa fa-plus"></i></a></h1>
		
</div>
	
<!--tabel met de bestaande pinpoints-->
<table class="table table-striped" id="pinpointsTable">
	
	<tr>
            
                <th>Naam</th>
		<th>#</th>
		<th>Omschrijving</th>
		<th></th>
		
	</tr>
	
</table>

<script>getPinpoints();</script>

<!--Pagina voor het aanmaken van een nieuwe pinpoint-->

<?php
endif;
if ($_GET['action'] == 'add'):
?>
<div class="page-header">
	
	<h1>Pinpoints <small>toevoegen</small></h1>
	
</div>

<form enctype="multipart/form-data">
	
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

<div class="page-header">
	
	<h1>Pagina's <small>toevoegen</small></h1>
	
</div>

    <div class='paginas'>   
        <div class="form-group pagina">
                
            <h1><small> Pagina 1</small></h1>
            
            <a href="javascript:void(0);" class="removePage"><i class="fa fa-trash-o"></i></a>
            
                <br>
            
                <label>Titel</label>
                <input class="form-control page-title" type="text"/>
                
                <br>
                
                <label>Afbeelding</label>
                <div class="input-group">
			<input class="form-control page-image" type="text" id="picture1"/>
			<div class="input-group-addon"><a href="javascript:void(0);" data-toggle="modal" data-target="#myModal1">Kies afbeelding</a></div>
		</div>

                <!-- Modal -->
                <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                      </div>
                      <div class="modal-body">
                        <iframe width="580" height="500" src="<?php echo BASE_URL; ?>filemanager/dialog.php?type=1&field_id=picture1&fldr=" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: hidden;">
                        </iframe>
                      </div>
                    </div>
                  </div>
                </div>
                
                <script>
                    
                    function responsive_filemanager_callback() {
                        
                        $('.modal').modal('hide');
                        
                    }
                    
                </script>
                
                
                <br>
                
                <label>Tekst</label>
                <textarea id="editor1" name="editor1"></textarea>
                <script type="text/javascript">
                        CKEDITOR.replace( 'editor1' );
                </script>
                
                <hr>
		
	</div>
    </div>
    
        <div class="form-group">
		
		<button class="btn btn-default addPage">Pagina toevoegen</button>
		
	</div>
    
        <button class="btn btn-labeled btn-success" id="pinpoint"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Opslaan</button> <a href="<?php echo BASE_URL; ?>pinpoints/show" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>
    </form>

<!--Pagina voor het aanpassen van een vraag-->
<?php
endif;
if ($_GET['action'] == 'aanpassen'):
    $id = $_GET['id'];
?>
        
        <div class="page-header">
	
	<h1>Pinpoints <small>aanpassen</small></h1>
	
</div>

<form>
	
	<div class="form-group">
                
                <label>Naam</label>
                <input class="form-control" type="text" id="name" value=""/>
                
                <br>
                
		<label>X-Positie pinpoint</label>
                <input class="form-control" type="text" id="xPos" value=""/>
                
                <br>
                
                <label>Y-Positie pinpoint</label>
                <input class="form-control" type="text" id="yPos" value=""/>
                
                <br>
                
                <label>Omschrijving</label>
                <input class="form-control" type="text" id="description" value=""/>
                
                <br>
                
                <label>Type</label>
		<select class="form-control" id="pinpointType">
			<option>Selecteer pinpoint type</option>
		</select>
                <script>loadPinpointType();</script>
                
                <hr>
		
	</div>
	
        <button class="btn btn-labeled btn-success" onclick="javascript: updatePinpoint(<?php echo $_GET['id'] ?>);"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Aanpassen</button> <a href="<?php echo BASE_URL; ?>pinpoints/show" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>
        
        <script>
            fillEditPinpointFormWithData( <?php echo $_GET['id'] ?> );
        </script>
    
<?php endif;
if ($_GET['action'] == 'verwijder'):
?>
        
        <div class="page-header">
	
	<h1>Pinpoints <small>verwijderen</small></h1>
	
</div>

<form>
	
	<div class="form-group">
		
                <label>PinpointID</label>
                <input class="form-control" type="text" id="pinID"/>
                
                <hr>
		
	</div>
	
        <a href="<?php echo BASE_URL; ?>pinpoints/show" onclick="javascript: deletePinpoint(<?php echo $_GET['id'] ?>);" id="pinID" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Verwijderen</a>
        
<?php endif; ?>
