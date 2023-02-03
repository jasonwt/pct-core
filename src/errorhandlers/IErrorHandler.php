<?php	
    declare(strict_types=1);

	namespace pct\core\errorhandlers;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	interface IErrorHandler {
		const TYPE_NOTICE = 1;
		const TYPE_WARNING = 2;
		const TYPE_ERROR = 4;
		const TYPE_FATAL = 8;

		public function GetFirstError(bool $peek = false) : ?string;
		public function GetLastError(bool $peek = false) : ?string;
		public function GetErrors() : array;
		public function ClearErrors() : bool;
		public function RegisterError(string $message, int $errorType = self::TYPE_FATAL, int $errorCode = 0);
	}
	
?>