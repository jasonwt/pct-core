<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\databaselink;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\errorhandlers\ErrorHandler;

	abstract class DatabaseLinkResult implements IDatabaseLinkResult {
		protected IErrorHandler $errorHandler;

		public function __construct(?IErrorHandler $errorHandler = null) {
			$this->errorHandler = (is_null($errorHandler) ? new ErrorHandler() : $errorHandler);
		}

		public function FetchRow() {
			return $this->FetchArray(static::RESULTS_MODE_NUM);
		}

		public function FetchAssoc() {
			return $this->FetchArray(static::RESULTS_MODE_ASSOC);
		}

		public function FetchAll(int $resultsMode = self::RESULTS_MODE_ALL) {
			$returnValue = [];

			while ($results = $this->FetchArray($resultsMode))
				$returnValue[] = $results;

			return $returnValue;
		}
	}

?>