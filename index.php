<?php
include_once ('Code/Core/Core.php');
Core::log('Start: '.$start = microtime (true), 'speed.log');


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


Core::log('End: '.$end = microtime (true), 'speed.log');
Core::log('Elapsed: '.$elapsed = ($end - $start), 'speed.log');
Core::log('A log has been created', 'create.log');
?>