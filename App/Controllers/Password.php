<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

/**
 * Password controller
 *
 * PHP version 7.4.12
 */
class Password extends \Core\Controller
{
    /**
     * Show the forgotten password page
     *
     * @return void
     */
    public function forgotAction()
    {
        View::renderTemplate($this->getClassName().'/forgot.html', [
			'classNameHypens' => $this->classNameHypens
		],
		$this->whichLang);
    }
	
	/**
     * Send the password reset link to the supplied email
     *
     * @return void
     */
    public function requestResetAction()
    {
        User::sendPasswordReset($_POST['email'], $this->whichLang);

        View::renderTemplate($this->getClassName().'/reset_requested.html', [
			'classNameHypens' => $this->classNameHypens
		],
		$this->whichLang);
    }
	
	/**
	 * Show the reset password form
	 *
	 * @return void
	 */
	public function resetAction()
	{
		$token = $this->routeParameters['token'];
		
		$user = $this->getUserOrExit($token);
		
		View::renderTemplate($this->getClassName().'/reset.html', [
			'token' => $token,
			'classNameHypens' => $this->classNameHypens
		],
		$this->whichLang);
	}
	
	/**
     * Reset the user's password
     *
     * @return void
     */
    public function resetPasswordAction()
    {
        $token = $_POST['token'];
		
		$user = $this->getUserOrExit($token);
		
		$user->setLang($this->whichLang);
		$user->setKindOfUser(); // user->whichLang must be set before
		
        if ($user->resetPassword($_POST['password'], $_POST['password_confirmation']))
		{
            $this->redirect("/password/reset-password-succeed");
        }
		else
		{
            View::renderTemplate($this->getClassName().'/reset.html', [
                'token' => $token,
                'user' => $user,
				'classNameHypens' => $this->classNameHypens
            ],
			$this->whichLang);
        }
    }
	
	/**
     * Show password succeed page.
     *
     * @return void
     */
	public function resetPasswordSucceedAction()
	{
		View::renderTemplate($this->getClassName().'/reset_success.html', [
			'classNameHypens' => $this->classNameHypens
		],
		$this->whichLang);
	}
	
	/**
     * Find the user model associated with the password reset token, or end the request with a message
     *
     * @param string $token Password reset token sent to user
     *
     * @return mixed User object if found and the token hasn't expired, null otherwise
     */
    protected function getUserOrExit($token)
    {
        $user = User::findByPasswordReset($token);

        if ($user) {

            return $user;

        } else {

            View::renderTemplate($this->getClassName().'/token_expired.html', [
				'classNameHypens' => $this->classNameHypens
			],
			$this->whichLang);
            exit;

        }
    }
}
