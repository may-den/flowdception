<?php
// This is global bootstrap for autoloading

require __DIR__ . '/../vendor/autoload.php';

use Imjoehaines\Flowder\Codeception\Flowdception;

use Imjoehaines\Flowder\Loader\PhpFileLoader;
use Imjoehaines\Flowder\Truncator\SqliteTruncator;
use Imjoehaines\Flowder\Persister\SqlitePersister;

$db = new PDO('sqlite::memory:');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec('CREATE TABLE IF NOT EXISTS example (
    one INT PRIMARY KEY,
    two INT,
    three INT,
    four INT
)');

Flowdception::bootstrap(
    __DIR__ . '/_data/example.php',
    new PhpFileLoader(),
    new SqliteTruncator($db),
    new SqlitePersister($db)
);
