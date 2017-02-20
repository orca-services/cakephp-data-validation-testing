<?php
/**
 * DataValidation testing
 *
 * Licensed under The MIT License.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Marc Würth
 * @author Marc Würth <ravage@bluewin.ch>
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link https://github.com/orca-services/cakephp-data-validation-testing
 */

/**
 * Test data validation rules.
 * @todo Check http://book.cakephp.org/2.0/en/models/data-validation.html for more data validation rules to check against
 */
class TestDataValidation {

	/**
	 * The calling test case.
	 *
	 * @var null|CakeTestCase
	 */
	protected $_testClass = null;

	/**
	 * The model under test.
	 *
	 * @var null|Model
	 */
	protected $_model = null;

	/**
	 * Set the test class and model under test.
	 *
	 * @param CakeTestCase &$testClass The calling test class.
	 * @param Model &$model The model under test.
	 */
	public function __construct(CakeTestCase &$testClass, Model &$model) {
		$this->_testClass = $testClass;
		$this->_model = $model;
	}

	/**
	 * Tests the data validation for the mandatory fields
	 *
	 * @param array $expected The array of expected validation errors.
	 * @return void
	 */
	public function testRequiredFieldsDataValidation($expected = array()) {
		$this->_model->create();
		$this->_model->invalidFields();

		$this->_testClass->assertEquals($expected, $this->_model->validationErrors);
	}

	/**
	 * Test a field with the 'notempty' data validation rule.
	 *
	 * Tests against one invalid input.
	 * Allows to specify an array of valid inputs.
	 *
	 * @param string $field The name of the field to test.
	 * @param string $expectedMessage The error message that is expected.
	 * @param int $validLength A string length that should be valid.
	 * @param array|false $valid An array of valid inputs to test instead. Set to false to disable.
	 * @param array $additionalData An array of additional data (e.g. mandatory fields).
	 * @return void
	 * @see TestDataValidation::testDataValidation() Does the actual processing.
	 */
	public function testNotEmptyDataValidation($field, $expectedMessage, $validLength, $valid = array(), $additionalData = array()) {
		$testName = 'notempty';

		// The invalid inputs to test
		$invalid = array(
			''
		);

		if (empty($valid)) {
			$valid[] = $this->_textFillUp($validLength);
		}

		$this->testDataValidation($testName, $field, $expectedMessage, $valid, $invalid, $additionalData);
	}

	/**
	 * Test a field with the 'minlength' data validation rule.
	 *
	 * Tests against one invalid input.
	 * Allows to specify an array of valid inputs.
	 *
	 * @param string $field The name of the field to test.
	 * @param string $expectedMessage The error message that is expected.
	 * @param int $minLength The minimum string length that should be valid.
	 * @param array|false $valid An array of valid inputs to test instead. Set to false to disable.
	 * @param array $additionalData An array of additional data (e.g. mandatory fields).
	 * @return void
	 * @see TestDataValidation::testDataValidation() Does the actual processing.
	 */
	public function testMinLengthDataValidation($field, $expectedMessage, $minLength,
												$valid = array(), $additionalData = array()) {
		$testName = 'minlength';

		// The invalid inputs to test
		$invalid = array(
			$this->_textFillUp($minLength - 1)
		);

		if (empty($valid)) {
			$valid[] = $this->_textFillUp($minLength);
		}

		$this->testDataValidation($testName, $field, $expectedMessage, $valid, $invalid, $additionalData);
	}

