<?php

namespace App\HelpingClasses\Messages\EN;

/**
 * Messages
 * 
 * PHP version 7.4.12
 */
class MadzixonMessages
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
	
	// ======================================= Error ======================================= //
	// =================== Settings =================== //
	// Income's, expense's categories and payment methods
	const ONE_FIELD_REQUIRED = OtherMessages::ONE_FIELD_REQUIRED;
	const LIMIT_TOO_HIGH = OtherMessages::LIMIT_TOO_HIGH;
	const LIMIT_NOT_A_NUMBER = OtherMessages::LIMIT_NOT_A_NUMBER;
	const NEGATIVE_AMOUNT_FORBIDDEN = OtherMessages::NEGATIVE_AMOUNT_FORBIDDEN;
	const ZERO_AMOUNT_FORBIDDEN = OtherMessages::ZERO_AMOUNT_FORBIDDEN;
	const DESIGNATION_TOO_LONG = OtherMessages::DESIGNATION_TOO_LONG;

	// Only user form
	const NAME_IS_REQUIRED = OtherMessages::NAME_IS_REQUIRED;
	const NAME_TOO_LONG = OtherMessages::NAME_TOO_LONG;
	const LAST_NAME_IS_REQUIRED = OtherMessages::LAST_NAME_IS_REQUIRED;
	const LAST_NAME_TOO_LONG = OtherMessages::LAST_NAME_TOO_LONG;
	const LOGIN_IS_REQUIRED = OtherMessages::LOGIN_IS_REQUIRED;
	const LOGIN_TOO_LONG = OtherMessages::LOGIN_TOO_LONG;
	const INVALID_EMAIL = OtherMessages::INVALID_EMAIL;
	const EMAIL_ALREADY_TAKEN = OtherMessages::EMAIL_ALREADY_TAKEN;
	const PASSWORD_DOES_NOT_MATCH_CONFIRMATION = OtherMessages::PASSWORD_DOES_NOT_MATCH_CONFIRMATION;
	const PASSWORD_TOO_SMALL = OtherMessages::PASSWORD_TOO_SMALL;
	const PASSWORD_DOES_NOT_HAVE_LETTER = OtherMessages::PASSWORD_DOES_NOT_HAVE_LETTER;
	const PASSWORD_DOES_NOT_HAVE_NUMBER = OtherMessages::PASSWORD_DOES_NOT_HAVE_NUMBER;
	const BOTS_FORBIDDEN = OtherMessages::BOTS_FORBIDDEN;
	
	// =================== Add Expense =================== //
	//const NEGATIVE_AMOUNT_FORBIDDEN = "Bardzo śmieszne.. Nie możesz dodać ujemnej wartości."; UP
	
}

?>