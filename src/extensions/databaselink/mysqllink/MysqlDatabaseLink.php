<?php	
	declare(strict_types=1);	

	namespace pct\core\extensions\databaselink\mysqllink;

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	use pct\core\errorhandlers\ErrorHandler;
	use pct\core\extensions\databaselink\DatabaseLink;
	use pct\core\extensions\databaselink\mysqllink\IMysqlDatabaseLink;
	use pct\core\extensions\databaselink\mysqllink\MysqlDatabaseLinkResult;
	
	class MysqlDatabaseLink extends DatabaseLink implements IMysqlDatabaseLink {
		protected \mysqli $dbLink;

		public function __construct(\mysqli $dbLink, array $attributes = [], ?ErrorHandler $errorHandler = null) {
			$this->dbLink = $dbLink;

			parent::__construct($attributes, $errorHandler);
		}

		public function DatabaseErrno(): string { 
			return (string) $this->dbLink->errno;
		}

		public function DatabaseError(): string { 
			return (string) $this->dbLink->error;
		}

		public function DatabaseConnectErrno(): string { 
			return (string) $this->dbLink->connect_errno;
		}

		public function DatabaseConnectError(): string { 
			return (string) $this->dbLink->connect_error;
		}

		public function AffectedRows(): string { 
			return (string) $this->dbLink->affected_rows;
		}

		public function FieldCount(): string { 
			return (string) $this->dbLink->field_count;
		}

		public function InsertId(): string { 
			return (string) $this->dbLink->insert_id;
		}

		public function EscapeString(string $str): string { 
			return $this->dbLink->real_escape_string($str);
		}

		public function Query(string $query, int $resultsMode = self::RESULT_MODE_STORE) {
			if ($resultsMode == static::RESULT_MODE_STORE)
				$resultsMode = MYSQLI_STORE_RESULT;
			else if ($resultsMode == static::RESULT_MODE_USE)
				$resultsMode = MYSQLI_USE_RESULT;
			else if ($resultsMode == static::RESULT_MODE_ASYNC)
				$resultsMode = MYSQLI_ASYNC;
			else
				throw new \Exception("Invalid resultsMode '$resultsMode'");		

			if (is_bool($results = $this->dbLink->query($query, $resultsMode)))
				return $results;

			return new MysqlDatabaseLinkResult($results);
		}
	}