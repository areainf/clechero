<h3>Tambo <small><?php echo $schema1->dairy()->name; ?></small></h3>
<!-- INICIO MOSTRAR ESQUEMAS DE CONTROL -->
<div class="row">
    <div class="col-md-6">
        <h3><small>Control Lechero 1</small></h3>
        <table class="table">
          <thead> 
            <tr>
              <th>Análisis / Ordeño</th>
              <th>Vacas Con MC</th>
              <th>Producción Lts.</th>
              <th>Período evaluado</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo $schema1->countCow() . ' / ' . $schema1->in_ordenio;?></td>
              <td><?php echo $schema1->countCowMC();?></td>
              <td>
                <p class="resaltado-1"><?php echo $schema1->liters_milk; ?></p>
              </td>
              <td>
                <p class="resaltado-1"><?php echo DateHelper::db_to_ar($schema1->date); ?></p>
              </td>
            </tr>
          </tbody>
         </table>
    </div>
    <div class="col-md-6">
        <h3><small>Control Lechero 2</small></h3>
        <table class="table">
          <thead> 
            <tr>
              <th>Análisis / Ordeño</th>
              <th>Vacas Con MC</th>
              <th>Producción Lts.</th>
              <th>Período evaluado</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo $schema2->countCow() . ' / ' . $schema2->in_ordenio;?></td>
              <td><?php echo $schema2->countCowMC();?></td>
              <td>
                <p class="resaltado-1"><?php echo $schema2->liters_milk; ?></p>
              </td>
              <td>
                <p class="resaltado-1"><?php echo DateHelper::db_to_ar($schema2->date); ?></p>
              </td>
            </tr>
          </tbody>
         </table>
    </div>
</div>  
<!-- FIN MOSTRAR ESQUEMAS DE CONTROL -->

<!-- INICIO MOSTRAR COMPARACIONES -->
<div class="row">
  <div class="col-md-6">
    <ul class="list-group">  
      <li class="list-group-item">
        <span class="badge"><?php echo $count_analizadas; ?></span>
        Cantidad de Vacas analizadas
      </li>
      <li class="list-group-item">
        <span class="badge"><?php echo $umbral; ?></span>
        Punto de Corte
      </li>
      <li class="list-group-item">
        <span class="badge"><?php echo $comparacion['sanas']; ?></span>
        Número de Vacas que siguen estando sanas
      </li>
      <li class="list-group-item">
        <span class="badge"><?php echo $comparacion['nuevas_inf']; ?></span>
        Número de Vacas con Nueva Infección
      </li>
      <li class="list-group-item">
        <span class="badge"><?php echo $comparacion['curadas']; ?></span>
        Número de Vacas que se han curado
      </li>
      <li class="list-group-item">
        <span class="badge"><?php echo $comparacion['cronicas']; ?></span>
        Número de Vacas que siguen continúan enfermas
      </li>
    </ul>
  </div>
    <div class="col-md-6">
      <ul class="list-group">  

        <li class="list-group-item">
          <span class="badge"><?php echo $comparacion['noanalizadas1']; ?></span>
          Número de vacas en el  Control 1 no en el 2 
        </li>
        <li class="list-group-item">
          <span class="badge"><?php echo $comparacion['noanalizadas2']; ?></span>
          Número de vacas en el  Control 2 y no en el 1 
        </li>
        <!--(Total de vacas crónicas + Total de nuevas infecciones) / Total de vacas analizadas -->
        <li class="list-group-item">
          <?php 
            $prevalencia = 0;
            if($count_analizadas != 0)
              $prevalencia = ($comparacion['cronicas'] + $comparacion['nuevas_inf']) / $count_analizadas;
          ?>
          <span class="badge"><?php echo round($prevalencia, 2); ?></span>
          Prevalencia de MSC
        </li>
        <!-- Total de nuevas infecciones / (Total de nuevas infecciones + Total de sanas) -->
        <li class="list-group-item">
          <?php 
            $incidencia = $comparacion['nuevas_inf'] + $comparacion['sanas'];
            if($incidencia > 0)
              $incidencia = $comparacion['nuevas_inf'] / $incidencia;
          ?>
          <span class="badge"><?php echo round($incidencia, 2); ?></span>
          Incidencia de MSC
        </li>
        <!-- Total de vacas crónicas / Total de vacas analizadas -->
        <li class="list-group-item">
          <?php 
            $proporcion = 0;
            if($count_analizadas != 0)
              $proporcion = $comparacion['cronicas'] / $count_analizadas;
          ?>
          <span class="badge"><?php echo round($proporcion, 2); ?></span>
          Proporción de Vacas Crónicas
        </li>
      </ul>
    </div>
  </div>
