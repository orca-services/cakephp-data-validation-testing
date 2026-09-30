<?php
declare(strict_types=1);

/**
 * Data Validation Testing Migration Script
 *
 * This script helps to migrate an application from orca-services/cakephp-data-validation-testing 2.x to 3.x.
 *
 * It follows the migration guide:
 * https://github.com/orca-services/cakephp-data-validation-testing/blob/cakephp-5.x/docs/Migration.md
 *
 * What it does:
 * 1. Renames all calls of the old `testDataValidation`-prefixed trait methods to the new `assertValidation`-prefixed ones.
 * 2. Replaces calls of the removed `testRules()` with `assertRules()` and lists them for manual review.
 * 3. Lists all calls of rule-dedicated methods, which now only check their own rule, for manual review.
 * 4. Optionally updates the version constraint of the package in composer.json.
 *
 * Usage: php vendor/orca-services/cakephp-data-validation-testing/migrate.php (from your application root)
 */

define('COL_DESCRIPTION', 0);
define('COL_HELP', 1);
define('COL_DEFAULT', 2);

define('PACKAGE_NAME', 'orca-services/cakephp-data-validation-testing');

$fields = [
    'paths' => [
        'Directories to migrate',
        'comma separated, relative to ' . getcwd(),
        'tests',
    ],
    'composer_json' => [
        'Path to the composer.json to update',
        'leave "-" to skip',
        'composer.json',
    ],
    'version_constraint' => [
        'New version constraint for ' . PACKAGE_NAME,
        '',
        '^3.0',
    ],
];

/**
 * Old method name => new method name
 *
 * Section 1 of the migration guide.
 */
$renames = [
    'testDataValidationNotEmpty' => 'assertValidationNotEmpty',
    'testDataValidationEmpty' => 'assertValidationEmpty',
    'testDataValidationRequired' => 'assertValidationRequired',
    'testDataValidationNotRequired' => 'assertValidationNotRequired',
    'testDataValidationBoolean' => 'assertValidationBoolean',
    'testDataValidationURLWithProtocol' => 'assertValidationURLWithProtocol',
    'testDataValidationDateTime' => 'assertValidationDateTime',
    'testDataValidationDate' => 'assertValidationDate',
    'testDataValidationInList' => 'assertValidationInList',
    'testDataValidation' => 'assertValidation',
    'testDataValidationNoErrors' => 'assertValidationNoErrors',
    'testFullDataValidation' => 'assertValidationTableErrors',
    'testFullDataValidationNoErrors' => 'assertValidationTableNoErrors',
    'testDataValidationContains' => 'assertValidationContains',
    'testDataValidationNotContains' => 'assertValidationNotContains',
    'assertDataValidationErrorsContain' => 'assertValidationErrorsContain',
    'testDataValidationListContains' => 'assertValidationListContains',
    'testDataValidationListNotContains' => 'assertValidationListNotContains',
    'testDataRules' => 'assertRules',
    // Removed, assertRules() is the replacement. See $manualReviewRenames.
    'testRules' => 'assertRules',
    'testDataRulesNoErrors' => 'assertRulesNoErrors',
    'testDataValidationMaxLength' => 'assertValidationMaxLength',
    'testDataValidationMinLength' => 'assertValidationMinLength',
    'testDataValidationScalar' => 'assertValidationScalar',
    'testDataValidationDecimal' => 'assertValidationDecimal',
    'testDataValidationInteger' => 'assertValidationInteger',
    'testDataValidationNonNegativeInteger' => 'assertValidationNonNegativeInteger',
    'testDataValidationGreaterThanOrEqual' => 'assertValidationGreaterThanOrEqual',
    'testDataValidationEmail' => 'assertValidationEmail',
    'testDataValidationUuid' => 'assertValidationUuid',
    'testDataValidationLengthBetween' => 'assertValidationLengthBetween',
    'testDataValidationRange' => 'assertValidationRange',
    'testDataValidationNaturalNumber' => 'assertValidationNaturalNumber',
    'testDataValidationForeignKey' => 'assertValidationForeignKey',
    'testDataValidationIsUnique' => 'assertValidationIsUnique',
];

/**
 * Old method names whose replacement does not behave exactly the same, with the reason
 */
$manualReviewRenames = [
    'testRules' => 'testRules() was removed. assertRules() skips data validation (validate => false) '
        . 'and asserts that saving fails, while testRules() asserted that there are no validation errors first.',
];

/**
 * Rule-dedicated methods, which now only check their own rule (section 2 of the migration guide)
 */
