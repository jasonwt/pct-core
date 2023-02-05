<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer\tags;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\htmltagrenderer\HTMLTagRenderer;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;
	use pct\core\extensions\htmltagrenderer\tags\input\InputRadioTagRenderer;

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
					if ($extension instanceof InputRadioTagRenderer)
						while ($extension->GetRadioValuesIterator() < (count($extension->GetRadioValues()) - 1))
							$extension->RenderHTML();
					
					$extension->RenderHTML();
				}

			$this->RenderClosingTag();
		}

		public function SetComponentTagRenderer(IHTMLTagRenderer $renderer, string $componentName) : bool {
			$funcGetArgs = func_get_args();

			for ($cnt = 1; $cnt < count($funcGetArgs); $cnt ++) {
				$componentName = trim($funcGetArgs[$cnt]);
				$component = $this->GetParent()->GetComponent($componentName);

				foreach ($component->GetExtensions("pct\\core\\extensions\\htmltagrenderer\\HTMLTagRenderer") as $extension)
					$component->UnregisterExtension($extension->GetName());

				$component->RegisterExtension(clone $renderer);
			}
			
			return true;
		}


	}
?>