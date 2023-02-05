<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer\tags\input;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\components\IComponent;
	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\htmltagrenderer\tags\input\InputTagRenderer;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;

	class InputRadioTagRenderer extends InputTagRenderer implements IHTMLTagRenderer {
		protected $radioValuesIterator = 0;
		protected array $radioValues = [];

		public function __construct(array $radioValues, array $attributes = [], ?IErrorHandler $errorHandler = null) {
			$this->validTagAttributes += [
				"checked" => false,
				"required" => false
			];

			$this->radioValues = $radioValues;

			parent::__construct("radio", $attributes, $errorHandler);
		}

		public function GetRadioValues() : array {
			return $this->radioValues;
		}

		public function GetRadioValuesIterator() : int {
			return $this->radioValuesIterator;
		}

		public function SetRatioValueKey(string $value) : IComponent {
			if (($this->radioValuesIterator = array_search($value, $this->radioValues)) === false)
				throw new \Exception("Value '$value' not found.");

			return $this->GetParent();
		}

		protected function GetTagAttributesArray() : array {
			if ($this->GetParent()->GetValue() == $this->radioValues[$this->radioValuesIterator])
				$this["checked"] = true;
				
			$tagAttributesArray = parent::GetTagAttributesArray();

			$tagAttributesArray["value"] = "value=\"" . $this->radioValues[$this->radioValuesIterator] . "\"";

			$tagAttributesArray["id"] = "id=\"" . $this->GetParent()->GetName() . (string) $this->radioValuesIterator . "\"";

			return $tagAttributesArray;
		}

		public function RenderOpeningTag() {
			parent::RenderOpeningTag();

			$this->radioValuesIterator++;

			if ($this->radioValuesIterator >= count($this->radioValues))
				$this->radioValuesIterator = 0;
			
		}
	}
?>