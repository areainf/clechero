<?php
  require_once HELPERS_PATH.'Security.php';
  
  $user = Security::current_user();
  $person = $user->person();
  $person_name = "";
?>
<div id="id-profile-list">
  <form class="form-horizontal" role="form" method="post"  action="update" name="form">

  <div class="row">
    <div class="col-md-6 col-md-offset-2">
      <div class="form-group">
        <label class="text-right control-label col-xs-4">Usuario:</label>
        <div class="col-xs-8">
          <span class="form-control input-sm" disabled><?php echo $user->username; ?></span>
        </div>
      </div>
    
      <div class="form-group">
        <label class="text-right control-label col-xs-4">Rol:</label>
        <div class="col-xs-8">
          <span class="form-control input-sm" disabled><?php echo Role::$roles[$user->role]; ?></span>
        </div>
      </div>
      <div class="form-group">
        <label class="text-right control-label col-xs-4">Email:</label>
        <div class="col-xs-8">
          <input type="text" class="form-control input-sm" name="person[email]" id="person_email" value="<?php echo $person->email; ?>" >
        </div>
      </div>
      <div class="form-group">
        <label class="text-right control-label col-xs-4">Teléfono:</label>
        <div class="col-xs-8">
          <input type="text" class="form-control input-sm" name="person[phone]" id="person_phone" value="<?php echo $person->phone; ?>" >
        </div>
      </div>
    </div>
    <div class="col-md-12 text-center">
      <button type="submit" name="boton" class="btn btn-default">Aceptar</button>
    </div>
  </div> 
  </form>
</div> 
