<?php

namespace Meklis\Blocks;
use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;


class LG
{
    /**
     * @var Logger
     */
    protected static $logger;


    /**
     * @return void
     */
    public static function init() {

        // create a log channel
        $log = new Logger('block');
        $log->pushHandler(new StreamHandler('php://stdout', Logger::INFO));
        self::$logger = $log;
    }

    /**
     * @return Logger
     */
    public static function get() {
        return self::$logger;
    }
}