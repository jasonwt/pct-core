<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer\tags\input;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\htmltagrenderer\tags\input\InputTagRenderer;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;

	class InputFileTagRenderer extends InputTagRenderer implements IHTMLTagRenderer {
		public function __construct(array $attributes = [], ?IErrorHandler $errorHandler = null) {
			$this->validTagAttributes += [
				// http://www.iana.org/assignments/media-types/media-types.xhtml
				"accept" => "", // file_extension|audio/*|video/*|image/*|media_type 
				"required" => false,
				"multiple" => false
			];

			parent::__construct("file", $attributes, $errorHandler);

			unset($this->validInputTypes["value"]);
			$this->UnregisterAttribute("value");
		}
	}
?>