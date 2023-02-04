<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer\tags;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\htmltagrenderer\HTMLTagRenderer;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;

	class FieldsetTagRenderer extends HTMLTagRenderer implements IHTMLTagRenderer {
		public function __construct(array $attributes = [], ?IErrorHandler $errorHandler = null) {
			$this->validTagAttributes = [
				"name" => "", 
				"form" => "post", 
				"disabled" => false
			] + $this->validTagAttributes;

			parent::__construct("HTMLTagRenderer", $attributes, $errorHandler);
		}

		public function RenderOpeningTag() {
			echo "<fieldset " . implode(" ", $this->GetTagAttributesArray()) . ">\n";
		}

		public function RenderClosingTag() {
			echo "</fieldset>";
		}
	}
?>