<?php	
    declare(strict_types=1);	

	namespace pct\core\debugging\debugstring;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\ICore;
	use pct\core\components\IComponent;
	use pct\core\debugging\debugstring\IDebugString;
	use pct\core\extensions\IExtension;

	class DebugString implements IDebugString {
		protected $objectTypesHander = [];

		public function __construct() {
		}

		static protected function CoreObjectString(ICore $obj, int $indentLevel = 0) : string {
			$returnValue = str_repeat("\t", $indentLevel) . get_class($obj) . "\n\n";

			$returnValue .= str_repeat("\t", $indentLevel+1) . "Name:     " . $obj->GetName() . "\n";
			$returnValue .= str_repeat("\t", $indentLevel+1) . "Parent:   " . (!is_null($obj->GetParent()) ? $obj->GetParent()->GetName() : "(null)") . "\n";
			$returnValue .= str_repeat("\t", $indentLevel+1) . "Version:  " . $obj->GetVersion() . "\n";

			if ($obj instanceof IComponent)
				$returnValue .= str_repeat("\t", $indentLevel+1) . "Value:    " . trim(str_replace("\n", "\n" . str_repeat("\t", $indentLevel+2), static::DebugTypeString($obj->GetValue()))) . "\n";
			
			$returnValue .= "\n";

			if (count($errors = $obj->GetErrors()) > 0) {
				$returnValue .= str_repeat("\t", $indentLevel+1) . "*** ERRORS ***\n";

				foreach ($errors as $error)
					$returnValue .= str_repeat("\t", $indentLevel+2) . implode(":", $error) . "\n";

				$returnValue .= "\n";
			}

			if (count($attributes = $obj->GetAttributes()) > 0) {
				$returnValue .= str_repeat("\t", $indentLevel+1) . "*** ATTRIBUTES ***\n";

				$maxNameLength = 0;

				foreach ($attributes as $attributeName => $attributeValue)
					$maxNameLength = max($maxNameLength, strlen($attributeName));

				foreach ($attributes as $attributeName => $attributeValue) {
					$returnValue .= 
						str_repeat("\t", $indentLevel+2) . 
						"$attributeName:  " . str_repeat(" ", $maxNameLength - strlen($attributeName)) .
						trim(str_replace("\n", "\n" . str_repeat("\t", $indentLevel+3) . "  ", static::DebugTypeString($attributeValue))) . 
						"\n";
				}

				$returnValue .= "\n";
			}

			if ($obj instanceof IComponent) {
				if (count($extensions = $obj->GetExtensions()) > 0) {
					$returnValue .= str_repeat("\t", $indentLevel+1) . "*** EXTENSIONS ***\n";		
					
					foreach ($extensions as $extensionName => $extension) {
						$returnValue .= static::CoreObjectString($extension, $indentLevel+2);
					}
				}

				if (count($components = $obj->GetComponents()) > 0) {
					$returnValue .= str_repeat("\t", $indentLevel+1) . "*** COMPONENTS ***\n";		
					
					foreach ($components as $componentName => $component) {
						$returnValue .= static::CoreObjectString($component, $indentLevel+2);
					}
				}
			}

			return $returnValue;
		}

		static protected function DebugTypeString($data) {
			$returnValue = "";

			if (is_null($data)) {
				$returnValue .= "(null)";
			} else if (is_string($data)) {
				$returnValue .= $data;
			} else if (is_object($data)) {
				if ($data instanceof ICore) {
					$returnValue .= static::CoreObjectString($data);
				} else {
					$returnValue = print_r($data, true);	
				}
			} else {
				$returnValue = print_r($data, true);
			}

			return $returnValue;
		}

		static public function DebugString() : string {
			$returnValue = "";

			$args = func_get_args();

			$debuggingBacktrace = debug_backtrace();

			for ($cnt = count($debuggingBacktrace)-1; $cnt >= 0; $cnt --) {
				$fileName     = (isset($debuggingBacktrace[$cnt]["file"])     ? $debuggingBacktrace[$cnt]["file"] : "");
				$lineNumber   = (isset($debuggingBacktrace[$cnt]["line"])     ? $debuggingBacktrace[$cnt]["line"] : "");
				$functionName = (isset($debuggingBacktrace[$cnt]["function"]) ? $debuggingBacktrace[$cnt]["function"] : "");
				$functionArgs = (isset($debuggingBacktrace[$cnt]["args"])     ? $debuggingBacktrace[$cnt]["args"] : "");
				$className    = (isset($debuggingBacktrace[$cnt]["class"])    ? $debuggingBacktrace[$cnt]["class"] : "");
				$classType    = (isset($debuggingBacktrace[$cnt]["type"])     ? $debuggingBacktrace[$cnt]["type"] : "");
				$obj          = (isset($debuggingBacktrace[$cnt]["object"])   ? $debuggingBacktrace[$cnt]["object"] : "");

				$returnValue .= $fileName . "[$lineNumber]: ";

				if ($cnt > 0) {
					$returnValue .= ($className != "" ? $className . ($classType == "::" ? "::" : "->") : "");
					$returnValue .= ($functionName != "" ? $functionName . "()" : "");
					$returnValue .= "\n";    
				} else {
					$returnValue .= "\n";

					if (count($args) > 0) {
						foreach ($args as $arg)
							$returnValue .= "\n" . static::DebugTypeString($arg);
							
						$returnValue .= "\n";
					}   
				}
			}        

			$returnValue .= "\n";

			return $returnValue;
		}
	}

?>