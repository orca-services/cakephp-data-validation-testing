<?php
declare(strict_types=1);

namespace DataValidationTesting\Test\TestCase\Traits;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use DataValidationTesting\Test\TestApp\Model\Table\ValidationTestTable;
use DataValidationTesting\Traits\DataValidationTestTrait;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

/**
 * The DataValidationTestTrait Test
 *
 * @coversDefaultClass \DataValidationTesting\Traits\DataValidationTestTrait
 */
class DataValidationTestTraitTest extends TestCase
{
    use DataValidationTestTrait;

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
     * Test the testDataValidation base method
     *
     * @return void
     * @covers ::testDataValidation
     */
    public function testTestDataValidation(): void
    {
        // Ensure data validation of some field works as expected first
        $fieldName = 'not_empty_field';
        $dataSet = [$fieldName => ''];
        $entity = $this->table->newEntity($dataSet);
        $expectedErrors = ['_empty' => 'This field cannot be left empty'];
        $errors = $entity->getError($fieldName);
        $this->assertSame($expectedErrors, $errors);

        $this->testDataValidation($this->table, $fieldName, $dataSet, $expectedErrors);
    }

    /**
     * Test the testDataValidationNoErrors base method
     *
     * @return void
     * @covers ::testDataValidationNoErrors
     */
    public function testTestDataValidationNoErrors(): void
    {
        // Ensure data validation of some field works as expected first
        $fieldName = 'empty_field';
        $dataSet = [$fieldName => ''];
        $entity = $this->table->newEntity($dataSet);
        $expectedErrors = [];
        $errors = $entity->getError($fieldName);
        $this->assertSame($expectedErrors, $errors);

        $this->testDataValidationNoErrors($this->table, $fieldName, $dataSet);
    }

    /**
     * Test that testDataValidationNotEmpty passes when the field is not empty.
     *
     * @return void
     * @covers ::testDataValidationNotEmpty
     */
    public function testTestDataValidationNotEmpty(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'not_empty_field';
        $expectedErrors = ['_empty' => 'This field cannot be left empty'];
        $dataSet = [$field => ''];
        $this->testDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->testDataValidationNotEmpty($this->table, $field);
    }

    /**
     * Test that testDataValidationEmpty passes when the field is empty.
     *
     * @return void
     * @covers ::testDataValidationEmpty
     */
    public function testTestDataValidationEmpty(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'empty_field';
        $dataSet = [$field => ''];
        $this->testDataValidationNoErrors($this->table, $field, $dataSet);

        $this->testDataValidationEmpty($this->table, $field);
    }

    /**
     * Test that Required passes when the field is required.
     *
     * @return void
     * @covers ::testDataValidationRequired
     */
    public function testRequiredPasses(): void
    {
        $this->testDataValidationRequired($this->table, 'required_field');
    }

    /**
     * Test that Required fails when the field is empty.
     *
     * @return void
     * @covers ::testDataValidationRequired
     */
    public function testRequiredFailsWhenFieldNotRequired(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->testDataValidationRequired($this->table, 'empty_field');
    }

    /**
     * Test that NotRequired passes when the field is empty.
     *
     * @return void
     * @covers ::testDataValidationNotRequired
     */
    public function testNotRequiredPasses(): void
    {
        $this->testDataValidationNotRequired($this->table, 'empty_field');
    }

    /**
     * Test that NotRequired fails when the field is required.
     *
     * @return void
     * @covers ::testDataValidationNotRequired
     */
    public function testNotRequiredFailsWhenFieldRequired(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->testDataValidationNotRequired($this->table, 'required_field');
    }

    /**
     * Test that Boolean passes when the field is boolean.
     *
     * @return void
     * @covers ::testDataValidationBoolean
     */
    public function testBooleanPasses(): void
    {
        $this->testDataValidationBoolean($this->table, 'boolean_field');
    }

    /**
     * Test that Boolean fails when the field is not boolean.
     *
     * @return void
     * @covers ::testDataValidationBoolean
     */
    public function testBooleanFailsOnNonBooleanField(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->testDataValidationBoolean($this->table, 'max_length_field');
    }

    /**
     * Test that URLWithProtocol passes when the field is url.
     *
     * @return void
     * @covers ::testDataValidationURLWithProtocol
     */
    public function testUrlWithProtocolPasses(): void
    {
        $this->testDataValidationURLWithProtocol($this->table, 'url_field');
    }

    /**
     * Test that URLWithProtocol fails when the field is not a url.
     *
     * @return void
     * @covers ::testDataValidationURLWithProtocol
     */
    public function testUrlWithProtocolFailsOnNonUrlField(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->testDataValidationURLWithProtocol($this->table, 'max_length_field');
    }

    /**
     * Test that DateTime passes when the field is datetime.
     *
     * @return void
     * @covers ::testDataValidationDateTime
     */
    public function testDateTimePasses(): void
    {
        $this->testDataValidationDateTime($this->table, 'datetime_field');
    }

