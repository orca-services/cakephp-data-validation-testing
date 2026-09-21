<?php
declare(strict_types=1);

namespace DataValidationTesting\Test\TestCase\Traits;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use DataValidationTesting\Test\TestApp\Model\Table\ValidationTestTable;
use DataValidationTesting\Traits\DataValidationTestTrait;
use PHPUnit\Framework\TestCase;

/**
 * The DataValidationTestTrait Test
 *
 * @coversDefaultClass \DataValidationTesting\Traits\DataValidationTestTrait
 */
class DataValidationTestTraitTest extends TestCase
{
    use DataValidationTestTrait;

    /**
     * @var Table A CakePHP table to test the DataValidationTestTrait methods on
     */
    private Table $table;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        TableRegistry::getTableLocator()->clear();
        $this->table = TableRegistry::getTableLocator()->get('ValidationTest', [
            'className' => ValidationTestTable::class,
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        $this->table->deleteAll([]);
        parent::tearDown();
    }

    /**
     * Test the assertDataValidation base method
     *
     * @return void
     * @covers ::assertDataValidation
     */
    public function testAssertDataValidation(): void
    {
        // Ensure data validation of some field works as expected first
        $fieldName = 'not_empty_field';
        $dataSet = [$fieldName => ''];
        $entity = $this->table->newEntity($dataSet);
        $expectedErrors = ['_empty' => 'This field cannot be left empty'];
        $errors = $entity->getError($fieldName);
        static::assertSame($expectedErrors, $errors);

        $this->assertDataValidation($this->table, $fieldName, $dataSet, $expectedErrors);
    }

    /**
     * Test the assertDataValidationNoErrors base method
     *
     * @return void
     * @covers ::assertDataValidationNoErrors
     */
    public function testAssertDataValidationNoErrors(): void
    {
        // Ensure data validation of some field works as expected first
        $fieldName = 'empty_field';
        $dataSet = [$fieldName => ''];
        $entity = $this->table->newEntity($dataSet);
        $expectedErrors = [];
        $errors = $entity->getError($fieldName);
        static::assertSame($expectedErrors, $errors);

        $this->assertDataValidationNoErrors($this->table, $fieldName, $dataSet);
    }

    /**
     * Test that assertDataValidationNotEmpty passes when the field is not empty.
     *
     * @return void
     * @covers ::assertDataValidationNotEmpty
     */
    public function testAssertDataValidationNotEmpty(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'not_empty_field';
        $expectedErrors = ['_empty' => 'This field cannot be left empty'];
        $dataSet = [$field => ''];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertDataValidationNotEmpty($this->table, $field);
    }

    /**
     * Test that assertDataValidationEmpty passes when the field is empty.
     *
     * @return void
     * @covers ::assertDataValidationEmpty
     */
    public function testAssertDataValidationEmpty(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'empty_field';
        $dataSet = [$field => ''];
        $this->assertDataValidationNoErrors($this->table, $field, $dataSet);

        $this->assertDataValidationEmpty($this->table, $field);
    }

    /**
     * Test that assertDataValidationRequired passes when the field is required.
     *
     * @return void
     * @covers ::assertDataValidationRequired
     */
    public function testAssertDataValidationRequired(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'required_field';
        $expectedErrors = ['_required' => 'This field is required'];
        $dataSet = [];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertDataValidationRequired($this->table, $field);
    }

    /**
     * Test that assertDataValidationNotRequired passes when the field is empty.
     *
     * @return void
     * @covers ::assertDataValidationNotRequired
     */
    public function testAssertDataValidationNotRequired(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'empty_field';
        $dataSet = [];
        $this->assertDataValidationNoErrors($this->table, $field, $dataSet);

        $this->assertDataValidationNotRequired($this->table, $field);
    }

    /**
     * Test that assertDataValidationBoolean passes when the field is boolean.
     *
     * @return void
     * @covers ::assertDataValidationBoolean
     */
    public function testAssertDataValidationBoolean(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'boolean_field';
        $expectedErrors = ['boolean' => 'The provided value must be a boolean'];
        $dataSet = [$field => 'Not a boolean'];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertDataValidationBoolean($this->table, $field);
    }

    /**
     * Test that assertDataValidationURLWithProtocol passes when the field is url.
     *
     * @return void
     * @covers ::assertDataValidationURLWithProtocol
     */
    public function testAssertDataValidationURLWithProtocol(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'url_field';
        $expectedErrors = ['urlWithProtocol' => 'The provided value must be a URL with protocol'];
        $dataSet = [$field => 'no-protocol.com'];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertDataValidationURLWithProtocol($this->table, $field);
    }

    /**
     * Test that assertDataValidationDateTime passes when the field is datetime.
     *
     * @return void
     * @covers ::assertDataValidationDateTime
     */
    public function testAssertDataValidationDateTime(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'datetime_field';
        $expectedErrors = ['dateTime' => 'The provided value must be a date and time of one of these formats: `ymd`'];
        $dataSet = [$field => 'Not a date/time'];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertDataValidationDateTime($this->table, $field);
    }

    /**
     * Test that assertDataValidationDate passes when the field is date.
     *
     * @return void
     * @covers ::assertDataValidationDate
     */
    public function testAssertDataValidationDate(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'date_field';
        $expectedErrors = ['date' => 'The provided value must be a date of one of these formats: `ymd`'];
        $dataSet = [$field => 'Not a date'];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertDataValidationDate($this->table, $field);
    }

    /**
     * Test that assertDataValidationInList passes when the field is datetime.
     *
     * @return void
     * @covers ::assertDataValidationInList
     */
    public function testAssertDataValidationInList(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'datetime_field';
        $expectedErrors = ['dateTime' => 'The provided value must be a date and time of one of these formats: `ymd`'];
        $invalidValues = ['Not a date/time', '123'];
        $this->assertDataValidationInList($this->table, $invalidValues, $field, $expectedErrors);

        $validValues = ['1900-01-01 00:00:00', '2022-10-12 11:50:32'];
        $this->assertDataValidationInList($this->table, $validValues, $field);
    }

    /**
     * Test that assertDataValidationMaxLength passes when the value is less than the max length.
     *
     * @return void
     * @covers ::assertDataValidationMaxLength
     */
    public function testAssertDataValidationMaxLength(): void
    {
        // Ensure data validation of the field works as expected first
        $maxLength = 10;
        $field = 'max_length_field';
        $expectedErrors = ['maxLength' => 'The provided value must be at most `10` characters long'];
        $dataSet = [$field => str_repeat('A', $maxLength + 1)];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertDataValidationMaxLength($this->table, $field, $maxLength);
    }

    /**
     * Test that assertDataValidationMinLength passes when the value is greater than the min length.
     *
     * @return void
     * @covers ::assertDataValidationMinLength
     */
    public function testAssertDataValidationMinLength(): void
    {
        // Ensure data validation of the field works as expected first
        $minLength = 5;
        $field = 'min_length_field';
        $expectedErrors = ['minLength' => 'The provided value must be at least `5` characters long'];
        $dataSet = [$field => str_repeat('A', $minLength - 1)];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertDataValidationMinLength($this->table, $field, $minLength);
    }

    /**
     * Test that assertDataValidationScalar passes when the field is scalar.
     *
     * @return void
     * @covers ::assertDataValidationScalar
     */
    public function testAssertDataValidationScalar(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'scalar_field';
        $entity = $this->table->newEntity([$field => []]);
        static::assertArrayHasKey('scalar', $entity->getError($field));

        $this->assertDataValidationScalar($this->table, $field);
    }

    /**
     * Test that assertDataValidationDecimal passes when the field is decimal.
     *
     * @return void
     * @covers ::assertDataValidationDecimal
     */
    public function testAssertDataValidationDecimal(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'decimal_field';
        $expectedErrors = ['decimal' => 'The provided value must be decimal with any number of decimal places, including none'];
        $dataSet = [$field => 'not a decimal'];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertDataValidationDecimal($this->table, $field);
    }

    /**
     * Test that assertDataValidationInteger passes when the field is integer.
     *
     * @return void
     * @covers ::assertDataValidationInteger
     */
    public function testAssertDataValidationInteger(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'integer_field';
        $expectedErrors = ['integer' => 'The provided value must be an integer'];
        $dataSet = [$field => 'not a integer'];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertDataValidationInteger($this->table, $field);
    }

    /**
     * Test that assertDataValidationNonNegativeInteger passes when the field is a non-negative integer.
     *
     * @return void
     * @covers ::assertDataValidationNonNegativeInteger
     */
    public function testAssertDataValidationNonNegativeInteger(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'non_negative_integer_field';
        $expectedErrors = ['nonNegativeInteger' => 'The provided value must be a non-negative integer'];
        $dataSet = [$field => 'not a integer'];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertDataValidationNonNegativeInteger($this->table, $field);
    }

    /**
     * Test that assertDataValidationGreaterThanOrEqual passes when the field value
     * is greater than or equal to the configured threshold.
     *
     * @return void
     * @covers ::assertDataValidationGreaterThanOrEqual
     */
    public function testAssertDataValidationGreaterThanOrEqual(): void
    {
        // Ensure data validation of the field works as expected first
        $threshold = 10;
        $field = 'greater_than_or_equal_field';
        $expectedErrors = [
            'greaterThanOrEqual' => sprintf(
                'The provided value must be greater than or equal to `%s`',
                $threshold,
            ),
        ];

        // Just below the threshold should fail
        $dataSet = [$field => $threshold - 1];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);

        // At the threshold should pass
        $dataSet = [$field => $threshold];
        $this->assertDataValidationNoErrors($this->table, $field, $dataSet);

        // Above the threshold should pass
        $dataSet = [$field => $threshold + 1];
        $this->assertDataValidationNoErrors($this->table, $field, $dataSet);

        $this->assertDataValidationGreaterThanOrEqual($this->table, $field, $threshold);
    }

