<?php  
require_once HELPERS_PATH.'I18n.php';
if (!$this->flash->isEmpty()){ 
  if ($this->flash->hasErrors()): 
?>

<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <ul>
        <?php foreach ($this->flash->getErrors() as $key => $value){ ?>
          <li>
            <?php 
              if(!is_int($key)){
                echo "<strong>".I18n::t($key).":</strong>" ;
              } 
            ?>

            <?php echo $value; ?>
          </li>
        <?php } ?>
      </ul>

</div>



  <?php endif; ?>

<?php  if ($this->flash->hasMessages()): ?>
    <div class="flash_messages">
      <ul>
        <?php foreach ($this->flash->getMessages() as $value){ ?>
          <li><?php echo $value; ?></li>
        <?php } ?>
      </ul>
    </div>
  <?php endif; ?>

<?php  if ($this->flash->hasAlerts()): ?>
    <div class="flash_alerts">
      <ul>
        <?php foreach ($this->flash->getAlerts() as $value){ ?>
          <li><?php echo $value; ?></li>
        <?php } ?>
      </ul>
    </div>
  <?php endif; ?>

<?php  } ?>
