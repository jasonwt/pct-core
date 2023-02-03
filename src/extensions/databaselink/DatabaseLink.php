<?php	
	declare(strict_types=1);	

	namespace pct\core\extensions\databaselink;

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\Extension;
	use pct\core\extensions\databaselink\IDatabaseLink;
	
	abstract class DatabaseLink extends Extension implements IDatabaseLink {
		public function __construct(array $attributes = [], ?IErrorHandler $errorHandler = null) {
			parent::__construct("DatabaseLink", $attributes, $errorHandler);
		}
	}