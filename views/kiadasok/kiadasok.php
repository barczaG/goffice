{{ streams:form stream="kiadasok" mode="new" exclude="" return="goffice/zaras" }}
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
$(function(){
	$('input[type="submit"]').addClass('btn btn-primary')
	.val('Mentés')
	.click(function(){
		//$('#szemelyzet').removeAttr('disabled');
	});
});

</script>
