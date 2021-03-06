<?php

namespace App\Controllers;

use \Core\View;
use App\Models\User;
use App\Auth;
use App\Flash;
use \App\HelpingClasses\Messages\Messages;

/**
 * Login controller
 *
 * PHP version 7.4.12
 */
class Login extends \Core\Controller
{
    /**
     * Show the login page
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
     * Log in a user
     *
     * @return void
     */
	public function createAction()
    {
        $user = User::authenticate($_POST['email'], $_POST['password']);
		
		$remember_me = isset($_POST['remember_me']);

        if ($user)
		{
			Auth::login($user, $remember_me);
			
			Flash::addMessage($this->messagesClassName::LOGIN_SUCCESSFULL);
			
            $this->redirect(Auth::getReturnToPage());

        }
		else
		{
			Flash::addMessage($this->messagesClassName::LOGIN_FAIL, Flash::WARNING);
			
            View::renderTemplate($this->getClassName().'/new.html', [
				'email' => $_POST['email'],
                'remember_me' => $remember_me,
				'classNameHypens' => $this->classNameHypens
			],
			$this->whichLang);
        }
    }
	
	/**
     * Show a "logged out" flash message and redirect to the homepage. Necessary to use the flash messages
     * as they use the session and at the end of the logout method (destroyAction) the session is destroyed
     * so a new action needs to be called in order to use the session.
     *
     * @return void
     */
    public function showLogoutMessageAction()
    {
      Flash::addMessage($this->messagesClassName::LOGOUT_SUCCESSFULL);

      $this->redirect('/');
    }
}