	/**
	 * Test a field with the 'maxlength' data validation rule.
	 *
	 * Tests against one invalid input.
	 * Allows to specify an array of valid inputs.
	 *
	 * @param string $field The name of the field to test.
	 * @param string $expectedMessage The error message that is expected.
	 * @param int $maxLength The maximum string length that should be valid.
	 * @param array|false $valid An array of valid inputs to test instead. Set to false to disable.
	 * @param array $additionalData An array of additional data (e.g. mandatory fields).
	 * @return void
	 * @see TestDataValidation::testDataValidation() Does the actual processing.
	 */
	public function testMaxLengthDataValidation($field, $expectedMessage, $maxLength,
												$valid = array(), $additionalData = array()) {
		$testName = 'maxlength';

		// The invalid inputs to test
		$invalid = array(
			$this->_textFillUp($maxLength + 1)
		);

		if (empty($valid)) {
			$valid[] = $this->_textFillUp($maxLength);
		}

		$this->testDataValidation($testName, $field, $expectedMessage, $valid, $invalid, $additionalData);
	}

	/**
	 * Test a field with the 'boolean' data validation rule.
	 *
	 * Tests against various invalid inputs.
	 * Allows to specify an array of valid inputs.
	 *
	 * @param string $field The name of the field to test.
	 * @param string $expectedMessage The error message that is expected.
	 * @param array|false $valid An array of valid inputs to test instead. Set to false to disable.
	 * @param array $additionalData An array of additional data (e.g. mandatory fields).
	 * @return void
	 * @see TestDataValidation::testDataValidation() Does the actual processing.
	 */
	public function testBooleanDataValidation($field, $expectedMessage, $valid = array(true, false, 1, 0), $additionalData = array()) {
		$testName = 'boolean';

		// The invalid inputs to test
		$invalid = array(
			'',
			'abc',
			'true',
			'false',
		);

		$this->testDataValidation($testName, $field, $expectedMessage, $valid, $invalid, $additionalData);
	}

	/**
	 * Test a field with the 'numeric' data validation rule.
	 *
	 * Tests against various invalid inputs.
	 * Allows to specify an array of valid inputs.
	 *
	 * @param string $field The name of the field to test.
	 * @param string $expectedMessage The error message that is expected.
	 * @param array|false $valid An array of valid inputs to test instead. Set to false to disable.
	 * @param array $additionalData An array of additional data (e.g. mandatory fields).
	 * @return void
	 * @see TestDataValidation::testDataValidation() Does the actual processing.
	 */
	public function testNumericDataValidation($field, $expectedMessage,
													$valid = array(0, 1, 123456789, -1, -123456789),
													$additionalData = array()) {
		$testName = 'numeric';

		// The invalid inputs to test
		$invalid = array(
			null,
			0.0,
			'abc123'
		);

		$this->testDataValidation($testName, $field, $expectedMessage, $valid, $invalid, $additionalData);
	}

	/**
	 * Test a field with the 'naturalnumber' data validation rule.
	 *
	 * Tests against various invalid inputs.
	 * Allows to specify an array of valid inputs.
	 *
	 * @param string $field The name of the field to test.
	 * @param string $expectedMessage The error message that is expected.
	 * @param array|false $valid An array of valid inputs to test instead. Set to false to disable.
	 * @param array $additionalData An array of additional data (e.g. mandatory fields).
	 * @return void
	 * @see TestDataValidation::testDataValidation() Does the actual processing.
	 */
	public function testNaturalNumberDataValidation($field, $expectedMessage,
													$valid = array(1, 99, 123456789), $additionalData = array()) {
		$testName = 'naturalnumber';

		// The invalid inputs to test
		$invalid = array(
			null,
			0,
			'abc123',
			-1
		);

		$this->testDataValidation($testName, $field, $expectedMessage, $valid, $invalid, $additionalData);
	}

