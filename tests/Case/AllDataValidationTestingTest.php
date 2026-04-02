<?php
/**
 * All data validation testing plugin tests
 */
class AllDataValidationTestingTest extends CakeTestCase {

	/**
	 * Suite define the tests for this plugin
	 *
	 * @return CakeTestSuite The test suite to execute.
	 */
	public static function suite() {
		$suite = new CakeTestSuite('All DataValidationTesting test');

		$path = CakePlugin::path('DataValidationTesting') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}

}
