<?php


class Ctrl {
  private $url;
  private $controllers_path;
  public $controller_name;
  public $controller;
  private $file;
  public $action; 
  public $action_callable;
  protected $extra = NULL; //informacion extra que pueda venir en el url ej:/edit/ahora/extra
  protected $parameters = NULL;//Parametros que vienen en el url
  protected $default_controller;
  protected $data;

  /*
  * @var object $instance
  */

  function __construct($url_request, $controllers_path) {
    $this->url = $url_request;
    $this->controllers_path = $controllers_path;
    $this->controller_name="";
    $this->parameters = $_GET;
    $this->data = $_POST;
    $this->defaultController();
    $this->getController(); 
  }

  public function setConfig($config){
    $this->config = $config;
  }

  public function loader(){
    /*** if the file is not there diaf ***/
    if (is_readable($this->file) == false){
        $this->file = $this->controllers_path.'/error404.php';
        $this->controller_name = 'error404';
    }
    /*** include the controller ***/
    include_once $this->file;
    /*** a new controller class instance ***/
    $class = $this->controller_name . 'Controller';
    $this->controller = new $class($this);
    /*** check if the action is callable ***/
    $this->action_callable = is_callable(array($this->controller, $this->action));
  }

  public function getData($name=NULL){
    if ($name != NULL)
      return isset($this->data[$name]) ? $this->data[$name] : NULL;
    return $this->data;
  }

  public function getParameters($name=NULL){
    if ($name != NULL)
      return isset($this->parameters[$name]) ? $this->parameters[$name] : NULL;
    return $this->parameters;
  }

  public function addParameter($name, $value){
    $this->parameters[$name] =  $value;
  }

  private function getController() {
    $current = $this->currentURL();
    if (strpos($current, URL_SITE)==0){
      $url_parse = substr($current, strlen(URL_SITE));

      $url_parse = explode('?',$url_parse,2);
      $arr=array_slice(array_filter(explode('/',$url_parse[0])),0);
      if(count($arr)>0){
          $this->controller_name = ucwords($arr[0]);
          $this->action = (count($arr)>1) ? $arr[1] : "";
          $this->extra = array();
          if (count($arr)>2)
            $this->extra=array_slice($arr, 2) ;
      }
      else
        $this->controller_name = "App";

    }
    else
      $this->setController404();
    
    if (empty($this->controller_name))
      $this->controller = 'App';
    /*** Get action ***/
    if (empty($this->action) || $this->action === '')
      $this->action = 'index';
    /*** set the file path ***/
    $this->file = $this->controllers_path .'/'. $this->controller_name . 'Controller.php';
  }
 
  private function defaultController(){
    include_once $this->controllers_path .'/AppController.php';
    $this->defaultController = new AppController($this);
  }

    /*** Obtiene el url base del sitio ***/
  private function currentUrl(){
    return sprintf(
      "%s://%s%s",
      isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
      $_SERVER['SERVER_NAME'],
      $_SERVER['REQUEST_URI']
    );

    // return $_SERVER['REQUEST_URI'];
  }

  /*
   * input = options (array)
   * keys options:
   *          control: name of controls
   *          action: name of action in control
   *          params: params in url
   *

  */

  final public static function getUrl($options){
        
      $ctrlname = isset($options['control']) ? $options['control'].'/' : '';
      $action = isset($options['action']) ? $options['action'] : '';
      $params_str="";
      if(isset($options['params'])){
          /*$index = 0;
          foreach ($options['params'] as $key => $value) {
              if($index != 0)
                  $params_str .= '&';
              $index++;
              $params_str .= $key.'='.urlencode($value);
          }
          $params_str = '?'.$params_str;
          */
          $params_str = '?'.http_build_query($options['params']);
      }
      return URL_SITE."/".$ctrlname.$action.$params_str;

    }

}

?>
