<?php

function quoteIdentifier($id)
{
    return '`' . str_replace('`', '``', $id) . '`';
}

$dsn = getenv('CI_DB_DSN');
$username = getenv('CI_DB_USERNAME');
$password = getenv('CI_DB_PASSWORD');
if ($dsn === false || $username === false || $password === false) {
    exit(0);
}

$dbName = getenv('MYSQL_DATABASE');
if ($dbName === false) {
    // Not mysql, nothing to do.
    exit(0);
}
$charset = getenv('MYSQL_CHARSET');
$collation = getenv('MYSQL_COLLATION');

if ($charset === false && $collation === false) {
    // Nothing to do.
    exit(0);
}

$pdo = new PDO($dsn, $username, $password);

$alters = [];
if ($charset !== false) {
    $alters[] = 'CHARACTER SET ' . quoteIdentifier($charset);
}
if ($collation !== false) {
    $alters[] = 'COLLATE ' . quoteIdentifier($collation);
}

$st = $pdo->prepare('ALTER DATABASE ' . quoteIdentifier($dbName) . ' ' . implode(' ', $alters));

if (!$st->execute()) {
    echo 'Failed to alter database charset/collation!';
    echo "\n";
    print_r($st->errorInfo());
    exit(1);
}
