$.validator.addMethod('validPassword',
	function(value, element, param) {
		if (value != '') {
			if (value.match(/.*[a-z]+.*/i) == null) {
				return false;
			}
			if (value.match(/.*\d+.*/) == null) {
				return false;
			}
		}

		return true;
	},
	'Must contain at least one letter and one number'
);

var messagesPL = new Map();

messagesPL.set('name required', 'Imię jest wymagane.');
messagesPL.set('email required', 'Adres email jest wymagany.');
messagesPL.set('email invalid format', 'Adres email musi mieć format imię@domena.com/pl etc.');
messagesPL.set('email already taken', 'Adres email jest już zajęty.');
messagesPL.set('password required', 'Hasło jest wymagane.');
messagesPL.set('password minlength', 'Wpisz conajmniej 6 znaków.');
messagesPL.set('password invalid', 'Hasło musi zawierać conajmniej 1 literę i 1 cyfrę.');
messagesPL.set('password confirmation required', 'Potwierdzenie hasła jest wymagane.');
messagesPL.set('passwords not match each other', 'Hasła nie są identyczne.');

var messagesEN = new Map();

messagesEN.set('name required', 'Name is required.');
messagesEN.set('email required', 'Email is required.');
messagesEN.set('email invalid format', 'Email address must be in format name@domain.com/pl etc.');
messagesEN.set('email already taken', 'Email address already taken.');
messagesEN.set('password required', 'Password is required.');
messagesEN.set('password confirmation required', 'Password confirmation is required.');