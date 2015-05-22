<!DOCTYPE html>
  <?php
  	$url_base = URL_SITE;
    $public_url = $url_base."/public/";
  ?>
<html lang="en">
  <head>
    <?php header('Content-Type: text/html; charset=UTF-8'); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->getPageTitle();?></title>
    <base href="<?php echo $url_base; ?>">
    <!-- Bootstrap -->
    <link href="<?php echo $public_url;?>bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $public_url;?>css/style.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?php echo $public_url;?>/js/graph/flot/excanvas.min.js"></script><![endif]-->

    <?php include "includes/_js.php"; ?>
  </head>
  <body>
      <?php 
        if(Security::is_admin())
          include "includes/_menu_top.php"; 
        elseif (Security::is_dairy()) {
          include "includes/_menu_top_dairy.php"; 
        }
      ?>
      <?php if(!empty($yield_sub_header)): ?>
        <!-- INICIO CONTAINER SUB_HEADER -->
        <div class="container">
          <div class="sub_header">
            <?php include $yield_sub_header; ?>
          </div>
        </div>
        <!-- FIN CONTAINER SUB_HEADER -->
      <?php endif; ?>
    <!-- INICIO CONTAINER PRINCIPAL -->
    <div class="container">
      <div id="_flash">
        <?php  include "includes/_flash.php"; ?>
      </div>  
      <?php include $yield; ?>
    </div>
    <!-- FIN CONTAINER PRINCIPAL -->
  </body>
</html>
    