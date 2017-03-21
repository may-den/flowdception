<?php

namespace Imjoehaines\Flowder\Codeception;

use LogicException;
use Codeception\Events;
use Codeception\Extension;
use Codeception\Event\TestEvent;
use Imjoehaines\Flowder\Flowder;

final class Flowdception extends Extension
{
    /**
     * List of Codeception events to listen for and the method to call when it is fired
     *
     * @var array
     */
    public static $events = [
        Events::TEST_START => 'beforeTest',
    ];

    /**
     * @var Flowder
     */
    private static $flowder;

    /**
     * Initialise the Flowder instance
     *
     * We need this to be able to provide a super-flexible fixture loader; if we
     * relied on Codeception's YAML configuration then you couldn't easily use
     * arbitrary instances of Flowder's dependencies
     *
     * @param Flowder $flowder
     * @return void
     */
    public static function bootstrap(Flowder $flowder)
    {
        static::$flowder = $flowder;
    }

    /**
     * Load fixtures before each test runs
     *
     * @param TestEvent $event
     * @return void
     * @throws LogicException when `bootstrap` hasn't been called
     */
    public function beforeTest(TestEvent $event)
    {
        $this->checkIsInitialised();

        static::$flowder->loadFixtures();
    }

    /**
     * Check `bootstrap` has been called before we try and use the Flowder instance
     *
     * @return void
     * @throws LogicException when the Flowder instance hasn't been created
     */
    private function checkIsInitialised()
    {
        if (!isset(static::$flowder)) {
            throw new LogicException(
                'Flowdception must be configured by calling Flowdception::bootstrap before any tests run'
            );
        }
    }
}
