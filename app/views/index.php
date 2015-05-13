<?php 
    $url_add_dairy = Ctrl::getUrl(array('control'=>'dairy', 'action'=>'add'));
    $url_add_schema = Ctrl::getUrl(array('control'=>'schema', 'action'=>'add'));

?>
<h1>Bienvenido</h1>
<p>Esta página es la inicial para cualquier usuario logueado. Aca podria ir cualquier tipo de información.
	Tambien se podria evitar esta pagina y redirigir a alguna accion por defecto
</p>
<div class="row">
  <div class="col-md-4 col-md-offset-1">
    <div class="box-owner">      
      <h3>FILIACIÓN</h3>
        <a href="<?php echo $url_add_dairy;?>" class="box-btn1 box-btn-sm">Nuevo Tambo</a><br>
        <a href="<?php echo $this->getUrlFor('dairy'); ?>" class="box-btn1 box-btn-sm">Mis Tambos</a>
    </div>
  </div>
  <div class="col-md-2">
  </div>
  <div class="col-md-4">
    <div class="box-schema">
      <h3>DATOS PRODUCTIVOS</h3>
        <a href="<?php echo $url_add_schema;?>" class="box-btn box-btn-sm">Nuevo Control Lechero</a><br>
      <a href="<?php echo $this->getUrlFor('schema'); ?>" class="box-btn  box-btn-sm">Listado de Controles</a>

    </div>
  </div>
</div>
<br>
<div class="row">
  <div class="col-md-12">
    <div class="box-schema">REPORTES</div>
  </div>
</div>
