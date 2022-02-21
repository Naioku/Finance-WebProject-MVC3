<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;
use \App\Config;
use \App\MailAddressess;
use \App\HelpingClasses\Messages\Messages;

/**
 * User model
 * 
 * PHP version 7.4.12
 */
class User extends \Core\Model
{
	public $whichLang;
	public $messagesClassName;
	
	public $id;
	public $login;
	public $name;
	public $lastName;
	public $email;
	public $password;
	public $password_confirmation;
	public $chooseLangFromDB;
	public $gRecaptchaResponse;
	
	public $isDumpling = false;
	
	public $temp_login;
	public $temp_name;
	public $temp_lastName;
	public $temp_email;
	public $temp_password;
	public $temp_password_confirmation;
	
	public $isUserDataError;
	
	/**
     * Date from
     *
     * @var int
     */
	public $dateFrom;
	
	/**
     * Date to
     *
     * @var int
     */
	public $dateTo;
	
	/**
     * Balance
     *
     * @var int
     */
	public $balance;
	
	/**
     * Income categories array
     *
     * @var array
     */
    public $incomeCategoriesArr;
	
	/**
     * Expense categories array
     *
     * @var array
     */
	public $expenseCategoriesArr;
	
	/**
     * Payment method categories array
     *
     * @var array
     */
	public $paymentMethodsArr;
	
	/**
     * Grouped incomes array
     *
     * @var array
     */
    public $groupedIncomesArr;
	
	/**
     * Grouped expenses array
     *
     * @var array
     */
	public $groupedExpensesArr;
	
	/**
     * Grouped payment methods array
     *
     * @var array
     */
	public $groupedPaymentMethodsArr;
	
	/**
     * Full incomes array
     *
     * @var array
     */
	public $fullIncomesArr;
	
	/**
     * Full incomes array
     *
     * @var array
     */
	public $fullExpensesArr;
	
	/**
     * Name of the updated income's category
     *
     * @var string
     */
	public $incomeCategoryName;
	
	/**
     * Income's category is already set at given name -> true
	 * otherwise -> false
     *
     * @var string
     */
	public $incomeCategoryNameIsSetFlag = false;
	
	public $isIncomeCategoryError = false;
	
	/**
     * Expense's category is already set at given name -> true
	 * otherwise -> false
     *
     * @var string
     */
	public $expenseCategoryNameIsSetFlag = false;
	
	public $isExpenseCategoryError = false;
	
	/**
     * Payment method is already set at given name -> true
	 * otherwise -> false
     *
     * @var string
     */
	public $paymentMethodNameIsSetFlag = false;
	
	public $ispaymentMethodError = false;
	
	public $isOnlyOneDataErrorArr = [];
	
	/**
     * Error messages
     *
     * @var array
     */
    public $errors = [];
	
	/**
     * AJAX error messages
     *
     * @var array
     */
    public $AJAXerrors = [];
	
	/**
     * Error messages for income's categories in user's settings
     *
     * @var array
     */
	public $incomeCategoriesErrorsArr = [];
	
	/**
     * Error messages for expense's categories in user's settings
     *
     * @var array
     */
	public $expenseCategoriesErrorsArr = [];
	
	/**
     * Error messages for payment methods in user's settings
     *
     * @var array
     */
	public $paymentMethodsErrorsArr = [];
	
	public $isCategoryLimitSetExpense;
	public $currentCategoryLimitExpense;
	public $expenseAmountSum;
	public $howMuchYouExceededExpense;
	public $howMuchYouNeedToExceedExpense;
	
	public $isPaymentMethodLimitSet;
	public $currentPaymentMethodLimit;
	public $paymentMethodAmountSum;
	public $howMuchYouExceededPaymentMethod;
	public $howMuchYouNeedToExceedPaymentMethod;
	
	/**
	 * Class constructor
	 * 
	 * @param array $data - Initial propety values
	 * 
	 * @return void
	 */
	public function __construct($data = [])
	{
		foreach($data as $key => $value)
		{
			$this->$key = $value;
		};
		
		$this->messagesClassName = Messages::setSpecialClassesNames($this->whichLang);
	}
	
	/**
	 * Save the user model with the current propety values
	 *
	 * @return void
	 */
	public function save()
	{	
		$this->validate();
		
		if(empty($this->errors))
		{
			$password_hash = password_hash($this->password, PASSWORD_DEFAULT);
			
			$token = new Token();
			$hashed_token = $token->getHash();
			$this->activation_token = $token->getValue();
			
			$sql = 'INSERT INTO users (login, name, last_name, email, password_hash, activation_hash)
					VALUES (:login, :name, :last_name, :email, :password_hash, :activation_hash)';
			
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			
			$stmt->bindValue(':login', $this->login, PDO::PARAM_STR);
			$stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
			$stmt->bindValue(':last_name', $this->lastName, PDO::PARAM_STR);
			$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
			$stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
			$stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);
			
			return $stmt->execute(); // execute() function retuns true - on success, false - on failure
		}
		
