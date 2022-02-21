<?php

namespace App\Controllers;

use \Core\View;


/**
 * Main menu controller
 *
 * PHP version 7.4.12
 */
class MainMenu extends Authenticated
{
	public $user;
	
	/**
     * Before filter - called before each action method
     *
     * @return void
     */
    protected function before()
    {
        parent::before();
    }
	
    /**
     * Show the main menu site
     *
     * @return void
     */
    public function showAction()
    {
        View::renderTemplate($this->getClassName().'/show.html', [
            'user' => $this->user,
			'classNameHypens' => $this->classNameHypens
        ],
		$this->whichLang);
    }
}
