  <?php

    $count_cow = $schema->in_ordenio;
    $precio_leche = $schema->milk_price;
    $perdida_msc_v = $analisis->perdida_msc / $count_cow;
    $perdida_mc_v = $analisis->perdida_mc / $count_cow;
    $perdida_lts_v = $analisis->perdida_lts / $count_cow;
    $costo_total_perdida_vaca = $analisis->perdida_costo / $count_cow;
  ?>
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
        <td>
          <span class="badge"><?php echo $analisis->perdida_msc ?> Lts.</span>
          <span class="badge">$ <?php echo round($analisis->perdida_msc * $precio_leche, 2) ?></span>
        </td>
        <td>
          <span class="badge"><?php echo round($perdida_msc_v, 2) ?> Lts.</span>
          <span class="badge">$ <?php echo round($perdida_msc_v * $precio_leche, 2) ?></span>
        </td>
      </tr>
      <tr>
        <th>Perdida por MC</th>
        <td>
          <span class="badge"><?php echo $analisis->perdida_mc ?> Lts.</span>
          <span class="badge">$ <?php echo round($analisis->perdida_mc * $precio_leche, 2) ?></span>
        </td>
        <td>
          <span class="badge"><?php echo round($perdida_mc_v, 2) ?> Lts.</span>
          <span class="badge">$ <?php echo round($perdida_mc_v * $precio_leche, 2) ?></span>
        </td>
      </tr>
      <tr>
        <th>Total de Perdidas</th>
        <td>
          <span class="badge"><?php echo $analisis->perdida_lts ?> Lts.</span>
        </td>
        <td>
          <span class="badge"><?php echo round($perdida_lts_v, 2) ?> Lts.</span>
        </td>
      </tr>
      <tr>
        <th>Efecto biológico de la enfermedad</th>
        <td><span class="badge">$ <?php echo $analisis->perdida_costo ?></span></td>
        <td><span class="badge">$ <?php echo round($costo_total_perdida_vaca, 2) ?></span></td>
      </tr>
    </tbody>
  </table>
</div>
