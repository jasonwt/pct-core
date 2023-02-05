<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\htmltagrenderer;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\extensions\IExtension;
	use pct\core\components\IComponent;

	interface IHTMLTagRenderer extends IExtension {
		public function RenderOpeningTag();
		public function RenderClosingTag();
		public function RenderHTML();

		public function SetTagAttributes(array $tagAttributes) : ?IComponent;
		public function SetTagAttributeValue(string $tagAttributeName, $tagAttributeValue): ?IComponent;
	}
?>