<?php
include_once ('Code/Core/Core.php');

Core::init();

if(Core::$page == 'home'){
	Core::$layout->build('homepage.xml');
}else if(Core::$page == '404'){
	Core::$layout->build('404.xml');
}else if(Core::$page == 'admin'){
	Core::$layout->build('admin.xml');
}else if(Core::$page == 'post'){
	Core::$layout->build('post.xml');
}else{
	echo Core::$page.' :No Page Found<br>';
	Core::$layout->build('404.xml');
}

?>