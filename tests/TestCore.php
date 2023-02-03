<?php	
    declare(strict_types=1);

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	require_once(__DIR__ . "/../vendor/autoload.php");
	
	use pct\core\components\IComponent;
	use pct\core\components\Component;
	use pct\core\errorhandlers\ErrorHandler;
	
	use pct\core\extensions\databaselink\mysqllink\MysqlDatabaseLink;
	
	use pct\core\extensions\databasetables\DatabaseTables;
	use pct\core\extensions\validator\Validate;
	use pct\core\extensions\validator\value\EmailAddressValidator;
	use pct\core\extensions\validator\value\pattern\RequiredValidator;

	use function pct\core\debugging\DebugPrint;

	require_once("dbconnect.php");	

	interface ITestComponent extends IComponent {
	}

	class TestComponent extends Component implements ITestComponent {
		public function __construct(string $name, array $attributes = [], $components = null, $extensions = null, ?ErrorHandler $errorHandler = null) {
			parent::__construct($name, null, $attributes, $components, $extensions, $errorHandler);

/*			
			$this->RegisterAttribute("attrNull", null);
			$this->RegisterAttribute("attrString", "string");
			$this->RegisterAttribute("attrInt", 100);
			

			$this->GetComponent("id")->SetValue("123");
			
			foreach ($this->GetComponents() as $componentName => $component) {
				$component->RegisterExtension(new ComponentValuePatternValidator());
				$component->AddValueValidationPatterns(
					ComponentValuePatternValidator::Required,
					ComponentValuePatternValidator::CreditCardNumber,
					ComponentValuePatternValidator::EmailAddress,
					ComponentValuePatternValidator::Integer,
					ComponentValuePatternValidator::NegativeInteger,
					ComponentValuePatternValidator::PhoneNumber,
					ComponentValuePatternValidator::PositiveInteger,
					ComponentValuePatternValidator::USCurrency,
					ComponentValuePatternValidator::ZipCode
				);
			}
*/			
		}

						
	}

	$testComponent = new TestComponent("testComponent", [], null, [
		new MysqlDatabaseLink($dbLink), 
		new DatabaseTables("hunters, permits"),
		new Validate()
	]);

	$testComponent->AddComponentValidator(new RequiredValidator());
	$testComponent->AddComponentValidator(new EmailAddressValidator());
	

	DebugPrint($testComponent, $testComponent->ValidateComponents());
	$testComponent->RemoveComponentValidator("RequiredValidator");

	//$testComponent->RegisterAttribute("attArray", ["element1" => "e1", "element2" => ["another array"]]);
	

	//DebugPrint(array_keys($testComponent->GetExtensions()));

	//echo DebugString($testComponent->ValidateComponents());

	

	//$testComponent->LoadFromDatabase("", "permits.hunter_id=hunters.id AND hunters.id='0004052'");
	
	$testComponent->SetComponentValues([
		"hunters_id" => 4213,
		"permits_id" => 14974
	]);

	$testComponent->WriteToDatabase();

	
	

	
	
	

//	$testComponent->RegisterExtension(new ComponentValuePatternValidator());

//	$testComponent->AddValueValidationPatterns(ComponentValuePatternValidator::EmailAddress);
	//$testComponent->testQuery("SELECT * FROM permits");

	//DebugPrint(array_keys($testComponent->GetExtensions("pct\\core\\extensions\\componentvalidator\\ComponentValidator")));

	
	
	
	
?>