		return false;
	}
	
	/**
     * Validate user's name
     *
     * @return void
     */
    public function validateUserName($name)
	{
		if ($name == '')
		{
			$this->errors[] = $this->messagesClassName::NAME_IS_REQUIRED;
		}
		
		if (strlen($name) > 50)
		{
			$this->errors[] = $this->messagesClassName::NAME_TOO_LONG;
		}
	}
	
	/**
     * Validate user's last name
     *
     * @return void
     */
    public function validateUserLastName($lastName)
	{
		if ($lastName == '')
		{
			$this->errors[] = $this->messagesClassName::LAST_NAME_IS_REQUIRED;
		}
		
		if (strlen($lastName) > 50)
		{
			$this->errors[] = $this->messagesClassName::LAST_NAME_TOO_LONG;
		}
	}
	
	/**
     * Validate user's login
     *
     * @return void
     */
    public function validateUserLogin($login)
	{
		if ($login == '')
		{
			$this->errors[] = $this->messagesClassName::LOGIN_IS_REQUIRED;
		}
		
		if (strlen($login) > 50)
		{
			$this->errors[] = $this->messagesClassName::LOGIN_TOO_LONG;
		}
	}
	
	/**
     * Validate user's email
     *
     * @return void
     */
    public function validateUserEmail($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
		   $this->errors[] = $this->messagesClassName::INVALID_EMAIL;
		}
		if (static::emailExists($email, $this->id ?? null)) // if it's new record "null" will be passed in
		{
		   $this->errors[] = $this->messagesClassName::EMAIL_ALREADY_TAKEN;
		}
	}
	
	/**
     * Validate user's password
     *
     * @return void
     */
    public function validateUserPassword($password, $password_confirmation)
	{
		if ($password != $password_confirmation)
		{
			$this->errors[] = $this->messagesClassName::PASSWORD_DOES_NOT_MATCH_CONFIRMATION;
		}

		if (strlen($password) < 6)
		{
			$this->errors[] = $this->messagesClassName::PASSWORD_TOO_SMALL;
		}

		if (preg_match('/.*[a-z]+.*/i', $password) == 0)
		{
		   $this->errors[] = $this->messagesClassName::PASSWORD_DOES_NOT_HAVE_LETTER;
		}

		if (preg_match('/.*\d+.*/i', $password) == 0)
		{
		   $this->errors[] = $this->messagesClassName::PASSWORD_DOES_NOT_HAVE_NUMBER;
		}
	}

	public function getCaptcha($secretKey)
	{
		$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".Config::RE_CAPTCHA_SECRET."&response={$secretKey}");
		$return = json_decode($response);
		return $return;
	}
	
	public function reCaptchaValidation()
	{
		$return = $this->getCaptcha($this->gRecaptchaResponse);
		//var_dump($return);
		if (!($return->success == true) || !($return->score > 0.5))
		{
			$this->errors[] = $this->messagesClassName::BOTS_FORBIDDEN;
		}
	}
	
	/**
     * Validate new current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validateNewUserData()
	{
		// Name, last name and login
		$this->validateUserName($this->name);
		
		if ($this->lastName != '')
		{
			$this->validateUserLastName($this->lastName);
		}
		if ($this->temp_login != '')
		{
			$this->validateUserLogin($this->name);
		}
		
		// Email
		$this->validateUserEmail($this->email);
		
		// Password
		$this->validateUserPassword($this->password, $this->password_confirmation);
		
		// reCaptcha
		$this->reCaptchaValidation();
	}
	
	/**
     * Validate temporary current property values, adding validation error messages to the errors array property
     *
     * @return void
     */
    public function validateEditedUserData()
	{
		// Name, last name and login
				
		$this->validateUserName($this->temp_name);
		
		if ($this->temp_lastName != '')
		{
			$this->validateUserLastName($this->temp_lastName);
		}
		if ($this->temp_login != '')
		{
			$this->validateUserLogin($this->temp_name);
		}
		
		// Email
		$this->validateUserEmail($this->temp_email);
		
		// Password
		$this->validateUserPassword($this->temp_password, $this->temp_password_confirmation);
		
	}
	
	/**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validate($areTemporaryData = false)
    {
		if ($areTemporaryData)
		{
			$this->validateEditedUserData();
		}
		else
		{
			$this->validateNewUserData();
		}
    }
	
	/**
     * Validate user's income
     *
     * @return void
     */
	public function validateIncomeCategory()
	{
		$errors = [];
		
		if ($this->incomeCategoryName == '')
		{
			$errors[] = $this->messagesClassName::NAME_IS_REQUIRED;
		}
		if (strlen($this->incomeCategoryName) > 50)
		{
			$errors[] = $this->messagesClassName::DESIGNATION_TOO_LONG;
		}
		
		$incomeCategoryNameLower = strtolower($this->incomeCategoryName);
		foreach ($this->incomeCategoriesArr as $incomeCategoryFromArr)
		{
			if (strtolower($incomeCategoryFromArr['name']) == $incomeCategoryNameLower)
			{
				$this->incomeCategoryNameIsSetFlag = true;
			}
		}
		
		if (!empty($errors))
		{
			if(isset($this->incomeCategoryId))
			{
				$this->incomeCategoriesErrorsArr = array($this->incomeCategoryId => $errors);
			}
			else
			{
				$this->incomeCategoriesErrorsArr = array(0 => $errors);
			}
		}
	}
	
	/**
     * Validate user's expense category or payment method
     *
     * @return void
     */
	private function validateExpenseCategoryOrPaymentMethod($doValidateName, $name, $limit, $arr, $isExpenseCategory)
	{
		$errors = [];
		
		if ($name == '' &&
			$limit == '')
		{
			$errors[] = $this->messagesClassName::ONE_FIELD_REQUIRED;
		}
		
		// Name
		if ($doValidateName)
		{
			if ($name == '')
			{
				$errors[] = $this->messagesClassName::NAME_IS_REQUIRED;
			}
		}
		if (strlen($name) > 50)
		{
			$errors[] = $this->messagesClassName::DESIGNATION_TOO_LONG;
		}
		
		if ($isExpenseCategory)
		{
			$expenseCategoryNameLower = strtolower($name);
			foreach ($arr as $expenseCategoryFromArr)
			{
				if (strtolower($expenseCategoryFromArr['name']) == $expenseCategoryNameLower)
				{
					$this->expenseCategoryNameIsSetFlag = true;
				}
			}
		}
		else
		{
			$paymentMethodNameLower = strtolower($name);
			foreach ($arr as $paymentMethodFromArr)
			{
				if (strtolower($paymentMethodFromArr['name']) == $paymentMethodNameLower)
				{
					$this->paymentMethodNameIsSetFlag = true;
				}
			}
		}
		
		// Limit
		if ($limit != '')
		{			
			$limit = floatval($limit);
			
			if (!is_numeric($limit))
			{
				$errors[] = $this->messagesClassName::LIMIT_NOT_A_NUMBER;
			}
			
			if ($limit !== 0 &&
				floor(log10($limit)) + 1 > 8)
			{
				$errors[] = $this->messagesClassName::LIMIT_TOO_HIGH;
			}
			
			if ($limit < 0)
			{
				$errors[] = $this->messagesClassName::NEGATIVE_AMOUNT_FORBIDDEN;
			}
		}
		
		return $errors;
	}
	
	
	/**
     * Validate user's expense category
     *
     * @return void
     */
	public function validateExpenseCategory($doValidateName = true)
	{
		$errors = $this->validateExpenseCategoryOrPaymentMethod($doValidateName,
																$this->expenseCategoryName,
																$this->expenseCategoryLimit,
																$this->expenseCategoriesArr,
																true);
		
		if (!empty($errors))
		{
			if(isset($this->expenseCategoryId))
			{
				$this->expenseCategoriesErrorsArr = array($this->expenseCategoryId => $errors);
			}
			else
			{
				$this->expenseCategoriesErrorsArr = array(0 => $errors);
			}
		}
	}
	
	/**
     * Validate user's payment method
     *
     * @return void
     */
	public function validatePaymentMethod($doValidateName = true)
	{
		$errors = $this->validateExpenseCategoryOrPaymentMethod($doValidateName,
																$this->paymentMethodName,
																$this->paymentMethodLimit,
																$this->paymentMethodsArr,
																true);
		
		if (!empty($errors))
		{
			if(isset($this->paymentMethodId))
			{
				$this->paymentMethodsErrorsArr = array($this->paymentMethodId => $errors);
			}
			else
			{
				$this->paymentMethodsErrorsArr = array(0 => $errors);
			}
		}
	}
	
	/**
     * Copy default income categories to income categories assigned to users
     *
     * @return void
     */
    public function copyIncomes()
	{
		if ($this->chooseLangFromDB == 'en')
		{
			$sql = "INSERT INTO incomes_category_assigned_to_users (user_id, name) SELECT users.id, incomes_category_default.name FROM users, incomes_category_default WHERE users.email = :email";
		}
		else if ($this->chooseLangFromDB == 'pl')
		{
			$sql = "INSERT INTO incomes_category_assigned_to_users (user_id, name) SELECT users.id, incomes_category_default_pl.name FROM users, incomes_category_default_pl WHERE users.email = :email";
		}
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
		
		return $stmt->execute();
	}
	
	/**
     * Copy default expense categories to expense categories assigned to users
     *
     * @return void
     */
    public function copyExpenses()
	{
		if ($this->chooseLangFromDB == 'en')
		{
			$sql = "INSERT INTO expenses_category_assigned_to_users (user_id, name) SELECT users.id, expenses_category_default.name FROM users, expenses_category_default WHERE users.email = :email";
		}
		else if ($this->chooseLangFromDB == 'pl')
		{
			$sql = "INSERT INTO expenses_category_assigned_to_users (user_id, name) SELECT users.id, expenses_category_default_pl.name FROM users, expenses_category_default_pl WHERE users.email = :email";
		}
		
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
		
		return $stmt->execute();
	}
	
	/**
     * Copy default payment methodes to payment methodes assigned to users
     *
     * @return void
     */
    public function copyPaymentMethods()
	{
		if ($this->chooseLangFromDB == 'en')
		{
			$sql = "INSERT INTO payment_methods_assigned_to_users (user_id, name) SELECT users.id, payment_methods_default.name FROM users, payment_methods_default WHERE users.email = :email";
		}
		else if ($this->chooseLangFromDB == 'pl')
		{
			$sql = "INSERT INTO payment_methods_assigned_to_users (user_id, name) SELECT users.id, payment_methods_default_pl.name FROM users, payment_methods_default_pl WHERE users.email = :email";
		}
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
		
		return $stmt->execute();
	}
	
	/**
     * See if a user record already exists with the specified email address.
     *
     * @param string - $email email address to search for
	 *
	 * @return boolean - True if a record already exists with the specified email, false otherwise
     */
	public static function emailExists($email, $ignore_id = null)
	{
		$user = static::findByEmail($email);

        if ($user) {
            if ($user->id != $ignore_id) {
                return true;
            }
        }

        return false;
	}
	
	/**
     * Find a user model by email address
     *
     * @param string $email email address to search for
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
		
        return $stmt->fetch();
    }
	
	/**
     * Authenticate a user by email and password.
     *
     * @param string $email email address
     * @param string $password password
     *
     * @return mixed  The user object or false if authentication fails
     */
    public static function authenticate($email, $password)
    {
        $user = static::findByEmail($email);

        if ($user && $user->is_active)
		{
            if (password_verify($password, $user->password_hash))
			{
                return $user;
            }
        }

        return false;
    }
	
	/**
     * Set language from controller.
     *
     * @param string - $whichLang language shortcut e.g. 'PL', 'EN'
	 */
	public function setLang($whichLang)
	{
		$this->whichLang = $whichLang;
	}
	
	private function setSpecialClassesNames()
	{
		$this->messagesClassName = Messages::setSpecialClassesNames($this->whichLang, $this->whichKindOfUser);
	}
	
	public function setKindOfUser()
	{
		if ($this->email == MailAddressess::AYEN ||
			$this->email == MailAddressess::KURO ||
			$this->email == MailAddressess::ADIK) 						$this->whichKindOfUser = 'Klus';//
			
		else if ($this->email == MailAddressess::ANNA) 			$this->whichKindOfUser = 'Anna';//
		
		else if ($this->email == MailAddressess::BOGDAN1 ||
				$this->email == MailAddressess::BOGDAN2) 			$this->whichKindOfUser = 'Bogdan';//
				
		else if ($this->email == MailAddressess::PULTI) 				$this->whichKindOfUser = 'Pulti';//
		
		else if ($this->email == MailAddressess::KIN) 		$this->whichKindOfUser = 'Kin';//
		
		else if ($this->email == MailAddressess::CLUD) 			$this->whichKindOfUser = 'Clud';//
		
		else if ($this->email == MailAddressess::TOLA) 	$this->whichKindOfUser = 'Tola';
		
		else if ($this->email == MailAddressess::WERKA) 	$this->whichKindOfUser = 'Werka';//
		
		else if ($this->email == MailAddressess::BAS) 			$this->whichKindOfUser = 'Bas';//
		
		else if ($this->email == MailAddressess::JAKUBSON) 			$this->whichKindOfUser = 'Jakubson';//
		
		else if ($this->email == MailAddressess::PRZYSZLY_PROGRAMISTA1 ||
				$this->email == MailAddressess::PRZYSZLY_PROGRAMISTA2) 		$this->whichKindOfUser = 'PrzyszlyProgramista';//
		
		else if ($this->email == MailAddressess::CORNISTO) 			$this->whichKindOfUser = 'Cornisto';
		else if ($this->email == MailAddressess::JOANNA) 						$this->whichKindOfUser = 'Joanna';//
		else if ($this->email == MailAddressess::ZUZANNA) 		$this->whichKindOfUser = 'Zuzia';//
		else if ($this->email == MailAddressess::APE) 			$this->whichKindOfUser = 'Lukster';//
		else if ($this->email == MailAddressess::BLOTNIAK) 				$this->whichKindOfUser = 'Mateusz';
		else if ($this->email == MailAddressess::BIOSHOCK1 ||
					$this->email == MailAddressess::BIOSHOCK2 ||
					$this->email == MailAddressess::BIOSHOCK3) 				$this->whichKindOfUser = 'Sebo';//
		else if ($this->email == MailAddressess::BLAKES) 			$this->whichKindOfUser = 'Alek';
		else if ($this->email == MailAddressess::NORBERT) 			$this->whichKindOfUser = 'Hubert';
		else if ($this->email == MailAddressess::MADZIXON) 				$this->whichKindOfUser = 'Madzixon';//
		else if ($this->email == MailAddressess::KASIEK) 			$this->whichKindOfUser = 'Kasiek';
		else if ($this->email == MailAddressess::FILIP) 			$this->whichKindOfUser = 'Filip';
		else if ($this->email == MailAddressess::JULKO) 			$this->whichKindOfUser = 'Julko';
		else if ($this->email == MailAddressess::JANKO) 						$this->whichKindOfUser = 'Janko';//
		
		else 															$this->whichKindOfUser = 'Other';
		
		$this->setSpecialClassesNames();
	}
	
	/**
     * Find a user model by ID
     *
     * @param string $id The user ID
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByID($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }
	
	/**
     * Remember the login by inserting a new unique token into the remembered_logins table
     * for this user record
     *
     * @return boolean  True if the login was remembered successfully, false otherwise
     */
    public function rememberLogin()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();

        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30;  // 30 days from now

        $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
                VALUES (:token_hash, :user_id, :expires_at)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }
	
	/**
     * Send password reset instructions to the user specified
     *
     * @param string $email The email address
     *
     * @return void
     */
    public static function sendPasswordReset($email, $whichLang)
    {
        $user = static::findByEmail($email);

        if ($user)
		{
            if ($user->startPasswordReset())
			{
                $user->sendPasswordResetEmail($whichLang);
            }
        }
    }
	
	/**
     * Start the password reset process by generating a new token and expiry
     *
     * @return void
     */
    protected function startPasswordReset()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
		$this->password_reset_token = $token->getValue();

        $expiry_timestamp = time() + Config::PASSWORD_EXPIRY_TIMESTAMP;

        $sql = 'UPDATE users
                SET password_reset_hash = :token_hash,
                    password_reset_expires_at = :expires_at
                WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }
	
	/**
     * Send password reset instructions in an email to the user
     *
     * @return void
     */
    protected function sendPasswordResetEmail($whichLang)
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_reset_token;
		
        $text = View::getTemplate('Password/reset_email.txt', ['url' => $url], $whichLang);
        $html = View::getTemplate('Password/reset_email.html', ['url' => $url], $whichLang);

        Mail::send($this->email, 'Password reset', $text, $html);
    }
	
	/**
     * Find a user model by password reset token and expiry
     *
     * @param string $token Password reset token sent to user
     *
     * @return mixed User object if found and the token hasn't expired, null otherwise
     */
    public static function findByPasswordReset($token)
    {
        $token = new Token($token);
        $hashed_token = $token->getHash();

        $sql = 'SELECT * FROM users
                WHERE password_reset_hash = :token_hash';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $user = $stmt->fetch();

        if ($user) {

            // Check password reset token hasn't expired
            if (strtotime($user->password_reset_expires_at) > time())
			{
                return $user;
            }
        }
    }
	
	/**
     * Reset the password
     *
     * @param string $password The new password
     *
     * @return boolean  True if the password was updated successfully, false otherwise
     */
    public function resetPassword($password, $password_confirmation)
    {
        $this->password = $password;
        $this->password_confirmation = $password_confirmation;

        $this->validateUserPassword($this->password, $this->password_confirmation);

        if (empty($this->errors))
		{
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
			
            $sql = 'UPDATE users
                    SET password_hash = :password_hash,
                        password_reset_hash = NULL,
                        password_reset_expires_at = NULL
                    WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }
	
	/**
     * Send an email to the user containing the activation link
     *
     * @return void
     */
    public function sendActivationEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/signup/activate/' . $this->activation_token;
		
        $text = View::getTemplate('Signup/activation_email.txt', ['url' => $url], $this->whichLang);
        $html = View::getTemplate('Signup/activation_email.html', ['url' => $url], $this->whichLang);

        Mail::send($this->email, 'Account activation', $text, $html);
    }
	
	/**
     * Activate the user account with the specified activation token
     *
     * @param string $value Activation token from the URL
     *
     * @return void
     */
    public static function activate($value)
    {
        $token = new Token($value);
        $hashed_token = $token->getHash();

        $sql = 'UPDATE users
                SET is_active = 1,
                    activation_hash = null
                WHERE activation_hash = :hashed_token';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);

        $stmt->execute();              
    }
	
	/**
     * Update the user's profile
     *
     * @param array $data Data from the edit profile form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function updateProfile($data)
    {
        $this->temp_name = $data['name'];
        $this->temp_lastName = $data['lastName'];
        $this->temp_login = $data['login'];
        $this->temp_email = $data['email'];

        // Only validate and update the password if a value provided
        if ($data['password'] != '') {
            $this->temp_password = $data['password'];
            $this->temp_password_confirmation = $data['password_confirmation'];
        }

        $this->validate(true);

        if (empty($this->errors))
		{	
			$this->isUserDataError = false;

            $sql = 'UPDATE users
                    SET name = :name,
                        email = :email,
						password_hash = :password_hash';
			
			// Add last name if it's set
			if (isset($this->temp_password))
			{
                $sql .= ', last_name = :last_name';
            }
			
			// Add login if it's set
			if (isset($this->temp_password))
			{
                $sql .= ', login = :login';
            }

            $sql .= "\nWHERE id = :id";

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->temp_name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->temp_email, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
			
			$password_hash = password_hash($this->temp_password, PASSWORD_DEFAULT);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            // Add last name if it's set
            if (isset($this->temp_lastName))
			{
                $stmt->bindValue(':last_name', $this->temp_lastName, PDO::PARAM_STR);
            }
			
			// Add login if it's set
            if (isset($this->temp_login))
			{
                $stmt->bindValue(':login', $this->temp_login, PDO::PARAM_STR);
            }

            return $stmt->execute();
        }
		
		$this->isUserDataError = true;
        return false;
    }
	
	/**
     * Update the user's name
     *
     * @param array $data Data from the edit profile form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function updateUserName($data)
    {
		$this->isOnlyOneDataErrorArr[] = 'name';
		
        $this->temp_name = $data['name'];

        $this->validateUserName($this->temp_name);

        if (empty($this->errors))
		{
			$this->isUserDataError = false;

            $sql = 'UPDATE users
                    SET name = :name
                    WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->temp_name, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

            return $stmt->execute();
        }
		
		$this->isUserDataError = true;
        return false;
    }
	
	/**
     * Update the user's last name
     *
     * @param array $data Data from the edit profile form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function updateUserLastName($data)
    {
        $this->isOnlyOneDataErrorArr[] = 'lastName';
		
        $this->temp_lastName = $data['lastName'];

        $this->validateUserLastName($this->temp_lastName);

        if (empty($this->errors))
		{
			$this->isUserDataError = false;

            $sql = 'UPDATE users
                    SET last_name = :last_name
                    WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':last_name', $this->temp_lastName, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

            return $stmt->execute();
        }
		
		$this->isUserDataError = true;
        return false;
    }
	
	/**
     * Update the user's login
     *
     * @param array $data Data from the edit profile form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function updateUserLogin($data)
    {
        $this->isOnlyOneDataErrorArr[] = 'login';
		
        $this->temp_login = $data['login'];

        $this->validateUserLogin($this->temp_login);

        if (empty($this->errors))
		{
			$this->isUserDataError = false;

            $sql = 'UPDATE users
                    SET login = :login
                    WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':login', $this->temp_login, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

            return $stmt->execute();
        }
		
		$this->isUserDataError = true;
        return false;
    }
	
	/**
     * Update the user's password
     *
     * @param array $data Data from the edit profile form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function updateUserPassword($data)
    {
        $this->isOnlyOneDataErrorArr[] = 'password';
		
        $this->temp_password = $data['password'];
        $this->temp_password_confirmation = $data['password_confirmation'];

        $this->validateUserPassword($this->temp_password, $this->temp_password_confirmation);

        if (empty($this->errors))
		{
			$this->isUserDataError = false;

            $sql = 'UPDATE users
                    SET password_hash = :password_hash
                    WHERE id = :id';
			
            $db = static::getDB();
            $stmt = $db->prepare($sql);
			
			$password_hash = password_hash($this->temp_password, PASSWORD_DEFAULT);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
			
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

            return $stmt->execute();
        }
		
		$this->isUserDataError = true;
        return false;
    }
	
	/**
     * Update the user's e-mail
     *
     * @param array $data Data from the edit profile form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function updateUserEmail($data)
    {
        $this->isOnlyOneDataErrorArr[] = 'email';
		
        $this->temp_email = $data['email'];

        $this->validateUserEmail($this->temp_email);

        if (empty($this->errors))
		{
			$this->isUserDataError = false;

            $sql = 'UPDATE users
                    SET email = :email
                    WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':email', $this->temp_email, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

            return $stmt->execute();
        }
		
		$this->isUserDataError = true;
        return false;
    }
	
	/**
     * Delete user's last name
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function deleteUserLastName()
	{
		
		$sql = 'UPDATE users
				SET last_name = NULL
				WHERE id = :id';
					
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
		
		return $stmt->execute();
	}
	
	/**
     * Delete user's login
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function deleteUserLogin()
	{
		$sql = 'UPDATE users
				SET login = NULL
				WHERE id = :id';
					
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
		
		return $stmt->execute();
	}
	
	/**
     * Update the category of user's income
     *
     * @param array $data Data from the edit income's category form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function updateIncomeCategory($data)
	{
		$this->incomeCategoryName = $data['name'];
		$this->incomeCategoryId = $data['categoryId'];
		
		$this->validateIncomeCategory();
		
		if(empty($this->incomeCategoriesErrorsArr))
		{
			$this->isIncomeCategoryError = false;
			
			$sql = 'UPDATE incomes_category_assigned_to_users
                    SET name = :name
					WHERE id = :id';
					
			$db = static::getDB();
            $stmt = $db->prepare($sql);
			$stmt->bindValue(':name', $this->incomeCategoryName, PDO::PARAM_STR);
			$stmt->bindValue(':id', $this->incomeCategoryId, PDO::PARAM_INT);
			
			return $stmt->execute();
		}
		
		$this->isIncomeCategoryError = true;
		return false;
	}
	
	/**
     * Add new category of user's income
     *
     * @param array $data Data from the add income's category form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function addNewIncomeCategory($data)
	{
		if(isset($data['name'])) $this->incomeCategoryName = $data['name'];
		if(isset($data['wantToProceed'])) $wantToProceed = $data['wantToProceed'];
		
		$this->validateIncomeCategory();
			
		if (isset($wantToProceed))
		{
			$this->incomeCategoryNameIsSetFlag = false;
			$this->incomeCategoriesErrorsArr = [];
		}
			
		if(empty($this->incomeCategoriesErrorsArr) &&
				!$this->incomeCategoryNameIsSetFlag)
		{
			$this->isIncomeCategoryError = false;
			
			$sql = 'INSERT INTO incomes_category_assigned_to_users (user_id, name)
                    VALUES (:user_id, :name)';
					
			$db = static::getDB();
            $stmt = $db->prepare($sql);
			$stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
			$stmt->bindValue(':name', $this->incomeCategoryName, PDO::PARAM_STR);
			
			return $stmt->execute();
		}
		
		$this->isIncomeCategoryError = true;
		return false;
	}
	
	private function deleteIncomesWithProvidedCategory()
	{
		$sql = 'DELETE FROM incomes
				WHERE incomes_category_assigned_to_user_id = :category_id';
					
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':category_id', $this->incomeCategoryId, PDO::PARAM_INT);
		
		return $stmt->execute();
	}
	
	/**
     * Delete category of user's income
     *
     * @param array $data Data from the delete income's category form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function deleteIncomeCategory($data)
	{
		$this->incomeCategoryId = $data['categoryId'];
		
		if ($this->deleteIncomesWithProvidedCategory())
		{
			$sql = 'DELETE FROM incomes_category_assigned_to_users
					WHERE id = :id';
						
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':id', $this->incomeCategoryId, PDO::PARAM_INT);
			
			return $stmt->execute();
		}
		return false;
	}
	
	/**
     * Update the category of user's expense
     *
     * @param array $data Data from the edit expense's category form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function updateExpenseCategory($data)
	{
		$this->expenseCategoryName = $data['name'];
		$this->expenseCategoryLimit = $data['limit'];
		$this->expenseCategoryId = $data['categoryId'];
		
		$this->validateExpenseCategory(false);
		
		if(empty($this->expenseCategoriesErrorsArr))
		{
			$this->isExpenseCategoryError = false;
			
			$sql = 'UPDATE expenses_category_assigned_to_users
                    SET ';
			if ($this->expenseCategoryName != '')
			{
				$sql .= 'name = :name';
						
			}
			if ($this->expenseCategoryName != '' &&
				$this->expenseCategoryLimit != '')
			{
				$sql .= ', ';
			}
			if ($this->expenseCategoryLimit != '')
			{
				$sql .= 'user_limit = :limit';
			}
			
			$sql .= "\nWHERE id = :id;";
				
			$db = static::getDB();
            $stmt = $db->prepare($sql);
			
			if ($this->expenseCategoryName != '')
			{
				$stmt->bindValue(':name', $this->expenseCategoryName, PDO::PARAM_STR);
			}
			if ($this->expenseCategoryLimit != '')
			{
				$stmt->bindValue(':limit', $this->expenseCategoryLimit, PDO::PARAM_INT);
			}
			
			$stmt->bindValue(':id', $this->expenseCategoryId, PDO::PARAM_INT);
			
			return $stmt->execute();
		}
		
		$this->isExpenseCategoryError = true;
		return false;
	}
	
	/**
     * Add new category of user's expense
     *
     * @param array $data Data from the add expense's category form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function addNewExpenseCategory($data)
	{
		if(isset($data['name'])) $this->expenseCategoryName = $data['name'];
		if(isset($data['limit'])) $this->expenseCategoryLimit = $data['limit'];
		if(isset($data['wantToProceed'])) $wantToProceed = $data['wantToProceed'];
		
		$this->validateExpenseCategory();
			
		if (isset($wantToProceed))
		{
			$this->expenseCategoryNameIsSetFlag = false;
			$this->expenseCategoriesErrorsArr = [];
		}
			
		if(empty($this->expenseCategoryNameIsSetFlag) &&
				!$this->expenseCategoriesErrorsArr)
		{
			$this->isExpenseCategoryError = false;
			
			if ($this->expenseCategoryLimit != '')
			{
				$sql = 'INSERT INTO expenses_category_assigned_to_users (user_id, name, user_limit)
						VALUES (:user_id, :name, :limit)';
			}
			else
			{
				$sql = 'INSERT INTO expenses_category_assigned_to_users (user_id, name)
						VALUES (:user_id, :name)';
			}
					
			$db = static::getDB();
            $stmt = $db->prepare($sql);
			$stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
			$stmt->bindValue(':name', $this->expenseCategoryName, PDO::PARAM_STR);
			if ($this->expenseCategoryLimit != '')
			{
				$stmt->bindValue(':limit', $this->expenseCategoryLimit, PDO::PARAM_INT);
			}
			
			return $stmt->execute();
		}
		
		$this->isExpenseCategoryError = true;
		return false;
	}
	
	private function deleteExpensesWithProvidedCategory()
	{
		$sql = 'DELETE FROM expenses
				WHERE expenses_category_assigned_to_user_id = :category_id';
					
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':category_id', $this->expenseCategoryId, PDO::PARAM_INT);
		
		return $stmt->execute();
	}
	
	/**
     * Delete category of user's expense
     *
     * @param array $data Data from the delete expense's category form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function deleteExpenseCategory($data)
	{
		$this->expenseCategoryId = $data['categoryId'];
		
		if ($this->deleteExpensesWithProvidedCategory())
		{
			$sql = 'DELETE FROM expenses_category_assigned_to_users
					WHERE id = :id';
						
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':id', $this->expenseCategoryId, PDO::PARAM_INT);
			
			return $stmt->execute();
		}
		
		return false;
	}
	
	/**
     * Update the user's payment method
     *
     * @param array $data Data from the edit expense's category form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function updatePaymentMethod($data)
	{
		$this->paymentMethodName = $data['name'];
		$this->paymentMethodLimit = $data['limit'];
		$this->paymentMethodId = $data['methodId'];
		
		$this->validatePaymentMethod(false);
		
		if(empty($this->paymentMethodsErrorsArr))
		{
			$this->isPaymentMethodError = false;
			
			$sql = 'UPDATE payment_methods_assigned_to_users
                    SET ';
			if ($this->paymentMethodName != '')
			{
				$sql .= 'name = :name';
						
			}
			if ($this->paymentMethodName != '' &&
				$this->paymentMethodLimit != '')
			{
				$sql .= ', ';
			}
			if ($this->paymentMethodLimit != '')
			{
				$sql .= 'user_limit = :limit';
			}
			
			$sql .= "\nWHERE id = :id;";
				
			$db = static::getDB();
            $stmt = $db->prepare($sql);
			
			if ($this->paymentMethodName != '')
			{
				$stmt->bindValue(':name', $this->paymentMethodName, PDO::PARAM_STR);
			}
			if ($this->paymentMethodLimit != '')
			{
				$stmt->bindValue(':limit', strval($this->paymentMethodLimit), PDO::PARAM_STR);
			}
			
			$stmt->bindValue(':id', $this->paymentMethodId, PDO::PARAM_INT);
			
			return $stmt->execute();
		}
		
		$this->isPaymentMethodError = true;
		return false;
	}
	
	/**
     * Add new user's payment method
     *
     * @param array $data Data from the add expense's category form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function addNewPaymentMethod($data)
	{
		if(isset($data['name'])) $this->paymentMethodName = $data['name'];
		if(isset($data['limit'])) $this->paymentMethodLimit = $data['limit'];
		if(isset($data['wantToProceed'])) $wantToProceed = $data['wantToProceed'];
		
		$this->validatepaymentMethod();
			
		if (isset($wantToProceed))
		{
			$this->paymentMethodNameIsSetFlag = false;
			$this->paymentMethodsErrorsArr = [];
		}
			
		if(empty($this->paymentMethodNameIsSetFlag) &&
				!$this->paymentMethodsErrorsArr)
		{
			$this->isPaymentMethodError = false;
			
			if ($this->paymentMethodLimit != '')
			{
				$sql = 'INSERT INTO payment_methods_assigned_to_users (user_id, name, user_limit)
						VALUES (:user_id, :name, :limit)';
			}
			else
			{
				$sql = 'INSERT INTO payment_methods_assigned_to_users (user_id, name)
						VALUES (:user_id, :name)';
			}
					
			$db = static::getDB();
            $stmt = $db->prepare($sql);
			$stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
			$stmt->bindValue(':name', $this->paymentMethodName, PDO::PARAM_STR);
			if ($this->paymentMethodLimit != '')
			{
				$stmt->bindValue(':limit', strval($this->paymentMethodLimit), PDO::PARAM_STR);
			}
			
			return $stmt->execute();
		}
		
		$this->isPaymentMethodError = true;
		return false;
	}
	
	private function deleteExpensesWithProvidedPaymentMethod()
	{
		$sql = 'DELETE FROM expenses
				WHERE payment_method_assigned_to_user_id = :payment_method_id';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':payment_method_id', $this->paymentMethodId, PDO::PARAM_INT);
		
		return $stmt->execute();
	}
	
	/**
     * Delete user's payment method
     *
     * @param array $data Data from the delete payment method's category form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function deletePaymentMethod($data)
	{
		$this->paymentMethodId = $data['methodId'];
		
		if ($this->deleteExpensesWithProvidedPaymentMethod())
		{
			$sql = 'DELETE FROM payment_methods_assigned_to_users
					WHERE id = :id';
						
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':id', $this->paymentMethodId, PDO::PARAM_INT);
			
			return $stmt->execute();
		}
		
		return false;
	}
	
	/**
     * Add income to database
	 * 
	 * @param array $data - Data from the adding income form
     *
     * @return boolean - True if the data was updated, false otherwise
     */
	public function addIncomeToDatabase($data)
	{
		$this->validateIncome($data);
		if (empty($this->AJAXerrors))
		{
			$sql = 'INSERT INTO incomes (user_id, incomes_category_assigned_to_user_id, amount, date_of_income, income_comment)
					VALUES (:user_id, :incomes_category_assigned_to_user_id, :amount, :date_of_income, :income_comment)';
					
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
			$stmt->bindValue(':incomes_category_assigned_to_user_id', $data['category'], PDO::PARAM_STR);
			$stmt->bindValue(':amount', $data['amount'], PDO::PARAM_STR);
			$stmt->bindValue(':date_of_income', $data['date'], PDO::PARAM_STR);
			$stmt->bindValue(':income_comment', $data['comment'], PDO::PARAM_STR);
			
			return $stmt->execute();
		}
		
		return false;
	}
	
	/**
     * Update user's income
     *
     * @param array $data Data from the update income form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
	function updateIncome($data)
	{
		$this->recordId = $data['recordId'];
		$this->validateIncome($data);
		if (empty($this->AJAXerrors))
		{
			$sql = 'UPDATE incomes
					SET user_id = :user_id,
						incomes_category_assigned_to_user_id = :incomes_category_assigned_to_user_id,
						amount = :amount,
						date_of_income = :date_of_income,
						income_comment = :income_comment
					WHERE id = :id';
					
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
			$stmt->bindValue(':incomes_category_assigned_to_user_id', $data['category'], PDO::PARAM_STR);
			$stmt->bindValue(':amount', $data['amount'], PDO::PARAM_STR);
			$stmt->bindValue(':date_of_income', $data['date'], PDO::PARAM_STR);
			$stmt->bindValue(':income_comment', $data['comment'], PDO::PARAM_STR);
			$stmt->bindValue(':id', $this->recordId, PDO::PARAM_INT);
			
			return $stmt->execute();
		}
		
		return false;
	}
	
	/**
     * Delete user's income
     *
     * @param array $data Data from the delete income form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
	function deleteIncome($data)
	{
		$this->recordId = $data['recordId'];
		
		$sql = 'DELETE FROM incomes
				WHERE id = :id';
					
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':id', $this->recordId, PDO::PARAM_INT);
		
		return $stmt->execute();
	}
	
	/**
     * Add expense to database
	 * 
	 * @param array $data - Data from the adding expense form
     *
     * @return boolean - True if the data was updated, false otherwise
     */
	public function addExpenseToDatabase($data)
	{
		$this->validateExpense($data);
		if (empty($this->AJAXerrors))
		{
			$sql = 'INSERT INTO expenses (user_id, expenses_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment)
					VALUES (:user_id, :expenses_category_assigned_to_user_id, :payment_method_assigned_to_user_id, :amount, :date_of_expense, :expense_comment)';
					
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
			$stmt->bindValue(':expenses_category_assigned_to_user_id', $data['category'], PDO::PARAM_STR);
			$stmt->bindValue(':payment_method_assigned_to_user_id', $data['paymentMethod'], PDO::PARAM_STR);
			$stmt->bindValue(':amount', $data['amount'], PDO::PARAM_STR);
			$stmt->bindValue(':date_of_expense', $data['date'], PDO::PARAM_STR);
			$stmt->bindValue(':expense_comment', $data['comment'], PDO::PARAM_STR);
			
			return $stmt->execute();
		}
		
		return false;
	}
	
	/**
     * Update user's expense
     *
     * @param array $data Data from the delete expense form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
	function updateExpense($data)
	{
		$this->recordId = $data['recordId'];
		$this->validateExpense($data);
		if (empty($this->AJAXerrors))
		{
			$sql = 'UPDATE expenses
						SET user_id = :user_id,
						expenses_category_assigned_to_user_id = :expenses_category_assigned_to_user_id,
						payment_method_assigned_to_user_id = :payment_method_assigned_to_user_id,
						amount = :amount,
						date_of_expense = :date_of_expense,
						expense_comment = :expense_comment
					WHERE id = :id';
					
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
			$stmt->bindValue(':expenses_category_assigned_to_user_id', $data['category'], PDO::PARAM_STR);
			$stmt->bindValue(':payment_method_assigned_to_user_id', $data['paymentMethod'], PDO::PARAM_STR);
			$stmt->bindValue(':amount', $data['amount'], PDO::PARAM_STR);
			$stmt->bindValue(':date_of_expense', $data['date'], PDO::PARAM_STR);
			$stmt->bindValue(':expense_comment', $data['comment'], PDO::PARAM_STR);
			$stmt->bindValue(':id', $this->recordId, PDO::PARAM_INT);
			
			return $stmt->execute();
		}
		
		return false;
	}
	
	/**
     * Delete user's expense
     *
     * @param array $data Data from the delete expense form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
	function deleteExpense($data)
	{
		$this->recordId = $data['recordId'];
		
		$sql = 'DELETE FROM expenses
				WHERE id = :id';
					
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':id', $this->recordId, PDO::PARAM_INT);
		
		return $stmt->execute();
	}

	/**
     * Get all user's income categories
     *
     * @return void
     */
    public function getAllUsersIncomeCategories()
	{
		$sql = 'SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);

        $stmt->execute();
		
		$this->incomeCategoriesArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	private function validateAmount($amount)
	{
		if ($amount < 0)
		{	
			$this->AJAXerrors[] = $this->messagesClassName::NEGATIVE_AMOUNT_FORBIDDEN;
		}
		else if($amount == 0)
		{
			$this->AJAXerrors[] = $this->messagesClassName::ZERO_AMOUNT_FORBIDDEN;
		}
	}
	
	public function validateIncome($data)
	{
		$amount = $data['amount'];
		$this->validateAmount($amount);
	}
	
	/**
     * Get all user's expense categories
     *
     * @return void
     */
    public function getAllUsersExpenseCategories()
	{
		$sql = 'SELECT id, name, user_limit FROM expenses_category_assigned_to_users WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);

        $stmt->execute();
		
		$this->expenseCategoriesArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
     * Get all user's payment methods
     *
     * @return void
     */
    public function getAllUsersPaymentMethods()
	{
		$sql = 'SELECT id, name, user_limit FROM payment_methods_assigned_to_users WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);

        $stmt->execute();
		
		$this->paymentMethodsArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	private function validateExpense($data)
	{
		$amount = $data['amount'];
		$this->validateAmount($amount);
	}
	
	/**
     * Get incomes, expenses and the balance between provided dates or from present month by default
     *
     * @param date variable $dateFrom (optional)
     * @param date variable $dateTo (optional)
     *
     * @return void
     */
	public function getIncomesAndExpensesAndBalanceBetweenProvidedDates($dateFrom = NULL, $dateTo = NULL)
	{
		if(!$dateFrom || !$dateTo)
		{
			$this->setDefaultDatesFromAndTo();
		}
		else
		{
			$this->dateFrom = $dateFrom;
			$this->dateTo = $dateTo;
		}
		
		$this->getAllIncomesAndExpensesBetweenProvidedDates($this->dateFrom, $this->dateTo);
		$this->getBalanceBetweenProvidedDates($this->dateFrom, $this->dateTo);
		
	}
	
	/**
     * Set date from and date to as present month first and last day by default
     *
     * @return void
     */
	public function setDefaultDatesFromAndTo()
	{
		$this->dateFrom = date('Y-m-01');
		$this->dateTo = date('Y-m-t');
	}
	
	/**
     * Get all user's incomes and expenses between provided dates
     *
     * @param date variable $dateFrom (optional)
     * @param date variable $dateTo (optional)
     *
     * @return void
     */
	public function getAllIncomesAndExpensesBetweenProvidedDates($dateFrom, $dateTo)
	{
		$this->getGroupedIncomesBetweenProvidedDates($this->dateFrom, $this->dateTo);
		$this->getGroupedExpensesBetweenProvidedDates($this->dateFrom, $this->dateTo);
		$this->getGroupedPaymentMethodsBetweenProvidedDates($this->dateFrom, $this->dateTo);
		$this->getFullIncomesBetweenProvidedDates($this->dateFrom, $this->dateTo);
		$this->getFullExpensesBetweenProvidedDates($this->dateFrom, $this->dateTo);
	}
	
	/**
     * Get all user's grouped incomes (without comment and date)
     *
     * @param date variable $dateFrom
     * @param date variable $dateTo
     *
     * @return void
     */
	public function getGroupedIncomesBetweenProvidedDates($dateFrom, $dateTo)
	{
		$sql = "SELECT SUM(incomes.amount) AS amount_sum, incomes_category_assigned_to_users.name AS category
				FROM incomes, incomes_category_assigned_to_users
				WHERE incomes.user_id = :userId AND
				incomes.date_of_income BETWEEN :dateFrom AND :dateTo AND
				incomes.user_id = incomes_category_assigned_to_users.user_id AND
				incomes.incomes_category_assigned_to_user_id = incomes_category_assigned_to_users.id
				GROUP BY incomes_category_assigned_to_users.name
				ORDER BY incomes.date_of_income
				DESC";
				
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':dateFrom', $dateFrom, PDO::PARAM_STR);
		$stmt->bindValue(':dateTo', $dateTo, PDO::PARAM_STR);
		$stmt->bindValue(':userId', $this->id, PDO::PARAM_INT);
		
		$stmt->execute();
		
		$this->groupedIncomesArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
     * Get all user's grouped expenses (without comment and date)
     *
     * @param date variable $dateFrom
     * @param date variable $dateTo
     *
     * @return void
     */
	public function getGroupedExpensesBetweenProvidedDates($dateFrom, $dateTo)
	{
		$sql = "SELECT SUM(expenses.amount) AS amount_sum, expenses_category_assigned_to_users.name AS category
				FROM expenses, expenses_category_assigned_to_users
				WHERE expenses.user_id = :userId AND
				expenses.date_of_expense BETWEEN :dateFrom AND :dateTo AND
				expenses.user_id = expenses_category_assigned_to_users.user_id AND
				expenses.expenses_category_assigned_to_user_id = expenses_category_assigned_to_users.id
				GROUP BY expenses_category_assigned_to_users.name
				ORDER BY expenses.date_of_expense
				DESC";
				
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':dateFrom', $dateFrom, PDO::PARAM_STR);
		$stmt->bindValue(':dateTo', $dateTo, PDO::PARAM_STR);
		$stmt->bindValue(':userId', $this->id, PDO::PARAM_INT);
		
		$stmt->execute();
		
		$this->groupedExpensesArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
     * Get all user's grouped expenses (without comment and date)
     *
     * @param date variable $dateFrom
     * @param date variable $dateTo
     *
     * @return void
     */
	public function getGroupedPaymentMethodsBetweenProvidedDates($dateFrom, $dateTo)
	{
		$sql = "SELECT SUM(expenses.amount) AS amount_sum, payment_methods_assigned_to_users.name AS payment_method
				FROM expenses, payment_methods_assigned_to_users
				WHERE expenses.user_id = :userId AND
				expenses.date_of_expense BETWEEN :dateFrom AND :dateTo AND
				expenses.user_id = payment_methods_assigned_to_users.user_id AND
				expenses.payment_method_assigned_to_user_id = payment_methods_assigned_to_users.id
				GROUP BY payment_methods_assigned_to_users.name
				ORDER BY expenses.date_of_expense
				DESC";
				
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':dateFrom', $dateFrom, PDO::PARAM_STR);
		$stmt->bindValue(':dateTo', $dateTo, PDO::PARAM_STR);
		$stmt->bindValue(':userId', $this->id, PDO::PARAM_INT);
		
		$stmt->execute();
		
		$this->groupedPaymentMethodsArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
     * Get all user's full incomes (with comment and date)
     *
     * @param date variable $dateFrom
     * @param date variable $dateTo
     *
     * @return void
     */
	public function getFullIncomesBetweenProvidedDates($dateFrom, $dateTo)
	{
		$sql = "SELECT incomes.id AS id, incomes.date_of_income AS date, incomes.amount, incomes_category_assigned_to_users.name AS category, incomes.income_comment AS comment, incomes_category_assigned_to_users.id AS category_id
				FROM incomes, incomes_category_assigned_to_users
				WHERE incomes.user_id = :userId AND
				incomes.date_of_income BETWEEN :dateFrom AND :dateTo AND
				incomes.user_id = incomes_category_assigned_to_users.user_id AND
				incomes.incomes_category_assigned_to_user_id = incomes_category_assigned_to_users.id
				ORDER BY incomes.date_of_income
				DESC";
				
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':dateFrom', $dateFrom, PDO::PARAM_STR);
		$stmt->bindValue(':dateTo', $dateTo, PDO::PARAM_STR);
		$stmt->bindValue(':userId', $this->id, PDO::PARAM_INT);
		
		$stmt->execute();
		
		$this->fullIncomesArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
     * Get all user's full expenses (with comment and date)
     *
     * @param date variable $dateFrom
     * @param date variable $dateTo
     *
     * @return void
     */
	public function getFullExpensesBetweenProvidedDates($dateFrom, $dateTo)
	{
		$sql = "SELECT expenses.id AS id, expenses.date_of_expense AS date, expenses.amount, expenses_category_assigned_to_users.name AS category, payment_methods_assigned_to_users.name AS payment_method, expenses.expense_comment AS comment, expenses_category_assigned_to_users.id AS category_id, payment_methods_assigned_to_users.id AS payment_method_id
				FROM expenses, expenses_category_assigned_to_users, payment_methods_assigned_to_users
				WHERE expenses.user_id = :userId AND
				expenses.date_of_expense BETWEEN :dateFrom AND :dateTo AND
				expenses.user_id = expenses_category_assigned_to_users.user_id AND
				expenses.expenses_category_assigned_to_user_id = expenses_category_assigned_to_users.id AND
				expenses.payment_method_assigned_to_user_id = payment_methods_assigned_to_users.id
				ORDER BY expenses.date_of_expense
				DESC";
				
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':dateFrom', $dateFrom, PDO::PARAM_STR);
		$stmt->bindValue(':dateTo', $dateTo, PDO::PARAM_STR);
		$stmt->bindValue(':userId', $this->id, PDO::PARAM_INT);
		
		$stmt->execute();
		
		$this->fullExpensesArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
     * Get the balance of all user's incomes and expenses
     *
     * @param date variable $dateFrom
     * @param date variable $dateTo
     *
     * @return void
     */
	public function getBalanceBetweenProvidedDates($dateFrom, $dateTo)
	{
		$this->balance = $this->getIncomesSumBetweenProvidedDates($dateFrom, $dateTo) - $this->getExpensesSumBetweenProvidedDates($dateFrom, $dateTo);
	}
	
	/**
     * Get the sum of all user's expenses
     *
     * @param date variable $dateFrom
     * @param date variable $dateTo
     *
     * @return int
     */
	public function getExpensesSumBetweenProvidedDates($dateFrom, $dateTo)
	{
		$sql = "SELECT SUM(expenses.amount) AS amount_sum
				FROM expenses, expenses_category_assigned_to_users
				WHERE expenses.user_id = :userId AND
				expenses.date_of_expense BETWEEN :dateFrom AND :dateTo AND
				expenses.user_id = expenses_category_assigned_to_users.user_id AND
				expenses.expenses_category_assigned_to_user_id = expenses_category_assigned_to_users.id;";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':dateFrom', $dateFrom, PDO::PARAM_STR);
		$stmt->bindValue(':dateTo', $dateTo, PDO::PARAM_STR);
		$stmt->bindValue(':userId', $this->id, PDO::PARAM_INT);
		
		$stmt->execute();
		
		$expensesSumArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$row = $expensesSumArr[0];
		$expensesSum = $row['amount_sum'];
		if($expensesSum == NULL) $expensesSum = 0;
		
		return $expensesSum;
	}
	
	/**
     * Get the sum of all user's incomes
     *
     * @param date variable $dateFrom
     * @param date variable $dateTo
     *
     * @return int
     */
	public function getIncomesSumBetweenProvidedDates($dateFrom, $dateTo)
	{
		$sql = "SELECT SUM(incomes.amount) AS amount_sum
				FROM incomes, incomes_category_assigned_to_users
				WHERE incomes.user_id = :userId AND
				incomes.date_of_income BETWEEN :dateFrom AND :dateTo AND
				incomes.user_id = incomes_category_assigned_to_users.user_id AND
				incomes.incomes_category_assigned_to_user_id = incomes_category_assigned_to_users.id";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':dateFrom', $dateFrom, PDO::PARAM_STR);
		$stmt->bindValue(':dateTo', $dateTo, PDO::PARAM_STR);
		$stmt->bindValue(':userId', $this->id, PDO::PARAM_INT);
		
		$stmt->execute();
		
		$incomesSumArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$row = $incomesSumArr[0];
		$incomesSum = $row['amount_sum'];
		if($incomesSum == NULL) $incomesSum = 0;
		
		return $incomesSum;
	}

	/**
     * Check if limit for category is exceeded
     *
     * @return void.
     */
	public function checkIfCategoryLimitExceeded($data)
	{
		$this->validateAmount($data["amount"]);
		
		if (empty($this->AJAXerrors))
		{
			$this->isCategoryLimitSetExpense = true;
			$amount = $data["amount"];
			$categoryId = $data["categoryId"];
			$dateFrom = $data["dateFrom"];
			$dateTo = $data["dateTo"];
			
			$this->getGroupedExpensesBetweenProvidedDates($dateFrom, $dateTo);
			foreach ($this->expenseCategoriesArr as $category)
			{
				if ($category["id"] == $categoryId) 
				{
					$categoryLimit = $category["user_limit"];
					if ($categoryLimit != null)
					{
						$this->currentCategoryLimitExpense = $categoryLimit;
						$categoryName = $category["name"];
						$this->expenseAmountSum = $amount;
						if (!empty($this->groupedExpensesArr))
						{	
							foreach ($this->groupedExpensesArr as $row)
							{
								if ($row["category"] == $categoryName)
								{
									$this->expenseAmountSum += $row["amount_sum"];
								}
							}
						}
						if ($this->expenseAmountSum > $categoryLimit)
						{	
							$this->howMuchYouExceededExpense = $this->expenseAmountSum - $categoryLimit;
						}
						else
						{
							$this->howMuchYouNeedToExceedExpense = $categoryLimit - $this->expenseAmountSum;
						}
					}
					else
					{
						$this->isCategoryLimitSetExpense = false;
					}
				}
			}
			
			$this->expenseAmountSum = number_format($this->expenseAmountSum, 2, '.', ' ');
			$this->howMuchYouExceededExpense = number_format($this->howMuchYouExceededExpense, 2, '.', ' ');
			$this->howMuchYouNeedToExceedExpense = number_format($this->howMuchYouNeedToExceedExpense, 2, '.', ' ');
			$this->currentCategoryLimitExpense = number_format($this->currentCategoryLimitExpense, 2, '.', ' ');
		}
	}
	
	/**
     * Check if limit for payment method is exceeded
     *
     * @return void.
     */
	public function checkIfPaymentMethodLimitExceeded($data)
	{
		$this->validateAmount($data["amount"]);
		if (empty($this->AJAXerrors))
		{
			$this->isPaymentMethodLimitSet = true;
			$amount = $data["amount"];
			$paymentMethodId = $data["paymentMethodId"];
			$dateFrom = $data["dateFrom"];
			$dateTo = $data["dateTo"];
			
			$this->getGroupedPaymentMethodsBetweenProvidedDates($dateFrom, $dateTo);
			
			foreach ($this->paymentMethodsArr as $paymentMethod)
			{
				if ($paymentMethod["id"] == $paymentMethodId) 
				{
					$paymentMethodLimit = $paymentMethod["user_limit"];
					if ($paymentMethodLimit != null)
					{
						$this->currentPaymentMethodLimit = $paymentMethodLimit;
						$paymentMethodName = $paymentMethod["name"];
						$this->paymentMethodAmountSum = $amount;
						if (!empty($this->groupedPaymentMethodsArr))
						{
							foreach ($this->groupedPaymentMethodsArr as $row)
							{
								if ($row["payment_method"] == $paymentMethodName)
								{
									$this->paymentMethodAmountSum += $row["amount_sum"];								
								}
							}
						}
						
						if ($this->paymentMethodAmountSum > $paymentMethodLimit)
						{	
							$this->howMuchYouExceededPaymentMethod = $this->paymentMethodAmountSum - $paymentMethodLimit;
						}
						else
						{
							$this->howMuchYouNeedToExceedPaymentMethod = $paymentMethodLimit - $this->paymentMethodAmountSum;
						}
					}
					else
					{
						$this->isPaymentMethodLimitSet = false;
					}
				}
			}
			
			$this->paymentMethodAmountSum = number_format($this->paymentMethodAmountSum, 2, '.', ' ');
			$this->howMuchYouExceededPaymentMethod = number_format($this->howMuchYouExceededPaymentMethod, 2, '.', ' ');
			$this->howMuchYouNeedToExceedPaymentMethod = number_format($this->howMuchYouNeedToExceedPaymentMethod, 2, '.', ' ');
			$this->currentPaymentMethodLimit = number_format($this->currentPaymentMethodLimit, 2, '.', ' ');
		}
	}
}

?>