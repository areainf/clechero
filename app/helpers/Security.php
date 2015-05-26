<?php 
require_once "Role.php";
class Security {
	  public static function current_user(){
        $user = isset($_SESSION['user']) ? unserialize($_SESSION['user']) : NULL;
        return $user;
    }
    public static function is_logged(){
      $user = isset($_SESSION['user']) ? $_SESSION['user'] : NULL;
      return $user != NULL;
    }
    public static function login($user){
        $_SESSION['user'] = serialize($user);
        return $user;
    }
    public static function logout(){
        unset($_SESSION['user']);
    }
    public static function is_admin(){
      $user = static::current_user();
      return $user != NULL && $user->role == Role::ROL_ADMIN;
    }
    public static function is_dairy($user=null){
      if(!$user)
        $user = static::current_user();
      return $user != NULL && $user->role == Role::ROL_DAIRY;
    }
    public static function is_veterinary($user=null){
      if(!$user)
        $user = static::current_user();
      return $user != NULL && $user->role == Role::ROL_VETERINARY;
    }
    public static function user_id(){
      $user = static::current_user();
      return $user != NULL ? $user->id : NULL;
    }

    public static function set_dairy($dairy){
        $_SESSION['dairy'] = serialize($dairy);
        return $dairy;
    }
    public static function destroy_dairy(){
        unset($_SESSION['dairy']);
    }
    public static function current_dairy(){
        $dairy = isset($_SESSION['dairy']) ? unserialize($_SESSION['dairy']) : NULL;
        return $dairy;
    }
}

?>