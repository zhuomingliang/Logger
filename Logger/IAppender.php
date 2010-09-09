<?php

/**
 * Logger_IAppender
 *
 * @package Logger
 * @author  Matt Erkkila <matt@matterkkila.com>
 */

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Message.php';

/**
 * Logger_IAppender
 *
 * Interface for log appenders
 *
 * @package Logger
 * @author  Matt Erkkila <matt@matterkkila.com>
 */
interface Logger_IAppender
{
    /**
     * Method that receives the log messages
     *
     * @param Logger_Message $message The message to log
     *
     * @return void
     */
    public function log(Logger_Message $message);
}

?>