<script src="<?php echo BASE_URL; ?>js/site/pages.page.js"></script>

<?php if ($_GET['action'] == 'edit'): ?>

 <div class="page-header">
	
	<h1>Pagina <small>aanpassen</small></h1>
	
</div>

<form>
	
	<div class="form-group">
                
        <label>Pinpoint id</label>
        <input class="form-control" type="text" id="pinId"/>

        <br>

        <label>Titel</label>
        <input class="form-control" type="text" id="title"/>

        <br>

        <label>Image</label>
        <div class="input-group">
			<input class="form-control page-image" type="text" id="image" readonly/>
			<div class="input-group-addon"><a data-toggle="modal" data-target="#myModal1">Kies afbeelding</a></div>
		</div>

        <div class="fileManagerModal">
            <script>$(".fileManagerModal").append(createFileManagerModal(1));</script>
        </div>

        <br>

        <label>Tekst</label>
        <textarea id="editor" name="editor"></textarea>
        <script type="text/javascript">
                CKEDITOR.replace('editor');
        </script>

        <hr>
		
	</div>

</form>
	
<button class="btn btn-labeled btn-success" type="button" onclick="javascript: updatePage(<?php echo $_GET['id'] ?>);"><span class="btn-label"><i class="fa fa-floppy-o"></i></span> Aanpassen</button> <a href="<?php echo BASE_URL; ?>pinpoints/show/" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-times"></i></span> Annuleren</a>

<script>fillEditPageFormWithData(<?php echo $_GET['id'] ?>);</script>
<?php endif; ?>