<div class="page-header">
	
	<h1>Dashboard <small>overzicht</small></h1>
	
</div>
	
<div class="row">
	
	<div class="col-md-3">
		
		<div class="chart" data-percent="35"><span class="percentage"></span></div>
		<p class="text-center">Bezoekers</p>
		
	</div>
	
	<div class="col-md-3">
		
		<div class="chart" data-percent="95"><span class="percentage"></span></div>
		
	</div>
	
	<div class="col-md-3">
		
		<div class="chart" data-percent="23"><span class="percentage"></span></div>
		
	</div>
	
	<div class="col-md-3">
		
		<div class="chart" data-percent="76"><span class="percentage"></span></div>
		
	</div>
	
</div>

<script>
$(document).ready(function() {
	
	$('.chart').easyPieChart({
		
		animate: 2000,
		scaleColor: false,
		barColor: '#007e2b',
		onStep: function(from, to, percent) {
			
			$(this.el).find('.percentage').text(Math.round(percent) + '%');
			
		}
		
	});
	
});
</script>