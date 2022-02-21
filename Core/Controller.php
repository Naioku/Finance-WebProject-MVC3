<?php

namespace Core;

use \App\Auth;
use \App\Flash;
use \App\HelpingClasses\Messages\Messages;

/**
 * Base controller
 *
 * PHP version 7.4.12
 */
abstract class Controller
{
	/**
	 * Parameters from the matched route
	 * @var array
	 */
	protected $routeParameters = [];
	
	protected $messagesClassName;
	
	protected $classNameHypens;
	
	protected $whichLang;
	protected $viewsFolder;
	
	/**
	 * Class constructor
	 *
	 * @param array routeParameters - Parameter from the route
	 *
	 * @return void
	 */
	public function __construct($routeParameters)
	{
		$this->routeParameters = $routeParameters;
		if (!isset($_SESSION['whichLang'])) $_SESSION['whichLang'] = 'EN';
			
		$this->whichLang = $_SESSION['whichLang'];
	}
	
	/**
     * Magic method called when a non-existent or inaccessible method is
     * called on an object of this class. Used to execute before and after
     * filter methods on action methods. Action methods need to be named
     * with an "Action" suffix, e.g. indexAction, showAction etc.
     *
     * @param string $name  Method name
     * @param array $args Arguments passed to the method
     *
     * @return void
     */
	public function __call($name, $args)
	{
		$method = $name.'Action';
		
		if (method_exists($this, $method))
		{
			if ($this->before() !== false)
			{
				call_user_func_array([$this, $method], $args);
				$this->after();
			}
		}
		else
		{
			throw new \Exception("Method $method not found in controller ".get_class($this));
		}
	}
	
	protected function getClassName()
	{
		$className = get_class($this);
		if ($pos = strrpos($className, '\\')) return substr($className, $pos + 1);
		return $pos;
	}
	
	/**
     * Convert the string with StudlyCaps to hyphens.
	 *
	 * AddExpense => add-expense
     *
     * @return string - class name separated by '-'
     */
	protected function getClassNameSeparatedByDash()
	{
		$className = $this->getClassName();
		$classNameHypens = '';
		$array = str_split($className);
		
		foreach ($array as $index => $char)
		{
			if ($index ==! 0 && ctype_upper($char))
			{
				
				$classNameHypens = lcfirst($classNameHypens);
				$classNameHypens .= '-';
				$char = lcfirst($char);
			}
			
			$classNameHypens .= $char;
		}
		$this->classNameHypens = $classNameHypens;
	}
	
	/**
     * Convert the string with camelCase to hyphens.
	 *
	 * showPage => show-page
     *
     * @return string - class name separated by '-'
     */
	protected function getMethodNameSeparatedByDash()
	{
		$className = $this->getClassName();
		$classNameHypens = '';
		$array = str_split($className);
		
		foreach ($array as $index => $char)
		{
			if ($index ==! 0 && ctype_upper($char))
			{
				
				$classNameHypens = lcfirst($classNameHypens);
				$classNameHypens .= '-';
				$char = lcfirst($char);
			}
			
			$classNameHypens .= $char;
		}
		$this->classNameHypens = $classNameHypens;
	}
	
	/**
     * Before filter - called before an action method.
     *
     * @return void
     */
	protected function before()
	{
		$this->getClassNameSeparatedByDash();
		$this->messagesClassName = Messages::setSpecialClassesNames($this->whichLang);
		if (Auth::getUser())
		{
			$this->redirect("/main-menu");
		}
	}
	
	/**
     * After filter - called after an action method.
     *
     * @return void
     */
	protected function after()
	{}
	
	public function changeLangAjaxAction()
	{
		$_SESSION['whichLang'] = $_POST['whichLang'];
	}
	
	/**
     * Redirect to a different page
     *
     * @param string $url  The relative URL
     *
     * @return void
     */
    public function redirect($url)
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $url, true, 303);
        exit;
    }
	
	public function requireLogin()
	{
		if (! Auth::getUser())
		{
			Flash::addMessage($this->messagesClassName::LOGIN_REQUIRED, Flash::INFO);
			
			Auth::rememberRequestedPage();
			
            $this->redirect('/login');
        }
	}
}

?>