<style>
/* the root element for scrollable */
#wizard {
	overflow:hidden;
	position:relative;
	height:500px;
}

/* scrollable items */
#wizard .items {
	width:20000em;
	clear:both;
	position:absolute;
}

/* single scrollable item called ".page" in this setup */
#wizard .page {
	padding:20px 30px;
	width:960px;
	float:left;
}

/* validation error message bar. positioned on the top edge */
#drawer {
	overflow:visible;
	position:fixed;
	left:0;
	top:0;
}
</style>

<!-- scrollable root element -->
<div id="wizard">


	<!-- scrollable items -->
	<div class="items">
		<!-- pages -->
		<div class="page">
			<form class="form-horizontal">
				<div class="control-group">
					<label class="control-label" for="rendszam">Rendszám</label>
					<div class="controls">
						<input type="text" id="main_rendszam" placeholder="Rendszám">
						<button type="submit" class="btn" id="tovabb_rendszam">Tovább</button>
					</div>
				</div>
				
			</form>


		</div>

		<div class="page" id="page_moso_tabla"></div>
	</div>

</div>

<!--************* -->
<!--MODAL ABLAKOK -->
<!--************* -->
<!-- Button to trigger modal -->

<!-- Modal -->
<div id="modal_ujauto" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Válassz ügyfelet</h3>
	</div>
	<div class="modal-body">
		<button class="btn btn-large" id="button_gyujto">Gyüjtő ügyfél</button>
		<?=form_dropdown('cegek',$cegek,NULL,'id="select_ugyfelek"')?>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Bezár</button>
		<button class="btn btn-primary">Tovább</button>
	</div>
</div>

<div id="modal_auto_adatok" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Autó adatai</h3>
	</div>
	<div class="modal-body">
		
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Bezár</button>
		<button class="btn btn-primary" id="auto_reg">Tovább</button>
	</div>
</div>


<script>
var scroll_api;
$(function(){
	var root = $("#wizard").scrollable();
	scroll_api=root.scrollable();


	$( "#main_rendszam" ).autocomplete({
		source: "goffice/rendeles_ajax/rendszam_lista",
		minLength: 2
	});

	$('#tovabb_rendszam').click(function(e){
		e.preventDefault();
		$.getJSON('goffice/rendeles_ajax/rendszam_ellenorzes/'+$('#main_rendszam').val(),function(data){
			if(data.statusz=="uj")
			{
				$('#modal_ujauto').modal();
			}
			else if(data.statusz=="regisztralt")
			{
				tovabb_moso_tabla(data.auto_id);
			}
			
		});
	});

	$('#main_rendszam').keyup(function(){
		$(this).val($(this).val().toUpperCase());
		$(this).val($(this).val().replace(/[^a-zA-Z 0-9]+/g,''));

	});

	$('#button_gyujto').click(function(e){
		e.preventDefault();
		auto_ugyfel(1);
	});

	$('#auto_reg').click(function(e){
		e.preventDefault();
		auto_reg();
	});


});
function auto_ugyfel(ugyfel_id)
{
	$('#modal_auto_adatok .modal-body').load('goffice/rendeles_ajax/auto_adatok/'+ugyfel_id,function(data){
		$('#modal_ujauto').modal('hide');
		$('#modal_auto_adatok').modal();
	});

}
function auto_reg()
{	
	$('#ugyfel').removeAttr('disabled');
	var auto_data=$('#modal_auto_adatok form').serialize();
	$.post('goffice/rendeles_ajax/auto_reg',auto_data,function(data){
		$('#auto_reg').button('reset');
		$('#modal_auto_adatok').modal('hide');
		tovabb_moso_tabla(data);
		
		
	},'json');	
}

function tovabb_moso_tabla(auto_id)
{
	$('#page_moso_tabla').load('goffice/rendeles_ajax/moso_tabla/'+auto_id,function(){
		scroll_api.next();
	});
}

</script>
