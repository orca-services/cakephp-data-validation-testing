# Usage

The `DataValidationTestTrait` provides helper methods to simplify testing the data validation configuration of your CakePHP tables.

## Setup

Add the trait to any PHPUnit test case:
```php
<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use Cake\TestSuite\TestCase;
use DataValidationTesting\Traits\DataValidationTestTrait;

class ArticlesTableTest extends TestCase
{
    use DataValidationTestTrait;

    private \App\Model\Table\ArticlesTable $Articles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->Articles = $this->getTableLocator()->get('Articles');
    }
}
```

All helper methods are `protected` and callable from within your test class.

## Example
```php
public function testValidationTitle(): void
{
    $this->_testDataValidationRequired($this->Articles, 'title');
    $this->_testDataValidationNotEmpty($this->Articles, 'title');
    $this->_testDataValidationMaxLength($this->Articles, 'title', 255);
}

public function testValidationIsPublished(): void
{
    $this->_testDataValidationBoolean($this->Articles, 'is_published');
}
```

Each helper builds an entity, runs the validator, and asserts the expected errors (or absence of errors) on the given field.

## Available methods

### Presence and emptiness

- `_testDataValidationRequired($table, $fieldName)` - field must be present.
- `_testDataValidationNotRequired($table, $fieldName)` - field is optional.
- `_testDataValidationNotEmpty($table, $fieldName)` - field cannot be `null` or `''`.
- `_testDataValidationEmpty($table, $fieldName)` - field may be `null` or `''`.

### Type validators

- `_testDataValidationBoolean($table, $fieldName)` - field must be boolean.
- `_testDataValidationURLWithProtocol($table, $fieldName)` - requires `http://` or `https://`.
- `_testDataValidationDateTime($table, $fieldName)` - field must be datetime.
- `_testDataValidationNaturalNumber($table, $fieldName)` - positive integers only.
- `_testDataValidationScalar($table, $fieldName)` - rejects non-scalar values like arrays.

### Length validators

- `_testDataValidationMaxLength($table, $fieldName, $maxLength)`
- `_testDataValidationMinLength($table, $fieldName, $minLength, $expected)`
- `_testDataValidationLengthBetween($table, $fieldName, $minLength, $maxLength)`

### Generic helpers

- `_testDataValidation($table, $fieldName, $dataSet, $expected)` - the underlying helper. Use when no specialized helper fits.
- `_testDataValidationNoErrors($table, $fieldName, $dataSet)` - asserts a data set produces no errors on the field.
- `_testDataValidationInList($table, $list, $fieldName, $expected)` - runs the same assertion for each value in a list.
- `_testFullDataValidation($table, $dataSet, $expected)` - asserts errors across all fields.
- `_testFullDataValidationNoErrors($table, $dataSet)` - asserts a full data set produces no errors at all.

### Rules helpers

For application rules that run at save time (not marshalling time). These require a real database connection and fixtures.

- `_testDataRules($table, $fieldName, $dataSet, $expected)`
- `_testDataRulesNoErrors($table, $fieldName, $dataSet)`
- `_testRules($table, $fieldName, $dataSet, $expected)`

## Dependent fields

Use the `$additionalDataSet` parameter to supply required companion fields so your test only fails for the reason you care about:
```php
$this->_testDataValidationNotEmpty(
    $this->Articles,
    'title',
    ['author_id' => 1]
);
```

## Passing `newEntity()` options

The `$options` parameter is forwarded to `Table::newEntity()`:
```php
$this->_testDataValidationRequired(
    $this->Articles,
    'title',
    [],
    ['validate' => 'create']
);
```
