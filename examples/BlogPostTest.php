<?php

App::uses('TestDataValidation', 'DataValidationTesting.Lib');

/**
 * BlogPost Test Case
 *
 * @coversDefaultClass BlogPost
 * @property BlogPost $_model
 */
class BlogPostTest extends CakeTestCase {

	/**
	 * The data validation testing class.
	 *
	 * @var null|TestDataValidation
	 */
	protected $_testDataValidation = null;

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array(
		'app.blogPost',
	);

	/**
	 * Set up model and data validation testing object
	 *
	 * @return void
	 */
	public function setUp() {
		$this->_model = ClassRegistry::init('BlogPost');
		parent::setUp();
		$this->_testDataValidation = new TestDataValidation($this, $this->_model);
	}

	/**
	 * Tests the data validation for the mandatory fields (if any)
	 *
	 * @param array $expected The array of expected validation errors.
	 * @return void
	 * @coversNothing
	 */
	public function testRequiredFieldsDataValidation($expected = array()) {
		parent::testRequiredFieldsDataValidation($expected);
	}

	/**
	 * Tests the data validation for the 'title' field
	 *
	 * @coversNothing
	 * @return void
	 */
	public function testTitleDataValidation() {
		$minLength = 4;
		$maxLength = 255;

		// Test the 'notempty' rule
		$this->_testDataValidation->testNotEmptyDataValidation(
			'title', 'Title must not be empty.', $minLength
		);

		// Test the 'minlength' rule
		$this->_testDataValidation->testMinLengthDataValidation(
			'title', 'Title must be at least 4 characters long.', $minLength
		);

		// Test the 'maxlength' rule
		$this->_testDataValidation->testMaxLengthDataValidation(
			'title', 'Title must be no longer than 255 characters.', $maxLength
		);
	}

	/**
	 * Test the data validation for the 'is_active' field
	 *
	 * @coversNothing
	 * @return void
	 */
	public function testIsTrueDataValidation() {
		// Test the 'boolean' rule
		$this->_testDataValidation->testBooleanDataValidation(
			'is_active', 'Is Active must be a boolean.'
		);
	}

	/**
	 * Test the data validation for the 'user_id' field
	 *
	 * The validForeignKey data validation rule is provided
	 * by the ValidForeignKey behavior plugin.
	 *
	 * @coversNothing
	 * @return void
	 */
	public function testItemIdDataValidation() {
		// Test the 'validForeignKey' rule
		$valid = array(1, 2, 3);
		$this->_testDataValidation->testValidForeignKeyDataValidation(
			'user_id', 'User ID must exist.', $valid
		);
	}
}
