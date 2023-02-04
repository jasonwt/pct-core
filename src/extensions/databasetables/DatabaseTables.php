<?php	
	declare(strict_types=1);	

	namespace pct\core\extensions\databasetables;

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	use pct\core\extensions\Extension;
	use pct\core\errorhandlers\IErrorHandler;

	use pct\core\extensions\databasetables\IDatabaseTables;
	
	class DatabaseTables extends Extension implements IDatabaseTables {
		public function __construct(string $tableNames, ?IErrorHandler $errorHandler = null) {
			parent::__construct("DatabaseTables", ["tables" => []], $errorHandler);

			if (($tableNames = trim($tableNames)) == "")
				return $this->TriggerError("Invalid tableNames '$tableNames'.");

			foreach (explode(",", $tableNames) as $tableName) {
				if (($tableName = trim($tableName)) == "")
					continue;

				$tableNamesAttribute[] = $tableName;

				$this[$tableName] = [
					"primaryKey" => "",
					"indexKeys" => [],
					"uniqueKeys" => [],
					"autoIncrementingKeys" => "",
					"fields" => []
				];				
			}

			$this["tables"] = $tableNamesAttribute;			
		}

		function GetRequiredExtensions() : array {
			return ["DatabaseLink" => "*"];
		}

		public function RegisterCallback() : bool {
			if (!parent::RegisterCallback())
				return false;

			foreach ($this["tables"] as $tableName) {
				$queryResults = $this->GetParent()->Query("DESCRIBE $tableName");

				$attributeValue = $this[$tableName];

				while ($row = $queryResults->FetchRow()) {
					list ($fieldName, $fieldType, $fieldNull, $fieldKey, $fieldDefault, $fieldExtra) = $row;			
	
					$fieldTypeParts = explode("(", $fieldType, 2);
	
					$fieldType = array_shift($fieldTypeParts);
					$fieldSize = "";
	
					if (count($fieldTypeParts) > 0) {
						if (strstr($fieldTypeParts[0], ")") !== false)
							list ($fieldSize, $fieldTypeParts[0]) = explode(")", $fieldTypeParts[0]);
	
						if (($fieldTypeParts[0] = trim($fieldTypeParts[0])) != "")
							$fieldExtra .= (trim($fieldExtra) != "" ? " " : "") . $fieldTypeParts[0];
					}				
	
					$attributeValue["fields"][$fieldName] = [
						"fieldName" => $fieldName,
						"fieldType" => $fieldType,
						"fieldSize" => $fieldSize,
						"fieldNull" => ($fieldNull == "YES" ? true : false),
						"fieldKey" => $fieldKey,
						"fieldDefault" => $fieldDefault,
						"fieldExtra" => $fieldExtra
					];
	
					if ($fieldKey == "PRI")
						$attributeValue["primaryKey"] = $fieldName;
					else if ($fieldKey == "MUL")
						$attributeValue["indexKeys"][] = $fieldName;
					else if ($fieldKey == "UNI")
						$attributeValue["uniqueKeys"][] = $fieldName;
	
					foreach (explode(" ", $fieldExtra) as $extra) {
						if (($extra = trim($extra)) == "")
							continue;

						if ($extra == "auto_increment")
							$attributeValue["autoIncrementingKeys"] = $fieldName;
					}

					$this[$tableName] = $attributeValue;
					

					$this->GetParent()->RegisterComponent($tableName . "_" . $fieldName);
				}
			}

			return true;
		}

		public function GetSelectFieldNames(string $tableName) : array {
			$selectFieldNames = [];

			$tableAttributes = $this->GetAttributeValue($tableName);

			foreach ($tableAttributes["fields"] as $fieldName => $fieldInfo)
				$selectFieldNames[$tableName . "." . $fieldName] = "$tableName" . "." . $fieldName . " AS $tableName" . "_" . $fieldName;

			return $selectFieldNames;			
		}

		public function GetInsertFieldValues(string $tableName) : array {
			$insertFieldValues = [];

			$tableAttributes = $this->GetAttributeValue($tableName);

			foreach ($tableAttributes["fields"] as $fieldName => $fieldInfo) {
				$databaseFieldName = $tableName . "." . $fieldName;
				$componentName = $tableName . "_" . $fieldName;
				$componentValue = $this->GetParent()->GetComponentValue($componentName);

				$includeInInsert = true;

				if (str_replace($tableName . "_", "", $componentName) == $tableAttributes["autoIncrementingKeys"]) {
					if (intval($componentValue) == 0)
						$includeInInsert = false;
				}

				if ($includeInInsert)
					$insertFieldValues[$componentName] = $databaseFieldName . "='" . $this->GetParent()->EscapeString((string) $componentValue) . "'";
			}

			return $insertFieldValues;			
		}

		public function LoadFromDatabase(string $tableNames = "", string $whereQuery = ""): bool {
			if (($tableNames = trim($tableNames)) == "")
				$tableNames = implode(",", $this->GetAttributeValue("tables"));

			$selectFieldNames = [];

			$tableNames = explode(",", $tableNames);

			for ($cnt = 0; $cnt < count($tableNames); $cnt ++) {
				if (($tableNames[$cnt] = trim($tableNames[$cnt])) == "")
					unset($tableNames[$cnt]);
				else
					$selectFieldNames += $this->GetParent()->GetSelectFieldNames($tableNames[$cnt]);
			}
			
			$query = "SELECT " . implode(", ", $selectFieldNames) . " FROM " . implode(", ", $tableNames);

			if (($whereQuery = trim($whereQuery)) != "")
				$query .= " WHERE $whereQuery";

			$queryResults = $this->GetParent()->Query($query);

			if ($this->GetParent()->DatabaseErrno()) {
				$this->errorHandler->RegisterError(
					"Database Error\n" .
					"Errno: " . $this->GetParent()->DatabaseErrno() . "\n" . 
					"Error: " . $this->GetParent()->DatabaseError() . "\n" . 
					"Query: $query\n\n"
				);

				return false;
			} 

			if ($row = $queryResults->FetchAssoc())
				$this->GetParent()->SetComponentValues($row);				

			return true;
		}

		public function WriteToDatabase(string $tableNames = ""): bool {
			if (($tableNames = trim($tableNames)) == "")
				$tableNames = implode(",", $this->GetAttributeValue("tables"));

			$tableNames = explode(",", $tableNames);

			for ($cnt = 0; $cnt < count($tableNames); $cnt ++) {
				$tableName = $tableNames[$cnt];

				if (($tableName = trim($tableName)) == "") {
					unset($tableNames[$cnt]);
				} else {
					$tableAttributes = $this->GetAttributeValue($tableName);

					$insertFieldValues = $this->GetParent()->GetInsertFieldValues($tableName);

					$sqlFunction = "INSERT INTO";

					$primaryKeyComponentValue = (($primaryKey = $tableAttributes["primaryKey"]) != "" ? $this->GetParent()->GetComponentValue($tableName . "_" . $primaryKey) : "");
					
					if ($primaryKey != "" && $primaryKeyComponentValue != "")
						$sqlFunction = "UPDATE";						

					$query = $sqlFunction . " $tableName SET " . implode(", ", $insertFieldValues);

					if ($sqlFunction == "UPDATE")
						$query .= " WHERE $tableName.$primaryKey='" . $this->GetParent()->EscapeString($primaryKeyComponentValue) . "' LIMIT 1";					

					$this->GetParent()->Query($query);

					if ($this->GetParent()->DatabaseErrno()) {
						$this->errorHandler->RegisterError(
							"Database Error\n" .
							"Errno: " . $this->GetParent()->DatabaseErrno() . "\n" . 
							"Error: " . $this->GetParent()->DatabaseError() . "\n" . 
							"Query: $query\n\n"
						);

						return false;
					}

					if (($autoIncrementingComponentName = $tableAttributes["autoIncrementingKeys"]) != "") {
						if ($sqlFunction == "INSERT INTO")
							$this->GetParent()->SetComponentValue($tableName . "_" . $autoIncrementingComponentName, $this->GetParent()->InsertId());
					}	
				}
			}

			return true;			
		}
	}

?>