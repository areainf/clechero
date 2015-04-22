<?php 
class Flash{
	protected $errors;
	protected $messages;
	protected $alerts;

  function __construct() {
  	$this->errors = array();
    $this->messages = array();
    $this->alerts = array();
	}

  function add($string, $repo="error"){
    switch ($repo) {
      case 'message':
        $this->messages[] = $string;
        break;
      case 'alert':
        $this->alerts[] = $string;
        break;
      default:
        $this->errors[] = $string;
        break;
    }
  }
  function isEmpty(){
    return count($this->errors) ==  0 &&
           count($this->messages) ==  0 &&
           count($this->alerts) ==  0;
  }
  function hasErrors(){
    return count($this->errors) !=  0;
  }
  
  function hasMessages(){
    return count($this->messages) !=  0;
  }
  
  function hasAlerts(){
    return count($this->alerts) !=  0;
  }

  function getErrors(){
    return $this->errors;
  }
  
  function getMessages(){
    return $this->messages;
  }
  
  function getAlerts(){
    return $this->alerts;
  }
  function addMessage($message){
    $this->add($message, 'message');
  }
  function addError($error, $key=null){
    if ($key == null)
      $this->errors[] = $error;
    else
      $this->errors[$key] = $error;
  }
  function addErrors($errors){
    //array_merge($this->errors, $errors);
    //tengo lio con clave valor cuando ocurre mas de un error con misma clave
    foreach ($errors as $key => $value) {
      if (is_int($key))
        $this->addError($value);
      else
        $this->addError($value,$key);
    }
  }

}
?>