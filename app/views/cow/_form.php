<?php 
    require_once HELPERS_PATH.'FormHelper.php';
?>
<div id="id-cow-form">
<div class="separator-15"></div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
      <?php 
        $mc = $cow->mc;
        if ($cow->isPersistent()){
          $hidden = FormHelper::hidden_tag("cow[id]", $cow->id, array('id'=>'caravana'));
          $action = 'update';
        }
        else{
          $hidden = "";
          $action = 'create';
        }
        $dairy = $cow->dairy();
      ?>
      <h2><small>Tambo</small></h2>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Número</th>
            <th>Nombre</th>
            <th>Vacas</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo $dairy->number;?></td>
            <td><?php echo $dairy->name;?></td>
            <td><?php echo $dairy->countCattle();?></td>
          </tr>
        </tbody>
      </table>
      <h2><small>Vaca</small></h2>
        <form class="form-horizontal" role="form" method="post"  action="<?php echo $action; ?>" name="form">
          <input type="hidden" id="dairy_id" name="cow[dairy_id]" value="<?php echo $cow->dairy_id; ?>">          
          <?php echo $hidden; ?>
          <div class="form-group">
            <label class="control-label col-sm-2" for="caravana">Caravana:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="caravana" name="cow[caravana]" placeholder="Caravana" value="<?php echo $cow->caravana; ?>">
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