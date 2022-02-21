<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\MyActive;
use \App\HelpingClasses\Messages\Messages;

/**
 * Add income controller
 *
 * PHP version 7.4.12
 */
class AddIncome extends Authenticated
{
	public $user;
	public $myActive;
	
	/**
     * Before filter - called before each action method
     *
     * @return void
     */
    protected function before()
    {
        parent::before();
		
		$this->myActive = new MyActive("addIncome"); // Which page is active in navbar
		
		// Actualization of income categories
		$this->user->getAllUsersIncomeCategories();
    }
	
    /**
     * Show the add income site
     *
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate($this->getClassName().'/new.html', [
            'user' => $this->user,
			'myActive' => $this->myActive,
			'classNameHypens' => $this->classNameHypens
        ],
		$this->whichLang);
    }
	
	/**
     * Check category limit
     *
     * @return void
     */
    public function checkIncomeAction()
    {
		if(isset($_POST['amount']))
		{
			if($_POST['amount'] != '')
			{
				$this->user->validateIncome($_POST);
				
				if (!empty($this->user->AJAXerrors))
				{
					// render AJAX answer
					View::renderTemplate($this->getClassName().'/AJAXerrors.html', [
						'user' => $this->user
					],
					$this->whichLang);
				}
				
			}
		}
	}
	
	/**
     * Add new income
     *
     * @return void
     */
    public function addNewIncomeAction()
    {
		if($this->user->addIncomeToDatabase($_POST))
		{
			Flash::addMessage($this->messagesClassName::INCOME_ADDITION_SUCCESSFULL, "success");
		}
		else
		{
			Flash::addMessage($this->messagesClassName::INCOME_ADDITION_FAIL, "warning");
		}
		
		// To avoid repassing a form.
		// To avoid duplication of add-income (localhost/add-income/add-income/add-new-income)
        $this->redirect("/add-income");
		
    }
}
