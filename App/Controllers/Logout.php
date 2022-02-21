<?php

namespace App\Controllers;

use App\Auth;

/**
 * Logout controller
 *
 * PHP version 7.4.12
 */
class Logout extends Authenticated
{
	/**
     * Log out a user
     *
     * @return void
     */
    public function destroyAction()
    {
		Auth::logout();
		
        $this->redirect('/login/show-logout-message'); // Via another request another road via public/index.php is done, so another session is started.
    }
}