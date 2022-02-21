<?php

namespace App\HelpingClasses\Messages\PL;

/**
 * Messages
 * 
 * PHP version 7.4.12
 */
class BogdanMessages
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
	const ONE_FIELD_REQUIRED = 'Conajmniej jedno pole musi być wypełnione.';
	const LIMIT_TOO_HIGH = "Limit jest za wysoki. Wpisz mniejszą liczbę. (maks. 99 999 999, 99)";
	const LIMIT_NOT_A_NUMBER = "Limit musi być liczbą.";
	const NEGATIVE_AMOUNT_FORBIDDEN = "Bardzo śmieszne.. Nie możesz dodać ujemnej wartości.";
	const ZERO_AMOUNT_FORBIDDEN = "Zero? Naprawdę??";
	const DESIGNATION_TOO_LONG = 'Nazwa jest zbyt długa. Wpisz maks. 50 znaków.';

	// Only user form
	const NAME_IS_REQUIRED = 'Imię jest wymagane.';
	const NAME_TOO_LONG = 'Imię jest zbyt długie. Wpisz maks. 50 znaków.';
	const LAST_NAME_IS_REQUIRED = 'Wpisz nazwisko.';
	const LAST_NAME_TOO_LONG = 'Nazwisko jest zbyt długie. Wpisz maks. 50 znaków.';
	const LOGIN_IS_REQUIRED = 'Podaj login.';
	const LOGIN_TOO_LONG = 'Login jest zbyt długi. Wpisz maks. 50 znaków.';
	const INVALID_EMAIL = 'Nieprawidłowy email.';
	const EMAIL_ALREADY_TAKEN = 'Email jest już zajęty.';
	const PASSWORD_DOES_NOT_MATCH_CONFIRMATION = 'Hasło musi pasować do potwierdzenia.';
	const PASSWORD_TOO_SMALL = 'Wpisz proszę conajmniej 6 znaków dla hasła.';
	const PASSWORD_DOES_NOT_HAVE_LETTER = 'Hasło musi zawierać conajmniej jedną literę.';
	const PASSWORD_DOES_NOT_HAVE_NUMBER = 'Hasło musi zawierać conajmniej jedną cyfrę.';
	const BOTS_FORBIDDEN = "Boty nie mają dostępu!";
	
	// =================== Add Expense =================== //
	//const NEGATIVE_AMOUNT_FORBIDDEN = "Bardzo śmieszne.. Nie możesz dodać ujemnej wartości."; UP
	
}

?>