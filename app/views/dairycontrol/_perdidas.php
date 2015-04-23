  <?php

    $count_cow = $schema->in_ordenio;
    $precio_leche = $schema->milk_price;
    $perdida_msc_v = $analisis->perdida_msc / $count_cow;
    $perdida_mc_v = $analisis->perdida_mc / $count_cow;
    $perdida_lts_v = $analisis->perdida_lts / $count_cow;
    $costo_total_perdida_vaca = $analisis->perdida_costo / $count_cow;
  ?>
<div class="panel panel-default">
  

  <!-- Table -->
  <table class="table table-bordered">
    <thead>
      <tr class="tr-report">
        <th>Análisis del Control Lechero</th>
        <th colspan="2">Para el tambo</th>
        <th colspan="2">Por vaca en ordeñe</th>
      </tr>
      <tr>
        <td></td>
        <th>Litros</th>
        <th>$</th>
        <th>Litros</th>
        <th>$</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Perdida por MSC</td>
        <td><span class="badge"><?php echo $analisis->perdida_msc ?></span></td>
        <td><span class="badge"><?php echo round($analisis->perdida_msc * $precio_leche, 2) ?></span></td>
        <td><span class="badge"><?php echo round($perdida_msc_v, 2) ?></span></td>
        <td><span class="badge"><?php echo round($perdida_msc_v * $precio_leche, 2) ?></span></td>
      </tr>
      <tr>
        <td>Perdida por MC</td>
        <td><span class="badge"><?php echo $analisis->perdida_mc ?></span></td>
        <td><span class="badge"><?php echo round($analisis->perdida_mc * $precio_leche, 2) ?></span></td>
        <td><span class="badge"><?php echo round($perdida_mc_v, 2) ?></span></td>
        <td><span class="badge"><?php echo round($perdida_mc_v * $precio_leche, 2) ?></span></td>
      </tr>
      <tr>
        <td>Total de Perdidas</td>
        <td><span class="badge"><?php echo $analisis->perdida_lts ?></span></td>
        <td></td>
        <td><span class="badge"><?php echo round($perdida_lts_v, 2) ?></span></td>
        <td></td>
      </tr>
      <tr>
        <td>Efecto biológico de la enfermedad</td>
        <td></td>
        <td><span class="badge"><?php echo $analisis->perdida_costo ?></span></td>
        <td></td>        
        <td><span class="badge"><?php echo round($costo_total_perdida_vaca, 2) ?></span></td>
      </tr>
    </tbody>
  </table>
</div>
