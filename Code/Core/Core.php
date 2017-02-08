<?php

final class Tifa{
	public static $database, $layout, $style, $theme;
	public static $settings;
	public static $page;

	static function init() {
		self::$settings = simplexml_load_file('config/settings.xml');

		include_once ('includes.php');

		self::$database = new Database();
		self::$layout = new Layout();
		self::$theme = new Theme();
		self::$style = new Style();

		self::handleURI();
	}

	static function handleURI(){
		$URI = strtolower($_SERVER['REQUEST_URI']);
		$URI = explode('?', $URI)[0];
		if($URI == '/admin'){
			self::$page = 'admin';
		}else if($URI == '/' || $URI == '/home'){
			self::$page = 'home';
		}else if(preg_match('(/post/[0-9])', $URI)){
			self::$page = 'post';
		}else if($URI == '/signup' || $URI == '/sign-up'){
			self::$page = 'sign-up';
		}else{
			self::$page = '404';
		}
	}

	static function log($msg, $file, $overwrite = false){
		$log = null;
		if(!$overwrite){
			$log = fopen('var/log/'.$file , 'a');
		}else{
			$log = fopen('var/log/'.$file , 'w');
		}
		fwrite($log, $msg."\n");
		fclose($log);
	}
}

?>