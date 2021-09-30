<?php

namespace Imjoehaines\Flowder\Codeception\Test;

use PDO;
use LogicException;
use PHPUnit\Framework\TestCase;
use Imjoehaines\Flowder\Flowder;
use Imjoehaines\Flowder\Loader\PhpFileLoader;
use Imjoehaines\Flowder\Codeception\Flowdception;
use Imjoehaines\Flowder\Truncator\SqliteTruncator;
use Imjoehaines\Flowder\Persister\SqlitePersister;

class FlowdceptionTest extends TestCase
{
    public function testItThrowsWhenNotBootstrapped()
    {
        $this->expectException(
            LogicException::class,
            'Flowdception must be configured by calling Flowdception::bootstrap before any tests run'
        );

        $flowdception = new Flowdception([], []);
        $flowdception->beforeTest();
    }

    public function testItLoadsFixturesWhenBeforeTestIsCalled()
    {
        $db = new PDO('sqlite::memory:');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $db->exec('CREATE TABLE IF NOT EXISTS example (
            one INT PRIMARY KEY,
            two INT,
            three INT,
            four INT
        )');

        Flowdception::bootstrap(
            new Flowder(
                new PhpFileLoader(),
                new SqliteTruncator($db),
                new SqlitePersister($db)
            ),
            __DIR__ . '/../data/example.php'
        );

        $flowdception = new Flowdception([], []);
        $flowdception->beforeTest();

        $statement = $db->prepare('SELECT * FROM example');
        $statement->execute();

        $actual = $statement->fetchAll();

        $expected = [[
            'one' => '1',
            'two' => '2',
            'three' => '3',
            'four' => '4',
        ]];

        $this->assertSame($expected, $actual);
    }

    public function testItAllowsFixturesToBeLoadedManually()
    {
        $db = new PDO('sqlite::memory:');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $db->exec('CREATE TABLE IF NOT EXISTS example (
            one INT PRIMARY KEY,
            two INT,
            three INT,
            four INT
        )');

        Flowdception::bootstrap(
            new Flowder(
                new PhpFileLoader(),
                new SqliteTruncator($db),
                new SqlitePersister($db)
            ),
            'no location'
        );

        Flowdception::loadFixtures(__DIR__ . '/../data/example.php');

        $statement = $db->prepare('SELECT * FROM example');
        $statement->execute();

        $actual = $statement->fetchAll();

        $expected = [[
            'one' => '1',
            'two' => '2',
            'three' => '3',
            'four' => '4',
        ]];

        $this->assertSame($expected, $actual);
    }
}
