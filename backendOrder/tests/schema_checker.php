<?php
/**
 * Automated static analyzer to check consistency between PHP files and DB schema.
 * 
 * 1. Parses order_full_schema.sql to get tables and columns.
 * 2. Scans all .php files for SQL queries.
 * 3. Checks if tables and columns used in queries exist in the schema.
 */

$schemaPath = __DIR__ . '/../../sql/order_full_schema.sql';
$backendDir = __DIR__ . '/../';

if (!file_exists($schemaPath)) {
    die("Schema file not found at: $schemaPath\n");
}

echo "Loading schema from: " . realpath($schemaPath) . "\n";
$schemaSql = file_get_contents($schemaPath);

// 1. Parse Schema
$tables = []; // [tableName => [col1, col2, ...]]
preg_match_all('/CREATE TABLE\s+(?:IF NOT EXISTS\s+)?`?([a-zA-Z0-9_]+)`?\s*\((.*?)\) ENGINE/s', $schemaSql, $matches);

foreach ($matches[1] as $index => $tableName) {
    $tableBody = $matches[2][$index];
    $columns = [];
    $lines = explode("\n", $tableBody);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || str_starts_with($line, 'PRIMARY') || str_starts_with($line, 'FOREIGN') || str_starts_with($line, 'KEY') || str_starts_with($line, 'UNIQUE') || str_starts_with($line, 'CONSTRAINT')) {
            continue;
        }
        // Match column definition: `col_name` type ...
        if (preg_match('/^`([a-zA-Z0-9_]+)`/', $line, $colMatch)) {
            $columns[] = $colMatch[1];
        } else if (preg_match('/^([a-zA-Z0-9_]+)\s+[A-Z]/', $line, $colMatch)) { // without backticks
            $columns[] = $colMatch[1];
        }
    }
    $tables[$tableName] = $columns;
}

echo "Found " . count($tables) . " tables in schema.\n";

// 2. Scan PHP files
$phpFiles = [];
$iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($backendDir));
foreach ($iter as $file) {
    if ($file->isFile() && $file->getExtension() === 'php' && strpos($file->getPathname(), 'tests' . DIRECTORY_SEPARATOR) === false) {
        $phpFiles[] = $file->getPathname();
    }
}

echo "Scanning " . count($phpFiles) . " PHP files...\n\n";

$errors = [];

foreach ($phpFiles as $filePath) {
    $content = file_get_contents($filePath);
    $relPath = str_replace($backendDir, '', $filePath);
    
    // Look for INSERT INTO
    if (preg_match_all('/INSERT\s+INTO\s+`?([a-zA-Z0-9_]+)`?\s*\((.*?)\)/i', $content, $insertMatches)) {
        foreach ($insertMatches[1] as $idx => $table) {
            if (!isset($tables[$table])) {
                $errors[] = "[$relPath] Unknown table in INSERT: $table";
                continue;
            }
            $colsStr = $insertMatches[2][$idx];
            $cols = array_map('trim', explode(',', str_replace('`', '', $colsStr)));
            foreach ($cols as $col) {
                if (!empty($col) && !in_array($col, $tables[$table])) {
                    $errors[] = "[$relPath] Unknown column in INSERT `$table`.`$col`";
                }
            }
        }
    }
    
    // Look for UPDATE
    if (preg_match_all('/UPDATE\s+`?([a-zA-Z0-9_]+)`?\s+SET\s+(.*?)(?:WHERE|$)/is', $content, $updateMatches)) {
        foreach ($updateMatches[1] as $idx => $table) {
            if (!isset($tables[$table])) {
                $errors[] = "[$relPath] Unknown table in UPDATE: $table";
                continue;
            }
            $setClause = $updateMatches[2][$idx];
            // simplistic split by comma
            $assignments = explode(',', $setClause);
            foreach ($assignments as $assign) {
                $parts = explode('=', $assign);
                $col = trim(str_replace('`', '', $parts[0]));
                // might contain table alias like u.col
                if (strpos($col, '.') !== false) {
                    $colParts = explode('.', $col);
                    $col = trim($colParts[1]);
                }
                if (!empty($col) && !preg_match('/[^a-zA-Z0-9_]/', $col)) { // skip complex expressions
                    if (!in_array($col, $tables[$table])) {
                        $errors[] = "[$relPath] Unknown column in UPDATE `$table`.`$col`";
                    }
                }
            }
        }
    }
    
    // Look for SELECT ... FROM (Simplistic matching)
    if (preg_match_all('/SELECT\s+(.*?)\s+FROM\s+`?([a-zA-Z0-9_]+)`?/is', $content, $selectMatches)) {
        foreach ($selectMatches[2] as $idx => $table) {
            // We only check if the table exists. Checking columns in SELECT is harder due to aliases and joins.
            if (!isset($tables[$table])) {
                $errors[] = "[$relPath] Unknown table in SELECT FROM: $table";
            }
        }
    }
    
    // Look for JOIN
    if (preg_match_all('/JOIN\s+`?([a-zA-Z0-9_]+)`?/is', $content, $joinMatches)) {
        foreach ($joinMatches[1] as $idx => $table) {
            if (!isset($tables[$table])) {
                $errors[] = "[$relPath] Unknown table in JOIN: $table";
            }
        }
    }
}

if (empty($errors)) {
    echo "SUCCESS: No schema inconsistencies found in INSERT/UPDATE/FROM statements.\n";
} else {
    echo "FOUND " . count($errors) . " INCONSISTENCIES:\n";
    foreach ($errors as $err) {
        echo " - $err\n";
    }
}
?>
