<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\validator;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\extensions\IExtension;
	use pct\core\extensions\validator\IValidator;

	interface IValidate extends IExtension {
		public function ValidateComponents() : array;
		public function AddComponentValidator(IValidator $validator, string $componentNames = "") : bool;
		public function RemoveComponentValidator(string $name) : bool;
	}

	

?>