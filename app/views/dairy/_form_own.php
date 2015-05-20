
<?php 
    require_once HELPERS_PATH.'FormHelper.php';
?>

<div class="separator-15"></div>
<div id="id-dairy_own-form">
  <div class="row">
    <div class="col-md-8 col-md-offset-1">
      <?php 
        if ($dairy->isPersistent()){
          $hidden = FormHelper::hidden_tag("dairy[id]", $dairy->id, array('id'=>'dairy_id'));
          $action = 'update';
        }
        else{
          $hidden = "";
          $action = 'create';
        }
        $owner = ($dairy->Owner == null) ? '' : "[{id:'".$dairy->Owner->id."', fullname:'" .$dairy->Owner->fullname()."'}]";
        $veterinary_id = ($dairy->Veterinary == null) ? '' : $dairy->Veterinary->id;
        $owner_id = ($dairy->owner() == null) ? '' : $dairy->owner()->id;
      ?>
        <form class="form-horizontal" role="form" method="post"  action="<?php echo $action; ?>" name="form">
          <?php echo $hidden; ?>
          <!-- <div class="form-group">
            <label class="control-label col-sm-2" for="number">Número:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="number" name="dairy[number]" placeholder="Numero" value="<?php echo $dairy->number;?>">
            </div>
          </div> -->
          <div class="form-group">
            <label class="control-label col-sm-6" for="name">Nombre:</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="name" name="dairy[name]" placeholder="Nombre" value="<?php echo $dairy->name;?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-6" for="location">Ubicación Geográfica:</label>
            <div class="col-sm-6">
              <textarea class="form-control" id="location" name="dairy[location]"><?php echo $dairy->location;?></textarea>
              <p class="help-block">Localidad más cercana, coordenada geográfica, referencia, etc.</p>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-6" for="industry">Industria a la que entrega la leche:</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="industry" name="dairy[industry]" value="<?php echo $dairy->industry;?>">
            </div>
          </div>
          <?php if(Security::is_dairy()){?>
            <div class="form-group">
              <label class="control-label col-sm-6" for="veterinary">Veterinario</label>
              <div class="col-sm-6"> 
                <select class="form-control" id="veterinary_id" name="dairy[veterinary_id]">
                  <?php 
                    echo FormHelper::options_for_collection($veterinarians,"id",'fullname',$veterinary_id);
                  ?>
                </select>
              </div>
            </div>
          <?php 
            }
            else{
          ?>
            <div class="form-group">
              <label class="control-label col-sm-6" for="owner">Dueño</label>
              <div class="col-sm-6"> 
                <select class="form-control" id="owner_id" name="dairy[owner_id]">
                  <?php 
                    echo FormHelper::options_for_collection($owners,"id",'fullname',$owner_id);
                  ?>
                </select>
              </div>
            </div>
          <?php    
            }
          ?>
          <div class="form-group">
            <label class="control-label col-sm-6" for="email">Email:</label>
            <div class="col-sm-6">
              <input type="email" class="form-control" id="email" name="dairy[email]" placeholder="Enter email" value="<?php echo $dairy->email;?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-6" for="phone">Teléfonos:</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="phone" name="dairy[phone]" placeholder="Telefonos" value="<?php echo $dairy->phone;?>">
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
</div>
