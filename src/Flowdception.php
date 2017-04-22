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
     * @var mixed
     */
    private static $thingToLoad;

    /**
     * Initialise the Flowder instance
     *
     * We need this to be able to provide a super-flexible fixture loader; if we
     * relied on Codeception's YAML configuration then you couldn't easily use
     * arbitrary instances of Flowder's dependencies
     *
     * @param Flowder $flowder
     * @param mixed $thingToLoad
     * @return void
     */
    public static function bootstrap(Flowder $flowder, $thingToLoad)
    {
        static::$flowder = $flowder;
        static::$thingToLoad = $thingToLoad;
    }

    /**
     * Load fixtures before each test runs
     *
     * @return void
     * @throws LogicException when `bootstrap` hasn't been called
     */
    public function beforeTest()
    {
        static::checkIsInitialised();

        static::$flowder->loadFixtures(static::$thingToLoad);
    }

    /**
     * Load fixtures manually rather than via the Codeception extension
     *
     * @param mixed $thingToLoad
     * @return void
     */
    public static function loadFixtures($thingToLoad)
    {
        static::checkIsInitialised();

        static::$flowder->loadFixtures($thingToLoad);
    }

    /**
     * Check `bootstrap` has been called before we try and use the Flowder instance
     *
     * @return void
     * @throws LogicException when the Flowder instance hasn't been created
     */
    private static function checkIsInitialised()
    {
        if (!isset(static::$flowder, static::$thingToLoad)) {
            throw new LogicException(
                'Flowdception must be configured by calling Flowdception::bootstrap before any tests run'
            );
        }
    }
}
