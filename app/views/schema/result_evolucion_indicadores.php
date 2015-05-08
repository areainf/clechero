<?php
  require_once HELPERS_PATH.'DateHelper.php';
  $graph_dates = Array();
  $graph_prev = Array();
  $graph_inci = Array();
  $graph_prop = Array();
?>
<h2>Monitoreo de Indicadores de Enfermedad</h2>
<h3><small>Tambo: </small><?php echo $dairy->name; ?></h3>

  <table class="table  table-bordered">
    <thead>
      <tr>
        <th>Controles Lecheros</th>
        <?php 
          foreach ($matrix as $schema => $indic) {
            $date = DateHelper::db_to_ar($schema);
            echo "<th>".$date."</th>";
            $graph_dates[] = $date;
            $graph_prev[] = round($indic['prevalencia'], 2);
            $graph_inci[] = round($indic['incidencia'], 2);
            $graph_prop[] = round($indic['proporcion'], 2);
          }
        ?>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Prevalencia</td>
        <?php 
          foreach ($matrix as $indic) {
            echo '<td>'.round($indic['prevalencia'], 2).'</td>';
          }
        ?>
      </tr>
      <tr>
        <td>Incidencia</td>
        <?php 
          foreach ($matrix as $indic) {
            echo '<td>'.round($indic['incidencia'], 2).'</td>';
          }
        ?>
      </tr>
      <tr>
        <td>Proporción Crónicas</td>
        <?php 
          foreach ($matrix as $indic) {
            echo '<td>'.round($indic['proporcion'], 2).'</td>';
          }
        ?>
      </tr>
    </tbody>
</table>
<div id="graph_container"></div>

<script type="text/javascript">
$(function () {
    var graph_dates = <?php echo json_encode($graph_dates); ?>;
    var graph_prev = <?php echo json_encode($graph_prev); ?>;
    var graph_inci = <?php echo json_encode($graph_inci); ?>;
    var graph_prop = <?php echo json_encode($graph_prop); ?>;
    graph_prev = graph_prev.map(function(item) {
          return parseFloat(item);
    });
    graph_inci = graph_inci.map(function(item) {
          return parseFloat(item);
    });
    graph_prop = graph_prop.map(function(item) {
          return parseFloat(item);
    });
    $('#graph_container').highcharts({
        title: {
            text: 'Indicadores de Enfermedad',
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
                text: '%'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }],
             labels: {
                format: '{value} %'
            }
        },
        tooltip: {
            valueSuffix: ' % '
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 1
        },
        series: [{
            name: 'PREVALENCIA',
            data: graph_prev
        }, {
            name: 'INCIDENCIA',
            data: graph_inci
        }, {
            name: 'Prop. CRÓNICAS',
            data: graph_prop
        }
      ]
    });
});
</script>