<?php if ($_GET[ 'action']=='show' ): ?>
<!--Overzicht vragen en de tab voor het aanmaken van een nieuwe vraag-->
<div class="page-header">

    <h1>Vragen <small>overzicht</small><a href="<?php echo BASE_URL; ?>questions/add/" class="btn btn-success pull-right"><i class="fa fa-plus"></i></a></h1>

</div>

<!--tabel met de bestaande vragen-->
<table class="table table-striped" id="questionsTable">
	
	<tr>
		
		<th>#</th>
		<th>Vraag</th>
                <th></th>
		
	</tr>
</table>
<script>
    getQuestions();
</script>

<!--Pagina voor het aanmaken van een nieuwe vraag-->
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
        <input class="form-control page-image" type="text" id="image" readonly value=""/>
		<div class="input-group-addon"><a href="javascript:void(0);" data-toggle="modal" data-target="#myModal1">Kies afbeelding</a></div>
	</div>
    
    <br />

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

    <div class="form-group antwoorden">
        
        <script>
            $('.antwoorden').append(generateAnswerTextField(undefined, true, undefined));
            $('.antwoorden').append(generateAnswerTextField(undefined, false, undefined));
            $('.antwoorden').append(generateAnswerTextField(undefined, false, undefined));
            $('.antwoorden').append(generateAnswerTextField(undefined, false, undefined));
        </script>

    </div>

    <div class="form-group">

        <button class="btn btn-default addAnswer">Antwoord toevoegen</button>

    </div>

    <button class="btn btn-labeled btn-success" type="button" onclick="javascript: addQuestion();"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Opslaan</button> <a href="<?php echo BASE_URL; ?>questions/show" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>

</form>
<?php endif; if ($_GET[ 'action']=='aanpassen' ): ?>
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
        <input class="form-control page-image" type="text" id="image" readonly value=""/>
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
    
    <br />

    <div class="form-group antwoorden">

        <!-- Antwoorden worden dynamisch toegevoegd -->

    </div>

    <div class="form-group">

        <button class="btn btn-default addAnswer">Antwoord toevoegen</button>

    </div>

    <button class="btn btn-labeled btn-success" type="button" onclick="javascript: editQuestion(<?php echo $_GET['id'] ?>);"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Opslaan</button> <a href="<?php echo BASE_URL; ?>questions/show" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>

    <script>
        fillEditQuestionFormWithData( <?php echo $_GET['id'] ?> );
    </script>

</form>

<?php endif; ?>
