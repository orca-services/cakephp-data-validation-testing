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
     * Test the assertValidation base method
     *
     * @return void
     * @covers ::assertValidation
     */
    public function testAssertValidation(): void
    {
        // Ensure data validation of some field works as expected first
        $fieldName = 'not_empty_field';
        $dataSet = [$fieldName => ''];
        $entity = $this->table->newEntity($dataSet);
        $expectedErrors = ['_empty' => 'This field cannot be left empty'];
        $errors = $entity->getError($fieldName);
        static::assertSame($expectedErrors, $errors);

        $this->assertValidation($this->table, $fieldName, $dataSet, $expectedErrors);
    }

    /**
     * Test the assertValidationNoErrors base method
     *
     * @return void
     * @covers ::assertValidationNoErrors
     */
    public function testAssertValidationNoErrors(): void
    {
        // Ensure data validation of some field works as expected first
        $fieldName = 'empty_field';
        $dataSet = [$fieldName => ''];
        $entity = $this->table->newEntity($dataSet);
        $expectedErrors = [];
        $errors = $entity->getError($fieldName);
        static::assertSame($expectedErrors, $errors);

        $this->assertValidationNoErrors($this->table, $fieldName, $dataSet);
    }

    /**
     * Test that assertValidationNotEmpty passes when the field is not empty.
     *
     * @return void
     * @covers ::assertValidationNotEmpty
     */
    public function testAssertValidationNotEmpty(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'not_empty_field';
        $expectedErrors = ['_empty' => 'This field cannot be left empty'];
        $dataSet = [$field => ''];
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertValidationNotEmpty($this->table, $field);
    }

    /**
     * Test that assertValidationEmpty passes when the field is empty.
     *
     * @return void
     * @covers ::assertValidationEmpty
     */
    public function testAssertValidationEmpty(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'empty_field';
        $dataSet = [$field => ''];
        $this->assertValidationNoErrors($this->table, $field, $dataSet);

        $this->assertValidationEmpty($this->table, $field);
    }

    /**
     * Test that assertValidationRequired passes when the field is required.
     *
     * @return void
     * @covers ::assertValidationRequired
     */
    public function testAssertValidationRequired(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'required_field';
        $expectedErrors = ['_required' => 'This field is required'];
        $dataSet = [];
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertValidationRequired($this->table, $field);
    }

    /**
     * Test that assertValidationNotRequired passes when the field is empty.
     *
     * @return void
     * @covers ::assertValidationNotRequired
     */
    public function testAssertValidationNotRequired(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'empty_field';
        $dataSet = [];
        $this->assertValidationNoErrors($this->table, $field, $dataSet);

        $this->assertValidationNotRequired($this->table, $field);
    }

    /**
     * Test that assertValidationBoolean passes when the field is boolean.
     *
     * @return void
     * @covers ::assertValidationBoolean
     */
    public function testAssertValidationBoolean(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'boolean_field';
        $expectedErrors = ['boolean' => 'The provided value must be a boolean'];
        $dataSet = [$field => 'Not a boolean'];
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertValidationBoolean($this->table, $field);
    }

    /**
     * Test that assertValidationURLWithProtocol passes when the field is url.
     *
     * @return void
     * @covers ::assertValidationURLWithProtocol
     */
    public function testAssertValidationURLWithProtocol(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'url_field';
        $expectedErrors = ['urlWithProtocol' => 'The provided value must be a URL with protocol'];
        $dataSet = [$field => 'no-protocol.com'];
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertValidationURLWithProtocol($this->table, $field);
    }

    /**
     * Test that assertValidationDateTime passes when the field is datetime.
     *
     * @return void
     * @covers ::assertValidationDateTime
     */
    public function testAssertValidationDateTime(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'datetime_field';
        $expectedErrors = ['dateTime' => 'The provided value must be a date and time of one of these formats: `ymd`'];
        $dataSet = [$field => 'Not a date/time'];
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertValidationDateTime($this->table, $field);
    }

    /**
     * Test that assertValidationDate passes when the field is date.
     *
     * @return void
     * @covers ::assertValidationDate
     */
    public function testAssertValidationDate(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'date_field';
        $expectedErrors = ['date' => 'The provided value must be a date of one of these formats: `ymd`'];
        $dataSet = [$field => 'Not a date'];
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertValidationDate($this->table, $field);
    }

    /**
     * Test that assertValidationInList passes when the field is datetime.
     *
     * @return void
     * @covers ::assertValidationInList
     */
    public function testAssertValidationInList(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'datetime_field';
        $expectedErrors = ['dateTime' => 'The provided value must be a date and time of one of these formats: `ymd`'];
        $invalidValues = ['Not a date/time', '123'];
        $this->assertValidationInList($this->table, $invalidValues, $field, $expectedErrors);

        $validValues = ['1900-01-01 00:00:00', '2022-10-12 11:50:32'];
        $this->assertValidationInList($this->table, $validValues, $field);
    }

    /**
     * Test that assertValidationMaxLength passes when the value is less than the max length.
     *
     * @return void
     * @covers ::assertValidationMaxLength
     */
    public function testAssertValidationMaxLength(): void
    {
        // Ensure data validation of the field works as expected first
        $maxLength = 10;
        $field = 'max_length_field';
        $expectedErrors = ['maxLength' => 'The provided value must be at most `10` characters long'];
        $dataSet = [$field => str_repeat('A', $maxLength + 1)];
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertValidationMaxLength($this->table, $field, $maxLength);
    }

    /**
     * Test that assertValidationMinLength passes when the value is greater than the min length.
     *
     * @return void
     * @covers ::assertValidationMinLength
     */
    public function testAssertValidationMinLength(): void
    {
        // Ensure data validation of the field works as expected first
        $minLength = 5;
        $field = 'min_length_field';
        $expectedErrors = ['minLength' => 'The provided value must be at least `5` characters long'];
        $dataSet = [$field => str_repeat('A', $minLength - 1)];
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertValidationMinLength($this->table, $field, $minLength);
    }

    /**
     * Test that assertValidationScalar passes when the field is scalar.
     *
     * @return void
     * @covers ::assertValidationScalar
     */
    public function testAssertValidationScalar(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'scalar_field';
        $entity = $this->table->newEntity([$field => []]);
        static::assertArrayHasKey('scalar', $entity->getError($field));

        $this->assertValidationScalar($this->table, $field);
    }

    /**
     * Test that assertValidationDecimal passes when the field is decimal.
     *
     * @return void
     * @covers ::assertValidationDecimal
     */
    public function testAssertValidationDecimal(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'decimal_field';
        $expectedErrors = ['decimal' => 'The provided value must be decimal with any number of decimal places, including none'];
        $dataSet = [$field => 'not a decimal'];
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertValidationDecimal($this->table, $field);
    }

    /**
     * Test that assertValidationInteger passes when the field is integer.
     *
     * @return void
     * @covers ::assertValidationInteger
     */
    public function testAssertValidationInteger(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'integer_field';
        $expectedErrors = ['integer' => 'The provided value must be an integer'];
        $dataSet = [$field => 'not a integer'];
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertValidationInteger($this->table, $field);
    }

    /**
     * Test that assertValidationNonNegativeInteger passes when the field is a non-negative integer.
     *
     * @return void
     * @covers ::assertValidationNonNegativeInteger
     */
    public function testAssertValidationNonNegativeInteger(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'non_negative_integer_field';
        $expectedErrors = ['nonNegativeInteger' => 'The provided value must be a non-negative integer'];
        $dataSet = [$field => 'not a integer'];
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertValidationNonNegativeInteger($this->table, $field);
    }

    /**
     * Test that assertValidationGreaterThanOrEqual passes when the field value
     * is greater than or equal to the configured threshold.
     *
     * @return void
     * @covers ::assertValidationGreaterThanOrEqual
     */
    public function testAssertValidationGreaterThanOrEqual(): void
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
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);

        // At the threshold should pass
        $dataSet = [$field => $threshold];
        $this->assertValidationNoErrors($this->table, $field, $dataSet);

        // Above the threshold should pass
        $dataSet = [$field => $threshold + 1];
        $this->assertValidationNoErrors($this->table, $field, $dataSet);

        $this->assertValidationGreaterThanOrEqual($this->table, $field, $threshold);
    }

    /**
     * Test that assertValidationEmail passes when the field is a valid email.
     *
     * @return void
     * @covers ::assertValidationEmail
     */
    public function testAssertValidationEmail(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'email_field';
        $expectedErrors = [
            'email' => 'The provided value must be an e-mail address',
        ];
        $dataSet = [$field => 'Not an email'];
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertValidationEmail($this->table, $field);
    }

    /**
     * Test that assertValidationUuid passes when the field is a valid uuid.
     *
     * @return void
     * @covers ::assertValidationUuid
     */
    public function testAssertValidationUuid(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'uuid_field';
        $expectedErrors = [
            'uuid' => 'The provided value must be a UUID',
        ];
        $dataSet = [$field => 'Not a uuid'];
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertValidationUuid($this->table, $field);
    }

    /**
     * Test that assertValidationLengthBetween passes when the field is between the min and max length.
     *
     * @return void
     * @covers ::assertValidationLengthBetween
     */
    public function testAssertValidationLengthBetween(): void
    {
        // Ensure data validation of the field works as expected first
        $minLength = 5;
        $maxLength = 10;
        $field = 'length_between_field';
        $expectedErrors = ['lengthBetween' => 'The length of the provided value must be between `5` and `10`, inclusively'];
        $dataSet = [$field => str_repeat('A', $minLength - 1)];
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);
        $dataSet = [$field => str_repeat('A', $maxLength + 1)];
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertValidationLengthBetween($this->table, $field, $minLength, $maxLength);
    }

    /**
     * Test that assertValidationRange passes when the field is between the lower and upper bound.
     *
     * @return void
     * @covers ::assertValidationRange
     */
    public function testAssertValidationRange(): void
    {
        // Ensure data validation of the field works as expected first
        $lowerBound = -30.0;
        $upperBound = 30.0;
        $field = 'range_field';
        $expectedErrors = ['range' => 'The provided value must be between `-30` and `30`, inclusively'];

        $dataSet = [$field => $lowerBound - 1];
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);

        $dataSet = [$field => $upperBound + 1];
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertValidationRange($this->table, $field, $lowerBound, $upperBound);
    }

    /**
     * Test that assertValidationNaturalNumber passes when the field is a natural number.
     *
     * @return void
     * @covers ::assertValidationNaturalNumber
     */
    public function testAssertValidationNaturalNumber(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'natural_number_field';
        $expectedErrors = ['naturalNumber' => 'The provided value must be a natural number'];
        $dataSet = [$field => -1];
        $this->assertValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->assertValidationNaturalNumber($this->table, $field);
    }

    /**
     * Test that assertValidationTableNoErrors passes when the full data set has no errors.
     *
     * @return void
     * @covers ::assertValidationTableNoErrors
     */
    public function testAssertValidationTableNoErrors(): void
    {
        $dataSet = ['required_field' => 'required', 'multi_rule_field' => 1];
        $this->assertValidationTableNoErrors($this->table, $dataSet);
    }

    /**
     * Test that assertValidationTableErrors reports all errors.
     *
     * @return void
     * @covers ::assertValidationTableErrors
     */
    public function testAssertValidationTableErrors(): void
    {
        $dataSet = ['not_empty_field' => '', 'multi_rule_field' => 1];
        $expectedErrors = [
            'not_empty_field' => ['_empty' => 'This field cannot be left empty'],
            'required_field' => ['_required' => 'This field is required'],
        ];
        $this->assertValidationTableErrors($this->table, $dataSet, $expectedErrors);
    }

    /**
     * Test that assertDataRules passes when saving leads to the expected rule errors.
     *
     * @return void
     * @covers ::assertDataRules
     */
    public function testAssertDataRules(): void
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
     * Test that assertRulesNoErrors passes when saving leads to no rule errors.
     *
     * @return void
     * @covers ::assertRulesNoErrors
     */
    public function testAssertRulesNoErrors(): void
    {
        // Ensure the rule works as expected first
        $field = 'unique_field';
        $dataSet = [$field => 'unique-value-' . uniqid('', true)];

        $entity = $this->table->newEntity($dataSet, ['validate' => false]);
        static::assertNotFalse($this->table->save($entity));
        static::assertEmpty($entity->getError($field));

        $this->assertRulesNoErrors($this->table, $field, [$field => 'another-unique-' . uniqid('', true)]);
    }

    /**
     * Test that assertValidationForeignKey passes when the foreign key does not exist.
     *
     * @return void
     * @covers ::assertValidationForeignKey
     */
    public function testAssertValidationForeignKey(): void
    {
        // Ensure the rule works as expected first
        $field = 'parent_id';
        $notExistingForeignKey = 999999;
        $expectedErrors = ['_existsIn' => 'This value does not exist'];

        $entity = $this->table->newEntity([$field => $notExistingForeignKey], ['validate' => false]);
        static::assertFalse($this->table->checkRules($entity));
        static::assertSame($expectedErrors, $entity->getError($field));

        $this->assertValidationForeignKey($this->table, $field, $notExistingForeignKey);
    }

    /**
     * Test that assertValidationForeignKey passes with the default not existing foreign key.
     *
     * @return void
     * @covers ::assertValidationForeignKey
     */
    public function testAssertValidationForeignKeyDefault(): void
    {
        $field = 'parent_id';
        $this->assertValidationForeignKey($this->table, $field);
    }

    /**
     * Test that assertValidationForeignKey accepts a custom expected error.
     *
     * @return void
     * @covers ::assertValidationForeignKey
     */
    public function testAssertValidationForeignKeyCustomExpected(): void
    {
        $field = 'parent_id';
        $expectedErrors = ['_existsIn' => 'This value does not exist'];

        $this->assertValidationForeignKey($this->table, $field, 999999, $expectedErrors);
    }

    /**
     * Test that assertValidationIsUnique passes when the field value is not unique.
     *
     * @return void
     * @covers ::assertValidationIsUnique
     */
    public function testAssertValidationIsUnique(): void
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
        $this->assertValidationIsUnique($this->table, $field, 'another-duplicate-value', $dataset);
    }

    /**
     * Test that assertValidationIsUnique accepts a custom expected error.
     *
     * @return void
     * @covers ::assertValidationIsUnique
     */
    public function testAssertValidationIsUniqueCustomExpected(): void
    {
        $field = 'unique_field';
        $dataset = [
            $field => 'custom-duplicate-value',
            'required_field' => 'required',
        ];
        $expectedErrors = ['_isUnique' => 'This value is already in use'];

        $this->assertValidationIsUnique($this->table, $field, 'custom-duplicate-value', $dataset, $expectedErrors);
    }

    /**
     * Test that assertRules passes when saving leads to the expected rule errors.
     *
     * @return void
     * @covers ::assertRules
     */
    public function testAssertRules(): void
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
     * Test the assertValidationContains base method.
     *
     * @return void
     * @covers ::assertValidationContains
     */
    public function testAssertValidationContains(): void
    {
        // An invalid scalar value must produce a `scalar` error
        $field = 'scalar_field';
        $expectedErrors = ['scalar' => 'The provided value must be scalar'];
        $dataSet = [$field => []];
        $entity = $this->table->newEntity($dataSet);
        static::assertArrayHasKey('scalar', $entity->getError($field));

        $this->assertValidationContains($this->table, $field, $dataSet, $expectedErrors);
    }

    /**
     * Test the assertValidationNotContains base method.
     *
     * @return void
     * @covers ::assertValidationNotContains
     */
    public function testAssertValidationNotContains(): void
    {
        // A valid boolean value must not produce a `boolean` error
        $field = 'boolean_field';
        $entity = $this->table->newEntity([$field => true]);
        static::assertArrayNotHasKey('boolean', $entity->getError($field));

        $this->assertValidationNotContains($this->table, $field, [$field => true], ['boolean']);
    }

    /**
     * Test the assertValidationListContains base method.
     *
     * @return void
     * @covers ::assertValidationListContains
     */
    public function testAssertValidationListContains(): void
    {
        $field = 'boolean_field';
        $expectedErrors = ['boolean' => 'The provided value must be a boolean'];
        $invalidValues = ['Not a boolean', 123];

        $this->assertValidationListContains($this->table, $invalidValues, $field, $expectedErrors);
    }

    /**
     * Test the assertValidationListNotContains base method.
     *
     * @return void
     * @covers ::assertValidationListNotContains
     */
    public function testAssertValidationListNotContains(): void
    {
        $field = 'boolean_field';
        $validValues = [true, false, 1, 0];

        $this->assertValidationListNotContains($this->table, $validValues, $field, ['boolean']);
    }

    /**
     * Test the assertValidationErrorsContain base method.
     *
     * @return void
     * @covers ::assertValidationErrorsContain
     */
    public function testAssertValidationErrorsContain(): void
    {
        $field = 'boolean_field';
        // The errors contain the expected rule alongside an unrelated one, which must be ignored
        $errors = [
            'boolean' => 'The provided value must be a boolean',
            'maxLength' => 'The provided value is too long',
        ];
        $expected = ['boolean' => 'The provided value must be a boolean'];

        $this->assertValidationErrorsContain($field, $errors, $expected);
    }

    /**
     * Test that a type-specific method only asserts its own rule and ignores unrelated errors.
     *
     * @return void
     * @covers ::assertValidationInteger
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
        $this->assertValidationInteger($this->table, $field);
    }
}
