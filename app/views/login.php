<div class="loginpage">
  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <h1>AVACO</h1>
      <p class="slogan">Una ayuda para el Asesor</p>
    </div>
    <div class="col-md-4">
      <img src="/public/images/icons/contacto.png"> info@avaco.com.ar
    </div>
  </div>
<div class="row">
  <div class="col-md-5 col-md-offset-1">
    <div class="credits"><br>
      <ul>
        <li>Universidad Nacional de Río Cuarto</li>
        <li>Universidad Nacional de Villa María</li>
        <li>Universidad Nacional de La Pampa</li>
        <li>Universidad de Buenos Aires</li>
      </ul>
      <p class="login_text1"><u>Entidades adoptantes del desarrollo</u></p>
      <ul>
        <li>APROCAL</li>
        <li>ACHA (ARC y ARPECOL)</li>
      </ul>
      <div class="login_text2">
        Proyecto de Desarrollo Tecnológico y Social  N°191<br>
        Convocatoria PDTS - CIN - CONICET 2014
      </div>
      <div class="login_text3">
        Director MV MSc PhD Alejandro Larriestra (UNRC)
      </div>
    </div>
  </div>
  <div class="col-md-4 col-md-offset-1">
  	<div id="login">
  		<form action="<?php echo $this->getUrlFor(array('session', 'login')) ?>" method="post" role="form">
  			<div class="form-group">
  				<label>Usuario</label>
  				<input type="text" name="username" value="" placeholder="Usuario" class="form-control" />
  			</div>
  			<div class="form-group">
  				<label>Contraseña</label>
  				<input type="password" name="password" value="" class="form-control" />
  			</div>
  			<button type="submit" class="btn btn-primary">Aceptar</button>
  		</form>
  	</div>
  </div>
</div>
</div>