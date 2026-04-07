# Change Log

All notable changes to this project are documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased](https://github.com/orca-services/cakephp-data-validation-testing/compare/0.1.1...master)

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
- Drop support for CakePHP 2.x
- Update the minimum required PHP Version to PHP 7.4

### Fixed

### Dependencies
- cakephp/cakephp installed in version 4.6.3
- cakephp/cakephp-codesniffer installed in version 4.7.1
- phpunit/phpunit updated from 3.7.38 to 8.5.52 major

## [0.1.1](https://github.com/orca-services/cakephp-data-validation-testing/releases/tag/0.1.1)

### Added

### Changed

### Fixed

- textFillUp function

## [0.1.0](https://github.com/orca-services/cakephp-data-validation-testing/releases/tag/0.1.0) - 2017-02-20

### Added

- Initial functionality
