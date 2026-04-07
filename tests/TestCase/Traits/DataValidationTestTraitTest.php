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
     * Test the _testDataValidation base method
     *
     * @return void
     * @covers ::_testDataValidation
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

        $this->_testDataValidation($this->table, $fieldName, $dataSet, $expectedErrors);
    }

    /**
     * Test the _testDataValidationNoErrors base method
     *
     * @return void
     * @covers ::_testDataValidationNoErrors
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

        $this->_testDataValidationNoErrors($this->table, $fieldName, $dataSet);
    }

    /**
     * Test that _testDataValidationNotEmpty passes when the field is not empty.
     *
     * @return void
     * @covers ::_testDataValidationNotEmpty
     */
    public function testTestDataValidationNotEmpty(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'not_empty_field';
        $expectedErrors = ['_empty' => 'This field cannot be left empty'];
        $dataSet = [$field => ''];
        $this->_testDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->_testDataValidationNotEmpty($this->table, $field);
    }

    /**
     * Test that _testDataValidationEmpty passes when the field is empty.
     *
     * @return void
     * @covers ::_testDataValidationEmpty
     */
    public function testTestDataValidationEmpty(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'empty_field';
        $dataSet = [$field => ''];
        $this->_testDataValidationNoErrors($this->table, $field, $dataSet);

        $this->_testDataValidationEmpty($this->table, $field);
    }

    /**
     * Test that Required passes when the field is required.
     *
     * @return void
     * @covers ::_testDataValidationRequired
     */
    public function testRequiredPasses(): void
    {
        $this->_testDataValidationRequired($this->table, 'required_field');
    }

    /**
     * Test that Required fails when the field is empty.
     *
     * @return void
     * @covers ::_testDataValidationRequired
     */
    public function testRequiredFailsWhenFieldNotRequired(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->_testDataValidationRequired($this->table, 'empty_field');
    }

    /**
     * Test that NotRequired passes when the field is empty.
     *
     * @return void
     * @covers ::_testDataValidationNotRequired
     */
    public function testNotRequiredPasses(): void
    {
        $this->_testDataValidationNotRequired($this->table, 'empty_field');
    }

    /**
     * Test that NotRequired fails when the field is required.
     *
     * @return void
     * @covers ::_testDataValidationNotRequired
     */
    public function testNotRequiredFailsWhenFieldRequired(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->_testDataValidationNotRequired($this->table, 'required_field');
    }

    /**
     * Test that Boolean passes when the field is boolean.
     *
     * @return void
     * @covers ::_testDataValidationBoolean
     */
    public function testBooleanPasses(): void
    {
        $this->_testDataValidationBoolean($this->table, 'boolean_field');
    }

    /**
     * Test that Boolean fails when the field is not boolean.
     *
     * @return void
     * @covers ::_testDataValidationBoolean
     */
    public function testBooleanFailsOnNonBooleanField(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->_testDataValidationBoolean($this->table, 'max_length_field');
    }

    /**
     * Test that URLWithProtocol passes when the field is url.
     *
     * @return void
     * @covers ::_testDataValidationURLWithProtocol
     */
    public function testUrlWithProtocolPasses(): void
    {
        $this->_testDataValidationURLWithProtocol($this->table, 'url_field');
    }

    /**
     * Test that URLWithProtocol fails when the field is not a url.
     *
     * @return void
     * @covers ::_testDataValidationURLWithProtocol
     */
    public function testUrlWithProtocolFailsOnNonUrlField(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->_testDataValidationURLWithProtocol($this->table, 'max_length_field');
    }

    /**
     * Test that DateTime passes when the field is datetime.
     *
     * @return void
     * @covers ::_testDataValidationDateTime
     */
    public function testDateTimePasses(): void
    {
        $this->_testDataValidationDateTime($this->table, 'datetime_field');
    }

    /**
     * Test that DateTime fails when the field is not a datetime.
     *
     * @return void
     * @covers ::_testDataValidationDateTime
     */
    public function testDateTimeFailsOnNonDateTimeField(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->_testDataValidationDateTime($this->table, 'max_length_field');
    }

    /**
     * Test that MaxLength passes when the value is less than the max length.
     *
     * @return void
     * @covers ::_testDataValidationMaxLength
     */
    public function testMaxLengthPasses(): void
    {
        $this->_testDataValidationMaxLength($this->table, 'max_length_field', 10);
    }

    /**
     * Test that MaxLength fails when the field has no max length rule.
     *
     * @return void
     * @covers ::_testDataValidationMaxLength
     */
    public function testMaxLengthFailsWhenFieldIsNoMaxLength(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->_testDataValidationMaxLength($this->table, 'empty_field', 10);
    }

    /**
     * Test that MinLength passes when the value is greater than the min length.
     *
     * @return void
     * @covers ::_testDataValidationMinLength
     */
    public function testMinLengthPasses(): void
    {
        $this->_testDataValidationMinLength($this->table, 'min_length_field', 5);
    }

    /**
     * Test that MinLength fails when the field has no min length rule.
     *
     * @return void
     * @covers ::_testDataValidationMinLength
     */
    public function testMinLengthFailsWhenFieldIsNoMinLength(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->_testDataValidationMinLength($this->table, 'empty_field', 5);
    }

    /**
     * Test that Scalar passes when the field is scalar.
     *
     * @return void
     * @covers ::_testDataValidationScalar
     */
    public function testScalarPasses(): void
    {
        $this->_testDataValidationScalar($this->table, 'scalar_field');
    }

    /**
     * Test that Scalar fails when the field is no scalar.
     *
     * @return void
     * @covers ::_testDataValidationScalar
     */
    public function testScalarFailsWhenFieldIsNoScalar(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->_testDataValidationScalar($this->table, 'empty_field');
    }

    /**
     * Test that LengthBetween passes when the field is between the min and max length.
     *
     * @return void
     * @covers ::_testDataValidationLengthBetween
     */
    public function testLengthBetweenPasses(): void
    {
        $this->_testDataValidationLengthBetween($this->table, 'length_between_field', 5, 10);
    }

    /**
     * Test that LengthBetween fails when the field has no length between rule.
     *
     * @return void
     * @covers ::_testDataValidationLengthBetween
     */
    public function testLengthBetweenFails(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->_testDataValidationLengthBetween($this->table, 'empty_field', 5, 10);
    }

    /**
     * Test that NaturalNumber passes when the field is a natural number.
     *
     * @return void
     * @covers ::_testDataValidationNaturalNumber
     */
    public function testNaturalNumberPasses(): void
    {
        $this->_testDataValidationNaturalNumber($this->table, 'natural_number_field');
    }

    /**
     * Test that NoErrors passes when the data set has no errors.
     *
     * @return void
     * @covers ::_testDataValidationNoErrors
     */
    public function testNoErrorsPasses(): void
    {
        $this->_testDataValidationNoErrors(
            $this->table,
            'not_empty_field',
            ['not_empty_field' => 'hello', 'required_field' => 'x']
        );
    }

    /**
     * Test that NoErrors fails when the data set has errors.
     *
     * @return void
     * @covers ::_testDataValidationNoErrors
     */
    public function testNoErrorsFailsWhenDataSetHasErrors(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->_testDataValidationNoErrors(
            $this->table,
            'not_empty_field',
            ['not_empty_field' => '']
        );
    }

    /**
     * Test that FullDataValidationNoErrors passes when the full data set has no errors.
     *
     * @return void
     * @covers ::_testFullDataValidationNoErrors
     */
    public function testFullDataValidationNoErrorsPasses(): void
    {
        $this->_testFullDataValidationNoErrors(
            $this->table,
            ['required_field' => 'x']
        );
    }

    /**
     * Test that FullDataValidationNoErrors fails when the full data set has errors.
     *
     * @return void
     * @covers ::_testFullDataValidationNoErrors
     */
    public function testFullDataValidationNoErrorsFails(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->_testFullDataValidationNoErrors(
            $this->table,
            ['not_empty_field' => '']
        );
    }

    /**
     * Test that FullDataValidation reports all errors.
     *
     * @return void
     * @covers ::_testFullDataValidation
     */
    public function testFullDataValidationReportsAllErrors(): void
    {
        $this->_testFullDataValidation(
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
     * @covers ::_testFullDataValidation
     */
    public function testFullDataValidationFails(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->_testFullDataValidation(
            $this->table,
            ['required_field' => 'x'],
            [
                'not_empty_field' => ['_empty' => 'This field cannot be left empty'],
            ]
        );
    }
}
