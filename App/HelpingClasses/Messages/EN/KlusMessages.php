<?php

namespace App\HelpingClasses\Messages\EN;

/**
 * Messages
 * 
 * PHP version 7.4.12
 */
class KlusMessages
{
	// ======================================= Flash ======================================= //
	// For now Flash block in OtherMessages is used. This code below isn't used.
	const LOGIN_SUCCESSFULL = OtherMessages::LOGIN_SUCCESSFULL;
	const LOGIN_FAIL = OtherMessages::LOGIN_FAIL;
	const LOGOUT_SUCCESSFULL = OtherMessages::LOGOUT_SUCCESSFULL;
	const LOGIN_REQUIRED = OtherMessages::LOGIN_REQUIRED;
	const INCOME_ADDITION_SUCCESSFULL = OtherMessages::INCOME_ADDITION_SUCCESSFULL;
	const INCOME_ADDITION_FAIL = OtherMessages::INCOME_ADDITION_FAIL;
	const EXPENSE_ADDITION_SUCCESSFULL = OtherMessages::EXPENSE_ADDITION_SUCCESSFULL;
	const EXPENSE_ADDITION_FAIL = OtherMessages::EXPENSE_ADDITION_FAIL;
	const CHANGES_SAVED = OtherMessages::CHANGES_SAVED;
	const GENEREAL_ERROR = OtherMessages::GENEREAL_ERROR;
	
	// =================== Settings =================== //
	// Income's, expense's categories and payment methods
	const ONE_FIELD_REQUIRED = 'At least one field must be filled, dumpling.';
	const LIMIT_TOO_HIGH = "Limit is too high. Type smaller amount, dumpling. I can't believe You wanted type such big number... (max. 99 999 999, 99)";
	const LIMIT_NOT_A_NUMBER = OtherMessages::LIMIT_NOT_A_NUMBER;
	const NEGATIVE_AMOUNT_FORBIDDEN = "Very funny.. You can't add the negative amount, dumpling..";
	const ZERO_AMOUNT_FORBIDDEN = "Zero? Really??";
	const DESIGNATION_TOO_LONG = 'Name is too long, dumpling. Type max 50 characters.';
	
	// Only user form
	const NAME_IS_REQUIRED = 'I know You are klus...but name is required!';
	const NAME_TOO_LONG = 'Name is too long, dumpling. Type max 50 characters.';
	const LAST_NAME_IS_REQUIRED = 'Enter the last name. Your "not want myself" is getting over limits...';
	const LAST_NAME_TOO_LONG = 'Last name is too long, dumpling. Type max 50 characters.';
	const LOGIN_IS_REQUIRED = 'Enter the login...';
	const LOGIN_TOO_LONG = 'Login is too long. Type max 50 characters, nox..';
	const INVALID_EMAIL = "I'm sorry my dumplingus, but Your e-mail don't want to be valid...";
	const EMAIL_ALREADY_TAKEN = 'Email is already taken. I know it is hard time, but You must come up with new one...';
	const PASSWORD_DOES_NOT_MATCH_CONFIRMATION = 'Password must match confirmation, nox.';
	const PASSWORD_TOO_SMALL = 'I know, I know... But please enter at least 6 characters for the password. I believe you can do it!';
	const PASSWORD_DOES_NOT_HAVE_LETTER = 'Password needs at least one letter, dumpling.';
	const PASSWORD_DOES_NOT_HAVE_NUMBER = 'Password needs at least one number, dumpling.';
	const BOTS_FORBIDDEN = "Bots does not have permission!";
	
	// =================== Add Expense =================== //
	//const NEGATIVE_AMOUNT_FORBIDDEN = "Very funny.. You can't add the negative amount, dumpling.."; UP
}

?>