<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

use pct\core\components\Component;
use pct\core\components\IComponent;
use pct\core\extensions\Extension;
	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;

	abstract class HTMLTagRenderer extends Extension implements IHTMLTagRenderer {		
		// Global HTML Attributes
		// https://www.w3schools.com/tags/ref_standardattributes.asp
		protected array $validTagAttributes = [
			"id" => "",
			"class" => "",
			"style" => "",
			"accesskey" => "",       // character
			"contenteditable" => "", // true|false
			//"data-*" => "",
			"dir" => "",             // ltr|rtl|auto
			"draggable" => "",       // true|false
			"hidden" => false,
			"lang" => "",            // language code https://www.w3schools.com/tags/ref_language_codes.asp
			"spellcheck" => "",      // true|false
			"tabindex" => "",        // number starting with 1
			"title" => "",
			"translate" => ""        // yes|no
		];

		public function __construct(string $name, array $attributes, ?IErrorHandler $errorHandler = null) {			
			parent::__construct($name, $this->validTagAttributes, $errorHandler);

			foreach ($attributes as $attributeName => $attributeValue) {
				if (!isset($this->validTagAttributes[$attributeName]))
					throw new \Exception("Invalid tag attribute '$attributeName' for '" . get_class($this) . "'");

				$this->SetTagAttributeValue($attributeName, $attributeValue);				
			}
		}

		protected function GetTagAttributesArray() : array {
			$tagAttributesArray = [];

			$attributes = $this->GetAttributes();

			foreach ($attributes as $attributeName => $attributeValue) {
				if ($attributeName == "id") {
					$attributeValue = $this->GetParent()->GetName();
				} else if ($attributeName == "name") {
					$attributeValue = $this->GetParent()->GetName();

					if (isset($attributes["multiple"]) ? $attributes["multiple"] : false)
						$attributeValue .= "[]";
				} else if ($attributeName == "value") {
					$attributeValue = $this->GetParent()->GetValue();
				} else if ($attributeName == "dirname") {
					$attributeValue = $this->GetParent()->GetName() . ".dir";
				}

				if (is_null($attributeValue)) {
					continue;
				} else if (is_bool($attributeValue)) {
					if ($attributeValue == true)
						$tagAttributesArray[$attributeName] = $attributeName;
				} else {
					if (($attributeValue = trim($attributeValue)) != "")
						$tagAttributesArray[$attributeName] = $attributeName . "=\"$attributeValue\"";						
				}
			}

			return $tagAttributesArray;
		}
		
		public function SetTagAttributes(array $tagAttributes) : ?IComponent {
			foreach ($tagAttributes as $attributeName => $attributeValue)
				if (is_null($this->GetParent()->SetTagAttributeValue($attributeName, $attributeValue)))
					return null;
			

			return $this->GetParent();
		}

		public function SetTagAttributeValue(string $tagAttributeName, $tagAttributeValue): ?IComponent {
			$attributeValueType = gettype($tagAttributeValue);
			$validTagAttributeType = gettype($this->validTagAttributes[$tagAttributeName]);
			
			if ($attributeValueType == "object" || $attributeValueType == "resource" || $attributeValueType == "unknown type" || $attributeValueType == "array")
				throw new \Exception("Invalid tag attribute '$tagAttributeName' type '$attributeValueType' for '" . get_class($this) . "'");

			if (!is_bool($tagAttributeValue))
				$tagAttributeValue = (string) $tagAttributeValue;

			if ($attributeValueType != $validTagAttributeType)
				throw new \Exception("Tag attribute '$tagAttributeName' type '$attributeValueType' mismatch for '" . get_class($this) . "'.  Expected type '$validTagAttributeType'");

			$this[$tagAttributeName] = $tagAttributeValue;

			return $this->GetParent();			
		}

		public function RenderClosingTag() {			
		}

		public function RenderHTML() {
			$this->GetParent()->RenderOpeningTag();
			$this->GetParent()->RenderClosingTag();
		}

	}

?>