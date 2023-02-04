<?php	
    declare(strict_types=1);

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	require_once(__DIR__ . "/../vendor/autoload.php");
	
	use pct\core\components\Component;
	use pct\core\errorhandlers\ErrorHandler;
	use pct\core\extensions\valuealiases\ValueAliases;
	use pct\core\extensions\htmltagrenderer\tags\FormTagRenderer;
	use pct\core\extensions\htmltagrenderer\tags\input\InputTextTagRenderer;

	use pct\core\extensions\databaselink\mysqllink\MysqlDatabaseLink;
	use pct\core\extensions\databasetables\DatabaseTables;
	use pct\core\extensions\htmltagrenderer\IHTMLTagRenderer;
use pct\core\extensions\htmltagrenderer\tags\SelectTagRenderer;

	use function pct\core\debugging\DebugPrint;

	require_once("dbconnect.php");	

	class TestComponent extends Component {
		public function __construct(string $name, array $attributes = [], $components = null, $extensions = null, ?ErrorHandler $errorHandler = null) {
			parent::__construct($name, null, $attributes, $components, $extensions, $errorHandler);

			$this->SetComponentValue("hunters_firstname", "Jason Thompson");
	
			$this->GetComponent("hunters_firstname")->RegisterExtension(new SelectTagRenderer(
				[
					"optgroup1" => [
						"group1 name1" => "group1 value1",
						"group1 name2" => "group1 value2",
						"group1 name3" => "group1 value3"
					],
					"optgroup2" => [
						"group2 name1" => "group2 value1",
						"group2 name2" => "group2 value2",
						"group2 name3" => "group2 value3"
					]
				],
				[
					"multiple" => true
				]
			));
//			foreach ($this->GetComponents() as $componentName => $component) {
//				$this->AddValueAlias($componentName, $componentName);
//				$this->AddValueAlias($componentName, $componentName . "_1");
//				$component->RegisterExtension(new InputTextTagRenderer());
//			}
				

			//$this->GetComponent("hunters_firstname")->RegisterExtension(new InputTextTagRenderer());
		}
	}

	$testComponent = new TestComponent("TestHTMLRenderer", [], null,
		[
			new MysqlDatabaseLink($dbLink), 
			new DatabaseTables("hunters"),
			new FormTagRenderer(),
			new ValueAliases()
		]
	);

	//$testComponent->AddValueAlias("hunters_firstname", "Hunters Firstname");
	//DebugPrint("GetValueAlias: ", $testComponent->GetValueAlias("hunters_firstname"));
	//DebugPrint("GetAliasValue: ", $testComponent->GetAliasValue("Hunters Firstname"));
	
	$testComponent->SetComponentValue("hunters_firstname", ["one" => 1, "two" => 2]);

//	DebugPrint($testComponent);

//	$testComponent->RenderHTML();

	echo $testComponent->hunters_firstname["one"] . "\n";
	$testComponent->hunters_firstname["one"] = "100";
	echo $testComponent->hunters_firstname["one"] . "\n";
	echo "lastname: " . $testComponent->hunters_lastname . "\n";
	$testComponent->hunters_lastname = "lastname";
	echo "lastname: " . $testComponent->hunters_lastname . "\n";
	
	//$tmp= "Aasdf";


	
?>