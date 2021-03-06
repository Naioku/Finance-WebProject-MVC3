<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;

/**
 * Home controller
 *
 * PHP version 7.4.12
 */
 
class Home extends \Core\Controller
{
	/**
	 * Show the index page
	 *
	 * return @void
	 */
	public function indexAction()
	{
		View::renderTemplate($this->getClassName().'/index.html', [
			'classNameHypens' => $this->classNameHypens
		],
		$this->whichLang);
	}
}

?>