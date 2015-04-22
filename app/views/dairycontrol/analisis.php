<?php 
  require_once HELPERS_PATH.'DateHelper.php';
  $url_report = Ctrl::getUrl(array('control'=>'dairycontrol', 'action'=>'analisis_report', 'params'=>array('schema_id'=>$schema->id)));
?>
<div>
<a href="<?php echo $url_report; ?>"><span class="btn btn-info">Descargar Reporte</span></a>  
</div>
<!-- INICIO DATOS GENERALES -->
  <h2><small>Datos Generales</small></h2>
<!--
  <div class="row">
    <div class="col-md-4">
      <fieldset>
        <legend>Tambo</legend>
        <div class="row">
          <div class="col-md-12">
            <p class="resaltado-1"><?php echo $schema->dairy()->name; ?></p>
          </div>
        </div>
      </fieldset>
    </div>
    <div class="col-md-5">
      <fieldset>
        <legend>Datos de Producción</legend>
        <div class="row">
          <div class="col-md-3  col-md-offset-1">
            <p>En Ordeño</p>
          </div>
          <div class="col-md-4">
            <p>Producción</p>
          </div>
          <div class="col-md-3 col-md-offset-1">
            <p>Precio X Lt.</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3  col-md-offset-1">
            <p class="resaltado-1"><?php echo $schema->in_ordenio; ?></p>
          </div>
          <div class="col-md-4">
            <p class="resaltado-1"><?php echo $schema->liters_milk; ?> Lts.</p>
          </div>
          <div class="col-md-3 col-md-offset-1">
            <p class="resaltado-1">$<?php echo $schema->milk_price; ?></p>
          </div>
        </div>
        <div class="row">
            <div class="col-md-12">
              <p class="help-block">Vacas en Ordeño, Litros diarios y precio del litro de Leche
              </p>
            </div>
        </div>
      </fielset>
    </div>
    <div class="col-md-2  col-md-offset-1">
      <fieldset>
        <legend>Período evaluado</legend>
        <p class="resaltado-1"><?php echo DateHelper::db_to_ar($schema->date); ?></p>
      </fieldset>
    </div>
  </div>-->
<!-- FIN DATOS GENERALES -->
<table class="table">
  <thead> 
    <tr>
      <th>Tambo</th>
      <th>Análisis / Ordeño</th>
      <th>Vacas Con MC</th>
      <th>Producción</th>
      <th>Precio x Lt.</th>
      <th>Período evaluado</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><p class="resaltado-1"><?php echo $schema->dairy()->name; ?></p></td>
      <td><?php echo $schema->countCow() . ' / ' . $schema->in_ordenio;?></td>
      <td><?php echo $schema->countCowMC();?></td>
      <td>
        <p class="resaltado-1"><?php echo $schema->liters_milk; ?> Lts.</p>
      </td>
      <td>
        <p class="resaltado-1">$<?php echo $schema->milk_price; ?></p>
      </td>
      <td>
        <p class="resaltado-1"><?php echo DateHelper::db_to_ar($schema->date); ?></p>
      </td>
    </tr>
  </tbody>
 </table>
<!--
 <table class="table table-bordered">
  <thead>      
    <tr>
      <th>Cantidad de animales análisis/ordeño</th>
      <th>Cantidad de animales con MC</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?php echo $schema->countCow() . ' / ' . $schema->in_ordenio;?></td>
      <td><?php echo $schema->countCowMC();?></td>
    </tr>
  </tbody>
</table>
-->
<!--
<div id="id-dairycontrol-analisis">   
 <h2><small>Esquema de Control</small></h2>
 <table class="table table-bordered">
    <thead>      
      <tr>
        <th colspan="4">Datos de Control</th>
        <th colspan="2">Desinfección pezones</th>
        <th colspan="3">Tratamiento MC</th>
        <th>Trat. al Secado</th>
        <th>Chequeo Máquina</th>
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
      <td>
        <?php 
          if (empty($p) || empty($d))
            $cad = '';
          else
            $cad = '$'.$p.'/'.$d.'dias';
        ?>
        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Producto: <?php echo $schema->desinf_pre_o_producto ?>"><?php echo $cad?></a>
      </td>
      <?php 
        $d = $schema->desinf_post_o_dias;
        $p = $schema->desinf_post_o_precio;
      ?>
      <td>
        <?php 
          if (empty($p) || empty($d))
            $cad = '';
          else
            $cad = '$'.$p.'/'.$d.'dias';
        ?>
        <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Producto: <?php echo $schema->desinf_post_o_producto ?>"><?php echo $cad?></a>
      </td>
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
      
    </tbody>
  </table>
