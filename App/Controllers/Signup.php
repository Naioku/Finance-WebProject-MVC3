<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

/**
 * Sign up controller
 *
 * PHP version 7.4.12
 */
class Signup extends \Core\Controller
{
	/**
	 * Show the sign up page
	 *
	 * @return void
	 */
	public function newAction()
	{
		View::renderTemplate($this->getClassName().'/new.html', [
			'classNameHypens' => $this->classNameHypens
		],
		$this->whichLang);
	}
	
	/**
	 * Sign up a new user
	 *
	 * @return void
	 */
	public function createAction()
	{
		$_POST['gRecaptchaResponse'] = $_POST['g-recaptcha-response'];
		unset($_POST['g-recaptcha-response']);
		$user = new User($_POST);
		$user->setLang($this->whichLang);
		$user->setKindOfUser(); // user->whichLang must be set before
		
		if($user->save())
		{
			$user->copyIncomes();
			$user->copyExpenses();
			$user->copyPaymentMethods();
			
			$user->sendActivationEmail();
			
			$this->redirect('/signup/success'); // To avoid repassing a form.
		}
		else
		{
			View::renderTemplate($this->getClassName().'/new.html', [
				'user' => $user,
				'classNameHypens' => $this->classNameHypens
			],
			$this->whichLang);
		}
	}
	
	/**
	 * Show the sign up success page.
	 *
	 * @return void
	 */
	public function successAction()
	{
		View::renderTemplate($this->getClassName().'/success.html', [
			'classNameHypens' => $this->classNameHypens
		],
		$this->whichLang);
	}
	
	/**
     * Activate a new account
     *
     * @return void
     */
    public function activateAction()
    {
        User::activate($this->routeParameters['token']);

        $this->redirect('/signup/activated');        
    }
	
	/**
     * Show the activation success page
     *
     * @return void
     */
    public function activatedAction()
    {
        View::renderTemplate($this->getClassName().'/activated.html', [
			'classNameHypens' => $this->classNameHypens
		],
		$this->whichLang);
    }
}

?>