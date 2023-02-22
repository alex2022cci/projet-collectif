<?php
class Router
{

	/**
	 * Permet de parser une url
	 * @param $url Url à parser
	 * @return tableau contenant les paramètres
	 **/
	static function parse($url, $request)
	{
		$url = trim($url, '/');
		$params = explode('/', $url);
		$request->controller = $params[0];
		$request->action = isset($params[1]) ? $params[1] : 'index';
		$request->params = array_slice($params, 2);
		return true;
	}

	static function filename($url)
	{
		// fonction basename() pour récupérer le nom du fichier
		$filename = basename($url);
		// fonction pathinfo() pour récupérer l'extension du fichier
		$file_extension = pathinfo($filename, PATHINFO_EXTENSION);
		// fonction str_replace() pour enlever l'extension du nom du fichier
		$file_name_without_extension = str_replace('.' . $file_extension, '', $filename);
		// retourne le nom du fichier sans extension avec une majuscule
		return $file_name_without_extension;
	}
}
