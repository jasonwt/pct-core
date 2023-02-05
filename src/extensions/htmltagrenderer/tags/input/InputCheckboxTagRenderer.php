<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer\tags\input;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\htmltagrenderer\tags\input\InputTagRenderer;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;

	class InputCheckboxTagRenderer extends InputTagRenderer implements IHTMLTagRenderer {
		protected $checkboxValue = "";

		public function __construct(string $checkboxValue, array $attributes = [], ?IErrorHandler $errorHandler = null) {
			if (($this->checkboxValue = trim($checkboxValue)) == "")
				throw new \Exception("Invalid checkbox value");

			$this->validTagAttributes += [
				"checked" => false,
				"required" => false
			];

			parent::__construct("checkbox", $attributes, $errorHandler);
		}

		protected function GetTagAttributesArray() : array {
			if ($this->GetParent()->GetValue() == $this->checkboxValue)
				$this["checked"] = true;
				
			$tagAttributesArray = parent::GetTagAttributesArray();

			$tagAttributesArray["value"] = "value=\"" . $this->checkboxValue . "\"";

			return $tagAttributesArray;
		}
	}
?>