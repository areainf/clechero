<?php
    $url_add_dairy = Ctrl::getUrl(array('control'=>'dairy', 'action'=>'add'));
    $url_add_schema = Ctrl::getUrl(array('control'=>'schema', 'action'=>'add'));

?>
<h1>Bienvenido</h1>
<?php if(Security::is_dairy() || Security::is_veterinary()) { ?>
<div class="row">
  <div class="col-md-6">
    <div class="block-landpage">
      <h1>FILIACIÓN</h1>
      <div class="block-landpage-body">
        <ul>
          <li>
            <a href="<?php echo $url_add_dairy;?>">
              Nuevo Tambo</a>
          </li>
          <li class="dairies">
            <a href="<?php echo $this->getUrlFor('dairy'); ?>">Mis Tambos</a>
          </li>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="block-landpage">
      <h1>DATOS PRODUCTIVOS</h1>
      <div class="block-landpage-body">
        <ul>
          <li>
            <a href="<?php echo $url_add_schema;?>">Nuevo Control Lechero</a>
          </li>
          <li class="dairies">
            <a href="<?php echo $this->getUrlFor('schema'); ?>">Listado de Controles</a>
          </li>
      </div>
    </div>
  </div>
</div>
<br>
<div class="row">
  <div class="col-md-12">
    <div class="block-landpage">
      <h1>ANÁLISIS INTRATAMBO</h1>
      <div class="block-landpage-body">
        <ul>
          <li>
            <a href="schema/compare">Comparar</a>
            <p class="help">Análisis de pérdidad e indicadores de mastitis entre controles lecheros y<br>
            Gráficos de pérdidas e indicadores de mastitis entre controles lecheros</a>
          </li>
      </div>
    </div>
  </div>
</div>
<?php }//is veterinary ?>


<!-- 
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
<br> -->
<!-- <div class="row">
  <div class="col-xs-6 col-md-3">
    <div class="thumbnail">
      <div class="caption">
        <h3>Nombre del Tambo</h3>
        <p>FECHA</p>
        <p><a href="#" class="btn btn-primary" role="button">Análisis y Erogaciones</a></p>
      </div>
    </div>
  </div>
  <div class="col-xs-6 col-md-3">
    <div class="thumbnail">
      <div class="caption">
        <h3>Nombre del Tambo</h3>
        <p>FECHA</p>
        <p><a href="#" class="btn btn-primary" role="button">Análisis y Erogaciones</a></p>
      </div>
    </div>
  </div>
  <div class="col-xs-6 col-md-3">
    <div class="thumbnail">
      <div class="caption">
        <h3>Nombre del Tambo</h3>
        <p>FECHA</p>
        <p><a href="#" class="btn btn-primary" role="button">Análisis y Erogaciones</a></p>
      </div>
    </div>
  </div>
  <div class="col-xs-6 col-md-3">
    <div class="thumbnail">
      <div class="caption">
        <h3>Nombre del Tambo</h3>
        <p>FECHA</p>
        <p><a href="#" class="btn btn-primary" role="button">Análisis y Erogaciones</a></p>
      </div>
    </div>
  </div>
</div>
 -->