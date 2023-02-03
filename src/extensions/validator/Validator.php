<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\validator;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\extensions\Extension;
	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\validator\IValidator;

	abstract class Validator extends Extension implements IValidator {		
		public function __construct(string $name, string $errorMessage, ?IErrorHandler $errorHandler = null) {			
			parent::__construct($name, ["errorMessage" => trim($errorMessage)], $errorHandler);
		}
	}

?>