<?php
class Logger {
	public final static function info($text){
        global $config;
        $app = $config->getValue('application');
        $file = $app['log'];
        $newtext = $text;
		error_log($newtext,0,$file);
    }
}
?>