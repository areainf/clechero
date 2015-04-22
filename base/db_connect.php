<?php
// require_once LIB_DIR.'php-activerecord/ActiveRecord.php';
 
// ActiveRecord\Config::initialize(function($cfg) {
//   global $config;
//   $cfg->set_model_directory(MODELS_PATH);
//   $cfg->set_connections(array(
//     'development' => $config->getValue('database')['db_url']));
// });
require_once(LIB_DIR . "ez_sql/shared/ez_sql_core.php");
require_once(LIB_DIR . "ez_sql/mysql/ez_sql_mysql.php");
global $_SQL;
$_SQL = new ezSQL_mysql();
$_SQL->connect('root', 'dijkstra', 'localhost');
$_SQL->select('clecherodb');
$_SQL->query("SET names utf8;");
?>