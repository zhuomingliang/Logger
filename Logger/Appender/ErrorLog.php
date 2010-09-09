<?php
/**
 * Logger_Appender_ErrorLog
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/IAppender.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Appender.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Exception.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Message.php';

/**
 * Logger_Appender_ErrorLog
 *
 * Append log messages to the php error_log method
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */
class Logger_Appender_ErrorLog extends Logger_Appender implements Logger_IAppender
{
    /**
     * Log directory
     *
     * @var string
     */
    protected $dir = null;

    /**
     * Log file
     *
     * @var string
     */
    protected $fileName = null;

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

        if (isset($cfg['logging.appender.' . $name . '.logdir'])) {
            $this->setDir($cfg['logging.appender.' . $name . '.logdir']);
        } else {
            $this->setDir('/tmp');
        }

        if (isset($cfg['logging.appender.' . $name . '.file'])) {
            $this->fileName = $cfg['logging.appender.' . $name . '.file'];
        } else {
            $this->fileName = 'logger.log';
        }
    }

    /**
     * Set the log directory for log files to be written to.
     *
     * @param string $dir Path on the file system to write the log files
     *
     * @return void
     */
    public function setDir($dir)
    {
        if ($dir === null) {
            throw new Logger_Exception('Log dir can not be null.');
        }

        $this->dir = $dir;
    }

    /**
     * Get the log directory this writes to
     *
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * Get the filename to log entries to.
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set the filename to log entries to.
     *
     * @param string $fileName The filenam
     *
     * @return void
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Get the log file this will write to
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->getDir() . '/' . $this->getFileName();
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
        error_log($this->getLayout()->format($message), 3, $this->getFilePath());
    }
}

?>