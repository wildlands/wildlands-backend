<script src="<?php echo BASE_URL; ?>js/site/levels.page.js"></script>

<!-- Show questions -->
<?php if ($_GET[ 'action']=='show' ): ?>
<div class="page-header">

    <h1>Niveaus <small>overzicht</small><a href="<?php echo BASE_URL; ?>levels/add/" class="btn btn-success pull-right"><i class="fa fa-plus"></i></a></h1>

</div>

<!-- Table with questions -->
<table class="table table-striped" id="levelsTable">

    <thead>
        <tr>
            <th>#</th>
            <th>Naam</th>
            <th></th>
        </tr>
    </thead>
	<tbody>

    </tbody>
</table>

<script>getLevels();</script>

<!-- Add question -->
<?php endif; if ($_GET[ 'action']=='add' ): ?>
<div class="page-header">

    <h1>Niveau <small>toevoegen</small></h1>

</div>

<form>

    <div class="form-group">
        <label class="control-label">Naam</label>
        <input class="form-control" type="text" name="name" id="name" />
    </div>

    <br>

    <button class="btn btn-labeled btn-success" type="button" onclick="javascript: addLevel();"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Opslaan</button> <a href="<?php echo BASE_URL; ?>levels/show/" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>

</form>

<!-- Edit question -->
<?php endif; if ($_GET[ 'action']=='edit' ): ?>
<div class="page-header">

    <h1>Niveau <small>aanpassen</small></h1>

</div>

<form>

    <div class="form-group">
        <label>Naam</label>
        <input class="form-control" type="text" id="name"/>
    </div>
    
    <br>

    <button class="btn btn-labeled btn-success" type="button" onclick="javascript: editLevel(<?php echo $_GET['id'] ?>);"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Opslaan</button> <a href="<?php echo BASE_URL; ?>levels/show/" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>

    <script>fillEditLevelFormWithData( <?php echo $_GET['id'] ?> );</script>
    
</form>

<?php endif; ?>

<script id="levelRowTemplate" type="text/template">
    <tr id="{{id}}" class="levelRow">
        <td>{{id}}</td>
        <td>{{name}}</td>
        <td>
            <a href="../edit/{{id}}" class="btn btn-warning col-md-offset-9"><i class="fa fa-pencil"></i></a>
            <a class='btn btn-danger pull-right deleteLevel' levelId="{{id}}" onclick="javascript: deleteLevel(this);"><i class="fa fa-times"></i></a>
        </td>
    </tr>
</script>