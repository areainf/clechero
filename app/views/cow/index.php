<?php 
    require_once HELPERS_PATH.'FormHelper.php';
    $vet = $dairy->veterinary();
    $veterinary_name ="";
    if (!empty($vet))
      $veterinary_name = $vet->fullname();
?>
<h2><small>Tambo</small></h2>
 <table class="table table-bordered">
    <thead>      
      <tr>
        <th>Nombre</th>
        <th>Ubicación</th>
        <th>Industría</th>
        <th>Veterinario</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <td><?php echo $dairy->name; ?></td>
      <td><?php echo $dairy->location; ?></td>
      <td><?php echo $dairy->industry; ?></td>
      <td><?php echo $veterinary_name; ?></td>
      <td>
        <?php 
          $img_edit = '<span class="glyphicon glyphicon-edit"></span>';
          $url_edit = Ctrl::getUrl(array('control'=>'dairy', 'action'=>'edit', 'params'=>array('id'=>$dairy->id)));
          $a_edit = FormHelper::link_to($url_edit,$img_edit);
          $div = '<div class="dt-action">';
          echo $div.$a_edit.'</div>';
        ?>

      </td>
    </tbody>
  </table>
<hr>
<h2><small>Listado de Animales</small></h2>

<div id="id-cow-list">
  <table id="table-cow" class="display" 
    data-source="<?php echo Ctrl::getUrl(array('control'=>'cow', 'action'=>'index_json', 'params'=>array('dairy_id'=>$dairy->id)));?>">
    <thead>      
      <tr>
        <th>ID</th>
        <th>Número</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>
