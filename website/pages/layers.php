<script src="<?php echo BASE_URL; ?>js/site/layers.page.js"></script>

<!-- Show questions -->
<?php if ($_GET[ 'action']=='show' ): ?>
<div class="page-header">

    <h1>Layers <small>overzicht</small></h1>

</div>

<!-- Table with questions -->
<table class="table table-striped" id="layersTable">

    <thead>
        <tr>
            <th>#</th>
            <th>Type</th>
            <th>Afbeelding</th>
            <th></th>
        </tr>
    </thead>
	<tbody>

    </tbody>
</table>

<script>getLayers();</script>

<!-- Edit question -->
<?php endif; if ($_GET[ 'action']=='edit' ): ?>
<div class="page-header">

    <h1>Layer <small>aanpassen</small></h1>

</div>

<form>

    <div class="form-group">
        <label class="control-label">Type</label>
        <input type="text" class="form-control" id="typeId" readonly/>

        <hr>
        
        <div class="form-group">
    
            <label class="control-label">Image</label>
            <div class="input-group">
                <input class="form-control page-image" type="text" id="image" readonly />
                <div class="input-group-addon">
                    <a data-toggle="modal" data-target="#fileManagerLayer">Kies afbeelding</a>
                </div>
            </div>

            <div class="modal fade" id="fileManagerLayer" tabindex="-1" role="dialog" aria-labelledby="fileManagerLayer_label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="fileManagerLayer_label">Media Gallery</h4>
                        </div>
                        <div>
                            <iframe width="580" height="500" src="<?php echo BASE_URL ?>filemanager/dialog.php?type=1&field_id=image&fldr=" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: hidden;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
    
    <br>

    <button class="btn btn-labeled btn-success" type="button" onclick="javascript: editLayer(<?php echo $_GET['id'] ?>);"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Opslaan</button> <a href="<?php echo BASE_URL; ?>layers/show/" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>

    <script>fillEditLayerFormWithData( <?php echo $_GET['id'] ?> );</script>
    
</form>

<?php endif; ?>

<script id="layerRowTemplate" type="text/template">
    <tr id="{{id}}" class="layerRow">
        <td>{{id}}</td>
        <td>>{{type}}</td>
        <td>>{{image}}</td>
        <td>
            <a href="../edit/{{id}}" class="btn btn-warning col-md-offset-9"><i class="fa fa-pencil"></i></a>
        </td>
    </tr>
</script>