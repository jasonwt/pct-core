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
	use pct\core\extensions\htmltagrenderer\tags\input\InputCheckboxTagRenderer;
	use pct\core\extensions\htmltagrenderer\tags\input\InputEmailTagRenderer;
	use pct\core\extensions\htmltagrenderer\tags\input\InputHiddenTagRenderer;
	use pct\core\extensions\htmltagrenderer\tags\input\InputNumberTagRenderer;
	use pct\core\extensions\htmltagrenderer\tags\input\InputRadioTagRenderer;
	use pct\core\extensions\htmltagrenderer\tags\input\InputTelTagRenderer;
	use pct\core\extensions\htmltagrenderer\tags\SelectTagRenderer;
	use pct\core\extensions\validator\value\pattern\EmailAddressValidator;

	use function pct\core\includes\arrays\GetDaysArray;
	use function pct\core\includes\arrays\GetMonthsArray;
	use function pct\core\includes\arrays\GetUSStatesArray;
	use function pct\core\includes\arrays\GetYearsArray;


	use function pct\core\debugging\DebugPrint;

	require_once("dbconnect.php");	

	class TestComponent extends Component {
		public function __construct(string $name, array $attributes = [], $components = null, $extensions = null, ?ErrorHandler $errorHandler = null) {
			parent::__construct($name, null, $attributes, $components, $extensions, $errorHandler);

			$this->RegisterComponent(
				"currentPage",
				// Page 1
				"dobMonth", "dobDay", "dobYear",
				// Page 2
				"heightFeet", "heightInches",
				// Page3
				"startmonth", "startday", "startyear", "cctype", "ccnumber", "ccmonth", "ccyear", "ccccv", "b_address", "b_city", "b_state", "b_zipcode"
			);

			$this->SetComponentTagRenderer(new InputHiddenTagRenderer(), "currentPage");

			$this->SetComponentTagRenderer(new InputTextTagRenderer(), 
				"hunters_firstname", "hunters_lastname", "hunters_address", "hunters_city", "b_address", "b_city"
			);
			$this->SetComponentTagRenderer(new SelectTagRenderer(
				["" => "Gender", "F" => "Female", "M" => "Male"]), 
				"hunters_gender"
			);
			$this->SetComponentTagRenderer(new SelectTagRenderer(
				["" => "State"] + array_combine(GetUSStatesArray(true), GetUSStatesArray(false))), 
				"hunters_state", "b_state"
			);
			$this->SetComponentTagRenderer(new SelectTagRenderer(
				["" => "Month"] + GetMonthsArray(false)), 
				"dobMonth", "startmonth", "ccmonth"
			);
			$this->SetComponentTagRenderer(new SelectTagRenderer(
				["" => "Day"] + GetDaysArray(30)), 
				"dobDay", "startday"
			);
			$this->SetComponentTagRenderer(new SelectTagRenderer(
				["1" => "Season Permit", "2" => "3 Consecutive Day Permit"]), 
				"permits_type"
			);
			$this->SetComponentTagRenderer(new SelectTagRenderer(
				GetYearsArray(date("Y")-(date("m")<4?1:0), date("Y")+(date("m")<4?1:2))), 
				"permits_permit_year"
			);
			$this->SetComponentTagRenderer(new SelectTagRenderer(
				["" => "Year"] + GetYearsArray(date("Y"), date("Y")-100)), 
				"dobYear", "startyear"
			);
			$this->SetComponentTagRenderer(new SelectTagRenderer(
				["" => "Select One", "001" => "Visa", "002" => "Mastercard"]), 
				"cctype"
			);
			$this->SetComponentTagRenderer(new SelectTagRenderer(
				["" => "Exp Year"] + GetYearsArray(date("Y"), date("Y")+8)), 
				"ccyear"
			);
			$this->SetComponentTagRenderer(new InputNumberTagRenderer(), 
				"ccnumber", "ccccv", "b_zipcode", "hunters_zipcode", "hunters_weight"
			);
			$this->SetComponentTagRenderer(new InputEmailTagRenderer(), 
				"hunters_email"
			);
			$this->SetComponentTagRenderer(new InputTelTagRenderer(), 
				"hunters_phone"
			);

			$this->SetComponentValues([
				"permit_purchase_date" => time(),
				"permits_issue" => "new",
				"permits_new" => 1,
				"startmonth" => "4",
				"startday" => "1",
				"startyear" => (string) ((int) date("Y") - (date("m") < 4 ? 1 : 0))
			]);
			
		}
	}

	$testComponent = new TestComponent("TestHTMLRenderer", [], null,
		[
			new MysqlDatabaseLink($dbLink), 
			new DatabaseTables("hunters, permits"),
			new FormTagRenderer(),
			new ValueAliases()
		]
	);

	//$testComponent->AddValueAlias("hunters_firstname", "Hunters Firstname");
	//DebugPrint("GetValueAlias: ", $testComponent->GetValueAlias("hunters_firstname"));
	//DebugPrint("GetAliasValue: ", $testComponent->GetAliasValue("Hunters Firstname"));

	//$testComponent->hunters_firstname = ["one" => 1, "two" => 2];	

	DebugPrint($testComponent);

	$testComponent->RenderHTML();

/*	
	echo $testComponent->hunters_firstname["one"] . "\n";
	$testComponent->hunters_firstname["one"] = "100";
	echo $testComponent->hunters_firstname["one"] . "\n";
	echo "lastname: " . $testComponent->hunters_lastname . "\n";
	$testComponent->hunters_lastname = "lastname";
	echo "lastname: " . $testComponent->hunters_lastname . "\n";
*/	
	//$tmp= "Aasdf";


	
?>