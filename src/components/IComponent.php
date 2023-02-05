<?php	
    declare(strict_types=1);

	namespace pct\core\components;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\ICore;
	use pct\core\extensions\IExtension;
	
	interface IComponent extends ICore {
		public function __set($name, $value);
		public function &__get($name);
		public function __isset($name);

		public function SetValue($value) : bool;
		public function GetValue();

		/************************************ COMPONENTS ************************************/		

		/**
		 * 
		 * @param string|IComponent $component 
		 * @return bool 
		 */
		public function RegisterComponent($component) : bool;
		public function UnregisterComponent(string $name): bool;
		public function GetComponent(string $name) : ?IComponent;
		public function GetComponents(string $derivedFrom = "") : ?array;

		/************************************ EXTENSIONS ************************************/		

		public function RegisterExtension(IExtension $extension) : bool;
		public function UnregisterExtension(string $name): bool;
		public function ExtensionExists(string $name) : bool;
		public function GetExtension(string $name) : ?IExtension;
		public function GetExtensions(string $derivedFrom = "") : ?array;
	}
	
?>