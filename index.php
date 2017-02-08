<?php
include_once ('Code/Core/Core.php');
if(!file_exists('config/install.lock')){
	include_once ('install.php');
	die();
}

Tifa::init();

if(Tifa::$page == 'home'){
	Tifa::$layout->build('homepage.xml');
}else if(Tifa::$page == '404'){
	Tifa::$layout->build('404.xml');
}else if(Tifa::$page == 'admin'){
	Tifa::$layout->build('admin.xml');
}else if(Tifa::$page == 'post'){
	Tifa::$layout->build('post.xml');
}else if(Tifa::$page == 'sign-up'){
	Tifa::$layout->build('signup.xml');
}else{
	echo Tifa::$page.' :No Page Found<br>';
	Tifa::$layout->build('404.xml');
}

?>