$ruleDedicatedMethods = [
    'assertValidationNotEmpty',
    'assertValidationEmpty',
    'assertValidationRequired',
    'assertValidationNotRequired',
    'assertValidationBoolean',
    'assertValidationURLWithProtocol',
    'assertValidationDateTime',
    'assertValidationDate',
    'assertValidationMaxLength',
    'assertValidationMinLength',
    'assertValidationScalar',
    'assertValidationDecimal',
    'assertValidationInteger',
    'assertValidationNonNegativeInteger',
    'assertValidationGreaterThanOrEqual',
    'assertValidationEmail',
    'assertValidationUuid',
    'assertValidationLengthBetween',
    'assertValidationRange',
    'assertValidationNaturalNumber',
];

$values = [];

function read_from_console($prompt)
{
    if (function_exists('readline')) {
        $line = trim(readline($prompt));
        if (!empty($line)) {
            readline_add_history($line);
        }
    } else {
        echo $prompt;
        $line = trim(fgets(STDIN));
    }

    return $line;
}

/**
 * Build the regular expression matching calls/references of the given method names
 *
 * Only matches names directly preceded by "->" or "::", so that e.g. own test methods named alike are not touched.
 * The longest names come first and a word boundary is enforced, so `testDataValidation` never matches a longer name.
 */
function method_call_pattern(array $methodNames)
{
    usort($methodNames, static function ($a, $b) {
        return strlen($b) - strlen($a);
    });
    $alternatives = implode('|', array_map(static function ($name) {
        return preg_quote($name, '/');
    }, $methodNames));

    return '/(->|::)(\s*)(' . $alternatives . ')\b/';
}

/**
 * Find all PHP files in the given directories
 */
function find_php_files(array $paths)
{
    $files = [];
    foreach ($paths as $path) {
        if (is_file($path) && substr($path, -4) === '.php') {
            $files[] = $path;
            continue;
        }
        if (!is_dir($path)) {
            echo "Warning: '$path' does not exist, skipping.\n";
            continue;
        }
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
        );
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }
    }
    sort($files);

    return array_unique($files);
}

/**
 * Get the line number of a byte offset within a text
 */
function line_of($text, $offset)
{
    return substr_count($text, "\n", 0, $offset) + 1;
}

$modify = 'n';
do {
    if ($modify == 'q') {
        exit;
    }

    $values = [];

    echo "----------------------------------------------------------------------\n";
    echo 'Migration of ' . PACKAGE_NAME . " to 3.x\n";
    echo "Please, provide the following information:\n";
    echo "----------------------------------------------------------------------\n";
    foreach ($fields as $fieldKey => $field) {
        $default = $field[COL_DEFAULT] ?? '';
        $prompt = sprintf(
            '%s%s%s: ',
            $field[COL_DESCRIPTION],
            $field[COL_HELP] ? ' (' . $field[COL_HELP] . ')' : '',
            $default !== '' ? ' [' . $default . ']' : '',
        );
        $values[$fieldKey] = read_from_console($prompt);
        if (empty($values[$fieldKey])) {
            $values[$fieldKey] = $default;
        }
    }
    echo "\n";

    echo "----------------------------------------------------------------------\n";
    echo "Please, check that everything is correct:\n";
    echo "----------------------------------------------------------------------\n";
    foreach ($fields as $fieldKey => $field) {
        echo $field[COL_DESCRIPTION] . ": $values[$fieldKey]\n";
    }
    echo "\n";
} while (($modify = strtolower(read_from_console('Migrate files with these values? [y/N/q] '))) !== 'y');
echo "\n";

$paths = array_filter(array_map('trim', explode(',', $values['paths'])));
$filesToMigrate = find_php_files($paths);

$renamePattern = method_call_pattern(array_keys($renames));
$ruleDedicatedPattern = method_call_pattern($ruleDedicatedMethods);
$leftoverPattern = '/\b(' . implode('|', array_map(static function ($name) {
        return preg_quote($name, '/');
}, array_keys($renames))) . ')\b/';

$totalReplacements = 0;
$changedFiles = 0;
$manualReview = [];
$ruleDedicatedCalls = [];
$leftovers = [];

