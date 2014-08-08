<table class="table table-hover">
  <caption>Műszakok</caption>
  <thead>
    <tr>
      <th>Műszak kezdete</th>
      <th>Végzett</th>
      <th>Késés</th>
      <th>Levonás</th>
      <th>Óraszám</th>
      <th>Órabér</th>
      <th>Borravaló</th>
      <th>Fizetés</th>
      <th>Összesen</th>

    </tr>
  </thead>
  <tbody>
    <? foreach($muszakok as $muszak):?>
    <tr>
      <td><?=$muszak->erkeznie_kellett?></td>
      <td><?=$muszak->vegzett?></td>
      <td><?=$muszak->keses?>p</td>
      <td><?=$muszak->levonas?></td>
      <td><?=$muszak->oraszam?></td>
      <td><?=$muszak->oraber?></td>
      <td><?=$muszak->borravalo?></td>
      <td><?=$muszak->fizetes?></td>
      <td><?=$muszak->osszesen?></td>

    </tr>
  <? endforeach;?>
</tbody>
</table>


<table class="table table-hover">
  <caption>Büfé fogyasztások</caption>
  <thead>
    <tr>
      <th>Felvéve</th>
      <th>Tételek</th>
      <th>Összesen</th>

    </tr>
  </thead>
  <tbody>
    <? foreach($bufe_rendelesek as $rendeles):?>
    <tr>
      <td><?=$rendeles->datum_kezdes?></td>
      <td>
        <?foreach($rendeles->bufe_tetelek as $bufe_tetel):?>
        <strong><?=$bufe_tetel->nev?></strong> - <?=$bufe_tetel->mennyiseg?>x<?=osszeg_formaz($bufe_tetel->tetel_ar)?> - <strong><?=osszeg_formaz($bufe_tetel->tetel_ar*$bufe_tetel->mennyiseg)?></strong> Ft<br>
        <?endforeach;?>
      </td>
      <td><?=osszeg_formaz($rendeles->ar_bufe)?></td>

    </tr>
  <? endforeach;?>
</tbody>
</table>

<table class="table table-hover">
  <caption>Levonások</caption>
  <thead>
    <tr>
      <th>Dátum</th>
      <th>Típus</th>
      <th>Összeg</th>
      <th>Megjegyzés</th>

    </tr>
  </thead>
  <tbody>
    <? foreach($levonasok as $levonas):?>
    <tr>
      <td><?=$levonas->created?></td>
      <td><?=$levonas->kifizetes_tipus?></td>
      <td><?=osszeg_formaz($levonas->osszeg)?></td>
      <td><?=$levonas->megjegyzes?></td>

    </tr>
  <? endforeach;?>
</tbody>
</table>