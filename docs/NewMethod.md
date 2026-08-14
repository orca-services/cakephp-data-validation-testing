# How to Add a new Data Validation Trait Method

1. Add database field in [tests/bootstrap.php](../tests/bootstrap.php)
2. Add validation rule to new field in [tests/TestApp/Model/Table/ValidationTestTable.php](../tests/TestApp/Model/Table/ValidationTestTable.php)
3. Implement new data validation trait method in [src/Traits/DataValidationTestTrait.php](../src/Traits/DataValidationTestTrait.php)
4. Cover new data validation trait method with test in [tests/TestCase/Traits/DataValidationTestTraitTest.php](../tests/TestCase/Traits/DataValidationTestTraitTest.php)
5. Document the new data validation trait method in [docs/Usage.md](Usage.md)
