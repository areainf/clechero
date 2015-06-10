<div class="loginpage">
<h1>AVACO</h1>
<p class="slogan">Una ayuda para el Asesor</p>
<div class="row">
  <div class="col-md-4 col-md-offset-4">
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