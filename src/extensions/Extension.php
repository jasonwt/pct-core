<?php	
    declare(strict_types=1);

	namespace pct\core\extensions;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\Core;
	use pct\core\errorhandlers\IErrorHandler;

	class Extension extends Core implements IExtension {
		public function GetRequiredExtensions() : array {
			return [];
		}

		public function RegisterCallback() : bool {
			foreach ($this->GetRequiredExtensions() as $extensionName => $extensionVersion) {
				if (!$this->GetParent()->ExtensionExists("$extensionName"))
					$this->errorHandler->RegisterError("Requires extension '$extensionName'", IErrorHandler::TYPE_FATAL);
			}

			return true;
		}
	}
	
?>