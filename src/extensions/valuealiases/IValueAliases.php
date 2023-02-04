<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\valuealiases;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\extensions\IExtension;

	interface IValueAliases extends IExtension {
		public function AddValueAlias(string $value, $alias) : bool;
		public function RemoveValueAlias(string $value, $alias = null) : bool;
		public function GetValueAlias(string $value);
		public function GetAliasValue($alias) : ?string;
	}

	

?>