<h2 class="seccion" style="margin-left: -40px;">Otras Erogaciones</h2>
<table class="table" id="table-erogaciones">
  <thead>
    <tr>
      <th class="required" width="25%">Nombre</th>
      <th width="50%">Descripción</th>
      <th class="required"  width="15%">Precio por vaca</th>
      <th class="required"  width="10%">Días</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      if(isset($erogaciones)){
        $id_erogacion = 1;
        foreach ($erogaciones as $value) {
          $fname = "erogaciones[".$id_erogacion++."][";

    ?>
      <tr id="tr-erogacion_<?php echo $id_erogacion;?>">
        <?php
          echo '<input type="hidden" name="'.$fname.'name]" value="'.$value->name.'">';
          echo '<input type="hidden" name="'.$fname.'description]" value="'.$value->description.'">';
          echo '<input type="hidden" name="'.$fname.'price]" value="'.$value->price.'">';
          echo '<input type="hidden" name="'.$fname.'days]" value="'.$value->days.'">';
        ?>
        <td>
          <?php echo $value->name;?>
        </td>
        <td>
          <?php echo $value->description;?>
        </td>
        <td>
          <?php echo $value->price;?>
        </td>
        <td>
          <?php echo $value->days;?>
        </td>
        <td>
          <a href="" class="btn btn-danger" onClick="return Erogaciones.delete(<?php echo $id_erogacion;?>,event);"><span class="glyphicon glyphicon-remove-circle"></span></a>
        </td>
      </tr>
    <?php
        }//fin foreach
      }//end if erogaciones
    ?>
    <tr id="tr-new-erogacion">
      <td>
        <input type="text" class="form-control" id="erogaciones_name">
      </td>
      <td>
        <input type="text" class="form-control" id="erogaciones_description">
      </td>
      <td>
        <div class="input-group">
          <span class="input-group-addon">$</span>
          <input type="text" class="form-control" id="erogaciones_price" aria-label="Precio en $AR.">
        </div>
      </td>
      <td>
        <input type="text" class="form-control" id="erogaciones_days">
      </td>
      <td>
        <a href="" id="erogaciones_add" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span></a>
      </td>
    </tr>
  </tbody>
</table>
