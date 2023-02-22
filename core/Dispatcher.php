<?php


/**
 * Dispatcher
 * Permet de charger le controller en fonction de la requête utilisateur
 **/
class Dispatcher 
{

	var $request;	// Object Reques

	/**
	 * Fonction principale du dispatcher
	 * Charge le controller en fonction du routing
	 **/
	function __construct()
	{
		$this->request = new Request();
		Router::parse($this->request->url, $this->request);
		$controller = $this->loadController();
		if (!in_array($this->request->action, get_class_methods($controller))) {
			$this->error('Le controller ' . $this->request->controller . ' n\'a pas de méthode ' . $this->request->action);
		}
		call_user_func_array(array($controller, $this->request->action), $this->request->params);
		$controller->render($this->request->action);
	}

	/**
	 * Permet de générer une page d'erreur en cas de problème au niveau du routing (page inexistante)
	 **/
	function error($message)
	{
		header("HTTP/1.0 404 Not Found");
		$controller = new Controller($this->request);
		$controller->set('message', $message);
		$controller->render('/errors/404');
		die();
	}

	/**
	 * Permet de charger le controller en fonction de la requête utilisateur
	 **/
	function loadController()
	{
		$name = ucfirst($this->request->controller) . 'Controller';
		$file = ROOT . DS . 'controller' . DS . $name . '.php';
		require $file;
		return new $name($this->request);
	}
}
