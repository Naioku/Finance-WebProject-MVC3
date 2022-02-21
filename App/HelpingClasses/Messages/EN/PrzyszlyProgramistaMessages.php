<?php

namespace App\HelpingClasses\Messages\EN;

/**
 * Messages
 * 
 * PHP version 7.4.12
 */
class PrzyszlyProgramistaMessages
{
	// ======================================= Flash ======================================= //
	// For now Flash block in OtherMessages is used. This code below isn't used.
	const LOGIN_SUCCESSFULL = 'Successfully logged in!';
	const LOGIN_FAIL = 'Incorrect email or password!';
	const LOGOUT_SUCCESSFULL = 'Logout successful!';
	const LOGIN_REQUIRED = 'Please log in to access that page.';
	const INCOME_ADDITION_SUCCESSFULL = 'Income has been added successfully!';
	const INCOME_ADDITION_FAIL = 'Income has not been added!';
	const EXPENSE_ADDITION_SUCCESSFULL = 'Expense has been added successfully!';
	const EXPENSE_ADDITION_FAIL = 'Expense has not been added!';
	const CHANGES_SAVED = 'Changes saved';
	const GENEREAL_ERROR = 'An error has occured';
	
	// ======================================= Error ======================================= //
	// =================== Settings =================== //
	// Income's, expense's categories and payment methods
	const ONE_FIELD_REQUIRED = 'At least one field must be filled.';
	const LIMIT_TOO_HIGH = "Limit is too high. Type smaller amount. (max. 99 999 999, 99)";
	const LIMIT_NOT_A_NUMBER = "Limit must be a number";
	const NEGATIVE_AMOUNT_FORBIDDEN = "Very funny.. You can't add the negative amount.";
	const ZERO_AMOUNT_FORBIDDEN = "Zero? Really??";
	const DESIGNATION_TOO_LONG = 'Name is too long. Type max 50 characters.';
	
	// Only user form
	const NAME_IS_REQUIRED = 'Name is required.';
	const NAME_TOO_LONG = 'Name is too long. Type max 50 characters.';
	const LAST_NAME_IS_REQUIRED = 'Enter the last name.';
	const LAST_NAME_TOO_LONG = 'Last name is too long. Type max 50 characters.';
	const LOGIN_IS_REQUIRED = 'Enter the login.';
	const LOGIN_TOO_LONG = 'Login is too long. Type max 50 characters.';
	const INVALID_EMAIL = 'Invalid email';
	const EMAIL_ALREADY_TAKEN = 'Email is already taken';
	const PASSWORD_DOES_NOT_MATCH_CONFIRMATION = 'Password must match confirmation';
	const PASSWORD_TOO_SMALL = 'Please enter at least 6 characters for the password';
	const PASSWORD_DOES_NOT_HAVE_LETTER = 'Password needs at least one letter';
	const PASSWORD_DOES_NOT_HAVE_NUMBER = 'Password needs at least one number';
	const BOTS_FORBIDDEN = "Bots does not have permission!";
	
	// =================== Add Expense =================== //
	//const NEGATIVE_AMOUNT_FORBIDDEN = "Very funny.. You can't add the negative amount."; UP
	
}

?>