echo "----------------------------------------------------------------------\n";
echo "Renaming methods:\n";
echo "----------------------------------------------------------------------\n";
foreach ($filesToMigrate as $filename) {
    $contentToReplaceIn = file_get_contents($filename);

    // Collect the calls that need a manual review, before replacing them
    if (preg_match_all($renamePattern, $contentToReplaceIn, $matches, PREG_OFFSET_CAPTURE)) {
        foreach ($matches[3] as $match) {
            if (isset($manualReviewRenames[$match[0]])) {
                $manualReview[] = sprintf(
                    '%s:%d: %s',
                    $filename,
                    line_of($contentToReplaceIn, $match[1]),
                    $manualReviewRenames[$match[0]],
                );
            }
        }
    }

    $count = 0;
    $migratedContent = preg_replace_callback(
        $renamePattern,
        static function ($match) use ($renames) {
            return $match[1] . $match[2] . $renames[$match[3]];
        },
        $contentToReplaceIn,
        -1,
        $count,
    );

    if ($count > 0) {
        file_put_contents($filename, $migratedContent);
        echo "$filename: $count replacement(s)\n";
        $totalReplacements += $count;
        $changedFiles++;
    }

    // Rule-dedicated methods now only check their own rule
    if (preg_match_all($ruleDedicatedPattern, $migratedContent, $matches, PREG_OFFSET_CAPTURE)) {
        foreach ($matches[3] as $match) {
            $ruleDedicatedCalls[] = sprintf(
                '%s:%d: %s()',
                $filename,
                line_of($migratedContent, $match[1]),
                $match[0],
            );
        }
    }

    // Old names still present, e.g. in strings, callables or own method definitions
    if (preg_match_all($leftoverPattern, $migratedContent, $matches, PREG_OFFSET_CAPTURE)) {
        foreach ($matches[1] as $match) {
            $leftovers[] = sprintf(
                '%s:%d: %s',
                $filename,
                line_of($migratedContent, $match[1]),
                $match[0],
            );
        }
    }
}
echo "\n$totalReplacements replacement(s) in $changedFiles of " . count($filesToMigrate) . " file(s).\n\n";

$composerUpdated = false;
if ($values['composer_json'] !== '-') {
    echo "----------------------------------------------------------------------\n";
    echo "Updating composer.json:\n";
    echo "----------------------------------------------------------------------\n";
    $composerJson = $values['composer_json'];
    if (!is_file($composerJson)) {
        echo "Warning: '$composerJson' does not exist, skipping.\n";
    } else {
        $content = file_get_contents($composerJson);
        $pattern = '/("' . preg_quote(PACKAGE_NAME, '/') . '"\s*:\s*")([^"]*)(")/';
        if (!preg_match($pattern, $content, $match)) {
            echo 'Warning: ' . PACKAGE_NAME . " is not required in '$composerJson', skipping.\n";
        } else {
            // Only replace the version constraint, so the formatting of the file is preserved
            $content = preg_replace($pattern, '${1}' . $values['version_constraint'] . '${3}', $content, 1);
            file_put_contents($composerJson, $content);
            echo "Changed the constraint from '$match[2]' to '" . $values['version_constraint'] . "'.\n";
            $composerUpdated = true;
        }
    }
    echo "\n";
}

echo "Done.\n\n";

if (!empty($manualReview)) {
    echo "----------------------------------------------------------------------\n";
    echo "Please, review these replacements manually:\n";
    echo "----------------------------------------------------------------------\n";
    echo implode("\n", $manualReview) . "\n\n";
}

if (!empty($leftovers)) {
    echo "----------------------------------------------------------------------\n";
    echo "Old method names that were not replaced automatically:\n";
    echo "(e.g. in strings, callables or own methods with the same name)\n";
    echo "----------------------------------------------------------------------\n";
    echo implode("\n", $leftovers) . "\n\n";
}

if (!empty($ruleDedicatedCalls)) {
    echo "----------------------------------------------------------------------\n";
    echo "Rule-dedicated methods now check only their own rule.\n";
    echo "Previously the entire error array of the field was compared, which could hide\n";
    echo "unrelated errors. Please, check that these tests still cover what you intend,\n";
    echo "especially where a custom \$expected with several rules is passed:\n";
    echo "----------------------------------------------------------------------\n";
    echo implode("\n", $ruleDedicatedCalls) . "\n\n";
}

echo "\nNext steps:\n";
if ($composerUpdated) {
    echo '- Run: composer update ' . PACKAGE_NAME . " --with-dependencies\n";
}
echo "- Run your test suite and fix failing tests.\n";
echo "- Review the diff (e.g. git diff) before committing.\n\n";

echo "See https://github.com/orca-services/cakephp-data-validation-testing/blob/cakephp-5.x/docs/Migration.md\n";
