<?php
/**
 * Logger_IBuilder
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */

/**
 * Logger_IBuilder
 *
 * Builder interface
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */
interface Logger_IBuilder
{
    /**
     * Build all the loggers
     *
     * @param array &$loggers Holds the loggers as they are built
     *
     * @return array
     */
    public function build(array &$loggers);

    /**
     * Build a specific logger
     *
     * @param string $name     Logger name
     * @param array  &$loggers Current list of loggers
     *
     * @return Logger_Category
     */
    public function buildLogger($name, array &$loggers);

    /**
     * Shutdown the appenders
     *
     * @return void
     */
    public function shutdown();
}

?>