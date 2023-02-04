<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer\tags\input;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\htmltagrenderer\tags\input\InputTagRenderer;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;

	class InputNumberTagRenderer extends InputTagRenderer implements IHTMLTagRenderer {
		public function __construct(array $attributes = [], ?IErrorHandler $errorHandler = null) {
			$this->validTagAttributes += [
				"list" => "", // The values of the list attribute is the id of a <datalist>
				"min" => "",  // yyyy-MM
				"max" => "",  // yyyy-MM
				"step" => "",
				"readonly" => false,
				"placeholder" => "",
				"required" => false
			];

			parent::__construct("number", $attributes, $errorHandler);
		}
	}
?>