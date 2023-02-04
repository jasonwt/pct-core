<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer\tags\input;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\htmltagrenderer\tags\input\InputTagRenderer;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;

	class InputUrlTagRenderer extends InputTagRenderer implements IHTMLTagRenderer {
		public function __construct(array $attributes = [], ?IErrorHandler $errorHandler = null) {
			$this->validTagAttributes += [
				"autocomplete" => "", // on|off
				"list" => "", // The values of the list attribute is the id of a <datalist>
				"maxlength" => "", // number
				"minlength" => "", // number
				"pattern" => "", // pattern
				"placeholder" => "", //
				"readonly" => false,
				"required" => false,
				"size" => "",
				"spellcheck" => "" // true|false
			];

			parent::__construct("url", $attributes, $errorHandler);
		}
	}
?>