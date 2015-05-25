<?php
class Role{
    const ROL_ADMIN = 1;
    const ROL_DAIRY = 2;
    const ROL_VETERINARY = 3;

    public static $roles = array(self::ROL_VETERINARY => "Veterinario", self::ROL_ADMIN => "Administrador", self::ROL_DAIRY => "Tambero");
}
?>