<?php
require_once HELPERS_PATH.'Flash.class.php';
require_once 'Registry.php';
Abstract Class BaseController {
  const extension= 'Controller';
  public $ctrl;
  protected $flash;
  // protected $messages;
  // protected $alerts;
  protected $page_title;
  protected $base_directory = "";
  protected $use_base_directory = true;
  protected $registry;

  
  /*
   * @registry object
   */
  function __construct($ctrl) {
    $this->ctrl = $ctrl;
    $this->registry = new Registry();
    $this->flash = new Flash();
    $this->setBaseDirectory();
  }
  
  public function canExecute($action, $user){return true;}
  
  public function getFlash(){return $this->flash;}

  public function getUrlFor($option){
    $control = "";
    $accion = "";
    if (is_array($option)){
      $found = false;
      if (isset($option['control'])){
        $control = $option['control'];
        $found = true;
      }
      if (isset($option['accion'])){
        $control = $option['control'];
        $found = true;
      }
      if (!$found){
        $c = count($option);
        if($c>0)
          $control = $option[0];
        if($c>1)
          $action = $option[1];
      }
    }
    else{
      $control = $option;
      $action = "";
    }
    return Ctrl::getUrl(array("control"=>$control, "action"=>$action));
  }
  public function getData($key = null){
    return $this->ctrl->getData($key);
  }
  public function getParameters($name=null){
    return $this->ctrl->getParameters($name);
  }
  public function addParameter($name, $value){
    $this->ctrl->addParameter($name, $value);
  }
  public function isGet(){
    return $_SERVER['REQUEST_METHOD'] == 'GET';
  }
  public function render($name, $layout='default') {
      $path = __SITE_PATH . '/app/views' . '/' ;
      if ($this->use_base_directory)
          $path .= $this->base_directory.'/';

      $path_subheader = $path;

      $path .=  $name . '.php';
      $path_layout = __SITE_PATH . '/app/views/layouts' . '/' . $layout . '.php';

      $path_subheader .= '_sub_header.php';

      if(file_exists($path_subheader))
          $yield_sub_header = $path_subheader;

      // Load variables
      foreach ($this->registry->getVars() as $key => $value){
          $$key = $value;
      }
      //pone a disposicion el usuario corriente
      $current_user = Security::current_user();
      
      $yield = $path;
      include ($path_layout);
  }

  public function getPageTitle(){
    if(empty($this->page_title)){
      $config = $this->ctrl->config;
      return $config->getValue('application')['page_title'];
    }
    else
      return $this->page_title;
  }

  public function setPageTitle($page_title){
    $this->$page_title = $page_title;
  }

  public function getAction(){
    return $this->ctrl->action;
  }
  public function renameAction($action){
    $this->ctrl->action = $action;
  }

  public function is_get(){
    return ($_SERVER['REQUEST_METHOD'] === 'GET');
  }

  public function is_post(){
    return ($_SERVER['REQUEST_METHOD'] === 'POST');
  }


  private function setBaseDirectory(){
    $control_name = get_class($this);
    $this->base_directory = strtolower(substr($control_name, 0, strlen(self::extension) * -1));
  }
  

  /**
   * @all controllers must contain an index method
   */
  abstract function index();
  }



?>
