<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer\tags\button;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\htmltagrenderer\tags\button\ButtonTagRenderer;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;

	class ButtonButtonTagRenderer extends ButtonTagRenderer implements IHTMLTagRenderer {
		public function __construct(array $attributes = [], ?IErrorHandler $errorHandler = null) {			
			parent::__construct("button", $attributes, $errorHandler);
		}
	}
?>