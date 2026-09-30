# Upgrading CakePHP Data Validation Testing to 3.x

This major version bundles two breaking changes:

1. **Method renames** — [PR #45](https://github.com/orca-services/cakephp-data-validation-testing/pull/45) (closes [#44](https://github.com/orca-services/cakephp-data-validation-testing/issues/44))
2. **Rule-dedicated methods now check only their own rule** — [PR #40](https://github.com/orca-services/cakephp-data-validation-testing/pull/40) (closes [#38](https://github.com/orca-services/cakephp-data-validation-testing/issues/38))

---

## Automated migration

A migration script automates most of the steps below. Run it from the root of your application,
after installing 3.x (or before, if you let the script update your `composer.json`):

```bash
php vendor/orca-services/cakephp-data-validation-testing/migrate.php
```

The script interactively asks for:

- The directories to migrate (comma separated, default: `tests`)
- The path to your `composer.json` (default: `composer.json`, enter `-` to skip updating it)
- The new version constraint for the package (default: `^3.0`)

What it does:

1. Renames all calls of the old method names to the new ones (see [Method renames](#1-method-renames)).
   Only method calls and references preceded by `->` or `::` are replaced,
   so your own test methods with similar names are left untouched.
2. Replaces calls of the removed `testRules()` with `assertRules()` and lists them for manual review
   (see [`testRules()` removal](#testrules-removal)).
3. Lists all calls of rule-dedicated methods for manual review
   (see [Rule-dedicated methods now check only their own rule](#2-rule-dedicated-methods-now-check-only-their-own-rule)).
4. Lists old method names it did not replace, e.g. in strings, callables or own methods with the same name.
5. Optionally updates the version constraint of the package in your `composer.json`.

The script modifies your files in place, so make sure your working tree is clean (e.g. committed in Git) before running it.
Afterward:

- Run `composer update orca-services/cakephp-data-validation-testing --with-dependencies`, if the script updated your `composer.json`.
- Review the diff (e.g. `git diff`) and the listed findings.
- Run your test suite and fix any failing tests.

---

## 1. Method renames

All `test`/`testData`-prefixed methods on `DataValidationTestTrait` are now prefixed with `assert` instead.
This avoids PHPUnit mistaking them for actual test methods. **No logic changed** only the method names.

### Rename table

| Old                                      | New                                    |
|------------------------------------------|----------------------------------------|
| `testDataValidationNotEmpty()`           | `assertValidationNotEmpty()`           |
| `testDataValidationEmpty()`              | `assertValidationEmpty()`              |
| `testDataValidationRequired()`           | `assertValidationRequired()`           |
| `testDataValidationNotRequired()`        | `assertValidationNotRequired()`        |
| `testDataValidationBoolean()`            | `assertValidationBoolean()`            |
| `testDataValidationURLWithProtocol()`    | `assertValidationURLWithProtocol()`    |
| `testDataValidationDateTime()`           | `assertValidationDateTime()`           |
| `testDataValidationDate()`               | `assertValidationDate()`               |
| `testDataValidationInList()`             | `assertValidationInList()`             |
| `testDataValidation()`                   | `assertValidation()`                   |
| `testDataValidationNoErrors()`           | `assertValidationNoErrors()`           |
| `testFullDataValidation()`               | `assertValidationTableErrors()`        |
| `testFullDataValidationNoErrors()`       | `assertValidationTableNoErrors()`      |
| `testDataValidationContains()`           | `assertValidationContains()`           |
| `testDataValidationNotContains()`        | `assertValidationNotContains()`        |
| `assertDataValidationErrorsContain()`    | `assertValidationErrorsContain()`      |
| `testDataValidationListContains()`       | `assertValidationListContains()`       |
| `testDataValidationListNotContains()`    | `assertValidationListNotContains()`    |
| `testDataRules()`                        | `assertRules()`                        |
| `testRules()`                            | Removed. Use `assertRules()` instead    |
| `testDataRulesNoErrors()`                | `assertRulesNoErrors()`                |
| `testDataValidationMaxLength()`          | `assertValidationMaxLength()`          |
| `testDataValidationMinLength()`          | `assertValidationMinLength()`          |
| `testDataValidationScalar()`             | `assertValidationScalar()`             |
| `testDataValidationDecimal()`            | `assertValidationDecimal()`            |
| `testDataValidationInteger()`            | `assertValidationInteger()`            |
| `testDataValidationNonNegativeInteger()` | `assertValidationNonNegativeInteger()` |
| `testDataValidationGreaterThanOrEqual()` | `assertValidationGreaterThanOrEqual()` |
| `testDataValidationEmail()`              | `assertValidationEmail()`              |
| `testDataValidationUuid()`               | `assertValidationUuid()`               |
| `testDataValidationLengthBetween()`      | `assertValidationLengthBetween()`      |
| `testDataValidationRange()`              | `assertValidationRange()`              |
| `testDataValidationNaturalNumber()`      | `assertValidationNaturalNumber()`      |
| `testDataValidationForeignKey()`         | `assertValidationForeignKey()`         |
| `testDataValidationIsUnique()`           | `assertValidationIsUnique()`           |

### `testRules()` removal

`testRules()` was removed without a direct equivalent. Use `assertRules()` instead, but note the difference in behavior:
`assertRules()` skips data validation (`validate => false`) and asserts that saving fails,
while `testRules()` first asserted that there are no validation errors.
Review each replaced call to make sure the test still covers what you intend.

---

## 2. Rule-dedicated methods now check only their own rule

Previously, methods like `assertValidationBoolean()`, `assertValidationEmail()`, `assertValidationInteger()`, etc. compared the **entire** error array for a field against an expected array (or `[]` for valid values). If a field had multiple validation errors, this could hide unrelated errors or cause false failures.

Now these methods assert **only their own rule key** (present or absent), ignoring any other errors on the same field.

Review your calls of these methods, especially where a custom `$expected` array containing several rules is passed,
and make sure the tests still cover what you intend. The migration script lists all these calls for you.
