
<?php 
    require_once HELPERS_PATH.'Role.php'; 
    require_once HELPERS_PATH.'FormHelper.php';
    $person = $user->person();
?>
<div class="separator-15"></div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form class="form-horizontal" role="form" method="post"  action="user/create" name="form">
          <?php 
            if($person != null){
              echo FormHelper::hidden_tag("user[person_id]", $person->id, array('id'=>'person_id'));
          ?>
          <div class="form-group">
            <label class="control-label col-sm-2" for="person">Perfil:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="person" value="<?php echo $person->fullname(); ?>" disabled="disabled">
            </div>
          </div>

          <?php    
            }
          ?>
    <!--       <div class="form-group">
            <label class="control-label col-sm-2" for="username">Usuario:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="username" name="user[username]" placeholder="Usuario" value="<?php echo $user->username; ?>">
            </div>
          </div> -->
          <div class="form-group">
            <label class="control-label col-sm-2" for="email">Email:</label>
            <div class="col-sm-10">
              <input type="email" class="form-control" id="email" name="user[email]" placeholder="Enter email" value="<?php echo $person != null ? $person->email : ''; ?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="password">Contraseña:</label>
            <div class="col-sm-10"> 
              <input type="password" class="form-control" id="password" name="user[password]" placeholder="Contraseña">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="repassword">Repetir</label>
            <div class="col-sm-10"> 
              <input type="password" class="form-control" id="repassword" name="user[repassword]"  placeholder="Repetir contraseña">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="role">Tipo de usuario</label>
            <div class="col-sm-10"> 
                <select class="form-control" id="role" name="user[role]">
                    <?php echo FormHelper::options_for(Role::createRoles(Security::current_user())); ?>
                </select>
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
