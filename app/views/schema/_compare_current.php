<div class ="row">
  <!-- ini col 1 -->
  <div class="col-md-6">
    <h3>Análisis de pérdidad e indicadores de mastitis entre controles lecheros</h3>
    <div class="panel panel-success">
      <div class="panel-heading"><?php echo $dairy->name; ?></div>
      <form action="schema/compare" method="post" class="form-inline">
        <div class="panel-body">
        <?php
            $schemas = $dairy->schemasOrder("date asc");
            if(!$schemas or count($schemas)<2){
              echo "<p>No hay esquemas de control para comparar.</p>";
            }
            else{
        ?>
          <table class="table table-borderer">
              <thead>
                  <tr>
                      <th colspan="3">Seleccionar fechas de controles a comparar</th>
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
          <label for="umbral">Punto de Corte de RCS para definir MSC</label>
          <div class="form-group">
            <input type="number"class="form-control input-sm" name="umbral" value="<?php echo $umbral; ?>">
            <input type="submit" class="btn btn-default" value="Aceptar">
          </div>
        </div><!-- fin panel-footer -->
      </form>
    </div><!-- fin panel -->
  </div>
  <!-- fin col 1 -->
  <!-- ini col 2 -->
  <div class="col-md-6">
    <h3>Gráficos de pérdidas e indicadores de mastitis entre controles lecheros</h3>
    <div class="panel panel-warning">
      <div class="panel-heading"><?php echo $dairy->name; ?></div>
        <form action="schema/evolucionEnfermedad" method="post" class="form-inline">
          <div class="panel-body">
            <?php
            $schemas = $dairy->schemasOrder("date asc");
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
                    <th>Análisis</th>
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
                        <input type="submit" class="btn btn-default" name="compare_costos" value="Pérdidas">
                        <input type="submit" class="btn btn-default" name="compare_indicadores" value="Ind. de Mastitis" title="Indicadores de Mastitis">
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
  </div>
  <!-- fin col 2 -->
</div>