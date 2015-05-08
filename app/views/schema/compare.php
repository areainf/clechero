<?php
if(empty($dairies))
  echo "<h2>Primero debe crear el tambo y los controles lecheros</h3>";
else{
?>
<div class ="row">
    <div class="col-md-6">
        <h2>Evolución Vaca por Vaca</h2>
<?php
  foreach ($dairies as $tambo) {
?>
  <div class="panel panel-success">
    <div class="panel-heading"><?php echo $tambo->name; ?></div>
    <form action="compare" method="post" class="form-inline">
    <div class="panel-body">
    <?php
        $schemas = $tambo->schemasOrder("date asc");
        if(!$schemas or count($schemas)<2){
          echo "<p>No hay esquemas de control para comparar.</p>";
        }
        else{
    ?>
        <table class="table table-borderer">
            <thead>
                <tr>
                    <th>Selección</th>
                    <th>Control Lechero 1</th>
                    <th>Control Lechero 2</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cant = count($schemas);
                for($i = 0; $i < $cant -1; $i++){
                    $schema1= $schemas[$i];
                    $schema2= $schemas[$i+1];
                    $chk_id="chkb_schema_".$schema1->id+"_"+$schema2->id;
                ?>
                <tr>
                    <td>
                        <label for="<?php echo $chk_id; ?>">
                            <input type="radio" name="schemas_ids" value="<?php echo $schema1->id . ',' . $schema2->id; ?>" id="<?php echo $chk_id; ?>">
                        </label>
                    </td>
                    <td>
                        <label for="<?php echo $chk_id; ?>">
                            <?php echo DateHelper::db_to_ar($schema1->date); ?>
                        </label>
                    </td>
                    <td>
                        <label for="<?php echo $chk_id; ?>">
                            <?php echo DateHelper::db_to_ar($schema2->date); ?>
                        </label>
                    </td>
                </tr>
                <?php } //fin for ?>
            </tbody>
        </table>
    <?php
        }
    ?>
    </div><!-- fin panel body -->
    <div class="panel-footer">
        <div class="form-group">
            <label for="umbral">Punto de Corte</label>
            <input type="number"class="form-control" name="umbral" value="<?php echo $umbral; ?>">
            <input type="submit" class="btn btn-default" value="Aceptar">
        </div>
    </div><!-- fin panel-footer -->
    </form>
  </div><!-- fin panel -->

<?php
    }// fin foreach
?>
    </div><!-- col-md-6 -->
    <div class="col-md-6">
      <h2>Evolución de la Enfermedad</h2>
      <?php
        foreach ($dairies as $tambo) {
      ?>
          <div class="panel panel-warning">
            <div class="panel-heading"><?php echo $tambo->name; ?></div>
            <form action="evolucionEnfermedad" method="post" class="form-inline">
              <div class="panel-body">
                <?php
                  $schemas = $tambo->schemasOrder("date asc");
                  if(!$schemas or count($schemas)<2){
                    echo "<p>No hay esquemas de control para seleccionar.</p>";
                  }
                  else{
                ?>
                    <table class="table table-borderer">
                        <thead>
                            <tr>
                                <th>Desde el Control</th>
                                <th>Hasta el Control</th>
                                <th>Evolución</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <select name="schema_id1" class="form-control">
                                            <?php
                                              foreach ($schemas as $schema) {
                                                  echo '<option value="'.$schema->id.'">'.DateHelper::db_to_ar($schema->date).'</option>';
                                              }
                                            ?>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select name="schema_id2" class="form-control">
                                            <?php
                                              foreach ($schemas as $schema) {
                                                  echo '<option value="'.$schema->id.'">'.DateHelper::db_to_ar($schema->date).'</option>';
                                              }
                                            ?>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-default" name="compare_costos" value="Costos">
                                        <input type="submit" class="btn btn-default" name="compare_indicadores" value="Indicadores">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                <?php
                  }//fin else
                ?>
              </div>
            </form>
           </div>
        <?php
            }//fin foreach
        ?>
    </div><!-- col-md-6 -->
</div><!-- row -->
<?php
}//fin else
?>