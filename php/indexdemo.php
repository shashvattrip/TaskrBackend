<?php

		/*spl_autoload_register(); // don't load our classes unless we use them

		$mode = 'debug'; // 'debug' or 'production'
		$server = new RestServer($mode);
		// $server->refreshCache(); // uncomment momentarily to clear the cache if classes change in production mode

		$server->addClass('Taskr');

		$server->addClass('ProductsController', '/products'); // adds this as a base to all the URLs in this class

		$server->handle();*/
		$frag =array();
		$frag = explode('/', $_SERVER['REQUEST_URI']);


		include 'class_response.php';

	/*if (preg_match('/\/blog\/[0-9]{4}\/[0-9]{2}\/.+/', $_SERVER['REQUEST_URI'])) 
	{
	    // load blog article here

	    echo ;
	}*/
	/*echo $frag[4];*/
/*
	switch ($frag[4])
	{
		case 'getTasks':
		//RestServer -> getTasks();
		$sss=new RestServer();
		$sss -> getTasks();
		if($frag[5])
		break;

		default :
		echo "Not done";

	}*/

	switch ($_GET['method'])
	{
		case 'getTasks':
		$sss=new RestServer();
		$sss -> getTasks();
		
		break;
	}

?>