    /**
     * Test that DateTime fails when the field is not a datetime.
     *
     * @return void
     * @covers ::testDataValidationDateTime
     */
    public function testDateTimeFailsOnNonDateTimeField(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->testDataValidationDateTime($this->table, 'max_length_field');
    }

    /**
     * Test that MaxLength passes when the value is less than the max length.
     *
     * @return void
     * @covers ::testDataValidationMaxLength
     */
    public function testMaxLengthPasses(): void
    {
        $this->testDataValidationMaxLength($this->table, 'max_length_field', 10);
    }

    /**
     * Test that MaxLength fails when the field has no max length rule.
     *
     * @return void
     * @covers ::testDataValidationMaxLength
     */
    public function testMaxLengthFailsWhenFieldIsNoMaxLength(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->testDataValidationMaxLength($this->table, 'empty_field', 10);
    }

    /**
     * Test that MinLength passes when the value is greater than the min length.
     *
     * @return void
     * @covers ::testDataValidationMinLength
     */
    public function testMinLengthPasses(): void
    {
        $this->testDataValidationMinLength($this->table, 'min_length_field', 5);
    }

    /**
     * Test that MinLength fails when the field has no min length rule.
     *
     * @return void
     * @covers ::testDataValidationMinLength
     */
    public function testMinLengthFailsWhenFieldIsNoMinLength(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->testDataValidationMinLength($this->table, 'empty_field', 5);
    }

    /**
     * Test that Scalar passes when the field is scalar.
     *
     * @return void
     * @covers ::testDataValidationScalar
     */
    public function testScalarPasses(): void
    {
        $this->testDataValidationScalar($this->table, 'scalar_field');
    }

    /**
     * Test that Scalar fails when the field is no scalar.
     *
     * @return void
     * @covers ::testDataValidationScalar
     */
    public function testScalarFailsWhenFieldIsNoScalar(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->testDataValidationScalar($this->table, 'empty_field');
    }

    /**
     * Test that LengthBetween passes when the field is between the min and max length.
     *
     * @return void
     * @covers ::testDataValidationLengthBetween
     */
    public function testLengthBetweenPasses(): void
    {
        $this->testDataValidationLengthBetween($this->table, 'length_between_field', 5, 10);
    }

    /**
     * Test that LengthBetween fails when the field has no length between rule.
     *
     * @return void
     * @covers ::testDataValidationLengthBetween
     */
    public function testLengthBetweenFails(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->testDataValidationLengthBetween($this->table, 'empty_field', 5, 10);
    }

    /**
     * Test that NaturalNumber passes when the field is a natural number.
     *
     * @return void
     * @covers ::testDataValidationNaturalNumber
     */
    public function testNaturalNumberPasses(): void
    {
        $this->testDataValidationNaturalNumber($this->table, 'natural_number_field');
    }

    /**
     * Test that NoErrors passes when the data set has no errors.
     *
     * @return void
     * @covers ::testDataValidationNoErrors
     */
    public function testNoErrorsPasses(): void
    {
        $this->testDataValidationNoErrors(
            $this->table,
            'not_empty_field',
            ['not_empty_field' => 'hello', 'required_field' => 'x']
        );
    }

    /**
     * Test that NoErrors fails when the data set has errors.
     *
     * @return void
     * @covers ::testDataValidationNoErrors
     */
    public function testNoErrorsFailsWhenDataSetHasErrors(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->testDataValidationNoErrors(
            $this->table,
            'not_empty_field',
            ['not_empty_field' => '']
        );
    }

    /**
     * Test that FullDataValidationNoErrors passes when the full data set has no errors.
     *
     * @return void
     * @covers ::testFullDataValidationNoErrors
     */
    public function testFullDataValidationNoErrorsPasses(): void
    {
        $this->testFullDataValidationNoErrors(
            $this->table,
            ['required_field' => 'x']
        );
    }

    /**
     * Test that FullDataValidationNoErrors fails when the full data set has errors.
     *
     * @return void
     * @covers ::testFullDataValidationNoErrors
     */
    public function testFullDataValidationNoErrorsFails(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->testFullDataValidationNoErrors(
            $this->table,
            ['not_empty_field' => '']
        );
    }

    /**
     * Test that FullDataValidation reports all errors.
     *
     * @return void
     * @covers ::testFullDataValidation
     */
    public function testFullDataValidationReportsAllErrors(): void
    {
        $this->testFullDataValidation(
            $this->table,
            ['not_empty_field' => ''],
            [
                'not_empty_field' => ['_empty' => 'This field cannot be left empty'],
                'required_field' => ['_required' => 'This field is required'],
            ]
        );
    }

    /**
     * Test that FullDataValidation fails when the errors do not match.
     *
     * @return void
     * @covers ::testFullDataValidation
     */
    public function testFullDataValidationFails(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->testFullDataValidation(
            $this->table,
            ['required_field' => 'x'],
            [
                'not_empty_field' => ['_empty' => 'This field cannot be left empty'],
            ]
        );
    }
}
