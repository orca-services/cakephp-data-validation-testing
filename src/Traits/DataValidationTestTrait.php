<?php
declare(strict_types=1);

namespace DataValidationTesting\Traits;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosDate;
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
    protected function testDataValidationNotEmpty(
        Table $table,
        string $fieldName,
        array $additionalDataSet = [],
        array $options = []
    ): void {
        $list = [null, ''];

        $expected = ['_empty' => 'This field cannot be left empty'];
        $this->testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);
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
    protected function testDataValidationEmpty(
        Table $table,
        string $fieldName,
        array $additionalDataSet = [],
        array $options = []
    ): void {
        $list = [null, ''];

        $expected = [];
        $this->testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);
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
    protected function testDataValidationRequired(
        Table $table,
        string $fieldName,
        array $dataSet = [],
        array $options = []
    ): void {
        $expected = ['_required' => 'This field is required'];
        $this->testDataValidation($table, $fieldName, $dataSet, $expected, $options);
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
    protected function testDataValidationNotRequired(
        Table $table,
        string $fieldName,
        array $dataSet = [],
        array $options = []
    ): void {
        $this->testDataValidationNoErrors($table, $fieldName, $dataSet, $options);
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
    protected function testDataValidationBoolean(
        Table $table,
        string $fieldName,
        array $additionalDataSet = [],
        array $options = []
    ): void {
        // Valid values
        $list = [true, false, 1, 0];
        $expected = [];
        $this->testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);

        // Invalid values
        $list = ['Not a boolean', 123, []];
        $expected = ['boolean' => 'The provided value must be a boolean'];
        $this->testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);
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
    protected function testDataValidationURLWithProtocol(
        Table $table,
        string $fieldName,
        array $additionalDataSet = [],
        array $options = []
    ): void {
        // Valid values
        $list = ['https://valid.com', 'http://valid.com'];
        $expected = [];
        $this->testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);

        // Invalid values
        $list = ['no-protocol.com', 'htp://foo.com'];
        $expected = ['urlWithProtocol' => 'The provided value must be a URL with protocol'];
        $this->testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);
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
    protected function testDataValidationDateTime(
        Table $table,
        string $fieldName,
        array $additionalDataSet = [],
        array $options = []
    ): void {
        // Valid values
        $list = [
            '1900-01-01 00:00:00', // Far in the past
            '2022-10-12 11:50:32', // Around today
            '2099-12-31 23:59:59', // Far in the future
            new Chronos(),
            new FrozenTime(),
        ];
        $expected = [];
        $this->testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);

        // Invalid values
        $list = ['Not a date/time', '123'];
        $expected = ['dateTime' => 'The provided value must be a date and time of one of these formats: `ymd`'];
        $this->testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);
    }

    /**
     * Validate that a given field is validated as a date
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check for data validation errors.
     * @param array $additionalDataSet Additional data set to test.
     * @param array $options Additional options for newEntity.
     * @return void
     */
    protected function testDataValidationDate(
        Table $table,
        string $fieldName,
        array $additionalDataSet = [],
        array $options = []
    ): void {
        // Valid values
        $list = [
            '1900-01-01', // Far in the past
            '2022-10-12', // Around today
            '2099-12-31', // Far in the future
            new Chronos(),
            new ChronosDate(),
            new FrozenDate(),
            new FrozenTime(),
        ];
        $expected = [];
        $this->testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);

        // Invalid values
        $list = [
            'Not a date', // String
            '123', // Numeric
            '2022-10-12 11:50:32', // Date time
        ];
        $expected = ['date' => 'The provided value must be a date of one of these formats: `ymd`'];
        $this->testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);
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
    protected function testDataValidationInList(
        Table $table,
        array $list,
        string $fieldName,
        array $expected = [],
        array $additionalDataSet = [],
        array $options = []
    ): void {
        foreach ($list as $value) {
            $dataSet = array_merge($additionalDataSet, [$fieldName => $value]);
            $this->testDataValidation($table, $fieldName, $dataSet, $expected, $options);
        }
    }

    /**
     * Validate that a given data set for a given table does not lead to data validation errors for a given field
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check for data validation errors.
     * @param array $dataSet The data set to test.
     * @param array $options Additional options for newEntity.
     * @return void
     */
    protected function testDataValidationNoErrors(
        Table $table,
        string $fieldName,
        array $dataSet,
        array $options = []
    ): void {
        $expected = [];
        $this->testDataValidation($table, $fieldName, $dataSet, $expected, $options);
    }

    /**
     * Validate that a given data set for a given table does not lead to data validation errors on any field
     *
     * @param Table $table The table to test
     * @param array $dataSet The data set to test.
     * @param array $options Additional options for newEntity.
     * @return void
     */
    protected function testFullDataValidationNoErrors(Table $table, array $dataSet, array $options = []): void
    {
        $expected = [];
        $this->testFullDataValidation($table, $dataSet, $expected, $options);
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
    protected function testDataValidation(
        Table $table,
        string $fieldName,
        array $dataSet,
        array $expected,
        array $options = []
    ): void {
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
    protected function testDataRules(
        Table $table,
        string $fieldName,
        array $dataSet,
        array $expected,
        array $options = []
    ): void {
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
    protected function testRules(
        Table $table,
        string $fieldName,
        array $dataSet,
        array $expected,
        array $options = []
    ): void {
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
    protected function testDataRulesNoErrors(Table $table, string $fieldName, array $dataSet, array $options = []): void
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
     * @param int $maxLength The maximum length of the field
     * @param array|null $expected The expected data validation errors.
     * @param ?array $options Additional options for newEntity.
     * @return void
     */
    protected function testDataValidationMaxLength(
        Table $table,
        string $fieldName,
        int $maxLength,
        ?array $expected = null,
        ?array $options = []
    ): void {
        $tooLongFieldContent = str_repeat('A', $maxLength + 1);
        $dataset = [$fieldName => $tooLongFieldContent];

        $expected = $expected ?? ['maxLength' => 'The provided value must be at most `10` characters long'];
        $this->testDataValidation($table, $fieldName, $dataset, $expected, $options);
    }

    /**
     * Validate the minimum length of a field for a given table
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check the minimum length for.
     * @param int $minLength The minimum length of the field
     * @param array|null $expected The expected data validation errors.
     * @param ?array $options Additional options for newEntity.
     * @return void
     */
    protected function testDataValidationMinLength(
        Table $table,
        string $fieldName,
        int $minLength,
        ?array $expected = null,
        ?array $options = []
    ): void {
        $tooShortFieldContent = str_repeat('A', $minLength - 1);
        $dataset = [$fieldName => $tooShortFieldContent];

        $expected ??= ['minLength' => 'The provided value must be at least `5` characters long'];
        $this->testDataValidation($table, $fieldName, $dataset, $expected, $options);
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
    protected function testDataValidationScalar(
        Table $table,
        string $fieldName,
        ?array $expected = null,
        ?array $options = []
    ): void {
        $dataset = [$fieldName => []];

        $expected ??= [
            'scalar' => 'The provided value must be scalar',
            'maxLength' => 'The provided value must be at most `50` characters long',
        ];
        $this->testDataValidation($table, $fieldName, $dataset, $expected, $options);
    }

    /**
     * Validate the decimal data validation of a field for a given table
     *
     * @param Table $table The table to test.
     * @param string $fieldName The field to check for decimal data for.
     * @param ?array|null $expected The expected data validation errors (optional).
     * @param ?array $options Additional options for newEntity (optional).
     * @return void
     */
    protected function testDataValidationDecimal(
        Table $table,
        string $fieldName,
        ?array $expected = null,
        ?array $options = []
    ): void {
        $expected ??= ['decimal' => 'The provided value must be decimal with any number of decimal places, including none'];

        // Invalid values
        $this->testDataValidation($table, $fieldName, [$fieldName => []], $expected, $options);
        $this->testDataValidation($table, $fieldName, [$fieldName => 'string'], $expected, $options);
        $this->testDataValidation($table, $fieldName, [$fieldName => '1abc'], $expected, $options);
        $this->testDataValidation($table, $fieldName, [$fieldName => 'abc1'], $expected, $options);
        $this->testDataValidation($table, $fieldName, [$fieldName => 'ab0.099'], $expected, $options);
        $this->testDataValidation($table, $fieldName, [$fieldName => '0.099ba'], $expected, $options);
        $this->testDataValidation($table, $fieldName, [$fieldName => '0,099ba'], $expected, $options);
        $this->testDataValidation($table, $fieldName, [$fieldName => 'ab0,099'], $expected, $options);

        // Valid values
        $this->testDataValidation($table, $fieldName, [$fieldName => -99.0], [], $options);
        $this->testDataValidation($table, $fieldName, [$fieldName => 0.099], [], $options);
    }

    /**
     * Validate the integer data validation of a field for a given table
     *
     * @param Table $table The table to test.
     * @param string $fieldName The field to check for integer data for.
     * @param ?array|null $expected The expected data validation errors (optional).
     * @param ?array $options Additional options for newEntity (optional).
     * @return void
     */
    protected function testDataValidationInteger(
        Table $table,
        string $fieldName,
        ?array $expected = null,
        ?array $options = []
    ): void {
        $expected ??= ['integer' => 'The provided value must be an integer'];

        // Invalid values
        $this->testDataValidation($table, $fieldName, [$fieldName => 'string'], $expected, $options);
        $this->testDataValidation($table, $fieldName, [$fieldName => '1abc'], $expected, $options);
        $this->testDataValidation($table, $fieldName, [$fieldName => 'abc1'], $expected, $options);
        $this->testDataValidation($table, $fieldName, [$fieldName => 'ab0.099'], $expected, $options);
        $this->testDataValidation($table, $fieldName, [$fieldName => '0.099ba'], $expected, $options);
        $this->testDataValidation($table, $fieldName, [$fieldName => '0,099ba'], $expected, $options);
        $this->testDataValidation($table, $fieldName, [$fieldName => 'ab0,099'], $expected, $options);

        // Valid values
        $this->testDataValidation($table, $fieldName, [$fieldName => -99], [], $options);
        $this->testDataValidation($table, $fieldName, [$fieldName => 99], [], $options);
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
    protected function testDataValidationLengthBetween(
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

            $expected ??= ['lengthBetween' => 'The length of the provided value must be between `5` and `10`, inclusively'];
            $this->testDataValidation($table, $fieldName, $dataset, $expected, $options);
        }

        // Too long
        $tooLongFieldContent = str_repeat('A', $maxlength + 1);
        $dataset = [$fieldName => $tooLongFieldContent];

        $expected ??= ['lengthBetween' => 'The length of the provided value must be between `5` and `10`, inclusively'];
        $this->testDataValidation($table, $fieldName, $dataset, $expected, $options);
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
    protected function testDataValidationNaturalNumber(
        Table $table,
        string $fieldName,
        array $additionalDataSet = [],
        array $options = []
    ): void {
        // Valid value
        $list = [1];
        $expected = [];
        $this->testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);

        // Invalid values
        $list = [0, -1];
        $expected = ['naturalNumber' => 'The provided value must be a natural number'];
        $this->testDataValidationInList($table, $list, $fieldName, $expected, $additionalDataSet, $options);
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
    protected function testFullDataValidation(Table $table, array $dataSet, array $expected, array $options = []): void
    {
        $entity = $table->newEntity($dataSet, $options);
        $errors = $entity->getErrors();

        static::assertEquals($expected, $errors);
    }

    /**
     * Validate the present foreign key to another table
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check the foreign key for.
     * @param int|null $notExistingForeignKey Not existing foreign key. Defaults to 999999.
     * @return void
     */
    protected function testDataValidationForeignKey(
        Table $table,
        string $fieldName,
        ?int $notExistingForeignKey = 999999
    ): void {
        $entity = $table->newEmptyEntity();
        $table->patchEntity($entity, [$fieldName => $notExistingForeignKey]);

        $result = $table->checkRules($entity);
        static::assertFalse($result);
        static::assertNotNull($entity->getErrors()[$fieldName]);

        $expected = ['_existsIn' => 'This value does not exist'];

        static::assertSame($expected, $entity->getErrors()[$fieldName]);
    }

    /**
     * Validate the uniq value of the given field
     *
     * @param Table $table The table to test
     * @param string $fieldName The field to check the foreign key for.
     * @param mixed $fieldValue The field value.
     * @param array $additionalProperties Other required properties for the new entity.
     * @return void
     */
    protected function testDataValidationIsUnique(
        Table $table,
        string $fieldName,
        $fieldValue,
        array $additionalProperties = []
    ): void {
        $prevEntity = $table->newEmptyEntity();
        $table->patchEntity($prevEntity, array_merge($additionalProperties, [$fieldName => $fieldValue]));
        $table->saveOrFail($prevEntity);

        $entity = $table->newEmptyEntity();
        $table->patchEntity($entity, array_merge($additionalProperties, [$fieldName => $fieldValue]));

        $result = $table->checkRules($entity);
        static::assertFalse($result);
        static::assertNotNull($entity->getErrors()[$fieldName]);

        $expected = ['_isUnique' => 'This value is already in use'];

        static::assertSame($expected, $entity->getErrors()[$fieldName]);
    }
}
