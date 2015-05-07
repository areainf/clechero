  <?php
    $count_cow = $schema->in_ordenio;
    $desinf_pre_o = $analisis->costo_desinf_pre_o;
    $desinf_pre_o1 = $desinf_pre_o / $count_cow;

    $desinf_pos_o = $analisis->costo_desinf_pos_o;
    $desinf_pos_o1  = $desinf_pos_o / $count_cow;

    $count_cow_mc = $schema->countCowMC();

    $costo_tratamiento_mc = $analisis->costo_tratamiento_mc;
    $costo_tratamiento_mc1 = $count_cow_mc == 0 ? 0 : $costo_tratamiento_mc / $count_cow;//_mc;
    
    $costo_tratamiento_secado = $analisis->costo_tratamiento_secado;    
    $costo_tratamiento_secado1 = $count_cow_mc == 0 ? 0 : $costo_tratamiento_secado / $count_cow;//_mc;    

    $costo_mantenimiento_maquina = $analisis->costo_mantenimiento_maquina;
    $costo_mantenimiento_maquina1 = $costo_mantenimiento_maquina / $count_cow;

    $total_erogacion = $analisis->costo_total;
    $total_erogacion1 = $desinf_pre_o1 + $desinf_pos_o1 + $costo_tratamiento_mc1 + $costo_tratamiento_secado1 + $costo_mantenimiento_maquina1;

    // $costo_total = $total_erogacion + $costo_total_perdida; // viene del archivo perdidas
    // $costo_total1 = $total_erogacion1 + $costo_total_perdida_vaca; // viene del archivo perdidas

    // $desinf_pre_o =  round(Calculos::costo_sellador($schema->desinf_pre_o_precio, $schema->desinf_pre_o_dias) * $count_cow , 2);
  ?>

<div class="panel panel-default">
  <table class="table">
    <thead>
      <tr class="tr-report">
        <th>Erogaciones por esquema de control</th>
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
    </tbody>
  </table>

</div>
