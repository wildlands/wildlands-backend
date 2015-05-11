<script src="<?php echo BASE_URL; ?>js/site/questions.page.js"></script>

<!-- Show questions -->
<?php if ($_GET[ 'action']=='show' ): ?>
<div class="page-header">

    <h1>Vragen <small>overzicht</small><a href="<?php echo BASE_URL; ?>questions/add/" class="btn btn-success pull-right"><i class="fa fa-plus"></i></a></h1>

</div>

<!-- Table with questions -->
<table class="table table-striped" id="questionsTable">
	
	<tr>
		<th>#</th>
		<th>Vraag</th>
        <th></th>
        <th></th>
	</tr>

</table>

<script>getQuestions();</script>

<!-- Add question -->
<?php endif; if ($_GET[ 'action']=='add' ): ?>
<div class="page-header">

    <h1>Vragen <small>toevoegen</small></h1>

</div>

<form>

    <div class="form-group">
        <label>Vraag</label>
        <input class="form-control" type="text" id="question" />
    </div>

    <label>Image</label>
    <div class="input-group">
        <input class="form-control page-image" type="text" id="image1" readonly value=""/>
		<div class="input-group-addon"><a data-toggle="modal" data-target="#myModal1">Kies afbeelding</a></div>
	</div>
    
    <br />

    <div class="fileManagerModal">
        <script>$(".fileManagerModal").append(createFileManagerModal(1));</script>
    </div>

    <div class="form-group antwoorden">
        <script>generateDefaultAnswerTextFields();</script>
    </div>

    <div class="form-group">
        <button class="btn btn-default addAnswer">Antwoord toevoegen</button>
    </div>

    <button class="btn btn-labeled btn-success" type="button" onclick="javascript: addQuestion();"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Opslaan</button> <a href="<?php echo BASE_URL; ?>questions/show" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>

</form>

<!-- Edit question -->
<?php endif; if ($_GET[ 'action']=='edit' ): ?>
<div class="page-header">

    <h1>Vragen <small>aanpassen</small></h1>

</div>

<form>

    <div class="form-group">
        <label>Vraag</label>
        <input class="form-control" type="text" id="question" />
    </div>

    <label>Image</label>
    <div class="input-group">
        <input class="form-control page-image" type="text" id="image1" readonly value=""/>
		<div class="input-group-addon"><a data-toggle="modal" data-target="#myModal1">Kies afbeelding</a></div>
	</div>

    <div class="fileManagerModal">
        <script>$(".fileManagerModal").append(createFileManagerModal(1));</script>
    </div>

    <br />

    <div class="form-group antwoorden">
        <!-- Answers are added automatically -->
    </div>

    <div class="form-group">
        <button class="btn btn-default addAnswerToForm" type="button" onclick="javascript: addAnswerFieldToForm();">Antwoord toevoegen</button>
    </div>

    <button class="btn btn-labeled btn-success" type="button" onclick="javascript: editQuestion(<?php echo $_GET['id'] ?>);"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Opslaan</button> <a href="<?php echo BASE_URL; ?>questions/show" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>

    <script>fillEditQuestionFormWithData( <?php echo $_GET['id'] ?> );</script>

</form>

<?php endif; ?>
