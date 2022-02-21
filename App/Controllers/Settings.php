<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\MyActive;
use \App\Flash;
use \App\HelpingClasses\Messages\Messages;

/**
 * Settings controller
 *
 * PHP version 7.4.12
 */
class Settings extends Authenticated
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
		
		$this->myActive = new MyActive("settings"); // Which page is active in navbar
		
		// Actualization of income and expense categories and payment methods
		$this->user->getAllUsersIncomeCategories();
		$this->user->getAllUsersExpenseCategories();
		$this->user->getAllUsersPaymentMethods();
    }
	
    /**
     * Show the settings site
     *
     * @return void
     */
    public function showAction()
    {
        View::renderTemplate($this->getClassName().'/show.html', [
            'user' => $this->user,
            'myActive' => $this->myActive,
			'classNameHypens' => $this->classNameHypens
        ],
		$this->whichLang);
    }
	
	/**
     * Update the profile
     *
     * @return void
     */
    public function updateUserDataAction()
    {
        if ($this->user->updateProfile($_POST))
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/settings/show');
        }
		else
		{
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
    }
	
	/**
     * Update the user's name
     *
     * @return void
     */
    public function updateUserNameAction()
	{
		 if ($this->user->updateUserName($_POST))
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/settings/show');
        }
		else
		{
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
	}
	
	/**
     * Update the user's last name
     *
     * @return void
     */
    public function updateUserLastNameAction()
	{
		 if ($this->user->updateUserLastName($_POST))
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/settings/show');
        }
		else
		{
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
	}
	
	/**
     * Update the user's login
     *
     * @return void
     */
    public function updateUserLoginAction()
	{
		 if ($this->user->updateUserLogin($_POST))
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/settings/show');
        }
		else
		{
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
	}
	
	/**
     * Update the user's password
     *
     * @return void
     */
    public function updateUserPasswordAction()
	{
		 if ($this->user->updateUserPassword($_POST))
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/settings/show');
        }
		else
		{
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
	}
	
	/**
     * Update the user's email
     *
     * @return void
     */
    public function updateUserEmailAction()
	{
		 if ($this->user->updateUserEmail($_POST))
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/settings/show');
        }
		else
		{
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
	}
	
	/**
     * Delete user last name
     *
     * @return void
     */
    public function deleteUserLastNameAction()
    {
        if ($this->user->deleteUserLastName())
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/settings/show');
        }
		else
		{
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
    }
	
	/**
     * Delete user login
     *
     * @return void
     */
    public function deleteUserLoginAction()
    {
        if ($this->user->deleteUserLogin())
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/settings/show');
        }
		else
		{
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
    }
	
	/**
     * Update name of income category
     *
     * @return void
     */
    public function updateIncomeCategoryAction()
    {
        if ($this->user->updateIncomeCategory($_POST))
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/settings/show');
        }
		else
		{
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
    }
	
	/**
     * Add new name of income category
     *
     * @return void
     */
    public function addNewIncomeCategoryAction()
    {
        if ($this->user->addNewIncomeCategory($_POST))
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/settings/show');
        }
		else
		{
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
    }
	
	/**
     * Delete income category
     *
     * @return void
     */
    public function deleteIncomeCategoryAction()
    {
        if ($this->user->deleteIncomeCategory($_POST))
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/settings/show');
        }
		else
		{
			Flash::addMessage($this->messagesClassName::GENERAL_ERROR);
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
    }
	
	/**
     * Update data of expense category
     *
     * @return void
     */
    public function updateExpenseCategoryAction()
    {
        if ($this->user->updateExpenseCategory($_POST))
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/settings/show');
        }
		else
		{
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
    }
	
	/**
     * Add new expense category
     *
     * @return void
     */
    public function addNewExpenseCategoryAction()
    {
        if ($this->user->addNewExpenseCategory($_POST))
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/settings/show');
        }
		else
		{
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
    }
	
	/**
     * Delete expense category
     *
     * @return void
     */
    public function deleteExpenseCategoryAction()
    {
        if ($this->user->deleteExpenseCategory($_POST))
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/settings/show');
        }
		else
		{
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
    }
	
	/**
     * Update data of payment method
     *
     * @return void
     */
    public function updatePaymentMethodAction()
    {
        if ($this->user->updatePaymentMethod($_POST))
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/settings/show');
        }
		else
		{
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
    }
	
	/**
     * Add payment method
     *
     * @return void
     */
    public function addNewPaymentMethodAction()
    {
        if ($this->user->addNewPaymentMethod($_POST))
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/settings/show');
        }
		else
		{
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
    }
	
	/**
     * Delete payment method
     *
     * @return void
     */
    public function deletePaymentMethodAction()
    {
        if ($this->user->deletePaymentMethod($_POST))
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/settings/show');
        }
		else
		{
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
    }
	
	/**
     * Update the profile with AJAX
     *
     * @return void
     */
    public function fastUpdateAction() {
	
		if ($this->user->updateProfile($_POST))
		{
			echo $this->user->name;
		}
		else
		{
			$this->findByID($this->user->id);
		}
	
	}
}
