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
        static::assertSame($expectedErrors, $errors);

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
        static::assertSame($expectedErrors, $errors);

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
     * Test that testDataValidationRequired passes when the field is required.
     *
     * @return void
     * @covers ::testDataValidationRequired
     */
    public function testTestDataValidationRequired(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'required_field';
        $expectedErrors = ['_required' => 'This field is required'];
        $dataSet = [];
        $this->testDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->testDataValidationRequired($this->table, $field);
    }

    /**
     * Test that testDataValidationNotRequired passes when the field is empty.
     *
     * @return void
     * @covers ::testDataValidationNotRequired
     */
    public function testTestDataValidationNotRequired(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'empty_field';
        $dataSet = [];
        $this->testDataValidationNoErrors($this->table, $field, $dataSet);

        $this->testDataValidationNotRequired($this->table, $field);
    }

    /**
     * Test that testDataValidationBoolean passes when the field is boolean.
     *
     * @return void
     * @covers ::testDataValidationBoolean
     */
    public function testTestDataValidationBoolean(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'boolean_field';
        $expectedErrors = ['boolean' => 'The provided value is invalid'];
        $dataSet = [$field => 'Not a boolean'];
        $this->testDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->testDataValidationBoolean($this->table, $field);
    }

    /**
     * Test that testDataValidationURLWithProtocol passes when the field is url.
     *
     * @return void
     * @covers ::testDataValidationURLWithProtocol
     */
    public function testTestDataValidationURLWithProtocol(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'url_field';
        $expectedErrors = ['urlWithProtocol' => 'The provided value is invalid'];
        $dataSet = [$field => 'no-protocol.com'];
        $this->testDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->testDataValidationURLWithProtocol($this->table, $field);
    }

    /**
     * Test that testDataValidationDateTime passes when the field is datetime.
     *
     * @return void
     * @covers ::testDataValidationDateTime
     */
    public function testTestDataValidationDateTime(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'datetime_field';
        $expectedErrors = ['dateTime' => 'The provided value is invalid'];
        $dataSet = [$field => 'Not a date/time'];
        $this->testDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->testDataValidationDateTime($this->table, $field);
    }

    /**
     * Test that testDataValidationInList passes when the field is datetime.
     *
     * @return void
     * @covers ::testDataValidationInList
     */
    public function testTestDataValidationInList(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'datetime_field';
        $expectedErrors = ['dateTime' => 'The provided value is invalid'];
        $invalidValues = ['Not a date/time', '123'];
        $this->testDataValidationInList($this->table, $invalidValues, $field, $expectedErrors);

        $validValues = ['1900-01-01 00:00:00', '2022-10-12 11:50:32'];
        $this->testDataValidationInList($this->table, $validValues, $field);
    }

    /**
     * Test that testDataValidationMaxLength passes when the value is less than the max length.
     *
     * @return void
     * @covers ::testDataValidationMaxLength
     */
    public function testTestDataValidationMaxLength(): void
    {
        // Ensure data validation of the field works as expected first
        $maxLength = 10;
        $field = 'max_length_field';
        $expectedErrors = ['maxLength' => 'The provided value is invalid'];
        $dataSet = [$field => str_repeat('A', $maxLength + 1)];
        $this->testDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->testDataValidationMaxLength($this->table, $field, $maxLength);
    }

    /**
     * Test that testDataValidationMinLength passes when the value is greater than the min length.
     *
     * @return void
     * @covers ::testDataValidationMinLength
     */
    public function testTestDataValidationMinLength(): void
    {
        // Ensure data validation of the field works as expected first
        $minLength = 5;
        $field = 'min_length_field';
        $expectedErrors = ['minLength' => 'The provided value is invalid'];
        $dataSet = [$field => str_repeat('A', $minLength - 1)];
        $this->testDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->testDataValidationMinLength($this->table, $field, $minLength);
    }

    /**
     * Test that testDataValidationScalar passes when the field is scalar.
     *
     * @return void
     * @covers ::testDataValidationScalar
     */
    public function testTestDataValidationScalar(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'scalar_field';
        $expectedErrors = [
            'scalar' => 'The provided value is invalid',
            'maxLength' => 'The provided value is invalid',
        ];
        $dataSet = [$field => []];
        $this->testDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->testDataValidationScalar($this->table, $field);
    }

    /**
     * Test that testDataValidationLengthBetween passes when the field is between the min and max length.
     *
     * @return void
     * @covers ::testDataValidationLengthBetween
     */
    public function testTestDataValidationLengthBetween(): void
    {
        // Ensure data validation of the field works as expected first
        $minLength = 5;
        $maxLength = 10;
        $field = 'length_between_field';
        $expectedErrors = ['lengthBetween' => 'The provided value is invalid'];
        $dataSet = [$field => str_repeat('A', $minLength - 1)];
        $this->testDataValidation($this->table, $field, $dataSet, $expectedErrors);
        $dataSet = [$field => str_repeat('A', $maxLength + 1)];
        $this->testDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->testDataValidationLengthBetween($this->table, $field, $minLength, $maxLength);
    }

    /**
     * Test that testDataValidationNaturalNumber passes when the field is a natural number.
     *
     * @return void
     * @covers ::testDataValidationNaturalNumber
     */
    public function testTestDataValidationNaturalNumber(): void
    {
        // Ensure data validation of the field works as expected first
        $field = 'natural_number_field';
        $expectedErrors = ['naturalNumber' => 'The provided value is invalid'];
        $dataSet = [$field => -1];
        $this->testDataValidation($this->table, $field, $dataSet, $expectedErrors);

        $this->testDataValidationNaturalNumber($this->table, $field);
    }

    /**
     * Test that testFullDataValidationNoErrors passes when the full data set has no errors.
     *
     * @return void
     * @covers ::testFullDataValidationNoErrors
     */
    public function testTestFullDataValidationNoErrors(): void
    {
        $dataSet = ['required_field' => 'required'];
        $this->testFullDataValidationNoErrors($this->table, $dataSet);
    }

    /**
     * Test that testFullDataValidation reports all errors.
     *
     * @return void
     * @covers ::testFullDataValidation
     */
    public function testTestFullDataValidation(): void
    {
        $dataSet = ['not_empty_field' => ''];
        $expectedErrors = [
            'not_empty_field' => ['_empty' => 'This field cannot be left empty'],
            'required_field' => ['_required' => 'This field is required'],
        ];
        $this->testFullDataValidation($this->table, $dataSet, $expectedErrors);
    }
}
