<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\validator\value\pattern;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\validator\IValidator;
	use pct\core\extensions\validator\Validator;

	class ValuePatternValidator extends Validator implements IValidator {
		public function __construct(string $name, string $errorMessage, string $pattern, ?IErrorHandler $errorHandler = null) {
			parent::__construct($name, $errorMessage, $errorHandler);

			$this->RegisterAttribute("pattern", $pattern);
		}

		public function ValidateComponent(): array {	
			if (preg_match("~" . $this->GetAttributeValue("pattern") . "~", (string) $this->GetParent()->GetValue()) != 1)
				return [$this->GetName() => $this->GetAttributeValue("errorMessage")];

			return [];			
		}
	}


?>