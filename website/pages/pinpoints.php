<script src="<?php echo BASE_URL; ?>js/site/pinpoints.page.js"></script>

<!-- Show pinpoints -->
<?php if ($_GET['action'] == 'show'): ?>
<div class="page-header">

	<h1>Pinpoints <small>overzicht</small><a href="<?php echo BASE_URL; ?>pinpoints/add/" class="btn btn-success pull-right"><i class="fa fa-plus"></i></a></h1>

</div>
<!--tabel met de bestaande pinpoints-->
<table class="table table-striped table-hover" id="pinpointsTable">
	<tr>
            <th>#</th>
            <th>Naam</th>
            <th>Thema</th>
            <th>Omschrijving</th>
            <th></th>
            <th></th>
	</tr>

</table>

<script>getPinpoints();</script>

<!-- Add pinpoint -->
<?php endif; if ($_GET['action'] == 'add'): ?>

<div class="page-header">

	<h1>Pinpoints <small>toevoegen</small></h1>

</div>

<form enctype="multipart/form-data" name="pinForm">

    <div class="form-group">

        <label class="control-label">Naam</label>
        <input class="form-control" type="text" id="name" name="name"/>

    </div>
    
        <br>

    <div class="form-group">  
        
        <label class="control-label">Positie pinpoint</label>
        <div>
            <div>
                <img id="myImgId" alt="Plattegrond van Wildlands" src="<?php echo BASE_URL; ?>/images/kaart.jpg" width="833" height="797" data-scale="3" />
            </div>
            <div id="spot" style="position: absolute; display: none;">
                <img alt="spot" src="<?php echo BASE_URL; ?>/images/spot.png" width="25" height="25"/>
            </div>
        </div>
    
        <script>$('#myImgId').click(setCoordinates);</script>
        
        <br>
        
        <div class="input-group">
            
            <span class="input-group-addon pos control-label">X</span>
            <input class="form-control" type="text" id="xPos" name="xPos" readonly/>
            
        </div>
        
        <br>
        
        <div class="input-group">
            
            <span class="input-group-addon pos control-label">Y</span>
            <input class="form-control" type="text" id="yPos" name="yPos" readonly/>
            
        </div>
    </div>
        
        <br>
        
    <div class="form-group">
        <label class="control-label">Omschrijving</label>
        <input class="form-control" type="text" id="description"/>

        <br>
    </div>
        
    <div class="form-group">
        <label class="control-label">Type</label>
		<select class="form-control" id="pinpointType" name="type">
			<option value="">Selecteer pinpoint type</option>
		</select>

        <script>loadPinpointType();</script>

        <hr>

    </div>

    <div class="page-header">

	    <h1>Pagina's <small>toevoegen</small></h1>

    </div>
    
    <div role="tabpanel">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist" id='tablist'>
            <!-- Added automatically -->
        </ul>

        <script>loadPageLevel();</script>
        
        <!-- Tab panes -->
        <div class="tab-content">
            
        </div>

    </div>
    
    <button class="btn btn-labeled btn-success" type="button" onclick="javascript: addPinpoint();"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Opslaan</button> <a href="<?php echo BASE_URL; ?>pinpoints/show/" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>

</form>

<!--Pagina voor het aanpassen van een vraag-->
<?php endif; if ($_GET['action'] == 'edit'): ?>

<div class="page-header">

	<h1>Pinpoints <small>aanpassen</small></h1>

</div>

<form>

	<div class="form-group">

            <label>Naam</label>
            <input class="form-control" type="text" id="name"/>
        
        </div>
            
            <br>
            
        <div class="form-group">

            <label>Positie pinpoint</label>

            <div>
                <div>
                    <img id="myImgId" alt="Plattegrond van Wildlands" src="<?php echo BASE_URL; ?>/images/kaart.jpg" width="833" height="797" data-scale="3" />
                </div>
                <div id="spot" style="position: absolute; display: none;">
                    <img alt="spot" src="<?php echo BASE_URL; ?>/images/spot.png" width="25" height="25"/>
                </div>
            </div>

            <script>$('#myImgId').click(setCoordinates);</script>
            
            <br>

            <div class="input-group">
            
                <span class="input-group-addon pos control-label">X</span>
                <input class="form-control" type="text" id="xPos" name="xPos" readonly/>

            </div>

            <br>

            <div class="input-group">

                <span class="input-group-addon pos control-label">Y</span>
                <input class="form-control" type="text" id="yPos" name="yPos" readonly/>

            </div>

        </div>
            
            <br>

        <div class="form-group">
            
            <label>Omschrijving</label>
            <input class="form-control" type="text" id="description"/>

        </div>    
            
            <br>

        <div class="form-group">
            
            <label>Type</label>

            <select class="form-control" id="pinpointType">

            </select>

            <hr>

	</div>
        
        <div class="page-header">

	    <h1>Pagina's <small>aanpassen</small></h1>

        </div>

        <div role="tabpanel">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist" id='tablist'>
                <!-- Added automatically -->
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Added automatically -->
            </div>

        </div>
    
</form>

<button class="btn btn-labeled btn-success" type="button" onclick="javascript: editPinpoint(<?php echo $_GET['id'] ?>);"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Aanpassen</button> <a href="<?php echo BASE_URL; ?>pinpoints/show/" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>

<script>fillEditPinpointFormWithData(<?php echo $_GET['id'] ?>)</script>

<?php endif; ?>
