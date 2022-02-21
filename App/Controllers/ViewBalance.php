<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\MyActive;
use \App\Flash;
use \App\HelpingClasses\Messages\Messages;

/**
 * View balance controller
 *
 * PHP version 7.4.12
 */
class ViewBalance extends Authenticated
{
	public $user;
	public $myActive;
	//private $dateFrom;
	//private $dateTo;
	
	/**
     * Before filter - called before each action method
     *
     * @return void
     */
    protected function before()
    {
        parent::before();
		
		$this->myActive = new MyActive("viewBalance");
		
		// Actualization of expense, income categories and payment methods
		$this->user->getAllUsersIncomeCategories();
		$this->user->getAllUsersExpenseCategories();
		$this->user->getAllUsersPaymentMethods();
		
		if(isset($_POST['dateFrom']) && isset($_POST['dateTo']))
		{
			$this->user->getIncomesAndExpensesAndBalanceBetweenProvidedDates($_POST['dateFrom'], $_POST['dateTo']);
		}
		else
		{
			$this->user->getIncomesAndExpensesAndBalanceBetweenProvidedDates();
		}
    }
	
	private function getDateFromIfIsSet($data)
	{
		return isset($data['dateFrom']) ? $data['dateFrom'] : date('Y-m-01');
	}
	
	private function getDateToIfIsSet($data)
	{
		return isset($data['dateTo']) ? $data['dateTo'] : date('Y-m-t');
	}
	
    /**
     * Show the view balance site
     *
     * @return void
     */
    public function showAction()
    {
		//exit($this->getDateFromIfIsSet($_POST['dateFrom']));
        View::renderTemplate($this->getClassName().'/show.html', [
            'user' => $this->user,
			'myActive' => $this->myActive,
			'classNameHypens' => $this->classNameHypens,
			'dateFrom' => $this->getDateFromIfIsSet($_POST),
			'dateTo' => $this->getDateToIfIsSet($_POST)
        ],
		$this->whichLang);
    }
	
	/**
     * Delete income
     *
     * @return void
     */
    public function deleteIncomeAction()
    {
         if ($this->user->deleteIncome($_POST))
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/view-balance/show');
        }
		else
		{
			Flash::addMessage($this->messagesClassName::GENEREAL_ERROR);
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
    }
	
	/**
     * Delete several income
     *
     * @return void
     */
    public function multiDeleteIncomeAction()
    {
		$result = [];
		
		foreach ($_POST as $id => $recordId)
		{
			$data = array('recordId' => $id);
			if (!$this->user->deleteIncome($data))
			{
				$result[] = $id;
			}
		}
		
		if (empty($result))
		{
			Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

			$this->redirect('/view-balance/show');
		}
		else
		{
			Flash::addMessage($this->messagesClassName::GENEREAL_ERROR);
			View::renderTemplate($this->getClassName().'/show.html', [
				'user' => $this->user,
				'classNameHypens' => $this->classNameHypens,
				'multiDeleteIncomeErrArr' => $result
			],
			$this->whichLang);
		}
    }
	
	/**
     * Delete expense
     *
     * @return void
     */
    public function deleteExpenseAction()
    {
         if ($this->user->deleteExpense($_POST))
		{
            Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

            $this->redirect('/view-balance/show');
        }
		else
		{
			Flash::addMessage($this->messagesClassName::GENEREAL_ERROR);
            View::renderTemplate($this->getClassName().'/show.html', [
                'user' => $this->user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
    }
	
	/**
     * Delete several expense
     *
     * @return void
     */
    public function multiDeleteExpenseAction()
    {
		$result = [];
		
		foreach ($_POST as $id => $recordId)
		{
			$data = array('recordId' => $id);
			if (!$this->user->deleteExpense($data))
			{
				$result[] = $id;
			}
		}
		
		if (empty($result))
		{
			Flash::addMessage($this->messagesClassName::CHANGES_SAVED);

			$this->redirect('/view-balance/show');
		}
		else
		{
			Flash::addMessage($this->messagesClassName::GENEREAL_ERROR);
			View::renderTemplate($this->getClassName().'/show.html', [
				'user' => $this->user,
				'classNameHypens' => $this->classNameHypens,
				'multiDeleteExpenseErrArr' => $result
			],
			$this->whichLang);
		}
    }
	
	/**
     * Get grouped expenses
     *
     * @return void
     */
    public function getGroupedExpensesAction()
    {
		
		return $this->user->groupedExpensesArr;
	}
	
	/**
     * Update income
     *
     * @return void
     */
    public function updateIncomeAction()
    {
		if ($this->user->updateIncome($_POST))
		{
			Flash::addMessage($this->messagesClassName::CHANGES_SAVED);
		}
		else
		{
			Flash::addMessage($this->messagesClassName::GENEREAL_ERROR);
		}
		
		// To avoid repassing a form.
		// To avoid this localhost/view-balance/add-income/check-income in AJAX post url
		$this->redirect('/view-balance');
	}
	
	/**
     * Update expense
     *
     * @return void
     */
    public function updateExpenseAction()
    {
		if ($this->user->updateExpense($_POST))
		{
			Flash::addMessage($this->messagesClassName::CHANGES_SAVED);
		}
		else
		{
			Flash::addMessage($this->messagesClassName::GENEREAL_ERROR);
		}
		
		// To avoid repassing a form.
		// To avoid this localhost/view-balance/add-expense/add-new-expense in AJAX post url
		$this->redirect('/view-balance');
	}
}
