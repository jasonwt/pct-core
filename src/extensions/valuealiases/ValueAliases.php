<?php	
    declare(strict_types=1);	

	namespace pct\core\extensions\valuealiases;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\extensions\Extension;
	use pct\core\errorhandlers\IErrorHandler;
	use pct\core\extensions\valuealiases\IValueAliases;
	
	class ValueAliases extends Extension implements IValueAliases {			
		public function __construct(array $attributes = [], ?IErrorHandler $errorHandler = null) {
			for ($cnt = 0; $cnt < count($arrayKeys = array_keys($attributes)); $cnt ++)
				if (!is_array($attributes[$arrayKeys[$cnt]]))
					$attributes[$arrayKeys[$cnt]] = [$attributes[$arrayKeys[$cnt]]];

			parent::__construct("Aliases", $attributes, $errorHandler);
		}

		public function AddValueAlias(string $value, $alias = null): bool { 
			if (!is_null($this->GetAliasValue($alias)))
				return false;

			if (!$this->AttributeExists($value))
				$this->RegisterAttribute($value, []);

			if (is_null($alias))
				return true;

			$attributeValues = $this->GetAttributeValue($value);
			$attributeValues[] = $alias;

			return $this->SetAttributeValue($value, $attributeValues);
		}

		public function RemoveValueAlias(string $value, $alias = null) : bool {
			if (!$this->AttributeExists($value))
				return null;

			if (is_null($alias))
				return $this->UnregisterAttribute($value);

			if (!in_array($alias, ($attributeValue = $this->GetAttributeValue($value))))
				return false;

			unset($attributeValue[$alias]);

			$this->SetAttributeValue($value, $attributeValue);

			return true;
		}

		public function GetValueAlias(string $value) { 
			if (!$this->AttributeExists($value))
				return null;

			return (count ($aliases = $this->GetAttributeValue($value)) > 1 ? $aliases : $aliases[0]);
		}

		public function GetAliasValue($alias) : ?string { 
			foreach ($this->GetAttributes() as $attributeName => $attribute)
				if (in_array($alias, $attribute))
					return $attributeName;
					
			return null;
		}
	}

?>