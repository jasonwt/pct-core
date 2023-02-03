<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\validator;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\extensions\IExtension;

	interface IValidator extends IExtension {
		public function ValidateComponent() : array;
	}

?>