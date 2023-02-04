<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer\tags\input;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\htmltagrenderer\tags\input\InputTagRenderer;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;

	class InputHiddenTagRenderer extends InputTagRenderer implements IHTMLTagRenderer {
		public function __construct(array $attributes = [], ?IErrorHandler $errorHandler = null) {
			$this->validTagAttributes += [];

			parent::__construct("hidden", $attributes, $errorHandler);
		}
	}
?>