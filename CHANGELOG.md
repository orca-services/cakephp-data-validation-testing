# Change Log

All notable changes to this project are documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased](https://github.com/orca-services/cakephp-data-validation-testing)

### Added
- Add the following new test methods:
  - Assert a field accepts only valid date values
  - Assert a field accepts only decimal values
  - Assert a field accepts only integer values
  - Assert a field enforces uniqueness across record
  - Assert a field's foreign key must reference an existing record

### Changed

### Fixed

### Dependencies

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
