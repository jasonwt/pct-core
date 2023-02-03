<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\validator\value\pattern;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	
	class NegativeIntgerValidator extends ValuePatternValidator {
		public function __construct(?IErrorHandler $errorHandler = null) {
			parent::__construct("NegativeIntegerValidator", "not a valid negative integer", '^(\s*|-[0-9]+)$', $errorHandler);
		}
	}
?>