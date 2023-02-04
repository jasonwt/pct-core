<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer\tags\button;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\htmltagrenderer\HTMLTagRenderer;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;

	class ButtonTagRenderer extends HTMLTagRenderer implements IHTMLTagRenderer {
		protected array $validButtonTypes = ["button", "submit", "reset"];

		public function __construct(string $buttonType, array $attributes = [], ?IErrorHandler $errorHandler = null) {
			if (!in_array($buttonType = trim($buttonType), $this->validButtonTypes))
				throw new \Exception("Invalid button tag type '$buttonType'");

			$this->validTagAttributes = [
				"type" => $buttonType,
				"disabled" => false
			] + $this->validTagAttributes;

			parent::__construct("HTMLTagRenderer", $attributes, $errorHandler);
		}

		public function RenderOpeningTag() {
			echo "<button " . implode(" ", $this->GetTagAttributesArray()) . ">";
		}

		public function RenderClosingTag() {
			echo "</button>";
		}
	}
?>