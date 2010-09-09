<?php
/**
 * Logger_Builder_Simple
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Category.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/IBuilder.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Appender/ErrorLog.php';

/**
 * Logger_Builder_Simple
 *
 * Build up a simple logger set
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */
class Logger_Builder_Simple implements Logger_IBuilder
{
    /**
     * Prebuild a set of loggers
     *
     * @param array &$loggers Holds the loggers as they are built
     *
     * @return array
     */
    public function build(array &$loggers)
    {
        return array('root' => $this->buildLogger('root', $loggers));
    }

    /**
     * Build an individual logger
     *
     * @param string $name     The class name for the logger
     * @param array  &$loggers Holds the loggers as they are built
     *
     * @return Logger_Category
     */
    public function buildLogger($name, array &$loggers)
    {
        $lc = new Logger_Category($name);
        $lc->addAppender(new Logger_Appender_ErrorLog('A1', array()));
        if (($name !== 'root') && isset($loggers['root'])) {
            $lc->setParent($loggers['root']);
        }

        return $lc;
    }

    /**
     * Shutdown the appenders.
     *
     * @return void
     */
    public function shutdown()
    {
        //Do Nothing
    }
}

?>