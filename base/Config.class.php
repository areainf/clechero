<?php

class Config{

  private $config_values = array();

  public function __construct(){
    $this->config_values = parse_ini_file(FILE_CONFIG_PATH, true);
  }

  public function getValue($key){
    return $this->config_values[$key];
  }
}
