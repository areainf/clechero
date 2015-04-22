<?php
class Logger {
	public final static function info($text){
        global $config;
        $file = $config->getValue('application')['log'];
        $newtext = $text;
		error_log($newtext,0,$file);
    }
}
?>