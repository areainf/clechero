<?php
 require_once HELPERS_PATH.'FormHelper.php';
 require_once MODELS_PATH.'User.php' 
?>
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
        <a class="navbar-brand" href="<?php echo URL_SITE;?>">Control Lechero v.1.0</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li><a class="navbar-brand" href="<?php echo URL_SITE;?>"><span class="glyphicon glyphicon-th-large"></span>Inicio</a></li>
          <?php 
            if (Security::is_dairy()) {
          ?>
          <li><a href="<?php echo $this->getUrlFor('veterinary') ?>">Veterinarios</a></li>
          <?php 
            }
            else{
          ?>
          <li><a href="<?php echo $this->getUrlFor('owner') ?>">Dueño</a></li>
          <?php 
            }
          ?>
          <li><a href="<?php echo $this->getUrlFor('dairy') ?>">Tambos</a></li>
          <li><a href="<?php echo $this->getUrlFor('schema') ?>">Datos</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
           <li>
            <?php
                $curr_d = Security::current_dairy();
                if ($curr_d)
                  $dairy_id = $curr_d->id;
                else
                  $dairy_id=0;
            ?>
            <select class="input-medium" id="navbar_select_dairies" data-update="dairy/select" style="margin-top: 17px;">
              <option value=""></option>
                <?php echo FormHelper::options_for_collection(Security::current_user()->dairies(), 'id', 'fullname', $dairy_id); ?>
            </select>
          </li>
          <li><a href="public/doc/manual_AVACO.pptx">
            <img src="public/images/icons/help-icon.png"  class="btn btn-success btn-xs">
          </a></li>
          <li><a href="<?php echo $this->getUrlFor(array('profile', 'index')) ?>">Perfil</a></li>
          <li class="active"><a href="<?php echo $this->getUrlFor(array('session', 'logout')) ?>">Salir <span class="sr-only">(current)</span> <?php echo Security::current_user()->username; ?></a></li>
         </ul>
      </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
  </nav>
