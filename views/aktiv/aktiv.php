<style>
.rendeles{
}
</style>

<? foreach($rendelesek as $rendeles):?>
<div class="rendeles" data-id="<?=$rendeles->id?>" data-ar="<?=$rendeles->ar_osszesen?>">
	<div class="row-fluid">
		<div class="span6">
			<h2>
				<? if( isset($rendeles->moso_van) ):?>
				<?=$rendeles->auto->rendszam?>
			<? else:?>
			Mosás hozzáadása
			<?endif;?>
			- 
			<? if( isset($rendeles->bufe_van) ):?>
			<?=$rendeles->bufe_azon?>
		<? else:?>
		<a href="goffice/bufe_rendeles/<?=$rendeles->id?>" class="btn btn-success">Büfé hozzáadása</a>
		<?endif;?>
	</h2>
</div>

</div>
<div class="row-fluid">
	<? if(isset($rendeles->moso_van)):?>
	<div class="span2">
		<h3>Autó infó</h3>
		<div>Rendszám: <?=$rendeles->auto->rendszam?></div>
		<div>Méret: <?=$rendeles->auto->auto_meret_nev?></div>
		<div>Márka: <a class="marka xeditable" data-type="select" data-pk="<?=$rendeles->auto->auto_id?>" data-name="auto_marka" data-url="goffice/aktiv_ajax/auto_modosit"><?=$rendeles->auto->auto_marka_nev?></a></div>
		<div>Szin: <a class="xeditable" data-type="text" data-pk="<?=$rendeles->auto->auto_id?>" data-name="szin" data-url="goffice/aktiv_ajax/auto_modosit"><?=$rendeles->auto->szin?></a></div>
		<div>Megjegyzés:<br/> <a class="megjegyzes xeditable" data-placement="right" data-type="textarea" data-pk="<?=$rendeles->auto->auto_id?>" data-name="megjegyzes" data-url="goffice/aktiv_ajax/auto_modosit"><?=nl2br($rendeles->auto->megjegyzes)?></a></div>
	</div>
	<div class="span3">
		<h3>Mosás</h3>
		<? foreach($rendeles->moso_tetelek as $moso_tetel):?>
		<div>
			<? if($moso_tetel->moso_tetel == 3): ?>
			<strong><?=$moso_tetel->egyeb_nev?></strong> 
		<? else:?>
		<strong><?=$moso_tetel->nev?></strong> 
	<? endif;?>
	- <?=osszeg_formaz($moso_tetel->tetel_ar)?> Ft
</div>
<?endforeach;?>
<h4>Mosó összesen: <?=osszeg_formaz($rendeles->ar_moso)?> Ft</h4>
</div>
<?endif;?>
<? if(isset($rendeles->bufe_van)):?>
<div class="span4">
	<h3>Büfé</h3>
	<? foreach($rendeles->bufe_tetelek as $bufe_tetel):?>
	<div>
		<strong><?=$bufe_tetel->nev?></strong> - <?=$bufe_tetel->mennyiseg?>x<?=osszeg_formaz($bufe_tetel->tetel_ar)?> - <strong><?=osszeg_formaz($bufe_tetel->tetel_ar*$bufe_tetel->mennyiseg)?></strong> Ft
	</div>
	<?endforeach;?>
	<h4>Büfé összesen: <?=osszeg_formaz($rendeles->ar_bufe)?> Ft</h4>
</div>
<?endif;?>
</div>
<div class="row-fluid">
	<div class="span6">
		<h2>Végösszeg: <?=osszeg_formaz($rendeles->ar_osszesen)?> Ft</h2>
		<input type="text" class="fizetve" id="inputEmail" placeholder="Összeg"/>
		<br/>
		<div class="btn-group">

			<button class="btn btn-large button_fizet" data-tipus="kp">Kp</button>
			<button class="btn btn-large button_fizet" data-tipus="kartya">Bankkártya</button>
			<button class="btn btn-large button_berlet" data-tipus="berlet">Bérlet</button>
		</div>
	</div>
</div>
</div>
<hr/>
<? endforeach;?>
<button class="btn btn-large btn-success" id="valt">Váltogat</button>
<script>
$(function(){
	$('.xeditable').not('.marka').editable({});
	$('.marka').editable({source:<?=json_encode($cegobj)?>});


	$('#valt').click(function(e){
		$('.rendeles .row-fluid:gt(0)').toggle('normal');
	});

	$('.button_fizet').click(function(e){
		var rendeles_div=$(this).parents('.rendeles');
		var rendeles_id=rendeles_div.data('id');
		var fizetesi_mod=$(this).data('tipus');
		var ar=rendeles_div.data('ar');
		var osszeg=rendeles_div.find('.fizetve').val();
		$.getJSON('goffice/aktiv_ajax/fizet/'+rendeles_id+'/'+fizetesi_mod+'/'+osszeg,function(data){
			if(data.status=='ok')
			{
				rendeles_div.hide('normal');
			}
		});
	})

});
</script>

