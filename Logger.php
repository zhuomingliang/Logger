<?php
/**
 * Logger
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/IBuilder.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Exception.php';

/**
 * Logger
 *
 * Logger class used to write log messages to multiple destinations.
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */
class Logger
{
    /**
     * Holds a reference to all the loggers from the config
     *
     * @param array
     */
    protected static $loggers = array();

    /**
     * Used to build Logger_Category instances
     *
     * @param Logger_IBuilder
     */
    protected static $builder = null;

    /**
     * Set the loggers array to something
     *
     * @param string $loggers Null or an array or loggers
     *
     * @return void
     */
    public static function setLoggers(array $loggers)
    {
        self::$loggers = $loggers;
    }

    /**
     * Set the class used to build loggers
     *
     * @param Logger_IBuilder $builder The builder class
     *
     * @return void
     */
    public static function setBuilder($builder)
    {
        self::$builder = $builder;
    }

    /**
     * Tell the builder to shutdown the appenders
     *
     * @return void
     */
    public static function shutdown()
    {
        if (self::$builder instanceof Logger_IBuilder) {
            self::$builder->shutdown();
        }
    }

    /**
     * Factory for building a logger object.
     *
     * @param string $name the class of the the logger
     *
     * @return Logger An instance of the Logger class
     */
    public static function getLogger($name = null)
    {
        if (!(self::$builder instanceof Logger_IBuilder)) {
            throw new Logger_Exception('You must define a builder.');
        }

        if (count(self::$loggers) === 0) {
            self::$builder->build(self::$loggers);
        }

        if (!isset(self::$loggers[$name])) {
            self::$loggers[$name] = self::$builder->buildLogger($name, self::$loggers);
        }

        return self::$loggers[$name];
    }
}

?>