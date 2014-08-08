<table class="table table-hover">
  <caption>Személyzet</caption>
  <thead>
    <tr>
      <th>Név</th>
      <th>Egyenleg</th>
      <th>Fizetés</th>
      <th>Büfé</th>
      <th>Előlegek levonások</th>
      <th>Műveletek</th>
    </tr>
  </thead>
  <tbody>
    <? foreach($szemelyzet as $szemely):?>
    <tr>
      <td><?=$szemely->nev?></td>
      <td><?=$szemely->egyenleg?></td>
      <td><?=$szemely->fizetes?></td>
      <td><?=$szemely->bufe_egyenleg?></td>
      <td><?=$szemely->levonasok?></td>
      <td>
        <?if(isset($szemely->muszak)):?>
        <a class="btn btn-primary btn-danger" href="goffice/szemelyzet/muszak_lezar/<?=$szemely->muszak?>">Műszak lezárása</a>
        <?else:?>
        <a class="btn btn-primary" href="goffice/szemelyzet/muszak_uj/<?=$szemely->id?>">Új Műszak</a>
        <?endif;?>
        <a class="btn btn-primary" href="goffice/szemelyzet/szemely_info/<?=$szemely->id?>">Infó</a>
        <a class="btn btn-primary" href="goffice/bufe_rendeles/szemelyzeti/<?=$szemely->id?>">Büfé rendelés</a>
        <a class="btn btn-primary" href="goffice/szemelyzet/kifizetes/<?=$szemely->id?>">Előleg - levonás</a>
      </td>

    </tr>
  <? endforeach;?>
</tbody>
</table>