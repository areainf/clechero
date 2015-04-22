
<?php 
    require_once HELPERS_PATH.'FormHelper.php';
?>
<div class="separator-15"></div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
      <?php 
        if ($owner->isPersistent()){
          $hidden = FormHelper::hidden_tag("owner[id]", $owner->id, array('id'=>'uowner_id'));
          $action = 'update';
        }
        else{
          $hidden = "";
          $action = 'create';
        }
      ?>
        <form class="form-horizontal" role="form" method="post"  action="<?php echo $action; ?>" name="form">
          <?php echo $hidden; ?>
          <div class="form-group">
            <label class="control-label col-sm-2" for="first_name">Nombre:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="first_name" name="owner[first_name]" placeholder="Nombre" value="<?php echo $owner->first_name;?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="last_name">Apellido:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="last_name" name="owner[last_name]" placeholder="Apellido" value="<?php echo $owner->last_name;?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="email">Email:</label>
            <div class="col-sm-10">
              <input type="email" class="form-control" id="email" name="owner[email]" placeholder="Enter email" value="<?php echo $owner->email;?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="phone">Teléfonos:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="phone" name="owner[phone]" placeholder="Telefonos" value="<?php echo $owner->phone;?>">
            </div>
          </div>
          <div class="form-group text-center"> 
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" name="boton" class="btn btn-default">Aceptar</button>
               
            </div>
          </div>
        </form>
    </div>
</div>
