<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\validator\value;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\validator\Validator;

	class EmailAddressValidator extends Validator {
		public function __construct(?IErrorHandler $errorHandler = null) {
			parent::__construct("EmailAddressValidator", "not a valid email address", $errorHandler);
		}

		public function ValidateComponent(): array { 
			if (!filter_var($this->GetParent()->GetValue(), FILTER_VALIDATE_EMAIL))
				return [$this->GetName() => $this->GetAttributeValue("errorMessage")];
				 
			return [];
		}
	}
?>