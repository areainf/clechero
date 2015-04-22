<?php
/*
 * @author Mauro Marozzi
 */
class Encriptar {
    //put your code here
    const salt="2bbe0c26f6b26d7058aaa0c82bc034df";
    public static function mycrypt($password){
        //return  password_hash($password, PASSWORD_DEFAULT);
        return crypt($password,self::salt);
    }
    public static function verificar($pass_plain,$pass_hash){
		$pwd = crypt($pass_plain,self::salt);
		return $pwd != null && $pwd==$pass_hash;
		//return password_verify($pass_plain,$pass_hash);
    }
}
