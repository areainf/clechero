<div class="container-fluid">
    <div class="col-md-4">        
        <h2>Vacas en el Tambo</h2>
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
          <?php 
            $url_add = Ctrl::getUrl(array('control' => 'cow', 'action' => 'add', 'params'=>array('dairy_id'=>$dairy->id)));
            $url_index = Ctrl::getUrl(array('control' => 'cow', 'action' => 'index', 'params'=>array('dairy_id'=>$dairy->id)));
            if($this->getAction() != 'index'){ 
          ?>  
          <li><a href="<?php echo $url_index ?>" class="btn btn-info btn-xs">Listado</a></li>
          <?php  } ?>  
        </ul>
    </div>
</div>
