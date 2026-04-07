<?php
declare(strict_types=1);

namespace DataValidationTesting\Traits;

use Cake\Chronos\Chronos;
use Cake\Chronos\Date;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\ORM\Table;

/**
 * A Trait for testing Data Validation Rules
 *
 * @mixin \PHPUnit\Framework\TestCase
 */
trait DataValidationTestTrait
{
    /**
     * Validate that a given field cannot be empty.
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check for data validation errors.
     * @param array $additionalDataSet Additional data set to test.
     * @param array $options Additional options for newEntity.
     * @return void
     */
    protected function _testDataValidationNotEmpty(
        Table $table,
        string $fieldName,
        array $additionalDataSet = [],
        array $options = []
    ): void {
        $list = [null, ''];

        $expected = ['_empty' => 'This field cannot be left empty'];
        $this->_testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);
    }

    /**
     * Validate that a given field can be empty.
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check for data validation errors.
     * @param array $additionalDataSet Additional data set to test.
     * @param array $options Additional options for newEntity.
     * @return void
     */
    protected function _testDataValidationEmpty(
        Table $table,
        string $fieldName,
        array $additionalDataSet = [],
        array $options = []
    ): void {
        $list = [null, ''];

        $expected = [];
        $this->_testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);
    }

    /**
     * Validate that a given field is required.
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check for data validation errors.
     * @param array $dataSet The data set to test.
     * @param array $options Additional options for newEntity.
     * @return void
     */
    protected function _testDataValidationRequired(
        Table $table,
        string $fieldName,
        array $dataSet = [],
        array $options = []
    ): void {
        $expected = ['_required' => 'This field is required'];
        $this->_testDataValidation($table, $fieldName, $dataSet, $expected, $options);
    }

    /**
     * Validate that a given field is not required.
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check for data validation errors.
     * @param array $dataSet The data set to test.
     * @param array $options Additional options for newEntity.
     * @return void
     */
    protected function _testDataValidationNotRequired(
        Table $table,
        string $fieldName,
        array $dataSet = [],
        array $options = []
    ): void {
        $this->_testDataValidationNoErrors($table, $fieldName, $dataSet, $options);
    }

    /**
     * Validate that a given field is validated as a boolean
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check for data validation errors.
     * @param array $additionalDataSet Additional data set to test.
     * @param array $options Additional options for newEntity.
     * @return void
     */
    protected function _testDataValidationBoolean(
        Table $table,
        string $fieldName,
        array $additionalDataSet = [],
        array $options = []
    ) {
        // Valid values
        $list = [true, false, 1, 0];
        $expected = [];
        $this->_testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);

        // Invalid values
        $list = ['Not a boolean', 123, []];
        $expected = ['boolean' => 'The provided value is invalid'];
        $this->_testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);
    }

    /**
     * Validate that a given field is validated for a URL with protocol
     *
     * @param Table $table The table to test.
     * @param string $fieldName The field to check for data validation errors.
     * @param array $additionalDataSet Additional data set to test.
     * @param array $options Additional options for newEntity.
     * @return void
     */
    protected function _testDataValidationURLWithProtocol(
        Table $table,
        string $fieldName,
        array $additionalDataSet = [],
        array $options = []
    ): void {
        // Valid values
        $list = ['https://valid.com', 'http://valid.com'];
        $expected = [];
        $this->_testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);

        // Invalid values
        $list = ['no-protocol.com', 'htp://foo.com'];
        $expected = ['urlWithProtocol' => 'The provided value is invalid'];
        $this->_testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);
    }

    /**
     * Validate that a given field is validated as a date/time
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check for data validation errors.
     * @param array $additionalDataSet Additional data set to test.
     * @param array $options Additional options for newEntity.
     * @return void
     */
    protected function _testDataValidationDateTime(
        Table $table,
        string $fieldName,
        array $additionalDataSet = [],
        array $options = []
    ) {
        // Valid values
        $list = [
            '1900-01-01 00:00:00', // Far in the past
            '2022-10-12 11:50:32', // Around today
            '2099-12-31 23:59:59', // Far in the future
            new Chronos(),
            new Date(),
            new FrozenDate(),
            new FrozenTime(),
        ];
        $expected = [];
        $this->_testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);

        // Invalid values
        $list = ['Not a date/time', '123'];
        $expected = ['dateTime' => 'The provided value is invalid'];
        $this->_testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);
    }

    /**
     * Validate that each value of a list of values for a given table does not lead to data validation errors
     *
     * @param Table $table The table to test
     * @param array $list A list of values to test.
     * @param string $fieldName The field to check for data validation errors.
     * @param array $expected The expected data validation errors.
     * @param array $additionalDataSet Additional data set to test.
     * @param array $options Additional options for newEntity.
     * @return void
     */
    protected function _testDataValidationInList(
        Table $table,
        array $list,
        string $fieldName,
        array $expected = [],
        array $additionalDataSet = [],
        array $options = []
    ) {
        foreach ($list as $value) {
            $dataSet = array_merge($additionalDataSet, [$fieldName => $value]);
            $this->_testDataValidation($table, $fieldName, $dataSet, $expected, $options);
        }
    }

    /**
     * Validate that a given data set for a given table does not lead to data validation errors
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check for data validation errors.
     * @param array $dataSet The data set to test.
     * @param array $options Additional options for newEntity.
     * @return void
     */
    protected function _testDataValidationNoErrors(Table $table, string $fieldName, array $dataSet, array $options = [])
    {
        $expected = [];
        $this->_testDataValidation($table, $fieldName, $dataSet, $expected, $options);
    }

    /**
     * Validate that a given data set for a given table does not lead to data validation errors
     *
     * @param Table $table The table to test
     * @param array $dataSet The data set to test.
     * @param array $options Additional options for newEntity.
     * @return void
     */
    protected function _testFullDataValidationNoErrors(Table $table, array $dataSet, array $options = [])
    {
        $expected = [];
        $this->_testFullDataValidation($table, $dataSet, $expected, $options);
    }

    /**
     * Validate that a given data set for a given table leads to the expected data validation errors
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check for data validation errors.
     * @param array $dataSet The data set to test.
     * @param array $expected The expected data validation errors.
     * @param array $options Additional options for newEntity.
     * @return void
     */
    protected function _testDataValidation(
        Table $table,
        string $fieldName,
        array $dataSet,
        array $expected,
        array $options = []
    ) {
        $entity = $table->newEntity($dataSet, $options);
        $errors = $entity->getError($fieldName);
        static::assertEquals($expected, $errors);
    }

    /**
     * Validate that a given data set for a given table leads to the expected rule errors
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check for errors.
     * @param array $dataSet The data set to test.
     * @param array $expected The expected errors.
     * @param array $options Additional options for newEntity.
     * @return void
     * @todo Move to a rules dedicated helper class.
     */
    protected function _testDataRules(
        Table $table,
        string $fieldName,
        array $dataSet,
        array $expected,
        array $options = []
    ) {
        $defaultOptions = ['validate' => false];
        $options = $defaultOptions + $options;
        $entity = $table->newEntity($dataSet, $options);

        $entitySaved = $table->save($entity, $options);
        static::assertFalse($entitySaved);

        $errors = $entity->getError($fieldName);
        static::assertEquals($expected, $errors);
    }

    /**
     * Validate that a given data set for a given table leads to the expected table rules errors
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check for table rules errors.
     * @param array $dataSet The data set to test.
     * @param array $expected The expected table rules errors.
     * @param array $options Additional options for newEntity.
     * @return void
     */
    protected function _testRules(Table $table, string $fieldName, array $dataSet, array $expected, array $options = [])
    {
        $entity = $table->newEntity($dataSet, $options);
        $errors = $entity->getError($fieldName);
        static::assertEmpty($errors);

        $table->save($entity);
        $errors = $entity->getError($fieldName);
        static::assertEquals($expected, $errors);
    }

    /**
     * Validate that a given data set for a given table does not lead to data rules errors
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check for errors.
     * @param array $dataSet The data set to test.
     * @param array $options Additional options for newEntity.
     * @return void
     * @todo Move to a rules dedicated helper class.
     */
    protected function _testDataRulesNoErrors(Table $table, string $fieldName, array $dataSet, array $options = [])
    {
        $defaultOptions = ['validate' => false];
        $options = $defaultOptions + $options;
        $entity = $table->newEntity($dataSet, $options);

        $entitySaved = $table->save($entity, $options);
        static::assertTrue((bool)$entitySaved);

        $errors = $entity->getError($fieldName);
        static::assertEquals([], $errors);
    }

    /**
     * Validate the maximum length of a field for a given table
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check the maximum length for.
     * @param int $maxlength The maximum length of the field
     * @param array|null $expected The expected data validation errors.
     * @param ?array $options Additional options for newEntity.
     * @return void
     */
    protected function _testDataValidationMaxLength(
        Table $table,
        string $fieldName,
        int $maxlength,
        ?array $expected = null,
        ?array $options = []
    ): void {
        $tooLongFieldContent = str_repeat('A', $maxlength + 1);
        $dataset = [$fieldName => $tooLongFieldContent];

        $expected = $expected ?? ['maxLength' => 'The provided value is invalid'];
        $this->_testDataValidation($table, $fieldName, $dataset, $expected, $options);
    }

    /**
     * Validate the minimum length of a field for a given table
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check the minimum length for.
     * @param int $minlength The minimum length of the field
     * @param array|null $expected The expected data validation errors.
     * @param ?array $options Additional options for newEntity.
     * @return void
     */
    protected function _testDataValidationMinLength(
        Table $table,
        string $fieldName,
        int $minlength,
        ?array $expected = null,
        ?array $options = []
    ): void {
        $tooShortFieldContent = str_repeat('A', $minlength - 1);
        $dataset = [$fieldName => $tooShortFieldContent];

        $expected ??= ['minlength' => 'The provided value is invalid'];
        $this->_testDataValidation($table, $fieldName, $dataset, $expected, $options);
    }

    /**
     * Validate the scalar data validation of a field for a given table
     *
     * @param Table $table The table to test.
     * @param string $fieldName The field to check the maximum length for.
     * @param ?array|null $expected The expected data validation errors (optional).
     * @param ?array $options Additional options for newEntity (optional).
     * @return void
     */
    protected function _testDataValidationScalar(
        Table $table,
        string $fieldName,
        ?array $expected = null,
        ?array $options = []
    ): void {
        $dataset = [$fieldName => []];

        $expected ??= [
            'scalar' => 'The provided value is invalid',
            // TODO maxLength will only be triggered when this rule is set on this field.
            'maxLength' => 'The provided value is invalid',
        ];
        $this->_testDataValidation($table, $fieldName, $dataset, $expected, $options);
    }

    /**
     * Validate the length between data validation of a field for a given table
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check the maximum length for.
     * @param int $minLength The minimum length of the field
     * @param int $maxlength The maximum length of the field
     * @param array|null $expected The expected data validation errors.
     * @param ?array $options Additional options for newEntity.
     * @return void
     */
    protected function _testDataValidationLengthBetween(
        Table $table,
        string $fieldName,
        int $minLength,
        int $maxlength,
        ?array $expected = null,
        ?array $options = []
    ): void {
        // Too short
        if ($minLength >= 1) {
            $tooShortFieldContent = str_repeat('A', $minLength - 1);
            $dataset = [$fieldName => $tooShortFieldContent];

            $expected ??= ['lengthBetween' => 'The provided value is invalid'];
            $this->_testDataValidation($table, $fieldName, $dataset, $expected, $options);
        }

        // Too long
        $tooLongFieldContent = str_repeat('A', $maxlength + 1);
        $dataset = [$fieldName => $tooLongFieldContent];

        $expected ??= ['lengthBetween' => 'The provided value is invalid'];
        $this->_testDataValidation($table, $fieldName, $dataset, $expected, $options);
    }

    /**
     * Validate that a given field is validated as a natural number (positive integers only)
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check for data validation errors.
     * @param array $additionalDataSet Additional data set to test.
     * @param array $options Additional options for newEntity.
     * @return void
     */
    protected function _testDataValidationNaturalNumber(
        Table $table,
        string $fieldName,
        array $additionalDataSet = [],
        array $options = []
    ): void {
        // Valid value
        $list = [1];
        $expected = [];
        $this->_testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);

        // Invalid values
        $list = [null, -1];
        $expected = ['naturalNumber' => 'The provided value is invalid'];
        $this->_testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);
    }

    /**
     * Validate that a given data set for a given table leads to the expected errors
     *
     * @param Table $table The table to test
     * @param array $dataSet The data set to test.
     * @param array $expected The expected errors.
     * @param array $options Additional options for newEntity.
     * @return void
     */
    protected function _testFullDataValidation(Table $table, array $dataSet, array $expected, array $options = [])
    {
        $entity = $table->newEntity($dataSet, $options);
        $errors = $entity->getErrors();

        static::assertEquals($expected, $errors);
    }
}
