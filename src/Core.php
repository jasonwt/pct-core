<?php	
    declare(strict_types=1);

	namespace pct\core;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\ICore;
	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\errorhandlers\ErrorHandler;
	use pct\core\components\IComponent;

	abstract class Core implements ICore {
		protected ?IComponent $parent = null;
		protected string $name = "";
		protected string $version = "";

		protected ErrorHandler $errorHandler;

		protected array $attributes = [];

		public function __construct(string $name, array $attributes = [], ?IErrorHandler $errorHandler = null) { 
			$this->errorHandler = (is_null($errorHandler) ? new ErrorHandler() : $errorHandler);

			if (!$this->VerifyCoreName($this->name = trim($name)))
				$this->errorHandler->RegisterError("Invalid Core Name '$name'", ErrorHandler::TYPE_FATAL);

			if (count($attributes) > 0) {
				foreach ($attributes as $k => $v) {
					if(array_keys($attributes) !== range(0, count($attributes) - 1)) 
						$this->RegisterAttribute($k, $v);
					else
						$this->RegisterAttribute($v, null);
				}
			}
		}

		/************************************ PUBLIC PROPERTIES GET/SET ************************************/

		public function GetParent() : ?IComponent {
			return $this->parent;
		}

		public function GetName() : string {
			return $this->name;
		}

		public function GetVersion() : string {
			return $this->version;
		}

		/************************************ PROTECTED VERIFIES ************************************/

		protected function VerifyCoreName(string $name) : bool {
			return trim($name) != "";
		}

		protected function VerifyAttributeName(string $name) : bool {
			return trim($name) != "";
		}

		/************************************ PROTECTED CALLBACKS ************************************/

		protected function RegisterCallback() : bool {
			return true;
		}

		protected function UnregisterCallback() : bool {
			$this->parent = null;
			return true;
		}

		/************************************ ERROR HANDLER ************************************/

		public function GetFirstError(bool $peek = false) : ?string {
			return $this->errorHandler->GetFirstError($peek);
		}

		public function GetLastError(bool $peek = false) : ?string {
			return $this->errorHandler->GetLastError($peek);
		}

		public function GetErrors() : array {
			return $this->errorHandler->GetErrors();
		}

		/************************************ ATTRIBUTES ************************************/		

		public function RegisterAttribute(string $name, $defaultValue) : bool {
			if (!$this->VerifyAttributeName($name = trim($name)))
				$this->errorHandler->RegisterError("Invalid Attribute Name '$name'", ErrorHandler::TYPE_ERROR);					

			if (array_key_exists($name, $this->attributes))
				$this->errorHandler->RegisterError("Attribute with name '$name' already exists", ErrorHandler::TYPE_WARNING);

			$this->attributes[$name] = $defaultValue;

			return true;
		}

		public function UnregisterAttribute(string $name): bool {
			if (array_key_exists($name, $this->attributes))
				$this->errorHandler->RegisterError("Attribute with name '$name' does not exist", ErrorHandler::TYPE_WARNING);

			unset($this->attributes[$name]);

			return true;
		}

		public function AttributeExists(string $name) : bool {
			return array_key_exists($name, $this->attributes);
		}

		public function SetAttributeValue(string $name, $value) : bool {
			if (!array_key_exists($name, $this->attributes)) {
				$this->errorHandler->RegisterError("Attribute with name '$name' does not exist", ErrorHandler::TYPE_WARNING);					

				return $this->RegisterAttribute($name, $value);					
			} else {
				if (!is_null($this->attributes[$name])) {
					if (gettype($value) != gettype($this->attributes[$name]))
						$this->errorHandler->RegisterError("Attribute '$name' set value mismatch.  old: " . gettype($this->attributes[$name]) . ", new: " . gettype($value), ErrorHandler::TYPE_WARNING);
				}
			}

			$this->attributes[$name] = $value;

			return true;
		}

		public function SetAttributeValues(array $values) : bool {
			foreach ($values as $name => $value) {
				if ($this->AttributeExists($name))
					$this->SetAttributeValue($name, $value);
			}

			return true;
		}

		public function GetAttributeValue(string $name) {
			if (!array_key_exists($name, $this->attributes))
				$this->errorHandler->RegisterError("Attribute with name '$name' does not exist", ErrorHandler::TYPE_WARNING);

			return $this->attributes[$name];
		}

		public function GetAttributes() : array {
			return $this->attributes;
		}

		/************************************ MAGIC ************************************/		

		protected function CanCallMethodObjectList(string $methodName, bool $includePublic, bool $includeProtected, bool $includePrivate) : array {
			$returnValue = [];

			if (method_exists($this, $methodName)) {
				$reflection = new \ReflectionMethod($this, $methodName);

				if ($includePublic && $reflection->isPublic())					
					$returnValue[$this->name] = $this;
				else if ($includeProtected && $reflection->isProtected())
					$returnValue[$this->name] = $this;
				else if ($includePrivate && $reflection->isPrivate())
					$returnValue[$this->name] = $this;
			}

			return $returnValue;
		}

		public function __call(string $methodName, array $arguments) {
			$canCallMethodObjectList = $this->CanCallMethodObjectList($methodName, true, false, false);

			if (count($canCallMethodObjectList) > 0)
				if (array_values($canCallMethodObjectList)[0] == $this)
					array_shift($canCallMethodObjectList);

			if (count($canCallMethodObjectList) > 0)
				return call_user_func_array([array_shift($canCallMethodObjectList), $methodName], $arguments);

			$debugBacktrace = debug_backtrace();

			throw new \Exception("\nCall to undefined method {$debugBacktrace[0]["class"]}::$methodName() in {$debugBacktrace[0]["file"]}:{$debugBacktrace[0]["line"]}");
			throw new \Exception("\nCall to undefined method\n");
		}

	}
	
?>