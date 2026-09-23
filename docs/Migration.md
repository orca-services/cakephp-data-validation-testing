# Upgrading CakePHP Data Validation Testing to 3.x

This major version bundles two breaking changes:

1. **Method renames** — [PR #45](https://github.com/orca-services/cakephp-data-validation-testing/pull/45) (closes [#44](https://github.com/orca-services/cakephp-data-validation-testing/issues/44))
2. **Rule-dedicated methods now check only their own rule** — [PR #40](https://github.com/orca-services/cakephp-data-validation-testing/pull/40) (closes [#38](https://github.com/orca-services/cakephp-data-validation-testing/issues/38))

---

## 1. Method renames

All `test`/`testData`-prefixed methods on `DataValidationTestTrait` are now prefixed with `assert` instead.
This avoids PHPUnit mistaking them for actual test methods. **No logic changed** only the method names.

### Rename table

| Old | New |
|---|---|
| `testDataValidationNotEmpty()` | `assertValidationNotEmpty()` |
| `testDataValidationEmpty()` | `assertValidationEmpty()` |
| `testDataValidationRequired()` | `assertValidationRequired()` |
| `testDataValidationNotRequired()` | `assertValidationNotRequired()` |
| `testDataValidationBoolean()` | `assertValidationBoolean()` |
| `testDataValidationURLWithProtocol()` | `assertValidationURLWithProtocol()` |
| `testDataValidationDateTime()` | `assertValidationDateTime()` |
| `testDataValidationDate()` | `assertValidationDate()` |
| `testDataValidationInList()` | `assertValidationInList()` |
| `testDataValidationNoErrors()` | `assertValidationNoErrors()` |
| `testFullDataValidationNoErrors()` | `assertFullDataValidationNoErrors()` |
| `testDataValidation()` | `assertValidation()` |
| `testDataValidationContains()` | `assertValidationContains()` |
| `testDataValidationNotContains()` | `assertValidationNotContains()` |
| `assertDataValidationErrorsContain()` | `assertValidationErrorsContain()` |
| `testDataValidationListContains()` | `assertValidationListContains()` |
| `testDataValidationListNotContains()` | `assertValidationListNotContains()` |
| `testDataRules()` | `assertDataRules()` |
| `testRules()` | `assertRules()` |
| `testDataRulesNoErrors()` | `assertRulesNoErrors()` |
| `testDataValidationMaxLength()` | `assertValidationMaxLength()` |
| `testDataValidationMinLength()` | `assertValidationMinLength()` |
| `testDataValidationScalar()` | `assertValidationScalar()` |
| `testDataValidationDecimal()` | `assertValidationDecimal()` |
| `testDataValidationInteger()` | `assertValidationInteger()` |
| `testDataValidationNonNegativeInteger()` | `assertValidationNonNegativeInteger()` |
| `testDataValidationGreaterThanOrEqual()` | `assertValidationGreaterThanOrEqual()` |
| `testDataValidationEmail()` | `assertValidationEmail()` |
| `testDataValidationUuid()` | `assertValidationUuid()` |
| `testDataValidationLengthBetween()` | `assertValidationLengthBetween()` |
| `testDataValidationRange()` | `assertValidationRange()` |
| `testDataValidationNaturalNumber()` | `assertValidationNaturalNumber()` |
| `testFullDataValidation()` | `assertFullDataValidation()` |
| `testDataValidationForeignKey()` | `assertValidationForeignKey()` |
| `testDataValidationIsUnique()` | `assertValidationIsUnique()` |

---

## 2. Rule-dedicated methods now check only their own rule

Previously, methods like `assertValidationBoolean()`, `assertValidationEmail()`, `assertValidationInteger()`, etc. compared the **entire** error array for a field against an expected array (or `[]` for valid values). If a field had multiple validation errors, this could hide unrelated errors or cause false failures.

Now these methods assert **only their own rule key** (present or absent), ignoring any other errors on the same field.
