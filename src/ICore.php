<?php	
    declare(strict_types=1);

	namespace pct\core;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use ArrayAccess;
	use Iterator;
	use pct\core\components\IComponent;

	interface ICore extends ArrayAccess, Iterator {
		/************************************ GET PROPERTIES ************************************/

		public function GetParent() : ?IComponent;
		public function GetName() : string;
		public function GetVersion() : string;

		/************************************ ERROR HANDLER ************************************/

		public function GetFirstError(bool $peek = false) : ?string;
		public function GetLastError(bool $peek = false) : ?string;
		public function GetErrors() : array;

		/************************************ ATTRIBUTES ************************************/

		public function SetAttributeValues(array $values) : bool;
		public function GetAttributes() : array;

		/************************************ MAGIC ************************************/

		public function __call(string $methodName, array $arguments);	
	}
	
?>