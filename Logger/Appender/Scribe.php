<?php
/**
 * Logger_Appender_Scribe
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/IAppender.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Appender/Buffered.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Message.php';

/**
 * Logger_Appender_Scribe
 *
 * Append log messages to an array in memory
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */
class Logger_Appender_Scribe extends Logger_Appender_Buffered implements Logger_IAppender
{
    /**
     * Scribe instance
     *
     * @var Scribe
     */
    protected $scribe = null;

    /**
     * Category to send messages through
     *
     * @var string
     */
    protected $category = null;

    /**
     * Constructor
     *
     * @param string $name   The name of the appender
     * @param array  $cfg    The config for the appender
     * @param string $driver The scribe driver class
     *
     * @return void
     */
    public function __construct($name, array $cfg, $driver = 'Scribe')
    {
        parent::__construct($name, $cfg);

        $cfgPrefix = 'logging.appender.' . $name . '.';

        $host           = isset($cfg[$cfgPrefix . 'host']) ? $cfg[$cfgPrefix . 'host'] : 'localhost';
        $port           = isset($cfg[$cfgPrefix . 'port']) ? $cfg[$cfgPrefix . 'port'] : 1463;
        $sendTimeout    = isset($cfg[$cfgPrefix . 'sendtimeout']) ? $cfg[$cfgPrefix . 'sendtimeout'] : 100;
        $receiveTimeout = isset($cfg[$cfgPrefix . 'recvtimeout']) ? $cfg[$cfgPrefix . 'recvtimeout'] : 750;
        $this->category = isset($cfg[$cfgPrefix . 'category']) ? $cfg[$cfgPrefix . 'category'] : null;

        loadClass($driver);
        $this->scribe = new $driver($host, $port, $sendTimeout, $receiveTimeout);
    }

    /**
     * Flush the buffered messages to scribe
     *
     * @return void
     */
    public function flush()
    {
        $scribeMessages = array();

        foreach ($this->bufferedMessages as $message) {
            $scribeMessages[] = $this->getScribeMessage($message);
        }

        if (count($scribeMessages) > 0) {
            try {
                $this->scribe->log($scribeMessages);
            } catch (Exception $e) {
                //Ignore errors from scribe
            }

            parent::flush();
        }
    }

    /**
     * Convert a message object to a scribe formatted message
     *
     * @param Logger_Message $message The message
     *
     * @return LogEntry
     */
    protected function getScribeMessage(Logger_Message $message)
    {
        $log           = new LogEntry;
        $log->category = ($this->category !== null) ? $this->category : $message->name;
        $log->message  = $this->getLayout()->format($message);

        return $log;
    }

    /**
     * Shut down the scribe handler
     *
     * @return void
     */
    public function close()
    {
        try {
            $this->scribe->close();
        } catch (Exception $e) {
            //Ignore errors from scribe
        }
        $this->scribe = null;
    }
}

?>