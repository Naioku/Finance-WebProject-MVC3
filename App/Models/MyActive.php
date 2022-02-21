<?php

namespace App\Models;

/**
 * MyActive model - to properly displaying of active bars in navigation bar.
 * 
 * PHP version 7.4.12
 */
class MyActive extends \Core\Model
{
	public $mainMenu;
	public $addIncome;
	public $addExpense;
	public $viewBalance;
	public $settings;
	
	public function __construct($whichSite)
	{
		if ($whichSite == "mainMenu")
		{
			$this->mainMenu = "myActive";
		}
		elseif ($whichSite == "addIncome")
		{
			$this->addIncome = "myActive";
		}
		elseif ($whichSite == "addExpense")
		{
			$this->addExpense = "myActive";
		}
		elseif ($whichSite == "viewBalance")
		{
			$this->viewBalance = "myActive";
		}
		elseif ($whichSite == "settings")
		{
			$this->settings = "myActive";
		}
	}
}