<?php
  require_once HELPERS_PATH.'DateHelper.php';
  $cows_mc = $schema->cowMC();
  $count_mc = $schema->countCowMC();
  $in_ordenio = $schema->in_ordenio;
  $porc = round(($count_mc*100)/$in_ordenio ,2);
?>
        
<!-- INICIO MODAL MC -->
<div id="id-modal-mc" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Listado de Vacas con MC</h4>
        <p>El <?php echo $porc ?>% tiene MC</p>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Indice</th>
              <th>Número</th>
              <th>DEL</th>
              <th>Fecha Lactancia</th>
              <th>Producción(Litros)</th>        
            </tr>
          </thead>
          <tbody>
            <?php 
              $i=0;
              foreach ($cows_mc as $dc) {
                  $i++;
                  $cow = $dc->cow()
            ?>
            <tr>
              <td><?php echo $i ;?></td>
              <td><?php echo $cow->caravana ;?></td>
              <td><?php echo $dc->dl ;?></td>
              <td><?php echo DateHelper::db_to_ar($dc->date_dl) ;?></td>
              <td><?php echo $dc->liters_milk;?></td>
            </tr>
            <?php 
              }// fin foreach mc
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- FIN MODAL MC -->