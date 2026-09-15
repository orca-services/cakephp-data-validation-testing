# Change Log

All notable changes to this project are documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased](https://github.com/orca-services/cakephp-data-validation-testing)

### Added
- `testDataValidationNotContains()` to assert that specific validation rules are absent, ignoring others on the same field.
- `testDataValidationInListContains()` and `testDataValidationInListNotContains()` list helpers.
- Optional custom `$expected` parameter for `testDataValidationForeignKey()` and `testDataValidationIsUnique()`.

### Changed
- **BREAKING CHANGE:** All type-specific/rule-dedicated methods now assert only their own validation rule for the field, ignoring others.

### Fixed
- Ignore dataValidation in `testDataValidationIsUnique()` to correctly assert build rules.

### Dependencies

## [2.4.0](https://github.com/orca-services/cakephp-data-validation-testing/releases/tag/2.4.0) - 2026-08-25

### Added
- Add DataValidationTestingPlugin class

## [2.3.0](https://github.com/orca-services/cakephp-data-validation-testing/releases/tag/2.3.0) - 2026-08-24

### Added
- `testDataValidationContains()` to assert that specific validation errors are present, ignoring others on the same field.

### Changed
- `testDataValidationScalar()` now checks only the `scalar` rule by default.

### Fixed
- Expected length validation messages now use the given parameters instead of hardcoded values.

## [2.2.0](https://github.com/orca-services/cakephp-data-validation-testing/releases/tag/2.2.0) - 2026-08-19

### Added

- Add how-to guide for adding new data validation trait methods
- Add validation trait method for email addresses
- Add validation trait method for UUIDs

## [2.1.1](https://github.com/orca-services/cakephp-data-validation-testing/releases/tag/2.1.1) - 2026-06-25

### Added

- Add a new test method to assert a field enforces the greater than or equal rule [#13](https://github.com/orca-services/cakephp-data-validation-testing/issues/13)
- Add FQSEN links to the corresponding data validation rule [#14](https://github.com/orca-services/cakephp-data-validation-testing/issues/14)

### Changed

- Exclude repository-only files from archives to reduce Composer distribution package size [#12](https://github.com/orca-services/cakephp-data-validation-testing/issues/12)

## [2.1.0](https://github.com/orca-services/cakephp-data-validation-testing/releases/tag/2.1.0) - 2026-04-14

### Added

- Add a new test method to assert a field accepts only non-negative integers
- Add Test suite run configuration for PhpStorm

## [2.0.0](https://github.com/orca-services/cakephp-data-validation-testing/releases/tag/2.0.0) - 2026-04-14

### Changed

- Bump support for CakePHP to 5.x [#7](https://github.com/orca-services/cakephp-data-validation-testing/issues/7)
- Update the minimum required PHP Version to PHP
  8.2 [#7](https://github.com/orca-services/cakephp-data-validation-testing/issues/7)

### Dependencies

- cakephp/cakephp updated from 4.6.3 to 5.3.3
  major [#7](https://github.com/orca-services/cakephp-data-validation-testing/issues/7)
- cakephp/cakephp-codesniffer updated from 4.7.1 to 5.3.0
  major [#7](https://github.com/orca-services/cakephp-data-validation-testing/issues/7)
- phpunit/phpunit updated from 8.5.52 to 10.5.63
  major [#7](https://github.com/orca-services/cakephp-data-validation-testing/issues/7)

## [1.1.0](https://github.com/orca-services/cakephp-data-validation-testing/releases/tag/1.1.0) - 2026-04-13

### Added

- Add the following new test methods:
    - Assert a field accepts only valid date values
    - Assert a field accepts only decimal values
    - Assert a field accepts only integer values
    - Assert a field enforces uniqueness across record
    - Assert a field's foreign key must reference an existing record

## [1.0.0](https://github.com/orca-services/cakephp-data-validation-testing/releases/tag/1.0.0) - 2026-04-08

### Added

- Add support for CakePHP 4.x
- Add the following new test methods:
    - Assert a field accepts empty values without errors
    - Assert a field is not required
    - Validate URLs with http/https protocol
    - Assert a dataset produces no errors for a given field
    - Assert a full dataset against expected errors across all fields
    - Assert a full dataset produces no errors across all fields
    - Assert application-level rule errors after a failed save
    - Assert no application-level rule errors occur after a successful save
    - Assert a field rejects non-scalar values such as arrays
    - Assert a field enforces a minimum and maximum length range

### Changed

- Drop support for CakePHP 2.x & skip support for CakePHP 3.x
- Update the minimum required PHP Version to PHP 7.4

### Dependencies

- cakephp/cakephp installed in version 4.6.3
- cakephp/cakephp-codesniffer installed in version 4.7.1
- phpunit/phpunit updated from 3.7.38 to 8.5.52 major

## [0.1.1](https://github.com/orca-services/cakephp-data-validation-testing/releases/tag/0.1.1) - 2017-02-21

### Fixed

- textFillUp function

## [0.1.0](https://github.com/orca-services/cakephp-data-validation-testing/releases/tag/0.1.0) - 2017-02-20

### Added

- Initial functionality
