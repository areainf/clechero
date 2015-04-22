  <?php
    $count_cow = $schema->in_ordenio;
    $desinf_pre_o = Calculos::costo_sellador($schema->desinf_pre_o_precio, $schema->desinf_pre_o_dias);
    $desinf_pre_o1 = $desinf_pre_o / $count_cow;

    $desinf_pos_o = Calculos::costo_sellador($schema->desinf_post_o_precio, $schema->desinf_post_o_dias);
    $desinf_pos_o1  = $desinf_pos_o / $count_cow;

    $count_cow_mc = $schema->countCowMC();
    $costo_tratamiento_mc1 = $schema->costo_tratamiento_mc();
    $costo_tratamiento_mc = $costo_tratamiento_mc1 * $count_cow_mc;
    
    $costo_tratamiento_secado1 = $schema->costo_tratamiento_secado();    
    $costo_tratamiento_secado = $costo_tratamiento_secado1 * $count_cow_mc;    

    $costo_mantenimiento_maquina = $schema->costo_mantenimiento_maquina();
    $costo_mantenimiento_maquina1 = $costo_mantenimiento_maquina / $count_cow;

    $total_erogacion = $desinf_pre_o + $desinf_pos_o + $costo_tratamiento_mc + $costo_tratamiento_secado + $costo_mantenimiento_maquina;
    $total_erogacion1 = $desinf_pre_o1 + $desinf_pos_o1 + $costo_tratamiento_mc1 + $costo_tratamiento_secado1 + $costo_mantenimiento_maquina1;

    // $costo_total = $total_erogacion + $costo_total_perdida; // viene del archivo perdidas
    // $costo_total1 = $total_erogacion1 + $costo_total_perdida_vaca; // viene del archivo perdidas

    // $desinf_pre_o =  round(Calculos::costo_sellador($schema->desinf_pre_o_precio, $schema->desinf_pre_o_dias) * $count_cow , 2);
  ?>
<!--  
<ul class="list-group">
  <li class="list-group-item">
    <span class="badge">$
      <?php echo round(Calculos::costo_sellador($schema->desinf_pre_o_precio, $schema->desinf_pre_o_dias) * $count_cow , 2)?> </span>
    Desinfección Pre-ordeñe
  </li>
  <li class="list-group-item">
    <span class="badge">$
      <?php echo round(Calculos::costo_sellador($schema->desinf_post_o_precio, $schema->desinf_post_o_dias) * $count_cow , 2) ?> </span>
    Desinfección Post-ordeñe
  </li>
  <li class="list-group-item">
    <span class="badge">$
      <?php echo round($schema->costo_tratamiento_mc() , 2); ?> </span>
    Tratamiento MC
  </li>
  <li class="list-group-item">
    <span class="badge">$
      <?php echo round($schema->costo_tratamiento_secado() , 2); ?> </span>
    Tratamiento al Secado
  </li>
  <li class="list-group-item">
    <span class="badge">$
      <?php echo round($schema->costo_mantenimiento_maquina() , 2); ?> </span>
    Costo Mantenimiento Máquina
  </li>
  <li class="list-group-item">
    <span class="badge">$
      Sumar todos los campos
    Erogaciones por esquema de control
  </li>
  
</ul>-->
<!--<li class="list-group-item">
    <span class="badge"><?php echo $perdida_lts ?> Lts.</span>
    Total de Perdidas
  </li>
  <li class="list-group-item">
    <span class="badge"><?php echo $count_cow ?></span>
    Cantidad de Animales en el Ordeño
  </li>
  <li class="list-group-item">
    <span class="badge"><?php echo round($porcentaje, 2) ?>%</span>
    <span class="badge"><?php echo $count_cow_mc ?></span>
    Cantidad de Animales con MC
  </li>
  <li class="list-group-item">
    <span class="badge">$<?php echo $perdida_lts * $schema->milk_price ?></span>
    Costo Directo
  </li>-->

<div class="panel panel-default">
  <div class="panel-heading">Erogaciones por esquema de control</div>
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
        <th>Desinfección Pre-ordeñe</th>
        <td>
          <span class="badge">$
            <?php echo round($desinf_pre_o , 2)?>
          </span>
        </td>
        <td>
          <span class="badge">$
            <?php echo round($desinf_pre_o1 , 2)?>
          </span>
        </td>
      </tr>
      <tr>
        <th>Desinfección Post-ordeñe</th>
        <td>
          <span class="badge">$
            <?php echo round($desinf_pos_o , 2) ?>
          </span>
        </td>
        <td>
          <span class="badge">$
            <?php echo round($desinf_pos_o1 , 2) ?>
          </span>
        </td>
      </tr>
      <tr>
        <th>Tratamiento MC</th>
        <td>
          <span class="badge">$
            <?php echo round($costo_tratamiento_mc , 2); ?>
          </span>
        </td>
        <td>
          <span class="badge">$
            <?php echo round($costo_tratamiento_mc1 , 2); ?>
          </span>
        </td>
      </tr>
      <tr>
        <th>Tratamiento al Secado</th>
        <td>
          <span class="badge">$
            <?php echo round($costo_tratamiento_secado , 2); ?>
          </span>
        </td>
        <td>
          <span class="badge">$
            <?php echo round($costo_tratamiento_secado1 , 2); ?>
          </span>
        </td>
      </tr>
      <tr>
        <th>Costo Mantenimiento Máquina</th>
        <td>
          <span class="badge">$
            <?php echo round($costo_mantenimiento_maquina , 2); ?>
          </span>
        </td>
        <td>
          <span class="badge">$
            <?php echo round($costo_mantenimiento_maquina1 , 2); ?>
          </span>
        </td>
      </tr>
      <tr>
        <th>Erogaciones por esquema de control</th>
        <td><span class="badge">$ <?php echo round($total_erogacion, 2); ?></span></td>
        <td><span class="badge">$ <?php echo round($total_erogacion1, 2); ?></span></td>
      </tr>
      <!--
      <tr>
        <th>Costo total de la enfermedad</th>
        <td><span class="badge">$ <?php echo round($costo_total, 2); ?></span></td>
        <td><span class="badge">$ <?php echo round($costo_total1, 2); ?></span></td>
      </tr>
      -->
    </tbody>
  </table>

</div>
