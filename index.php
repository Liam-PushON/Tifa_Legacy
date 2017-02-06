<?php
include_once ('Code/Core/Core.php');

Core::init();

if(Core::$page == 'home'){
	Core::$layout->build('homepage.xml');
}else if(Core::$page == '404'){
	Core::$layout->build('404.xml');
}else if(Core::$page == 'admin'){
	Core::$layout->build('admin.xml');
}else{
	echo Core::$page.'<br>';
	Core::$layout->build('homepage.xml');
}
?>