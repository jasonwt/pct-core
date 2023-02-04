<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer\tags\input;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\htmltagrenderer\tags\input\InputTagRenderer;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;

	class InputImageTagRenderer extends InputTagRenderer implements IHTMLTagRenderer {
		public function __construct(array $attributes = [], ?IErrorHandler $errorHandler = null) {
			$this->validTagAttributes += [
				"src" => "", // required
				"height" => "", // number
				"width" => "", // number
				"alt" => "",
				"formaction" => "",
				"formenctype" => "", // application/x-www-form-urlencoded|multipart/form-data|text/plain
				"formmethod" => "", // get|post|dialog
				"formtarget" => "" // _Self|_blank|_parent|_top
			];

			parent::__construct("image", $attributes, $errorHandler);
		}
	}
?>