<style>
.sor-al{
  padding-left: 25px !important;
}
.sor-kassza{
  text-transform: uppercase;
  font-size: 20px;
  font-weight: bold;

}
</style>
<table class="table table-hover">
  <caption>Készpénz elszámolás</caption>
  <thead>
    <tr>
      <th>Név</th>
      <th>Összeg</th>
    </tr>
  </thead>
  <tbody>
    <tr class="success">

      <td><strong>Bevétel összesen</strong></td>
      <td><strong><?=osszeg_formaz($kp->osszesen)?></strong></td>
      
    </tr>
    <tr>
      <td class="sor-al">Mosó</td>
      <td><?=osszeg_formaz($kp->moso)?></td>
    </tr>
    <tr>
      <td class="sor-al">Büfé</td>
      <td><?=osszeg_formaz($kp->bufe)?></td>
    </tr>
    <tr>
      <td class="sor-al">Bérlet</td>
      <td><?=osszeg_formaz($kp->berlet)?></td>
    </tr>
    <tr>
      <td class="sor-al">Borravaló</td>
      <td><?=osszeg_formaz($kp->borravalo)?></td>
    </tr>
    <tr class="info">

      <td><strong>Kiadás</strong></td>
      <td><strong><?=osszeg_formaz($kp->kiadas)?></strong></td>
      
    </tr>
    <tr class="error sor-kassza" >

      <td><strong>Kassza</strong></td>
      <td><strong><?=osszeg_formaz($kp->kassza)?></strong></td>
      
    </tr>
  </tbody>
</table>



<hr>
<table class="table table-hover">
  <caption>Mosások</caption>
  <thead>
    <tr>
      <th>Rendszám</th>
      <th>Méret</th>
      <th>Márka</th>
      <th>Szín</th>
      <th>Mosások</th>
      <th>Ár</th>
      <th>Fizetési mód</th>
      <th>Felvette</th>
      <th>Kezdés</th>
      <th>Lezárás</th>
    </tr>
  </thead>
  <tbody>
    <? foreach($mosasok as $mosas):?>
    <tr>
      <td><?=$mosas->rendszam?></td>
      <td><?=$mosas->meret_nev?></td>
      <td><?=$mosas->marka_nev?></td>
      <td><?=$mosas->szin?></td>
      <td>
        <? foreach($mosas->moso_tetelek as $moso_tetel):?>
          <? if($moso_tetel->moso_tetel == 3): ?>
            <strong><?=$moso_tetel->egyeb_nev?></strong> 
          <? else:?>
            <strong><?=$moso_tetel->nev?></strong> 
          <? endif;?>
          - <?=osszeg_formaz($moso_tetel->tetel_ar)?> Ft<br/>
        <?endforeach;?>
      </td>
      <td><?=$mosas->ar_moso?></td>
      <td><?=$mosas->fizetesi_mod?></td>
      <td><?=$mosas->felvette?></td>
      <td><?=$mosas->datum_kezdes?></td>
      <td><?=$mosas->datum_lezaras?></td>
    </tr>
    <?endforeach;?>
</tbody>
</table>
<hr>
<table class="table table-hover">
  <caption>Büfé rendelések</caption>
  <thead>
    <tr>
      <th>Azonosító</th>
      <th>Tételek</th>
      <th>Ár</th>
      <th>Fizetési mód</th>
      <th>Felvette</th>
      <th>Kezdés</th>
      <th>Lezárás</th>
    </tr>
  </thead>
  <tbody>
    <? foreach($bufek as $bufe):?>
    <tr>
      <td><?=$bufe->bufe_azon?></td>
      <td>
        <?foreach($bufe->bufe_tetelek as $bufe_tetel):?>
        <strong><?=$bufe_tetel->nev?></strong> - <?=$bufe_tetel->mennyiseg?>x<?=osszeg_formaz($bufe_tetel->tetel_ar)?> - <strong><?=osszeg_formaz($bufe_tetel->tetel_ar*$bufe_tetel->mennyiseg)?></strong> Ft<br>
        <?endforeach;?>
      </td>
      <td><?=$bufe->ar_bufe?></td>
      <td><?=$bufe->fizetesi_mod?></td>
      <td><?=$bufe->felvette?></td>
      <td><?=$bufe->datum_kezdes?></td>
      <td><?=$bufe->datum_lezaras?></td>
    </tr>
    <?endforeach;?>
</tbody>
</table>

<button class="btn btn-large btn-info" id="button_zaras">Zárás</button>



<!-- Modal zárás -->
<div id="modal_zaras" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Zárás 1</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Bezár</button>
    <button class="btn btn-primary" id="button_zaras_1">Tovább</button>
  </div>
</div>

<script type="text/javascript">
$(function(){
  
  $('#button_zaras').click(function(){
    $.get('goffice/zaras_ajax/zaras_1',function(data){
      if(data =="nincs_muszak")
      {
        $('#button_zaras').data('popover').options.content="Nincs aktív műszak, kérlek vedd fel őket!";
        $('#button_zaras').popover('show');
      }
      else
      {
        $('.modal-body').html(data);
        $('#modal_zaras').modal();

      }
    })
  }).popover({trigger:'manual',title:'Hiba'});
});
</script>