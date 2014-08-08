{{ streams:form stream="muszakok" mode="edit" edit_id=muszak exclude="borravalo" return="goffice/szemelyzet" }}
{{ form_open }}

{{ fields }}
<div class="control-group group-{{ input_slug }}">
	<label class="control-label" for="">{{ input_title }}</label>
	<div class="controls">
		{{ input }}
	</div>
</div>
{{ /fields }}
<div class="control-group">
	<label class="control-label" for="">Lezárás időpontja</label>
	<div class="controls">
		<input type="text" name="datum_lezaras" value="<?=date('Y-m-d H:i:s')?>" id="datum_lezaras"  />
	</div>
</div>

{{ form_submit }}
{{ form_close }}
{{ /streams:form }}
<script>
$(function(){
	//$('#szemelyzet').val({{szemelyzet}});
	$('.group-datum_lezaras').remove();
	$('#datepicker_vegzett').val($('#datepicker_erkezett').val());
	$('#datepicker_vegzett,#datepicker_erkezett,#datepicker_erkeznie_kellett,#datum_lezaras').attr('readonly','readonly');
	
	console.log($('.group-erkezett').find('.select2-container'));
	$('select').select2();
	$('#szemelyzet').select2('disable');
	$('.group-erkezett,.group-erkeznie_kellett').find('.select2-container').hide();

	$('input[type="submit"]').addClass('btn btn-primary')
	.val('Mentés')
	.click(function(){
		$('#szemelyzet').removeAttr('disabled');
	});
});

</script>