    /**
     * Test that assertDataValidationEmail passes when the field is a valid email.
     *
     * @return void
     * @covers ::assertDataValidationEmail
     */
    public function testAssertDataValidationEmail(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'email_field';
        $expectedErrors = [
            'email' => 'The provided value must be an e-mail address',
        ];
        $dataSet = [$field => 'Not an email'];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertDataValidationEmail($this->table, $field);
    }

    /**
     * Test that assertDataValidationUuid passes when the field is a valid uuid.
     *
     * @return void
     * @covers ::assertDataValidationUuid
     */
    public function testAssertDataValidationUuid(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'uuid_field';
        $expectedErrors = [
            'uuid' => 'The provided value must be a UUID',
        ];
        $dataSet = [$field => 'Not a uuid'];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertDataValidationUuid($this->table, $field);
    }

    /**
     * Test that assertDataValidationLengthBetween passes when the field is between the min and max length.
     *
     * @return void
     * @covers ::assertDataValidationLengthBetween
     */
    public function testAssertDataValidationLengthBetween(): void
    {
        // Ensure data validation of the field works as expected first
        $minLength = 5;
        $maxLength = 10;
        $field = 'length_between_field';
        $expectedErrors = ['lengthBetween' => 'The length of the provided value must be between `5` and `10`, inclusively'];
        $dataSet = [$field => str_repeat('A', $minLength - 1)];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);
        $dataSet = [$field => str_repeat('A', $maxLength + 1)];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertDataValidationLengthBetween($this->table, $field, $minLength, $maxLength);
    }

    /**
     * Test that assertDataValidationRange passes when the field is between the lower and upper bound.
     *
     * @return void
     * @covers ::assertDataValidationRange
     */
    public function testAssertDataValidationRange(): void
    {
        // Ensure data validation of the field works as expected first
        $lowerBound = -30.0;
        $upperBound = 30.0;
        $field = 'range_field';
        $expectedErrors = ['range' => 'The provided value must be between `-30` and `30`, inclusively'];

        $dataSet = [$field => $lowerBound - 1];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $dataSet = [$field => $upperBound + 1];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertDataValidationRange($this->table, $field, $lowerBound, $upperBound);
    }

    /**
     * Test that assertDataValidationNaturalNumber passes when the field is a natural number.
     *
     * @return void
     * @covers ::assertDataValidationNaturalNumber
     */
    public function testAssertDataValidationNaturalNumber(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'natural_number_field';
        $expectedErrors = ['naturalNumber' => 'The provided value must be a natural number'];
        $dataSet = [$field => -1];
        $this->assertDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertDataValidationNaturalNumber($this->table, $field);
    }

    /**
     * Test that testFullDataValidationNoErrors passes when the full data set has no errors.
     *
     * @return void
     * @covers ::testFullDataValidationNoErrors
     */
    public function testTestFullDataValidationNoErrors(): void
    {
        $dataSet = ['required_field' => 'required', 'multi_rule_field' => 1];
        $this->assertFullDataValidationNoErrors($this->table, $dataSet);
    }

    /**
     * Test that testFullDataValidation reports all errors.
     *
     * @return void
     * @covers ::testFullDataValidation
     */
    public function testTestFullDataValidation(): void
    {
        $dataSet = ['not_empty_field' => '', 'multi_rule_field' => 1];
        $expectedErrors = [
            'not_empty_field' => ['_empty' => 'This field cannot be left empty'],
            'required_field' => ['_required' => 'This field is required'],
        ];
        $this->assertFullDataValidation($this->table, $dataSet, $expectedErrors);
    }

    /**
     * Test that testDataRules passes when saving leads to the expected rule errors.
     *
     * @return void
     * @covers ::testDataRules
     */
    public function testTestDataRules(): void
    {
        $field = 'unique_field';
        $dataSet = [$field => 'duplicate'];
        $expectedErrors = ['_isUnique' => 'This value is already in use'];

        // Ensure a first record exists so the unique rule will fail on the second
        $existing = $this->table->newEntity($dataSet, ['validate' => false]);
        static::assertNotFalse($this->table->save($existing));

        $duplicate = $this->table->newEntity($dataSet, ['validate' => false]);
        static::assertFalse($this->table->save($duplicate));
        static::assertSame($expectedErrors, $duplicate->getError($field));

        $this->assertDataRules($this->table, $field, $dataSet, $expectedErrors);
    }

    /**
     * Test that testDataRulesNoErrors passes when saving leads to no rule errors.
     *
     * @return void
     * @covers ::testDataRulesNoErrors
     */
    public function testTestDataRulesNoErrors(): void
    {
        // Ensure the rule works as expected first
        $field = 'unique_field';
        $dataSet = [$field => 'unique-value-' . uniqid('', true)];

        $entity = $this->table->newEntity($dataSet, ['validate' => false]);
        static::assertNotFalse($this->table->save($entity));
        static::assertEmpty($entity->getError($field));

        $this->assertDataRulesNoErrors($this->table, $field, [$field => 'another-unique-' . uniqid('', true)]);
    }

    /**
     * Test that assertDataValidationForeignKey passes when the foreign key does not exist.
     *
     * @return void
     * @covers ::assertDataValidationForeignKey
     */
    public function testAssertDataValidationForeignKey(): void
    {
        // Ensure the rule works as expected first
        $field = 'parent_id';
        $notExistingForeignKey = 999999;
        $expectedErrors = ['_existsIn' => 'This value does not exist'];

        $entity = $this->table->newEntity([$field => $notExistingForeignKey], ['validate' => false]);
        static::assertFalse($this->table->checkRules($entity));
        static::assertSame($expectedErrors, $entity->getError($field));

        $this->assertDataValidationForeignKey($this->table, $field, $notExistingForeignKey);
    }

    /**
     * Test that assertDataValidationForeignKey passes with the default not existing foreign key.
     *
     * @return void
     * @covers ::assertDataValidationForeignKey
     */
    public function testAssertDataValidationForeignKeyDefault(): void
    {
        $field = 'parent_id';
        $this->assertDataValidationForeignKey($this->table, $field);
    }

    /**
     * Test that assertDataValidationForeignKey accepts a custom expected error.
     *
     * @return void
     * @covers ::assertDataValidationForeignKey
     */
    public function testAssertDataValidationForeignKeyCustomExpected(): void
    {
        $field = 'parent_id';
        $expectedErrors = ['_existsIn' => 'This value does not exist'];

        $this->assertDataValidationForeignKey($this->table, $field, 999999, $expectedErrors);
    }

    /**
     * Test that assertDataValidationIsUnique passes when the field value is not unique.
     *
     * @return void
     * @covers ::assertDataValidationIsUnique
     */
    public function testAssertDataValidationIsUnique(): void
    {
        // Ensure the rule works as expected first
        $field = 'unique_field';
        $fieldValue = 'duplicate-value';
        $dataset = [
            $field => $fieldValue,
            'required_field' => 'required',
        ];
        $expectedErrors = ['_isUnique' => 'This value is already in use'];

        $existing = $this->table->newEntity($dataset, ['validate' => false]);
        static::assertNotFalse($this->table->save($existing));

        $duplicate = $this->table->newEntity($dataset, ['validate' => false]);
        static::assertFalse($this->table->checkRules($duplicate));
        static::assertSame($expectedErrors, $duplicate->getError($field));

        // Use a different value since the trait method will also save a record
        $this->table->deleteAll([]);
        $this->assertDataValidationIsUnique($this->table, $field, 'another-duplicate-value', $dataset);
    }

    /**
     * Test that assertDataValidationIsUnique accepts a custom expected error.
     *
     * @return void
     * @covers ::assertDataValidationIsUnique
     */
    public function testAssertDataValidationIsUniqueCustomExpected(): void
    {
        $field = 'unique_field';
        $dataset = [
            $field => 'custom-duplicate-value',
            'required_field' => 'required',
        ];
        $expectedErrors = ['_isUnique' => 'This value is already in use'];

        $this->assertDataValidationIsUnique($this->table, $field, 'custom-duplicate-value', $dataset, $expectedErrors);
    }

    /**
     * Test that testRules passes when saving leads to the expected rule errors.
     *
     * @return void
     * @covers ::testRules
     */
    public function testTestRules(): void
    {
        $field = 'unique_field';
        $dataSet = ['required_field' => 'required', $field => 'duplicate', 'multi_rule_field' => 1];
        $expectedErrors = ['_isUnique' => 'This value is already in use'];

        // Ensure a first record exists so the unique rule will fail on the second
        $existing = $this->table->newEntity($dataSet);
        static::assertNotFalse($this->table->save($existing));

        // Ensure the rule works as expected first
        $duplicate = $this->table->newEntity($dataSet);
        static::assertEmpty($duplicate->getError($field));
        static::assertFalse($this->table->save($duplicate));
        static::assertSame($expectedErrors, $duplicate->getError($field));

        $this->assertRules($this->table, $field, $dataSet, $expectedErrors);
    }

    /**
     * Test the assertDataValidationContains base method.
     *
     * @return void
     * @covers ::assertDataValidationContains
     */
    public function testAssertDataValidationContains(): void
    {
        // An invalid scalar value must produce a `scalar` error
        $field = 'scalar_field';
        $expectedErrors = ['scalar' => 'The provided value must be scalar'];
        $dataSet = [$field => []];
        $entity = $this->table->newEntity($dataSet);
        static::assertArrayHasKey('scalar', $entity->getError($field));

        $this->assertDataValidationContains($this->table, $field, $dataSet, $expectedErrors);
    }

    /**
     * Test the assertDataValidationNotContains base method.
     *
     * @return void
     * @covers ::assertDataValidationNotContains
     */
    public function testAssertDataValidationNotContains(): void
    {
        // A valid boolean value must not produce a `boolean` error
        $field = 'boolean_field';
        $entity = $this->table->newEntity([$field => true]);
        static::assertArrayNotHasKey('boolean', $entity->getError($field));

        $this->assertDataValidationNotContains($this->table, $field, [$field => true], ['boolean']);
    }

    /**
     * Test the assertDataValidationListContains base method.
     *
     * @return void
     * @covers ::assertDataValidationListContains
     */
    public function testAssertDataValidationListContains(): void
    {
        $field = 'boolean_field';
        $expectedErrors = ['boolean' => 'The provided value must be a boolean'];
        $invalidValues = ['Not a boolean', 123];

        $this->assertDataValidationListContains($this->table, $invalidValues, $field, $expectedErrors);
    }

    /**
     * Test the assertDataValidationListNotContains base method.
     *
     * @return void
     * @covers ::assertDataValidationListNotContains
     */
    public function testAssertDataValidationListNotContains(): void
    {
        $field = 'boolean_field';
        $validValues = [true, false, 1, 0];

        $this->assertDataValidationListNotContains($this->table, $validValues, $field, ['boolean']);
    }

    /**
     * Test the assertDataValidationErrorsContain base method.
     *
     * @return void
     * @covers ::assertDataValidationErrorsContain
     */
    public function testAssertDataValidationErrorsContain(): void
    {
        $field = 'boolean_field';
        // The errors contain the expected rule alongside an unrelated one, which must be ignored
        $errors = [
            'boolean' => 'The provided value must be a boolean',
            'maxLength' => 'The provided value is too long',
        ];
        $expected = ['boolean' => 'The provided value must be a boolean'];

        $this->assertDataValidationErrorsContain($field, $errors, $expected);
    }

    /**
     * Test that a type-specific method only asserts its own rule and ignores unrelated errors.
     *
     * @return void
     * @covers ::assertDataValidationInteger
     */
    public function testTypeSpecificMethodIgnoresUnrelatedErrors(): void
    {
        $field = 'multi_rule_field';

        // The invalid value triggers both the integer and the (unrelated) maxLength rule
        $entity = $this->table->newEntity([$field => 'abcd']);
        $errors = $entity->getError($field);
        static::assertArrayHasKey('integer', $errors);
        static::assertArrayHasKey('maxLength', $errors);

        // The type-specific method still passes because it checks for the `integer` rule, only
        $this->assertDataValidationInteger($this->table, $field);
    }
}
