{{ streams:form stream="autok" mode="new" exclude="auto_meret" }}
{{ form_open }}
<div class="control-group">
	<label class="control-label" for="">Méret</label>
	<div class="controls">
		<?=form_dropdown('auto_meret',$meretek,NULL,'id="meret"')?>
	</div>
</div>
{{ fields }}
<div class="control-group">
	<label class="control-label" for="">{{ input_title }}</label>
	<div class="controls">
		{{ input }}
	</div>
</div>
{{ /fields }}
{{ form_close }}

{{ /streams:form }}

<script>
$(function(){
	$('#ugyfel').val({{ugyfel}});
	$('select').select2();
	$('#ugyfel').select2('disable');
	$('#rendszam').attr('readonly','readonly');
	$('#rendszam').val($('#main_rendszam').val());


	$('#megjegyzes').attr('rows',4);
});
</script>