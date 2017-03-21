<?php

use Imjoehaines\Flowder\Codeception\Flowdception;

use Imjoehaines\Flowder\Flowder;
use Imjoehaines\Flowder\Loader\PhpFileLoader;
use Imjoehaines\Flowder\Truncator\SqliteTruncator;
use Imjoehaines\Flowder\Persister\SqlitePersister;

class FlowdceptionCest
{
    public function itThrowsWhenNotBootstrapped(IntegrationTester $I)
    {
        $I->expectException(
            new LogicException(
                'Flowdception must be configured by calling Flowdception::bootstrap before any tests run'
            ),
            function () {
                $flowdception = new Flowdception([], []);
                $flowdception->beforeTest();
            }
        );
    }

    public function itLoadsFixturesWhenBeforeTestIsCalled(IntegrationTester $I)
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
                __DIR__ . '/../_data/example.php',
                new PhpFileLoader(),
                new SqliteTruncator($db),
                new SqlitePersister($db)
            )
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

        $I->assertSame($expected, $actual);
    }
}
