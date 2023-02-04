<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer\tags\input;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\htmltagrenderer\HTMLTagRenderer;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;

	class InputTagRenderer extends HTMLTagRenderer implements IHTMLTagRenderer {
		protected array $validInputTypes = [
			"button", "checkbox", "color", "date", "datetime-local", "email", "file", 
			"hidden", "image", "month", "number", "password", "radio", "range", "reset", 
			"search", "submit", "tel", "text", "time", "url", "week"
		];

		public function __construct(string $inputType, array $attributes = [], ?IErrorHandler $errorHandler = null) {
			if (!in_array($inputType = trim($inputType), $this->validInputTypes))
				throw new \Exception("Invalid input tag type '$inputType'");
				
			$this->validTagAttributes = [
				"type" => $inputType,
				"value" => "",
				"name" => ""
			] + $this->validTagAttributes + [
				"form" => "", // specifies the form the <input> element belongs to.
				"autofocus" => false,
				"disabled" => false
//				"list" => "" // refers to a <datalist> element that contains pre-defined options for an <input> element.
			];

			parent::__construct("HTMLTagRenderer", $attributes, $errorHandler);
		}

		public function SetTagAttributeValue(string $tagAttributeName, $tagAttributeValue): bool {
			if (($tagAttributeName = trim($tagAttributeName)) == "type")
				throw new \Exception("Can not change Input Tag Type.");

			if ($tagAttributeName == "dirname")
				if ($tagAttributeValue != $this->GetParent()->GetName() . ".dir")
					throw new \Exception("dirname must equal '" . $this->GetParent()->GetName() . ".dir'");

			return parent::SetTagAttributeValue($tagAttributeName, $tagAttributeValue);
		}

		public function RenderOpeningTag() {
			echo "<input " . implode(" ", $this->GetTagAttributesArray()) . ">\n";
		}		
	}
?>