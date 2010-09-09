<?php
/**
 * Logger
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/IAppender.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Exception.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Message.php';

/**
 * Logger
 *
 * Logger class used to write log messages to multiple destinations.
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */
class Logger_Category
{
    /**
     * NOTSET level const
     */
    const NOTSET = 0;

    /**
     * CRIT level const
     */
    const CRIT = 32;

    /**
     * ERROR level const
     */
    const ERROR = 16;

    /**
     * WARN level const
     */
    const WARN = 8;

    /**
     * INFO level const
     */
    const INFO = 4;

    /**
     * DEBUG level const
     */
    const DEBUG = 2;

    /**
     * TRACE level const
     */
    const TRACE = 1;

    /**
     * Holds the name for this logging instance
     *
     * @var string
     */
    protected $name = null;

    /**
     * Holds a reference to the parent logger
     *
     * @var Logger
     */
    protected $parent = null;

    /**
     * Priority level for this logger
     *
     * @var integer
     */
    protected $level = self::NOTSET;

    /**
     * Holds a list of appenders
     *
     * @var array
     */
    protected $appenders = array();

    /**
     * Should we move up the chain to parent loggers?
     *
     * @var boolean
     */
    protected $propagate = true;

    /**
     * Maps a log level to a text description
     *
     * @var array
     */
    public static $levelToText = array(
        self::NOTSET => 'NOTSET',
        self::CRIT   => 'CRIT',
        self::ERROR  => 'ERROR',
        self::WARN   => 'WARN',
        self::INFO   => 'INFO',
        self::DEBUG  => 'DEBUG',
        self::TRACE  => 'TRACE',
    );

    /**
     * Maps a text to a log level
     *
     * @var array
     */
    public static $textToLevel = array(
        'NOTSET' => self::NOTSET,
        'CRIT'   => self::CRIT,
        'ERROR'  => self::ERROR,
        'WARN'   => self::WARN,
        'INFO'   => self::INFO,
        'DEBUG'  => self::DEBUG,
        'TRACE'  => self::TRACE,
    );

    /**
     * Constructor
     *
     * @param string  $name  The name for this logging instance, all messages will be sent to this class.
     * @param integer $level The level of priority for this logging instance
     *
     * @return void
     */
    public function __construct($name = null, $level = self::WARN)
    {
        $this->name  = $name;
        $this->level = $level;
    }

    /**
     * Get the name of the logger
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the level of this logger
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Get the propagate value
     *
     * @return boolean
     */
    public function getPropagate()
    {
        return $this->propagate;
    }

    /**
     * Set the propagate value
     *
     * @param boolean $propagate Flag
     *
     * @return void
     */
    public function setPropagate($propagate)
    {
        $this->propagate = $propagate;
    }

