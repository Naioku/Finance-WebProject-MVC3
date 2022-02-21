<?php

namespace App\HelpingClasses\Messages;

/**
 * Messages
 * 
 * PHP version 7.4.12
 */
class Messages
{	
	public static function setSpecialClassesNames($whichLang, $whichKindOfUser = "Other")
	{
		return '\App\HelpingClasses\Messages\\'.$whichLang.'\\'.$whichKindOfUser.'Messages';
	}
}
?>