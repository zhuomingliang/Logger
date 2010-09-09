<?php
/**
 * Logger_Appender_BaseRotating
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/IAppender.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Appender/ErrorLog.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Message.php';

/**
 * Logger_Appender_BaseRotating
 *
 * Base class for files based appenders which rotate filenames.
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */
abstract class Logger_Appender_BaseRotating extends Logger_Appender_ErrorLog implements Logger_IAppender
{
    /**
     * Method that receives the log messages, handles rollover
     * for files
     *
     * @param Logger_Message $message The message to log
     *
     * @return void
     */
    public function log(Logger_Message $message)
    {
        if ($this->shouldRollover($message)) {
            $this->doRollover();
        }

        parent::log($message);
    }

    /**
     * Determine if we need to rollover to a new filename
     *
     * @param Logger_Message $message The logger message
     *
     * @return bool
     */
    abstract public function shouldRollover(Logger_Message $message);

    /**
     * Perform the rollover
     *
     * @return void
     */
    abstract public function doRollover();
}

?>