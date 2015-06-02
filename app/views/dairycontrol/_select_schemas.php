<?php
  // $dairy = Security::current_dairy();
  if(isset($schema)){
    $dairy = $schema->dairy();
?>
<div id="dairycontrol_schemas">
  <label class="col-sm-6">Cambio rápido de Control Lechero</label>
  <div class="col-sm-6">
  <select id="select_schema" class="form-control">
    <?php    
        foreach ($dairy->schemas() as $sch) {
          $selected = $sch->id == $schema->id ? ' selected ' : '';
         echo '<option value="'.$sch->id.'" '.$selected.'>'.DateHelper::db_to_ar($sch->date).'</option>';
        }
    ?>
  </select>
  </div>
</div>
<?php    
  }//end if(isset($schema)){
?>