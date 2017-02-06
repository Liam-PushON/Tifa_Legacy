<?php

final class Core{

	public static $database, $layout, $style, $template, $theme;
	public static $settings;
	public static $page;


	function init() {
		self::$settings = simplexml_load_file('var/config/settings.xml');

		include_once ('includes.php');
		self::$database = new Database();
		self::$layout = new Layout();
		# self::$template = new Template();
		self::$theme = new Theme();
		self::$style = new Style();

		self::handleURI();

	}

	function handleURI(){
		$URI = strtolower($_SERVER['REQUEST_URI']);
		$URI = explode('?', $URI)[0];
		if($URI == '/admin'){
			self::$page = 'admin';
		}else if($URI == '/' || $URI == '/home'){
			self::$page = 'home';
		}else{
			self::$page = '404';
		}
	}

}

?>