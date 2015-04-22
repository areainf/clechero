<?php require_once MODELS_PATH.'User.php' ?>
    <!-- Static navbar -->
  <nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Control Lechero v.1.0</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li><a href="<?php echo $this->getUrlFor('veterinary') ?>">Veterinarios</a></li>
          <li><a href="<?php echo $this->getUrlFor('owner') ?>">Tamberos</a></li>
          <li><a href="<?php echo $this->getUrlFor('dairy') ?>">Tambos</a></li>
          <li><a href="#">Esquema</a></li>
          <li><a href="#">Control</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Listado<span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#">Listado de ...</a></li>
              <li><a href="#">Listado de ...</a></li>
              <li><a href="#">Listado de ...</a></li>
              <li><a href="#">Listado de ...</a></li>
              <li class="divider"></li>
              <li class="dropdown-header">Procesamiento </li>
              <li><a href="#">Procesamiento de ....</a></li>
              <li><a href="#">Procesamiento de ....</a></li>
              <li><a href="#">Procesamiento de ....</a></li>
            </ul>
          </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="<?php echo $this->getUrlFor('user') ?>">Usuarios</a></li>
          <li class="active"><a href="<?php echo $this->getUrlFor(['session', 'logout']) ?>">Salir <span class="sr-only">(current)</span> <?php echo Security::current_user()->username; ?></a></li>
        </ul>
      </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
  </nav>
