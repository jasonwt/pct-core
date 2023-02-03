<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\databasetables;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\extensions\IExtension;

	interface IDatabaseTables extends IExtension {				
		public function LoadFromDatabase(string $tableNames, string $whereQuery) : bool;
		public function WriteToDatabase(string $tableName = "") : bool;
		public function GetSelectFieldNames(string $tableName) : array;
		public function GetInsertFieldValues(string $tableName) : array;
	}

?>