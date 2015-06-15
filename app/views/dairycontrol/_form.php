<?php 
    require_once HELPERS_PATH.'FormHelper.php';
?>
<div id="id-dairycontrol-form">
<div class="separator-15"></div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
      <?php 
        $mc = $dairycontrol->mc;
        $action = 'dairycontrol/';
        if ($dairycontrol->isPersistent()){
          $hidden = FormHelper::hidden_tag("dairycontrol[id]", $dairycontrol->id, array('id'=>'dairycontrol_id'));
          $action .= 'update';
        }
        else{
          $hidden = "";
          $action .= 'create';
        }
        $cow = $dairycontrol->cow();
        $select_cow = ($cow == null) ? '' : "[{id:'".$cow->id."', caravana:'" .$cow->caravana."'}]";

      ?>
        <form class="form-horizontal" role="form" method="post"  action="<?php echo $action; ?>" name="form">
          <input type="hidden" id="schema_id" name="dairycontrol[schema_id]" value="<?php echo $dairycontrol->schema_id; ?>">          
          <?php echo $hidden; ?>
          <div class="form-group">
            <label class="control-label col-sm-6" for="cow_id">Caravana:</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="cow_id" name="dairycontrol[cow_id]" placeholder="Caravana" data-populate="<?php echo $select_cow; ?>" data-source="<?php echo Ctrl::getUrl(array('control' => 'cow', 'action' => '_not_in_control_json', 'params'=>array('schema_id'=>$schema->id)));?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-6" for="nop">Número Ordinal de Parto:</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="nop" name="dairycontrol[nop]" placeholder="nop" value="<?php echo $dairycontrol->nop;?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-6" for="dl">Días Lactancia:</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="dl" name="dairycontrol[dl]" placeholder="dl" value="<?php echo $dairycontrol->dl;?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-6" for="rcs">Recuento Celulas Somáticas:</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="rcs" name="dairycontrol[rcs]" placeholder="rcs" value="<?php echo $dairycontrol->rcs;?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-6" for="mc">Tiene MC:</label>
            <div class="col-sm-6">
              <select class="form-control" id="mc" name="dairycontrol[mc]">
                <option value="1" <?php echo empty($mc) ? '' : 'selected'; ?>>SI</option>
                <option value="1" <?php echo empty($mc) ? 'selected' : ''; ?>>NO</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-6" for="liters_milk">Lts. Leche:</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="liters_milk" name="dairycontrol[liters_milk]" placeholder="liters_milk" value="<?php echo $dairycontrol->liters_milk;?>">
            </div>
          </div>

          <div class="form-group text-center"> 
            <div class="center-block">
              <button type="submit" name="boton" class="btn btn-default">Aceptar</button>
               
            </div>
          </div>
        </form>
    </div>
</div>
</div>