    /**
     * The parent logger
     *
     * @param Logger_Category $parent The parent logger for this class
     *
     * @return void
     */
    public function setParent(Logger_Category $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get the parent logger
     *
     * @return Logger
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add an appender to send messages to.
     *
     * @param string $appender The appender to add, must implement Logger_IAppender
     *
     * @return void
     */
    public function addAppender(Logger_IAppender $appender)
    {
        $this->appenders[$appender->getName()] = $appender;
    }

    /**
     * Get the list of appenders
     *
     * @return array
     */
    public function getAppenders()
    {
        return $this->appenders;
    }

    /**
     * Wrapper for the crit level message.
     *
     * @param mixed $msg  Log message, string, array, or object
     * @param array $args Pass structured data to handlers.
     *
     * @return void
     */
    public function crit($msg, array $args = array())
    {
        if ($this->isEnabledFor(self::CRIT)) {
            $this->log($msg, self::CRIT, $args);
        }
    }

    /**
     * Wrapper for the error level message.
     *
     * @param mixed $msg  Log message, string, array, or object
     * @param array $args Pass structured data to handlers.
     *
     * @return void
     */
    public function error($msg, array $args = array())
    {
        if ($this->isEnabledFor(self::ERROR)) {
            $this->log($msg, self::ERROR, $args);
        }
    }

    /**
     * Wrapper for the warn level message.
     *
     * @param mixed $msg  Log message, string, array, or object
     * @param array $args Pass structured data to handlers.
     *
     * @return void
     */
    public function warn($msg, array $args = array())
    {
        if ($this->isEnabledFor(self::WARN)) {
            $this->log($msg, self::WARN, $args);
        }
    }

    /**
     * Wrapper for the info level message.
     *
     * @param mixed $msg  Log message, string, array, or object
     * @param array $args Pass structured data to handlers.
     *
     * @return void
     */
    public function info($msg, array $args = array())
    {
        if ($this->isEnabledFor(self::INFO)) {
            $this->log($msg, self::INFO, $args);
        }
    }

    /**
     * Wrapper for the debug level message.
     *
     * @param mixed $msg  Log message, string, array, or object
     * @param array $args Pass structured data to handlers.
     *
     * @return void
     */
    public function debug($msg, array $args = array())
    {
        if ($this->isEnabledFor(self::DEBUG)) {
            $this->log($msg, self::DEBUG, $args);
        }
    }

    /**
     * Wrapper for the trace level message.
     *
     * @param mixed $msg Log message, string, array, or object
     *
     * @return void
     */
    public function trace($msg, array $args = array())
    {
        if ($this->isEnabledFor(self::TRACE)) {
            $this->log($msg, self::TRACE, $args);
        }
    }

    /**
     * Log a message to a list of appenders
     *
     * @param mixed   $msg   The message to log, may be a string, array, or object.
     * @param integer $level The level of the mssage to log.
     * @param array   $args  Pass structured data to handlers.
     *
     * @return void
     */
    protected function log($msg, $level, array $args = array())
    {
        list($file, $function, $lineno) = $this->findCaller(debug_backtrace());
        $message = $this->makeMessage($this->name, $msg, $level, $file, $function, $lineno, $args);
        $this->handle($message);
    }

    /**
     * Handle a specific log message
     *
     * @param Logger_Message $msg The message object
     *
     * @return void
     */
    public function handle(Logger_Message $msg)
    {
        $this->callAppenders($msg);
    }

    /**
     * Call the appenders and then move up the chain
     *
     * @param Logger_Message $msg The message object
     *
     * @return void
     */
    public function callAppenders(Logger_Message $msg)
    {
        $a = $this;

        while ($a) {
            foreach ($a->getAppenders() as $appender) {
                $appender->log($msg);
            }

            if (!$a->getPropagate()) {
                break;
            }

            if ($a->getParent() !== null) {
                $a = $a->getParent();
            } else {
                $a = null;
            }
        }
    }

    /**
     * Look up the stack to find the calling function.
     *
     * @param array $stack The call stack
     *
     * @return array $file, $function, $lineno
     */
    protected function findCaller(array $stack)
    {
        $stack = array_slice($stack, 0, 4);

        $c  = null;
        $c2 = null;

        for ($i=0; $i<count($stack); $i++) {
            if (strpos($stack[$i]['file'], 'Logger/Category') !== false) {
                continue;
            }

            $c  = $stack[$i];
            $c2 = $stack[$i+1];
            break;
        }

        $file     = (isset($c['file'])) ? $c['file'] : null;
        $lineno   = (isset($c['line'])) ? $c['line'] : null;
        $function = (isset($c2['class'])) ? $c2['class'] . '::' : '';
        $function = (isset($c2['function'])) ? $function . $c2['function'] : null;

        return array($file, $function, $lineno);
    }

    /**
     * Build a message object
     *
     * @param string  $name     The class name
     * @param string  $msg      The log message
     * @param integer $level    The log level
     * @param string  $file     The file name
     * @param string  $function The function name
     * @param integer $lineno   The line no
     * @param array   $args     Mixed misc args for the message
     *
     * @return Logger_Message
     */
    protected function makeMessage($name, $msg, $level, $file, $function, $lineno, array $args)
    {
        return new Logger_Message($this->name, $msg, $level, $file, $function, $lineno, $args);
    }

    /**
     * Traverse logger structure to determine the log level.
     *
     * @return void
     * @author Matt Erkkila
     */
    protected function getEffectiveLevel()
    {
        $logger = $this;

        while ($logger) {
            $level = $logger->getLevel();
            if ($level) {
                return $level;
            }
            $logger = $logger->getParent();
        }

        return self::NOTSET;
    }

    /**
     * Check to see if this log level is enabled
     *
     * @param integer $level The log level of the current message
     *
     * @return bool
     */
    protected function isEnabledFor($level)
    {
        return ($level >= $this->getEffectiveLevel());
    }
}

?>