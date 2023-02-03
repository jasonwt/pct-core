<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\validator\value\pattern;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	
	class PhoneNumberValidator extends ValuePatternValidator {
		public function __construct(?IErrorHandler $errorHandler = null) {
			parent::__construct("PhoneNumberValidator", "not a valid phone number", '^(\s*|(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4})$', $errorHandler);
		}
	}
?>