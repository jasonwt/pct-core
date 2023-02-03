<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\validator;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\extensions\Extension;
	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\validator\IValidate;
	use pct\core\extensions\validator\IValidator;

	class Validate extends Extension implements IValidate {		
		public function __construct(array $attributes = [], ?IErrorHandler $errorHandler = null) {
			parent::__construct("Validator", $attributes, $errorHandler);
		}

		public function ValidateComponents() : array {
			$validationErrors = [];

			foreach ($this->GetParent()->GetComponents() as $componentName => $component) {
				$componentErrors = [];

				foreach ($component->GetExtensions("pct\\core\\extensions\\validator\\Validator") as $extensionName => $extension)
					$componentErrors += $extension->ValidateComponent();

				if (count($componentErrors) > 0)
					$validationErrors[$componentName] = $componentErrors;
			}

			return $validationErrors;
		}

		public function AddComponentValidator(IValidator $validator, string $componentNames = "") : bool {
			if (count($funcGetArgs = func_get_args()) == 1)
				$funcGetArgs = array_keys($this->GetParent()->GetComponents());
			else
				array_shift($funcGetArgs);

			foreach ($funcGetArgs as $componentName) {
				$component = $this->GetParent()->GetComponent($componentName);

				if ($component->ExtensionExists($validator->GetName()))
					continue;

				if (!$component->RegisterExtension(clone $validator))
					return false;
			}

			return true;
		}

		public function RemoveComponentValidator(string $validatorName) : bool {
			if (count($funcGetArgs = func_get_args()) == 1)
				$funcGetArgs = array_keys($this->GetParent()->GetComponents());
			else
				array_shift($funcGetArgs);

			foreach ($funcGetArgs as $componentName) {
				$component = $this->GetParent()->GetComponent($componentName);

				if (!$component->ExtensionExists($validatorName))
					continue;

				if (!$component->UnregisterExtension($validatorName))
					return false;
			}

			return true;
		}
		
	}


?>