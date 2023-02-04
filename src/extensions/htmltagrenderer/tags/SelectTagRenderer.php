<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer\tags;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\htmltagrenderer\HTMLTagRenderer;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;

	class SelectTagRenderer extends HTMLTagRenderer implements IHTMLTagRenderer {
		protected array $selectOptionsArray = [];

		public function __construct(array $selectOptionsArray, array $attributes = [], ?IErrorHandler $errorHandler = null) {
			$this->validTagAttributes = ["name" => ""] + 
				$this->validTagAttributes + [
					"autofocus" => false, 
					"disabled" => false, 
					"form" => "",
					"multiple" => false,
					"required" => false,
					"size" => "" // number
				];

			$this->selectOptionsArray = $selectOptionsArray;

			parent::__construct("HTMLTagRenderer", $attributes, $errorHandler);
		}

		static public function GetSelectOptions(array $selectOptionsArray, $selectedValue) : string {
			$selectOptions = "";

			if (count($selectOptionsArray) == 0)
				return $selectOptions;

			foreach ($selectOptionsArray as $optionName => $optionValue) {				
				if (is_array($optionValue)) {
					$selectOptions .= "\t<optgroup label=\"$optionName\">\n";
					$selectOptions .= "\t" . str_replace("\n", "\n\t", static::GetSelectOptions($optionValue, $selectedValue));
					$selectOptions .= "</optgroup>\n";
				} else {
					$selected = "";

					if (is_array($selectedValue))
						$selected = (in_array($optionValue, $selectedValue) ? " SELECTED" : "");
					else if ((string) $selectedValue == $optionValue)
						$selected = " SELECTED";

					$selectOptions .= "\t<option value=\"$optionValue\"$selected>$optionName</option>\n";
				}
			}
			
			return $selectOptions;
		}

		public function RenderOpeningTag() {
			echo "<select " . implode(" ", $this->GetTagAttributesArray()) . ">\n";
			echo static::GetSelectOptions($this->selectOptionsArray, $this->GetParent()->GetValue());
		}

		public function RenderClosingTag() {
			echo "</select>\n";
		}
	}
?>