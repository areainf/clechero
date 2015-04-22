<?php
require_once MODELS_PATH.'User.php';
require_once HELPERS_PATH.'Valid.php';

Class SessionController Extends BaseController {
  
  function __construct($ctrl) {
     parent::__construct($ctrl);
     $this->use_base_directory=false;
  }

  public function index(){
    die("No Implementado");
  }

  public function login() {  	
    if(Security::is_logged()){
      //ir a pagina principal de usuario logueado      
      return $this->render('index');
    } 
    else{
      $username = $this->ctrl->getData("username");
      $pword = $this->ctrl->getData("password");
      if(Valid::blank($username) || Valid::blank($pword)){
        $this->flash->add("El Usuario o Contraseña no puede ser vacio");
        $this->render('login');
      }
      else{
        $password = Encriptar::mycrypt($pword);
        $user = User::first(array('conditions' => array('username =? and password = ?', $username, $password)));
        if($user){
          if($user->disable){
            $this->flash->add("El usuario está deshabilitado");
            $this->render('login');   
          }
          else{
            Security::login($user);
            return $this->render('index');
          }
        }
        else{
          $this->flash->add("Usuario o Contraseña incorrecto");
          $this->render('login');
        }
      }
    }
  }

  public function logout(){
    if(Security::is_logged()){
      Security::logout();
      $this->flash->addMessage("Salio correctamente del sistema");
    } 
    return $this->render('login');
  }

  public function unauthorized() {
    $this->flash->add('No autorizado ir a la pagina de login');
    $this->render('login');
  }

  public function canExecute($action, $user){
    return true;
    return $user != NULL;
  }
}

?>