	/**
	 * Test a field with the 'range' data validation rule.
	 *
	 * Tests against various invalid inputs.
	 * Allows to specify an array of valid inputs.
	 *
	 * @param string $field The name of the field to test.
	 * @param string $expectedMessage The error message that is expected.
	 * @param int $minRange The minimum value of the range.
	 * @param int $maxRange The maximum value of the range.
	 * @param array|false $invalid An array of invalid inputs to test instead.
	 * @param array $additionalData An array of additional data (e.g. mandatory fields).
	 * @return void
	 * @see TestDataValidation::testDataValidation() Does the actual processing.
	 */
	public function testRangeDataValidation($field, $expectedMessage, $minRange, $maxRange,
												$invalid, $additionalData = array()) {
		$testName = 'range';

		$valid = array(
			$minRange,
			$maxRange,
		);

		$this->testDataValidation($testName, $field, $expectedMessage, $valid, $invalid, $additionalData);
	}

	/**
	 * Test a field with the 'datetime' data validation rule.
	 *
	 * Tests against various invalid inputs.
	 * Allows to specify an array of valid inputs.
	 *
	 * @param string $field The name of the field to test.
	 * @param string $expectedMessage The error message that is expected.
	 * @param array|false $valid An array of valid inputs to test instead. Set to false to disable.
	 * @param array $additionalData An array of additional data (e.g. mandatory fields).
	 * @return void
	 * @see TestDataValidation::testDataValidation() Does the actual processing.
	 */
	public function testDateTimeDataValidation($field, $expectedMessage,
													$valid = array('2014-02-28 12:24:35'), $additionalData = array()) {
		$testName = 'datetime';

		// The invalid inputs to test
		$invalid = array(
			null,
			0,
			-1,
			'',
			'abc',
			'1234',
			'1. January 2014',
		);

		$this->testDataValidation($testName, $field, $expectedMessage, $valid, $invalid, $additionalData);
	}

	/**
	 * Test a field with the 'ip' data validation rule.
	 *
	 * Tests against various invalid inputs.
	 * Allows to specify an array of valid inputs.
	 *
	 * @param string $field The name of the field to test.
	 * @param string $expectedMessage The error message that is expected.
	 * @param array|false $valid An array of valid inputs to test instead. Set to false to disable.
	 * @param array $additionalData An array of additional data (e.g. mandatory fields).
	 * @return void
	 * @see TestDataValidation::testDataValidation() Does the actual processing.
	 */
	public function testIpDataValidation($field, $expectedMessage, $valid = array(), $additionalData = array()) {
		$testName = 'ip';

		if (empty($valid)) {
			$valid = array(
				'0.0.0.0',
				'1.0.0.0',
				'10.255.0.255',
				'126.255.255.255',
				'127.0.0.0',
				'192.168.1.5',
				'212.0.177.0',
				'255.255.255.255'
			);
		}

		// The invalid inputs to test
		$invalid = array(
			null,
			0,
			-1,
			'',
			'abc',
			'1234',
			123456789,
			'192.0.0.x',
			'192.168.1.',
			'0.0.0.0.0',
			'192.168.1.0.1',
			'255.255.255.256',
		);

		$this->testDataValidation($testName, $field, $expectedMessage, $valid, $invalid, $additionalData);
	}

	/**
	 * Test a field with the 'validForeignKey' data validation rule.
	 *
	 * The validForeignKey data validation rule is provided
	 * by the ValidForeignKey behavior plugin.
	 *
	 * Tests against various invalid inputs.
	 * Allows to specify an array of valid inputs.
	 *
	 * @param string $field The name of the field to test.
	 * @param string $expectedMessage The error message that is expected.
	 * @param array|false $valid An array of valid inputs to test instead. Set to false to disable.
	 * @param array $additionalData An array of additional data (e.g. mandatory fields).
	 * @return void
	 * @see TestDataValidation::testDataValidation() Does the actual processing.
	 */
	public function testValidForeignKeyDataValidation($field, $expectedMessage, $valid = array(), $additionalData = array()) {
		$testName = 'validForeignKey';

		// The invalid inputs to test
		$invalid = array(
			null, // Null
			'', // Empty
			'abc123', // Alphanumeric
			0, // Zero
			(-1), // Negative input
			999999, // non-existent key
		);

		$db = ConnectionManager::getDataSource('test');
		if ($db instanceof Sqlserver) {
			if (($key = array_search('abc123', $invalid)) !== false) {
				unset($invalid[$key]);
			}
		}

		$this->testDataValidation($testName, $field, $expectedMessage, $valid, $invalid, $additionalData);
	}

