<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer\tags;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\htmltagrenderer\HTMLTagRenderer;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;

	class TextareaTagRenderer extends HTMLTagRenderer implements IHTMLTagRenderer {
		public function __construct(array $attributes = [], ?IErrorHandler $errorHandler = null) {
			$this->validTagAttributes = ["name" => ""] + 
				$this->validTagAttributes + [
					"placeholder" => "",
					"autofocus" => false, 
					"cols" => "", // number
					"rows" => "", // number
					"dirname" => "",
					"disabled" => false, 
					"form" => "",
					"maxlength" => "", // number
					"readonly" => false,
					"required" => false,
					"wrap" => "" // soft|hard
				];

			parent::__construct("HTMLTagRenderer", $attributes, $errorHandler);
		}

		public function RenderOpeningTag() {
			echo "<textarea " . implode(" ", $this->GetTagAttributesArray()) . ">";
		}

		public function RenderClosingTag() {
			echo "</select>";
		}
	}
?>