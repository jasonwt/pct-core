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
		private $iteratorPosition = 0;

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
						$this[$k] = $v;
					else
						$this[$v] = null;
				}
			}
		}

		/************************************ PROTECTED VERIFIES ************************************/

		protected function VerifyCoreName($name) : bool {
			return trim($name) != "";
		}

		protected function VerifyAttributeName($name) : bool {
			return trim($name) != "";
		}

		protected function ValidateAttributeValue($name, $value): bool {
			return true;
		}

		/************************************ ArrayAccess Methods ************************************/
		
	
		public function rewind(): void {
			$this->iteratorPosition = 0;
		}
	
		#[\ReturnTypeWillChange]
		public function current() {
			return $this->attributes[array_keys($this->attributes)[$this->iteratorPosition]];
		}
	
		#[\ReturnTypeWillChange]
		public function key() {
			return array_keys($this->attributes)[$this->iteratorPosition];			
		}
	
		public function next(): void {
			++$this->iteratorPosition;
		}
	
		public function valid(): bool {
			return ($this->iteratorPosition <= count($this->attributes));			
		}

		/************************************ ArrayAccess Methods ************************************/

		#[\ReturnTypeWillChange]
		public function offsetExists($offset): bool {
			return array_key_exists($offset, $this->attributes);
		}

		#[\ReturnTypeWillChange]
		public function offsetGet($offset) { 
			if (!isset($this->attributes[$offset])) {
				$this->errorHandler->RegisterError("attribute '$offset' is not set");

				return null;
			}

			return $this->attributes[$offset];
		}

		#[\ReturnTypeWillChange]
		public function offsetSet($offset, $value): void {
			if (!$this->VerifyAttributeName($offset)) {
				$this->errorHandler->RegisterError("Invalid attribute name '" . print_r($offset) . "'");
				return;
			}

			if (!$this->ValidateAttributeValue($offset, $value)) {
				$this->errorHandler->RegisterError("Invalid attribute value for name '$offset'");
				return;
			}

			$this->attributes[$offset] = $value;
		}

		#[\ReturnTypeWillChange]
		public function offsetUnset($offset): void { 
			if (!isset($this->attributes[$offset]))
				$this->errorHandler->RegisterError("attribute '$offset' is not set");
			else
				unset($this->attributes[$offset]);
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

		public function SetAttributeValues(array $values) : bool {
			foreach ($values as $name => $value) {
				if (isset($this[$name]))
					$this[$name] = $value;				
			}

			return true;
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