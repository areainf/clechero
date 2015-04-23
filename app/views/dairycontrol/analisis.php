<?php 
  require_once HELPERS_PATH.'DateHelper.php';
  $url_report = Ctrl::getUrl(array('control'=>'dairycontrol', 'action'=>'analisis_report', 'params'=>array('schema_id'=>$schema->id)));
  $analisis = $schema->analisis();
?>
<div>
  <span class="pull-right">
    <a href="<?php echo $url_report; ?>"><span class="btn btn-warning">Descargar Reporte</span></a>  
  </span>
</div>
<!-- INICIO DATOS GENERALES -->
  <h2><small>Datos Generales</small></h2>
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
  $costo_total = $total_erogacion + $analisis->perdida_costo;
  $costo_total1 = $total_erogacion1 + ($analisis->perdida_costo / $schema->in_ordenio);

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
  $graph_mc1 = empty($count_cow) ? 0 : ($analisis->perdida_mc/$count_cow) * $schema->milk_price;
  $graph_msc1 = empty($count_cow) ? 0 : ($analisis->perdida_msc/$count_cow) * $schema->milk_price;
  $graph_total1 = $graph_mc1 + $graph_msc1 + $total_erogacion1;

  $graph_mc_porc1 = $graph_mc1 == 0 ? 0 : round($graph_total1 / $graph_mc1, 2);
  $graph_msc_porc1 = empty($graph_msc1) ? 0 : round($graph_total1 / $graph_msc1);
  $graph_erogacion_porc1 = round(100 - $graph_mc_porc1 - $graph_msc_porc1, 2);
?>
<div class="row">
  <div class="col-md-12">
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
      $min = $order_data[0]->dml;
      $max = $order_data[$count_data - 1]->dml;
      $intervals = round(($max - $min) / $cant_interval, 2);
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
          if($order_data[$index_data]->dml <= $int_max){
            $count_by_interval[$i]++;
            $index_data++;
          }
          else
            $br = true;
        }
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
