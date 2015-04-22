<?php 
  require_once HELPERS_PATH.'DateHelper.php';
?>
 <h2><small>Esquema de Control</small></h2>
 <table class="table table-bordered">
    <thead>      
      <tr>
        <th colspan="4">Datos de Control</th>
        <th colspan="2">Desinfección pezones</th>
        <th colspan="3">Tratamiento MC</th>
        <th>Trat. al Secado</th>
        <th>Chequeo Máquina</th>
        <th></th>
      </tr>
      <tr>
        <th>Tambo</th>
        <th>Fecha</th>
        <th>Litros Leche</th>
        <th>$/L</th>
        <th>Pre-Ordeño</th>
        <th>Post-Ordeño</th>
        <th>Pomo</th>
        <th>Antibiótico Inyect.</th>
        <th>Anti-inflamatorio</th>
        <th>Pomo</th>
        <th>Precio/Día</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <td><?php echo $schema->dairy()->fullname(); ?></td>
      <td><?php echo DateHelper::db_to_ar($schema->date); ?></td>
      <td><?php echo $schema->liters_milk; ?></td>
      <td><?php echo $schema->milk_price; ?></td>
      <?php 
        $d = $schema->desinf_pre_o_dias;
        $p = $schema->desinf_pre_o_precio;
      ?>
      <td><?php echo $schema->desinf_pre_o_producto . ' ' . (empty($p) || empty($d)) ? '' :  '$'.$p.'/'.$d.'dias' ;?></td>
      <?php 
        $d = $schema->desinf_post_o_dias;
        $p = $schema->desinf_post_o_precio;
      ?>
      <td><?php echo $schema->desinf_post_o_producto . ' ' . (empty($p) || empty($d)) ? '' :  '$'.$p.'/'.$d.'dias' ;?></td>
      <?php 
        $d = $schema->tmc_ab_pomo_cantidad;
        $p = $schema->tmc_ab_pomo_precio;
      ?>
      <td><?php echo (empty($p) || empty($d)) ? '' :  '$'.$p.'/'.$d; ?></td>
      <?php 
        $d = $schema->tmc_ab_inyect_cantidad;
        $p = $schema->tmc_ab_inyect_precio;
      ?>
      <td><?php echo (empty($p) || empty($d)) ? '' :  '$'.$p.'/'.$d; ?></td>
      <?php 
        $d = $schema->tmc_ai_inyect_cantidad;
        $p = $schema->tmc_ai_inyect_precio;
      ?>
      <td><?php echo (empty($p) || empty($d)) ? '' :  '$'.$p.'/'.$d; ?></td>
      <td><?php echo $schema->ts_pomo_precio; ?></td>
      <?php 
        $d = $schema->maquina_control_dias;
        $p = $schema->maquina_control_precio;
      ?>
      <td><?php echo (empty($p) || empty($d)) ? '' :  '$'.$p.'/'.$d; ?></td>
      <td>
        <?php 
          $img_edit = '<span class="glyphicon glyphicon-edit"></span>';
          $img_down = '<span class="glyphicon glyphicon-file"></span>';
          $img_analisis = '<span class="glyphicon glyphicon-stats"></span>';
          $url_edit = Ctrl::getUrl(array('control'=>'schema', 'action'=>'edit', 'params'=>array('id'=>$schema->id)));
          $url_down = Ctrl::getUrl(array('control'=>'schema', 'action'=>'downloadFile', 'params'=>array('id'=>$schema->id)));
          $url_analisis = Ctrl::getUrl(array('control'=>'dairycontrol', 'action'=>'analisis', 'params'=>array('schema_id'=>$schema->id)));
          $a_edit = FormHelper::link_to($url_edit,$img_edit);
          $a_analisis = FormHelper::link_to($url_analisis,$img_analisis);
          if($schema->hasFile())
            $a_down = FormHelper::link_to($url_down,$img_down);
          else
            $a_down = $img_down;
          $div = '<span class="dt-action">';
          echo $div.$a_edit.'</span>'.$div.$a_analisis.'</span>'.$div.$a_down.'</span>';
        ?>

      </td>
    </tbody>
  </table>
<hr>
<h2><small>Listado de Vacas</small></h2>

<div id="id-dairycontrol-list">
  <table id="table-dairycontrol" class="display" 
    data-source="<?php echo Ctrl::getUrl(array('control'=>'dairycontrol', 'action'=>'index_json', 'params'=>array('schema_id'=>$schema->id)));?>">
    <thead>      
      <tr>
        <th>ID</th>
        <th>Número</th>
        <th>NOP</th>
        <th>DL</th>
        <th>Fecha Parto</th>
        <th>RCS<br>(cél/mL x1000)</th>
        <th>MC</th>
        <th>Producción<br>(Litros)</th>
        <th>Coeficiente</th>
        <th>Pérdida Diaria</th>
        <!-- <th>Acciones</th> -->
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>