-->
  <hr>

  <!-- <h2><small>Análisis del Control Lechero</small></h2> -->
  <div class="row">
    <div class="col-md-6">
      <?php include ('_perdidas.php'); ?>
    </div>
    <div class="col-md-6">
      <?php include ('_erogaciones.php'); ?>
    </div>
  </div>
</div> 
<?php
  $costo_total = $total_erogacion + $costo_total_perdida; // viene del archivo perdidas
  $costo_total1 = $total_erogacion1 + $costo_total_perdida_vaca; // viene del archivo perdidas

?>
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="alert alert-success" role="alert">
      <h3>Costo total de la enfermedad <small>(Efecto biológico + Erogaciones por esquema de control)</small></h3>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Por Tambo</th>
            <th>Por Vaca</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><span class="btn btn-default">$ <?php echo round($costo_total, 2); ?></td>
            <td><span class="btn btn-default">$ <?php echo round($costo_total1, 2); ?></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- LOS SIGUIENTES DATOS SE SACAN DE perdidas y erogaciones -->
<?php
  $graph_mc = $perdida_mc * $schema->milk_price;
  $graph_msc = $perdida_msc * $schema->milk_price;
  $graph_total = $graph_mc + $graph_msc + $total_erogacion;

  $graph_mc_porc = round($graph_total / $graph_mc, 2);
  $graph_msc_porc = round($graph_total / $graph_msc);
  $graph_erogacion_porc = round(100 - $graph_mc_porc - $graph_msc_porc, 2);
?>
<?php
/*
<div class="row">
  <div class="col-md-12">
    <p>Perdida por MC: <?php echo $graph_mc . '  -  '.$graph_mc_porc . ' %'; ?></p>
    <p>Perdida por MSC: <?php echo $graph_msc . '  -  '.$graph_msc_porc . ' %'; ?></p>
    <p>Erogaciones: <?php echo $total_erogacion . '  -  '.$graph_erogacion_porc . ' %'; ?></p>

    <p>Costo total: <?php echo $costo_total_perdida ?></p>
    <div id="graficaCircular" style="width: 100%; height: 500px; margin: 0 auto">
    </div>
    <script type="text/javascript">
      var dataset = [];
      dataset[0] = ["Perdida por MC", <?php echo $graph_mc_porc?>];
      dataset[1] = ["Perdida por MSC", <?php echo $graph_msc_porc ?>];
      dataset[2] = ["Erogaciones por esquema de control", <?php echo $graph_erogacion_porc ?>];
    </script>
  </div>
</div>
<script type="text/javascript">
  var chart;
  $(document).ready(function() {
    chart = new Highcharts.Chart({
      chart: {
        renderTo: 'graficaCircular'
      },
      title: {
        text: 'Importancia relativa de las perdidas'
      },
      subtitle: {
        text: 'Costo Mastitis(tambo/día)'
      },
      tooltip: {
        formatter: function() {
          return '<b>'+ this.point.name +'</b>: '+ this.y + ' %';
        }
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          dataLabels: {
            enabled: true,
            color: '#000000',
            connectorColor: '#000000',
            formatter: function() {
              return '<b>'+ this.point.name +'</b>: '+ this.y +' %';
            }
          }
        }
      },
        series: [{
        type: 'pie',
        name: 'Importancia Relativa Perdida',
        data: dataset
      }]
    });
  });     
</script>
*/
?>



