<script src="<?php echo BASE_URL; ?>js/site/start.page.js"></script>

<div class="page-header">
	
	<h1>Dashboard <small>overzicht</small></h1>
	
</div>
	
<div class="row">
	
	<div class="col-md-3">
		
		<div class="chart" data-percent="0" id="chartPinpoints"><span class="percentage"></span></div>
		<p class="text-center">Pinpoints</p>
		
	</div>

	<div class="col-md-3">

		<div class="chart" data-percent="0" id="chartPages"><span class="percentage"></span></div>
		<p class="text-center">Pagina's</p>

	</div>

	<div class="col-md-3">

		<div class="chart" data-percent="0" id="chartQuestions"><span class="percentage"></span></div>
		<p class="text-center">Vragen</p>

	</div>

	<div class="col-md-3">

		<div class="chart" data-percent="0" id="chartLevels"><span class="percentage"></span></div>
		<p class="text-center">Niveaus</p>

	</div>
	
</div>

<script>
	loadValues();
</script>