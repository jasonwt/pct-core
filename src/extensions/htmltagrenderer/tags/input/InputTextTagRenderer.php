<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer\tags\input;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\htmltagrenderer\tags\input\InputTagRenderer;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;

	class InputTextTagRenderer extends InputTagRenderer implements IHTMLTagRenderer {
		public function __construct(array $attributes = [], ?IErrorHandler $errorHandler = null) {
			$this->validTagAttributes += [
				"placeholder" => "",
				"readonly" => false,
				"autocomplete"=>"", // on|off
				"pattern" => "",
				"maxlength" => "",
				"minlength" => "",
				"required" => false,
				"size" => ""
			];

			parent::__construct("text", $attributes, $errorHandler);
		}
	}
?>