<!-- LOS SIGUIENTES DATOS SE SACAN DE perdidas y erogaciones -->
<?php
  $graph_mc1 = ($perdida_mc/$count_cow) * $schema->milk_price;
  $graph_msc1 = ($perdida_msc/$count_cow) * $schema->milk_price;
  $graph_total1 = $graph_mc1 + $graph_msc1 + $total_erogacion1;

  $graph_mc_porc1 = round($graph_total1 / $graph_mc1, 2);
  $graph_msc_porc1 = round($graph_total1 / $graph_msc1);
  $graph_erogacion_porc1 = round(100 - $graph_mc_porc1 - $graph_msc_porc1, 2);
?>
<div class="row">
  <div class="col-md-12">
    <!--<p>Perdida por MC: <?php echo $graph_mc1 . '  -  '.$graph_mc_porc1 . ' %'; ?></p>
    <p>Perdida por MSC: <?php echo $graph_msc1 . '  -  '.$graph_msc_porc1 . ' %'; ?></p>
    <p>Erogaciones: <?php echo $total_erogacion1 . '  -  '.$graph_erogacion_porc1 . ' %'; ?></p>

    <p>Costo total: <?php echo $costo_total_perdida_vaca ?></p> -->
    <div id="graficaCircular1" style=" height: 500px; margin: 0 auto">
    </div>
  </div>
</div>
<div class="container">
<div class="row">
  <div class="col-md-12">
    <h3>Distribución de pérdidas por MSC</h3>
    <!-- Litros perdidos por msc, o sea mc=0 -->
    <?php 
      $order_data = $schema->getDataDistrPerdidaMSC();
      $count_data = count($order_data);
      $cant_interval = Calculos::histrogramCantInterval($count_data);
      $min = $order_data[0]->perdida;
      $max = $order_data[$count_data - 1]->perdida;
      $intervals = ($max - $min) / $cant_interval; 
      $interv_maxs = []; 
      $count_by_interval =[];//cantidad por intervalos
      $index_data = 0; // indice de busqueda
      $int_min = $min;
      for($i = 0; $i < $cant_interval; $i++){
        $int_max = $int_min +  $intervals;
        //busqueda
        $br = false;
        $count_by_interval[$i] = 0;
        while($index_data < $count_data && !$br){
          if($order_data[$index_data]->perdida <= $int_max){
            $count_by_interval[$i]++;
            $index_data++;
          }
          else
            $br = true;
        }
        echo "<p>".$int_min . "----" . $int_max."</p>";
        $int_min = $int_max;  
        $interv_maxs[] = $int_min;
      }
    ?>
    <div id="graficaHistogram"></div>
  </div>
</div>
</div>


<script type="text/javascript">
  var dataset = [];
      dataset[0] = ["Perdida por MC", <?php echo $graph_mc_porc1?>];
      dataset[1] = ["Perdida por MSC", <?php echo $graph_msc_porc1 ?>];
      dataset[2] = ["Erogaciones por esquema de control", <?php echo $graph_erogacion_porc1 ?>];
  var chart;
  $(document).ready(function() {
    var chart = new Highcharts.Chart({
      chart: {
        renderTo: 'graficaCircular1'
      },
      title: {
        text: 'Importancia relativa de las perdidas'
      },
      subtitle: {
        text: 'Costo Mastitis(vaca/día)'
      },
      tooltip: {
        formatter: function() {
          return '<b>'+ this.point.name +'</b>: '+ this.y + ' %';
        }
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          dataLabels: {
            enabled: true,
            color: '#000000',
            connectorColor: '#000000',
            formatter: function() {
              return '<b>'+ this.point.name +'</b>: '+ this.y +' %';
            }
          }
        }
      },
        series: [{
        type: 'pie',
        name: 'Importancia Relativa Perdida',
        data: dataset
      }]
    });
    var categories = <?php echo json_encode($interv_maxs) ?>;
    var hist_data = <?php echo json_encode($count_by_interval) ?>;
    /*HISTOGRAMA*/
    $(function () {
    $('#graficaHistogram').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Distribución de pérdidas por MSC'
        },
        subtitle: {
            text: 'Mastitis sub clinica'
        },
        xAxis: {
            categories:categories,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Frecuencia Relativa %'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">Cantidad</td>' +
                '<td style="padding:0"><b>{point.y} vacas</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            data: hist_data
            }]
    });
});
  });     
</script>
