<?php 
if(empty($dairies))
  echo "<h2>Primero debe crear el tambo y los controles lecheros</h3>";
else{
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
                    <th>Esquema 1</th>
                    <th>Esquema 2</th>
                </tr>
            </thead>
            <tbody>
                <?php            
                $cant = count($schemas);
                for($i = 0; $i < $cant -1; $i++){
                    $schema1= $schemas[$i];
                    $schema2= $schemas[$i+1];
                ?>
                <tr>
                    <td>
                        <label for="chkb_schema<?php echo $i; ?>">
                            <input type="radio" name="schemas_ids" value="<?php echo $schema1->id . ',' . $schema2->id; ?>" id="chkb_schema<?php echo $i; ?>">
                        </label>
                    </td>
                    <td>
                        <label for="chkb_schema<?php echo $i; ?>">
                            <?php echo DateHelper::db_to_ar($schema1->date); ?>
                        </label>
                    </td>
                    <td>
                        <label for="chkb_schema<?php echo $i; ?>">
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
    }
}
?>