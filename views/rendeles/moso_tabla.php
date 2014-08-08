<div class="row-fluid">
	<form id="form_moso_tabla">
		<?foreach($kategoriak as $kategoria):?>
		<div class="span3">
			<h3><?=$kategoria->nev?></h3>
			<?foreach($tetelek[$kategoria->id] as $tetel):?>
			<div class="tetel">
				<?if($tetel->mosas_kategoria == 1):?>
				<label class="radio">
					<input type="radio" name="akcio" id="optionsRadios1" value="<?=$tetel->komb_id?>" checked>
					<?=$tetel->nev?>
				</label>
				<?elseif($tetel->mosas_kategoria !== "akcio" AND $tetel->ar_tipus === "valt"):?>
					<?if($tetel->id == 3):?>
						<a id="egyeb" data-type="text"><?=$tetel->nev?></a>
						<input type="text" name="m[<?=$tetel->komb_id?>]" class="input-mini" id="inputEmail" placeholder="Ár">
					<?else:?>
					<?=$tetel->nev?>
						<input type="text" name="m[<?=$tetel->komb_id?>]" class="input-mini" id="inputEmail" placeholder="Ár">
					<?endif;?>
				<?else:?>
				<label class="checkbox inline">
					<input type="checkbox" id="inlineCheckbox1" name="m[<?=$tetel->komb_id?>]" value="1"> <?=$tetel->nev?>
				</label>
				<?endif;?>
			</div>
			<?endforeach;?>

		</div>
		<?endforeach;?>
		<input type="hidden" name="auto_id" value="{{auto_id}}" style="display:none"/>
	</form>
</div>
<button type="submit" class="btn btn-primary btn-large" id="button_felvesz">Felvesz</button>
<script>
$('#egyeb').editable();
$('#button_felvesz').click(function(e){
	e.preventDefault();
	$(this).button('loading');
	var form=$('#form_moso_tabla').serializeObject();
	form.egyeb_text=$('#egyeb').text();
	$.each(form,function(key,val){
		if(val=="")
		{
			delete form[key];
		}
	});
	console.log(form);
	$.post('goffice/rendeles_ajax/mosas_felvesz',form,function(data){
		top.location.href="goffice/aktiv";
	});
});

</script>