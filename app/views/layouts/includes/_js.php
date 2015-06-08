 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo $public_url;?>bootstrap/js/bootstrap.min.js"></script>

<?php 
;
$directory = PUBLIC_PATH.'/js/';

$files = array_diff(scandir($directory), array('..', '.'));
foreach ($files as $key) {
    if(strlen($key) > 3 && substr($key, -3)=='.js')
        echo '<script src="' . $public_url . 'js/' . $key . '"></script>';
}
?>


<!--    <script src="<?php echo $public_url;?>/js/user.js"></script>
    <script src="<?php echo $public_url;?>/js/veterinary.js"></script>
    <script src="<?php echo $public_url;?>/js/owner.js"></script>
    <script src="<?php echo $public_url;?>/js/dairy.js"></script>
    <script src="<?php echo $public_url;?>/js/schema.js"></script>
-->
    <script src="<?php echo $public_url;?>/tokeninput/src/jquery.tokeninput.js"></script>
    <link rel="stylesheet" href="<?php echo $public_url;?>/tokeninput/styles/token-input.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $public_url;?>/tokeninput/styles/token-input-facebook.css" type="text/css" />
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo $public_url .'datatables/media/css/jquery.dataTables.css'; ?>">
    
    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="<?php echo $public_url .'datatables/media/js/jquery.dataTables.js"'; ?>"></script>
    <!-- GRAPH -->
    <script type="text/javascript" charset="utf8" src="<?php echo $public_url .'js/graph/flot/jquery.flot.js"'; ?>"></script>
    <script type="text/javascript" charset="utf8" src="<?php echo $public_url .'js/graph/highcharts/highcharts.js"'; ?>"></script>
    <script type="text/javascript" charset="utf8" src="<?php echo $public_url .'js/graph/highcharts/highcharts-more.js"'; ?>"></script>
    <script type="text/javascript" charset="utf8" src="<?php echo $public_url .'parsley/parsley.js"'; ?>"></script>
    <script type="text/javascript" charset="utf8" src="<?php echo $public_url .'parsley/es.js"'; ?>"></script>
    <script type="text/javascript">
      window.ParsleyValidator.setLocale('es');
    </script>
