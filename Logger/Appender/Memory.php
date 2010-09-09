<?php
/**
 * Logger_Appender_Memory
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/IAppender.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Appender.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Message.php';

/**
 * Logger_Appender_Memory
 *
 * Append log messages to an array in memory
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */
class Logger_Appender_Memory extends Logger_Appender implements Logger_IAppender
{
    protected $messages = array();

    /**
     * Get the messages sent to this appender
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Method that receives the log messages
     *
     * @param Logger_Message $message The message to log
     *
     * @return void
     */
    public function log(Logger_Message $message)
    {
        $this->messages[] = $message;
    }
}

?>