<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer\tags\input;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\htmltagrenderer\tags\input\InputTagRenderer;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;

	class InputDateTimeLocalTagRenderer extends InputTagRenderer implements IHTMLTagRenderer {
		public function __construct(array $attributes = [], ?IErrorHandler $errorHandler = null) {
			$this->validTagAttributes = ["value" => ""] + $this->validTagAttributes + [
				"min" => "", // YYYY-MM-DDThh:mm
				"max" => "", // YYYY-MM-DDThh:mm
				"step" => "", // number.  milliseconds, default 60
				"autocomplete" => "", // on|off
				"required" => false,
				"readonly" => false
			];

			parent::__construct("datetime-local", $attributes, $errorHandler);
		}
	}
?>