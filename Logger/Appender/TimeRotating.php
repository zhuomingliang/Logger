<?php
/**
 * Logger_Appender_BaseRotating
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/IAppender.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Appender/BaseRotating.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Message.php';

/**
 * Logger_Appender_BaseRotating
 *
 * Base class for files based appenders which rotate filenames.
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */
class Logger_Appender_TimeRotating extends Logger_Appender_BaseRotating implements Logger_IAppender
{
    /**
     * Default interval for roll over
     *
     * @var integer
     */
    protected $interval = 5;

    /**
     * Time modifier
     *
     * Possible Values:
     * m = Minutes
     * h = Hours
     * d = days
     *
     * @var string
     */
    protected $modifier = 'm';

    /**
     * Constructor
     *
     * @param string $name Name of the appender
     * @param array  $cfg  The config
     *
     * @return void
     */
    public function __construct($name, array $cfg)
    {
        parent::__construct($name, $cfg);
        if (isset($cfg['logging.appender.' . $name . '.interval'])) {
            $this->interval = (int) $cfg['logging.appender.' . $name . '.interval'];
        }

        if (isset($cfg['logging.appender.' . $name . '.modifier'])) {
            $this->modifier = strtolower($cfg['logging.appender.' . $name . '.modifier']);
        }
    }

    /**
     * Determine if we need to rollover to a new filename
     *
     * @param Logger_Message $message The logger message
     *
     * @return bool
     */
    public function shouldRollover(Logger_Message $message)
    {
        //Let's say false to save the call overhead to doRollover()
        return true;
    }

    /**
     * Perform the rollover, well, actually do nothing, as error_log handles the file streams
     *
     * @return void
     */
    public function doRollover()
    {
        return;
    }

    /**
     * Append a suffix to the specified filename
     *
     * @return string
     */
    public function getFileName()
    {
        $t = time();

        switch ($this->modifier) {
            case 'd':
                $t = $t - ($t % (60 * 60 * 24 * $this->interval));
                break;
            case 'h':
                $t = $t - ($t % (60 * 60 * $this->interval));
                break;
            case 'm':
            default:
                $t = $t - ($t % (60 * $this->interval));
                break;
        }

        return parent::getFileName() . date('-Ymd.His', $t);
    }
}

?>