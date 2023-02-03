<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\databaselink\mysqllink;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\ErrorHandler;
	use pct\core\extensions\databaselink\DatabaseLinkResult;

	class MysqlDatabaseLinkResult extends DatabaseLinkResult implements IMysqlDatabaseLinkResult {
		protected \mysqli_result $queryResults;

		public function __construct(\mysqli_result $queryResults, ?ErrorHandler $errorHandler = null) {
			parent::__construct($errorHandler);

			$this->queryResults = $queryResults;
		}

		public function NumRows(): string { 
			return (string) $this->queryResults->num_rows;
		}

		public function FetchRow() {
			return $this->queryResults->fetch_row();
		}

		public function FetchAssoc() {		
			return $this->queryResults->fetch_assoc();
		}

		public function FetchArray(int $resultsMode = self::RESULTS_MODE_ALL) {
			if ($resultsMode == static::RESULTS_MODE_ALL)
				$resultsMode = MYSQLI_BOTH;
			else if ($resultsMode == static::RESULTS_MODE_NUM)
				$resultsMode = MYSQLI_NUM;
			else if ($resultsMode == static::RESULTS_MODE_ASSOC)
				$resultsMode = MYSQLI_ASSOC;
			else
				throw new \Exception("Invalid resultsMode '$resultsMode'");
			
			return $this->queryResults->fetch_array($resultsMode);
		}
		
	}

?>