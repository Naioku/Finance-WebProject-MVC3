<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\MyActive;
use \App\HelpingClasses\Messages\Messages;

/**
 * Add expense controller
 *
 * PHP version 7.4.12
 */
class AddExpense extends Authenticated
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
		
		$this->myActive = new MyActive("addExpense"); // Which page is active in navbar
		
		// Actualization of expense categories and payment methods
		$this->user->getAllUsersExpenseCategories();
		$this->user->getAllUsersPaymentMethods();
    }
	
    /**
     * Show the add expense site
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
     * Add new expense
     *
     * @return void
     */
    public function addNewExpenseAction()
    {
		if($this->user->addExpenseToDatabase($_POST))
		{
			Flash::addMessage($this->messagesClassName::EXPENSE_ADDITION_SUCCESSFULL, "success");	
		}
		else
		{
			Flash::addMessage($this->messagesClassName::EXPENSE_ADDITION_FAIL, "warning");
		}
		
		// To avoid repassing a form.
		// To avoid duplication of add-expense (localhost/add-expense/add-expense/add-new-expense)
		$this->redirect("/add-expense");
    }
	
	/**
     * Check category limit
     *
     * @return void
     */
    public function checkCategoryLimitAction()
    {
		if(isset($_POST['amount']) &&
			isset($_POST['categoryId']) &&
			isset($_POST['dateFrom']) &&
			isset($_POST['dateTo']))
		{
			if($_POST['amount'] != '')
			{
				$this->user->checkIfCategoryLimitExceeded($_POST);
				
				if (empty($this->user->AJAXerrors))
				{
					$filePath = $this->getClassName().'/limitContentCategory.html';
				}
				else
				{
					$filePath = $this->getClassName().'/AJAXerrors.html';
					// errors are printed only here to avoid duplication in checkPaymentMethodLimitAction
				}
				
				// render answer of checking limit
				View::renderTemplate($filePath, [
					'user' => $this->user
				],
				$this->whichLang);
			}
		}
	}
	
	/**
     * Check payment method limit
     *
     * @return void
     */
    public function checkPaymentMethodLimitAction()
    {
		if(isset($_POST['amount']) &&
			isset($_POST['paymentMethodId']) &&
			isset($_POST['dateFrom']) &&
			isset($_POST['dateTo']))
		{			
			if(!empty($_POST['amount']))
			{
				$this->user->checkIfPaymentMethodLimitExceeded($_POST);
				if (empty($this->user->AJAXerrors))
				{
					// render answer of checking limit
					View::renderTemplate($this->getClassName().'/limitContentPaymentMethod.html', [
						'user' => $this->user
					],
					$this->whichLang);
				}
			}
		}
	}
}
