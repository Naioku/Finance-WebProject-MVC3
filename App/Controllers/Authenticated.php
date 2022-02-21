<?php

namespace App\Controllers;
use \App\HelpingClasses\Messages\Messages;

use \App\Auth;
/**
 * Authenticated base controller
 *
 * PHP version 7.4.12
 */
abstract class Authenticated extends \Core\Controller
{
    /**
     * Require the user to be authenticated before giving access to all methods in the controller
     *
     * @return void
     */
    protected function before()
    {
		$this->getClassNameSeparatedByDash();
		$this->messagesClassName = Messages::setSpecialClassesNames($this->whichLang); // To get possibility of getting messages from Messages classes to e.g. FlashMessages
        $this->requireLogin();
		$this->user = Auth::getUser();
		$this->user->setLang($this->whichLang);
		$this->user->setKindOfUser(); // user->whichLang must be set before
		
    }
}
