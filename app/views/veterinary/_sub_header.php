<div class="container-fluid">
    <div class="col-md-4">        
        <h2>VETERINARIOS</h2>
    </div>
    <div class="col-md-4 text-center">        
        <?php if($this->getAction()=='add'){ ?>  
          <h3>Nuevo</h3>
        <?php
          }
          else if($this->getAction()=='edit'){
        ?>  
          <h3>Editar</h3>
        <?php
          }
          else{
        ?>  
          <h3>Listado</h3>
        <?php  } ?>  
    </div>
    <div class="col-md-4">        
       <ul class="list-nav list-nav-right">
          <?php if($this->getAction()=='index'){ ?>  
          <li><a href="add" class="btn btn-info btn-xs">Nuevo</a></li>
          <?php
            }
            else{ 
          ?>  
          <li><a href="index" class="btn btn-info btn-xs">Listado</a></li>
          <?php  } ?>  
        </ul>
    </div>
</div>
