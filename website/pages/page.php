<?php 
if ($_GET['action'] == 'aanpassen'):
    $id = $_GET['id'];
    
    $query = $mysqli->query("SELECT * FROM page WHERE PageID ='$id' limit 1;");
    $data = $query->fetch_assoc(); 
    
?>


        
        <div class="page-header">
	
	<h1>Pagina <small>aanpassen</small></h1>
	
</div>

<form>
	
	<div class="form-group">
                
                <label>Pinpoint id</label>
                <input class="form-control" type="text" id="pinId" value="<?php echo $data['PinID'];?>"/>
                
                <br>
            
                <label>Titel</label>
                <input class="form-control" type="text" id="title" value="<?php echo $data['Title'];?>"/>
                
                <br>
                
                <label>Image</label>
                <div class="input-group">
			<input class="form-control page-image" type="text" id="image" readonly value="<?php echo $data['Image'];?>"/>
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
                        <iframe width="580" height="500" src="<?php echo BASE_URL; ?>filemanager/dialog.php?type=1&field_id=image&fldr=" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: hidden;">
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
                <textarea id="editor" name="editor"><?php echo $data['Text'] ?></textarea>
                <script type="text/javascript">
                        CKEDITOR.replace( 'editor' );
                </script>
                <input type="hidden" id="pageId" value="<?php echo $_GET['id'] ?>">
                <input type="hidden" id="pinpointId" value="<?php echo $data['PinID'] ?>">
                
                <hr>
		
	</div>
	
        <button class="btn btn-labeled btn-success updatePage"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Aanpassen</button> <a href="<?php echo BASE_URL; ?>pinpoints/show/" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>
    
<?php 
    endif;
?>