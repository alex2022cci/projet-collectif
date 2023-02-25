<?php
class Router
{
	static $routes = array(); 
	static $prefixes = array(); 

	/**
	* Ajoute un prefix au Routing
	**/
	static function prefix($url,$prefix)
	{
		self::$prefixes[$url] = $prefix; 
	}

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

	static function connect($redir,$url){
		$r = array();
		$r['params'] = array();
		$r['url'] = $url;  

		$r['originreg'] = preg_replace('/([a-z0-9]+):([^\/]+)/','${1}:(?P<${1}>${2})',$url);
		$r['originreg'] = str_replace('/*','(?P<args>/?.*)',$r['originreg']);
		$r['originreg'] = '/^'.str_replace('/','\/',$r['originreg']).'$/'; 
		// MODIF
		$r['origin'] = preg_replace('/([a-z0-9]+):([^\/]+)/',':${1}:',$url);
		$r['origin'] = str_replace('/*',':args:',$r['origin']); 

		$params = explode('/',$url);
		foreach($params as $k=>$v){
			if(strpos($v,':')){
				$p = explode(':',$v);
				$r['params'][$p[0]] = $p[1]; 
			}
		} 

		$r['redirreg'] = $redir;
		$r['redirreg'] = str_replace('/*','(?P<args>/?.*)',$r['redirreg']);
		foreach($r['params'] as $k=>$v){
			$r['redirreg'] = str_replace(":$k","(?P<$k>$v)",$r['redirreg']);
		}
		$r['redirreg'] = '/^'.str_replace('/','\/',$r['redirreg']).'$/';

		$r['redir'] = preg_replace('/:([a-z0-9]+)/',':${1}:',$redir);
		$r['redir'] = str_replace('/*',':args:',$r['redir']); 

		self::$routes[] = $r; 
	}

	static function url($url = '')
	{
		trim($url,'/'); 
		foreach(self::$routes as $v){
			if(preg_match($v['originreg'],$url,$match)){
				$url = $v['redir']; 
				foreach($match as $k=>$w){
					$url = str_replace(":$k:",$w,$url); 
				}
			}
		}
		foreach(self::$prefixes as $k=>$v){
			if(strpos($url,$v) === 0){
				$url = str_replace($v,$k,$url); 
			}
		}
		return BASE_URL.'/'.$url; 
	}

	static function webroot($url)
	{
		trim($url,'/');
		return BASE_URL.'/'.$url; 
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
