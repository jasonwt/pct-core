<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\databaselink;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\extensions\IExtension;

	interface IDatabaseLink extends IExtension {
		const RESULT_MODE_STORE = 1;
		const RESULT_MODE_USE = 2;
		const RESULT_MODE_ASYNC = 3;

		public function DatabaseErrno() : string;
		public function DatabaseError() : string;
		public function DatabaseConnectErrno() : string;
		public function DatabaseConnectError() : string;

		public function AffectedRows() : string;
		public function FieldCount() : string;
		public function InsertId() : string;

		public function EscapeString(string $string) : string;
		public function Query(string $query, int $resultsMode = self::RESULT_MODE_STORE);
	}

?>