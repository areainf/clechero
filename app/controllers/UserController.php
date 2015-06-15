<?php
require_once MODELS_PATH.'User.php';
require_once MODELS_PATH.'Person.php';
require_once DATATABLE_PATH.'UserDatatable.php';

class UserController Extends BaseController {
  function __construct($ctrl) {
    parent::__construct($ctrl);
    $this->page_title = "Usuarios";
  }

  public function index() {
    $this->registry->users = User::where();
    $this->render('index'); 
  }
  public function index_json() {
    $dt = new UserDatatable($this->getParameters());
    echo $dt->getJsonData();
  }
  public function add($user=null){    
    if($user == null){
      $params = $this->getParameters('user');
      $user = new User($params);
    }
    $this->registry->user = $user;

    $this->render('add');
  }

  public function create(){
      $data = $this->getData();
      $params = $data['user'];
      $params['username'] = $params['email'];
      $user = new User($params);
      if(Valid::blank($params['password'])){
        $this->flash->addError("La contraseña no puede estar vacía"); 
        return $this->add($user);
      }
      if($params['password'] == $params['repassword']){//validar params
          $user->password = Encriptar::mycrypt($params['password']);
          if ($user->is_valid() && $user->save()){
            $this->flash->addMessage("Se agrego correctamente el usuario");
            $this->renameAction('index');
            if(Security::current_user()->is_admin())
              return $this->index();
            else
              $controller = new AppController($this->ctrl);
              $controller->index();
          }
          else{
            $this->flash->addErrors($user->validation->getErrors()); 
            return $this->add($user);
          }
      }
      else{
        $this->flash->addError("Las contraseñas no coinciden"); 
        return $this->add($user);
      }
  }

  public function edit(){

      $id = $this->getParameters('id');
      $user = User::find($id);
      if ($user){
        $this->registry->user = $user;
        $this->render('edit');
      }
      else{
        $this->flash->addError("Usuario No encontrado"); 
        $this->renameAction('index');
        return $this->index();
      }
  }
  public function update(){
    $data = $this->getData();
      $params = $data['user'];
      $params['username'] = $params['email'];
      $user = User::find($params['id']);
      if($user){
        if($params['password'] == $params['repassword']){//validar params
          if ($params['password'] == '')
            unset($params['password']);
          else
            $params['password'] =  Encriptar::mycrypt($params['password']);
          if ($user->update_attributes($params)){
            $this->flash->addMessage("Se modifico correctamente el usuario");
            $this->renameAction('index');
            return $this->index();
          }
          else{
            $this->flash->addErrors($user->validation->getErrors()); 
            $this->registry->user = $user;
            $this->render('edit');
          }
        }
        else{
          $this->flash->addError("Las contraseñas no coinciden"); 
          $this->registry->user = $user;
          $this->render('edit');
        }
      }
      else{
        $this->flash->addError("Usuario No encontrado"); 
        $this->renameAction('index');
        return $this->index();
      }
  }
  public function delete(){
      $id = $this->getParameters('id');
      $user = User::find($id);
      if ($user){
        $logged = Security::current_user();
        if($logged->id == $user->id){
          $this->flash->addError("No se puede eliminar el usuario logueado"); 
        }
        else{
          $user->delete();
          $this->flash->addErrors($user->validation->getErrors()); 
          $this->registry->user = $user;
        }
      }
      else{
        $this->flash->addError("Usuario No encontrado"); 
      }
      $this->renameAction('index');
      return $this->index();
  }

  public function canExecute($action, $user){
    if ($user != NULL){
      if ($user->role == Role::ROL_ADMIN) return true;
      if (in_array($action, array('add','create'))) return true;
      $user_ref = User::find($this->ctrl->getValue('id'));
      if($user_ref != NULL ){
        $person = $user_ref->person();
        return $person != NULL && $person->created_by == $user->id;
      }

    }
    return false;
  }
}
?>