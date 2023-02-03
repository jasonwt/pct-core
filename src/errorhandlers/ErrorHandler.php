<?php	
    declare(strict_types=1);

	namespace pct\core\errorhandlers;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;

	class ErrorHandler implements IErrorHandler {
		protected $errors = [];

		public function __construct() {

		}

		public function GetFirstError(bool $peek = false) : ?string {
			return (
				count($this->errors) == 0 ? null : (
					implode(":", ($peek ? $this->errors[array_key_first($this->errors)] : array_shift($this->errors)))
				)
			);
		}

		public function GetLastError(bool $peek = false) : ?string {
			return (
				count($this->errors) == 0 ? null : (
					implode(":", ($peek ? $this->errors[array_key_last($this->errors)] : array_pop($this->errors)))
				)
			);

			return (count($this->errors) == 0 ? null : implode(":", array_pop($this->errors)));
		}

		public function GetErrors() : array {
			return $this->errors;
		}

		public function ClearErrors() : bool {
			$this->errors = [];

			return true;
		}

		public function RegisterError(string $message, int $errorType = self::TYPE_FATAL, int $errorCode = 0) {
			$this->errors[] = [
				"type" => $errorType,
				"code" => $errorCode,
				"message" => $message
			];

			throw new \Exception(print_r($this->errors, true));
		}

	}
	
?>