<h4>Ki veszi most a borravalót? </h4>
<ul class="unstyled">
	<form id="form_borravalo">
		
	<?foreach ($dolgozok as $key => $val):?>
	<li>
		<label class="checkbox inline">
			<input type="checkbox" id="inlineCheckbox1" name="kiveszik[]" value="<?=$key?>"> <?=$val?>
		</label>
	</li>
<? endforeach;?>
<div class="control-group">
		<label class="control-label" for="inputEmail">Külső borravaló</label>
		<div class="controls">
			<input type="text" id="inputEmail" name="kulso_borravalo" placeholder="Email">
		</div>
</div>
</form>
<hr>
<h3>Kártyás fizetések </h3>
Összesen: <?=$kartyas['ossz']?>, <?=count($kartyas['tetelek'])?>db
<ol class="">
	<?foreach ($kartyas['tetelek'] as $tetel):?>
	<li>
		<label class="checkbox inline">
			<input type="checkbox" id="inlineCheckbox1" name="" value="1"><?=osszeg_formaz($tetel->ar_osszesen)?>
		</label>
	</li>
<? endforeach;?>
</ol>
<br>
<div id="div_checkbox" style="padding-left:40px">
	<input type="checkbox" id="inlineCheckbox1" class="checkbox_kartya" name="" value="1"> Kártyás fizetés egyezik a pénztárgéppel<br>
	<input type="checkbox" id="inlineCheckbox1" class="checkbox_kartya" name="" value="1"> Kártyás fizetés egyezik a slippekkel
</div>

<script>
$(function(){
	$('#button_zaras_1').click(function(e){
		console.log($('.checkbox_kartya:checked').length);
		if($('.checkbox_kartya:checked').length != 2)
		{
			$('#div_checkbox').effect("shake", { times:3 }, 900);
			return false;
		}
		$('.modal-body').load('goffice/zaras_ajax/zaras_2',$('#form_borravalo').serialize(),function(){

		})
	});
});
</script>