<?php	
    declare(strict_types=1);

	namespace pct\core\extensions;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\ICore;

	interface IExtension extends ICore {
		public function GetRequiredExtensions() : array;		
	}
	
?>