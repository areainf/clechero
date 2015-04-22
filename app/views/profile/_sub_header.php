<div class="container-fluid">
    <div class="col-md-4">        
        <h2>PERFIL</h2>
    </div>
    <div class="col-md-4 text-center">        
        <?php
          if($this->getAction()=='edit'){
        ?>  
          <h3>Modificar perfil</h3>
        <?php
          }
          else{
        ?>  
          <h3>Detalle</h3>
        <?php  } ?>  
    </div>
    <div class="col-md-4">        
       <ul class="list-nav list-nav-right">
          <?php if($this->getAction()=='index'){ ?>  
            <li><a href="edit" class="btn btn-info btn-xs">Editar</a></li>
          <?php
            }
          ?>  
        </ul>
    </div>
</div>
