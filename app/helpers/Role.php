<?php
class Role{
    const ROL_ADMIN = 1;
    const ROL_DAIRY = 2;

    public static $roles = array(self::ROL_ADMIN => "Administrador", self::ROL_DAIRY => "Tambero");
}
?>