<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\validator\value\pattern;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	
	class USCurrencyValidator extends ValuePatternValidator {
		public function __construct(?IErrorHandler $errorHandler = null) {
			parent::__construct("USCurrencyValidator", "not a valid us currency", '^(\s*|[-]?[$]?\d{1,3}(?:,?\d{3})*\.?\d{0,2})$', $errorHandler);
		}
	}
?>