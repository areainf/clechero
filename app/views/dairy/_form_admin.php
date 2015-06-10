<?php 
    require_once HELPERS_PATH.'FormHelper.php';
?>
<div class="separator-15"></div>
<div id="id-dairy-form">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <?php 
        $action = 'dairy/';
        if ($dairy->isPersistent()){
          $hidden = FormHelper::hidden_tag("dairy[id]", $dairy->id, array('id'=>'dairy_id'));
          $action .= 'update';
        }
        else{
          $hidden = "";
          $action .= 'create';
        }
        $owner = ($dairy->Owner == null) ? '' : "[{id:'".$dairy->Owner->id."', fullname:'" .$dairy->Owner->fullname()."'}]";
        $veterinary = ($dairy->Veterinary == null) ? '' : "[{id:'".$dairy->Veterinary->id."', fullname:'" .$dairy->Veterinary->fullname()."'}]";
      ?>
        <form class="form-horizontal" role="form" method="post"  action="<?php echo $action; ?>" name="form">
          <?php echo $hidden; ?>
          <div class="form-group">
            <label class="control-label col-sm-2" for="number">Número:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="number" name="dairy[number]" placeholder="Numero" value="<?php echo $dairy->number;?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="name">Nombre:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="name" name="dairy[name]" placeholder="Nombre" value="<?php echo $dairy->name;?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="owner">Dueño</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" id="owner_id" name="dairy[owner_id]" placeholder="Nombre" data-populate="<?php echo $owner; ?>" data-source="<?php echo $this->getUrlFor(array('owner', 'index_json'));?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="veterinary">Veterinario</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control" id="veterinary_id" name="dairy[veterinary_id]" placeholder="Nombre" data-populate="<?php echo $veterinary; ?>" data-source="<?php echo $this->getUrlFor(array('veterinary', 'index_json'));?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="email">Email:</label>
            <div class="col-sm-10">
              <input type="email" class="form-control" id="email" name="dairy[email]" placeholder="Enter email" value="<?php echo $dairy->email;?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="phone">Teléfonos:</label>
            <div class="col-sm-10">
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
