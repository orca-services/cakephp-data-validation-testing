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
    // Assert that the "title" field's presence is required
    $this->testDataValidationRequired($this->Articles, 'title');
    // Assert that the field "title" cannot be empty
    $this->testDataValidationNotEmpty($this->Articles, 'title');
    // Assert that the "title" field's maximum length is 255 characters
    $this->testDataValidationMaxLength($this->Articles, 'title', 255);
}

public function testValidationIsPublished(): void
{
    // Assert that the field "is_published" must have a boolean value
    $this->testDataValidationBoolean($this->Articles, 'is_published');
}
```

Each helper builds an entity, runs the validator, and asserts the expected errors (or absence of errors) on the given field.

## Available methods

### Presence and emptiness

- `testDataValidationRequired($table, $fieldName)` - field must be present.
- `testDataValidationNotRequired($table, $fieldName)` - field is optional.
- `testDataValidationNotEmpty($table, $fieldName)` - field cannot be `null` or `''`.
- `testDataValidationEmpty($table, $fieldName)` - field may be `null` or `''`.

### Type validators

- `testDataValidationBoolean($table, $fieldName)` - field must be boolean.
- `testDataValidationURLWithProtocol($table, $fieldName)` - requires `http://` or `https://`.
- `testDataValidationDateTime($table, $fieldName)` - field must be datetime.
- `testDataValidationDate($table, $fieldName)` - field must be date.
- `testDataValidationNaturalNumber($table, $fieldName)` - positive integers only.
- `testDataValidationScalar($table, $fieldName)` - rejects non-scalar values like arrays.
- `testDataValidationDecimal($table, $fieldName)` - rejects non-decimal values like arrays.
- `testDataValidationInteger($table, $fieldName)` - rejects non-integer values like arrays.

### Length validators

- `testDataValidationMaxLength($table, $fieldName, $maxLength)`
- `testDataValidationMinLength($table, $fieldName, $minLength, $expected)`
- `testDataValidationLengthBetween($table, $fieldName, $minLength, $maxLength)`

### Generic helpers

- `testDataValidation($table, $fieldName, $dataSet, $expected)` - the underlying helper. Use when no specialized helper fits.
- `testDataValidationNoErrors($table, $fieldName, $dataSet)` - asserts a data set produces no errors on the field.
- `testDataValidationInList($table, $list, $fieldName, $expected)` - runs the same assertion for each value in a list.
- `testFullDataValidation($table, $dataSet, $expected)` - asserts errors across all fields.
- `testFullDataValidationNoErrors($table, $dataSet)` - asserts a full data set produces no errors at all.

### Rules helpers

For application rules that run at save time (not marshalling time). These require a real database connection and fixtures.

- `testDataRules($table, $fieldName, $dataSet, $expected)`
- `testDataRulesNoErrors($table, $fieldName, $dataSet)`
- `testRules($table, $fieldName, $dataSet, $expected)`
- `testDataValidationUnique($table, $fieldName, $fieldValue)`
- `testDataValidationForeignKey($table, $fieldName)`

## Dependent fields

Use the `$additionalDataSet` parameter to supply required companion fields so your test only fails for the reason you care about:
```php
$this->testDataValidationNotEmpty(
    $this->Articles,
    'title',
    ['author_id' => 1]
);
```

## Passing `newEntity()` options

The `$options` parameter is forwarded to `Table::newEntity()`:
```php
$this->testDataValidationRequired(
    $this->Articles,
    'title',
    [],
    ['validate' => 'create']
);
```
