<?php 
  require_once HELPERS_PATH.'DateHelper.php';
  $analisis = Array();
  $graph_dates = Array();
  $graph_mc = Array();
  $graph_msc = Array();
  $graph_erogaciones = Array();
?>
<h2>Análisis Evaluación de Costos</h2>
<h3><small>Tambo: </small><?php echo $dairy->name; ?></h3>

  <table class="table  table-bordered">
    <thead>
      <tr>
        <th>Controles Lecheros</th>
        <?php 
          foreach ($schemas as $schema) {
            echo "<th>".DateHelper::db_to_ar($schema->date)."</th>";
            $an = $schema->analisis();
            $analisis[] = $an;
            $graph_dates[] = DateHelper::db_to_ar($schema->date);
            $graph_mc[] = $an->perdida_mc;
            $graph_msc[] = $an->perdida_msc;
            $graph_erogaciones[] = $an->costo_total;
          }
        ?>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>MC</td>
        <?php 
          foreach ($analisis as $an) {
            echo '<td>'.round($an->perdida_mc, 2).'</td>';
          }
        ?>
      </tr>
      <tr>
        <td>MSC</td>
        <?php 
          foreach ($analisis as $an) {
            echo '<td>'.round($an->perdida_msc, 2).'</td>';
          }
        ?>
      </tr>
      <tr>
        <td>EROGACIONES</td>
        <?php 
          foreach ($analisis as $an) {
            echo '<td>'.round($an->costo_total, 2).'</td>';
          }
        ?>
      </tr>
    </tbody>
</table>
<div id="graph_container"></div>

<script type="text/javascript">
$(function () {
    var graph_dates = <?php echo json_encode($graph_dates); ?>;
    var graph_mc = <?php echo json_encode($graph_mc); ?>;
    var graph_msc = <?php echo json_encode($graph_msc); ?>;
    var graph_erogaciones = <?php echo json_encode($graph_erogaciones); ?>;
    graph_mc = graph_mc.map(function(item) {
          return parseFloat(item);
    });
    graph_msc = graph_msc.map(function(item) {
          return parseFloat(item);
    });
    graph_erogaciones = graph_erogaciones.map(function(item) {
          return parseFloat(item);
    });
    $('#graph_container').highcharts({
        title: {
            text: 'Análisis de Pérdidas',
            x: -20 //center
        },
        plotOptions: {
                series: {
                    threshold: 0
                },

            },

        xAxis: {
            categories: graph_dates
        },
        yAxis: {
            title: {
                text: 'Costos $'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }],
             labels: {
                format: '$ {value}'
            }
        },
        tooltip: {
            valuePrefix: '$ '
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 1
        },
        series: [{
            name: 'MC',
            data: graph_mc
        }, {
            name: 'MSC',
            data: graph_msc
        }, {
            name: 'Erogación',
            data: graph_erogaciones
        }
      ]
    });
});
</script>