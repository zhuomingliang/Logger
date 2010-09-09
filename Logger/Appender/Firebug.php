<?php
/**
 * Logger_Appender_Firebug
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/IAppender.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Appender.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Category.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Message.php';

/**
 * Logger_Appender_Firebug
 *
 * Send log messages to firebug.  This is based on the
 * Pear "Log" package found at http://pear.php.net/
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */
class Logger_Appender_Firebug extends Logger_Appender implements Logger_IAppender
{
    /**
     * Mapping of log priorities to Firebug methods.
     *
     * @var array
     */
    protected $methods = array(
        Logger_Category::CRIT  => 'error',
        Logger_Category::ERROR => 'error',
        Logger_Category::WARN  => 'warn',
        Logger_Category::INFO  => 'info',
        Logger_Category::DEBUG => 'debug',
        Logger_Category::TRACE => 'debug',
    );

    /**
     * Method that receives the log messages
     *
     * @param Logger_Message $message The message to log
     *
     * @return void
     */
    public function log(Logger_Message $message)
    {
        $strMessage = $this->getLayout()->format($message);

        /* normalize line breaks */
        $strMessage = str_replace("\r\n", "\n", $strMessage);

        /* escape line breaks */
        $strMessage = str_replace("\n", "\\n\\\n", $strMessage);

        /* escape quotes */
        $strMessage = str_replace('"', '\\"', $strMessage);

        $method = $this->methods[$message->level];

        $output  = '<script type="text/javascript">';
        $output .= "\nif (('console' in window) && ('firebug' in console)) {\n";
        /* Build and output the complete log line. */
        $output .= sprintf('  console.%s("%s");', $method, $strMessage);
        $output .= "\n}\n";
        $output .= "</script>\n";

        return $this->sendOutput($output);
    }

    /**
     * Send the output to the client
     *
     * @param string $output The message
     *
     * @return void
     */
    protected function sendOutput($output)
    {// @codeCoverageIgnoreStart
        print $output;

        return;
    }
    // @codeCoverageIgnoreEnd
}

?>