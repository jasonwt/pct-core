<?php	
// https://stitcher.io/blog/dealing-with-deprecations
    declare(strict_types=1);

	namespace pct\core\components;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	

	use pct\core\Core;
	use pct\core\components\IComponent;
	use pct\core\extensions\IExtension;
	use pct\core\errorhandlers\IErrorHandler;

	
	class Component extends Core implements IComponent {
		protected $value = null;

		protected array $components = [];
		protected array $extensions = [];

		public function __construct(string $name, $value = null, array $attributes = [], $components = null, $extensions = null, ?IErrorHandler $errorHandler = null) {
			parent::__construct($name, $attributes, $errorHandler);

			$this->value = $value;

			if (!is_null($components)) {
				if (!is_array($components))
					$components = [$components];

				foreach ($components as $component)
					$this->RegisterComponent($component);
			}

			if (!is_null($extensions)) {
				if (!is_array($extensions))
					$extensions = [$extensions];

				foreach ($extensions as $extension)
					$this->RegisterExtension($extension);
			}
		}

		

		protected function ValidateComponentValue(string $name, $value): bool {
			return true;
		}

		/************************************ PUBLIC PROPERTIES GET/SET ************************************/

		public function __set($name, $value) {
			if (!isset($this->components[$name])) {
				$this->errorHandler->RegisterError("Component '$name' does not exist.");
				return null;
			}

			return $this->components[$name]->SetValue($value);
		}

		public function &__get($name) {
			echo "calling_get\n";
			if (!isset($this->components[$name])) {
				$n = null;
				$this->errorHandler->RegisterError("Component '$name' does not exist.");
				return $n;
			}

			return $this->components[$name]->value;
		}

		public function __isset($name) {
			return isset($this->components[$name]);
		}

		public function GetValue() {
			return $this->value;
		}

		public function SetValue($value) : bool {
			$this->value = $value;
			return true;
		}

		/************************************ COMPONENTS ************************************/		

		public function RegisterComponent($component) : bool {
			foreach (func_get_args() as $component) {
				if (is_string($component))
					$component = new Component($component);
				else if (!($component instanceof Component))
					$this->errorHandler->RegisterError("Invalid parameter for component.  Expected string:name or IComponent:object", IErrorHandler::TYPE_ERROR);
				
				if (!$this->VerifyCoreName($name = trim($component->GetName())))
					$this->errorHandler->RegisterError("Invalid Component Name '$name'", IErrorHandler::TYPE_ERROR);

				if (property_exists($this, $name))
					$this->errorHandler->RegisterError("A property with the name '$name' already exists", IErrorHandler::TYPE_WARNING);

				if (array_key_exists($name, $this->components))
					$this->errorHandler->RegisterError("A Component with the name '$name' already exists", IErrorHandler::TYPE_WARNING);

				if ($component->GetParent() != "")
					$this->errorHandler->RegisterError("A Component with the name '$name' is already registered with parent '" . $component->GetParent()->GetName() . "'", IErrorHandler::TYPE_WARNING);

				$component->parent = $this;
				$this->components[$component->GetName()] = $component;
				$this->components[$component->GetName()]->RegisterCallback();
			}

			return true;
		}

		public function UnregisterComponent(string $name): bool {
			if (!isset($this->components[$name]))
				$this->errorHandler->RegisterError("Component with name '$name' does not exist", IErrorHandler::TYPE_WARNING);

			$component = $this->components[$name];

			unset($this->components[$name]);

			$component->UnregisterCallback();

			return true;
		}

		public function ComponentExists(string $name) : bool {
			return isset($this->components[$name]);			
		}

		public function GetComponentValue(string $name) {
			if (!array_key_exists($name, $this->components))
				$this->errorHandler->RegisterError("Component with name '$name' does not exist", IErrorHandler::TYPE_WARNING);

			return $this->components[$name]->GetValue();			
		}

		public function SetComponentValue(string $name, $value) : bool {
			if (!array_key_exists($name, $this->components))
				$this->errorHandler->RegisterError("Component with name '$name' does not exist", IErrorHandler::TYPE_WARNING);

			$this->components[$name]->SetValue($value);

			return true;
		}

		public function SetComponentValues(array $values) : bool {
			foreach ($values as $name => $value) {
				if (array_key_exists($name, $this->components))
					$this->components[$name]->SetValue($value);					
			}
			
			return true;
		}

		public function GetComponent(string $name) : ?IComponent {
			if (!array_key_exists($name, $this->components))
				$this->errorHandler->RegisterError("Component with name '$name' does not exist", IErrorHandler::TYPE_WARNING);

			return $this->components[$name];
		}

		public function GetComponents(string $derivedFrom = "") : ?array {
			$returnValue = [];

			if (count($funcGetArgs = func_get_args()) == 0)
				return $this->components;

			foreach ($funcGetArgs as $derivedFrom) {
				if (!is_string($derivedFrom)) {
					$this->errorHandler->RegisterError("Expected type string for derivedFrom parameter.");
					continue;
				}

				if (($derivedFrom = trim($derivedFrom)) == "")
					continue;

				$returnValue += array_filter($this->components, function ($v, $k) use ($derivedFrom) {
					return is_a($v, $derivedFrom);
				}, ARRAY_FILTER_USE_BOTH);				
			}
			
			return $returnValue;
		}

		/************************************ EXTENSIONS ************************************/		

		public function RegisterExtension(IExtension $extension) : bool {
			foreach (func_get_args() as $extension) {
				if (!($extension instanceof IExtension))
					$this->errorHandler->RegisterError("Invalid parameter for extension.  Expected IExtension:object", IErrorHandler::TYPE_ERROR);

				if (!$this->VerifyCoreName($name = trim($extension->GetName())))
					$this->errorHandler->RegisterError("Invalid extension Name '$name'", IErrorHandler::TYPE_ERROR);						

				if (array_key_exists($name, $this->extensions))
					$this->errorHandler->RegisterError("Extension with name '$name' already exists", IErrorHandler::TYPE_WARNING);

				if ($extension->GetParent() != "")
					$this->errorHandler->RegisterError("Extension '$name' is already registered with parent '" . $extension->GetParent()->GetName() . "'", IErrorHandler::TYPE_WARNING);

				$extension->parent = $this;

				$this->extensions[$extension->GetName()] = $extension;
				$this->extensions[$extension->GetName()]->RegisterCallback();		
			}

			return true;
		}

		public function UnregisterExtension(string $name): bool {
			if (!isset($this->extensions[$name]))			
				$this->errorHandler->RegisterError("Extension with name '$name' does not exist", IErrorHandler::TYPE_WARNING);

			$extension = $this->extensions[$name];

			unset($this->extensions[$name]);

			$extension->UnregisterCallback();

			return true;
		}

		public function ExtensionExists(string $name) : bool {
			return isset($this->extensions[$name]);			
		}

		public function GetExtension(string $name) : ?IExtension {
			if (!array_key_exists($name, $this->extensions))
				$this->errorHandler->RegisterError("Extension with name '$name' does not exist", IErrorHandler::TYPE_WARNING);

			return $this->extensions[$name];
		}

		public function GetExtensions(string $derivedFrom = "") : ?array {
			$returnValue = [];

			if (count($funcGetArgs = func_get_args()) == 0)
				return $this->extensions;

			foreach ($funcGetArgs as $derivedFrom) {
				if (!is_string($derivedFrom)) {
					$this->errorHandler->RegisterError("Expected type string for derivedFrom parameter.");
					continue;
				}

				if (($derivedFrom = trim($derivedFrom)) == "")
					continue;

				$returnValue += array_filter($this->extensions, function ($v, $k) use ($derivedFrom) {
					return is_a($v, $derivedFrom);
				}, ARRAY_FILTER_USE_BOTH);				
			}
			
			return $returnValue;
		}

		/************************************ MAGIC ************************************/		

		protected function CanCallMethodObjectList(string $methodName, bool $includePublic, bool $includeProtected, bool $includePrivate) : array {
			$returnValue = parent::CanCallMethodObjectList($methodName, $includePublic, $includeProtected, $includePrivate);

			foreach ($this->extensions as $extensionName => $extension) {
				$results = $extension->CanCallMethodObjectList($methodName, $includePublic, $includeProtected, $includePrivate);

				foreach ($results as $k => $v)
					$returnValue[$k] = $v;
			}			

			return $returnValue;
		}
	}
	
?>