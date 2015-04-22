  <?php
    $perdida_msc = $schema->calculoPerdidaPorMSC();
    $perdida_mc = $schema->calculoPerdidaPorMC();
    $perdida_lts = $perdida_msc + $perdida_mc;
    $count_cow_a = $schema->countCow();
    $count_cow = $schema->in_ordenio;
    $count_cow_mc = $schema->countCowMC();
    $porcentaje = 0;
    if(!empty($count_cow))
      $porcentaje = ($count_cow_mc / $count_cow) * 100;
    $costo_total_perdida = $perdida_lts * $schema->milk_price;
    $costo_total_perdida_vaca = $costo_total_perdida / $count_cow;
  ?>
<!--
<ul class="list-group">
  <li class="list-group-item">
    <span class="badge"><?php echo $perdida_msc ?> Lts.</span>
    Perdida por MSC
  </li>
  <li class="list-group-item">
    <span class="badge"><?php echo $perdida_mc ?> Lts.</span>
    Perdida por MC
  </li>
  <li class="list-group-item">
    <span class="badge"><?php echo $perdida_lts ?> Lts.</span>
    Total de Perdidas
  </li>
  <li class="list-group-item">
    <span class="badge"><?php echo $count_cow_a.' / '.$count_cow ?></span>
    Cantidad de Animales Análisis/Ordeño
  </li>
  <li class="list-group-item">
    <span class="badge"><?php echo round($porcentaje, 2) ?>%</span>
    <span class="badge"><?php echo $count_cow_mc ?></span>
    Cantidad de Animales con MC
  </li>
  <li class="list-group-item">
    <span class="badge">$<?php echo $perdida_lts * $schema->milk_price ?></span>
    Efecto biológico de la enfermedad
  </li>
</ul>
-->
<div class="panel panel-default">
  <div class="panel-heading">Análisis del Control Lechero</div>

  <!-- Table -->
  <table class="table">
    <thead>
      <tr>
        <th></th>
        <th>Para el tambo</th>
        <th>Por vaca en ordeñe</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>Perdida por MSC</th>
        <td><span class="badge"><?php echo $perdida_msc ?> Lts.</span></td>
        <td><span class="badge"><?php echo round($perdida_msc / $count_cow, 2) ?> Lts.</span></td>
      </tr>
      <tr>
        <th>Perdida por MC</th>
        <td><span class="badge"><?php echo $perdida_mc ?> Lts.</span></td>
        <td><span class="badge"><?php echo round($perdida_mc / $count_cow) ?> Lts.</span></td>
      </tr>
      <tr>
        <th>Total de Perdidas</th>
        <td><span class="badge"><?php echo $perdida_lts ?> Lts.</span></td>
        <td><span class="badge"><?php echo round($perdida_lts / $count_cow, 2) ?> Lts.</span></td>
      </tr>
      <!--<tr>
        <th>Cantidad de Vacas Análisis/Ordeño</th>
        <td colspan="2"><span class="badge"><?php echo $count_cow_a.' / '.$count_cow ?></span></td>
      </tr>-->
      <!--
      <tr>
        <th>Cantidad de Vacas con MC</th>
        <td>
          <span class="badge"><?php echo $count_cow_mc ?> = <?php echo round($porcentaje, 2) ?>%</span>
        </td>
        <td>
          <span class="badge"><?php echo round(100 / $count_cow, 2) ?>%</span>
        </td>
      </tr> -->
      <tr>
        <th>Efecto biológico de la enfermedad</th>
        <td><span class="badge">$ <?php echo $costo_total_perdida ?></span></td>
        <td><span class="badge">$ <?php echo round($costo_total_perdida_vaca, 2) ?></span></td>
      </tr>
    </tbody>
  </table>
</div>
