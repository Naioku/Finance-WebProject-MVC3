<?php

namespace Core;

/**
 * Router
 *
 * PHP version 7.4.12
 */
 
class Router
{
	protected $routes = [];
	protected $parameters = [];
	
	/**
	 * $route = string
	 * $parameters = array
	 */
	public function add($route, $parameters = [])
	{
		// Convert the route to the regular expression: escape foward slashes
		$route = preg_replace('/\//', '\\/', $route);
		
		// Convert variables e.g. {controller}
		$route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
		
		// Convert variables with custom regular expressions e.g. {id:\d+}
		$route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
		
		// Add start and end delimiters, and case insensitive flag
		$route = '/^'.$route.'$/i';
		
		$this->routes[$route] = $parameters;
	}
	
	public function getRoutes()
	{
		return $this->routes;
	}
	
	public function match($url)
	{
		// Match to the fixed URL format /controller/action
		//$reg_exp = "/^(?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)$/";
		foreach ($this->routes as $route => $parameters)
		{
			if (preg_match($route, $url, $matches))
			{
				// Get named capture group values
				
				foreach ($matches as $key => $match)
				{
					if (is_string($key))
					{
						$parameters[$key] = $match;
					}
				}

				$this->parameters = $parameters;
				return true;
			}
		}
		
		return false;
	}
	
	public function getParameters()
	{
		return $this->parameters;
	}
	
	/**
	 * Dispatch the route, creating the controller object and running the
	 * action method
	 *
	 * @param string $url The route URL
	 *
	 * @return void
	 */
	public function dispatch($url)
	{
		$url = $this->removeQueryStringVariables($url);
		if ($this->match($url))
		{
			$controllerName = $this->parameters['controller'];
			$controllerName = $this->convertToStudlyCaps($controllerName);
			$controllerName = $this->getNamespace().$controllerName;
			
			
			if (class_exists($controllerName))
			{
				$controllerObject = new $controllerName($this->parameters);
				
				$actionName = $this->parameters['action'];
				$actionName = $this->convertToCamelCase($actionName);
				
				if(preg_match('/action$/i', $actionName) == 0)
				{
					$controllerObject->$actionName();
				}
				else
				{
					throw new \Exception("Method $actionName in controller $controllerName not found");
				}
			}
			else
			{
				throw new \Exception("Controller class $controllerName not found");
			}
		}
		else
		{
			throw new \Exception('No route matched.', 404);
		}
	}
	 
	/**
	 * Convert the string with hyphens to StudlyCaps,
	 * e.g. post-authors => PostAuthors
	 *
	 * @param string $string The string to convert
	 *
	 * @return string
	*/
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Convert the string with hyphens to camelCase,
     * e.g. add-new => addNew
     *
     * @param string $string The string to convert
     *
     * @return string
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }
	
	/**
     * Remove the query string variables from the URL (if any). As the full
     * query string is used for the route, any variables at the end will need
     * to be removed before the route is matched to the routing table. For
     * example:
     *
     *   URL                           $_SERVER['QUERY_STRING']  Route
     *   -------------------------------------------------------------------
     *   localhost                     ''                        ''
     *   localhost/?                   ''                        ''
     *   localhost/?page=1             page=1                    ''
     *   localhost/posts?page=1        posts&page=1              posts
     *   localhost/posts/index         posts/index               posts/index
     *   localhost/posts/index?page=1  posts/index&page=1        posts/index
     *
     * A URL of the format localhost/?page (one variable name, no value) won't
     * work however. (NB. The .htaccess file converts the first ? to a & when
     * it's passed through to the $_SERVER variable).
     *
     * @param string $url The full URL
     *
     * @return string The URL with the query string variables removed
     */
    protected function removeQueryStringVariables($url)
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);

            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return $url;
    }
	
	/**
     * Get the namespace for the controller class. The namespace defined in the
     * route parameters is added if present.
     *
     * @return string The request URL
     */
    protected function getNamespace()
    {
        $namespace = 'App\Controllers\\';

        if (array_key_exists('namespace', $this->parameters)) {
            $namespace .= $this->parameters['namespace'] . '\\';
        }

        return $namespace;
    }
}

?>