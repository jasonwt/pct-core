<?php	
    declare(strict_types=1);	

	namespace pct\core\debugging\debugstring;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	interface IDebugString {
		static function DebugString() : string;
	}

	

?>