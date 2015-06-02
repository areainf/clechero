<?php
class Role{
    const ROL_ADMIN = 1;
    const ROL_DAIRY = 2;
    const ROL_VETERINARY = 3;

    public static $roles = array(self::ROL_VETERINARY => "Veterinario", self::ROL_ADMIN => "Administrador", self::ROL_DAIRY => "Tambero");

    public static function createRoles($user){
      var_dump($user);
      if($user->is_dairy()){
        return array(self::ROL_VETERINARY => "Veterinario");
      }
      elseif ($user->is_veterinary()) {
        return array(self::ROL_DAIRY => "Propietario");
      }
      elseif ($user->is_admin()) {
        return self::$roles;
      }
      return array();
    }
}
?>