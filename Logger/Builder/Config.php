<?php
/**
 * Logger_Builder
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Category.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/IBuilder.php';

/**
 * Logger_Builder
 *
 * Build up a set of loggers based on a config
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */
class Logger_Builder_Config implements Logger_IBuilder
{
    /**
     * The logging config
     *
     * @param array
     */
    protected $cfg = null;

    /**
     * List of appenders already built
     *
     * @param array
     */
    protected $appenders = array();

    /**
     * Constructor
     *
     * @param array $cfg The logging config
     *
     * @return void
     */
    public function __construct(array $cfg)
    {
        $this->cfg = $cfg;
    }

    /**
     * Shut down the appenders
     *
     * @return void
     */
    public function shutdown()
    {
        foreach ($this->appenders as $a) {
            try {
                $a->flush();
                $a->close();
            } catch (Exception $e) {
                //Ignore errors
            }
        }
    }

    /**
     * Prebuild a set of loggers from the config
     *
     * @param array &$loggers Holder for the loggers as they are built
     *
     * @return array
     */
    public function build(array &$loggers)
    {
        $loggers['root'] = $this->buildLogger('root', $loggers);

        $loggerConfig = $this->cfg;
        ksort($loggerConfig, SORT_STRING);

        foreach ($loggerConfig as $name => $logConfig) {

            if (substr($name, 0, 15) !== 'logging.logger.') {
                continue;
            }
            $name = substr($name, strlen('logging.logger.'));
            $loggers[$name] = $this->buildLogger($name, $loggers);
        }

        return;
    }

    /**
     * Build a logger for the given name
     *
     * @param string $name     The logger name
     * @param array  &$loggers Holds the loggers as they are built
     *
     * @return Logger_Category
     */
    public function buildLogger($name, array &$loggers)
    {
        $loggerConfig = null;

        if ($name === 'root') {
            if (!isset($this->cfg['logging.rootLogger'])) {
                throw new Logger_Exception('Root logger is required.');
            }
            $loggerConfig = $this->cfg['logging.rootLogger'];
        } elseif (isset($this->cfg['logging.logger.' . $name])) {
            $loggerConfig = $this->cfg['logging.logger.' . $name];
        }

        $level = null;
        $apps  = null;

        if ($loggerConfig !== null) {
            list($level, $apps) = explode(',', $loggerConfig, 2);
            $level = Logger_Category::$textToLevel[$level];
        }

        $logger = $this->makeLogger($name, $level);

        if (isset($this->cfg['logging.' . $name . '.propagate'])) {
            $logger->setPropagate($this->cfg['logging.' . $name . '.propagate']);
        }

        if ($apps !== null) {
            $apps = explode(',', trim($apps));

            if (count($apps) > 0) {
                foreach ($apps as $app) {
                    $app = trim($app);

                    if (!isset($this->appenders[$app])) {
                        $this->appenders[$app] = $this->buildAppender($app);
                    }

                    $logger->addAppender($this->appenders[$app]);
                }
            }
        }

        $parent = null;
        if ($name !== 'root') {
            $parent = $this->findParentLogger($name, $loggers);
            $logger->setParent($loggers[$parent]);
        }

        return $logger;
    }

    /**
     * Make a new Logger instance
     *
     * @param string  $name  The name of the logger
     * @param integer $level The level for the logger
     *
     * @return Logger_Category
     */
    protected function makeLogger($name, $level)
    {
        return new Logger_Category($name, $level);
    }

    /**
     * Build an appender based on the config
     *
     * @param string $name The name of the appender to build
     *
     * @return void
     */
    protected function buildAppender($name)
    {
        if (!isset($this->cfg['logging.appender.' . $name])) {
            throw new Logger_Exception('Could not find appender: ' . $name);
        }

        $class = $this->cfg['logging.appender.' . $name];

        if (($class === null) || (strlen($class) <= 0)) {
            throw new Logger_Exception('Could not find appender: ' . $name);
        }

        loadClass($class);
        $instance = new $class($name, $this->cfg);
        $appender = $instance;

        return $appender;
    }

    /**
     * Finds the parent of a given logger
     *
     * @param string $name     The name of the logger to search for
     * @param array  &$loggers Holds the loggers already built
     *
     * @return string
     */
    public function findParentLogger($name, array &$loggers)
    {
        $logger_keys = array_keys($loggers);
        rsort($logger_keys, SORT_STRING);

        foreach ($logger_keys as $log) {
            if (strlen($log) < strlen($name)) {
                if ((substr($name, 0, strlen($log)) == $log) && ($name !== 'root')) {
                    return $log;
                }
            }
        }
        return 'root';
    }
}

?>