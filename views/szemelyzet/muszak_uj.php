{{ streams:form stream="muszakok" mode="new" exclude="kesett|vegzett|datum_lezaras|borravalo" return="goffice/szemelyzet" }}
{{ form_open }}

{{ fields }}
<div class="control-group">
	<label class="control-label" for="">{{ input_title }}</label>
	<div class="controls">
		{{ input }}
	</div>
</div>
{{ /fields }}
{{ form_submit }}
{{ form_close }}
{{ /streams:form }}
<script>
var ma='<?=date("Y-m-d")?>'
$(function(){
	$('#datepicker_erkezett,#datepicker_erkeznie_kellett').val(ma)
	.attr('readonly','readonly');
	$('#szemelyzet').val({{szemelyzet}});
	$('select').select2();
	$('#szemelyzet').select2('disable');
	$('input[type="submit"]').addClass('btn btn-primary')
	.val('Mentés')
	.click(function(){
		$('#szemelyzet').removeAttr('disabled');
	});
});

</script>
