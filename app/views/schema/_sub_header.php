<div class="container-fluid">
    <div class="col-md-4">        
        <h2>Esquema de Control</h2>
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
          else if($this->getAction()=='compare'){
        ?>  
          <h3>Comparar Controles Lecheros</h3>
        <?php
          }
          else if($this->getAction()=='evolucionEnfermedad'){
        ?>  
          <h3>Evolución de la Emfermedad</h3>
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
            <li><a href="schema/add" class="btn btn-info btn-xs">Nuevo</a></li>
            <li><a href="schema/compare" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-transfer">Comparar</span></a></li>
          <?php
            }
            elseif($this->getAction()=='compare'){
          ?>
            <li><a href="schema/add" class="btn btn-info btn-xs">Nuevo</a></li>
            <li><a href="schema/index" class="btn btn-info btn-xs">Listado</a></li>
            <?php 
              if($_SERVER['REQUEST_METHOD'] == 'POST'){
            ?>
              <li><a href="schema/compare" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-transfer">Volver</span></a></li>
          <?php
              }
            }

            elseif($this->getAction()=='evolucionEnfermedad'){
          ?>
            <li><a href="schema/add" class="btn btn-info btn-xs">Nuevo</a></li>
            <li><a href="schema/index" class="btn btn-info btn-xs">Listado</a></li>
            <li><a href="schema/compare" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-transfer">Volver</span></a></li>
          <?php
            }
            else{ 
          ?>  
          <li><a href="schema/add" class="btn btn-info btn-xs">Nuevo</a></li>
            <li><a href="schema/index" class="btn btn-info btn-xs">Listado</a></li>
            <li><a href="schema/compare" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-transfer">Comparar</span></a></li>
          <?php  } ?>  
        </ul>
    </div>
</div>