<!-- FIN MOSTRAR COMPARACIONES -->
<!-- INICIO MOSTRAR CONTROLES POR VACA -->
<hr>
<h2><small>Listado de Vacas</small></h2>
<div class="pull-right">
  <form class="form-horizontal" role="form" method="post"  action="downloadCronicas" name="form">
     <input type="hidden" name="schema_1_id" value="<?php echo $schema1->id;?>">
     <input type="hidden" name="schema_2_id" value="<?php echo $schema2->id;?>">
     <input type="hidden" name="umbral" value="<?php echo $umbral;?>">
     <input type="submit" class="btn btn-success" value="Descargar listado de Crónicas">
  </form>
</div>
<table id="table-dairycontrol" class="table table-bordered">
<thead>
  <tr>
    <th rowspan="2">Número</th>
    <th colspan="4">Control Lechero Nº 1</th>
    <th colspan="4">Control Lechero Nº 2</th>
    <th rowspan="2">Estado</th>
  </tr>
  <tr>
    <th>RCS<br>(cél/mL x1000)</th>
    <th>MC</th>
    <th>Producción<br>(Litros)</th>
    <th>Pérdida Diaria</th>
    <th>RCS<br>(cél/mL x1000)</th>
    <th>MC</th>
    <th>Producción<br>(Litros)</th>
    <th>Pérdida Diaria</th>    
  </tr>
</thead>
<tbody>
    <?php 
      foreach ($map as $array_dc) {
          $dc1 = $array_dc[0];
          $dc2 = $array_dc[1];
    ?>
    <tr>
      <td><?php echo $dc1->cow()->caravana ;?></td>
      <td><?php echo $dc1->rcs ;?></td>
      <td><?php echo $dc1->mc ;?></td>
      <td><?php echo $dc1->liters_milk ;?></td>
      <td><?php echo $dc1->dml ;?></td>
      <td><?php echo $dc2->rcs ;?></td>
      <td><?php echo $dc2->mc ;?></td>
      <td><?php echo $dc2->liters_milk ;?></td>
      <td><?php echo $dc2->dml ;?></td>
      <td>
        <?php
        if($dc1->rcs > $umbral){//si enferma 1 control
          if($dc2->rcs > $umbral)//si cronica
            echo '<span class="label label-danger">Crónica</span>';
          else
            echo '<span class="label label-success">Curada</span>';
        }
        else{
          if($dc2->rcs > $umbral)//si nueva inf
            echo '<span class="label label-warning">Infectada</span>';
          else
            echo '<span class="label label-info">Sana</span>';
        }
        ?>
      </td>
    </tr>
    <?php
      }
    ?>
    <?php 
      if ($comparacion['noanalizadas1']>0){
    ?>
    <tr>
        <th colspan="10">Vacas que NO estan en el Esquema Nº 2</th>
    </tr>
    <?php 
      foreach ($noanalizadas1 as $dc1) {
    ?>
    <tr>
      <td><?php echo $dc1->cow()->caravana ;?></td>
      <td><?php echo $dc1->rcs ;?></td>
      <td><?php echo $dc1->mc ;?></td>
      <td><?php echo $dc1->liters_milk ;?></td>
      <td><?php echo $dc1->dml ;?></td>
      <td>----</td>
      <td>----</td>
      <td>----</td>
      <td>----</td>
      <td>
        <?php
        if($dc1->rcs > $umbral)
            echo '<span class="label label-warning">Infectada</span>';
        else
            echo '<span class="label label-info">Sana</span>';
        ?>
      </td>
    </tr>
    <?php
          }//fin foreach noanalizadas1
      }//fin si no analizadas1 > 0
      if ($comparacion['noanalizadas2']>0){
    ?>
    <tr>
        <th colspan="10">Vacas que NO estan en el Esquema Nº 1</th>
    </tr>
    <?php 
      foreach ($noanalizadas2 as $dc2) {
    ?>
    <tr>
      <td><?php echo $dc2->cow()->caravana;?></td>
      <td>----</td>
      <td>----</td>
      <td>----</td>
      <td>----</td>
      <td><?php echo $dc2->rcs ;?></td>
      <td><?php echo $dc2->mc ;?></td>
      <td><?php echo $dc2->liters_milk ;?></td>
      <td><?php echo $dc2->dml ;?></td>
      <td>
        <?php
        if($dc2->rcs > $umbral)
            echo '<span class="label label-warning">Infectada</span>';
        else
            echo '<span class="label label-info">Sana</span>';
        ?>
      </td>
    </tr>
    <?php
          }//fin foreach noanalizadas1
      }//fin si no analizadas1 > 0
    ?>
</tbody>
</table>

<!-- FIN MOSTRAR CONTROLES POR VACA -->
