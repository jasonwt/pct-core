<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer\tags;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\htmltagrenderer\HTMLTagRenderer;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;

	class FormTagRenderer extends HTMLTagRenderer implements IHTMLTagRenderer {
		public function __construct(array $attributes = [], ?IErrorHandler $errorHandler = null) {
			$this->validTagAttributes = [
				"action" => "", 
				"method" => "post", 
				"enctype" => ""
			] + $this->validTagAttributes;

			parent::__construct("FormTagRenderer", $attributes, $errorHandler);
		}

		public function RenderOpeningTag() {
			echo "<form " . implode(" ", $this->GetTagAttributesArray()) . ">\n";
		}

		public function RenderClosingTag() {
			echo "</form>";
		}

		public function RenderHTML() {
			$this->RenderOpeningTag();

			foreach ($this->GetParent()->GetComponents() as $componentName => $component)
				foreach ($component->GetExtensions("pct\\core\\extensions\\htmltagrenderer\\HTMLTagRenderer") as $extensionName => $extension) {
					$extension->RenderHTML();
				}

			$this->RenderClosingTag();
		}


	}
?>