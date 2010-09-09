<?php
/**
 * Logger_Message
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */

/**
 * Logger_Message
 *
 * Holder for the log message
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */
class Logger_Message
{
    /**
     * Message Class
     *
     * @var string
     */
    public $name = null;

    /**
     * Message Text
     *
     * @var string
     */
    public $message = null;

    /**
     * Message Priority
     *
     * @var integrr
     */
    public $level = null;

    /**
     * Path to file
     *
     * @var string
     */
    public $file = null;

    /**
     * Function name
     *
     * @var string
     */
    public $function = null;

    /**
     * Line number
     *
     * @var integer
     */
    public $lineno = null;

    /**
     * Message Time
     *
     * @var integer
     */
    public $time = null;

    /**
     * Milliseconds
     *
     * @var integer
     */
    public $milliseconds = null;

    /**
     * Misc Data holder
     *
     * @var array
     */
    public $args = null;

    /**
     * Constructor
     *
     * @param string  $name     The class of the message
     * @param string  $message  The message text
     * @param integer $level    The priority of the message
     * @param string  $file     The path to the file where the message originated
     * @param string  $function The function name which originated the message
     * @param integer $lineno   The line number for the log call
     * @param array   $args     Misc data for the message
     *
     * @return void
     */
    public function __construct($name, $message, $level, $file, $function, $lineno, array $args)
    {
        $this->name         = $name;
        $this->message      = $message;
        $this->level        = $level;
        $this->file         = $file;
        $this->function     = $function;
        $this->lineno       = $lineno;
        $this->args         = $args;
        $this->time         = time();
        $this->milliseconds = (int)((microtime(true) - $this->time) * 10000000);
    }
}

?>