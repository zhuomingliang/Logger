<?php
/**
 * Logger_Appender_Buffered
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Appender.php';

/**
 * Logger_Appender_Buffered
 *
 * The base appender
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */
abstract class Logger_Appender_Buffered extends Logger_Appender
{
    /**
     * Buffer capacity
     *
     * @var integer
     */
    protected $capacity = null;

    /**
     * Holder for buffered messages
     *
     * @var array
     */
    protected $bufferedMessages = array();

    /**
     * Constructor
     *
     * @param string $name The name of the appender
     * @param array  $cfg  The config for the appender
     *
     * @return void
     */
    public function __construct($name, array $cfg)
    {
        parent::__construct($name, $cfg);
        $this->capacity = $this->getCapacity();
    }

    /**
     * Determine if the appender has buffering turned on
     *
     * @return boolean
     */
    public function getCapacity()
    {
        if (isset($this->cfg['logging.appender.' . $this->name . '.capacity'])) {
            return $this->cfg['logging.appender.' . $this->name . '.capacity'];
        }

        return null;
    }

    /**
     * Buffer a log message
     *
     * @param Logger_Message $message The log message to buffer
     *
     * @return void
     */
    public function log(Logger_Message $message)
    {
        $this->bufferedMessages[] = $message;
        if ($this->shouldFlush($message)) {
            $this->flush();
        }
    }

    /**
     * Check to see if we should flush the buffer
     *
     * @param Logger_Message $message The message currently being logged
     *
     * @return void
     */
    public function shouldFlush(Logger_Message $message)
    {
        if ($this->capacity == null) {
            return false;
        }

        return (count($this->bufferedMessages) >= $this->capacity);
    }

    /**
     * Flush the buffer
     *
     * @return void
     */
    public function flush()
    {
        $this->bufferedMessages = array();
    }
}

?>