	/**
	 * Test a field's data validation rule against invalid and valid inputs.
	 *
	 * Is used by the other methods of this class to actually process
	 * the invalid an valid inputs.
	 *
	 * @param string $testName A name for the test to be executed, used in the debug message.
	 * @param string $field The name of the field to test.
	 * @param string $expectedMessage The error message that is expected.
	 * @param array|false $valid An array of valid inputs to test instead. Set to false to disable.
	 * @param array $invalid An array of invalid inputs to test.
	 * @param array $additionalData An array of additional data (e.g. mandatory fields).
	 * @return void
	 */
	public function testDataValidation($testName, $field, $expectedMessage, $valid = array(), $invalid = array(), $additionalData = array()) {
		// Remove intersecting valid inputs from invalid array
		foreach ($invalid as $invalidKey => $invalidValue) {
			if (in_array($invalidValue, $valid, true)) {
				unset($invalid[$invalidKey]);
			}
		}

		$expected = array(
			$field => array(
				$expectedMessage
			)
		);

		// Test invalid inputs
		foreach ($invalid as $invalidInput) {
			$this->_model->create(
				array_merge(
					$additionalData,
					array($field => $invalidInput)
				)
			);
			$this->_model->invalidFields();
			$this->_testClass->assertEquals($expected, $this->_model->validationErrors,
				sprintf('The "%s" check failed to test the seemingly INVALID input' . "\r" .
					'%s (%s)' . "\r" .
					'on field "%s.%s".',
					$testName,
					var_export($invalidInput, true),
					gettype($invalidInput),
					$this->_model->name,
					$field
				)
			);
		}

		// Test valid inputs
		if ($valid === false) {
			$valid = array();
		}

		foreach ($valid as $validInput) {
			$this->_model->create(array($field => $validInput));
			$this->_model->invalidFields();
			$this->_testClass->assertEquals(array(), $this->_model->validationErrors,
				sprintf('The "%s" check failed to test the seemingly VALID input' . "\r" .
					'%s (%s)' . "\r" .
					'on field "%s.%s".',
					$testName,
					var_export($validInput, true),
					gettype($validInput),
					$this->_model->name,
					$field)
			);
		}
	}

	/**
	 * Test a model's data validation rules against a single data set.
	 *
	 * The data set can be valid or invalid. Depending on what message(s) we expect.
	 *
	 * @param string $testName A name for the test to be executed, used in the debug message.
	 * @param array $dataSet The data set to test.
	 * @param string $expectedMessageArray The error message array that is expected.
	 * @return void
	 */
	public function testDataSetDataValidation($testName, $dataSet, $expectedMessageArray) {
		$this->_model->create($dataSet);
		$this->_model->invalidFields();

		$this->_testClass->assertEquals($expectedMessageArray, $this->_model->validationErrors,
			sprintf('The "%s" check failed to test the data set' . "\r" .
				'%s' . "\r" .
				'on model "%s".',
				$testName,
				var_export($dataSet, true),
				$this->_model->name
			)
		);
	}

	/**
	 * Returns a string filled up with characters
	 *
	 * @param int $length The length of the string.
	 * @return string A $length long string filled up with the supplied characters from $characterList.
	 */
	protected static function textFillUp($length) {
		$filled = '';
		$characterList = '012345679';
		$charListLength = strlen($characterList);
		$charPos = 0;

		for ($i = 0; $i < $length; $i++) {
			if ($charPos >= $charListLength) {
				$charPos = 0;
			}
			$filled = $filled . substr($characterList, $charPos, 1);

			$charPos++;
		}
		return $filled;
	}

}
