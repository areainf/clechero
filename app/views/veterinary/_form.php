
<?php 
    require_once HELPERS_PATH.'FormHelper.php';
?>
<div class="separator-15"></div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
      <?php
        $action = "veterinary/";
        if ($veterinary->isPersistent()){
          $hidden = FormHelper::hidden_tag("veterinary[id]", $veterinary->id, array('id'=>'veterinary_id'));
          $action .= 'update';
        }
        else{
          $hidden = "";
          $action .= 'create';
        }
      ?>
        <form class="form-horizontal" role="form" method="post"  action="<?php echo $action; ?>" name="form">
          <?php echo $hidden; ?>
          <div class="form-group">
            <label class="control-label col-sm-2" for="first_name">Nombre:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="first_name" name="veterinary[first_name]" placeholder="Nombre" value="<?php echo $veterinary->first_name;?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="last_name">Apellido:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="last_name" name="veterinary[last_name]" placeholder="Apellido" value="<?php echo $veterinary->last_name;?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="email">Email:</label>
            <div class="col-sm-10">
              <input type="email" class="form-control" id="email" name="veterinary[email]" placeholder="Enter email" value="<?php echo $veterinary->email;?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="phone">Teléfonos:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="phone" name="veterinary[phone]" placeholder="Telefonos" value="<?php echo $veterinary->phone;?>">
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
